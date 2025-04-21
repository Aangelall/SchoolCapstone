@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <!-- Header Section -->
    <div class="header">
        <h1>Teacher Management</h1>
        <div class="button-group">
            <button class="add-user-btn teacher-btn" onclick="openTeacherModal()">+ Add Teacher</button>
        </div>
    </div>

    <!-- Table Actions -->
    <div class="table-actions">
        <form action="{{ route('import.teachers') }}" method="POST" enctype="multipart/form-data" class="import-form">
            @csrf
            <div class="file-input-wrapper">
                <input type="file" name="csv_file" accept=".csv" class="file-input" id="teacher-csv-input">
                <label for="teacher-csv-input" class="file-input-label">
                    <i class='bx bx-upload'></i>
                    <span>Choose CSV</span>
                </label>
            </div>
            <span id="file-name" class="file-name">No file chosen</span>
            <button type="submit" class="import-btn" style="display:none;" id="import-btn">Import Teachers</button>
        </form>
    </div>

    <!-- Teachers Table -->
    <div class="section-header">
        <h2>Teachers</h2>
    </div>
    <div class="table-container">
        <table class="user-table">
        <thead>
    <tr>
        <th><input type="checkbox" id="select-all" class="checkbox"></th>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th>Username</th>
        <th>Role</th>
        <th>Date Created</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    @php $teacherIndex = 1; @endphp
    @foreach ($users as $user)
        @if ($user->role === 'teacher')
        <tr>
            <td><input type="checkbox" class="teacher-checkbox checkbox" value="{{ $user->id }}"></td>
            <td>{{ $teacherIndex++ }}</td>
            <td>
                @if($user->profile_image)
                    <img src="{{ asset($user->profile_image) }}" alt="Profile Image" class="profile-thumbnail">
                @else
                    <div class="profile-placeholder"><i class='bx bx-user'></i></div>
                @endif
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="role-badge teacher">{{ ucfirst($user->role) }}</span></td>
            <td>{{ $user->created_at->format('Y-m-d H:i:s') }}</td>
            <td>
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

    <!-- CSV Preview Table -->
    <div id="csv-preview" style="display:none; margin-top: 20px;">
        <h3>CSV Preview</h3>
        <table class="user-table" id="csv-table">
            <thead>
                <tr id="csv-header"></tr>
            </thead>
            <tbody id="csv-body"></tbody>
        </table>
    </div>
</div>
</div>

<!-- Create Teacher Modal -->
<div id="createTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeTeacherModal()">&times;</span>
        <h2 class="modal-title">Add Teacher Account</h2>
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" onsubmit="return validateCreateTeacherForm()">
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
                <div class="password-input-container">
                    <input type="password" id="teacher-password" name="password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility('teacher-password', this)">
                        <i class='bx bx-hide'></i>
                    </span>
                </div>
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

<!-- Edit Teacher Modal -->
<div id="editTeacherModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeEditTeacherModal()">&times;</span>
        <h2 class="modal-title">Edit Teacher</h2>
        <form id="editTeacherForm" method="POST" enctype="multipart/form-data" onsubmit="return validateEditTeacherForm()">
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
                <div class="password-input-container">
                    <input type="password" id="edit-teacher-password" name="password">
                    <span class="toggle-password" onclick="togglePasswordVisibility('edit-teacher-password', this)">
                        <i class='bx bx-hide'></i>
                    </span>
                </div>
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

<style>

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

    .teacher-btn:hover {
        background-color: #2d6b3c;
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

    .table-actions {
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        align-items: center;
    }

    .import-form {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .file-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .file-input-label {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .file-input-label:hover {
        background-color: #e9ecef;
    }

    .file-name {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .import-btn {
        white-space: nowrap;
    }

    .checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    #export-csv {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background-color: #3c8d50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    #export-csv:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    #export-csv:not(:disabled):hover {
        background-color: #2d6b3c;
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

    .password-input-container {
        position: relative;
    }
    
    .password-input-container input {
        padding-right: 40px;
    }
    
    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #666;
        z-index: 2;
    }
    
    .toggle-password:hover {
        color: #333;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 5px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File input handling
        const teacherFileInput = document.getElementById('teacher-csv-input');
        const teacherFileName = document.getElementById('file-name');
        if (teacherFileInput && teacherFileName) {
            teacherFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    teacherFileName.textContent = file.name;
                    previewCSV();
                } else {
                    teacherFileName.textContent = 'No file chosen';
                }
            });
        }

        const selectAllCheckbox = document.getElementById('select-all');
        const teacherCheckboxes = document.querySelectorAll('.teacher-checkbox');
        const exportButton = document.getElementById('export-csv');

        // Handle "Select All" checkbox
        selectAllCheckbox.addEventListener('change', function() {
            teacherCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateExportButton();
        });

        // Handle individual checkboxes
        teacherCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllCheckbox();
                updateExportButton();
            });
        });

        // Update "Select All" checkbox state
        function updateSelectAllCheckbox() {
            const checkedCount = document.querySelectorAll('.teacher-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === teacherCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < teacherCheckboxes.length;
        }

        // Update export button state
        function updateExportButton() {
            const checkedCount = document.querySelectorAll('.teacher-checkbox:checked').length;
            exportButton.disabled = checkedCount === 0;
        }

        // Handle export button click
        exportButton.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.teacher-checkbox:checked'))
                .map(checkbox => checkbox.value);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("export.teachers") }}';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_teachers[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        });
    });

    // Modal Functions
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
            document.getElementById('teacher-image-preview').style.backgroundImage = '';
            document.getElementById('teacher-image-preview').classList.remove('has-image');
            document.getElementById('teacher-image-preview').innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';
        }, 300);
    }

    function openEditTeacherModal(userId) {
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit-teacher-first-name').value = data.first_name || '';
                document.getElementById('edit-teacher-last-name').value = data.last_name || '';
                document.getElementById('edit-teacher-username').value = data.email || '';
                document.getElementById('editTeacherForm').action = `/users/${userId}`;

                const imagePreview = document.getElementById('edit-teacher-image-preview');
                imagePreview.style.backgroundImage = '';
                imagePreview.classList.remove('has-image');
                imagePreview.innerHTML = '<i class="bx bx-image-add"></i><span>Upload Image</span>';

                if (data.profile_image) {
                    imagePreview.style.backgroundImage = `url('/${data.profile_image}')`;
                    imagePreview.classList.add('has-image');
                    imagePreview.innerHTML = '';
                }

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

    // Password Visibility Toggle
    function togglePasswordVisibility(inputId, iconElement) {
        const input = document.getElementById(inputId);
        const icon = iconElement.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bx-hide', 'bx-show');
        } else {
            input.type = 'password';
            icon.classList.replace('bx-show', 'bx-hide');
        }
    }

    // Form Validation
    function validateCreateTeacherForm() {
        const password = document.getElementById('teacher-password').value;
        
        if (password.length < 8) {
            alert('Password must be at least 8 characters');
            return false;
        }
        
        return true;
    }

    function validateEditTeacherForm() {
        const password = document.getElementById('edit-teacher-password').value;
        
        // Only validate if password is being changed (not empty)
        if(password && password.length < 8) {
            alert('Password must be at least 8 characters');
            return false;
        }
        
        return true;
    }

    // Image Preview Function
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

    // Delete Confirmation
    function confirmDelete(userId) {
        if (confirm('Are you sure you want to delete this user?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/users/${userId}`;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);

            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            form.appendChild(methodField);

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modals when clicking outside or pressing Escape
    document.addEventListener('click', function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                if (modal.id === 'createTeacherModal') {
                    closeTeacherModal();
                } else if (modal.id === 'editTeacherModal') {
                    closeEditTeacherModal();
                }
            }
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (modal.classList.contains('show')) {
                    if (modal.id === 'createTeacherModal') {
                        closeTeacherModal();
                    } else if (modal.id === 'editTeacherModal') {
                        closeEditTeacherModal();
                    }
                }
            });
        }
    });

    function previewCSV() {
        const fileInput = document.getElementById('teacher-csv-input');
        const file = fileInput.files[0];
        if (!file) {
            alert('Please select a CSV file first.');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const text = e.target.result;
            const lines = text.split('\n');
            const headers = lines[0].split(',');

            const headerRow = document.getElementById('csv-header');
            headerRow.innerHTML = '';
            headers.forEach(header => {
                const th = document.createElement('th');
                th.textContent = header.trim();
                headerRow.appendChild(th);
            });

            const body = document.getElementById('csv-body');
            body.innerHTML = '';
            for (let i = 1; i < lines.length; i++) {
                const row = lines[i].split(',');
                if (row.length !== headers.length) continue;
                const tr = document.createElement('tr');
                row.forEach(cell => {
                    const td = document.createElement('td');
                    td.textContent = cell.trim();
                    tr.appendChild(td);
                });
                body.appendChild(tr);
            }

            document.getElementById('csv-preview').style.display = 'block';
            document.getElementById('import-btn').style.display = 'inline-block';
        };
        reader.readAsText(file);
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filteredStudents = currentStudents.filter(student =>
            student.lrn.toLowerCase().includes(searchTerm) ||
            student.name.toLowerCase().includes(searchTerm) ||
            student.firstName.toLowerCase().includes(searchTerm) || // Assuming you have a firstName field
            student.lastName.toLowerCase().includes(searchTerm)     // Assuming you have a lastName field
        );
        updateTableWithStudents(filteredStudents);
    });
</script>
@endsection