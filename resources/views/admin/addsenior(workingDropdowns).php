@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="main-card">
        <!-- Header with title -->
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
            <button type="button" id="edit-strand-section-btn" class="btn btn-primary" onclick="openEditStrandSectionModal()">
                <i class='bx bx-edit'></i> Edit Strand and Section
            </button>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form id="add-class-form" action="{{ route('store.senior.class') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <!-- Class Information Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Class Information</h2>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="grade-level">Grade Level</label>
                            <select id="grade-level" name="grade_level" class="form-control" required>
                                <option value="" selected disabled>Select Grade Level</option>
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
                            <select id="section" name="section" class="form-control" required></select>
                                <!-- <option value="" selected disabled>Select Section</option> -->
                                <!-- Sections will be loaded dynamically based on strand selection -->
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

                <!-- Subjects Section -->
                <!-- Subjects Section -->
<div class="form-section">
    <div class="subjects-header">
        <h2 class="form-section-title">Subjects</h2>
        <button type="button" id="add-subject-btn" class="btn btn-primary">
            <i class='bx bx-plus'></i> Add Subject
        </button>
    </div>

    <div id="subjects-container">
        <!-- Initial subject row will be added dynamically -->
    </div>
    
    <!-- Template for subject row with select dropdown -->
    <template id="subject-row-template">
        <div class="subject-row">
            <div class="form-row">
                <div class="form-group subject-name-group">
                    <label>Subject</label>
                    <div class="subject-select-container">
                        <select class="form-control subject-select" required>
                            <option value="" selected disabled>Select or add subject</option>
                            <!-- Existing subjects will be loaded here -->
                        </select>
                        <input type="text" class="form-control subject-input" style="display:none;" 
                               placeholder="Enter new subject name">
                    </div>
                </div>

                <div class="form-group subject-teacher-group">
                    <label>Teacher</label>
                    <select class="form-control teacher-select" required>
                        <option value="" selected disabled>Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="subject-actions">
                    <button type="button" class="btn btn-danger remove-subject-btn" disabled>
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

                <!-- Student List Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Student List</h2>
                    <div class="student-selection-container">
                        <div class="student-search">
                            <input type="text" id="student-search" class="form-control" placeholder="Search students...">
                            <button type="button" id="add-selected-student" class="btn btn-primary">
                                <i class='bx bx-plus'></i> <span>Add Selected Student</span>
                            </button>
                        </div>
                        <div class="student-lists">
                            <div class="available-students">
                                <h3>Available Students</h3>
                                <select id="available-students-list" multiple class="form-control">
                                    <!-- Options will be dynamically added here -->
                                </select>
                                <div class="no-students-message" style="display: none;">
                                    No available students found
                                </div>
                            </div>
                            <div class="selected-students">
                                <h3>Selected Students</h3>
                                <div id="selected-students-table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>LRN</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="selected-students-body">
                                            <!-- Selected students will be dynamically added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Promoted Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Student Promoted</h2>
                    <div class="table-responsive">
                        <table class="student-promoted-table">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Previous Class</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($promotedStudents as $student)
                                    @php
                                        $promotedClass = $student->classStudents->firstWhere('is_promoted', true)->class ?? null;
                                    @endphp
                                    @if($promotedClass)
                                        <tr class="promoted-student-row"
                                            data-lrn="{{ $student->lrn }}"
                                            data-student-id="{{ $student->id }}"
                                            data-grade-level="{{ $promotedClass->year_level + 1 }}"
                                            data-strand="{{ $promotedClass->strand }}"
                                            data-section="{{ $promotedClass->section }}">
                                            <td>{{ $student->lrn }}</td>
                                            <td>{{ $student->last_name }}, {{ $student->first_name }}</td>
                                            <td>
                                                Grade {{ $promotedClass->year_level }} {{ $promotedClass->strand }} - {{ $promotedClass->section }}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
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

    /* Subjects Section Styles */
    .subjects-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e0e0e0;
    }

    .subjects-header h2 {
        margin: 0;
    }

    .subject-row {
        background-color: white;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .subject-name-group {
        flex: 2;
    }

    .subject-teacher-group {
        flex: 2;
    }

    .subject-actions {
        display: flex;
        align-items: flex-end;
        margin-bottom: 16px;
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

    .remove-subject-btn {
        padding: 8px;
        height: 38px;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('add-class-form');
    const addSubjectBtn = document.getElementById('add-subject-btn');
    const subjectsContainer = document.getElementById('subjects-container');
    const gradeLevelSelect = document.getElementById('grade-level');
    const strandSelect = document.getElementById('strand');
    const sectionSelect = document.getElementById('section');
    const semesterSelect = document.getElementById('semester');
    const adviserSelect = document.getElementById('adviser');
    let subjectCount = 0;

    gradeLevelSelect.addEventListener('change', updateSectionOptions);
        strandSelect.addEventListener('change', updateSectionOptions);
        
        // Optional: Initialize sections if values are already selected
        if (strandSelect.value && gradeLevelSelect.value) {
            updateSectionOptions();
        }
    // Get the teachers data for dynamic creation of select options
    const teacherOptions = Array.from(document.querySelector('#teacher-1').options)
        .map(option => {
            return {
                value: option.value,
                text: option.text
            };
        });

    // Initialize student selection
    initializeStudentSelection();

    // Grade Level Change Handler
    gradeLevelSelect.addEventListener('change', function() {
        initializeStudentSelection();
        updateSectionOptions();
        loadSubjectOptions(); // Added this line
    });
    
    // Strand Change Handler
    strandSelect.addEventListener('change', function() {
        updateSectionOptions();
        loadSubjectOptions(); // Added this line
    });
    
    // Semester Change Handler - Added this new event listener
    semesterSelect.addEventListener('change', function() {
        loadSubjectOptions();
    });


    // New function to load subjects based on current parameters
    async function loadSubjectOptions() {
        const gradeLevel = gradeLevelSelect.value;
        const strand = strandSelect.value;
        const semester = semesterSelect.value;
        
        if (!gradeLevel || !strand || !semester) return;
        
        try {
            const response = await fetch(`/subjects-by-group?grade=${gradeLevel}&strand=${strand}&semester=${semester}`);
            const subjects = await response.json();
            
            // Update all subject select dropdowns
            document.querySelectorAll('.subject-select').forEach(select => {
                // Save current value
                const currentValue = select.value;
                
                // Clear and repopulate options
                select.innerHTML = '<option value="" selected disabled>Select or add subject</option>';
                
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    select.appendChild(option);
                });
                
                // Add "Add new subject" option
                const newOption = document.createElement('option');
                newOption.value = 'new';
                newOption.textContent = '+ Add new subject';
                select.appendChild(newOption);
                
                // Restore selected value if it exists in new options
                if (currentValue && Array.from(select.options).some(opt => opt.value === currentValue)) {
                    select.value = currentValue;
                }
            });
        } catch (error) {
            console.error('Error loading subjects:', error);
        }

        if (!gradeLevel || !strand || !semester) {
            console.log('Missing required parameters');
            return;
        }
    }

    // Handle subject selection (including adding new subjects)
    subjectsContainer.addEventListener('change', function(e) {
        if (e.target.classList.contains('subject-select')) {
            const select = e.target;
            const container = select.closest('.subject-select-container');
            const textInput = container.querySelector('.subject-input');
            
            if (select.value === 'new') {
                // Switch to text input for new subject
                select.style.display = 'none';
                textInput.style.display = 'block';
                textInput.focus();
            }
        }
    });
    
    // Handle new subject input
    subjectsContainer.addEventListener('blur', function(e) {
        if (e.target.classList.contains('subject-input') && e.target.value) {
            const input = e.target;
            const container = input.closest('.subject-select-container');
            const select = container.querySelector('.subject-select');
            
            // Add the new option to the select
            const newOption = document.createElement('option');
            newOption.value = input.value.toLowerCase().replace(/\s+/g, '-');
            newOption.textContent = input.value;
            select.insertBefore(newOption, select.lastChild);
            
            // Select the new option and hide input
            select.value = newOption.value;
            select.style.display = 'block';
            input.style.display = 'none';
            input.value = '';
        }
    }, true);

    // Modified add subject function
    addSubjectBtn.addEventListener('click', function() {
        subjectCount++;
        
        // Enable all remove buttons when we have more than one subject
        const removeButtons = document.querySelectorAll('.remove-subject-btn');
        removeButtons.forEach(btn => {
            btn.disabled = false;
        });
        
        // Clone the template
        const template = document.getElementById('subject-row-template');
        const newSubjectRow = template.content.cloneNode(true);
        
        // Set proper names for form submission
        const subjectSelect = newSubjectRow.querySelector('.subject-select');
        subjectSelect.name = `subjects[${subjectCount}][subject_id]`;
        
        const subjectInput = newSubjectRow.querySelector('.subject-input');
        subjectInput.name = `subjects[${subjectCount}][new_subject]`;
        
        const teacherSelect = newSubjectRow.querySelector('.teacher-select');
        teacherSelect.name = `subjects[${subjectCount}][teacher_id]`;
        
        // Add to container
        subjectsContainer.appendChild(newSubjectRow);
        
        // Load current subject options
        loadSubjectOptions();
        
        // Add remove event
        const newRemoveBtn = newSubjectRow.querySelector('.remove-subject-btn');
        newRemoveBtn.addEventListener('click', function() {
            this.closest('.subject-row').remove();
            
            // If only one subject remains, disable its remove button
            const remainingSubjects = document.querySelectorAll('.subject-row');
            if (remainingSubjects.length === 1) {
                document.querySelector('.remove-subject-btn').disabled = true;
            }
        });
    });

// Function to update section options based on selected strand and grade level
function updateSectionOptions() {
    const selectedStrand = strandSelect.value;
    const selectedGradeLevel = gradeLevelSelect.value;
    
    // Clear and disable while loading
    sectionSelect.innerHTML = '<option value="" selected disabled>Loading...</option>';
    sectionSelect.disabled = true;

    // Only proceed if both values are selected
    if (!selectedStrand || !selectedGradeLevel) {
        sectionSelect.innerHTML = '<option value="" selected disabled>Select Strand and Grade First</option>';
        sectionSelect.disabled = false;
        return;
    }

    fetch(`/sections-by-strand?strand=${encodeURIComponent(selectedStrand)}&grade_level=${selectedGradeLevel}`)
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(sections => {
            // Clear existing options
            sectionSelect.innerHTML = '';
            
            // Add default option
            const defaultOption = new Option('Select Section', '');
            defaultOption.disabled = true;
            defaultOption.selected = true;
            sectionSelect.add(defaultOption);

            // Add received sections
            sections.forEach(section => {
                const option = new Option(section, section);
                sectionSelect.add(option);
            });
            
            sectionSelect.disabled = false;
        })
        .catch(error => {
            console.error('Error:', error);
            sectionSelect.innerHTML = '<option value="" selected disabled>Error loading sections</option>';
            sectionSelect.disabled = false;
        });
}

// Event listeners for dropdown changes
gradeLevelSelect.addEventListener('change', updateSectionOptions);
strandSelect.addEventListener('change', updateSectionOptions);
    function initializeStudentSelection() {
        const availableStudentsList = document.getElementById('available-students-list');
        const selectedStudentsBody = document.getElementById('selected-students-body');
        const addSelectedStudentBtn = document.getElementById('add-selected-student');
        const studentSearch = document.getElementById('student-search');
        const gradeLevel = gradeLevelSelect.value;

        // Clear existing options
        availableStudentsList.innerHTML = '';

        // Fetch available students with year_level parameter
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

        // Add student to selected list
        addSelectedStudentBtn.addEventListener('click', function() {
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

                // If this student has a strand and the strand select is empty, set it
                if (option.dataset.strand && !strandSelect.value) {
                    // Check if the strand exists in the dropdown options
                    const strandExists = Array.from(strandSelect.options).some(opt => 
                        opt.value === option.dataset.strand
                    );
                    
                    if (strandExists) {
                        strandSelect.value = option.dataset.strand;
                    }
                }
            });
        });

        // Remove student from selected list
        selectedStudentsBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-student')) {
                const row = e.target.closest('tr');
                const studentId = row.querySelector('input[name="selected_students[]"]').value;
                const studentLrn = row.cells[0].textContent;
                const studentName = row.cells[1].textContent;

                // Add back to available list
                const option = new Option(`${studentLrn} - ${studentName}`, studentId);
                option.dataset.lrn = studentLrn;
                option.dataset.name = studentName;
                availableStudentsList.add(option);

                row.remove();
            }
        });

        // Search functionality
        studentSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            Array.from(availableStudentsList.options).forEach(option => {
                const text = option.text.toLowerCase();
                option.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });
    }

    // Make promoted student rows clickable
    document.querySelectorAll('.promoted-student-row').forEach(row => {
        row.addEventListener('click', function() {
            const studentId = this.dataset.studentId;
            const lrn = this.dataset.lrn;
            const name = this.cells[1].textContent;
            const gradeLevel = this.dataset.gradeLevel;
            const strand = this.dataset.strand;
            const section = this.dataset.section;

            // Set the form fields
            gradeLevelSelect.value = gradeLevel;
            
            // Check if the strand exists in the dropdown
            const strandExists = Array.from(strandSelect.options).some(opt => 
                opt.value === strand
            );
            
            if (strandExists) {
                strandSelect.value = strand;
                
                // Fetch sections for this strand and then set the section value
                fetch(`/sections-by-strand?strand=${encodeURIComponent(strand)}&grade_level=${gradeLevel}`)
                    .then(response => response.json())
                    .then(sections => {
                        // Update the sections dropdown
                        sectionSelect.innerHTML = '<option value="" selected disabled>Select Section</option>';
                        
                        // Add the new options
                        sections.forEach(sectionObj => {
                            const option = document.createElement('option');
                            option.value = sectionObj.name;
                            option.textContent = sectionObj.name;
                            sectionSelect.appendChild(option);
                        });
                        
                        // Check if the section exists and select it
                        const sectionExists = Array.from(sectionSelect.options).some(opt => 
                            opt.value === section
                        );
                        
                        if (sectionExists) {
                            sectionSelect.value = section;
                        }
                        
                        // Continue with student selection
                        continueWithStudentSelection();
                    })
                    .catch(error => {
                        console.error('Error fetching sections:', error);
                        // Continue anyway
                        continueWithStudentSelection();
                    });
            } else {
                // Continue without setting strand/section
                continueWithStudentSelection();
            }
            
            function continueWithStudentSelection() {
                // Trigger the grade level change to update available students
                gradeLevelSelect.dispatchEvent(new Event('change'));

                // Wait for the available students to load
                setTimeout(() => {
                    // Find and select the student in available students
                    const availableStudents = document.getElementById('available-students-list');
                    const options = Array.from(availableStudents.options);
                    const studentOption = options.find(opt => opt.value === studentId);

                    if (studentOption) {
                        studentOption.selected = true;
                        // Trigger the add selected student button
                        document.getElementById('add-selected-student').click();
                    }
                }, 500);
            }
        });
    });

    // Adviser selection handling
    const adviserWarningModal = document.getElementById('adviser-warning-modal');

    adviserSelect.addEventListener('change', async function() {
        const selectedTeacherId = this.value;
        if (!selectedTeacherId) return;

        try {
            const response = await fetch(`/check-adviser-availability/${selectedTeacherId}`);
            const data = await response.json();

            if (data.hasAdvisoryClass) {
                adviserWarningModal.style.display = 'flex';
                this.value = '';
            }
        } catch (error) {
            console.error('Error checking adviser availability:', error);
        }
    });

    // Close modal when clicking outside
    adviserWarningModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAdviserWarningModal();
        }
    });

    window.closeAdviserWarningModal = function() {
        adviserWarningModal.style.display = 'none';
    };

    // Form Submission
    form.addEventListener('submit', async function(e) {
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
    });
});

// Strand and Section Modal Functions
function openEditStrandSectionModal() {
    document.getElementById('edit-strand-section-modal').style.display = 'flex';
    loadStrandsAndSections();
}

function closeEditStrandSectionModal() {
    document.getElementById('edit-strand-section-modal').style.display = 'none';
}

function openAddStrandModal() {
    document.getElementById('add-strand-modal').style.display = 'flex';
}

function closeAddStrandModal() {
    document.getElementById('add-strand-modal').style.display = 'none';
    document.getElementById('new-strand-name').value = '';
}

function closeDeleteConfirmationModal() {
    document.getElementById('delete-confirmation-modal').style.display = 'none';
    itemToDelete = null;
    deleteType = '';
}

async function loadStrandsAndSections() {
    try {
        console.log('Fetching strands and sections...');
        const response = await fetch('/strands-sections');
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        const data = await response.json();
        console.log('Data received:', data);
        
        // Clear existing strands
        document.querySelectorAll('.strands-container').forEach(container => {
            container.innerHTML = '';
        });
        
        // Populate strands and sections for each grade
        const grades = ['11', '12'];
        
        grades.forEach(grade => {
            const gradeElement = document.querySelector(`.grade-section[data-grade="${grade}"]`);
            if (!gradeElement) return;
            
            const strandsContainer = gradeElement.querySelector('.strands-container');
            strandsContainer.innerHTML = ''; // Clear existing content
            
            // Process the flat list of strands
            data.forEach(strand => {
                // Filter sections for this grade level
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
        alert('Failed to load strands and sections: ' + error.message);
    }
}

async function saveNewStrand() {
    const strandName = document.getElementById('new-strand-name').value.trim();
    
    if (!strandName) {
        alert('Please enter a strand name');
        return;
    }
    
    try {
        console.log('Saving new strand:', strandName);
        const response = await fetch('/strands', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name: strandName })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            console.error('Server responded with error:', errorData);
            throw new Error(errorData.message || 'Failed to add strand');
        }
        
        const data = await response.json();
        console.log('Strand created successfully:', data);
        
        closeAddStrandModal();
        loadStrandsAndSections(); // Refresh the list
    } catch (error) {
        console.error('Error adding strand:', error);
        alert('Failed to add strand: ' + error.message);
    }
}

async function addNewSection(strandId, gradeLevel) {
    const sectionName = prompt('Enter section name:');
    
    if (!sectionName) return;
    
    try {
        const response = await fetch('/sections-with-strand', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: sectionName,
                strand_id: strandId,
                grade_level: gradeLevel
            })
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'Failed to add section');
        }
        
        loadStrandsAndSections(); // Refresh the list
    } catch (error) {
        console.error('Error adding section:', error);
        alert('Failed to add section: ' + error.message);
    }
}

async function updateSection(id, newName, gradeLevel) {
    if (!newName.trim()) {
        alert('Section name cannot be empty');
        return;
    }
    
    try {
        const response = await fetch(`/sections-with-strand/${id}`, {
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
    } catch (error) {
        console.error('Error updating section:', error);
        alert('Failed to update section: ' + error.message);
    }
}

let itemToDelete = null;
let deleteType = '';

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

async function deleteItem() {
    if (!itemToDelete || !deleteType) return;
    
    try {
        const endpoint = deleteType === 'strand' 
            ? `/strands/${itemToDelete}` 
            : `/sections-with-strand/${itemToDelete}`;
            
        const response = await fetch(endpoint, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `Failed to delete ${deleteType}`);
        }
        
        closeDeleteConfirmationModal();
        loadStrandsAndSections(); // Refresh the list
    } catch (error) {
        console.error(`Error deleting ${deleteType}:`, error);
        alert(`Failed to delete ${deleteType}: ${error.message}`);
    }
}

// Add event listeners for clicking outside modals
window.addEventListener('click', function(e) {
    const modals = [
        'edit-strand-section-modal',
        'delete-confirmation-modal',
        'add-strand-modal'
    ];
    
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
    </script>
@endsection
