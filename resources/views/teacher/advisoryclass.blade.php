@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="section-header">
                <div class="header-content">
                    <h1>Advisory Class</h1>
                    @if($advisoryClass)
                        <div class="button-group">
                            <button id="subject-btn" class="subject-button">
                                <i class='bx bx-book-alt'></i>
                                <span>Subjects</span>
                            </button>
                            <button id="promote-btn" class="promote-button" onclick="handlePromote()">
                                <i class='bx bx-up-arrow-alt'></i>
                                <span>Promote</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            @if($advisoryClass)
                <!-- Search Container -->
                <div class="search-add-container">
                    <div class="search-container">
                        <i class='bx bx-search search-icon'></i>
                        <input type="text" class="search-input" placeholder="Search student...">
                    </div>
                </div>

                <!-- Class Information Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Category Info -->
                    <div class="info-card bg-green-50">
                        <h2 class="info-card-title text-green-800">Category Information</h2>
                        <div class="info-card-content">
                            <p class="info-item">
                                <span class="info-label">Level:</span>
                                <span class="info-value text-green-700">
                                    {{ $advisoryClass->level_type === 'junior' ? 'Junior High School' : 'Senior High School' }}
                                </span>
                            </p>
                            @if($advisoryClass->level_type === 'senior')
                                <p class="info-item">
                                    <span class="info-label">Strand:</span>
                                    <span class="info-value text-green-700">{{ $advisoryClass->strand }}</span>
                                </p>
                                <p class="info-item">
                                    <span class="info-label">Semester:</span>
                                    <span class="info-value text-green-700">{{ $advisoryClass->semester }}</span>
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Section Info -->
                    <div class="info-card bg-blue-50">
                        <h2 class="info-card-title text-blue-800">Section Details</h2>
                        <div class="info-card-content">
                            <p class="info-item">
                                <span class="info-label">Section:</span>
                                <span class="info-value text-blue-700">{{ $advisoryClass->sectionDetails ? $advisoryClass->sectionDetails->name : $advisoryClass->section }}</span>
                            </p>
                            <p class="info-item">
                                <span class="info-label">Year Level:</span>
                                <span class="info-value text-blue-700">
                                    @if($advisoryClass->level_type === 'junior')
                                        @php
                                            $yearLevel = $advisoryClass->year_level - 6;
                                            $suffix = match($yearLevel) {
                                                1 => 'st',
                                                2 => 'nd',
                                                3 => 'rd',
                                                default => 'th',
                                            };
                                        @endphp
                                        {{ $yearLevel }}{{ $suffix }} Year
                                    @else
                                        Grade {{ $advisoryClass->year_level }}
                                    @endif
                                </span>
                            </p>
                            <p class="info-item">
                                <span class="info-label">Total Students:</span>
                                <span class="info-value text-blue-700">{{ $advisoryClass->students->count() }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-header-left">
                            <h2>List of Students</h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($advisoryClass->students as $index => $student)
                                    <tr class="{{ $index % 2 === 0 ? 'even-row' : '' }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $student->lrn }}</td>
                                        <td>
                                            {{ $student->last_name }}, {{ $student->first_name }}
                                            @if($student->middle_name)
                                                {{ $student->middle_name }}
                                            @endif
                                        </td>
                                        <td>
                                            <button
                                                onclick="showGrades({{ $student->id }})"
                                                class="view-grades-btn">
                                                View Grades
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Modal -->
    <div id="subject-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Class Subjects</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="period-selector mb-4">
                    <label for="subject-period-select" class="block text-sm font-medium text-gray-700 mb-2">Select Period:</label>
                    <select id="subject-period-select" class="form-select w-full" onchange="updateSubjectGrades()">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="subject-table">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Status</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody id="subject-grades-list">
                            <!-- Will be populated dynamically -->
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button id="submit-grades" class="submit-button" onclick="submitGrades()" disabled>
                            Submit Grades
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
<!-- Add the promote confirmation modal -->
<div id="promote-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Confirm Promotion</h2>
            <button class="close-modal" onclick="closePromoteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to promote all students? This action will mark them as completed for this academic year.</p>
            <p class="text-sm text-gray-600 mt-2">Note: This action can only be performed after all grades have been submitted and confirmed.</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closePromoteModal()" style="background-color: #6c757d; color: white;">Cancel</button>
            <button class="btn btn-primary" onclick="promoteStudents()" style="background-color: #28a745; color: white;">Confirm Promotion</button>
        </div>
    </div>
</div>
    <!-- Grades Modal -->
    <div id="grades-modal" class="modal">
        <div class="modal-content modal-content-wide">
            <div class="modal-header">
                <h2>Student Grades</h2>
                <button class="close-modal" onclick="closeGradesModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="grades-container">
                    <div class="grades-info">
                        <div id="student-info" class="mb-4"></div>
                        <div class="table-responsive">
                            <table class="grades-table">
                                <thead>
                                    <tr id="grades-header">
                                        <th>Subject</th>
                                        <th>Teacher</th>
                                        <!-- Period columns will be added dynamically -->
                                    </tr>
                                </thead>
                                <tbody id="grades-list">
                                    <!-- Grades will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="student-profile">
                        <div id="student-image" class="profile-image-container">
                            <!-- Profile image will be displayed here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @else
        <!-- No Advisory Class Message -->
        <div class="no-data-message">
            <div class="flex items-center justify-center">
                <i class='bx bx-info-circle text-yellow-400 text-4xl mr-3'></i>
                <div>
                    <h3 class="text-xl font-semibold text-gray-700">No Advisory Class Assigned</h3>
                    <p class="text-gray-500 mt-2">You are currently not assigned to any advisory class.</p>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Container Styles */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 16px;
    }

    @media (min-width: 768px) {
        .container {
            padding: 24px 16px;
        }
    }
    .button-group {
    display: flex;
    gap: 12px;
}

.promote-button {
    background-color: #4F46E5;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.promote-button:hover {
    background-color: #4338CA;
}

.promote-button:disabled {
    background-color: #9CA3AF;
    cursor: not-allowed;
}
    .main-card {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 16px;
    }

    @media (min-width: 768px) {
        .main-card {
            padding: 24px;
        }
    }

    /* Header Styles */
    .section-header {
        margin-bottom: 20px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .section-header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 0;
    }

    /* Subject Button Styles */
    .subject-button {
        display: flex;
        align-items: center;
        gap: 8px;
        background-color: #00b050;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .subject-button:hover {
        background-color: #009040;
    }

    .subject-button i {
        font-size: 18px;
    }

    /* Search Styles */
    .search-add-container {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
    }

    .search-container {
        position: relative;
        flex-grow: 1;
    }

    .search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .search-input {
        width: 100%;
        padding: 8px 8px 8px 36px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    /* Info Card Styles */
    .info-card {
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .info-card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .info-card-content {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-label {
        font-weight: 500;
        min-width: 100px;
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
    }

    .modal-content {
        position: relative;
        background-color: white;
        margin: 20px auto;
        padding: 20px;
        width: 90%;
        max-width: 600px;
        border-radius: 8px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-content-wide {
        max-width: 900px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e0e0e0;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #666;
    }

    /* Table Styles */
    .table-container {
        margin-top: 20px;
        background-color: white;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .table-header {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #e8f8ee;
    }

    .table-header h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .student-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .student-table th,
    .student-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .student-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .even-row {
        background-color: #f9f9f9;
    }

    /* Subject Table Styles */
    .subject-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .subject-table th,
    .subject-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .subject-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .subject-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Status Badge Styles */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-complete {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-pending {
        background-color: #fef9c3;
        color: #854d0e;
    }

    /* Progress Bar Styles */
    .progress-bar {
        width: 100%;
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background-color: #10b981;
        transition: width 0.3s ease;
    }

    .progress-text {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Grades Container Styles */
    .grades-container {
        display: flex;
        gap: 24px;
    }

    .grades-info {
        flex: 1;
    }

    .student-profile {
        width: 300px;
        padding: 16px;
        background-color: #f8fafc;
        border-radius: 8px;
    }

    #grades-modal .profile-image-container {
        width: 100%;
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        background-color: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #grades-modal .profile-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #grades-modal .profile-image-container .no-image {
        color: #9ca3af;
        font-size: 48px;
    }

    /* Student info styles */
    #student-info {
        background-color: #f8fafc;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 20px;
    }

    #student-info h3 {
        color: #1e293b;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    #student-info p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 4px;
    }

    /* Grades table styles */
    .grades-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }

    .grades-table th,
    .grades-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .grades-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
    }

    .grades-table th.period-grade {
        text-align: center;
        min-width: 100px;
    }

    .grades-table td.period-grade {
        text-align: center;
    }

    .grade-cell {
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        min-width: 60px;
        text-align: center;
        background-color: #f3f4f6;
        color: #374151;
    }

    /* Remove color-specific styles */
    .grade-outstanding,
    .grade-very-good,
    .grade-good,
    .grade-passed,
    .grade-failed,
    .grade-pending {
        background-color: #f3f4f6;
        color: #374151;
    }

    /* View Grades Button */
    .view-grades-btn {
        background-color: #4F46E5;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 14px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .view-grades-btn:hover {
        background-color: #4338CA;
    }

    /* Period Selector Styles */
    .period-selector {
        margin-bottom: 16px;
    }

    .form-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        background-color: white;
        font-size: 14px;
        color: #333;
    }

    /* No Data Message */
    .no-data-message {
        padding: 48px 24px;
        text-align: center;
        background-color: #fff;
        border-radius: 8px;
        border: 2px dashed #e0e0e0;
    }

    /* Responsive Modal Styles */
    @media (max-width: 768px) {
        .modal-content-wide {
            width: 95%;
            margin: 10px auto;
            padding: 10px;
        }

        .grades-container {
            flex-direction: column;
            gap: 16px;
        }

        .student-profile {
            width: 100%;
            margin-top: 16px;
        }

        .grades-table th,
        .grades-table td {
            padding: 8px;
            font-size: 12px;
        }
        .grades-table tr:last-child {
    background-color: #f5f5f5; /* Light gray background */
    font-weight: bold; /* Bold text */
}

.grades-table tr:last-child td {
    border-top: 2px solid #e0e0e0; /* Add a border above the row */
}
        .grades-table th.period-grade,
        .grades-table td.period-grade {
            min-width: 60px;
        }

        .grade-cell {
            min-width: 50px;
            padding: 4px;
            font-size: 12px;
        }

        .modal-header h2 {
            font-size: 18px;
        }

        .modal-header .close-modal {
            font-size: 20px;
        }

        #student-info h3 {
            font-size: 16px;
        }

        #student-info p {
            font-size: 12px;
        }

        .profile-image-container {
            width: 100%;
            height: auto;
            aspect-ratio: 1;
        }

        .profile-image-container .no-image {
            font-size: 36px;
        }
    }
    .modal-footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    text-align: right;
}

.submit-button {
    background-color: #9ca3af;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: not-allowed;
    transition: all 0.2s ease;
}

.submit-button-enabled {
    background-color: #00b050;
    cursor: pointer;
}

.submit-button-enabled:hover {
    background-color: #009040;
}

.submitted-button {
    background-color: #4B5563;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: not-allowed;
}
</style>

<script>
    let currentYear = '';
    let currentSubject = null;
    let currentGrades = {};
    let currentLevelType = '{{ $advisoryClass ? $advisoryClass->level_type : "junior" }}';

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize period selector for subject modal
        initializePeriodSelector();

        // Subject modal functionality
        const modal = document.getElementById('subject-modal');
        const subjectBtn = document.getElementById('subject-btn');
        const closeBtn = document.querySelector('.close-modal');

        if (subjectBtn) {
            subjectBtn.addEventListener('click', () => {
                modal.style.display = 'block';
                updateSubjectGrades(); // Update grades for initial period
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        }

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.student-table tbody tr');

                rows.forEach(row => {
                    const lrn = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
                    const name = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';

                    if (lrn.includes(searchTerm) || name.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
    function handlePromote() {
    const promoteModal = document.getElementById('promote-modal');
    promoteModal.style.display = 'block';
}

function closePromoteModal() {
    const promoteModal = document.getElementById('promote-modal');
    promoteModal.style.display = 'none';
}

function promoteStudents() {
    fetch('/promote-students', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Students have been successfully promoted!');
            window.location.reload();
        } else {
            alert(data.message || 'Failed to promote students. Please ensure all grades are submitted and confirmed.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to promote students. Please try again.');
    })
    .finally(() => {
        closePromoteModal();
    });
}
    function initializePeriodSelector() {
        const periodSelect = document.getElementById('subject-period-select');
        const isJunior = currentLevelType === 'junior';
        const periods = isJunior ? 4 : 2;
        const periodType = isJunior ? 'Quarter' : 'Semester';

        periodSelect.innerHTML = Array.from({ length: periods }, (_, i) => {
            const num = i + 1;
            return `<option value="${num}">${num}${getOrdinalSuffix(num)} ${periodType}</option>`;
        }).join('');
    }

    function updateSubmitButton(subjects) {
    const submitButton = document.getElementById('submit-grades');
    const allComplete = subjects.every(subject => subject.status === 'complete');

    submitButton.disabled = !allComplete;
    submitButton.classList.toggle('submit-button-enabled', allComplete);
}

// In advisoryclass.blade.php, replace the submitGrades() function with:

// In advisoryclass.blade.php, update the submitGrades() function:

function submitGrades() {
    const selectedPeriod = document.getElementById('subject-period-select').value;
    const periodType = currentLevelType === 'junior' ? 'quarter' : 'semester';

    // Show loading state
    const submitButton = document.getElementById('submit-grades');
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Submitting...';
    submitButton.disabled = true;

    // Make API call to confirm grades
    fetch('/advisory-class/confirm-grades', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            period: selectedPeriod,
            period_type: periodType
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button to "Submitted" state
            submitButton.textContent = 'Submitted';
            submitButton.disabled = true;
            submitButton.classList.remove('submit-button-enabled');
            submitButton.classList.add('submitted-button');

            // Close the subject modal
            document.getElementById('subject-modal').style.display = 'none';

            // Refresh the grades display for any open student grades modal
            const gradesModal = document.getElementById('grades-modal');
            if (gradesModal.style.display === 'block') {
                const studentId = gradesModal.getAttribute('data-student-id');
                if (studentId) {
                    showGrades(studentId); // Refresh the grades display
                }
            }

            // Show success message
            alert('Grades have been successfully submitted and are now visible to students.');

            // Refresh the subject grades display
            updateSubjectGrades();
        } else {
            throw new Error(data.message || 'Failed to submit grades');
        }
    })
    .catch(error => {
        console.error('Error submitting grades:', error);
        alert('Failed to submit grades. Please try again.');
        
        // Reset button state on error
        submitButton.textContent = originalText;
        submitButton.disabled = false;
    })
    .finally(() => {
        // We don't need to reset the button state here anymore as we handle it in the then and catch blocks
    });
}

// Update the showGrades() function to store the student ID
function showGrades(studentId) {
    const modal = document.getElementById('grades-modal');
    modal.style.display = 'block';
    modal.setAttribute('data-student-id', studentId);

    // Add event listener for close button
    const closeButton = modal.querySelector('.close-modal');
    if (closeButton) {
        closeButton.onclick = function() {
            closeGradesModal();
        };
    }

    // Add event listener for clicking outside modal
    window.onclick = function(event) {
        if (event.target === modal) {
            closeGradesModal();
        }
    };

    // Fetch student grades
    fetch(`/advisory-class/student-grades/${studentId}`)
        .then(response => response.json())
        .then(data => {
            // Update student info
            document.getElementById('student-info').innerHTML = `
                <h3>${data.student.name}</h3>
                <p>LRN: ${data.student.lrn}</p>
            `;

            // Update student image
            const imageContainer = document.getElementById('student-image');
            if (data.student.profile_image) {
                imageContainer.innerHTML = `
                    <img src="/${data.student.profile_image}" alt="${data.student.name}'s profile picture">
                `;
            } else {
                imageContainer.innerHTML = `
                    <div class="no-image">
                        <i class='bx bx-user'></i>
                    </div>
                `;
            }

            // Update table headers based on level type
            const headerRow = document.getElementById('grades-header');
            const isJunior = data.level_type === 'junior';
            const periods = isJunior ? 4 : 2;
            const periodType = isJunior ? 'Quarter' : 'Semester';

            // Clear existing period columns
            while (headerRow.children.length > 2) {
                headerRow.removeChild(headerRow.lastChild);
            }

            // Add appropriate period columns
            for (let i = 1; i <= periods; i++) {
                const th = document.createElement('th');
                th.className = 'period-grade';
                th.textContent = `${i}${getOrdinalSuffix(i)} ${periodType}`;
                headerRow.appendChild(th);
            }

            // Add Final Rating column
            const finalRatingHeader = document.createElement('th');
            finalRatingHeader.textContent = 'Final Rating';
            headerRow.appendChild(finalRatingHeader);

            let totalFinalRating = 0;
            let subjectCount = 0;
            let subjectsWithCompleteGrades = 0;
            let totalSubjects = data.grades.length;

            const gradesList = document.getElementById('grades-list');
            gradesList.innerHTML = data.grades.map(grade => {
                let periodCells = '';
                let totalGrade = 0;
                let validPeriods = 0;
                let allPeriodsPresent = true;

                for (let i = 1; i <= periods; i++) {
                    const periodKey = `${isJunior ? 'quarter' : 'semester'}_${i}`;
                    const periodGrade = grade.grades[periodKey];
                    
                    if (periodGrade && periodGrade !== 'N/A') {
                        totalGrade += parseFloat(periodGrade);
                        validPeriods++;
                    } else {
                        allPeriodsPresent = false;
                    }

                    periodCells += `
                        <td class="period-grade">
                            <span class="grade-cell">${periodGrade || 'N/A'}</span>
                        </td>
                    `;
                }

                // Calculate Final Rating only if all periods have grades
                let finalRating = 'N/A';
                if (allPeriodsPresent && validPeriods === periods) {
                    finalRating = Math.round(totalGrade / validPeriods);
                    totalFinalRating += finalRating;
                    subjectsWithCompleteGrades++;
                }

                return `
                    <tr>
                        <td>${grade.subject_name}</td>
                        <td>${grade.teacher_name}</td>
                        ${periodCells}
                        <td class="period-grade">
                            <span class="grade-cell">${finalRating}</span>
                        </td>
                    </tr>
                `;
            }).join('');

            // Add General Average row only if all subjects have final ratings
            if (subjectsWithCompleteGrades === totalSubjects && totalSubjects > 0) {
                const generalAverage = Math.round(totalFinalRating / totalSubjects);
                gradesList.innerHTML += `
                    <tr>
                        <td colspan="${2}" style="text-align: right; font-weight: bold;">General Average:</td>
                        <td colspan="${periods}" style="text-align: right;"></td>
                        <td class="period-grade">
                            <span class="grade-cell">${generalAverage}</span>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching grades:', error);
            alert('Failed to fetch grades. Please try again.');
        });
}

// Modify your existing updateSubjectGrades function to include the submit button update
function updateSubjectGrades() {
    const selectedPeriod = document.getElementById('subject-period-select').value;
    const periodType = currentLevelType === 'junior' ? 'quarter' : 'semester';
    const subjectsList = document.getElementById('subject-grades-list');
    const submitButton = document.getElementById('submit-grades');

    // Show loading state
    subjectsList.innerHTML = '<tr><td colspan="4" class="text-center py-4">Loading...</td></tr>';

    // First check if the period is already confirmed
    fetch(`/advisory-class/check-submission-status?period=${selectedPeriod}&period_type=${periodType}`)
        .then(response => response.json())
        .then(submissionStatus => {
            // If the period is confirmed, fetch grade status
            return fetch(`/advisory-class/subject-grade-status?period=${selectedPeriod}`)
                .then(response => response.json())
                .then(subjects => {
                    subjectsList.innerHTML = subjects.map(subject => {
                        // If period is confirmed, show complete status regardless of new students
                        if (submissionStatus.submitted) {
                            return `
                                <tr>
                                    <td>${subject.subject_name}</td>
                                    <td>${subject.teacher_name}</td>
                                    <td>
                                        <span class="status-badge status-complete">Complete</span>
                                    </td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: 100%"></div>
                                        </div>
                                        <div class="progress-text">
                                            Quarter Confirmed
                                        </div>
                                    </td>
                                </tr>
                            `;
                        } else {
                            // For unconfirmed periods, show actual progress
                            const progressPercentage = (subject.submitted_count / subject.total_students) * 100;
                            return `
                                <tr>
                                    <td>${subject.subject_name}</td>
                                    <td>${subject.teacher_name}</td>
                                    <td>
                                        <span class="status-badge ${subject.status === 'complete' ? 'status-complete' : 'status-pending'}">
                                            ${subject.status === 'complete' ? 'Complete' : 'Pending'}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress-bar">
                                            <div class="progress-fill" style="width: ${progressPercentage}%"></div>
                                        </div>
                                        <div class="progress-text">
                                            ${subject.submitted_count}/${subject.total_students} students
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    }).join('');

                    // Update submit button state
                    if (submissionStatus.submitted) {
                        // If period is confirmed, show submitted state
                        submitButton.textContent = 'Submitted';
                        submitButton.disabled = true;
                        submitButton.classList.remove('submit-button-enabled');
                        submitButton.classList.add('submitted-button');
                    } else {
                        // For unconfirmed periods, enable button only if all subjects are complete
                        const allComplete = subjects.every(subject => subject.status === 'complete');
                        submitButton.textContent = 'Submit Grades';
                        submitButton.disabled = !allComplete;
                        submitButton.classList.toggle('submit-button-enabled', allComplete);
                        submitButton.classList.remove('submitted-button');
                    }
                });
        })
        .catch(error => {
            console.error('Error fetching subject grades:', error);
            subjectsList.innerHTML = '<tr><td colspan="4" class="text-center text-red-600">Failed to load grades</td></tr>';
        });
}

// Remove the duplicate closeGradesModal function and keep only this one
function closeGradesModal() {
    const modal = document.getElementById('grades-modal');
    if (modal) {
        modal.style.display = 'none';
        // Clean up event listeners
        window.onclick = null;
        const closeButton = modal.querySelector('.close-modal');
        if (closeButton) {
            closeButton.onclick = null;
        }
    }
}

    function getGradeStatus(grade) {
        if (!grade) return 'Pending';
        if (grade >= 90) return 'Outstanding';
        if (grade >= 85) return 'Very Good';
        if (grade >= 80) return 'Good';
        if (grade >= 75) return 'Passed';
        return 'Failed';
    }

    function getGradeStatusClass(grade) {
        return 'grade-cell'; // Single class for all grades
    }

    function getOrdinalSuffix(i) {
        const j = i % 10,
              k = i % 100;
        if (j == 1 && k != 11) {
            return "st";
        }
        if (j == 2 && k != 12) {
            return "nd";
        }
        if (j == 3 && k != 13) {
            return "rd";
        }
        return "th";
    }

    function openGradesModal(studentId, studentName, lrn) {
        document.getElementById('gradesModal').classList.remove('hidden');
        document.getElementById('studentName').textContent = studentName;
        document.getElementById('studentLRN').textContent = 'LRN: ' + lrn;
        
        // Show loading state
        const tbody = document.querySelector('#gradesTable tbody');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    <div class="flex items-center justify-center">
                        <div class="loading-spinner mr-3"></div>
                        <span>Loading grades...</span>
                    </div>
                </td>
            </tr>
        `;

        // Fetch grades data
        fetch(`/api/student/${studentId}/grades`)
            .then(response => response.json())
            .then(data => {
                tbody.innerHTML = '';
                
                if (data.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No grades available
                            </td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(grade => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border-b">${grade.subject_name}</td>
                        <td class="border-b">${grade.teacher_name}</td>
                        <td class="border-b text-center">${formatGrade(grade.q1)}</td>
                        <td class="border-b text-center">${formatGrade(grade.q2)}</td>
                        <td class="border-b text-center">${formatGrade(grade.q3)}</td>
                        <td class="border-b text-center">${formatGrade(grade.q4)}</td>
                    `;
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error fetching grades:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-red-500">
                            Error loading grades. Please try again.
                        </td>
                    </tr>
                `;
            });
    }

    function formatGrade(grade) {
        if (!grade) return '-';
        if (grade === 'drp') return 'DRP';
        if (grade === 'trf') return 'TRF';
        return Math.floor(grade);
    }
</script>
@endsection
