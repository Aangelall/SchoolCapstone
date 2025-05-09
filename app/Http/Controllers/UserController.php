<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use League\Csv\Reader;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the teachers.
     */
    public function showTeachers()
    {
        // Only allow admin access
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('user.dashboard')->with('error', 'Unauthorized access.');
        }
    
        $users = User::all();
        return view('admin.addteacher', compact('users'));
    }
    public function exportSelectedTeachersToCSV(Request $request)
    {
        $selectedIds = $request->input('selected_teachers', []);

        $teachers = User::whereIn('id', $selectedIds)
            ->where('role', 'teacher')
            ->get(['first_name', 'last_name', 'email']);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=teachers.csv',
        ];

        $callback = function() use ($teachers) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['First Name', 'Last Name', 'Username']);

            // Add teacher data
            foreach ($teachers as $teacher) {
                fputcsv($file, [
                    $teacher->first_name,
                    $teacher->last_name,
                    $teacher->email
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
    public function importTeachers(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            // Get the file path
            $filePath = $request->file('csv_file')->getPathname();

            // Read the entire file to detect format
            $fileContent = file_get_contents($filePath);

            // Normalize line endings
            $fileContent = str_replace(["\r\n", "\r"], "\n", $fileContent);

            // Parse CSV manually
            $lines = explode("\n", trim($fileContent));
            $headers = str_getcsv(array_shift($lines));

            // Normalize headers (remove quotes, trim spaces, convert to lowercase)
            $headers = array_map(function($header) {
                return strtolower(trim(str_replace('"', '', $header)));
            }, $headers);

            // Define expected headers and their variations
            $expectedHeaders = [
                'first_name' => ['first name', 'first_name', 'firstname', 'given name'],
                'last_name' => ['last name', 'last_name', 'lastname', 'surname', 'family name']
            ];

            // Map actual headers to standardized names
            $headerMap = [];
            foreach ($expectedHeaders as $standardHeader => $variations) {
                foreach ($variations as $variant) {
                    $index = array_search($variant, $headers);
                    if ($index !== false) {
                        $headerMap[$standardHeader] = $index;
                        break;
                    }
                }
            }

            // Check if we have both required headers
            if (!isset($headerMap['first_name']) || !isset($headerMap['last_name'])) {
                throw new \Exception('CSV must contain First Name and Last Name columns');
            }

            $importedCount = 0;
            $errors = [];

            foreach ($lines as $lineNumber => $line) {
                if (empty(trim($line))) continue;

                $values = str_getcsv($line);

                // Get values using the mapped indexes
                $firstName = trim($values[$headerMap['first_name']] ?? '');
                $lastName = trim($values[$headerMap['last_name']] ?? '');

                if (empty($firstName) || empty($lastName)) {
                    $errors[] = "Line " . ($lineNumber + 2) . ": First name and last name are required";
                    continue;
                }

                // Generate username (firstname.lastname)
                $username = strtolower(
                    str_replace(' ', '', $firstName) . '.' . 
                    str_replace(' ', '', $lastName)
                );

                try {
                    User::updateOrCreate(
                        ['email' => $username],
                        [
                            'name' => $firstName . ' ' . $lastName,
                            'first_name' => $firstName,
                            'last_name' => $lastName,
                            'email' => $username,
                            'role' => 'teacher',
                            'password' => Hash::make('abcde12345')
                        ]
                    );
                    $importedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Line " . ($lineNumber + 2) . ": " . $e->getMessage();
                }
            }

            $message = "Successfully imported $importedCount teachers.";
            if (!empty($errors)) {
                $message .= " " . count($errors) . " errors occurred.";
            }

            return redirect()->back()
                ->with('success', $message)
                ->with('errors', $errors);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing teachers: ' . $e->getMessage());
        }
    }

// Modify the importStudents method to check for duplicate LRNs
public function importStudents(Request $request)
{
    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt'
    ]);

    try {
        $filePath = $request->file('csv_file')->getPathname();
        $fileContent = file_get_contents($filePath);
        $fileContent = str_replace(["\r\n", "\r"], "\n", $fileContent);
        $lines = explode("\n", trim($fileContent));

        $headers = str_getcsv(array_shift($lines));
        $headers = array_map(function($header) {
            return trim(str_replace(['"', "'"], '', $header));
        }, $headers);

        $importedCount = 0;
        $duplicateCount = 0;
        $errors = [];

        foreach ($lines as $lineNumber => $line) {
            if (empty(trim($line))) continue;

            $values = str_getcsv($line);
            if (count($values) !== count($headers)) {
                $errors[] = "Line " . ($lineNumber + 2) . ": Column count doesn't match headers";
                continue;
            }

            $record = array_combine($headers, $values);

            $lrn = preg_replace('/[^0-9]/', '', $record['LRN'] ?? '');
            $firstName = trim($record['First Name'] ?? '');
            $lastName = trim($record['Last Name'] ?? '');
            $birthday = trim($record['Birthday'] ?? '');

            // Validate required fields
            if (empty($lrn)) {
                $errors[] = "Line " . ($lineNumber + 2) . ": LRN is required";
                continue;
            }

            if (empty($firstName) || empty($lastName)) {
                $errors[] = "Line " . ($lineNumber + 2) . ": First name and last name are required";
                continue;
            }

            if (empty($birthday)) {
                $errors[] = "Line " . ($lineNumber + 2) . ": Birthday is required";
                continue;
            }

            // Check if LRN already exists
            if (User::where('lrn', $lrn)->where('role', 'student')->exists()) {
                $duplicateCount++;
                continue;
            }

            // Parse birthday
            try {
                $birthdayObj = null;

                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $birthday)) {
                    $birthdayObj = Carbon::createFromFormat('m/d/Y', $birthday);
                } elseif (preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $birthday)) {
                    $birthdayObj = Carbon::createFromFormat('Y-m-d', $birthday);
                }

                if (!$birthdayObj) {
                    $birthdayObj = Carbon::parse($birthday);
                }
            } catch (\Exception $e) {
                $errors[] = "Line " . ($lineNumber + 2) . ": Invalid date format for birthday";
                continue;
            }

            $passwordFromBirthday = $birthdayObj->format('dmY');

            User::create([
                'name' => $firstName . ' ' . $lastName,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'lrn' => $lrn,
                'email' => $lrn,
                'birthday' => $birthdayObj->format('Y-m-d'),
                'role' => 'student',
                'password' => Hash::make($passwordFromBirthday)
            ]);

            $importedCount++;
        }

        $message = "Imported $importedCount students successfully.";
        if ($duplicateCount > 0) {
            $message .= " Skipped $duplicateCount duplicate LRNs.";
        }
        if (!empty($errors)) {
            $message .= " " . count($errors) . " errors occurred.";
        }

        return redirect()->back()
            ->with('success', $message)
            ->with('errors', $errors);

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Error importing students: ' . $e->getMessage());
    }
}
public function checkLrnUnique(Request $request)
{
    $request->validate([
        'lrn' => 'required|string|max:12',
        'user_id' => 'nullable|integer|exists:users,id'
    ]);

    $query = User::where('lrn', $request->lrn)
                ->where('role', 'student');

    if ($request->user_id) {
        $query->where('id', '!=', $request->user_id);
    }

    $exists = $query->exists();

    return response()->json([
        'unique' => !$exists
    ]);
}
public function showStudents()
{
    // Only allow admin access
    if (Auth::user()->role !== 'admin') {
        return redirect()->route('user.dashboard')->with('error', 'Unauthorized access.');
    }

    $users = User::all();
    return view('admin.addstudent', compact('users'));
}

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if ($request->role === 'teacher') {
            // Validate the request for teacher
            $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);

            // Generate username for teacher (firstname.lastname) without spaces
            $username = strtolower(str_replace(' ', '', $request->first_name) . '.' . $request->last_name);

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Store directly in the public folder
                $image->move(public_path('profile_images'), $imageName);
                $imagePath = 'profile_images/' . $imageName;
            }

            // Create the teacher user
            User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $username, // Just use the username without domain
                'role' => 'teacher',
                'password' => Hash::make($request->password),
                'profile_image' => $imagePath,
            ]);

            return redirect()->route('add.teacher')->with('success', 'Teacher created successfully.');

        } else if ($request->role === 'student') {
            $request->validate([
                'lrn' => ['required', 'string', 'max:12', 'unique:users'],
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'birthday' => ['required', 'date'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ]);
            if (User::where('lrn', $request->lrn)->where('role', 'student')->exists()) {
                return redirect()->back()->with('error', 'This LRN is already in use.');
            }
            // Format birthday for password (remove slashes and dashes)
            $birthdayObj = Carbon::parse($request->birthday);
            $passwordFromBirthday = $birthdayObj->format('dmY');

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Store directly in the public folder
                $image->move(public_path('profile_images'), $imageName);
                $imagePath = 'profile_images/' . $imageName;
            }

            // Create the student user
            User::create([
                'name' => $request->first_name . ' ' . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'lrn' => $request->lrn,
                'email' => $request->lrn, // Just use the LRN without domain
                'birthday' => $request->birthday,
                'role' => 'student',
                'password' => Hash::make($passwordFromBirthday),
                'profile_image' => $imagePath,
            ]);

            return redirect()->route('add.student')->with('success', 'Student created successfully.');
        }

        return redirect()->back()->with('error', 'Invalid user role specified.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // Return user data as JSON for AJAX request
        return response()->json($user);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->role === 'teacher') {
            // Validate the request for teacher
            $rules = [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = ['string', 'min:8'];
            }

            $request->validate($rules);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Store directly in the public folder
                $image->move(public_path('profile_images'), $imageName);
                $user->profile_image = 'profile_images/' . $imageName;
            }

            // Update user data
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->role = 'teacher';

            // Only update password if it's provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('add.teacher')->with('success', 'Teacher updated successfully.');

        } else if ($user->role === 'student') {
            // Validate the request for student
            $rules = [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'lrn' => ['required', 'string', 'max:12', Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    return $query->where('role', 'student');
                })],
                'birthday' => ['required', 'date'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = ['string', 'min:8'];
            }

            $request->validate($rules);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Store directly in the public folder
                $image->move(public_path('profile_images'), $imageName);
                $user->profile_image = 'profile_images/' . $imageName;
            }

            // Update user data
            $user->name = $request->first_name . ' ' . $request->last_name;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->lrn = $request->lrn;
            $user->email = $request->lrn; // Email is the same as LRN for students
            $user->birthday = $request->birthday;

            // Only update password if it's provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->route('add.student')->with('success', 'Student updated successfully.');

        } else {
            // For other roles (like admin), use the original update logic
            $rules = [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id],
                'role' => ['required', 'string', 'in:admin,teacher,student'],
                'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $rules['password'] = ['string', 'min:8'];
            }

            $request->validate($rules);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                    unlink(public_path($user->profile_image));
                }

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                // Store directly in the public folder
                $image->move(public_path('profile_images'), $imageName);
                $user->profile_image = 'profile_images/' . $imageName;
            }

            // Update user data
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;

            // Only update password if it's provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()->back()->with('success', 'User updated successfully.');
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Delete profile image if exists
        if ($user->profile_image && file_exists(public_path($user->profile_image))) {
            unlink(public_path($user->profile_image));
        }

        $user->delete();

        // Redirect based on user role
        if ($user->role === 'teacher') {
            return redirect()->route('add.teacher')->with('success', 'Teacher deleted successfully.');
        } else if ($user->role === 'student') {
            return redirect()->route('add.student')->with('success', 'Student deleted successfully.');
        }

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}

