@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header with title -->
<!-- Header with title and action button -->
<div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1>Add Junior High Class</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('section.subject') }}">Sections & Subjects</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Junior High Class</li>
            </ol>
        </nav>
    </div>
    <button type="button" id="edit-section-btn" class="btn btn-primary" onclick="openEditSectionsModal()">
    <i class='bx bx-edit'></i> Edit Section
</button>
</div>

        
            <!-- Form Container -->
            <div class="form-container">
                <form id="add-class-form" action="{{ route('store.junior.class') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Class Information Section -->
                    <div class="form-section">
                        <h2 class="form-section-title">Class Informationaa</h2>       <!-- //Hellooooooooooooooo   -->

                        <div class="form-row">
                            <div class="form-group">
                                <label for="year-level">Year Level</label>
                                <select id="year-level" name="year_level" class="form-control" required>
                                    <option value="" selected disabled>Select Year Level</option>
                                    <option value="7">Grade 7</option>
                                    <option value="8">Grade 8</option>
                                    <option value="9">Grade 9</option>
                                    <option value="10">Grade 10</option>
                                </select>
                            </div>

                            <div class="form-group">
    <label for="section">Section</label>
    <select id="section" name="section" class="form-control" required>
        <option value="" selected disabled>Select Section</option>
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
<!-- In your form, modify the subjects section to look like this: -->
    <div class="form-section">
    <div class="subjects-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h2 class="form-section-title">Subjects</h2>
    <button type="button" id="add-subject-btn" class="btn btn-primary">
        <i class='bx bx-plus'></i> Add Subject
    </button>
</div>

    <!-- Subject headers (only shown once) -->
    <div class="subject-headers">
        <div class="form-row">
            <div class="form-group subject-name-group">
                <label>Subject</label>
            </div>
            <div class="form-group subject-teacher-group">
                <label>Teacher</label>
            </div>
            <div class="subject-actions">
                <!-- Empty for alignment -->
            </div>
        </div>
    </div>

    <div id="subjects-container">
        <!-- Subjects will be added here by JavaScript -->
    </div>
</div>

                    <!-- Student List Section -->
                    <div class="form-section" id="student-list-section">
                        <h2 class="form-section-title">Student List</h2>
                        <div class="form-group">
                            <label for="student-list">Upload Student List (CSV)</label>
                            <div class="file-upload-container">
                                <input type="file" id="student-list" name="student_list" accept=".csv" class="file-input" onchange="handleFileSelect(this)">
                                <label for="student-list" class="file-label">
                                <i class='bx bx-upload'></i>
                                    <span id="file-name">Choose CSV file</span>
                            </label>
                                <div id="file-preview" class="file-preview"></div>
                            </div>
                            <small class="text-muted">CSV format: First Name, Last Name, LRN, Birthday (YYYY-MM-DD)</small>
                        </div>
                    </div>

                    <!-- Student Promoted Section -->
<div class="form-section">
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
<div class="form-section">
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

<!-- Edit Sections Modal -->
<div id="edit-sections-modal" class="modal">
    <div class="modal-content sections-modal">
        <div class="modal-header">
            <h2>Edit Sections</h2>
            <button class="close-modal" onclick="closeEditSectionsModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="sections-container">
                <!-- Grade 7 -->
                <div class="grade-section" data-grade="7">
                    <h3>Grade 7</h3>
                    <div class="section-items-container">
                        <div class="section-items">
                            <!-- Sections will be loaded here -->
                        </div>
                    </div>
                    <button class="add-section-btn" onclick="addNewSection(this)">
                        <i class='bx bx-plus'></i> Add Section
                    </button>
                </div>
                
                <!-- Grade 8 -->
                <div class="grade-section" data-grade="8">
                    <h3>Grade 8</h3>
                    <div class="section-items-container">
                        <div class="section-items">
                            <!-- Sections will be loaded here -->
                        </div>
                    </div>
                    <button class="add-section-btn" onclick="addNewSection(this)">
                        <i class='bx bx-plus'></i> Add Section
                    </button>
                </div>
                
                <!-- Grade 9 -->
                <div class="grade-section" data-grade="9">
                    <h3>Grade 9</h3>
                    <div class="section-items-container">
                        <div class="section-items">
                            <!-- Sections will be loaded here -->
                        </div>
                    </div>
                    <button class="add-section-btn" onclick="addNewSection(this)">
                        <i class='bx bx-plus'></i> Add Section
                    </button>
                </div>
                
                <!-- Grade 10 -->
                <div class="grade-section" data-grade="10">
                    <h3>Grade 10</h3>
                    <div class="section-items-container">
                        <div class="section-items">
                            <!-- Sections will be loaded here -->
                        </div>
                    </div>
                    <button class="add-section-btn" onclick="addNewSection(this)">
                        <i class='bx bx-plus'></i> Add Section
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeEditSectionsModal()">Done</button>
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
            <p>Are you sure you want to delete this section?</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeDeleteConfirmationModal()">Cancel</button>
            <button class="btn btn-danger" onclick="deleteSection()">Delete</button>
        </div>
    </div>
</div>

<!-- Add a modal for displaying duplicate LRNs -->
<div id="duplicate-lrn-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Duplicate LRNs Found</h2>
            <button class="close-modal" onclick="closeDuplicateLRNModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>The following LRNs already exist in the database:</p>
            <div id="duplicate-lrn-list" style="max-height: 300px; overflow-y: auto;">
                <!-- List of duplicate LRNs will be added here -->
            </div>
            <div class="alert alert-info mt-3">
                <p>You have the following options:</p>
                <ul>
                    <li>Continue and use the existing student accounts with these LRNs</li>
                    <li>Cancel and modify your CSV file to remove or update these LRNs</li>
                </ul>
            </div>
        </div>
        <div class="modal-footer">
            <button id="cancel-submission-btn" class="btn btn-secondary">Cancel Submission</button>
            <button id="continue-submission-btn" class="btn btn-primary">Continue with Existing Accounts</button>
        </div>
    </div>
</div>

<style>
    /* Base Styles */
    .subjects-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.subjects-header h2 {
    margin: 0;
}
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 24px 16px;
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

    /* Compact Subjects Table */
    .subjects-table-container {
        margin-top: 16px;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .subjects-table {
        width: 100%;
        border-collapse: collapse;
    }

    .subjects-table th, 
    .subjects-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    .subjects-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #555;
    }

    .subject-name-cell {
        font-weight: 500;
        width: 40%;
    }

    .subject-teacher-cell {
        width: 50%;
    }

    .subject-teacher-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .subject-action-cell {
        width: 10%;
        text-align: center;
    }

    .remove-subject-btn {
        background: none;
        border: none;
        color: #e53e3e;
        cursor: pointer;
        font-size: 18px;
        padding: 0;
    }

    .remove-subject-btn:disabled {
        color: #ccc;
        cursor: not-allowed;
    }

    /* Student Tables */
    .table-responsive {
        margin-top: 16px;
    }
    

    .student-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .student-promoted-table th,
    .student-promoted-table td,
    .student-table th,
    .student-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .student-promoted-table th,
    .student-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
    }
    
    .student-promoted-table tr:nth-child(even),
    .student-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Upload Section */
    .upload-container {
        border: 2px dashed #ddd;
        border-radius: 6px;
        padding: 32px;
        text-align: center;
        background-color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .upload-container:hover {
        border-color: #00b050;
        background-color: #f0f9f4;
    }
    
    .upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .upload-label i {
        font-size: 36px;
        color: #00b050;
        margin-bottom: 12px;
    }
    
    .upload-label span {
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }
    
    .upload-label small {
        font-size: 12px;
        color: #666;
    }
    
    .file-input {
        display: none;
    }

    /* Buttons */
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

    /* Modal */
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
        padding: 24px;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        width: 90%;
        max-width: 500px;
        margin: 20px auto;
    }
    #selected-students-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

#selected-students-table th, 
#selected-students-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

#selected-students-table th {
    background-color: #f5f5f5;
    font-weight: 500;
    color: #666;
}

#selected-students-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.remove-selected-student {
    padding: 4px 8px;
    font-size: 12px;
}
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }
    
    .modal-footer {
        padding-top: 16px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
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

    /* Interactive Elements */
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

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .form-section {
            padding: 16px;
        }
        
        .subjects-table th, 
        .subjects-table td {
            padding: 8px 12px;
        }
        
        .upload-container {
            padding: 24px;
        }
    }

    .subject-headers {
    margin-bottom: 8px;
}

.subject-headers .form-row {
    align-items: flex-end;
}

.subject-headers label {
    margin-bottom: 0;
    font-weight: 500;
    color: #333;
}

/* Ensure consistent width between headers and inputs */
.subject-headers .form-group,
.subject-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

.subject-headers .subject-actions,
.subject-row .subject-actions {
    width: 60px; /* Match your button width */
}

    .section-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
    flex-wrap: wrap;
}

.section-header h1 {
    margin: 0 0 8px 0;
}

#edit-section-btn {
    margin-left: auto; /* Pushes button to the right */
    align-self: center; /* Vertically centers with the header */
}

/* Edit Sections Modal Styles */
.sections-modal {
    width: 500px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
}

.modal-body {
    overflow-y: auto;
    padding: 16px;
}

.sections-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.grade-section {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #dee2e6;
}

.grade-section h3 {
    margin-top: 0;
    margin-bottom: 12px;
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
}

.section-items-container {
    max-height: 150px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background: white;
    margin-bottom: 8px;
}

.section-items {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.section-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
}

.section-item:last-child {
    border-bottom: none;
}

.section-input {
    flex: 1;
    padding: 6px 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
    border: none;
    background: transparent;
}

.section-input:focus {
    outline: none;
    background: white;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}
/* Add to your existing CSS */
.bulk-actions {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    margin-bottom: 20px;
}

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

.promoted-student-row:hover {
    background-color: #f5f5f5;
}

.promoted-student-row.selected {
    background-color: #e8f4fc;
}
.remove-section-btn {
    background: none;
    border: none;
    color: #dc3545;
    font-size: 18px;
    cursor: pointer;
    padding: 0 8px;
    line-height: 1;
    margin-left: 8px;
}

.remove-section-btn:hover {
    color: #c82333;
}

.add-section-btn {
    background: none;
    border: 1px dashed #6c757d;
    color: #6c757d;
    cursor: pointer;
    padding: 6px 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    border-radius: 4px;
    width: 100%;
    justify-content: center;
}

.add-section-btn:hover {
    background-color: #f8f9fa;
}
.section-items-container {
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    background: white;
    margin-bottom: 8px;
}

.section-item {
    display: flex;
    align-items: center;
    padding: 8px 12px;
    border-bottom: 1px solid #f0f0f0;
}

.section-item:last-child {
    border-bottom: none;
}

.section-input {
    flex: 1;
    padding: 6px 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    font-size: 14px;
}

.remove-section-btn {
    background: none;
    border: none;
    color: #dc3545;
    cursor: pointer;
    margin-left: 8px;
    font-size: 18px;
}

.add-section-btn {
    background: none;
    border: 1px dashed #6c757d;
    color: #6c757d;
    cursor: pointer;
    padding: 6px 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    border-radius: 4px;
    width: 100%;
    justify-content: center;
}

.add-section-btn:hover {
    background-color: #f8f9fa;
}

/* Add these styles to your existing CSS */
.csv-preview {
    margin-top: 20px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.preview-table th,
.preview-table td {
    padding: 8px 12px;
    border: 1px solid #e0e0e0;
    text-align: left;
}

.preview-table th {
    background-color: #f5f5f5;
    font-weight: 500;
}

.preview-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.file-upload-container {
    margin-top: 10px;
}

.file-input {
    display: none;
}

.file-label {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background-color: #f8f9fa;
    border: 1px solid #ddd;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-label:hover {
    background-color: #e9ecef;
}

.file-label i {
    margin-right: 8px;
    font-size: 20px;
}
#selected-students-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
}

#selected-students-table th, 
#selected-students-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

#selected-students-table th {
    background-color: #f5f5f5;
    font-weight: 500;
    color: #666;
}

#selected-students-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.remove-selected-student {
    padding: 4px 8px;
    font-size: 12px;
}
.file-preview {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: none;
}

.text-muted {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 4px;
    display: block;
}

.section-group-container {
    margin-bottom: 24px;
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

.loading {
    padding: 20px;
    text-align: center;
    color: #666;
}


</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('add-class-form');
        const addSubjectBtn = document.getElementById('add-subject-btn');
        const subjectsContainer = document.getElementById('subjects-container');
        const yearLevelSelect = document.getElementById('year-level');
        const studentListSection = document.getElementById('student-list-section');
        const sectionSelect = document.getElementById('section');
        let subjectCount = 0;

        // Get teachers data from the adviser dropdown (already populated from backend)
        const teacherOptions = Array.from(document.querySelector('#adviser').options)
            .filter(option => option.value) // Skip the "Select Teacher" option
            .map(option => ({
                value: option.value,
                text: option.text
            }));

        // Function to create a new subject row with teacher dropdown
        function createSubjectRow(index, subjectName = '') {
            const teacherOptionsHtml = teacherOptions
                .map(teacher => `<option value="${teacher.value}">${teacher.text}</option>`)
                .join('');

            return `
                <div class="subject-row" data-index="${index}">
                    <div class="form-row">
                        <div class="form-group subject-name-group">
                            <input type="text" id="subject-${index}" name="subjects[${index}][name]" 
                                class="form-control subject-input" placeholder="Enter subject name" 
                                value="${subjectName}" required>
                        </div>

                        <div class="form-group subject-teacher-group">
                            <select id="teacher-${index}" name="subjects[${index}][teacher_id]" class="form-control teacher-select" required>
                                <option value="" selected disabled>Select Teacher</option>
                                ${teacherOptionsHtml}
                            </select>
                        </div>

                        <div class="subject-actions">
                            <button type="button" class="btn btn-danger remove-subject-btn" ${index === 0 ? 'disabled' : ''}>
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Initialize default subjects
        function initializeDefaultSubjects() {
            const defaultSubjects = [
                'Filipino',
                'English',
                'Mathematics',
                'Science',
                'Araling Panlipunan',
                'Edukasyon sa Pagpapakatao',
                'Technology and Livelihood Education',
                'MAPEH'
            ];

            let html = '';
            defaultSubjects.forEach((subject, index) => {
                html += createSubjectRow(index, subject);
                subjectCount = index;
            });
            
            subjectsContainer.innerHTML = html;
            
            // Add event listeners to all remove buttons
            document.querySelectorAll('.remove-subject-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const subjectRow = this.closest('.subject-row');
                    subjectRow.remove();
                    
                    updateSubjectIndices();
                    
                    // If only one subject remains, disable its remove button
                    const remainingSubjects = document.querySelectorAll('.subject-row');
                    if (remainingSubjects.length === 1) {
                        remainingSubjects[0].querySelector('.remove-subject-btn').disabled = true;
                    }
                });
            });
        }

        // Add new subject
        addSubjectBtn.addEventListener('click', function() {
            subjectCount++;
            subjectsContainer.insertAdjacentHTML('beforeend', createSubjectRow(subjectCount));

            // Add event listener to the new remove button
            const newRemoveBtn = subjectsContainer.querySelector(`.subject-row[data-index="${subjectCount}"] .remove-subject-btn`);
            newRemoveBtn.addEventListener('click', function() {
                this.closest('.subject-row').remove();

                const remainingSubjects = document.querySelectorAll('.subject-row');
                if (remainingSubjects.length === 1) {
                    remainingSubjects[0].querySelector('.remove-subject-btn').disabled = true;
                }

                updateSubjectIndices();
            });

            // Enable all remove buttons when we have more than one subject
            if (subjectCount > 0) {
                document.querySelectorAll('.remove-subject-btn').forEach(btn => {
                    btn.disabled = false;
                });
            }
        });

        // Update subject indices after removal
        function updateSubjectIndices() {
            const subjectRows = document.querySelectorAll('.subject-row');
            subjectCount = -1;
            
            subjectRows.forEach((row, index) => {
                subjectCount = index;
                row.dataset.index = index;
                
                const nameInput = row.querySelector('.subject-input');
                const teacherSelect = row.querySelector('.teacher-select');

                nameInput.id = `subject-${index}`;
                nameInput.name = `subjects[${index}][name]`;
                
                teacherSelect.id = `teacher-${index}`;
                teacherSelect.name = `subjects[${index}][teacher_id]`;
            });
        }

        // Add event listener for year level change
        yearLevelSelect.addEventListener('change', function() {
    const selectedYearLevel = this.value;
            
            // Get references to all the relevant containers
            const studentListSection = document.getElementById('student-list-section');
            const promotedStudentsSection = document.getElementById('promoted-students-container').closest('.form-section');
            const selectedStudentsSection = document.getElementById('selected-students-container').closest('.form-section');
            
            // Hide or show the sections based on grade level
            if (selectedYearLevel && parseInt(selectedYearLevel) > 7) {
                // For grades 8, 9, 10
                studentListSection.style.display = 'none';
                promotedStudentsSection.style.display = 'block';
                selectedStudentsSection.style.display = 'block';
            } else {
                // For grade 7
                studentListSection.style.display = 'block';
                promotedStudentsSection.style.display = 'none';
                selectedStudentsSection.style.display = 'none';
            }
    
    // Load sections for the selected year level
    loadSectionsForYearLevel(selectedYearLevel);
    
            // Clear any previously selected students
            document.getElementById('selected-students-body').innerHTML = '';
        });

        // If the year level is pre-selected, trigger the change event
        if (yearLevelSelect.value) {
            yearLevelSelect.dispatchEvent(new Event('change'));
        }

// Function to load sections for a specific year level
function loadSectionsForYearLevel(yearLevel) {
            fetch(`/sections/by-grade/${yearLevel}`)
                .then(response => response.json())
                .then(sections => {
                    const sectionSelect = document.getElementById('section');
    sectionSelect.innerHTML = '<option value="" selected disabled>Select Section</option>';
    
                    // First, get sections that already have a class
                    fetch(`/sections/with-classes?grade_level=${yearLevel}`)
                        .then(response => response.json())
                        .then(usedSections => {
                            // Convert the array to a Set for faster lookups
                            const usedSectionIds = new Set(usedSections.map(s => s.id));
                            
                            sections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                
                                // Disable the option if this section already has a class and is not promoted
                                if (usedSectionIds.has(section.id)) {
                                    option.disabled = true;
                                    option.textContent += ' (Already has a class)';
                                }
                                
                                sectionSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading used sections:', error);
                            // Fallback: just add the sections without disabling
                sections.forEach(section => {
                    const option = document.createElement('option');
                    option.value = section.id;
                    option.textContent = section.name;
                    sectionSelect.appendChild(option);
                });
                        });
        })
        .catch(error => {
            console.error('Error loading sections:', error);
                });
                
            // Also load promoted students from previous grade level (if applicable)
            if (yearLevel && yearLevel !== '7') {
                loadGroupedPromotedStudents(parseInt(yearLevel));
            } else {
                // For Grade 7, clear and show message that no promoted students are available
                const container = document.getElementById('promoted-students-container');
                container.innerHTML = '<div class="alert alert-info">No promoted students available for Grade 7. Please add students directly.</div>';
            }
}

        // Function to toggle student list section based on year level
        function toggleStudentListSection() {
    const studentListSection = document.getElementById('student-list-section');
    
    // Use the simplified CSV upload for all grade levels
    studentListSection.innerHTML = `
        <h2 class="form-section-title">Student List</h2>
        <div class="form-group">
            <label for="student-list">Upload Student List (CSV)</label>
            <div class="file-upload-container">
                <input type="file" id="student-list" name="student_list" accept=".csv" class="file-input">
                <label for="student-list" class="file-label">
                    <i class='bx bx-upload'></i>
                    <span id="file-name">Choose CSV file</span>
                </label>
                <div id="file-preview" class="file-preview"></div>
            </div>
            <small class="text-muted">CSV format: First Name, Last Name, LRN, Birthday (YYYY-MM-DD)</small>
        </div>
    `;
    
    // Reinitialize the file input handler
    const fileInput = document.getElementById('student-list');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            handleFileSelect(this);
        });
    }
}

        // Initialize file upload handling
        function initializeFileUpload() {
            const uploadContainer = document.querySelector('.upload-container');
            const fileInput = document.getElementById('student-list');
            const csvPreview = document.getElementById('csv-preview');
            const previewTable = document.getElementById('preview-table').getElementsByTagName('tbody')[0];

                uploadContainer.addEventListener('click', function() {
                    fileInput.click();
                });

            fileInput.addEventListener('change', async function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Update the file name display
                        const uploadLabel = document.querySelector('.upload-label span');
                    uploadLabel.textContent = file.name;
                        uploadContainer.style.borderColor = '#00b050';

                    const reader = new FileReader();
                    reader.onload = async function(e) {
                        const text = e.target.result;
                        const lines = text.split('\n').filter(line => line.trim()); // Filter out empty lines
                        
                        // Validate that the file has content
                        if (lines.length <= 1) {
                            csvPreview.innerHTML = `
                                <div class="alert alert-danger">
                                    <strong>Error:</strong> The CSV file appears to be empty or only contains headers. 
                                    Please make sure your file contains student data.
                                </div>`;
                            csvPreview.style.display = 'block';
                            return;
                        }
                        
                        // Try to determine the delimiter
                        const firstLine = lines[0];
                        let delimiter = ',';  // Default to comma
                        
                        if (firstLine.includes('\t')) {
                            delimiter = '\t';  // Tab delimiter
                        } else if (firstLine.includes(';')) {
                            delimiter = ';';   // Semicolon delimiter
                        }
                        
                        // Validate headers
                        const headers = firstLine.split(delimiter).map(h => h.trim().toLowerCase());
                        const requiredFields = ['first name', 'last name', 'lrn'];
                        const missingFields = requiredFields.filter(field => 
                            !headers.some(header => header.includes(field.toLowerCase()))
                        );
                        
                        let previewHTML = '';
                        
                        // Show delimiter information
                        previewHTML += `<div class="alert alert-info">Your CSV appears to be using ${delimiter === '\t' ? 'tab' : delimiter} as a delimiter.</div>`;
                        
                        // Show warning if missing fields
                        if (missingFields.length > 0) {
                            previewHTML += `
                                <div class="alert alert-warning">
                                    <strong>Warning:</strong> Your CSV may be missing required fields: ${missingFields.join(', ')}.
                                    Please ensure your file has columns for First Name, Last Name, LRN, and Birthdate.
                                </div>`;
                        }
                        
                        // Show preview table
                        previewHTML += '<table class="preview-table"><thead><tr><th>First Name</th><th>Last Name</th><th>LRN</th><th>Birthdate</th></tr></thead><tbody>';
                        
                        // Show data rows
                        for (let i = 1; i < Math.min(5, lines.length); i++) {
                            const columns = lines[i].split(delimiter).map(col => col.trim());
                            
                            // Try to map columns to expected fields using headers
                            let firstName = '', lastName = '', lrn = '', birthdate = '';
                            headers.forEach((header, index) => {
                                if (index >= columns.length) return;
                                if (header.includes('first') || header.includes('given')) firstName = columns[index];
                                else if (header.includes('last') || header.includes('surname') || header.includes('family')) lastName = columns[index];
                                else if (header.includes('lrn') || header.includes('id')) lrn = columns[index];
                                else if (header.includes('birth') || header.includes('date')) birthdate = columns[index];
                            });
                            
                            // Use positional fallback if mapping failed
                            if (!firstName && columns.length > 0) firstName = columns[0] || '';
                            if (!lastName && columns.length > 1) lastName = columns[1] || '';
                            if (!lrn && columns.length > 2) lrn = columns[2] || '';
                            if (!birthdate && columns.length > 3) birthdate = columns[3] || '';
                            
                            previewHTML += `<tr>
                                <td>${firstName || ''}</td>
                                <td>${lastName || ''}</td>
                                <td>${lrn || ''}</td>
                                <td>${birthdate || ''}</td>
                            </tr>`;
                        }
                        
                        previewHTML += '</tbody></table>';
                        
                        // Add format guidance
                        previewHTML += `
                        <div class="csv-format-guide" style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 4px;">
                            <h4 style="margin-top: 0;">CSV Format Guidelines:</h4>
                            <ul>
                                <li>Your file should be saved as a <strong>comma-separated (CSV)</strong> file</li>
                                <li>First row must contain headers: <code>First Name,Last Name,LRN,Birthdate</code></li>
                                <li>Birthdate should be in YYYY-MM-DD format (e.g., 2001-01-01)</li>
                                <li>If using Excel, export as "CSV (Comma delimited)"</li>
                            </ul>
                            <p>Example of correct CSV content:</p>
                            <pre style="background: #eee; padding: 8px; overflow: auto;">First Name,Last Name,LRN,Birthdate
John,Doe,123456789012,2001-01-01
Jane,Smith,123456789013,2001-02-15</pre>
                        </div>
                        
                        <div class="alert alert-primary mt-3">
                            <strong>Need help?</strong> If you're having trouble with the file format, here are some troubleshooting tips:
                            <ul>
                                <li>Make sure your file has the correct column headers</li>
                                <li>Check for extra commas or other characters that might break the CSV format</li>
                                <li>If using Excel, use "Save As" and select "CSV (Comma delimited)"</li>
                                <li>Ensure all required fields (First Name, Last Name, LRN, Birthdate) have values</li>
                            </ul>
                        </div>
                        `;
                        
                        previewTable.innerHTML = previewHTML;
                        csvPreview.style.display = 'block';
                    };
                    reader.readAsText(file);
                }
            });
        }
        document.getElementById('add-selected-promoted').addEventListener('click', function() {
            addSelectedPromotedStudents();
        });

        // Promoted student row click handler
        document.querySelectorAll('.promoted-student-row').forEach(row => {
            row.addEventListener('click', function(event) {
                // Skip if clicking on the checkbox directly
                if (event.target.type === 'checkbox') return;
                
                // Toggle the checkbox when clicking on the row
                const checkbox = this.querySelector('.student-checkbox');
                checkbox.checked = !checkbox.checked;
            });
        });

        function fetchAvailableStudents(yearLevel) {
            fetch(`/available-students?year_level=${yearLevel}`)
                .then(response => response.json())
                .then(students => {
                    const availableStudentsList = document.getElementById('available-students-list');
                    availableStudentsList.innerHTML = '';

                    if (students.length === 0) {
                        const option = new Option('No available students found', '');
                        option.disabled = true;
                        availableStudentsList.add(option);
                    } else {
                        students.forEach(student => {
                            const option = new Option(`${student.lrn} - ${student.name}`, student.id);
                            option.dataset.lrn = student.lrn;
                            option.dataset.name = student.name;
                            availableStudentsList.add(option);
                        });
                    }
                });
        }

        function initializeStudentSelection(yearLevel) {
            const availableStudentsList = document.getElementById('available-students-list');
            const selectedStudentsBody = document.getElementById('selected-students-body');
            const addSelectedStudentBtn = document.getElementById('add-selected-student');
            const studentSearch = document.getElementById('student-search');

            // Clear existing options
            availableStudentsList.innerHTML = '';

            // Fetch available students with year_level parameter
            fetch(`/available-students?year_level=${yearLevel}&level_type=junior`)
                .then(response => response.json())
                .then(students => {
                    if (students.length === 0) {
                        const option = new Option('No available students found', '');
                        option.disabled = true;
                        availableStudentsList.add(option);
                    } else {
                        // Clear existing options first
                        availableStudentsList.innerHTML = '';

                        students.forEach(student => {
                            const option = new Option(
                                `${student.lrn} - ${student.name}` +
                                (student.is_promoted ? ' (Promoted)' : ''),
                                student.id
                            );
                            option.dataset.lrn = student.lrn;
                            option.dataset.name = student.name;
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
                });
            });

            // Remove student from selected list
            selectedStudentsBody.addEventListener('click', function(e) {
                if (e.target.closest('.remove-student')) {
                    const row = e.target.closest('tr');
                    if (confirm('Are you sure you want to remove this student from the selection?')) {
                    row.remove();
                    }
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

        // Adviser selection handling
        const adviserSelect = document.getElementById('adviser');
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

        // Add function to check for duplicate LRNs
        async function checkForDuplicateLRNs(csvFile) {
            // Create FormData and append the file
            const formData = new FormData();
            formData.append('csv_file', csvFile);
            formData.append('_token', '{{ csrf_token() }}');
            
            try {
                const response = await fetch('/check-duplicate-lrns', {
                    method: 'POST',
                    body: formData
                });
                
                if (!response.ok) {
                    throw new Error(`Server responded with ${response.status}: ${await response.text()}`);
                }
                
                const result = await response.json();
                return result;
            } catch (error) {
                console.error('Error checking for duplicate LRNs:', error);
                return { duplicates: [], error: error.message };
            }
        }
        
        // Function to open duplicate LRN modal
        function showDuplicateLRNModal(duplicates) {
            const modal = document.getElementById('duplicate-lrn-modal');
            const duplicateList = document.getElementById('duplicate-lrn-list');
            
            // Clear previous content
            duplicateList.innerHTML = '';
            
            // Create a table to display student information
            const table = document.createElement('table');
            table.className = 'table table-striped';
            
            // Add table header
            const thead = document.createElement('thead');
            thead.innerHTML = `
                <tr>
                    <th>LRN</th>
                    <th>Name</th>
                    <th>Current Class</th>
                </tr>
            `;
            table.appendChild(thead);
            
            // Add table body with duplicate entries
            const tbody = document.createElement('tbody');
            duplicates.forEach(student => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${student.lrn}</td>
                    <td>${student.name}</td>
                    <td>${student.current_class || 'Not assigned'}</td>
                `;
                tbody.appendChild(tr);
            });
            
            table.appendChild(tbody);
            duplicateList.appendChild(table);
            
            // Display the modal
            modal.style.display = 'flex';
            
            // Return a promise that resolves when the user makes a decision
            return new Promise((resolve) => {
                document.getElementById('continue-submission-btn').onclick = function() {
                    closeDuplicateLRNModal();
                    resolve(true); // Continue with submission
                };
                
                document.getElementById('cancel-submission-btn').onclick = function() {
                    closeDuplicateLRNModal();
                    resolve(false); // Cancel submission
                };
            });
        }
        
        // Function to close the duplicate LRN modal
        function closeDuplicateLRNModal() {
            document.getElementById('duplicate-lrn-modal').style.display = 'none';
        }

        // Handle form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            try {
                // Validate essential form fields client-side
                const yearLevel = document.getElementById('year-level').value;
                const section = document.getElementById('section').value;
                const adviser = document.getElementById('adviser').value;
                
                // Check if there are subjects
                const subjectInputs = document.querySelectorAll('input[name^="subjects["][name$="[name]"]');
                
                // Check if there are selected students
                const selectedStudents = document.querySelectorAll('#selected-students-body tr');
                
                if (!yearLevel) {
                    alert('Please select a Year Level');
                    return;
                }
                
                if (!section) {
                    alert('Please select a Section');
                    return;
                }
                
                if (!adviser) {
                    alert('Please select a Class Adviser');
                    return;
                }
                
                if (subjectInputs.length === 0) {
                    alert('Please add at least one subject');
                    return;
                }
                
                // Handle conditionally required student list
                if (yearLevel === '7') {
                    const studentListFile = document.getElementById('student-list').files[0];
                    if (!studentListFile) {
                        alert('Please upload a CSV file for Grade 7 student list');
                        return;
                    }
                    
                    // Check for duplicate LRNs in the CSV
                    const duplicateResult = await checkForDuplicateLRNs(studentListFile);
                    
                    if (duplicateResult.error) {
                        alert('Error checking for duplicate LRNs: ' + duplicateResult.error);
                        return;
                    }
                    
                    if (duplicateResult.duplicates && duplicateResult.duplicates.length > 0) {
                        // Show modal with duplicate LRNs and wait for user decision
                        const continueSubmission = await showDuplicateLRNModal(duplicateResult.duplicates);
                        
                        if (!continueSubmission) {
                            // User chose to cancel submission
                            return;
                        }
                        
                        // If we continue, the duplicate LRNs will update the existing students
                        console.log('Continuing with submission, using existing student accounts');
                    }
                } else {
                    // For grades 8-10, check if there are selected students
                    if (selectedStudents.length === 0) {
                        alert('Please select at least one student to add to the class');
                        return;
                    }
                }

                // Check if a class with this section already exists
                const response = await fetch(`/check-section-used?section=${section}&year_level=${yearLevel}`);
                const data = await response.json();
                
                if (data.exists) {
                    alert('A class with this section already exists. Please select a different section.');
                    return;
                }

                // Submit the form (now with proper validation)
                const formData = new FormData(this);
                
                // Show loading indicator
                const submitButton = this.querySelector('[type="submit"]');
                const originalText = submitButton.innerHTML;
                submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Submitting...';
                submitButton.disabled = true;
                
                // Send form data
                const submitResponse = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await submitResponse.json();

                if (submitResponse.ok) {
                    alert('Class created successfully!');
                    window.location.href = "{{ route('section.subject') }}";
                } else {
                    // Reset button
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                    
                    // Show error messages
                    let errorMessage = 'An error occurred while saving the class.';
                    if (result.errors) {
                        errorMessage = Object.values(result.errors).flat().join('\n');
                    } else if (result.message) {
                        errorMessage = result.message;
                    } else if (result.error) {
                        errorMessage = result.error;
                    }
                    
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                alert('An unexpected error occurred. Please try again.');
                
                // Reset button state
                const submitButton = this.querySelector('[type="submit"]');
                submitButton.innerHTML = 'Submit';
                submitButton.disabled = false;
            }
        });

        // Initialize default subjects when page loads
        initializeDefaultSubjects();

        // Initialize the page
        const firstYearBtn = document.querySelector('.year-btn');
        if (firstYearBtn) {
            selectYear(firstYearBtn.dataset.year);
        }
        updatePeriodSelector('junior');
        
        // Load grouped promoted students
        loadGroupedPromotedStudents();

        // Debug function to check selector matching
        function debugSelectors() {
            console.log("=== DEBUGGING SELECTORS ===");
            console.log("All checkboxes:", document.querySelectorAll('.student-checkbox').length);
            console.log("Checked checkboxes:", document.querySelectorAll('.student-checkbox:checked').length);
            console.log("Promoted table checkboxes:", document.querySelectorAll('.student-promoted-table .student-checkbox').length);
            console.log("Checked promoted table checkboxes:", document.querySelectorAll('.student-promoted-table .student-checkbox:checked').length);
            console.log("=========================");
        }

        // Attach debugging to the button
        document.addEventListener('DOMContentLoaded', function() {
            const addSelectedBtn = document.getElementById('add-selected-promoted');
            if (addSelectedBtn) {
                addSelectedBtn.addEventListener('click', function() {
                    debugSelectors(); // Run the debug function first
                    addSelectedPromotedStudents(); // Then run the main function
                });
            }
        });
    });

    // Edit Sections Modal Functions
// Load sections when modal opens
function openEditSectionsModal() {
    document.getElementById('edit-sections-modal').style.display = 'flex';
    loadSections(); // Load sections when modal opens
}

async function loadSections() {
    try {
        const response = await fetch('/sections');
        
        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`Server responded with ${response.status}: ${errorText}`);
        }
        
        const data = await response.json();
        
        // Debug: Log the received data
        console.log('Received sections data:', data);
        
        // Clear existing sections
        document.querySelectorAll('.section-items').forEach(container => {
            container.innerHTML = '';
        });
        
        // Check if data is in expected format
        if (typeof data !== 'object' || data === null) {
            throw new Error('Invalid data format received from server');
        }
        
        // Populate sections for each grade
        for (const [grade, sections] of Object.entries(data)) {
            const gradeElement = document.querySelector(`.grade-section[data-grade="${grade}"]`);
            
            if (!gradeElement) {
                console.warn(`No container found for grade ${grade}`);
                continue;
            }
            
            const container = gradeElement.querySelector('.section-items');
            if (!container) {
                console.warn(`No section-items container found for grade ${grade}`);
                continue;
            }
            
            // Ensure sections is an array
            const sectionsArray = Array.isArray(sections) ? sections : [sections];
            
            sectionsArray.forEach(section => {
                if (!section.id || !section.name) {
                    console.warn('Invalid section data:', section);
                    return;
                }
                
                const sectionItem = document.createElement('div');
                sectionItem.className = 'section-item';
                sectionItem.dataset.id = section.id;
                sectionItem.innerHTML = `
                    <input type="text" value="${section.name}" class="section-input" 
                           onchange="updateSection(${section.id}, this.value)" required>
                    <button class="remove-section-btn" onclick="confirmRemoveSection(${section.id}, this)">
                        <i class='bx bx-trash'></i>
                    </button>
                `;
                container.appendChild(sectionItem);
            });
        }
    } catch (error) {
        console.error('Error loading sections:', error);
        alert('Failed to load sections: ' + error.message);
        
        // Show more detailed error in console
        console.group('Error Details');
        console.error('Error:', error);
        if (error.response) {
            console.error('Response status:', error.response.status);
            console.error('Response text:', await error.response.text());
        }
        console.groupEnd();
    }
}
async function addNewSection(button) {
    const gradeSection = button.closest('.grade-section');
    const grade = gradeSection.dataset.grade;
    const sectionName = prompt('Enter section name:');
    
    if (sectionName) {
        try {
            const response = await fetch('/sections', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: sectionName,
                    grade_level: grade
                })
            });
            
            if (!response.ok) throw new Error('Failed to add section');
            
            const newSection = await response.json();
            loadSections(); // Refresh the list
        } catch (error) {
            console.error('Error adding section:', error);
            alert('Failed to add section: ' + error.message);
        }
    }
}

async function updateSection(id, newName) {
    try {
        await fetch(`/sections/${id}`, { 
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                name: newName
            })
        });
    } catch (error) {
        console.error('Error updating section:', error);
        alert('Failed to update section');
    }
}

async function confirmRemoveSection(id, button) {
    if (confirm('Are you sure you want to delete this section?')) {
        try {
            await fetch(`/sections/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            button.closest('.section-item').remove();
        } catch (error) {
            console.error('Error deleting section:', error);
            alert('Failed to delete section');
        }
    }
}

function closeEditSectionsModal() {
    document.getElementById('edit-sections-modal').style.display = 'none';
}

function updateStudentListSection(yearLevel) {
    const currentFile = document.getElementById('student-list')?.files[0];
    
    // Simple CSV upload for all grade levels
    studentListSection.innerHTML = `
        <h2 class="form-section-title">Student List</h2>
        <div class="form-group">
            <label for="student-list">Upload Student List (CSV)</label>
            <div class="file-upload-container">
                <input type="file" id="student-list" name="student_list" accept=".csv" class="file-input" onchange="handleFileSelect(this)">
                <label for="student-list" class="file-label">
                    <i class='bx bx-upload'></i>
                    <span id="file-name">${currentFile ? currentFile.name : 'Choose CSV file'}</span>
                </label>
                <div id="file-preview" class="file-preview"></div>
            </div>
            <small class="text-muted">CSV format: First Name, Last Name, LRN, Birthday (YYYY-MM-DD)</small>
        </div>
    `;
    
    // Initialize file upload handling
    if (typeof initializeFileUpload === 'function') {
        initializeFileUpload();
    }
    
    // If there was a file previously selected, restore it
    if (currentFile) {
        const newFileInput = document.getElementById('student-list');
        if (newFileInput && 'DataTransfer' in window) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(currentFile);
                newFileInput.files = dataTransfer.files;
                handleFileSelect(newFileInput);
            } catch (error) {
                console.error('Error restoring file selection:', error);
            }
        }
    }
}

async function checkLrnUniqueness(lrn, userId = null) {
    if (!lrn) return false;

    try {
        const response = await fetch('/check-lrn-unique', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ lrn, user_id: userId }) // Ensure user_id is passed
        });

        const data = await response.json();
        const errorElement = userId ?
            document.getElementById('edit-lrn-error') :
            document.getElementById('lrn-error');

        if (!data.unique) {
            errorElement.textContent = 'This LRN is already in use by another student. Please use a different LRN.';
            return false;
        } else {
            errorElement.textContent = '';
            return true;
        }
    } catch (error) {
        console.error('Error checking LRN:', error);
        return false;
    }
}

function handleFileSelect(input) {
    const fileName = document.getElementById('file-name');
    const filePreview = document.getElementById('file-preview');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileName.textContent = file.name;
        
        // Preview logic would go here
        filePreview.innerHTML = 'File selected: ' + file.name;
        filePreview.style.display = 'block';
    } else {
        fileName.textContent = 'Choose CSV file';
        filePreview.style.display = 'none';
    }
}

    function loadGroupedPromotedStudents(yearLevel) {
    const container = document.getElementById('promoted-students-container');
    container.innerHTML = '<div class="loading">Loading promoted students...</div>';
    
        // Only show promoted students from the previous grade level based on the selected year level
        const previousYearLevel = yearLevel - 1;
        
        const url = `/available-students?level_type=junior&previous_year_level=${previousYearLevel}`;
    
    fetch(url)
        .then(response => response.json())
        .then(students => {
            if (students.length === 0) {
                    container.innerHTML = `<div class="alert alert-info">No promoted students found from Grade ${previousYearLevel}.</div>`;
                return;
            }
                
                // Only include students not already assigned to a class at the next grade level
                // This filtering is now handled server-side in the SectionSubjectController
            
            const groupedStudents = {};
            
            students.forEach(student => {
                if (!student.is_promoted) return;
                
                const sectionGroup = student.section_group || 'ungrouped';
                
                if (!groupedStudents[sectionGroup]) {
                    groupedStudents[sectionGroup] = {
                        section_name: student.section_name,
                        students: []
                    };
                }
                
                groupedStudents[sectionGroup].students.push(student);
            });
            
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
        </div>
        <div class="alert alert-info">
            <i class='bx bx-info-circle'></i> Showing promoted students from Grade ${previousYearLevel} ready for assignment to Grade ${yearLevel}
    </div>
`;
                
                if (Object.keys(groupedStudents).length === 0) {
                    html += `<div class="alert alert-warning">No promoted students found from Grade ${previousYearLevel}.</div>`;
                    container.innerHTML = html;
                    return;
                }
            
            Object.entries(groupedStudents).forEach(([sectionId, groupData]) => {
                if (!groupData.students.length) return;
                
                const sectionName = groupData.section_name || 
                                   (sectionId === 'ungrouped' ? 'Ungrouped Students' : `Section ${sectionId}`);
                
                html += `
                    <div class="section-group-container">
                            <h3 class="section-group-title">From Grade ${previousYearLevel} - ${sectionName}</h3>
                        <div class="table-responsive">
                            <table class="student-promoted-table">
                                <thead>
                                    <tr>
                                        <th width="40px"><input type="checkbox" class="section-select-all" data-section="${sectionId}"></th>
                                        <th>LRN</th>
                                        <th>Student Name</th>
                                        <th>Previous Class</th>
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
                            data-section="${student.section_group || ''}">
                            <td><input type="checkbox" class="student-checkbox" value="${student.id}"></td>
                            <td>${student.lrn}</td>
                            <td>${student.name}</td>
                            <td>Grade ${student.section_group || '?'} - ${student.section_name || 'Unknown'}</td>
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
            
                // Set up the event listeners AFTER the HTML has been created
                
                // Add click event listeners to the promoted student rows
                document.querySelectorAll('.promoted-student-row').forEach(row => {
                    row.addEventListener('click', function(event) {
                        // Skip if clicking on the checkbox directly
                        if (event.target.type === 'checkbox') return;
                        
                        // Toggle the checkbox when clicking on the row
                        const checkbox = this.querySelector('.student-checkbox');
                        checkbox.checked = !checkbox.checked;
                        
                        // Log the state for debugging
                        console.log(`Row clicked: ${this.querySelector('td:nth-child(3)').textContent}, Checkbox state: ${checkbox.checked}`);
                    });
                });
                
                // Add event listeners for section "Select All" checkboxes
            document.querySelectorAll('.section-select-all').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                        const section = this.dataset.section;
                        const studentCheckboxes = document.querySelectorAll(`.promoted-student-row[data-section="${section}"] .student-checkbox`);
                        studentCheckboxes.forEach(box => box.checked = this.checked);
                        console.log(`Section ${section} selected all: ${this.checked}, affected ${studentCheckboxes.length} checkboxes`);
                });
            });
            
                // Global Select All button
                const selectAllBtn = document.getElementById('select-all-btn');
                if (selectAllBtn) {
                    selectAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                            checkbox.checked = true;
                        });
                        
                        document.querySelectorAll('.section-select-all').forEach(checkbox => {
                            checkbox.checked = true;
                        });
                        
                        console.log('Selected all students');
                    });
                }
                
                // Global Deselect All button
                const deselectAllBtn = document.getElementById('deselect-all-btn');
                if (deselectAllBtn) {
                    deselectAllBtn.addEventListener('click', function() {
                        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        
                        document.querySelectorAll('.section-select-all').forEach(checkbox => {
                            checkbox.checked = false;
                        });
                        
                        console.log('Deselected all students');
                    });
                }
        })
        .catch(error => {
            console.error('Error loading promoted students:', error);
            container.innerHTML = '<div class="alert alert-danger">Failed to load promoted students. Please try again.</div>';
        });
}

    // Functions for handling promoted students
function selectAllPromotedStudents() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
        
        // Update all "select all" checkboxes
    document.querySelectorAll('.section-select-all').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllPromotedStudents() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
        
        // Update all "select all" checkboxes
    document.querySelectorAll('.section-select-all').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function addSelectedPromotedStudents() {
        debugSelectors(); // Run debug before processing
        
        // Get all checked checkboxes within the promoted students table
        const selectedCheckboxes = document.querySelectorAll('.student-promoted-table .student-checkbox:checked');
        console.log('Selected checkboxes count:', selectedCheckboxes.length);
    
    if (selectedCheckboxes.length === 0) {
        alert('Please select at least one student to add');
        return;
    }

    const selectedStudentsBody = document.getElementById('selected-students-body');
        let addedCount = 0;
    
    selectedCheckboxes.forEach(checkbox => {
            const studentId = checkbox.value;
            
            // Check if this student is already in the selected list
            const existingRow = document.querySelector(`#selected-students-body tr[data-student-id="${studentId}"]`);
            if (existingRow) {
                console.log(`Student ${studentId} already in selected list, skipping`);
                return; // Skip this student if already in the list
            }
            
        const row = checkbox.closest('tr');
        const lrn = row.dataset.lrn;
            const name = row.querySelector('td:nth-child(3)').textContent;
            const yearLevel = row.dataset.yearLevel;
        
            // Create new row for selected students table
            const newRow = document.createElement('tr');
            newRow.dataset.studentId = studentId;
            newRow.innerHTML = `
                <td>${lrn}</td>
                <td>${name}</td>
                <td>Grade ${yearLevel}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-student">
                        <i class='bx bx-trash'></i> Remove
                    </button>
                    <input type="hidden" name="selected_students[]" value="${studentId}">
                </td>
            `;
            
            selectedStudentsBody.appendChild(newRow);
            addedCount++;
        });
        
        if (addedCount > 0) {
            // Show success message
            const alertContainer = document.getElementById('alerts-container') || document.createElement('div');
            if (!document.getElementById('alerts-container')) {
                alertContainer.id = 'alerts-container';
                document.querySelector('.container-fluid').prepend(alertContainer);
            }
            
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show';
            alert.innerHTML = `
                <i class='bx bx-check-circle'></i> Successfully added ${addedCount} student(s) to the selected list.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            alertContainer.appendChild(alert);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        // Update the count of selected students
        updateSelectedStudentsCount();
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
        
        // Show or hide the count based on whether there are students
        countDisplay.style.display = selectedCount > 0 ? 'block' : 'none';
        
        // Also update any "Select All" checkboxes status
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

    function updateSelectAllCheckboxes() {
        // Update section checkboxes
        document.querySelectorAll('.section-container').forEach(container => {
            const sectionId = container.dataset.sectionId;
            const selectAllCheckbox = container.querySelector('.section-select-all');
            const studentCheckboxes = container.querySelectorAll('.student-checkbox');
            const checkedStudents = container.querySelectorAll('.student-checkbox:checked');
            
            if (studentCheckboxes.length > 0) {
                selectAllCheckbox.checked = studentCheckboxes.length === checkedStudents.length;
                selectAllCheckbox.indeterminate = checkedStudents.length > 0 && checkedStudents.length < studentCheckboxes.length;
            }
        });
        
        // Update global select all button state
        const allStudentCheckboxes = document.querySelectorAll('.student-checkbox');
        const allCheckedStudents = document.querySelectorAll('.student-checkbox:checked');
        
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        
        if (selectAllBtn && deselectAllBtn) {
            selectAllBtn.disabled = allStudentCheckboxes.length === allCheckedStudents.length;
            deselectAllBtn.disabled = allCheckedStudents.length === 0;
        }
    }

    function toggleSectionSelection(checkbox, sectionId) {
        const container = document.querySelector(`.section-container[data-section-id="${sectionId}"]`);
        const studentCheckboxes = container.querySelectorAll('.student-checkbox');
        
        studentCheckboxes.forEach(studentBox => {
            studentBox.checked = checkbox.checked;
        });
        
        updateSelectAllCheckboxes();
    }

    function selectAllStudents() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSelectAllCheckboxes();
    }

    function deselectAllStudents() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
        updateSelectAllCheckboxes();
    }

    // Initialize selection counts on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSelectedStudentsCount();
        
        // Attach event listeners to section select-all checkboxes
        document.querySelectorAll('.section-select-all').forEach(checkbox => {
            const sectionId = checkbox.closest('.section-container').dataset.sectionId;
            checkbox.addEventListener('change', function() {
                toggleSectionSelection(this, sectionId);
            });
        });
        
        // Attach event listeners to individual student checkboxes
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckboxes);
        });
        
        // Attach event listeners to global select/deselect buttons
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', selectAllStudents);
        }
        
        if (deselectAllBtn) {
            deselectAllBtn.addEventListener('click', deselectAllStudents);
        }
        
        // Add event listener for removing students from the selected list
document.getElementById('selected-students-body').addEventListener('click', function(e) {
            if (e.target.closest('.remove-student')) {
                const row = e.target.closest('tr');
                row.remove();
                updateSelectedStudentsCount();
            }
        });
});
</script>
@endsection
