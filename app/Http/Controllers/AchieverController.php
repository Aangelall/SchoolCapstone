<?php
// final
namespace App\Http\Controllers;

use App\Models\Achiever;
use App\Models\Classes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchieverController extends Controller
{
    public function getAchieversByYear(Request $request)
    {
        try {
            $teacher = Auth::user();
            $levelType = $request->level_type;
            $yearLevel = $request->year_level;
            $period = $request->period;
            $periodType = $levelType === 'junior' ? 'quarter' : 'semester';

            // Convert year level to the appropriate format for querying
            $yearLevelNumber = $this->convertYearLevelToNumber($yearLevel, $levelType);

            // Fetch the advisory class of the logged-in teacher
            $advisoryClass = Classes::where('adviser_id', $teacher->id)
                ->where('level_type', $levelType)
                ->where('year_level', $yearLevelNumber)
                ->first();

            if (!$advisoryClass) {
                return response()->json([]);
            }

            // Fetch students in the advisory class with their grades
            $students = $advisoryClass->students()->with(['grades' => function ($query) use ($period, $periodType) {
                $query->where('is_confirmed', true)
                      ->where('period', $period)
                      ->where('period_type', $periodType);
            }])->get();

            // Process achievers
            $achievers = [];
            foreach ($students as $student) {
                // Calculate average grade for the student for the selected period
                $averageGrade = $this->calculateAverageGrade($student->grades);

                // Round the average grade to the nearest whole number
                $roundedGrade = round($averageGrade);

                // Determine if the student is an achiever based on the rounded grade
                if ($roundedGrade >= 90) {
                    $achievers[] = [
                        'name' => $student->name,
                        'lrn' => $student->lrn,
                        'average_grade' => $roundedGrade,
                        'section' => $advisoryClass->section,
                    ];
                }
            }

            // Sort achievers by average grade in descending order
            usort($achievers, function($a, $b) {
                return $b['average_grade'] - $a['average_grade'];
            });

            return response()->json($achievers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch achievers: ' . $e->getMessage()
            ], 500);
        }
    }

    private function convertYearLevelToNumber($yearLevel, $levelType)
    {
        if ($levelType === 'junior') {
            return (int) substr($yearLevel, 0, 1) + 6;
        } else {
            return (int) substr($yearLevel, 1);
        }
    }

    private function calculateAverageGrade($grades)
    {
        if ($grades->isEmpty()) {
            return 0;
        }

        $total = 0;
        $count = 0;

        foreach ($grades as $grade) {
            $total += $grade->grade;
            $count++;
        }

        return $total / $count;
    }

    public function index()
    {
        return view('teacher.achievers');
    }
}
