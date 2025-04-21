@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <div class="header">
        <h1>User Management</h1>
        <div class="button-group">
            <button class="add-user-btn teacher-btn" onclick="openTeacherModal()">+ Add Teacher</button>
            <button class="add-user-btn student-btn" onclick="openStudentModal()">+ Add Student</button>
        </div>
    </div>

    <!-- Teachers Table -->
    <div class="section-header">
        <h2>Teachers</h2>
    </div>
    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $teacherIndex = 1; @endphp
                @foreach ($users as $user)
                    @if ($user->role === 'teacher')
                    <tr>
                        <td>{{ $teacherIndex++ }}</td>
                        <td>
                            @if($user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="profile-thumbnail">
                            @else
                                <div class="profile-placeholder">
                                    <i class='bx bx-user'></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="role-badge teacher">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>••••••••</td> <!-- Password is hidden for security -->
                        <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button class="action-btn edit-btn" onclick="openEditTeacherModal({{ $user->id }})" title="Edit">
                                    <i class='bx bx-edit-alt'></i>
                                </button>
                                <button class="action-btn delete-btn" onclick="confirmDelete({{ $user->id }})" title="Delete">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Students Table -->
    <div class="section-header">
        <h2>Students</h2>
    </div>
    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>LRN</th>
                    <th>Birthday</th>
                    <th>Role</th>
                    <th>Password</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @php $studentIndex = 1; @endphp
                @foreach ($users as $user)
                    @if ($user->role === 'student')
                    <tr>
                        <td>{{ $studentIndex++ }}</td>
                        <td>
                            @if($user->profile_image)
                                <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="profile-thumbnail">
                            @else
                                <div class="profile-placeholder">
                                    <i class='bx bx-user'></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->lrn }}</td>
                        <td>{{ $user->birthday ? $user->birthday->format('m/d/Y') : 'N/A' }}</td>
                        <td>
                            <span class="role-badge student">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>••••••••</td> <!-- Password is hidden for security -->
                        <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <button class="action-btn edit-btn" onclick="openEditStudentModal({{ $user->id }})" title="Edit">
                                    <i class='bx bx-edit-alt'></i>
                                </button>
                                <button class="action-btn delete-btn" onclick="confirmDelete({{ $user->id }})" title="Delete">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</div>

<!-- Modal for Creating a Teacher -->
<div id="createTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeTeacherModal()">&times;</span>
        <h2 class="modal-title">Create Teacher</h2>
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="teacher-first-name">First Name</label>
                    <input type="text" id="teacher-first-name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="teacher-last-name">Last Name</label>
                    <input type="text" id="teacher-last-name" name="last_name" required>
                </div>
            </div>
            <input type="hidden" name="role" value="teacher">
            <div class="form-group">
                <label for="teacher-password">Password</label>
                <input type="password" id="teacher-password" name="password" required>
            </div>
            <div class="form-group">
                <label for="teacher-image">Profile Image</label>
                <div class="image-upload-container">
                    <div class="image-preview" id="teacher-image-preview">
                        <i class='bx bx-image-add'></i>
                        <span>Upload Image</span>
                    </div>
                    <input type="file" id="teacher-image" name="image" accept="image/*" onchange="previewImage(this, 'teacher-image-preview')">
                </div>
            </div>
            <div class="form-group">
                <p class="text-sm text-gray-600">Username will be automatically generated as firstname.lastname (spaces removed)</p>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeTeacherModal()">Cancel</button>
                <button type="submit" class="submit-btn">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Creating a Student -->
<div id="createStudentModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeStudentModal()">&times;</span>
        <h2 class="modal-title">Create Student</h2>
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="student-lrn">LRN</label>
                <input type="text" id="student-lrn" name="lrn" required maxlength="12">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="student-first-name">First Name</label>
                    <input type="text" id="student-first-name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="student-last-name">Last Name</label>
                    <input type="text" id="student-last-name" name="last_name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="student-birthday">Birthday</label>
                <input type="date" id="student-birthday" name="birthday" required>
            </div>
            <input type="hidden" name="role" value="student">
            <div class="form-group">
                <label for="student-image">Profile Image</label>
                <div class="image-upload-container">
                    <div class="image-preview" id="student-image-preview">
                        <i class='bx bx-image-add'></i>
                        <span>Upload Image</span>
                    </div>
                    <input type="file" id="student-image" name="image" accept="image/*" onchange="previewImage(this, 'student-image-preview')">
                </div>
            </div>
            <div class="form-group">
                <p class="text-sm text-gray-600">Username will be the LRN and password will be the birthday (DDMMYYYY format)</p>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeStudentModal()">Cancel</button>
                <button type="submit" class="submit-btn">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Editing a Teacher -->
<div id="editTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditTeacherModal()">&times;</span>
        <h2 class="modal-title">Edit Teacher</h2>
        <form id="editTeacherForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group">
                    <label for="edit-teacher-first-name">First Name</label>
                    <input type="text" id="edit-teacher-first-name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="edit-teacher-last-name">Last Name</label>
                    <input type="text" id="edit-teacher-last-name" name="last_name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="edit-teacher-username">Username</label>
                <input type="text" id="edit-teacher-username" name="email" required>
                <p class="text-sm text-gray-600">Username format: firstname.lastname (no spaces)</p>
            </div>
            <input type="hidden" name="role" value="teacher">
            <div class="form-group">
                <label for="edit-teacher-password">Password (leave blank to keep current)</label>
                <input type="password" id="edit-teacher-password" name="password">
            </div>
            <div class="form-group">
                <label for="edit-teacher-image">Profile Image</label>
                <div class="image-upload-container">
                    <div class="image-preview" id="edit-teacher-image-preview">
                        <i class='bx bx-image-add'></i>
                        <span>Upload Image</span>
                    </div>
                    <input type="file" id="edit-teacher-image" name="image" accept="image/*" onchange="previewImage(this, 'edit-teacher-image-preview')">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeEditTeacherModal()">Cancel</button>
                <button type="submit" class="submit-btn">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal for Editing a Student -->
<div id="editStudentModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditStudentModal()">&times;</span>
        <h2 class="modal-title">Edit Student</h2>
        <form id="editStudentForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit-student-lrn">LRN</label>
                <input type="text" id="edit-student-lrn" name="lrn" required maxlength="12">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="edit-student-first-name">First Name</label>
                    <input type="text" id="edit-student-first-name" name="first_name" required>
                </div>
                <div class="form-group">
                    <label for="edit-student-last-name">Last Name</label>
                    <input type="text" id="edit-student-last-name" name="last_name" required>
                </div>
            </div>
            <div class="form-group">
                <label for="edit-student-birthday">Birthday</label>
                <input type="date" id="edit-student-birthday" name="birthday" required>
            </div>
            <input type="hidden" name="role" value="student">
            <div class="form-group">
                <label for="edit-student-password">Password (leave blank to keep current)</label>
                <input type="password" id="edit-student-password" name="password">
                <p class="text-sm text-gray-600">Default password is birthday in DDMMYYYY format</p>
            </div>
            <div class="form-group">
                <label for="edit-student-image">Profile Image</label>
                <div class="image-upload-container">
                    <div class="image-preview" id="edit-student-image-preview">
                        <i class='bx bx-image-add'></i>
                        <span>Upload Image</span>
                    </div>
                    <input type="file" id="edit-student-image" name="image" accept="image/*" onchange="previewImage(this, 'edit-student-image-preview')">
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="cancel-btn" onclick="closeEditStudentModal()">Cancel</button>
                <button type="submit" class="submit-btn">Update</button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Custom CSS for the User Management Page */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .header h1 {
        font-size: 24px;
        color: #333;
    }

    .button-group {
        display: flex;
        gap: 10px;
    }

    .add-user-btn {
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    .teacher-btn {
        background-color: #3c8d50;
    }

    .student-btn {
        background-color: #007bff;
    }

    .teacher-btn:hover {
        background-color: #2d6b3c;
    }

    .student-btn:hover {
        background-color: #0056b3;
    }

    .section-header {
        margin: 30px 0 15px 0;
        border-bottom: 2px solid #3c8d50;
        padding-bottom: 10px;
    }

    .section-header h2 {
        font-size: 20px;
        color: #3c8d50;
    }

    .table-container {
        overflow-x: auto;
        margin-bottom: 30px;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .user-table th, .user-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .user-table th {
        background-color: #f8f9fa;
        font-weight: bold;
        color: #333;
    }

    .user-table tbody tr:hover {
        background-color: #f1f1f1;
    }

    .role-badge {
        padding: 5px 10px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: bold;
    }

    .role-badge.teacher {
        background-color: #3c8d50;
        color: white;
    }

    .role-badge.student {
        background-color: #007bff;
        color: white;
    }

    /* Profile Image Styles */
    .profile-thumbnail {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #3c8d50;
    }

    .profile-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #6c757d;
    }

    .image-upload-container {
        position: relative;
        width: 100%;
        margin-top: 8px;
    }

    .image-preview {
        width: 100%;
        height: 150px;
        border: 2px dashed #ddd;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        transition: all 0.3s ease;
    }

    .image-preview i {
        font-size: 36px;
        color: #6c757d;
        margin-bottom: 8px;
    }

    .image-preview span {
        color: #6c757d;
    }

    .image-preview.has-image i,
    .image-preview.has-image span {
        display: none;
    }

    input[type="file"] {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Action Buttons Styles */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        color: white;
        font-size: 18px;
    }

    .edit-btn {
        background-color: #4a6cf7;
    }

    .delete-btn {
        background-color: #dc3545;
    }

    .edit-btn:hover {
        background-color: #3151d3;
        transform: translateY(-2px);
    }

    .delete-btn:hover {
        background-color: #bd2130;
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .modal.show {
        opacity: 1;
        visibility: visible;
    }

    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 12px;
        width: 90%;
        max-width: 550px;
        position: relative;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal.show .modal-content {
        transform: translateY(0);
    }

    .modal-title {
        text-align: center;
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        transition: color 0.3s ease;
    }

    .close-btn:hover {
        color: #000;
    }

    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    @media (max-width: 768px) {
        .form-row {
            flex-direction: column;
            gap: 0;
        }
    }

    .form-group {
        margin-bottom: 20px;
        width: 100%;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #333;
    }

    .form-group input, .form-group select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 16px;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-group input:focus, .form-group select:focus {
        border-color: #4a6cf7;
        box-shadow: 0 0 0 3px rgba(74, 108, 247, 0.2);
        outline: none;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .text-gray-600 {
        color: #718096;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    .submit-btn, .cancel-btn {
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .submit-btn {
        background-color: #3c8d50;
        color: white;
        border: none;
    }

    .cancel-btn {
        background-color: #f8f9fa;
        color: #333;
        border: 1px solid #ddd;
    }

    .submit-btn:hover {
        background-color: #2d6b3c;
        transform: translateY(-2px);
    }

    .cancel-btn:hover {
        background-color: #e9ecef;
        transform: translateY(-2px);
    }
</style>

<script>
    // JavaScript to handle modal open/close for Teacher
    function openTeacherModal() {
        const modal = document.getElementById('createTeacherModal');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }

    function closeTeacherModal() {
        const modal = document.getElementById('createTeacherModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            // Reset form and image preview
            document.getElementById('teacher-image-preview').style.backgroundImage = '';
            document.getElementById('teacher-image-preview').classList.remove('has-image');
            document.getElementById('teacher-image-preview').innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';
        }, 300);
    }

    // JavaScript to handle modal open/close for Student
    function openStudentModal() {
        const modal = document.getElementById('createStudentModal');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
    }

    function closeStudentModal() {
        const modal = document.getElementById('createStudentModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            // Reset form and image preview
            document.getElementById('student-image-preview').style.backgroundImage = '';
            document.getElementById('student-image-preview').classList.remove('has-image');
            document.getElementById('student-image-preview').innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';
        }, 300);
    }

    // JavaScript to handle edit modal for Teacher
    function openEditTeacherModal(userId) {
        // Fetch user data and populate the edit form
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields
                document.getElementById('edit-teacher-first-name').value = data.first_name || '';
                document.getElementById('edit-teacher-last-name').value = data.last_name || '';
                document.getElementById('edit-teacher-username').value = data.email || '';

                // Set the form action dynamically
                document.getElementById('editTeacherForm').action = `/users/${userId}`;

                // Reset image preview
                const imagePreview = document.getElementById('edit-teacher-image-preview');
                imagePreview.style.backgroundImage = '';
                imagePreview.classList.remove('has-image');
                imagePreview.innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';

                // If user has a profile image, show it in the preview
                if (data.profile_image) {
                    imagePreview.style.backgroundImage = `url('/${data.profile_image}')`;
                    imagePreview.classList.add('has-image');
                    imagePreview.innerHTML = '';
                }

                // Display the edit modal
                const modal = document.getElementById('editTeacherModal');
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    }

    function closeEditTeacherModal() {
        const modal = document.getElementById('editTeacherModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // JavaScript to handle edit modal for Student
    function openEditStudentModal(userId) {
        // Fetch user data and populate the edit form
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate the form fields
                document.getElementById('edit-student-first-name').value = data.first_name || '';
                document.getElementById('edit-student-last-name').value = data.last_name || '';
                document.getElementById('edit-student-lrn').value = data.lrn || data.email || '';

                // Format birthday for the date input if available
                if (data.birthday) {
                    const birthdayDate = new Date(data.birthday);
                    const formattedDate = birthdayDate.toISOString().split('T')[0];
                    document.getElementById('edit-student-birthday').value = formattedDate;
                }

                // Set the form action dynamically
                document.getElementById('editStudentForm').action = `/users/${userId}`;

                // Reset image preview
                const imagePreview = document.getElementById('edit-student-image-preview');
                imagePreview.style.backgroundImage = '';
                imagePreview.classList.remove('has-image');
                imagePreview.innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';

                // If user has a profile image, show it in the preview
                if (data.profile_image) {
                    imagePreview.style.backgroundImage = `url('/${data.profile_image}')`;
                    imagePreview.classList.add('has-image');
                    imagePreview.innerHTML = '';
                }

                // Display the edit modal
                const modal = document.getElementById('editStudentModal');
                modal.style.display = 'flex';
                setTimeout(() => {
                    modal.classList.add('show');
                }, 10);
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    }

    function closeEditStudentModal() {
        const modal = document.getElementById('editStudentModal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Function to preview uploaded image
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.style.backgroundImage = `url('${e.target.result}')`;
                preview.classList.add('has-image');
                preview.innerHTML = '';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = '';
            preview.classList.remove('has-image');
            preview.innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';
        }
    }

    // Function to confirm deletion
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            // Create a form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/${userId}`;

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            // Add method field
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            // Append to body and submit
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside of modal content
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                if (modal.id === 'createTeacherModal') {
                    closeTeacherModal();
                } else if (modal.id === 'createStudentModal') {
                    closeStudentModal();
                } else if (modal.id === 'editTeacherModal') {
                    closeEditTeacherModal();
                } else if (modal.id === 'editStudentModal') {
                    closeEditStudentModal();
                }
            }
        });
    });

    // Add escape key listener to close modals
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.classList.contains('show')) {
                    if (modal.id === 'createTeacherModal') {
                        closeTeacherModal();
                    } else if (modal.id === 'createStudentModal') {
                        closeStudentModal();
                    } else if (modal.id === 'editTeacherModal') {
                        closeEditTeacherModal();
                    } else if (modal.id === 'editStudentModal') {
                        closeEditStudentModal();
                    }
                }
            });
        }
    });
</script>
@endsection
