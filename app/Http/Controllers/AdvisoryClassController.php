<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdvisoryClassController extends Controller
{
    public function index(): View
    {
        if (Auth::user()->role === 'student') {
            return view('errors.access_denied', ['message' => 'Access denied.']);
        }

        $teacher = Auth::user();
        
        // Debug the teacher
        \Log::info('Teacher attempting to access advisory class:', [
            'teacher_id' => $teacher->id,
            'teacher_name' => $teacher->name,
        ]);
        
        // Get current school year or default to current year
        $currentSchoolYear = date('Y');
        
        // Get classes where this teacher is assigned as an adviser
        $advisoryClass = Classes::with(['students', 'subjects.teacher'])
            ->where('adviser_id', $teacher->id)
            ->orderBy('created_at', 'desc')
            ->first();
            
        // Log the found advisory class
        if ($advisoryClass) {
            \Log::info('Advisory class found:', [
                'class_id' => $advisoryClass->id,
                'level_type' => $advisoryClass->level_type,
                'year_level' => $advisoryClass->year_level,
                'section' => $advisoryClass->section,
            ]);
        } else {
            \Log::info('No advisory class found for this teacher');
        }

        return view('teacher.advisoryclass', compact('advisoryClass'));
    }

    public function getStudentGrades($studentId)
    {
        try {
            $teacher = Auth::user();
            $student = User::findOrFail($studentId);

            // Get the advisory class and its subjects
            $advisoryClass = Classes::with(['subjects.teacher'])
                ->where('adviser_id', $teacher->id)
                ->firstOrFail();

            // Get grades for each subject
            $grades = $advisoryClass->subjects->map(function($subject) use ($student, $advisoryClass) {
                $periodType = $advisoryClass->level_type === 'junior' ? 'quarter' : 'semester';
                $totalPeriods = $advisoryClass->level_type === 'junior' ? 4 : 2;
                
                // Get all grades for this subject where they are confirmed
                $subjectGrades = Grade::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->get();
                
                // Create grades array with proper period keys
                $gradesByPeriod = [];
                for ($i = 1; $i <= $totalPeriods; $i++) {
                    $periodGrade = $subjectGrades->where('period', $i)->first();
                    $gradesByPeriod[$periodType . '_' . $i] = $periodGrade ? $periodGrade->grade : 'N/A';
                }

                return [
                    'subject_name' => $subject->name,
                    'teacher_name' => $subject->teacher->name,
                    'grades' => $gradesByPeriod
                ];
            });

            return response()->json([
                'student' => [
                    'name' => $student->name,
                    'lrn' => $student->lrn,
                    'profile_image' => $student->profile_image
                ],
                'grades' => $grades,
                'level_type' => $advisoryClass->level_type
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch grades: ' . $e->getMessage()
            ], 500);
        }
    }


    public function confirmGrades(Request $request)
    {
        try {
            $teacher = Auth::user();
            $advisoryClass = Classes::with(['subjects.teacher', 'students'])
                ->where('adviser_id', $teacher->id)
                ->firstOrFail();
    
            $period = $request->period;
            $periodType = $advisoryClass->level_type === 'junior' ? 'quarter' : 'semester';
    
            // Get all subjects in this class
            $subjects = $advisoryClass->subjects;
            $allSubjectsComplete = true;
    
            foreach ($subjects as $subject) {
                $gradesCount = Grade::where('subject_id', $subject->id)
                    ->where('period', $period)
                    ->where('period_type', $periodType)
                    ->count();
    
                // If not all students have grades, throw an error
                if ($gradesCount !== $advisoryClass->students->count()) {
                    $allSubjectsComplete = false;
                    break;
                }
            }
    
            if (!$allSubjectsComplete) {
                return response()->json([
                    'success' => false,
                    'message' => 'Not all subjects have complete grades for this period'
                ], 400);
            }
    
            // Mark all grades as confirmed for this period
            foreach ($subjects as $subject) {
                Grade::where('subject_id', $subject->id)
                    ->where('period', $period)
                    ->where('period_type', $periodType)
                    ->update(['is_confirmed' => true]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Grades confirmed successfully'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function getSubjectGradeStatus(Request $request)
    {
        $teacher = Auth::user();
        $advisoryClass = Classes::with(['subjects.teacher', 'students'])
            ->where('adviser_id', $teacher->id)
            ->firstOrFail();

        $period = $request->period;
        $periodType = $advisoryClass->level_type === 'junior' ? 'quarter' : 'semester';

        $subjectsStatus = $advisoryClass->subjects->map(function($subject) use ($period, $periodType, $advisoryClass) {
            // Count how many students have grades for this period
            $gradesCount = Grade::where('subject_id', $subject->id)
                ->where('period', $period)
                ->where('period_type', $periodType)
                ->count();

            // Total number of students in the class
            $totalStudents = $advisoryClass->students->count();

            return [
                'subject_name' => $subject->name,
                'teacher_name' => $subject->teacher->name,
                'status' => $gradesCount === $totalStudents ? 'complete' : 'pending',
                'submitted_count' => $gradesCount,
                'total_students' => $totalStudents
            ];
        });

        return response()->json($subjectsStatus);
    }

    /**
     * Check if grades for a specific period have been submitted and confirmed
     */
    public function checkSubmissionStatus(Request $request)
    {
        try {
            $teacher = Auth::user();
            $advisoryClass = Classes::with(['subjects'])
                ->where('adviser_id', $teacher->id)
                ->firstOrFail();

            $period = $request->period;
            $periodType = $request->period_type;

            // Check if grades have been confirmed for this period
            $allConfirmed = true;
            
            foreach ($advisoryClass->subjects as $subject) {
                // Count confirmed grades for this subject in this period
                $confirmedCount = Grade::where('subject_id', $subject->id)
                    ->where('period', $period)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->count();
                
                // If any subject doesn't have confirmed grades, set allConfirmed to false
                if ($confirmedCount === 0) {
                    $allConfirmed = false;
                    break;
                }
            }

            return response()->json([
                'submitted' => $allConfirmed
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'submitted' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get advisory class information
     */
    public function getClassInfo()
    {
        try {
            $teacher = Auth::user();
            $advisoryClass = Classes::where('adviser_id', $teacher->id)->firstOrFail();

            return response()->json([
                'level_type' => $advisoryClass->level_type,
                'year_level' => $advisoryClass->year_level,
                'section' => $advisoryClass->section
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch class info: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get achievers from the advisory class for a specific period
     */
    public function getAchievers(Request $request)
    {
        try {
            $teacher = Auth::user();
            $period = $request->query('period', 1);

            // Get the advisory class
            $advisoryClass = Classes::with(['students', 'subjects'])
                ->where('adviser_id', $teacher->id)
                ->firstOrFail();

            $periodType = $advisoryClass->level_type === 'junior' ? 'quarter' : 'semester';

            // Get all students with their grades
            $achievers = [];
            foreach ($advisoryClass->students as $student) {
                // Get confirmed grades for this student in this period
                $grades = Grade::where('student_id', $student->id)
                    ->where('period', $period)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->get();

                // Only process if student has grades for all subjects
                if ($grades->count() === $advisoryClass->subjects->count()) {
                    // Calculate average grade
                    $totalGrade = $grades->sum('grade');
                    $averageGrade = round($totalGrade / $grades->count(), 2);

                    // Only include students with average grade >= 90
                    if ($averageGrade >= 90) {
                        // Determine honor category
                        $honorCategory = '';
                        if ($averageGrade >= 98) {
                            $honorCategory = 'Highest Honor';
                        } elseif ($averageGrade >= 95) {
                            $honorCategory = 'High Honor';
                        } else {
                            $honorCategory = 'With Honor';
                        }

                        $achievers[] = [
                            'name' => $student->last_name . ', ' . $student->first_name . 
                                    ($student->middle_name ? ' ' . $student->middle_name : ''),
                            'lrn' => $student->lrn,
                            'average_grade' => $averageGrade,
                            'honor_category' => $honorCategory
                        ];
                    }
                }
            }

            // Sort achievers by honor category, then by grade, then by name
            usort($achievers, function($a, $b) {
                // Define honor category priority (higher number = higher priority)
                $honorPriority = [
                    'Highest Honor' => 3,
                    'High Honor' => 2,
                    'With Honor' => 1
                ];

                // First compare by honor category
                $honorComparison = $honorPriority[$b['honor_category']] <=> $honorPriority[$a['honor_category']];
                
                if ($honorComparison !== 0) {
                    return $honorComparison;
                }

                // If same honor category, compare by grade
                $gradeComparison = $b['average_grade'] <=> $a['average_grade'];
                
                // If grades are equal, compare by name
                if ($gradeComparison === 0) {
                    return $a['name'] <=> $b['name'];
                }
                
                return $gradeComparison;
            });

            return response()->json($achievers);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch achievers: ' . $e->getMessage()
            ], 500);
        }
    }
}
