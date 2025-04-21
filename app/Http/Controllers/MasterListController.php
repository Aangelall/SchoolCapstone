<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MasterListController extends Controller
{
    public function index(): View
    {
        // Generate school years (e.g., last 5 years and next year)
        $currentYear = date('Y');
        $schoolYears = [];
        for ($i = 2; $i >= 0; $i--) {
            $schoolYears[] = ($currentYear - $i) . '-' . ($currentYear - $i + 1);
        }
        for ($i = 1; $i <= 2; $i++) {
            $schoolYears[] = ($currentYear + $i) . '-' . ($currentYear + $i + 1);
        }

        $data = [
            'schoolYears' => $schoolYears,
            'currentSchoolYear' => $currentYear . '-' . ($currentYear + 1),
            'juniorHighYears' => ['1st Year', '2nd Year', '3rd Year', '4th Year', 'ALL'],
            'seniorHighGrades' => ['G11', 'G12', 'ALL'],
            'seniorHighStrands' => ['STEM', 'GAS', 'ABM', 'HUMSS', 'TVL'],
            'seniorHighSemesters' => ['1st Sem', '2nd Sem'],
            'sections' => ['A', 'B', 'C', 'ALL'],
            'classes' => Classes::with(['students'])->get()
        ];

        return view('admin.masterlist', $data);
    }

    public function getFilteredStudents(Request $request)
    {
        try {
            $query = Classes::with(['students'])
                ->select('classes.*', 'sections.name as section_name')
                ->leftJoin('sections', 'sections.id', '=', 'classes.section')
                ->where('level_type', $request->level_type);

            if ($request->level_type === 'junior') {
                if ($request->year_level !== 'ALL') {
                    $yearLevel = (int) substr($request->year_level, 0, 1);
                    $query->where('year_level', $yearLevel + 6);
                }
            } else {
                if ($request->grade_level !== 'ALL') {
                    $gradeLevel = (int) substr($request->grade_level, 1);
                    $query->where('year_level', $gradeLevel);
                }

                if ($request->strand && $request->strand !== 'ALL') {
                    $query->where('strand', $request->strand);
                }

                if ($request->semester) {
                    $semester = $request->semester === '1st Sem' ? 1 : 2;
                    $query->where('semester', $semester);
                }
            }

            if ($request->section && $request->section !== 'ALL') {
                $query->where('classes.section', $request->section);
            }

            $classes = $query->get();
            $students = collect();

            foreach ($classes as $class) {
                $students = $students->concat($class->students->map(function ($student) use ($class) {
                    return [
                        'id' => $student->id,
                        'lrn' => $student->lrn,
                        'name' => $student->name,
                        'section' => $class->section_name ?? $class->section,
                        'sectionId' => $class->section,
                        'yearLevel' => $class->level_type === 'junior'
                            ? ($class->year_level - 6) . 'st Year'
                            : 'G' . $class->year_level,
                        'strand' => $class->strand ?? null,
                        'semester' => $class->semester ? ($class->semester === 1 ? '1st Sem' : '2nd Sem') : null
                    ];
                }));
            }

            return response()->json([
                'students' => $students->values()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch students: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get grades for a specific student
     */
    public function getStudentGrades($studentId)
    {
        try {
            $student = User::findOrFail($studentId);
            
            // Determine level type from any class
            $studentClass = $student->classStudents()->latest()->first();
            $isJunior = $studentClass && $studentClass->class ? $studentClass->class->level_type === 'junior' : true;
            
            // Get all grades for this student regardless of class
            if ($isJunior) {
                // For junior high, get quarterly grades
                $grades = Grade::where('student_id', $studentId)
                    ->where('period_type', 'quarter')
                    ->get();
                
                $quarters = [
                    'q1' => [],
                    'q2' => [],
                    'q3' => [],
                    'q4' => []
                ];
                
                foreach ($grades as $grade) {
                    $quarterKey = 'q' . $grade->period;
                    $quarters[$quarterKey][] = $grade->grade;
                }
                
                // Calculate average for each quarter
                foreach ($quarters as $quarter => $gradeValues) {
                    if (!empty($gradeValues)) {
                        $quarters[$quarter] = round(array_sum($gradeValues) / count($gradeValues));
                    } else {
                        $quarters[$quarter] = null;
                    }
                }
                
                return response()->json([
                    'quarters' => $quarters,
                    'semesters' => null
                ]);
                
            } else {
                // For senior high, get semester grades
                $grades = Grade::where('student_id', $studentId)
                    ->where('period_type', 'semester')
                    ->get();
                
                $semesters = [
                    'sem1' => [],
                    'sem2' => []
                ];
                
                foreach ($grades as $grade) {
                    $semesterKey = 'sem' . $grade->period;
                    $semesters[$semesterKey][] = $grade->grade;
                }
                
                // Calculate average for each semester
                foreach ($semesters as $semester => $gradeValues) {
                    if (!empty($gradeValues)) {
                        $semesters[$semester] = round(array_sum($gradeValues) / count($gradeValues));
                    } else {
                        $semesters[$semester] = null;
                    }
                }
                
                return response()->json([
                    'quarters' => null,
                    'semesters' => $semesters
                ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch grades: ' . $e->getMessage()
            ], 500);
        }
    }
}
