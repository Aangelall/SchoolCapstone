<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Grade;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use League\Csv\Reader;

class StudentController extends Controller
{
    public function exportSelectedToCSV(Request $request)
    {
        $selectedIds = $request->input('selected_students', []);

        $students = User::whereIn('id', $selectedIds)
            ->where('role', 'student')
            ->get(['lrn', 'first_name', 'last_name', 'birthday']);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=students.csv',
        ];

        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['LRN', 'First Name', 'Last Name', 'Birthday']);

            // Add student data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->lrn,
                    $student->first_name,
                    $student->last_name,
                    $student->birthday->format('Y-m-d')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function getGradeClass($grade)
    {
        if (!$grade) return 'grade-pending';
        if ($grade >= 90) return 'grade-outstanding';
        if ($grade >= 85) return 'grade-very-good';
        if ($grade >= 80) return 'grade-good';
        if ($grade >= 75) return 'grade-passed';
        return 'grade-failed';
    }

    public function showGrades()
    {
        $student = Auth::user();

        // Get all classes for the student, ordered by school year
        $studentClasses = Classes::whereHas('students', function($query) use ($student) {
            $query->where('users.id', $student->id);
        })
        ->with(['adviser', 'subjects.teacher', 'subjects.grades' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }, 'classStudents' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->orderBy('school_year', 'desc')
        ->get();

        $gradeHistory = [];

        foreach ($studentClasses as $class) {
            $classStudent = $class->classStudents->first();

            $grades = $class->subjects->map(function($subject) use ($student, $class) {
                $periodType = $class->level_type === 'junior' ? 'quarter' : 'semester';
                $periods = $class->level_type === 'junior' ? 4 : 2;

                $allGrades = $subject->grades
                    ->where('student_id', $student->id)
                    ->where('period_type', $periodType)
                    ->where('is_confirmed', true)
                    ->keyBy('period');

                $gradesByPeriod = [];
                for ($i = 1; $i <= $periods; $i++) {
                    $grade = $allGrades->get($i);
                    $gradesByPeriod[] = $grade ? $grade->grade : null;
                }

                // Use teacher_name from grades if available, otherwise use current teacher
                $teacherName = $allGrades->first()?->teacher_name ??
                    ($subject->teacher ? $subject->teacher->first_name . ' ' . $subject->teacher->last_name : 'N/A');

                return [
                    'subject_name' => $subject->name,
                    'teacher_name' => $teacherName,
                    'grades' => $gradesByPeriod,
                    'period_type' => $periodType
                ];
            });

            $gradeHistory[] = [
                'school_year' => $class->school_year,
                'level_type' => $class->level_type,
                'year_level' => $class->year_level,
                'section' => $class->section,
                'strand' => $class->strand,
                'semester' => $class->semester,
                'adviser_name' => $classStudent?->adviser_name ??
                    ($class->adviser ? $class->adviser->first_name . ' ' . $class->adviser->last_name : 'N/A'),
                'grades' => $grades
            ];
        }

        return view('student.grades', [
            'gradeHistory' => $gradeHistory,
            'student' => $student,
            'currentClass' => $studentClasses->first(),
            'getGradeClass' => [$this, 'getGradeClass']
        ]);
    }

    

    public function destroy(User $user)
    {
    try {
        // Verify this is actually a student
        if ($user->role !== 'student') {
            return response()->json(['success' => false, 'message' => 'Only students can be deleted'], 400);
        }

        // Delete related records first if needed
        $user->grades()->delete();
        $user->classStudents()->delete();

        // Then delete the user
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Student deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Failed to delete student: ' . $e->getMessage()], 500);
    }
    }

    public function checkLRN($lrn)
    {
        try {
            $exists = User::where('lrn', $lrn)
                         ->where('role', 'student')
                         ->exists();
            return response()->json([
                'success' => true,
                'exists' => $exists,
                'message' => $exists ? 'Student already exists' : 'New student'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking LRN: ' . $e->getMessage()
            ], 500);
        }
    }

    public function processStudentCSV(Request $request)
    {
        try {
            if (!$request->hasFile('student_list')) {
                return response()->json(['success' => false, 'message' => 'No file uploaded']);
            }

            $file = $request->file('student_list');
            $newStudents = [];
            
            // Read CSV file
            $handle = fopen($file->getPathname(), 'r');
            
            // Skip header row
            fgetcsv($handle);
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 4) {
                    $firstName = trim($row[0]);
                    $lastName = trim($row[1]);
                    $lrn = trim($row[2]);
                    $birthdate = trim($row[3]);
                    
                    // Convert birthdate to password format (ddmmyy)
                    $birthdateObj = Carbon::parse($birthdate);
                    $password = $birthdateObj->format('dmy');
                    
                    // Create new user with student role
                    $user = User::create([
                        'name' => "$firstName $lastName",
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'lrn' => $lrn,
                        'birthday' => $birthdate,
                        'username' => $lrn,
                        'password' => Hash::make($password),
                        'role' => 'student'
                    ]);
                    
                    $newStudents[] = $user->id;
                }
            }
            
            fclose($handle);
            
            return response()->json([
                'success' => true,
                'new_students' => $newStudents,
                'message' => 'Students processed successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing CSV: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check for duplicate LRNs in a CSV file
     */
    public function checkDuplicateLRNs(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);

            // Get the CSV file
            $csvFile = $request->file('csv_file');
            
            // Read CSV file
            $csv = Reader::createFromPath($csvFile->getPathname());
            $csv->setHeaderOffset(0);
            
            // Try to determine the delimiter automatically
            $firstLine = file($csvFile->getPathname())[0];
            $delimiter = false;
            
            // Check if file contains tabs, commas, or semicolons
            if (strpos($firstLine, "\t") !== false) {
                $delimiter = "\t";
            } elseif (strpos($firstLine, ",") !== false) {
                $delimiter = ",";
            } elseif (strpos($firstLine, ";") !== false) {
                $delimiter = ";";
            }
            
            if ($delimiter) {
                $csv->setDelimiter($delimiter);
            } else {
                $csv->setDelimiter(","); // Default to comma
            }
            
            // Get records
            $records = $csv->getRecords();
            
            // Get original headers (preserve case)
            $originalHeaders = $csv->getHeader();
            // Get lowercased headers for comparison
            $lowerHeaders = array_map('strtolower', array_map('trim', $originalHeaders));
            
            // Map common header variations (keep variations lowercase for comparison)
            $headerMap = [
                'lrn' => ['lrn', 'learner reference number', 'learner_reference_number', 'student id', 'student_id'],
                'first_name' => ['first name', 'first_name', 'firstname', 'given name', 'given_name'],
                'last_name' => ['last name', 'last_name', 'lastname', 'surname', 'family name', 'family_name'],
            ];

            // Find the actual column header strings from the CSV
            $columnMap = [];
            foreach ($headerMap as $field => $variations) {
                foreach ($variations as $variant) { // $variant is lowercase from the map
                    $foundIndex = array_search($variant, $lowerHeaders); // Find index in lowercased headers
                    if ($foundIndex !== false) {
                        // Use the original header string from that index
                        $columnMap[$field] = $originalHeaders[$foundIndex];
                        break;
                    }
                }
            }
            
            if (empty($columnMap) || !isset($columnMap['lrn'])) {
                return response()->json([
                    'error' => 'Could not find LRN column in CSV. Please check your CSV format.',
                    'duplicates' => []
                ], 400);
            }
            
            // Extract all LRNs from the CSV
            $lrns = [];
            foreach ($records as $record) {
                $lrn = trim($record[$columnMap['lrn']] ?? '');
                if (!empty($lrn)) {
                    $lrns[] = $lrn;
                }
            }
            
            if (empty($lrns)) {
                return response()->json([
                    'message' => 'No valid LRNs found in the CSV file.',
                    'duplicates' => []
                ]);
            }
            
            // Find existing students with these LRNs
            $existingStudents = User::whereIn('lrn', $lrns)
                ->where('role', 'student')
                ->with(['classStudents' => function($query) {
                    $query->with('class');
                    $query->orderBy('created_at', 'desc');
                }])
                ->get();
            
            if ($existingStudents->isEmpty()) {
                return response()->json([
                    'message' => 'No duplicate LRNs found.',
                    'duplicates' => []
                ]);
            }
            
            // Format the response with details about duplicates
            $duplicates = $existingStudents->map(function($student) {
                $currentClass = $student->classStudents->first();
                return [
                    'id' => $student->id,
                    'lrn' => $student->lrn,
                    'name' => $student->name,
                    'current_class' => $currentClass 
                        ? 'Grade ' . $currentClass->class->year_level . ' - ' . 
                          ($currentClass->class->sectionDetails ? $currentClass->class->sectionDetails->name : $currentClass->class->section)
                        : null,
                ];
            });
            
            return response()->json([
                'message' => 'Duplicate LRNs found.',
                'duplicates' => $duplicates
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error checking duplicate LRNs: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Failed to check for duplicate LRNs: ' . $e->getMessage(),
                'duplicates' => []
            ], 500);
        }
    }
}




