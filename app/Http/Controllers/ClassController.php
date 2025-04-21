<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use App\Models\ClassStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ClassController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            
            // Create the class
            $class = Classes::create([
                'year_level' => $request->year_level,
                'section_id' => $request->section,
                'adviser_id' => $request->adviser_id,
                'level_type' => 'junior'
            ]);
            
            // Handle subjects and teachers
            if ($request->has('subjects')) {
                foreach ($request->subjects as $subjectData) {
                    $class->subjects()->attach($subjectData['teacher_id'], [
                        'subject_name' => $subjectData['name']
                    ]);
                }
            }
            
            // Handle students based on year level
            if ($request->year_level == 7) {
                if ($request->hasFile('student_list')) {
                    $file = $request->file('student_list');
                    $handle = fopen($file->getPathname(), 'r');
                    
                    // Skip header row
                    fgetcsv($handle);
                    
                    $studentIds = [];
                    
                    while (($row = fgetcsv($handle)) !== false) {
                        if (count($row) >= 5) {
                            $firstName = trim($row[0]);
                            $middleName = trim($row[1]);
                            $lastName = trim($row[2]);
                            $lrn = trim($row[3]);
                            $birthdate = trim($row[4]);
                            
                            // Check if student exists
                            $existingStudent = Student::where('lrn', $lrn)->first();
                            
                            if ($existingStudent) {
                                $studentIds[] = $existingStudent->id;
                            } else {
                                try {
                                    // Try to parse the birthdate in various formats
                                    $birthdateObj = null;
                                    $birthdate = trim($row[4]);
                                    
                                    // Remove any extra spaces
                                    $birthdate = preg_replace('/\s+/', ' ', $birthdate);
                                    
                                    // Try different date formats
                                    $dateFormats = [
                                        'Y-m-d',           // 2000-12-31
                                        'm/d/Y',           // 12/31/2000
                                        'd/m/Y',           // 31/12/2000
                                        'F d, Y',          // December 31, 2000
                                        'M d, Y',          // Dec 31, 2000
                                        'm-d-Y',           // 12-31-2000
                                        'd-m-Y',           // 31-12-2000
                                        'Y/m/d',           // 2000/12/31
                                        'm.d.Y',           // 12.31.2000
                                        'd.m.Y'            // 31.12.2000
                                    ];
                                    
                                    foreach ($dateFormats as $format) {
                                        try {
                                            $birthdateObj = Carbon::createFromFormat($format, $birthdate);
                                            if ($birthdateObj && $birthdateObj->isValid()) {
                                                break;
                                            }
                                        } catch (\Exception $e) {
                                            continue;
                                        }
                                    }
                                    
                                    // If no format worked, try natural language parsing
                                    if (!$birthdateObj || !$birthdateObj->isValid()) {
                                        $birthdateObj = Carbon::parse($birthdate);
                                    }
                                    
                                    if (!$birthdateObj || !$birthdateObj->isValid()) {
                                        throw new \Exception("Invalid date format for birthdate: $birthdate");
                                    }
                                    
                                    // Format password as ddmmyy
                                    $password = $birthdateObj->format('dmy');
                                    
                                    // Store birthdate in standard format
                                    $standardBirthdate = $birthdateObj->format('Y-m-d');
                                    
                                    // Create user account
                                    $user = User::create([
                                        'name' => "$firstName $middleName $lastName",
                                        'username' => $lrn,
                                        'email' => $lrn,
                                        'password' => Hash::make($password),
                                        'role' => 'student'
                                    ]);
                                    
                                    // Create student record
                                    $student = Student::create([
                                        'user_id' => $user->id,
                                        'lrn' => $lrn,
                                        'first_name' => $firstName,
                                        'middle_name' => $middleName,
                                        'last_name' => $lastName,
                                        'birthdate' => $standardBirthdate
                                    ]);
                                    
                                    $studentIds[] = $student->id;
                                } catch (\Exception $e) {
                                    throw new \Exception("Error processing student data for LRN $lrn: " . $e->getMessage());
                                }
                            }
                        }
                    }
                    
                    fclose($handle);
                    
                    // Attach all students to the class
                    if (!empty($studentIds)) {
                        $class->students()->attach($studentIds);
                    }
                }
            } else {
                // Handle existing student selection for other grades
                if ($request->has('selected_students')) {
                    $class->students()->attach($request->selected_students);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Class created successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create class: ' . $e->getMessage()
            ], 500);
        }
    }

    public function addStudentToClass(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'class_id' => 'required|exists:classes,id',
                'lrn' => 'required|string|max:12|unique:users,lrn',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'birthday' => 'required|date'
            ]);

            DB::beginTransaction();

            // Format birthday for password (ddmmyyyy)
            $birthday = Carbon::parse($request->birthday);
            $passwordDate = $birthday->format('dmY');

            // Create user account
            $user = User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'lrn' => $request->lrn,
                'email' => $request->lrn,
                'password' => Hash::make($passwordDate),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'role' => 'student',
                'birthday' => $birthday
            ]);

            // Add student to class
            ClassStudent::create([
                'class_id' => $request->class_id,
                'student_id' => $user->id,
                'is_active' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Student added successfully'
            ]);

        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add student: ' . $e->getMessage()
            ], 500);
        }
    }
} 