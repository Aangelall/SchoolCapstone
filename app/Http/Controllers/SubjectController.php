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
                
                // Add a flag to indicate if the current teacher is the adviser
                $subject->is_adviser = ($subject->class->adviser_id === Auth::id());
                
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
            'grades.*.grade' => 'required',
            'grades.*.period' => 'required|integer|min:1|max:4',
            'grades.*.period_type' => 'required|in:quarter,semester'
        ]);
    
        try {
            DB::beginTransaction();
    
            $subject = Subject::with(['teacher', 'class'])->findOrFail($request->subject_id);
    
            // Verify the teacher owns this subject (only check needed)
            if ($subject->teacher_id !== Auth::id()) {
                throw new \Exception('Unauthorized access to subject');
            }
            
            $currentPeriod = $request->grades[0]['period'] ?? 1;
            $periodType = $request->grades[0]['period_type'] ?? 'quarter';
            
            // Check if grades are already confirmed for this period
            $gradesConfirmed = Grade::where('subject_id', $subject->id)
                ->where('period', $currentPeriod)
                ->where('period_type', $periodType)
                ->where('is_confirmed', true)
                ->exists();
                
            if ($gradesConfirmed) {
                throw new \Exception('Grades for this period have already been confirmed by the adviser and cannot be modified');
            }
            
            // Check if previous period's grades are confirmed (if not first period)
            if ($currentPeriod > 1) {
                $previousPeriod = $currentPeriod - 1;
                $previousGradesConfirmed = Grade::where('subject_id', $subject->id)
                    ->where('period', $previousPeriod)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->exists();
                    
                if (!$previousGradesConfirmed) {
                    throw new \Exception("Previous {$periodType} grades must be confirmed by the adviser first");
                }
            }
    
            $teacherName = $subject->teacher ?
                $subject->teacher->first_name . ' ' . $subject->teacher->last_name : 'N/A';
    
            foreach ($request->grades as $gradeData) {
                // Handle special grade values (drp, trf)
                $grade = strtolower($gradeData['grade']);
                if (in_array($grade, ['drp', 'trf'])) {
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
                            'is_confirmed' => false, // Only advisory teachers can confirm
                            'updated_at' => now()
                        ]
                    );
                    continue;
                }
    
                // Process numeric grades
                $grade = floor($gradeData['grade']);
                if ($grade < 70) $grade = 70;
                if ($grade > 100) $grade = 100;
    
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
                        'is_confirmed' => false, // Only advisory teachers can confirm
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
                
            // Check if previous period grades are confirmed (if not first period)
            $previousPeriodConfirmed = true;
            $previousPeriodMessage = '';
            
            if ($currentPeriod > 1) {
                $previousPeriod = $currentPeriod - 1;
                $previousPeriodConfirmed = Grade::where('subject_id', $subject->id)
                    ->where('period', $previousPeriod)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->exists();
                    
                if (!$previousPeriodConfirmed) {
                    $periodLabel = $periodType === 'quarter' ? 'Quarter' : 'Semester';
                    $previousPeriodMessage = "Previous {$periodLabel} grades must be confirmed by the adviser first";
                }
            }

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
                'grades_confirmed' => $gradesConfirmed,
                'previous_period_confirmed' => $previousPeriodConfirmed,
                'previous_period_message' => $previousPeriodMessage,
                'is_adviser' => ($subject->class->adviser_id === Auth::id()),
                'semester' => $subject->group->semester ?? null
            ];

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Check if the current teacher is the adviser for a specific subject
     */
    public function checkAdviserRole(Subject $subject)
    {
        try {
            $teacher = Auth::user();
            $subject->load('class');
            
            $isAdviser = ($subject->class->adviser_id === $teacher->id);
            
            return response()->json([
                'is_adviser' => $isAdviser
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}