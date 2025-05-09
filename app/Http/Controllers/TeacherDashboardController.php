<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Classes;

class TeacherDashboardController extends Controller
{
    public function getAdvisoryClassInfo()
    {
        $teacher = Auth::user();
        
        $advisoryClass = Classes::where('adviser_id', $teacher->id)
            ->withCount(['students', 'subjects'])
            ->first();
        
        return response()->json([
            'advisoryClass' => $advisoryClass ? [
                'name' => $this->formatClassName($advisoryClass),
                'student_count' => $advisoryClass->students_count,
                'subject_count' => $advisoryClass->subjects_count
            ] : null
        ]);
    }
    
    public function getSubjects()
    {
        $teacher = Auth::user();
        
        // Get subjects from the advisory class
        $advisoryClass = Classes::where('adviser_id', $teacher->id)
            ->with(['subjects'])
            ->first();
            
        if (!$advisoryClass) {
            return response()->json(['subjects' => []]);
        }
        
        return response()->json([
            'subjects' => $advisoryClass->subjects->map(function($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name
                ];
            })
        ]);
    }
    
    public function getPerformanceData(Request $request)
    {
        $teacher = Auth::user();
        
        // Get the advisory class
        $advisoryClass = Classes::where('adviser_id', $teacher->id)
            ->first();
            
        if (!$advisoryClass) {
            return response()->json([
                'gradeDistribution' => [0, 0, 0, 0, 0],
                'lowGradesBySubject' => ['labels' => [], 'values' => []],
                'studentGrades' => []
            ]);
        }
        
        // Build base query for grades in this class
        $gradesQuery = Grade::whereHas('student.classStudents', function($query) use ($advisoryClass) {
                $query->where('class_id', $advisoryClass->id);
            })
            ->whereHas('subject', function($query) use ($advisoryClass) {
                $query->where('class_id', $advisoryClass->id);
            })
            ->with(['student', 'subject', 'subject.teacher'])
            ->whereNotIn('grade', ['drp', 'trf']);
        
        // Apply subject filter if specified
        if ($request->has('subject_id') && $request->subject_id !== 'all') {
            $gradesQuery->where('subject_id', $request->subject_id);
        }
        
        // Apply period filter if specified
        if ($request->has('period') && $request->period !== 'all') {
            $gradesQuery->where('period', $request->period);
        }
        
        // Get all matching grades
        $grades = $gradesQuery->get();
        
        // Grade distribution data
        $gradeDistribution = [
            '90-100' => 0,
            '85-89' => 0,
            '80-84' => 0,
            '75-79' => 0,
            'Below 75' => 0
        ];
        
        // Low grades by subject data
        $lowGradesBySubject = [];
        
        // Student grades for table
        $studentGrades = [];
        
        foreach ($grades as $grade) {
            $numericGrade = (int)$grade->grade;
            
            // Apply grade range filter if specified
            if ($request->has('grade_range') && $request->grade_range !== 'all') {
                switch ($request->grade_range) {
                    case 'below75':
                        if ($numericGrade >= 75) continue 2;
                        break;
                    case '75-79':
                        if ($numericGrade < 75 || $numericGrade >= 80) continue 2;
                        break;
                    case '80-89':
                        if ($numericGrade < 80 || $numericGrade >= 90) continue 2;
                        break;
                    case '90-100':
                        if ($numericGrade < 90) continue 2;
                        break;
                }
            }
            
            // Count in grade distribution
            if ($numericGrade >= 90) {
                $gradeDistribution['90-100']++;
            } elseif ($numericGrade >= 85) {
                $gradeDistribution['85-89']++;
            } elseif ($numericGrade >= 80) {
                $gradeDistribution['80-84']++;
            } elseif ($numericGrade >= 75) {
                $gradeDistribution['75-79']++;
            } else {
                $gradeDistribution['Below 75']++;
                
                // Track low grades by subject
                if (!isset($lowGradesBySubject[$grade->subject->name])) {
                    $lowGradesBySubject[$grade->subject->name] = 0;
                }
                $lowGradesBySubject[$grade->subject->name]++;
            }
            
            // Add to student grades table
            $studentGrades[] = [
                'student_name' => $grade->student->name,
                'subject_name' => $grade->subject->name,
                'teacher_name' => $grade->subject->teacher->name ?? 'N/A',
                'grade' => $numericGrade,
                'period_type' => ucfirst($grade->period_type),
                'period' => $grade->period
            ];
        }
        
        // Prepare data for the low grades by subject chart
        arsort($lowGradesBySubject);
        $lowGradesChartData = [
            'labels' => array_keys($lowGradesBySubject),
            'values' => array_values($lowGradesBySubject)
        ];
        
        // Sort student grades by grade (lowest first)
        usort($studentGrades, function($a, $b) {
            return $a['grade'] - $b['grade'];
        });
        
        return response()->json([
            'gradeDistribution' => array_values($gradeDistribution),
            'lowGradesBySubject' => $lowGradesChartData,
            'studentGrades' => $studentGrades
        ]);
    }
    
    private function formatClassName($class)
    {
        if ($class->level_type === 'junior') {
            $yearNum = $class->year_level - 6;
            $suffix = 'th';
            if ($yearNum == 1) $suffix = 'st';
            elseif ($yearNum == 2) $suffix = 'nd';
            elseif ($yearNum == 3) $suffix = 'rd';
            
            return "Grade {$yearNum}{$suffix} - {$class->section}";
        } else {
            $strand = $class->strand ? " - {$class->strand}" : '';
            return "Grade {$class->year_level}{$strand} - {$class->section}";
        }
    }
}