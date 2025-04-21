<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Grade;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        // Debug the teacher
        \Log::info('Teacher attempting to access subjects:', [
            'teacher_id' => $teacher->id,
            'teacher_name' => $teacher->name,
        ]);
        
        // Get subjects where this teacher is assigned
        $subjects = Subject::with(['class.students'])
            ->where('teacher_id', $teacher->id)
            ->get();
            
        // Log the found subjects
        \Log::info('Subjects found for teacher:', [
            'count' => $subjects->count(),
            'subject_ids' => $subjects->pluck('id')->toArray(),
            'subject_names' => $subjects->pluck('name')->toArray(),
        ]);

        return view('teacher.subject', compact('subjects'));
    }

    public function getSubjects(Request $request)
    {
        $teacher = Auth::user();

        $subjects = Subject::with(['class.students' => function($query) {
                $query->select('users.id', 'users.name', 'users.lrn')
                      ->orderBy('users.name');
            }, 'class.sectionDetails', 'class.adviser', 'teacher', 'group'])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->map(function($subject) {
                $subject->students = $subject->class->students->map(function($student) use ($subject) {
                    $grades = Grade::where('student_id', $student->id)
                                 ->where('subject_id', $subject->id)
                                 ->get()
                                 ->mapWithKeys(function($grade) {
                                     return ["{$grade->period_type}_{$grade->period}" => $grade->grade];
                                 });

                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'lrn' => $student->lrn,
                        'grades' => $grades,
                        'level_type' => $subject->class->level_type
                    ];
                });

                // Check if previous period grades are confirmed
                $currentPeriod = request('period', 1);
                $periodType = $subject->class->level_type === 'junior' ? 'quarter' : 'semester';

                if ($currentPeriod > 1) {
                    $previousPeriod = $currentPeriod - 1;
                    $previousGradesConfirmed = Grade::where('subject_id', $subject->id)
                        ->where('period', $previousPeriod)
                        ->where('period_type', $periodType)
                        ->where('is_confirmed', true)
                        ->exists();

                    $subject->previous_period_confirmed = $previousGradesConfirmed;
                } else {
                    $subject->previous_period_confirmed = true;
                }

                // Format year level based on level type
                $yearLevelDisplay = '';
                if ($subject->class->level_type === 'junior') {
                    $yearNum = $subject->class->year_level - 6;
                    $suffix = 'th';
                    if ($yearNum == 1) $suffix = 'st';
                    elseif ($yearNum == 2) $suffix = 'nd';
                    elseif ($yearNum == 3) $suffix = 'rd';
                    $yearLevelDisplay = $yearNum . $suffix . ' Year';
                } else {
                    $yearLevelDisplay = 'Grade ' . $subject->class->year_level;
                }
                
                // Build display name with semester for senior high
                $displayName = $subject->name . ' - ' . $yearLevelDisplay . ' - ' . 
                    ($subject->class->sectionDetails ? $subject->class->sectionDetails->name : $subject->class->section);
                
                // Add semester information for senior high
                if ($subject->class->level_type === 'senior' && $subject->group) {
                    $semester = $subject->group->semester;
                    $displayName .= ' - ' . ($semester == 1 ? '1st sem' : '2nd sem');
                }
                
                $subject->display_name = $displayName;
                $subject->level_type = $subject->class->level_type;
                $subject->adviser_name = $subject->class->adviser ? $subject->class->adviser->name : null;
                $subject->teacher_name = $subject->teacher ? $subject->teacher->name : null;
                unset($subject->class);
                return $subject;
            });

        return response()->json($subjects);
    }

    public function updateGrades(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.grade' => 'required|numeric|min:0|max:100',
            'grades.*.period' => 'required|integer|min:1|max:4',
            'grades.*.period_type' => 'required|in:quarter,semester'
        ]);

        try {
            DB::beginTransaction();

            $subject = Subject::with(['teacher', 'class'])->findOrFail($request->subject_id);

            // Verify the teacher owns this subject
            if ($subject->teacher_id !== Auth::id()) {
                throw new \Exception('Unauthorized access to subject');
            }

            // Check if grades for this period are already confirmed
            $periodConfirmed = Grade::where('subject_id', $request->subject_id)
                ->where('period', $request->grades[0]['period'])
                ->where('period_type', $request->grades[0]['period_type'])
                ->where('is_confirmed', true)
                ->exists();

            if ($periodConfirmed) {
                throw new \Exception('Cannot modify grades. These grades have been confirmed by the adviser.');
            }

            $teacherName = $subject->teacher ?
                $subject->teacher->first_name . ' ' . $subject->teacher->last_name : 'N/A';

            foreach ($request->grades as $gradeData) {
                // Convert grade to integer using floor
                $grade = is_numeric($gradeData['grade']) ? 
                    floor($gradeData['grade']) : 
                    $gradeData['grade']; // Keep original value for special cases like 'drp' or 'trf'

                Grade::updateOrCreate(
                    [
                        'student_id' => $gradeData['student_id'],
                        'subject_id' => $request->subject_id,
                        'period' => $gradeData['period'],
                        'period_type' => $gradeData['period_type']
                    ],
                    [
                        'grade' => $grade,
                        'teacher_name' => $teacherName,
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();

            return response()->json([
                'message' => 'Grades updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 403);
        }
    }

    /**
     * Get subjects by group (grade level, strand, semester)
     */
    public function getByGroup(Request $request)
    {
        $validated = $request->validate([
            'grade' => 'required|in:11,12',
            'strand' => 'required|string',
            'semester' => 'required|in:1,2'
        ]);
        
        // First find the matching subject group
        $subjectGroup = \App\Models\SubjectGroup::firstOrCreate([
            'grade_level' => $validated['grade'],
            'strand' => $validated['strand'],
            'semester' => $validated['semester']
        ]);
        
        // Get all subjects for this group
        $subjects = $subjectGroup->subjects()->with('teacher')->get();
        
        return response()->json($subjects);
    }

    /**
     * Save a subject to a subject group
     */
    public function saveToGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|integer|min:7|max:12',
            'teacher_id' => 'nullable|exists:users,id',
            'strand' => 'required_if:grade_level,11,12|string|max:255|nullable',
            'semester' => 'required_if:grade_level,11,12|in:1,2|nullable',
        ]);
        
        // Only create subject groups for senior high (grades 11-12)
        if ($validated['grade_level'] >= 11) {
            // First find or create the subject group
            $subjectGroup = \App\Models\SubjectGroup::firstOrCreate([
                'grade_level' => $validated['grade_level'],
                'strand' => $validated['strand'],
                'semester' => $validated['semester']
            ]);
            
            // Check if the subject already exists in this group
            $existingSubject = \App\Models\Subject::where('name', $validated['name'])
                ->where('subject_group_id', $subjectGroup->id)
                ->first();
                
            if ($existingSubject) {
                // Update the existing subject's teacher if provided
                if (isset($validated['teacher_id'])) {
                    $existingSubject->teacher_id = $validated['teacher_id'];
                    $existingSubject->save();
                }
                
                return response()->json([
                    'message' => 'Subject already exists in this group',
                    'subject' => $existingSubject
                ]);
            }
            
            // Create the new subject in this group
            $subject = new \App\Models\Subject([
                'name' => $validated['name'],
                'teacher_id' => $validated['teacher_id'] ?? null
            ]);
            
            $subjectGroup->subjects()->save($subject);
        } else {
            // For junior high, create subject without a group
            $subject = \App\Models\Subject::create([
                'name' => $validated['name'],
                'teacher_id' => $validated['teacher_id'] ?? null
            ]);
        }
        
        return response()->json([
            'message' => 'Subject saved successfully',
            'subject' => $subject->load('teacher')
        ], 201);
    }

    /**
     * Get students for a specific subject with their grades
     */
    public function getStudentsForSubject(Subject $subject)
    {
        try {
            // Check if the authenticated teacher owns this subject
            $teacher = Auth::user();
            if ($subject->teacher_id !== $teacher->id) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            // Load the subject with class, students and their grades
            $subject->load([
                'class.students' => function($query) {
                    $query->select('users.id', 'users.name', 'users.lrn')
                          ->orderBy('users.name');
                }, 
                'class.adviser',
                'class.sectionDetails'
            ]);

            $levelType = $subject->class->level_type;
            $currentPeriod = request('period', 1);
            $periodType = $levelType === 'junior' ? 'quarter' : 'semester';

            // Check if grades for this period are confirmed
            $gradesConfirmed = Grade::where('subject_id', $subject->id)
                ->where('period', $currentPeriod)
                ->where('period_type', $periodType)
                ->where('is_confirmed', true)
                ->exists();

            $students = $subject->class->students->map(function($student) use ($subject, $levelType) {
                // Get grades for this student in this subject
                $grades = Grade::where('student_id', $student->id)
                            ->where('subject_id', $subject->id)
                            ->get()
                            ->mapWithKeys(function($grade) {
                                return ["{$grade->period_type}_{$grade->period}" => $grade->grade];
                            });

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'lrn' => $student->lrn,
                    'grades' => $grades
                ];
            });

            // Format response data
            $result = [
                'id' => $subject->id,
                'name' => $subject->name,
                'level_type' => $levelType,
                'students' => $students,
                'adviser_name' => $subject->class->adviser ? $subject->class->adviser->name : null,
                'section' => $subject->class->sectionDetails ? $subject->class->sectionDetails->name : $subject->class->section,
                'grades_confirmed' => $gradesConfirmed
            ];

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
