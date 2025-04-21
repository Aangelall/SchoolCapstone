@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="main-card">
        <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1>Add Senior High Class</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('section.subject') }}">Sections & Subjects</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add Senior High Class</li>
                    </ol>
                </nav>
            </div>
            <button type="button" id="edit-strand-section-btn" class="btn btn-primary">
                <i class='bx bx-edit'></i> Edit Strand and Section
            </button>
        </div>

        <div class="form-container">
            <form id="add-class-form" action="{{ route('store.senior.class') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Class Information Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Class Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade_level">Grade Level</label>
                            <select class="form-control" id="grade_level" name="grade_level" required>
                                <option value="">Select Grade Level</option>
                                <option value="11">Grade 11</option>
                                <option value="12">Grade 12</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="strand">Strand</label>
                            <select id="strand" name="strand" class="form-control" required>
                                <option value="" selected disabled>Select Strand</option>
                                @foreach($strands as $strand)
                                    <option value="{{ $strand->name }}">{{ $strand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="semester">Semester</label>
                            <select id="semester" name="semester" class="form-control" required>
                                <option value="" selected disabled>Select Semester</option>
                                <option value="1">1st Semester</option>
                                <option value="2">2nd Semester</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section">Section</label>
                            <select id="section" name="section" class="form-control" required>
                                <option value="" selected disabled>Loading sections...</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="adviser">Class Adviser</label>
                        <select id="adviser" name="adviser_id" class="form-control" required>
                            <option value="" selected disabled>Select Class Adviser</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Subjects Section - Updated Structure -->
                <div class="form-section">
                    <div class="subjects-header">
                        <h2 class="form-section-title">Subjects</h2>
                        <button type="button" id="add-subject-btn" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Add Subject
                        </button>
                    </div>

                    <div id="subjects-container">
                        <div class="subject-list-container">
                            <div class="subject-list-header">
                                <div class="subject-column">Subject</div>
                                <div class="teacher-column">Teacher</div>
                                <div class="action-column">Action</div>
                            </div>
                            <div class="subject-list-body">
                                <!-- Initial subject row will be added here -->
                            </div>
                        </div>
                    </div>
                    
                    <template id="subject-row-template">
                        <div class="subject-row">
                            <div class="subject-column">
                                <input type="text" class="form-control subject-input" 
                                       name="subjects[][name]" placeholder="Enter subject name" required>
                            </div>
                            <div class="teacher-column">
                                <select class="form-control teacher-select" name="subjects[][teacher_id]" required>
                                    <option value="" selected disabled>Select Teacher</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="action-column">
                                <button type="button" class="btn btn-danger remove-subject-btn">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Student Promoted Section -->
                <div class="form-section" id="promoted-students-section" style="display: none;">
                    <h2 class="form-section-title">Student Promoted</h2>
                    <div id="promoted-students-container">
                        <!-- Section groups will be loaded here -->
                    </div>
                    <div class="form-actions" style="margin-top: 10px;">
                        <button type="button" id="add-selected-promoted" class="btn btn-primary">
                            <i class='bx bx-plus'></i> Add Selected Students to Class
                        </button>
                    </div>
                </div>

                <!-- Selected Students Section -->
                <div class="form-section" id="selected-students-section" style="display: none;">
                    <h2 class="form-section-title">Selected Students</h2>
                    <div id="selected-students-container">
                        <div class="table-responsive">
                            <table class="student-table" id="selected-students-table">
                                <thead>
                                    <tr>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Previous Class</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="selected-students-body">
                                    <!-- Selected students will be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- CSV Upload Section for Grade 11, 1st Semester -->
                <div class="form-section" id="csvUploadSection" style="display: none;">
                    <h2 class="form-section-title">Upload Student List</h2>
                    <div class="form-group">
                        <label for="student_list">Upload Student List (CSV)</label>
                        <input type="file" class="form-control-file" id="student_list" name="student_list" accept=".csv">
                        <small class="form-text text-muted">
                            Upload a CSV file containing student information. Required columns: LRN, First Name, Last Name, Birthdate
                        </small>
                    </div>
                </div>

                <!-- Student Selection Section -->
                <div class="form-group" id="studentSelectionSection">
                    <label>Select Students</label>
                    <div id="studentList" class="border p-3 rounded" style="max-height: 300px; overflow-y: auto;">
                        <!-- Students will be loaded here -->
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<!-- Adviser Warning Modal -->
<div id="adviser-warning-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Warning</h2>
            <button class="close-modal" onclick="closeAdviserWarningModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>This teacher is already assigned as a class adviser. Please select another teacher.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeAdviserWarningModal()">OK</button>
        </div>
    </div>
</div>

<!-- Edit Strand and Section Modal -->
<div id="edit-strand-section-modal" class="modal">
    <div class="modal-content sections-modal">
        <div class="modal-header">
            <h2>Edit Strands and Sections</h2>
            <button class="close-modal" onclick="closeEditStrandSectionModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="add-strand-container">
                <button class="btn btn-primary add-strand-btn" onclick="openAddStrandModal()">
                    <i class='bx bx-plus'></i> Add Strand
                </button>
            </div>
            <div class="sections-container">
                <!-- Grade 11 -->
                <div class="grade-section" data-grade="11">
                    <h3>Grade 11</h3>
                    <div class="strands-container">
                        <!-- Strands will be loaded here -->
                    </div>
                </div>
                
                <!-- Grade 12 -->
                <div class="grade-section" data-grade="12">
                    <h3>Grade 12</h3>
                    <div class="strands-container">
                        <!-- Strands will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeEditStrandSectionModal()">Cancel</button>
            <button class="btn btn-primary" onclick="closeEditStrandSectionModal()">Done</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-confirmation-modal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Confirm Deletion</h2>
            <button class="close-modal" onclick="closeDeleteConfirmationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this item?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteConfirmationModal()">Cancel</button>
            <button class="btn btn-danger" onclick="deleteItem()">Delete</button>
        </div>
    </div>
</div>

<!-- Add Strand Modal -->
<div id="add-strand-modal" class="modal">
    <div class="modal-content" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Add New Strand</h2>
            <button class="close-modal" onclick="closeAddStrandModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="new-strand-name">Strand Name</label>
                <input type="text" id="new-strand-name" class="form-control" placeholder="Enter strand name" required>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeAddStrandModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveNewStrand()">Add Strand</button>
        </div>
    </div>
</div>

<style>
    /* General Styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px;
    }
    .student-promoted-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    .promoted-student-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .promoted-student-row:hover {
        background-color: #e8f4fc !important;
    }

    .promoted-student-row:active {
        background-color: #d0e8ff !important;
    }
    .student-promoted-table th,
    .student-promoted-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .student-promoted-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
    }

    .student-promoted-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    .main-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 24px;
    }

    /* Header Styles */
    .section-header {
        margin-bottom: 24px;
    }

    .section-header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 0 0 8px 0;
    }

    .breadcrumb {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        font-size: 14px;
    }

    .breadcrumb-item {
        color: #666;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        content: "/";
        padding: 0 8px;
        color: #ccc;
    }

    .breadcrumb-item a {
        color: #00b050;
        text-decoration: none;
    }

    .breadcrumb-item a:hover {
        text-decoration: underline;
    }

    .breadcrumb-item.active {
        color: #666;
    }

    /* Form Styles */
    .form-container {
        margin-top: 24px;
    }

    .form-section {
        margin-bottom: 32px;
        padding: 24px;
        background-color: #f9f9f9;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .form-section-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0 0 20px 0;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .form-row {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 16px;
    }

    @media (min-width: 768px) {
        .form-row {
            flex-direction: row;
        }
    }

    .form-group {
        margin-bottom: 16px;
        flex: 1;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 8px;
        color: #333;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #00b050;
        box-shadow: 0 0 0 3px rgba(0, 176, 80, 0.2);
    }

    /* Subjects Section Styles - Updated */
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .subject-list-container {
        background-color: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .subject-list-header {
        display: flex;
        background-color: #f5f5f5;
        padding: 12px 16px;
        font-weight: 500;
        border-bottom: 1px solid #e0e0e0;
    }

    .subject-column {
        flex: 2;
        padding: 0 8px;
    }

    .teacher-column {
        flex: 2;
        padding: 0 8px;
    }

    .action-column {
        flex: 0.5;
        text-align: center;
        padding: 0 8px;
    }

    .subject-list-body {
        max-height: 300px;
        overflow-y: auto;
    }

    .subject-row {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #f0f0f0;
    }

    .subject-row:last-child {
        border-bottom: none;
    }

    .subject-row .form-control {
        margin-bottom: 0;
        width: 100%;
    }

    .remove-subject-btn {
        padding: 8px;
        height: 38px;
    }

    /* Student Selection Styles */
    .student-selection-container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
    }

    .student-search {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
    }

    .student-search input {
        flex: 1;
    }

    .student-lists {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .available-students,
    .selected-students {
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 6px;
    }

    .available-students h3,
    .selected-students h3 {
        font-size: 16px;
        margin-bottom: 12px;
        color: #333;
    }

    #available-students-list {
        height: 300px;
        overflow-y: auto;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    /* Add this to your existing CSS */
    .student-selection-container {
        margin-top: 16px;
    }

    .student-search {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 16px;
    }

    @media (min-width: 768px) {
        .student-search {
            flex-direction: row;
        }
    }

    .student-lists {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    @media (min-width: 768px) {
        .student-lists {
            flex-direction: row;
        }
    }

    .available-students,
    .selected-students {
        flex: 1;
        background-color: #f9f9f9;
        padding: 16px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    #available-students-list {
        width: 100%;
        height: 200px;
        overflow-y: auto;
        margin-bottom: 12px;
    }

    @media (min-width: 768px) {
        #available-students-list {
            height: 300px;
        }
    }

    #selected-students-table {
        overflow-x: auto;
    }

    #selected-students-table table {
        width: 100%;
        min-width: 300px;
    }

    #selected-students-table th,
    #selected-students-table td {
        padding: 8px 12px;
        white-space: nowrap;
    }

    #selected-students-table th {
        background-color: #f5f5f5;
        font-weight: 500;
    }

    #selected-students-table .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }
    .table th,
    .table td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f5f5f5;
        font-weight: 600;
    }

    /* Button Styles */
    .btn {
        padding: 10px 16px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn:focus {
        outline: none;
    }

    .btn-primary {
        background-color: #00b050;
        color: white;
    }

    .btn-primary:hover {
        background-color: #009040;
    }

    .btn-secondary {
        background-color: #e0e0e0;
        color: #333;
    }

    .btn-secondary:hover {
        background-color: #d0d0d0;
    }

    .btn-danger {
        background-color: #e53e3e;
        color: white;
    }

    .btn-danger:hover {
        background-color: #c53030;
    }

    .btn-sm {
        padding: 4px 8px;
        font-size: 12px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 24px;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: white;
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        position: relative;
        width: 90%;
        max-width: 450px;
        margin: 20px auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .modal-body {
        margin-bottom: 12px;
        max-height: 400px;
        overflow-y: auto;
        padding-right: 5px;
    }

    /* Add custom scrollbar */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    .modal-footer {
        padding-top: 12px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
        padding: 0;
        line-height: 1;
    }

    .close-modal:hover {
        color: #333;
    }
    /* Strand and Section Modal Styles */
    .sections-modal {
        max-width: 600px;
        width: 90%;
    }

    .grade-section {
        margin-bottom: 16px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 8px;
    }

    .grade-section h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 1px solid #ddd;
        padding-bottom: 8px;
        font-size: 15px;
    }

    .grade-section:last-child {
        margin-bottom: 5px;
    }

    .strands-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 8px;
    }

    .add-strand-container {
        margin-bottom: 12px;
        text-align: right;
        position: sticky;
        top: 0;
        background-color: white;
        padding: 5px 0;
        z-index: 10;
    }

    .strand-item {
        background-color: white;
        padding: 12px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        margin-bottom: 10px;
    }

    .strand-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .strand-header h4 {
        margin: 0;
        color: #333;
        font-size: 16px;
    }

    .section-items {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 8px;
    }

    .section-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .section-input {
        flex: 1;
        padding: 6px 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .add-section-btn {
        display: flex;
        align-items: center;
        gap: 6px;
        background: none;
        border: none;
        color: #00b050;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 4px;
        transition: background-color 0.2s;
        font-size: 13px;
    }

    .add-section-btn:hover {
        background-color: #e8f8ee;
    }

    .remove-section-btn {
        background: none;
        border: none;
        color: #e53e3e;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .remove-section-btn:hover {
        background-color: #fce8e8;
    }

    .remove-strand-btn {
        padding: 4px 8px;
        font-size: 12px;
    }

    /* Promoted Students Styles */
    .section-group-container {
        margin-bottom: 30px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .section-group-title {
        padding: 12px 16px;
        background-color: #f0f8f0;
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #00b050;
        border-bottom: 1px solid #e0e0e0;
    }

    .student-checkbox {
        transform: scale(1.2);
        margin: 0;
        cursor: pointer;
    }

    .section-select-all {
        transform: scale(1.2);
        margin: 0;
        cursor: pointer;
    }

    .promoted-student-row {
        cursor: pointer;
    }
    
    .promoted-student-row:hover {
        background-color: #f5f5f5;
    }

    .promoted-student-row.selected {
        background-color: #e8f4fc;
    }

    .bulk-actions {
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        margin-bottom: 20px;
    }

    .student-promoted-table {
        width: 100%;
        border-collapse: collapse;
    }

    .student-promoted-table th,
    .student-promoted-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .student-promoted-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
    }

    /* Badge Styles */
    .promoted-badge {
        display: inline-block;
        padding: 2px 6px;
        background-color: #fde68a;
        color: #92400e;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 8px;
    }

    .not-assigned-badge {
        display: inline-block;
        padding: 2px 6px;
        background-color: #fee2e2;
        color: #b91c1c;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }
</style>

<script>
console.log('Script initialized');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded');
    
    // Check if the edit-strand-section-btn exists
    const editStrandSectionBtn = document.getElementById('edit-strand-section-btn');
    if (editStrandSectionBtn) {
        console.log('Edit Strand and Section button found');
        // Add a direct event listener to ensure it works
        editStrandSectionBtn.addEventListener('click', function() {
            console.log('Edit Strand and Section button clicked');
            openEditStrandSectionModal();
        });
    } else {
        console.error('Edit Strand and Section button not found');
    }
    
    // DOM Elements
    const form = document.getElementById('add-class-form');
    const addSubjectBtn = document.getElementById('add-subject-btn');
    const subjectsContainer = document.querySelector('.subject-list-body');
    const gradeLevelSelect = document.getElementById('grade_level');
    const strandSelect = document.getElementById('strand');
    const sectionSelect = document.getElementById('section');
    const semesterSelect = document.getElementById('semester');
    const adviserSelect = document.getElementById('adviser');
    const availableStudentsList = document.getElementById('available-students-list');
    const selectedStudentsBody = document.getElementById('selected-students-body');
    
    let subjectCount = 0;

    // Initialize with one empty subject row
    addSubjectRow();

    // Event Listeners
    gradeLevelSelect.addEventListener('change', function() {
        initializeStudentSelection();
        updateSectionOptions();
        loadSubjectsForSelection();
    });
    
    strandSelect.addEventListener('change', function() {
        updateSectionOptions();
        loadSubjectsForSelection();
    });
    
    semesterSelect.addEventListener('change', loadSubjectsForSelection);
    
    addSubjectBtn.addEventListener('click', addSubjectRow);
    
    document.getElementById('add-selected-student')?.addEventListener('click', addSelectedStudents);
    document.getElementById('selected-students-body')?.addEventListener('click', removeSelectedStudent);
    document.getElementById('student-search')?.addEventListener('input', searchStudents);
    
    adviserSelect.addEventListener('change', checkAdviserAvailability);
    
    form.addEventListener('submit', handleFormSubmit);
    
    document.querySelectorAll('.promoted-student-row').forEach(row => {
        row.addEventListener('click', handlePromotedStudentClick);
    });

    // Auto-load subjects when all three are selected
    async function loadSubjectsForSelection() {
        const grade = gradeLevelSelect.value;
        const strand = strandSelect.value;
        const semester = semesterSelect.value;
        
        if (!grade || !strand || !semester) return;
        
        try {
            // Show loading state
            subjectsContainer.innerHTML = '<div class="text-center p-3">Loading subjects...</div>';
            
            // Fetch subjects for this combination
            const response = await fetch(`/subjects-by-group?grade=${grade}&strand=${encodeURIComponent(strand)}&semester=${semester}`);
            if (!response.ok) throw new Error('Failed to fetch subjects');
            
            const subjects = await response.json();
            
            // Clear existing subjects
            subjectsContainer.innerHTML = '';
            subjectCount = 0;
            
            if (subjects.length > 0) {
                subjects.forEach(subject => {
                    addSubjectRow(subject);
                });
                // Add an empty row at the end for new subjects
                addEmptySubjectRow();
            } else {
                // Add one empty row if no subjects found
                addEmptySubjectRow();
            }
        } catch (error) {
            console.error('Error loading subjects:', error);
            // Fallback to one empty row
            subjectsContainer.innerHTML = '';
            addEmptySubjectRow();
        }
    }

    // Add a new subject row with optional existing subject data
    function addSubjectRow(existingSubject = null) {
        subjectCount++;
        
        const template = document.getElementById('subject-row-template');
        const newSubjectRow = template.content.cloneNode(true);
        
        // Update names with current subjectCount
        const subjectInput = newSubjectRow.querySelector('.subject-input');
        subjectInput.name = `subjects[${subjectCount}][name]`;
        
        const teacherSelect = newSubjectRow.querySelector('.teacher-select');
        teacherSelect.name = `subjects[${subjectCount}][teacher_id]`;
        
        // Set data if existing subject is provided
        if (existingSubject) {
            subjectInput.value = existingSubject.name;
            subjectInput.dataset.existing = 'true';
            subjectInput.dataset.subjectId = existingSubject.id;
            
            if (existingSubject.teacher_id) {
                teacherSelect.value = existingSubject.teacher_id;
            }
        }
        
        // Enable remove button if not first row
        const removeBtn = newSubjectRow.querySelector('.remove-subject-btn');
        removeBtn.disabled = (subjectCount === 1 && !existingSubject);
        removeBtn.addEventListener('click', function() {
            this.closest('.subject-row').remove();
            subjectCount--;
        });
        
        // Add event listener to save subject to group when input changes
        subjectInput.addEventListener('change', function() {
            saveSubjectToGroup(this, teacherSelect);
        });
        
        // Add event listener to update teacher when select changes
        teacherSelect.addEventListener('change', function() {
            if (subjectInput.dataset.existing === 'true') {
                saveSubjectToGroup(subjectInput, this);
            }
        });
        
        subjectsContainer.appendChild(newSubjectRow);
    }

    // Function to add a completely empty subject row
    function addEmptySubjectRow() {
        addSubjectRow();
    }

    // Save subject to group in database
    async function saveSubjectToGroup(subjectInput, teacherSelect) {
        const subjectName = subjectInput.value.trim();
        if (!subjectName) return;
        
        const grade = gradeLevelSelect.value;
        const strand = strandSelect.value;
        const semester = semesterSelect.value;
        
        if (!grade || !strand || !semester) {
            alert('Please select Grade Level, Strand, and Semester first');
            return;
        }
        
        try {
            const response = await fetch('/save-subject-to-group', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: subjectName,
                    grade_level: grade,
                    strand: strand,
                    semester: semester,
                    teacher_id: teacherSelect.value || null
                })
            });
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to save subject');
            }
            
            const data = await response.json();
            console.log('Subject saved:', data);
            
            // Mark the input as an existing subject
            subjectInput.dataset.existing = 'true';
            subjectInput.dataset.subjectId = data.subject.id;
            
            // If this was the last row, add a new empty row
            const isLastRow = subjectInput.closest('.subject-row').nextElementSibling === null;
            if (isLastRow) {
                addEmptySubjectRow();
            }
        } catch (error) {
            console.error('Error saving subject:', error);
        }
    }

    // Update section options based on selected strand and grade level
    async function updateSectionOptions() {
        const selectedStrand = strandSelect.value;
        const selectedGradeLevel = gradeLevelSelect.value;
        
        sectionSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';
        sectionSelect.disabled = true;
    
        if (!selectedStrand || !selectedGradeLevel) {
            sectionSelect.innerHTML = '<option value="" selected disabled>Select Year Level and Strand First</option>';
            sectionSelect.disabled = false;
            return;
        }
    
        try {
            const response = await fetch(`/sections-by-strand?strand=${encodeURIComponent(selectedStrand)}&grade_level=${selectedGradeLevel}`);
            if (!response.ok) throw new Error('Network error');
            
            const sections = await response.json();
            
            sectionSelect.innerHTML = '';
            const defaultOption = new Option('Select Section', '');
            defaultOption.disabled = true;
            defaultOption.selected = true;
            sectionSelect.add(defaultOption);
    
            sections.forEach(section => {
                const option = new Option(section, section);
                sectionSelect.add(option);
            });
            
            sectionSelect.disabled = false;
        } catch (error) {
            console.error('Error:', error);
            sectionSelect.innerHTML = '<option value="" selected disabled>Error loading sections</option>';
            sectionSelect.disabled = false;
        }
    }

    // Initialize student selection
    function initializeStudentSelection() {
        const gradeLevel = gradeLevelSelect.value;

        availableStudentsList.innerHTML = '';

        fetch(`/available-students?year_level=${gradeLevel}&level_type=senior`)
            .then(response => response.json())
            .then(students => {
                if (students.length === 0) {
                    const option = new Option('No available students found', '');
                    option.disabled = true;
                    availableStudentsList.add(option);
                } else {
                    students.forEach(student => {
                        const option = new Option(
                            `${student.lrn} - ${student.name}` +
                            (student.is_promoted ? ' (Promoted)' : ''),
                            student.id
                        );
                        option.dataset.lrn = student.lrn;
                        option.dataset.name = student.name;
                        option.dataset.strand = student.strand;
                        option.dataset.isPromoted = student.is_promoted;
                        availableStudentsList.add(option);
                    });
                }
            })
            .catch(error => console.error('Error fetching students:', error));
    }

    // Add selected students to the list
    function addSelectedStudents() {
        const selectedOptions = Array.from(availableStudentsList.selectedOptions);

        selectedOptions.forEach(option => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${option.dataset.lrn}</td>
                <td>${option.dataset.name}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-student">
                        <i class='bx bx-trash'></i>
                    </button>
                    <input type="hidden" name="selected_students[]" value="${option.value}">
                </td>
            `;
            selectedStudentsBody.appendChild(tr);
            option.remove();

            if (option.dataset.strand && !strandSelect.value) {
                const strandExists = Array.from(strandSelect.options).some(opt => 
                    opt.value === option.dataset.strand
                );
                
                if (strandExists) {
                    strandSelect.value = option.dataset.strand;
                }
            }
        });
    }

    // Remove student from selected list
    function removeSelectedStudent(e) {
        if (e.target.closest('.remove-student')) {
            const row = e.target.closest('tr');
            const studentId = row.querySelector('input[name="selected_students[]"]').value;
            const studentLrn = row.cells[0].textContent;
            const studentName = row.cells[1].textContent;

            const option = new Option(`${studentLrn} - ${studentName}`, studentId);
            option.dataset.lrn = studentLrn;
            option.dataset.name = studentName;
            availableStudentsList.add(option);

            row.remove();
        }
    }

    // Search students
    function searchStudents() {
        const searchTerm = this.value.toLowerCase();
        Array.from(availableStudentsList.options).forEach(option => {
            const text = option.text.toLowerCase();
            option.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    }

    // Handle promoted student row click
    function handlePromotedStudentClick() {
        const studentId = this.dataset.studentId;
        const lrn = this.dataset.lrn;
        const name = this.cells[1].textContent;
        const gradeLevel = this.dataset.gradeLevel;
        const strand = this.dataset.strand;
        const section = this.dataset.section;

        gradeLevelSelect.value = gradeLevel;
        
        const strandExists = Array.from(strandSelect.options).some(opt => 
            opt.value === strand
        );
        
        if (strandExists) {
            strandSelect.value = strand;
            
            fetch(`/sections-by-strand?strand=${encodeURIComponent(strand)}&grade_level=${gradeLevel}`)
                .then(response => response.json())
                .then(sections => {
                    sectionSelect.innerHTML = '<option value="" selected disabled>Select Section</option>';
                    
                    sections.forEach(sectionObj => {
                        const option = document.createElement('option');
                        option.value = sectionObj;
                        option.textContent = sectionObj;
                        sectionSelect.appendChild(option);
                    });
                    
                    const sectionExists = Array.from(sectionSelect.options).some(opt => 
                        opt.value === section
                    );
                    
                    if (sectionExists) {
                        sectionSelect.value = section;
                    }
                    
                    continueWithStudentSelection();
                })
                .catch(error => {
                    console.error('Error fetching sections:', error);
                    continueWithStudentSelection();
                });
        } else {
            continueWithStudentSelection();
        }
        
        function continueWithStudentSelection() {
            gradeLevelSelect.dispatchEvent(new Event('change'));

            setTimeout(() => {
                const availableStudents = document.getElementById('available-students-list');
                const options = Array.from(availableStudents.options);
                const studentOption = options.find(opt => opt.value === studentId);

                if (studentOption) {
                    studentOption.selected = true;
                    document.getElementById('add-selected-student').click();
                }
            }, 500);
        }
    }

    // Check if adviser is already assigned to another class
    async function checkAdviserAvailability() {
        const selectedTeacherId = this.value;
        if (!selectedTeacherId) return;

        try {
            const response = await fetch(`/check-adviser-availability/${selectedTeacherId}`);
            const data = await response.json();

            if (data.hasAdvisoryClass) {
                document.getElementById('adviser-warning-modal').style.display = 'flex';
                this.value = '';
            }
        } catch (error) {
            console.error('Error checking adviser availability:', error);
        }
    }

    // Handle form submission
    async function handleFormSubmit(e) {
        e.preventDefault();

        try {
            const formData = new FormData(this);

            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok) {
                alert('Class added successfully!');
                window.location.href = "{{ route('section.subject') }}";
            } else {
                const errorMessage = data.errors ? Object.values(data.errors).flat().join('\n') : data.message || 'An error occurred';
                alert('Error: ' + errorMessage);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    }

    // We'll move these functions to the global scope
});

// Global modal functions
    function openEditStrandSectionModal() {
    console.log('openEditStrandSectionModal called');
    const modal = document.getElementById('edit-strand-section-modal');
    console.log('Modal element:', modal);
    
    if (!modal) {
        console.error('Modal element not found');
        alert('Error: Modal element not found');
        return;
    }
    
    console.log('Setting modal display to flex');
    modal.style.display = 'flex';
    console.log('Modal display set to:', modal.style.display);
    
    console.log('Calling loadStrandsAndSections');
        loadStrandsAndSections();
    }

    function closeEditStrandSectionModal() {
    console.log('Closing Edit Strand Section Modal');
    const modal = document.getElementById('edit-strand-section-modal');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }
    modal.style.display = 'none';
    }

    function openAddStrandModal() {
    const modal = document.getElementById('add-strand-modal');
    if (!modal) {
        console.error('Add strand modal element not found');
        return;
    }
    modal.style.display = 'flex';
    }

    function closeAddStrandModal() {
    const modal = document.getElementById('add-strand-modal');
    if (!modal) {
        console.error('Add strand modal element not found');
        return;
    }
    modal.style.display = 'none';
        document.getElementById('new-strand-name').value = '';
    }

    function closeDeleteConfirmationModal() {
    const modal = document.getElementById('delete-confirmation-modal');
    if (!modal) {
        console.error('Delete confirmation modal element not found');
        return;
    }
    modal.style.display = 'none';
    }

    function closeAdviserWarningModal() {
    const modal = document.getElementById('adviser-warning-modal');
    if (!modal) {
        console.error('Adviser warning modal element not found');
        return;
    }
    modal.style.display = 'none';
    }

    // Load strands and sections for edit modal
    async function loadStrandsAndSections() {
    console.log('loadStrandsAndSections called');
    
    // Get the modal containers ready
    const containers = document.querySelectorAll('.strands-container');
    console.log('Found strand containers:', containers.length);
    
    containers.forEach(container => {
        container.innerHTML = '<p>Loading strands and sections...</p>';
    });
    
    try {
        // Ensure we have the proper CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        console.log('CSRF token found:', csrfToken ? 'Yes' : 'No');
        
        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('CSRF token not found. Please refresh the page and try again.');
            return;
        }
        
        console.log('Fetching from URL:', '{{ url("/strands-sections") }}');
        const response = await fetch('{{ url("/strands-sections") }}', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        console.log('Fetch response received. Status:', response.status);
        
        if (!response.ok) {
            console.error('Response not OK:', response.status, response.statusText);
            const errorText = await response.text();
            console.error('Error response:', errorText);
            
            document.querySelectorAll('.strands-container').forEach(container => {
                container.innerHTML = `<div class="alert alert-danger">Error loading data: ${response.status} ${response.statusText}</div>`;
            });
            
            throw new Error(`Failed to load strands and sections: ${response.status} ${response.statusText}`);
        }
        
            const data = await response.json();
        console.log('Received data:', data);
            
        // Clear existing strands
            document.querySelectorAll('.strands-container').forEach(container => {
                container.innerHTML = '';
            });
            
            const grades = ['11', '12'];
        
        if (!data || data.length === 0) {
            // No strands or sections found
            grades.forEach(grade => {
                const gradeElement = document.querySelector(`.grade-section[data-grade="${grade}"]`);
                if (!gradeElement) return;
                
                const strandsContainer = gradeElement.querySelector('.strands-container');
                strandsContainer.innerHTML = '<p>No strands found. Use the "Add Strand" button to create a strand.</p>';
            });
            return;
        }
            
            grades.forEach(grade => {
                const gradeElement = document.querySelector(`.grade-section[data-grade="${grade}"]`);
                if (!gradeElement) return;
                
                const strandsContainer = gradeElement.querySelector('.strands-container');
                
                data.forEach(strand => {
                    const gradeSections = strand.sections.filter(section => section.grade_level == grade);
                    
                    const strandDiv = document.createElement('div');
                    strandDiv.className = 'strand-item';
                    strandDiv.dataset.id = strand.id;
                    strandDiv.innerHTML = `
                        <div class="strand-header">
                            <h4>${strand.name}</h4>
                            <button class="btn btn-danger btn-sm remove-strand-btn" onclick="confirmRemoveStrand(${strand.id}, this)">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                        <div class="section-items">
                            ${gradeSections.map(section => `
                                <div class="section-item" data-id="${section.id}">
                                    <input type="text" value="${section.name}" class="section-input" 
                                           onchange="updateSection(${section.id}, this.value, ${grade})" required>
                                    <button class="remove-section-btn" onclick="confirmRemoveSection(${section.id}, this)">
                                        <i class='bx bx-trash'></i>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                        <button class="add-section-btn" onclick="addNewSection(${strand.id}, ${grade})">
                            <i class='bx bx-plus'></i> Add Section
                        </button>
                    `;
                    strandsContainer.appendChild(strandDiv);
                });
            });
        } catch (error) {
            console.error('Error loading strands and sections:', error);
        document.querySelectorAll('.strands-container').forEach(container => {
            container.innerHTML = `<div class="alert alert-danger">Error: ${error.message}</div>`;
        });
            alert('Failed to load strands and sections: ' + error.message);
        }
    }

function confirmRemoveStrand(id, button) {
    itemToDelete = id;
    deleteType = 'strand';
    document.querySelector('#delete-confirmation-modal .modal-body p').textContent = 
        'Are you sure you want to delete this strand and all its sections?';
    document.getElementById('delete-confirmation-modal').style.display = 'flex';
}

function confirmRemoveSection(id, button) {
    itemToDelete = id;
    deleteType = 'section';
    document.querySelector('#delete-confirmation-modal .modal-body p').textContent = 
        'Are you sure you want to delete this section?';
    document.getElementById('delete-confirmation-modal').style.display = 'flex';
}

let itemToDelete = null;
let deleteType = '';

async function deleteItem() {
    console.log('deleteItem called with:', {type: deleteType, id: itemToDelete});
    if (!itemToDelete || !deleteType) return;
    
    try {
        const endpoint = deleteType === 'strand' 
            ? `{{ url('/strands') }}/${itemToDelete}` 
            : `{{ url('/sections') }}/${itemToDelete}`;
            
        console.log('Deleting from endpoint:', endpoint);
        const response = await fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        console.log('Delete response status:', response.status);
        
        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error data:', errorData);
            throw new Error(errorData.message || `Failed to delete ${deleteType}`);
        }
        
        console.log('Delete successful');
        closeDeleteConfirmationModal();
        loadStrandsAndSections();
    } catch (error) {
        console.error(`Error deleting ${deleteType}:`, error);
        alert(`Failed to delete ${deleteType}: ${error.message}`);
    }
}

async function saveNewStrand() {
    const strandName = document.getElementById('new-strand-name').value.trim();
    
    if (!strandName) {
        alert('Please enter a strand name');
        return;
    }
    
    try {
        const response = await fetch('{{ url('/strands') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: strandName })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to add strand');
        }
        
        closeAddStrandModal();
        loadStrandsAndSections();
    } catch (error) {
        console.error('Error adding strand:', error);
        alert('Failed to add strand: ' + error.message);
    }
}

async function addNewSection(strandId, gradeLevel) {
    console.log('Adding new section for strand:', strandId, 'grade level:', gradeLevel);
    const sectionName = prompt('Enter section name:');
    
    if (!sectionName) {
        console.log('Section name was empty, cancelling');
        return;
    }
    
    console.log('Section name entered:', sectionName);
    
    try {
        console.log('Preparing to send request to add section');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');
        
        const requestBody = {
            name: sectionName,
            strand_id: strandId,
            grade_level: gradeLevel
        };
        console.log('Request payload:', JSON.stringify(requestBody));
        
        // Use sections-with-strand endpoint for adding sections in senior high
        console.log('Sending POST request to:', '{{ url('/sections-with-strand') }}');
        const response = await fetch('{{ url('/sections-with-strand') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(requestBody)
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            const errorData = await response.json();
            console.error('Error response data:', errorData);
            throw new Error(errorData.message || 'Failed to add section');
        }
        
        const responseData = await response.json();
        console.log('Success response:', responseData);
        
        // Silently reload the strands and sections without showing an alert
        loadStrandsAndSections();
    } catch (error) {
        console.error('Error details:', error);
        alert('Failed to add section: ' + error.message);
    }
}

async function updateSection(id, newName, gradeLevel) {
    if (!newName.trim()) {
        alert('Section name cannot be empty');
        return;
    }
    
    try {
        const response = await fetch(`{{ url('/sections') }}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ 
                name: newName,
                grade_level: gradeLevel
            })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to update section');
        }
        
        // No need to reload everything when just updating a name
    } catch (error) {
        console.error('Error updating section:', error);
        alert('Failed to update section: ' + error.message);
    }
}

// Promoted Students Functions
function loadGroupedPromotedStudents(yearLevel) {
    const container = document.getElementById('promoted-students-container');
    container.innerHTML = '<div class="loading">Loading promoted students...</div>';
    
    console.log('Loading promoted students for year level:', yearLevel);
    
    // Only show promoted students from the previous grade level based on the selected year level
    const previousYearLevel = yearLevel - 1;
    
    // Special case for Grade 11 - get promoted Grade 10 students from junior level
    const levelType = previousYearLevel === 10 ? 'junior' : 'senior';
    const url = `/available-students?level_type=${levelType}&previous_year_level=${previousYearLevel}&show_unassigned=true`;
    
    console.log('Fetching from URL:', url);

    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(students => {
            console.log('Received students:', students.length);
            
            if (students.length === 0) {
                container.innerHTML = `<div class="alert alert-info">No promoted students found from Grade ${previousYearLevel}.</div>`;
                if (previousYearLevel === 10) {
                    container.innerHTML += `
                    <div class="alert alert-warning">
                        <p><strong>Note:</strong> To see Grade 10 students here, you need to:</p>
                        <ol>
                            <li>Ensure Grade 10 students have been promoted</li>
                            <li>Grade 10 classes should have their advisers unassigned</li>
                        </ol>
                    </div>`;
                }
                return;
            }
            
            const groupedStudents = {};
            
            students.forEach(student => {
                if (!student.is_promoted) {
                    console.log('Skipping non-promoted student:', student.id);
                    return;
                }
                
                const sectionGroup = student.section_group || 'ungrouped';
                const strandGroup = student.strand || 'unstrand';
                const groupKey = `${sectionGroup}-${strandGroup}`;
                
                if (!groupedStudents[groupKey]) {
                    groupedStudents[groupKey] = {
                        section_name: student.section_name,
                        strand: student.strand,
                        students: []
                    };
                }
                
                groupedStudents[groupKey].students.push(student);
            });
            
            console.log('Grouped students:', Object.keys(groupedStudents).length, 'groups');
            
            let html = '';
            
            // Add bulk action controls at the top
            html += `
            <div class="bulk-actions" style="margin-bottom: 20px;">
                <button type="button" class="btn btn-primary btn-sm" id="select-all-btn">
                    <i class='bx bx-check-square'></i> Select All
                </button>
                <button type="button" class="btn btn-secondary btn-sm" id="deselect-all-btn" style="margin-left: 10px;">
                    <i class='bx bx-checkbox-minus'></i> Deselect All
                </button>
            </div>`;
            
            // Special notice for Grade 11 showing promoted Grade 10 students
            if (yearLevel === 11) {
                html += `
                <div class="alert alert-warning">
                    <i class='bx bx-info-circle'></i> Showing <strong>promoted Grade 10 students</strong> with <strong>unassigned advisers</strong> ready for assignment to Grade 11
                </div>`;
            } else {
                html += `
                <div class="alert alert-info">
                    <i class='bx bx-info-circle'></i> Showing promoted students from Grade ${previousYearLevel} ready for assignment to Grade ${yearLevel}
                </div>`;
            }
            
            if (Object.keys(groupedStudents).length === 0) {
                html += `<div class="alert alert-warning">No promoted students found from Grade ${previousYearLevel}.</div>`;
                container.innerHTML = html;
                return;
            }
            
            Object.entries(groupedStudents).forEach(([groupKey, groupData]) => {
                if (!groupData.students.length) return;
                
                const sectionName = groupData.section_name || 'Unknown Section';
                const strandName = groupData.strand || '';
                const strandDisplay = strandName ? ` - ${strandName}` : '';
                
                // Add a special indicator for Grade 10 promoted classes
                const classTitle = previousYearLevel === 10 
                    ? `<span class="promoted-badge">Promoted</span> Grade 10 - ${sectionName}`
                    : `From Grade ${previousYearLevel}${strandDisplay} - ${sectionName}`;
                
                html += `
                    <div class="section-group-container">
                        <h3 class="section-group-title">${classTitle}</h3>
                        <div class="table-responsive">
                            <table class="student-promoted-table">
                                <thead>
                                    <tr>
                                        <th width="40px"><input type="checkbox" class="section-select-all" data-section="${groupKey}"></th>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Previous Class</th>
                                        <th>Adviser</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                
                groupData.students.forEach(student => {
                    html += `
                        <tr class="promoted-student-row"
                            data-lrn="${student.lrn}"
                            data-student-id="${student.id}"
                            data-year-level="${yearLevel}"
                            data-section="${student.section_group || ''}"
                            data-strand="${student.strand || ''}">
                            <td><input type="checkbox" class="student-checkbox" value="${student.id}"></td>
                            <td>${student.lrn}</td>
                            <td>${student.name}</td>
                            <td>Grade ${student.section_group || '?'}${student.strand ? ' ' + student.strand : ''} - ${student.section_name || 'Unknown'}</td>
                            <td><span class="not-assigned-badge">Not Assigned</span></td>
                        </tr>
                    `;
                });
                
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            
            // Set up event listeners
            setupPromotedStudentsEvents();
        })
        .catch(error => {
            console.error('Error loading promoted students:', error);
            container.innerHTML = `<div class="alert alert-danger">Error loading promoted students: ${error.message}</div>`;
        });
}

function setupPromotedStudentsEvents() {
    // Add click event listeners to the promoted student rows
    document.querySelectorAll('.promoted-student-row').forEach(row => {
        row.addEventListener('click', function(event) {
            // Skip if clicking on the checkbox directly
            if (event.target.type === 'checkbox') return;
            
            // Toggle the checkbox when clicking on the row
            const checkbox = this.querySelector('.student-checkbox');
            checkbox.checked = !checkbox.checked;
        });
    });
    
    // Section select all checkboxes
    document.querySelectorAll('.section-select-all').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const section = this.dataset.section;
            const studentCheckboxes = document.querySelectorAll(`.promoted-student-row[data-section="${section}"] .student-checkbox`);
            
            studentCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });
    
    // Select all and deselect all buttons
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = true;
            });
            
            document.querySelectorAll('.section-select-all').forEach(cb => {
                cb.checked = true;
            });
        });
    }
    
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.student-checkbox').forEach(cb => {
                cb.checked = false;
            });
            
            document.querySelectorAll('.section-select-all').forEach(cb => {
                cb.checked = false;
            });
        });
    }
    
    // Add selected students button
    const addSelectedBtn = document.getElementById('add-selected-promoted');
    if (addSelectedBtn) {
        addSelectedBtn.addEventListener('click', addSelectedPromotedStudents);
    }
}

function addSelectedPromotedStudents() {
    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
    const selectedStudentsBody = document.getElementById('selected-students-body');
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one student to add.');
        return;
    }
    
    selectedCheckboxes.forEach(checkbox => {
        const row = checkbox.closest('.promoted-student-row');
        const studentId = row.dataset.studentId;
        const lrn = row.dataset.lrn;
        const studentName = row.querySelector('td:nth-child(3)').textContent;
        const previousClass = row.querySelector('td:nth-child(4)').textContent;
        
        // Create a new row in the selected students table
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${lrn}</td>
            <td>${studentName}</td>
            <td>${previousClass}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-student">
                    <i class='bx bx-trash'></i>
                </button>
                <input type="hidden" name="selected_students[]" value="${studentId}">
            </td>
        `;
        selectedStudentsBody.appendChild(tr);
        
        // Uncheck the checkbox
        checkbox.checked = false;
    });
    
    // Update select all checkboxes
    updateSelectAllCheckboxes();
    
    // Update the count of selected students
    updateSelectedStudentsCount();
}

function updateSelectAllCheckboxes() {
    document.querySelectorAll('.section-container').forEach(container => {
        const selectAllCheckbox = container.querySelector('.section-select-all');
        const studentCheckboxes = container.querySelectorAll('.student-checkbox');
        const checkedStudents = container.querySelectorAll('.student-checkbox:checked');
        
        if (studentCheckboxes.length > 0) {
            selectAllCheckbox.checked = studentCheckboxes.length === checkedStudents.length;
            selectAllCheckbox.indeterminate = checkedStudents.length > 0 && checkedStudents.length < studentCheckboxes.length;
        }
    });
    
    const allStudentCheckboxes = document.querySelectorAll('.student-checkbox');
    const allCheckedStudents = document.querySelectorAll('.student-checkbox:checked');
    
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    
    if (selectAllBtn && deselectAllBtn) {
        selectAllBtn.disabled = allStudentCheckboxes.length === allCheckedStudents.length;
        deselectAllBtn.disabled = allCheckedStudents.length === 0;
    }
}

function updateSelectedStudentsCount() {
    const selectedCount = document.querySelectorAll('#selected-students-body tr').length;
    const countDisplay = document.getElementById('selected-students-count') || document.createElement('div');
    
    if (!document.getElementById('selected-students-count')) {
        countDisplay.id = 'selected-students-count';
        countDisplay.className = 'alert alert-info mt-3';
        document.querySelector('#selected-students-container').prepend(countDisplay);
    }
    
    countDisplay.textContent = `Selected Students: ${selectedCount}`;
    countDisplay.style.display = selectedCount > 0 ? 'block' : 'none';
    updateSelectAllCheckboxes();
}

function debugSelectors() {
    console.log('---- Debugging Selectors ----');
    console.log('Student checkboxes:', document.querySelectorAll('.student-checkbox').length);
    console.log('Checked student checkboxes:', document.querySelectorAll('.student-checkbox:checked').length);
    console.log('Section select-all checkboxes:', document.querySelectorAll('.section-select-all').length);
    console.log('Student promoted table rows:', document.querySelectorAll('.student-promoted-table tr').length);
    console.log('Selected students rows:', document.querySelectorAll('#selected-students-body tr').length);
    console.log('------------------------');
}

// Initialize promoted students section when year level changes
document.getElementById('grade_level').addEventListener('change', function() {
    const yearLevel = parseInt(this.value);
    if (yearLevel) {
        console.log('Loading promoted students for year level:', yearLevel);
        loadGroupedPromotedStudents(yearLevel);
    }
});

// Remove student from selected list
document.getElementById('selected-students-body').addEventListener('click', function(e) {
    if (e.target.closest('.remove-student')) {
        const row = e.target.closest('tr');
        if (confirm('Are you sure you want to remove this student from the selection?')) {
            row.remove();
            updateSelectedStudentsCount();
        }
    }
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    const yearLevelSelect = document.getElementById('grade_level');
    if (yearLevelSelect && yearLevelSelect.value) {
        console.log('Initializing with year level:', yearLevelSelect.value);
        loadGroupedPromotedStudents(parseInt(yearLevelSelect.value));
    }
    
    // Setup remove student action
    document.getElementById('selected-students-body').addEventListener('click', function(e) {
        if (e.target.closest('.remove-student')) {
            const row = e.target.closest('tr');
            row.remove();
            updateSelectedStudentsCount();
        }
    });
});

document.getElementById('grade_level').addEventListener('change', function() {
    const csvUploadSection = document.getElementById('csvUploadSection');
    const studentSelectionSection = document.getElementById('studentSelectionSection');
    
    if (this.value === '11') {
        csvUploadSection.style.display = 'block';
        studentSelectionSection.style.display = 'none';
    } else {
        csvUploadSection.style.display = 'none';
        studentSelectionSection.style.display = 'block';
    }
});

// Update the combined grade level and semester change handler
function updateSectionVisibility() {
    const gradeLevel = document.getElementById('grade_level').value;
    const semester = document.getElementById('semester').value;
    
    const promotedStudentsSection = document.getElementById('promoted-students-section');
    const selectedStudentsSection = document.getElementById('selected-students-section');
    const csvUploadSection = document.getElementById('csvUploadSection');
    
    // Hide all sections first
    promotedStudentsSection.style.display = 'none';
    selectedStudentsSection.style.display = 'none';
    csvUploadSection.style.display = 'none';
    
    // Only process if both grade level and semester are selected
    if (gradeLevel && semester) {
        if (gradeLevel === '11' && semester === '1') {
            // Grade 11, 1st Semester - Show CSV upload section
            csvUploadSection.style.display = 'block';
        } else if ((gradeLevel === '11' && semester === '2') || 
                  (gradeLevel === '12' && semester === '1') || 
                  (gradeLevel === '12' && semester === '2')) {
            // Grade 11, 2nd Semester OR Grade 12, any semester - Show promotion student list
            promotedStudentsSection.style.display = 'block';
            selectedStudentsSection.style.display = 'block';
            
            // Load promoted students if this is the correct combination
            loadGroupedPromotedStudents(parseInt(gradeLevel));
        }
    }
}

// Add event listeners to update the sections when grade level or semester changes
document.getElementById('grade_level').addEventListener('change', updateSectionVisibility);
document.getElementById('semester').addEventListener('change', updateSectionVisibility);

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Initial check to show/hide sections based on initial values
    updateSectionVisibility();
    
    // Rest of your existing initialization code...
});

// Update the existing grade_level change event to use the new function
document.getElementById('grade_level').addEventListener('change', function() {
    // Call the updateSectionVisibility function to handle section visibility
    updateSectionVisibility();
    
    // Keep the existing functionality for fetching sections, etc.
    initializeStudentSelection();
    updateSectionOptions();
    loadSubjectsForSelection();
});
</script>
@endsection