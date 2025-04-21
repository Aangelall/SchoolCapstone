<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\ClassStudent;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use League\Csv\Reader;
use Illuminate\Support\Facades\Auth;
use App\Models\Section;
use App\Models\Strand;
use App\Models\Teacher;

class SectionSubjectController extends Controller
{
    public function promoteStudents(Request $request)
    {
        try {
            $teacher = Auth::user();

            // Get the advisory class with all necessary relationships
            $advisoryClass = Classes::with([
                'students',
                'subjects.grades',
                'adviser',
                'subjects.teacher'
            ])->where('adviser_id', $teacher->id)
              ->firstOrFail();

            // Check if all grades are confirmed
            $allGradesConfirmed = true;
            $periodType = $advisoryClass->level_type === 'junior' ? 'quarter' : 'semester';
            $totalPeriods = $advisoryClass->level_type === 'junior' ? 4 : 2;

            $failedStudents = [];
            $droppedTransferredStudents = [];
            $canBePromoted = [];

                foreach ($advisoryClass->students as $student) {
                $studentGrades = [];
                $hasDroppedOrTransferred = false;

                foreach ($advisoryClass->subjects as $subject) {
                    // Get all grades for this student in this subject
                    $grades = Grade::where('subject_id', $subject->id)
                        ->where('student_id', $student->id)
                        ->where('period_type', $periodType)
                        ->where('is_confirmed', true)
                        ->get();

                    if ($grades->count() < $totalPeriods) {
                        $allGradesConfirmed = false;
                        break 2;
                    }

                    // Check for dropped or transferred status
                    foreach ($grades as $grade) {
                        if (in_array($grade->grade, ['drp', 'trf'])) {
                            $hasDroppedOrTransferred = true;
                            $droppedTransferredStudents[] = [
                                'name' => $student->last_name . ', ' . $student->first_name,
                                'status' => strtoupper($grade->grade),
                                'subject' => $subject->name
                            ];
                            break 2;
                        }
                        
                        // Add numeric grades for averaging
                        if (is_numeric($grade->grade)) {
                            $studentGrades[] = $grade->grade;
                        }
                    }
                }

                // Skip further checks if student is dropped or transferred
                if ($hasDroppedOrTransferred) {
                    continue;
                }

                // Calculate final rating if we have grades
                if (count($studentGrades) > 0) {
                    $finalRating = round(array_sum($studentGrades) / count($studentGrades));
                    
                    if ($finalRating < 75) {
                        $failedStudents[] = [
                            'name' => $student->last_name . ', ' . $student->first_name,
                            'final_rating' => $finalRating
                        ];
                    } else {
                        $canBePromoted[] = $student->id;
                    }
                }
            }

            if (!$allGradesConfirmed) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot promote students. Some grades are not yet confirmed.'
                ], 400);
            }

            if (empty($canBePromoted)) {
                $message = "No students eligible for promotion.\n";
                if (!empty($droppedTransferredStudents)) {
                    $message .= "\nDropped/Transferred students:\n";
                    foreach ($droppedTransferredStudents as $student) {
                        $message .= "- {$student['name']} ({$student['status']}) in {$student['subject']}\n";
                    }
                }
                if (!empty($failedStudents)) {
                    $message .= "\nStudents with failing grades:\n";
                    foreach ($failedStudents as $student) {
                        $message .= "- {$student['name']} (Final Rating: {$student['final_rating']})\n";
                    }
                }
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            DB::beginTransaction();

            try {
                // Store adviser name in class_students before removing
                $adviserName = $advisoryClass->adviser ?
                    $advisoryClass->adviser->first_name . ' ' . $advisoryClass->adviser->last_name : 'N/A';

                // Only update students who can be promoted
                ClassStudent::where('class_id', $advisoryClass->id)
                    ->whereIn('student_id', $canBePromoted)
                    ->update([
                        'adviser_name' => $adviserName,
                        'section_group' => $advisoryClass->section,
                        'is_promoted' => true,
                        'updated_at' => now()
                    ]);

                // Store teacher names in grades before removing
                foreach ($advisoryClass->subjects as $subject) {
                    $teacherName = $subject->teacher ?
                        $subject->teacher->first_name . ' ' . $subject->teacher->last_name : 'N/A';

                    Grade::where('subject_id', $subject->id)
                        ->update([
                            'teacher_name' => $teacherName,
                            'updated_at' => now()
                        ]);
                }

                // Remove adviser from class
                $advisoryClass->adviser_id = null;
                $advisoryClass->save();

                // Remove all teachers from subjects
                Subject::where('class_id', $advisoryClass->id)
                    ->update([
                        'teacher_id' => null,
                        'updated_at' => now()
                    ]);

                DB::commit();

                $message = "Students have been successfully promoted.";
                if (!empty($droppedTransferredStudents) || !empty($failedStudents)) {
                    $message .= "\n\nThe following students were not promoted:";
                    if (!empty($droppedTransferredStudents)) {
                        $message .= "\n\nDropped/Transferred students:";
                        foreach ($droppedTransferredStudents as $student) {
                            $message .= "\n- {$student['name']} ({$student['status']}) in {$student['subject']}";
                        }
                    }
                    if (!empty($failedStudents)) {
                        $message .= "\n\nStudents with failing grades:";
                        foreach ($failedStudents as $student) {
                            $message .= "\n- {$student['name']} (Final Rating: {$student['final_rating']})";
                        }
                    }
                }

                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);

            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to promote students: ' . $e->getMessage()
                ], 500);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to promote students: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getFilteredClasses(Request $request)
    {
        try {
            $query = Classes::with(['adviser', 'subjects.teacher', 'students']);
    
            // Choose different approaches for junior and senior high classes
            if ($request->level_type === 'junior') {
                // For junior high, we use the join with sections table 
                $query->select('classes.*', 'sections.name as section_name')
                    ->leftJoin('sections', 'sections.id', '=', 'classes.section')
                    ->where('level_type', 'junior');
                
                if ($request->year_level !== 'ALL') {
                    $yearLevel = (int) str_replace('Grade ', '', $request->year_level);
                    $query->where('classes.year_level', $yearLevel);
                }
            } 
            // Handle Senior High filtering
            else if ($request->level_type === 'senior') {
                // For senior high, we select directly from classes and use the section field as the name
                $query->selectRaw('classes.*, classes.section as section_name');
                
                // For Senior High, either fetch:
                // 1. All senior classes
                // 2. Grade 10 classes as well if the show_grade_10 param is true
                $query->where(function($q) use ($request) {
                    $q->where('level_type', 'senior');
                    
                    // Include promoted Grade 10 classes if show_grade_10 is true
                    if ($request->show_grade_10) {
                        $q->orWhere(function($subQ) {
                            $subQ->where('level_type', 'junior')
                                 ->where('year_level', 10)
                                 ->where('adviser_id', null) // This indicates it's a promoted class
                                 ->whereHas('students', function($studentQ) {
                                     // Only classes with students who were promoted
                                     $studentQ->whereHas('classStudents', function($csQ) {
                                         $csQ->where('is_promoted', true);
                                     });
                                 });
                        });
                    }
                });
                
                // Grade level filtering (11, 12, ALL)
                if ($request->year_level !== 'ALL') {
                    $yearLevel = (int) str_replace('G', '', $request->year_level);
                    $query->where('classes.year_level', $yearLevel);
                }
                
                // Add strand filtering for senior high
                if ($request->strand && $request->strand !== 'ALL') {
                    $query->where('classes.strand', $request->strand);
                }
            }
    
            // Section filtering
            if ($request->section && $request->section !== 'ALL') {
                $query->where('classes.section', $request->section);
            }
            
            // Get all classes sorted by assignment status (unassigned first)
            $query->orderByRaw('CASE WHEN adviser_id IS NULL THEN 0 ELSE 1 END');

            $classes = $query->get();
    
            // Handle section names for Grade 10 classes separately
            foreach ($classes as $class) {
                // If this is a Grade 10 class shown in senior view, we need to get section name from the relationship
                if ($class->level_type === 'junior' && $class->year_level === 10 && is_numeric($class->section)) {
                    $sectionDetails = Section::find($class->section);
                    if ($sectionDetails) {
                        $class->section_name = $sectionDetails->name;
                    }
                }
                
                // Add a flag to identify Grade 10 classes for the frontend
                if ($class->level_type === 'junior' && $class->year_level === 10) {
                    $class->is_grade_10 = true;
                } else {
                    $class->is_grade_10 = false;
                }
                
                if ($class->adviser_id === null && count($class->students) > 0) {
                    // For each student in the unassigned class
                    foreach ($class->students as $student) {
                        // Calculate the next grade level
                        $nextYearLevel = $class->year_level + 1;
                        
                        // Check if the student is already in a class of the next grade level
                        $alreadyAssigned = Classes::whereHas('students', function($query) use ($student, $nextYearLevel) {
                            $query->where('users.id', $student->id)
                                  ->where('classes.year_level', $nextYearLevel);
                        })->exists();
                        
                        // Add this information to the student object
                        $student->already_assigned_to_new_class = $alreadyAssigned;
                    }
                    
                    // If all students in this class are already assigned to new classes
                    // mark the entire class as already assigned
                    $class->all_students_already_assigned = $class->students->count() > 0 && 
                        $class->students->every(function($student) {
                            return $student->already_assigned_to_new_class === true;
                        });
                }
            }
    
            // Calculate available advisers
            $assignedAdviserIds = Classes::pluck('adviser_id')->filter()->toArray();
            $availableAdvisers = User::where('role', 'teacher')
                ->whereNotIn('id', $assignedAdviserIds)
                ->get(['id', 'name']);
    
            return response()->json([
                'classes' => $classes,
                'availableAdvisers' => $availableAdvisers
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch classes: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getAvailableStudents(Request $request)
    {
        $yearLevel = $request->query('year_level');
        $levelType = $request->query('level_type', 'junior');
        $previousYearLevel = $request->query('previous_year_level');
        $showUnassigned = $request->query('show_unassigned', false);
        
        // If year_level is provided (like Grade 8), we want students from previous grade (Grade 7)
        if ($yearLevel && !$previousYearLevel) {
            $previousYearLevel = (int)$yearLevel - 1;
        }
    
        // Check for special case - Grade 10 students being promoted to Grade 11 with unassigned advisers
        $isGrade10Promotion = ($previousYearLevel === 10 && $levelType === 'junior' && $showUnassigned);
    
        $query = User::where('role', 'student')
            ->whereHas('classStudents', function($query) use ($previousYearLevel, $levelType, $isGrade10Promotion) {
                $query->where('is_promoted', true)
                       ->whereHas('class', function($q) use ($previousYearLevel, $levelType, $isGrade10Promotion) {
                           $q->where('year_level', $previousYearLevel)
                             ->where('level_type', $levelType);
                             
                           // Add condition for Grade 10 promotion to only show classes with unassigned advisers
                           if ($isGrade10Promotion) {
                               $q->whereNull('adviser_id');
                           }
                       });
            })
            ->with(['classStudents' => function($query) {
                $query->where('is_promoted', true)
                      ->with(['class' => function($q) {
                          $q->with(['sectionDetails', 'adviser']);
                      }]);
            }])
            ->select('id', 'lrn', 'first_name', 'last_name')
            ->orderBy('last_name');
    
        $students = $query->get()->map(function($student) {
            $classStudent = $student->classStudents->first();
            $promotedClass = $classStudent ? $classStudent->class : null;
            $sectionName = $promotedClass && $promotedClass->sectionDetails ? 
                $promotedClass->sectionDetails->name : null;
            
            // Get the previous year level
            $previousYearLevel = $promotedClass ? $promotedClass->year_level : null;
            
            // For promoted students, calculate the new year level (previous + 1)
            $currentYearLevel = $previousYearLevel ? ($previousYearLevel + 1) : null;
            
            // Get strand for senior high classes
            $strand = $promotedClass && $promotedClass->strand ? $promotedClass->strand : null;
            
            // Check if adviser is assigned
            $hasAdviser = $promotedClass && $promotedClass->adviser ? true : false;
    
            return [
                'id' => $student->id,
                'lrn' => $student->lrn,
                'name' => $student->last_name . ', ' . $student->first_name,
                'is_promoted' => true,
                'section_group' => $previousYearLevel, // This should be the previous grade level (e.g., 7 for Grade 8)
                'current_year_level' => $currentYearLevel, // This should be the current grade level (e.g., 8)
                'section_name' => $sectionName,
                'strand' => $strand,
                'has_adviser' => $hasAdviser
            ];
        });
    
        // Filter out students who are already assigned to a class at the next grade level
        $nextYearLevel = $previousYearLevel + 1;
        $filteredStudents = collect();
        
        foreach ($students as $student) {
            // Check if the student is already in a class at the next grade level
            $alreadyAssigned = ClassStudent::where('student_id', $student['id'])
                ->whereHas('class', function($query) use ($nextYearLevel) {
                    $query->where('year_level', $nextYearLevel);
                })
                ->exists();
            
            // Only include students who are NOT already assigned
            if (!$alreadyAssigned) {
                $filteredStudents->push($student);
            }
        }
    
        return response()->json($filteredStudents);
    }

    public function checkAdviserAvailability($teacherId)
    {
        $hasAdvisoryClass = Classes::where('adviser_id', $teacherId)->exists();
        return response()->json(['hasAdvisoryClass' => $hasAdvisoryClass]);
    }

    public function index(): View
    {
    $data = [
        'juniorHighYears' => ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'ALL'],
        'seniorHighGrades' => ['G11', 'G12', 'ALL'],
        'seniorHighStrands' => ['STEM', 'GAS', 'ABM', 'HUMSS', 'TVL'],
        'seniorHighSemesters' => ['1st Sem', '2nd Sem'],
        'classes' => Classes::with(['adviser', 'subjects.teacher'])->get(),
        'advisers' => User::where('role', 'teacher')->get()
    ];

    return view('admin/sectionsubject', $data);
    }

    // SectionSubjectController.php

    public function addClassJunior(): View
    {
    $teachers = User::where('role', 'teacher')->get();

    $promotedStudents = User::whereHas('classStudents', function ($query) {
        $query->where('is_promoted', true)
              ->whereHas('class', function ($q) {
                  $q->where('level_type', 'junior');
              });
    })->with(['classStudents' => function($query) {
        $query->where('is_promoted', true)
              ->with('class')
              ->latest();
    }])->get();

    // Filter out students who are already assigned to classes at the next grade level
    $filteredPromotedStudents = $promotedStudents->filter(function($student) {
        $promotedClass = $student->classStudents->firstWhere('is_promoted', true)->class ?? null;
        
        if (!$promotedClass) {
            return false;
        }
        
        // Calculate the next grade level this student should be assigned to
        $nextGradeLevel = $promotedClass->year_level + 1;
        
        // Check if the student is already assigned to a class in the next grade level
        $alreadyAssigned = ClassStudent::where('student_id', $student->id)
            ->whereHas('class', function($query) use ($nextGradeLevel) {
                $query->where('year_level', $nextGradeLevel);
            })
            ->exists();
        
        // Keep only students who are NOT already assigned to classes at the next grade level
        return !$alreadyAssigned;
    });

    return view('admin/addclassjunior', compact('teachers', 'filteredPromotedStudents'));
    }

    public function addClassSenior(): View
    {
    $teachers = User::where('role', 'teacher')->get();
    
    // Fetch all strands from the database
    $strands = \App\Models\Strand::orderBy('name')->get();

    $promotedStudents = User::whereHas('classStudents', function ($query) {
        $query->where('is_promoted', true)
              ->whereHas('class', function ($q) {
                  $q->where('level_type', 'senior');
              });
    })->with(['classStudents' => function($query) {
        $query->where('is_promoted', true)
              ->with('class')
              ->latest();
    }])->get();

    // Filter out students who are already assigned to classes at the next grade level
    $filteredPromotedStudents = $promotedStudents->filter(function($student) {
        $promotedClass = $student->classStudents->firstWhere('is_promoted', true)->class ?? null;
        
        if (!$promotedClass) {
            return false;
        }
        
        // Calculate the next grade level this student should be assigned to
        $nextGradeLevel = $promotedClass->year_level + 1;
        
        // Check if the student is already assigned to a class in the next grade level
        $alreadyAssigned = ClassStudent::where('student_id', $student->id)
            ->whereHas('class', function($query) use ($nextGradeLevel) {
                $query->where('year_level', $nextGradeLevel);
            })
            ->exists();
        
        // Keep only students who are NOT already assigned to classes at the next grade level
        return !$alreadyAssigned;
    });

    return view('admin/addclasssenior', compact('teachers', 'filteredPromotedStudents', 'strands'));
    }
    public function storeJuniorClass(Request $request)
    {
        \Log::info('Starting storeJuniorClass with data:', $request->except(['student_list']));
        try {
            $validator = Validator::make($request->all(), [
                'year_level' => 'required|integer|between:7,10',
                'section' => 'required|string',
                'adviser_id' => 'required|exists:users,id',
                'subjects' => 'required|array|min:1',
                'subjects.*.name' => 'required|string',
                'subjects.*.teacher_id' => 'required|exists:users,id',
                'student_list' => 'required_if:year_level,7|file|mimes:csv,txt',
                'selected_students' => 'required_if:year_level,8,9,10|array'
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed in storeJuniorClass:', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            // Check if a class with this section already exists for the given year level
            $classExists = Classes::where('section', $request->section)
                ->where('year_level', $request->year_level)
                ->exists();
                
            if ($classExists) {
                return response()->json([
                    'errors' => ['section' => ['A class with this section already exists for this year level.']],
                ], 422);
            }

            DB::beginTransaction();

            // Create the class
            \Log::info('Creating class with data:', [
                'level_type' => 'junior',
                'year_level' => $request->year_level,
                'section' => $request->section,
                'adviser_id' => $request->adviser_id
            ]);
            
            $class = Classes::create([
                'level_type' => 'junior',
                'year_level' => $request->year_level,
                'section' => $request->section,
                'adviser_id' => $request->adviser_id
            ]);
            
            \Log::info('Class created successfully with ID: ' . $class->id);

            // Create subjects
            $subjectIds = [];

            // Remove subject group creation for junior high
            foreach ($request->subjects as $index => $subjectData) {
                try {
                    // Create a new subject record
                    $subject = Subject::create([
                        'name' => $subjectData['name'],
                        'class_id' => $class->id,
                        'teacher_id' => $subjectData['teacher_id']
                    ]);
                    
                    $subjectIds[] = $subject->id;
                    \Log::info("Created new subject {$subjectData['name']}");
                } catch (\Exception $e) {
                    \Log::error("Error processing subject {$index}: " . $e->getMessage());
                    throw new \Exception("Error processing subject '{$subjectData['name']}': " . $e->getMessage());
                }
            }

            \Log::info('All subjects processed successfully: ' . count($subjectIds) . ' subjects');

            // Process students based on year level
            if ($request->year_level == 7) {
                // Process CSV file for Grade 7
                if ($request->hasFile('student_list')) {
                    $csvFile = $request->file('student_list');
                    \Log::info('Processing CSV file: ' . $csvFile->getClientOriginalName());
                    
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
                        \Log::info('Using delimiter: ' . ($delimiter === "\t" ? "tab" : $delimiter));
                    } else {
                        \Log::warning('Could not determine delimiter, defaulting to comma');
                        $csv->setDelimiter(",");
                    }
                    
                    try {
                        $records = $csv->getRecords();
                        $studentCount = 0;
                        $errorCount = 0;
                        
                        // Get original headers (preserve case)
                        $originalHeaders = $csv->getHeader();
                        // Get lowercased headers for comparison
                        $lowerHeaders = array_map('strtolower', array_map('trim', $originalHeaders));
                        \Log::info('Original CSV Headers:', $originalHeaders);
                        \Log::info('Lowercase CSV Headers:', $lowerHeaders);
    
                        // Map common header variations (keep variations lowercase for comparison)
                        $headerMap = [
                            'lrn' => ['lrn', 'learner reference number', 'learner_reference_number', 'student id', 'student_id'],
                            'first_name' => ['first name', 'first_name', 'firstname', 'given name', 'given_name'],
                            'last_name' => ['last name', 'last_name', 'lastname', 'surname', 'family name', 'family_name'],
                            'birthdate' => ['birthday', 'birthdate', 'birth date', 'birth_date', 'date of birth', 'date_of_birth']
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
    
                        \Log::info('Column mapping (maps internal field name => actual CSV header):', $columnMap);
                        
                        if (empty($columnMap)) {
                            throw new \Exception("Could not map CSV headers to required fields. Please check your CSV format.");
                        }
                        
                        if (!isset($columnMap['lrn']) || !isset($columnMap['first_name']) || !isset($columnMap['last_name'])) {
                            throw new \Exception("CSV is missing required columns. Make sure you have First Name, Last Name, and LRN columns.");
                        }
                    
                        foreach ($records as $recordIndex => $record) {
                            try {
                                // Debug log the raw record
                                \Log::info("Processing record #{$recordIndex}:", $record);
                                
                                // Extract data using the column map (which now holds the actual header keys)
                                // Use null coalescing operator ?? for safety
                                $lrn = trim($record[$columnMap['lrn']] ?? '');
                                $firstName = trim($record[$columnMap['first_name']] ?? '');
                                $lastName = trim($record[$columnMap['last_name']] ?? '');
                                $birthdate = trim($record[$columnMap['birthdate']] ?? '');
    
                                // Validate required fields
                                $validationErrors = [];
                                if (empty($lrn)) $validationErrors[] = "LRN is missing";
                                if (empty($firstName)) $validationErrors[] = "First Name is missing";
                                if (empty($lastName)) $validationErrors[] = "Last Name is missing";
                                if (empty($birthdate)) $validationErrors[] = "Birthdate is missing";
                                
                                if (!empty($validationErrors)) {
                                    throw new \Exception("Record #{$recordIndex} has validation errors: " . implode(", ", $validationErrors));
                                }
    
                                // Normalize birthdate - try all possible formats and date strings
                                $birthdateObj = null;
                                
                                // Special case for '01-Jan-01' format
                                if (preg_match('/^\d{1,2}-[A-Za-z]{3}-\d{1,2}$/', $birthdate)) {
                                    try {
                                        list($day, $month, $year) = explode('-', $birthdate);
                                        $monthMap = [
                                            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 
                                            'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 
                                            'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
                                        ];
                                        $monthNum = $monthMap[strtolower($month)] ?? null;
                                        if ($monthNum) {
                                            if (strlen($year) <= 2) {
                                                $year = (int)$year < 50 ? '20'.$year : '19'.$year;
                                            }
                                            if (checkdate($monthNum, (int)$day, (int)$year)) {
                                                $birthdateObj = new \DateTime("$year-$monthNum-$day");
                                                \Log::info("Parsed special date format: $birthdate → {$birthdateObj->format('Y-m-d')}");
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        \Log::warning("Failed to parse special date format: $birthdate", ['error' => $e->getMessage()]);
                                    }
                                }
                                
                                // First try common formats if special case didn't work
                                if (!$birthdateObj) {
                                    $formats = [
                                        'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d',
                                        'd-m-Y', 'M d, Y', 'F d, Y', 
                                        'Y-n-j', 'n/j/Y', 'j/n/Y',
                                        'm.d.Y', 'd.m.Y', 'Y.m.d',
                                        'd-M-y', 'd-M-Y',
                                        'j-M-y', 'j-M-Y', 
                                        'j-F-y', 'j-F-Y',
                                        'd-F-y', 'd-F-Y'
                                    ];
        
                                    // Try with standard date formats first
                                    foreach ($formats as $format) {
                                        try {
                                            $dateObj = \DateTime::createFromFormat($format, $birthdate);
                                            if ($dateObj !== false) {
                                                $birthdateObj = $dateObj;
                                                break;
                                            }
                                        } catch (\Exception $e) {
                                            // Continue to next format
                                        }
                                    }
                                    
                                    // If no format matched, try with Carbon's flexible parsing
                                    if (!$birthdateObj) {
                                        try {
                                            $birthdateObj = \Carbon\Carbon::parse($birthdate);
                                        } catch (\Exception $e) {
                                            // Continue to next approach
                                        }
                                    }
        
                                    // If still no match, try to extract numbers
                                    if (!$birthdateObj) {
                                        preg_match_all('/\d+/', $birthdate, $matches);
                                        $numbers = $matches[0];
                                        
                                        if (count($numbers) >= 3) {
                                            // Try different number arrangements
                                            $arrangements = [
                                                // year could be 2-4 digits and in different positions
                                                [$numbers[2], $numbers[1], $numbers[0]], // y-m-d
                                                [$numbers[0], $numbers[1], $numbers[2]], // d-m-y
                                                [$numbers[1], $numbers[0], $numbers[2]], // m-d-y
                                                [$numbers[1], $numbers[2], $numbers[0]], // m-y-d
                                                [$numbers[0], $numbers[2], $numbers[1]], // d-y-m
                                                [$numbers[2], $numbers[0], $numbers[1]]  // y-d-m
                                            ];
            
                                            foreach ($arrangements as list($part1, $part2, $part3)) {
                                                // Try to identify which part is the year
                                                $year = null;
                                                $month = null;
                                                $day = null;
                                                
                                                // Year is likely the 4-digit number or largest number
                                                if (strlen($part1) == 4 || (int)$part1 > 31) {
                                                    $year = $part1;
                                                    $month = $part2;
                                                    $day = $part3;
                                                } else if (strlen($part2) == 4 || (int)$part2 > 31) {
                                                    $year = $part2;
                                                    $month = $part1;
                                                    $day = $part3;
                                                } else if (strlen($part3) == 4 || (int)$part3 > 31) {
                                                    $year = $part3;
                                                    $month = $part1;
                                                    $day = $part2;
                                                } else {
                                                    // If no part is obviously the year, assume standard arrangement
                                                    $day = $part1;
                                                    $month = $part2;
                                                    $year = $part3;
                                                }
                                                
                                                // Ensure year is 4 digits
                                                if (strlen($year) == 2) {
                                                    // Assume 20YY for years less than 23, 19YY for others
                                                    $year = (int)$year <= 23 ? '20' . $year : '19' . $year;
                                                }
                                                
                                                // Try to create a valid date
                                                if (checkdate((int)$month, (int)$day, (int)$year)) {
                                                    $birthdateObj = \DateTime::createFromFormat('Y-n-j', "$year-$month-$day");
                                                    break 2; // Break out of both loops if we found a valid date
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                // Use current date as default if parsing fails (but log warning)
                                if (!$birthdateObj) {
                                    \Log::warning("Could not parse birthdate: '$birthdate'. Using default date.", [
                                        'record_lrn' => $lrn,
                                        'record_name' => "$firstName $lastName"
                                    ]);
                                    $birthdateObj = new \DateTime();
                                }
    
                                // Format the dates
                                $formattedBirthdate = $birthdateObj->format('Y-m-d');
                                $passwordDate = $birthdateObj->format('dmY'); // Format: ddmmyyyy
    
                                \Log::info("Date processed", [
                                    'original' => $birthdate,
                                    'formatted' => $formattedBirthdate,
                                    'password' => $passwordDate
                                ]);
    
                                // Create or update student
                                $student = User::updateOrCreate(
                                    ['lrn' => $lrn], // Use LRN as the unique identifier
                                    [
                                        'first_name' => $firstName,
                                        'last_name' => $lastName,
                                        'name' => $firstName . ' ' . $lastName,
                                        'birthday' => $formattedBirthdate,
                                        'role' => 'student',
                                        'username' => $lrn, // Set username to LRN
                                        'email' => $lrn, // Set email to LRN for compatibility
                                        'password' => bcrypt($passwordDate) // Password is birthdate in ddmmyyyy format
                                    ]
                                );
    
                                // Create class student relationship
                                ClassStudent::updateOrCreate(
                                    [
                                    'class_id' => $class->id,
                                    'student_id' => $student->id
                                    ],
                                    []
                                );
    
                                $studentCount++;
                                \Log::info("Successfully processed student", [
                                    'lrn' => $lrn,
                                    'name' => $student->name,
                                    'birthdate' => $formattedBirthdate,
                                    'class_id' => $class->id
                                ]);
    
                            } catch (\Exception $e) {
                                $errorCount++;
                                \Log::error("Error processing student record #{$recordIndex}", [
                                    'record' => $record,
                                    'error' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                            }
                        }
    
                        // Log final statistics
                        \Log::info("CSV processing completed", [
                            'total_processed' => $studentCount + $errorCount,
                            'successful' => $studentCount,
                            'failed' => $errorCount,
                            'class_id' => $class->id
                        ]);
    
                        if ($studentCount === 0) {
                            throw new \Exception("No students were successfully processed from the CSV file. Please check the file format and try again.");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error processing CSV file: " . $e->getMessage());
                        throw new \Exception("Error processing CSV file: " . $e->getMessage());
                    }
                } else {
                    \Log::error("No student_list file was uploaded but year_level is 7");
                    throw new \Exception("Year level is 7 but no student list CSV was uploaded.");
                }
            } else {
                // Process selected students for Grades 8-10
                if (!empty($request->selected_students)) {
                    \Log::info("Processing selected students for grades 8-10", [
                        'count' => count($request->selected_students)
                    ]);
                    
                    foreach ($request->selected_students as $studentId) {
                        try {
                            ClassStudent::create([
                                'class_id' => $class->id,
                                'student_id' => $studentId
                            ]);
                            \Log::info("Added student ID {$studentId} to class {$class->id}");
                        } catch (\Exception $e) {
                            \Log::error("Error adding student ID {$studentId} to class: " . $e->getMessage());
                            throw new \Exception("Error adding student ID {$studentId} to class: " . $e->getMessage());
                        }
                    }
                } else {
                    \Log::warning("No students selected for grades 8-10 class");
                }
            }

            DB::commit();
            \Log::info("Class created successfully with ID: {$class->id}");
            return response()->json(['message' => 'Class created successfully'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Failed to create class: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to create class: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeSeniorClass(Request $request)
    {
        \Log::info('Starting storeSeniorClass with data:', $request->except(['selected_students', 'student_list']));
        try {
            $validator = Validator::make($request->all(), [
                'grade_level' => 'required|integer|in:11,12',
                'strand' => 'required|string',
                'semester' => 'required|in:1,2',
                'section' => 'required|string',
                'adviser_id' => 'required|exists:users,id',
                'subjects' => 'required|array|min:1',
                'subjects.*.name' => 'required|string',
                'subjects.*.teacher_id' => 'required|exists:users,id',
                'selected_students' => 'required_if:grade_level,12|array',
                'student_list' => 'required_if:grade_level,11|file|mimes:csv,txt'
            ]);

            if ($validator->fails()) {
                \Log::warning('Validation failed in storeSeniorClass:', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            // Check if a class with this section already exists for the given year level and strand
            $classExists = Classes::where('section', $request->section)
                ->where('year_level', $request->grade_level)
                ->where('strand', $request->strand)
                ->where('semester', $request->semester)
                ->exists();
                
            if ($classExists) {
                return response()->json([
                    'errors' => ['section' => ['A class with this section already exists for this year level, strand, and semester.']],
                ], 422);
            }

            DB::beginTransaction();

            // Create the class
            \Log::info('Creating senior class with data:', [
                'level_type' => 'senior',
                'year_level' => $request->grade_level,
                'section' => $request->section,
                'strand' => $request->strand,
                'semester' => $request->semester,
                'adviser_id' => $request->adviser_id
            ]);
            
            $class = Classes::create([
                'level_type' => 'senior',
                'year_level' => $request->grade_level,
                'section' => $request->section,
                'strand' => $request->strand,
                'semester' => $request->semester,
                'adviser_id' => $request->adviser_id
            ]);
            
            \Log::info('Class created successfully with ID: ' . $class->id);

            // Create subjects
            $subjectIds = [];
            
            // Find or create the subject group for this grade level, strand, and semester
            $subjectGroup = \App\Models\SubjectGroup::firstOrCreate([
                'grade_level' => $request->grade_level,
                'strand' => $request->strand,
                'semester' => $request->semester
            ]);
            
            foreach ($request->subjects as $index => $subjectData) {
                try {
                    // Check if subject already exists in this group
                    $existingSubject = Subject::where('name', $subjectData['name'])
                        ->where('subject_group_id', $subjectGroup->id)
                        ->first();
                    
                    // Create a new subject record, either copying from existing or creating new
                    $subject = Subject::create([
                            'name' => $subjectData['name'],
                        'class_id' => $class->id,
                            'teacher_id' => $subjectData['teacher_id'],
                            'subject_group_id' => $subjectGroup->id
                        ]);
                    
                    $subjectIds[] = $subject->id;
                    \Log::info(
                        $existingSubject 
                            ? "Created new subject record for existing subject {$subjectData['name']}" 
                            : "Created completely new subject {$subjectData['name']}"
                    );
                } catch (\Exception $e) {
                    \Log::error("Error processing subject {$index}: " . $e->getMessage());
                    throw new \Exception("Error processing subject '{$subjectData['name']}': " . $e->getMessage());
                }
            }
            
            \Log::info('All subjects processed successfully: ' . count($subjectIds) . ' subjects');

            // Process students based on grade level
            if ($request->grade_level == 11) {
                // Process CSV file for Grade 11
                if ($request->hasFile('student_list')) {
                    $csvFile = $request->file('student_list');
                    \Log::info('Processing CSV file: ' . $csvFile->getClientOriginalName());
                    
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
                        \Log::info('Using delimiter: ' . ($delimiter === "\t" ? "tab" : $delimiter));
                    } else {
                        \Log::warning('Could not determine delimiter, defaulting to comma');
                        $csv->setDelimiter(",");
                    }
                    
                    try {
                        $records = $csv->getRecords();
                        $studentCount = 0;
                        $errorCount = 0;
                        
                        // Get original headers (preserve case)
                        $originalHeaders = $csv->getHeader();
                        // Get lowercased headers for comparison
                        $lowerHeaders = array_map('strtolower', array_map('trim', $originalHeaders));
                        \Log::info('Original CSV Headers:', $originalHeaders);
                        \Log::info('Lowercase CSV Headers:', $lowerHeaders);

                        // Map common header variations (keep variations lowercase for comparison)
                        $headerMap = [
                            'lrn' => ['lrn', 'learner reference number', 'learner_reference_number', 'student id', 'student_id'],
                            'first_name' => ['first name', 'first_name', 'firstname', 'given name', 'given_name'],
                            'last_name' => ['last name', 'last_name', 'lastname', 'surname', 'family name', 'family_name'],
                            'birthdate' => ['birthday', 'birthdate', 'birth date', 'birth_date', 'date of birth', 'date_of_birth']
                        ];

                        // Find the actual column header strings from the CSV
                        $columnMap = [];
                        foreach ($headerMap as $field => $variations) {
                            foreach ($variations as $variant) {
                                $foundIndex = array_search($variant, $lowerHeaders);
                                if ($foundIndex !== false) {
                                    $columnMap[$field] = $originalHeaders[$foundIndex];
                                    break;
                                }
                            }
                        }

                        \Log::info('Column mapping:', $columnMap);
                        
                        if (empty($columnMap)) {
                            throw new \Exception("Could not map CSV headers to required fields. Please check your CSV format.");
                        }
                        
                        if (!isset($columnMap['lrn']) || !isset($columnMap['first_name']) || !isset($columnMap['last_name'])) {
                            throw new \Exception("CSV is missing required columns. Make sure you have First Name, Last Name, and LRN columns.");
                        }
                    
                        foreach ($records as $recordIndex => $record) {
                            try {
                                \Log::info("Processing record #{$recordIndex}:", $record);
                                
                                $lrn = trim($record[$columnMap['lrn']] ?? '');
                                $firstName = trim($record[$columnMap['first_name']] ?? '');
                                $lastName = trim($record[$columnMap['last_name']] ?? '');
                                $birthdate = trim($record[$columnMap['birthdate']] ?? '');

                                // Validate required fields
                                $validationErrors = [];
                                if (empty($lrn)) $validationErrors[] = "LRN is missing";
                                if (empty($firstName)) $validationErrors[] = "First Name is missing";
                                if (empty($lastName)) $validationErrors[] = "Last Name is missing";
                                if (empty($birthdate)) $validationErrors[] = "Birthdate is missing";
                                
                                if (!empty($validationErrors)) {
                                    throw new \Exception("Record #{$recordIndex} has validation errors: " . implode(", ", $validationErrors));
                                }

                                // Parse birthdate using the same logic as in storeJuniorClass
                                $birthdateObj = null;
                                
                                // Special case for '01-Jan-01' format
                                if (preg_match('/^\d{1,2}-[A-Za-z]{3}-\d{1,2}$/', $birthdate)) {
                                    try {
                                        list($day, $month, $year) = explode('-', $birthdate);
                                        $monthMap = [
                                            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4, 
                                            'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8, 
                                            'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
                                        ];
                                        $monthNum = $monthMap[strtolower($month)] ?? null;
                                        if ($monthNum) {
                                            if (strlen($year) <= 2) {
                                                $year = (int)$year < 50 ? '20'.$year : '19'.$year;
                                            }
                                            if (checkdate($monthNum, (int)$day, (int)$year)) {
                                                $birthdateObj = new \DateTime("$year-$monthNum-$day");
                                            }
                                        }
                                    } catch (\Exception $e) {
                                        \Log::warning("Failed to parse special date format: $birthdate");
                                    }
                                }
                                
                                // Try common formats if special case didn't work
                                if (!$birthdateObj) {
                                    $formats = [
                                        'Y-m-d', 'm/d/Y', 'd/m/Y', 'Y/m/d',
                                        'd-m-Y', 'M d, Y', 'F d, Y', 
                                        'Y-n-j', 'n/j/Y', 'j/n/Y',
                                        'm.d.Y', 'd.m.Y', 'Y.m.d',
                                        'd-M-y', 'd-M-Y',
                                        'j-M-y', 'j-M-Y', 
                                        'j-F-y', 'j-F-Y',
                                        'd-F-y', 'd-F-Y'
                                    ];
    
                                    foreach ($formats as $format) {
                                        try {
                                            $dateObj = \DateTime::createFromFormat($format, $birthdate);
                                            if ($dateObj !== false) {
                                                $birthdateObj = $dateObj;
                                                break;
                                            }
                                        } catch (\Exception $e) {
                                            continue;
                                        }
                                    }
                                    
                                    if (!$birthdateObj) {
                                        try {
                                            $birthdateObj = \Carbon\Carbon::parse($birthdate);
                                        } catch (\Exception $e) {
                                            // Continue to next approach
                                        }
                                    }
                                }
                                
                                if (!$birthdateObj) {
                                    \Log::warning("Could not parse birthdate: '$birthdate'. Using default date.");
                                    $birthdateObj = new \DateTime();
                                }

                                // Format the dates
                                $formattedBirthdate = $birthdateObj->format('Y-m-d');
                                $passwordDate = $birthdateObj->format('dmY');

                                // Create or update student
                                $student = User::updateOrCreate(
                                    ['lrn' => $lrn],
                                    [
                                        'first_name' => $firstName,
                                        'last_name' => $lastName,
                                        'name' => $firstName . ' ' . $lastName,
                                        'birthday' => $formattedBirthdate,
                                        'role' => 'student',
                                        'username' => $lrn,
                                        'email' => $lrn,
                                        'password' => bcrypt($passwordDate)
                                    ]
                                );

                                // Create class student relationship
                                ClassStudent::updateOrCreate(
                                    [
                        'class_id' => $class->id,
                                        'student_id' => $student->id
                                    ],
                                    []
                                );

                                $studentCount++;
                                \Log::info("Successfully processed student", [
                                    'lrn' => $lrn,
                                    'name' => $student->name,
                                    'birthdate' => $formattedBirthdate,
                                    'class_id' => $class->id
                                ]);

                } catch (\Exception $e) {
                                $errorCount++;
                                \Log::error("Error processing student record #{$recordIndex}", [
                                    'record' => $record,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }

                        \Log::info("CSV processing completed", [
                            'total_processed' => $studentCount + $errorCount,
                            'successful' => $studentCount,
                            'failed' => $errorCount,
                            'class_id' => $class->id
                        ]);

                        if ($studentCount === 0) {
                            throw new \Exception("No students were successfully processed from the CSV file.");
                        }
                    } catch (\Exception $e) {
                        \Log::error("Error processing CSV file: " . $e->getMessage());
                        throw new \Exception("Error processing CSV file: " . $e->getMessage());
                    }
                } else {
                    \Log::error("No student_list file was uploaded but grade_level is 11");
                    throw new \Exception("Grade level is 11 but no student list CSV was uploaded.");
                }
            } else {
                // Process selected students for Grade 12
                if (!empty($request->selected_students)) {
                    \Log::info("Processing selected students for grade 12", [
                    'count' => count($request->selected_students)
                ]);
                
                foreach ($request->selected_students as $studentId) {
                    try {
                        ClassStudent::create([
                            'class_id' => $class->id,
                            'student_id' => $studentId
                        ]);
                        \Log::info("Added student ID {$studentId} to class {$class->id}");
                    } catch (\Exception $e) {
                        \Log::error("Error adding student ID {$studentId} to class: " . $e->getMessage());
                        throw new \Exception("Error adding student ID {$studentId} to class: " . $e->getMessage());
                    }
                }
            } else {
                    \Log::warning("No students selected for grade 12 class");
                }
            }

            DB::commit();
            \Log::info("Senior class created successfully with ID: {$class->id}");
            return response()->json(['message' => 'Class created successfully'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Failed to create senior class: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Failed to create senior class: ' . $e->getMessage()
            ], 500);
        }
    }

    // 
    

    public function getSectionsByStrand(Request $request)
    {
        \Log::info('Fetching sections for:', [
            'strand' => $request->query('strand'),
            'grade_level' => $request->query('grade_level')
        ]);
        
        $strand = $request->query('strand');
        $gradeLevel = $request->query('grade_level');
        
        if (!$strand || !$gradeLevel) {
            return response()->json([]);
        }
    
        // Find the strand by name
        $strandModel = Strand::where('name', $strand)->first();
    
        if (!$strandModel) {
            return response()->json([]);
        }
    
        // Get sections for this strand and grade level
        $sections = Section::where('strand_id', $strandModel->id)
            ->where('grade_level', $gradeLevel)
            ->orderBy('name')
            ->get()
            ->pluck('name'); // Only return the names
    
        \Log::info('Sections found:', $sections->toArray());
    
        return response()->json($sections);
    }
    /**
     * Get strands by grade level
     */
    public function getStrandsByGradeLevel(Request $request)
    {
        try {
            $gradeLevel = $request->input('grade_level');
            
            if (!$gradeLevel || $gradeLevel === 'ALL') {
                // If ALL is selected, return all strands
                $strands = Strand::orderBy('name')->get();
                return response()->json($strands);
            }

            // Convert grade level format if needed (G11 -> 11)
            if (str_starts_with($gradeLevel, 'G')) {
                $gradeLevel = (int) substr($gradeLevel, 1);
            }

            // For debugging purposes, log which strands we're returning
            \Log::info('Fetching strands for grade level: ' . $gradeLevel);
            
            // Return all strands for senior high school
            // We don't filter by sections since sections might not exist yet
            $strands = Strand::orderBy('name')->get();
            
            \Log::info('Found strands: ' . $strands->pluck('name')->implode(', '));
            
            return response()->json($strands);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching strands: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get available teachers (those not already assigned to a class)
     */
    public function getAvailableTeachers(Request $request)
    {
        try {
            // Get all teacher IDs who are already assigned as advisers
            $assignedTeacherIds = Classes::whereNotNull('adviser_id')
                ->pluck('adviser_id')
                ->toArray();
            
            // Get teachers who aren't assigned to any class
            $availableTeachers = User::where('role', 'teacher')
                ->whereNotIn('id', $assignedTeacherIds)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
            
            return response()->json($availableTeachers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get available teachers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getByGroup(Request $request)
{
    $request->validate([
        'grade' => 'required|in:11,12',
        'strand' => 'required|string',
        'semester' => 'required|in:1,2'
    ]);
    
    $subjects = Subject::whereHas('group', function($query) use ($request) {
        $query->where('grade_level', $request->grade)
              ->where('strand', $request->strand)
              ->where('semester', $request->semester);
    })->get();
    
    return response()->json($subjects);
}

    /**
     * Assign a teacher to a class as the adviser
     */
    public function assignTeacherToClass(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|exists:classes,id',
                'teacher_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if the teacher already has an advisory class
            $hasAdvisoryClass = Classes::where('adviser_id', $request->teacher_id)->exists();
            if ($hasAdvisoryClass) {
                return response()->json([
                    'success' => false,
                    'message' => 'This teacher is already assigned as an adviser to another class.'
                ], 400);
            }

            // Assign the teacher to the class
            $class = Classes::findOrFail($request->class_id);
            $class->adviser_id = $request->teacher_id;
            $class->save();

            return response()->json([
                'success' => true,
                'message' => 'Teacher assigned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign teacher: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sections that already have classes assigned to them
     */
    public function getSectionsWithClasses(Request $request)
    {
        try {
            $gradeLevel = $request->query('grade_level');
            
            if (!$gradeLevel) {
                return response()->json([]);
            }
            
            // Find sections that are used in classes that have not been promoted
            $usedSections = Section::whereHas('classes', function($query) use ($gradeLevel) {
                $query->where('year_level', $gradeLevel)
                      ->whereNotNull('adviser_id'); // Only consider classes with an adviser as "used"
            })->get(['id', 'name']);
            
            return response()->json($usedSections);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get sections with classes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if a class with the given section already exists
     */
    public function checkSectionUsed(Request $request)
    {
        try {
            $section = $request->query('section');
            $yearLevel = $request->query('year_level');
            
            if (!$section || !$yearLevel) {
                return response()->json(['exists' => false]);
            }
            
            // Check if a class with this section already exists for the given year level
            $classExists = Classes::where('section', $section)
                ->where('year_level', $yearLevel)
                ->exists();
            
            return response()->json(['exists' => $classExists]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to check section: ' . $e->getMessage(),
                'exists' => false
            ], 500);
        }
    }
}



