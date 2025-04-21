@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header with Filter Button -->
            <div class="section-header">
                <div class="header-content">
                    <h1>Subject Management</h1>
                </div>
            </div>

            <!-- Subjects List -->
            <div id="subjects-container" class="info-card bg-blue-50 mb-6">
                <h2 class="info-card-title text-blue-800">Your Subjects</h2>
                <div id="subjects-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Subjects will be populated here -->
                </div>
            </div>

            <!-- Students List -->
            <div id="students-container" style="display: none;">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-header-left">
                            <h2>Student Grades</h2>
                            <div id="adviser-info" class="text-sm mt-2 text-gray-700 font-medium"></div>
                            <div id="semester-info" class="text-sm mt-1 text-gray-600"></div>
                        </div>
                        <div class="table-header-right">
                            <!-- Period selector -->
                            <div class="period-selector">
                                <label for="period-select">Select Period:</label>
                                <select id="period-select" class="form-select" onchange="updatePeriodDisplay()">
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>
                            <button onclick="saveGrades()" class="save-grades-button">
                                Save Grades
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                    <th>Student Name</th>
                                    <th>Grade</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="students-list" class="divide-y divide-gray-200">
                                <!-- Students will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No Class Message -->
            <div id="no-class-message" class="no-data-message">
                <div class="flex items-center justify-center">
                    <i class='bx bx-info-circle text-yellow-400 text-4xl mr-3'></i>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700">No Class</h3>
                        <p class="text-gray-500 mt-2">There are no classes available for the selected filters.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    flex-direction: column;
    gap: 16px;
    width: 100%;
}

@media (min-width: 768px) {
    .header-content {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.section-header h1 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0;
}

/* Filter Controls */
.filter-controls {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
}

@media (min-width: 768px) {
    .filter-controls {
        flex-direction: row;
        width: auto;
    }
}

.level-selector {
    display: flex;
    width: 100%;
    gap: 8px;
}

@media (min-width: 768px) {
    .level-selector {
        width: auto;
    }
}

.level-btn {
    flex: 1;
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    border: 1px solid #00b050;
    transition: all 0.2s ease;
    text-align: center;
    white-space: nowrap;
}

@media (min-width: 768px) {
    .level-btn {
        flex: none;
    }
}

.level-btn.active {
    background-color: #00b050;
    color: white;
}

.level-btn:not(.active) {
    background-color: white;
    color: #00b050;
}

.level-btn:hover:not(.active) {
    background-color: #e8f8ee;
}

/* Filter Button Styles */
.filter-button {
    width: 100%;
    height: 40px;
    border-radius: 4px;
    background-color: #00b050;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.2s;
}

@media (min-width: 768px) {
    .filter-button {
        width: 40px;
        border-radius: 50%;
    }
}

.filter-button:hover {
    background-color: #009040;
}

.filter-button i {
    font-size: 20px;
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

.modal-footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e0e0e0;
    text-align: right;
}

/* Button Group Styles */
.button-group {
    margin-bottom: 16px;
}

.button-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.btn {
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
    flex: 1;
    min-width: 80px;
    text-align: center;
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

/* Subject Button Styles */
.subject-button {
    background-color:#009040;
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
    background-color:rgb(5, 168, 73);
}

.subject-button.selected {
    background-color:rgb(191, 209, 199);
    box-shadow: 0 0 0 3px rgb(191, 209, 199);
    transform: scale(1.05);
}

/* Save Grades Button Style */
.save-grades-button {
    background-color:#009040;
    color: white;
    border: none;
    border-radius: 6px;
    padding: 8px 16px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s;
}

.save-grades-button:hover {
    background-color:rgb(5, 168, 73);
}

.save-grades-button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
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
    display: flex;
    flex-direction: column;
    gap: 16px;
}

@media (min-width: 768px) {
    .table-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
    }
}

.table-header-right {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
}

@media (min-width: 768px) {
    .table-header-right {
        flex-direction: row;
        align-items: center;
        width: auto;
    }
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

.student-table input {
    width: 80px;
    padding: 4px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
    transition: border-color 0.2s ease;
}

.student-table input.border-yellow-500 {
    border: 2px solid #f59e0b; /* Amber/yellow color */
    background-color: #fef3c7; /* Light yellow background */
}

.student-table input.border-red-500 {
    border: 2px solid #ef4444; /* Red color */
    background-color: #fee2e2; /* Light red background */
}

/* Status Badge Styles */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #e5e7eb; /* Light gray border */
}

.status-badge i {
    margin-right: 0.25rem;
}

/* Period Selector Styles */
.period-selector {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 100%;
}

@media (min-width: 768px) {
    .period-selector {
        flex-direction: row;
        align-items: center;
        width: auto;
        gap: 12px;
    }
}

.period-selector label {
    font-weight: 500;
    color: #333;
    white-space: nowrap;
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

@media (min-width: 768px) {
    .form-select {
        width: auto;
        min-width: 150px;
    }
}
.grade-input-container {
    position: relative;
    width: 100px;
}

.grade-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: none;
    z-index: 1000;
}

.grade-suggestion {
    padding: 8px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.grade-suggestion:hover {
    background-color: #f0f0f0;
}
/* Add new styles for completed subjects */
.subject-button.completed {
    background-color: #059669;
}

.subject-button.completed:hover {
    background-color: #047857;
}

/* No Data Message */
.no-data-message {
    padding: 48px 24px;
    text-align: center;
    background-color: #fff;
    border-radius: 8px;
    border: 2px dashed #e0e0e0;
    margin-top: 20px;
}

/* Add these styles */
.subject-info-item {
    padding: 8px;
    border-radius: 6px;
}

.subject-info-item span {
    display: block;
    margin-bottom: 4px;
}

.subject-info-item h3 {
    font-size: 1.1rem;
}

/* Add these styles to your existing styles section */
.subject-card {
    margin-bottom: 10px;
}

.subject-info {
    text-align: left;
    width: 100%;
}

.subject-name {
    font-weight: 500;
    margin-bottom: 4px;
}

.subject-button {
    width: 100%;
    text-align: left;
    padding: 12px;
}

/* Level Selection */
.level-radio-buttons {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.level-radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 500;
    color: #555;
}

.level-radio {
    display: none;
}

.radio-custom {
    width: 18px;
    height: 18px;
    border: 2px solid #00b050;
    border-radius: 50%;
    display: inline-block;
    position: relative;
}

.level-radio:checked + .radio-custom::after {
    content: '';
    width: 10px;
    height: 10px;
    background: #00b050;
    border-radius: 50%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.level-radio:checked ~ span:last-child {
    color: #00b050;
    font-weight: 600;
}

/* Subject Card Styles */
.subject-card {
    transition: all 0.2s ease-in-out;
    background-color: #f0f9f4;
    border: 1px solid #e2e8f0;
    border-left: 4px solid #00b050;
}

.subject-card:hover {
    transform: translateY(-2px);
    background-color: #e8f5ec;
    border-color: #00b050;
    box-shadow: 0 4px 6px rgba(0, 176, 80, 0.1);
}

.subject-card button {
    outline: none;
    width: 100%;
    height: 100%;
    padding: 1rem;
}

.subject-card button:focus {
    outline: none;
    background-color: #e8f5ec;
}

.subject-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.subject-info h3 {
    color: #1a1a1a;
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.subject-info .text-sm {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #4b5563;
}

.subject-info .bx {
    color: #00b050;
    font-size: 1.1rem;
}

.subject-btn.completed {
    background-color: transparent;
}

.subject-btn.completed:hover {
    background-color: rgba(0, 176, 80, 0.05);
}

/* Selected state styles */
.subject-card button.selected {
    background-color: #d1e7dd;
    border-color: #00b050;
}

.subject-card button.selected .subject-info h3 {
    color: #0a4429;
}

.subject-card button.selected .subject-info .text-sm {
    color: #065f46;
}

.subject-card button.selected .subject-info .bx {
    color: #047857;
}

.subject-card.selected {
    background-color: #d1e7dd;
    border-color: #00b050;
    box-shadow: 0 0 0 2px rgba(0, 176, 80, 0.3);
}

.subject-btn.selected {
    background-color: #d1e7dd !important;
    border-color: #00b050;
    box-shadow: 0 0 0 2px rgba(0, 176, 80, 0.3);
}

/* Update the subjects container */
#subjects-container {
    background-color: #ffffff;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

#subjects-list {
    gap: 1rem;
}

/* Info Card Styles Update */
.info-card {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.info-card-title {
    color: #00b050;
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
}

/* Grid Layout Update */
@media (min-width: 768px) {
    #subjects-list {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (min-width: 1024px) {
    #subjects-list {
        grid-template-columns: repeat(3, 1fr);
    }
}

.table-header-left {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

#semester-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

#semester-info::before {
    content: '•';
    color: #00b050;
}
</style>

<script>
// Global variables
window.currentSubject = null;
window.currentGrades = {};
let isGradesConfirmed = false;

document.addEventListener('DOMContentLoaded', () => {
    fetchSubjects();
});

function fetchSubjects() {
    fetch('/subjects/by-year')
        .then(response => response.json())
        .then(data => {
            const subjectsList = document.getElementById('subjects-list');
            const noClassMessage = document.getElementById('no-class-message');
            const subjectsContainer = document.getElementById('subjects-container');

            if (data && data.length > 0) {
                subjectsList.innerHTML = data.map(subject => {
                    // Extract semester information from the display name
                    const displayNameParts = subject.display_name.split(' - ');
                    const semesterInfo = displayNameParts.find(part => part.toLowerCase().includes('sem')) || '';
                    const semester = semesterInfo.toLowerCase().includes('1st sem') ? '1st Semester' : 
                                   semesterInfo.toLowerCase().includes('2nd sem') ? '2nd Semester' : '';
                    
                    return `
                    <div class="subject-card bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow ${subject.completed ? 'bg-green-50' : ''}">
                        <button
                            onclick="selectSubject(${subject.id}, '${subject.display_name}', '${subject.level_type}')"
                            class="subject-btn w-full text-left"
                            data-id="${subject.id}"
                            data-level-type="${subject.level_type}"
                        >
                            <div class="subject-info">
                                <h3 class="text-lg font-semibold text-gray-800">${subject.name}</h3>
                                <div class="mt-2 space-y-1">
                                    <div class="text-sm text-gray-600">
                                        <i class='bx bx-book-alt'></i>
                                        ${displayNameParts[1] || ''} <!-- Year Level -->
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i class='bx bx-group'></i>
                                        Section: ${displayNameParts[2] || ''} <!-- Section -->
                                    </div>
                                    ${subject.level_type === 'senior' ? `
                                    <div class="text-sm text-gray-600">
                                        <i class='bx bx-calendar'></i>
                                        ${semester}
                                    </div>
                                    ` : ''}
                                    ${subject.adviser_name ? 
                                        `<div class="text-sm text-gray-600">
                                            <i class='bx bx-user'></i>
                                            Adviser: ${subject.adviser_name}
                                        </div>` : ''
                                    }
                                </div>
                            </div>
                        </button>
                    </div>
                `}).join('');
                subjectsContainer.style.display = 'block';
                noClassMessage.style.display = 'none';
            } else {
                subjectsContainer.style.display = 'none';
                noClassMessage.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error fetching subjects:', error);
            alert('Failed to fetch subjects. Please try again.');
        });
}

function selectSubject(subjectId, subjectName, levelType) {
    window.currentSubject = subjectId;
    window.currentLevelType = levelType;
    
    // Update UI to show selected subject
    document.querySelectorAll('.subject-btn').forEach(btn => {
        btn.classList.remove('selected');
        if (btn.dataset.id === subjectId.toString()) {
            btn.classList.add('selected');
            
            // Get and display semester information if it's a senior high class
            if (levelType === 'senior') {
                const displayNameParts = subjectName.split(' - ');
                const semesterMatch = displayNameParts.join(' ').match(/(1st|2nd)\s*sem/i);
                
                // Update semester info display
                const semesterInfoElement = document.getElementById('semester-info');
                if (semesterInfoElement && semesterMatch) {
                    const semester = semesterMatch[1].toLowerCase() === '1st' ? '1st Semester' : '2nd Semester';
                    semesterInfoElement.textContent = semester;
                    semesterInfoElement.style.display = 'block';
                }
            } else {
                // Hide semester info for junior high
                const semesterInfoElement = document.getElementById('semester-info');
                if (semesterInfoElement) {
                    semesterInfoElement.style.display = 'none';
                }
            }
        }
    });

    // Update period selector based on subject level type
    updatePeriodSelector(levelType);

    const studentsContainer = document.getElementById('students-container');
    studentsContainer.style.display = 'block';

    document.getElementById('students-list').innerHTML = '';
    fetchStudents(subjectId);
}

function fetchStudents(subjectId) {
    let currentPeriod = document.getElementById('period-select').value;

    // Show loading indicator
    const studentsList = document.getElementById('students-list');
    studentsList.innerHTML = `<tr><td colspan="4" class="text-center p-4"><i class='bx bx-loader-alt bx-spin'></i> Loading students...</td></tr>`;

    console.log(`Fetching students for subject ${subjectId}, period ${currentPeriod}, level type ${window.currentLevelType}`);

    // First check if grades are confirmed for this period
    fetch(`/advisory-class/check-submission-status?period=${currentPeriod}&period_type=${window.currentLevelType === 'junior' ? 'quarter' : 'semester'}`)
        .then(response => response.json())
        .then(submissionStatus => {
            // Set the global confirmation status
            isGradesConfirmed = submissionStatus.submitted;
            
            // Proceed with fetching students
            return fetch(`/subjects/${subjectId}/students`).then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.error || 'Failed to fetch students data');
                    });
                }
                return response.json().then(data => ({ data, isConfirmed: submissionStatus.submitted }));
            });
        })
        .then(({ data, isConfirmed }) => {
            console.log("API response:", data);
            
            if (!data) throw new Error('Subject data is empty');
            if (!data.students || !Array.isArray(data.students)) {
                throw new Error('Invalid student data received');
            }
            
            window.currentLevelType = data.level_type || 'junior';
            console.log(`Updated level type from API: ${window.currentLevelType}`);

            // Display adviser info
            const adviserInfo = document.getElementById('adviser-info');
            if (data.adviser_name) {
                adviserInfo.textContent = `Class Adviser: ${data.adviser_name}`;
                adviserInfo.style.display = 'block';
            } else {
                adviserInfo.style.display = 'none';
            }

            // Display semester info for senior high
            const semesterInfoElement = document.getElementById('semester-info');
            if (window.currentLevelType === 'senior' && data.semester) {
                const semesterText = data.semester === 1 ? '1st Semester' : '2nd Semester';
                semesterInfoElement.textContent = semesterText;
                semesterInfoElement.style.display = 'block';
            } else {
                semesterInfoElement.style.display = 'none';
            }

            const currentPeriodNum = parseInt(currentPeriod);
            const periodType = window.currentLevelType === 'junior' ? 'quarter' : 'semester';

            // Check all previous periods' confirmation status
            const checkPreviousPeriods = async () => {
                let allPreviousPeriodsConfirmed = true;
                let unconfirmedPeriod = null;

                // Check each previous period
                for (let i = 1; i < currentPeriodNum; i++) {
                    try {
                        const response = await fetch(`/advisory-class/check-submission-status?period=${i}&period_type=${periodType}`);
                        const status = await response.json();
                        if (!status.submitted) {
                            allPreviousPeriodsConfirmed = false;
                            unconfirmedPeriod = i;
                            break;
                        }
                    } catch (error) {
                        console.error(`Error checking period ${i}:`, error);
                        allPreviousPeriodsConfirmed = false;
                        break;
                    }
                }

                return { allPreviousPeriodsConfirmed, unconfirmedPeriod };
            };

            checkPreviousPeriods().then(({ allPreviousPeriodsConfirmed, unconfirmedPeriod }) => {
                if (data.students.length === 0) {
                    studentsList.innerHTML = `<tr><td colspan="4" class="text-center p-4">No students found in this class</td></tr>`;
                    return;
                }

                // Add warning message if grades are confirmed
                if (isConfirmed) {
                    const warningDiv = document.createElement('div');
                    warningDiv.className = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4';
                    warningDiv.innerHTML = `
                        <p class="font-bold">Grades Confirmed</p>
                        <p>These grades have been confirmed by the adviser and can no longer be modified.</p>
                    `;
                    studentsList.parentElement.insertBefore(warningDiv, studentsList);
                } else if (!allPreviousPeriodsConfirmed && currentPeriodNum > 1) {
                    // Add warning for unconfirmed previous periods
                    const warningDiv = document.createElement('div');
                    warningDiv.className = 'bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-4';
                    warningDiv.innerHTML = `
                    
                    `;
                    studentsList.parentElement.insertBefore(warningDiv, studentsList);
                }

                studentsList.innerHTML = data.students.map(student => {
                    const periodGrade = student.grades[`${periodType}_${currentPeriod}`] || '';
                    const isDisabled = isConfirmed || (!allPreviousPeriodsConfirmed && currentPeriodNum > 1);
                    const disabledReason = isConfirmed ? 'Grades are confirmed' : 
                                         (!allPreviousPeriodsConfirmed && currentPeriodNum > 1) ? `Previous ${periodType} not confirmed` : '';

                    return `
                        <tr>
                            <td>${student.lrn}</td>
                            <td>${student.name}</td>
                            <td>
                                <div class="grade-input-container">
                                    <input
                                        type="text"
                                        class="grade-input ${isDisabled ? 'bg-gray-100' : ''}"
                                        value="${periodGrade}"
                                        ${isDisabled ? 'disabled' : ''}
                                        data-student-id="${student.id}"
                                        oninput="showGradeSuggestions(this)"
                                        onkeydown="validateGradeInput(event)"
                                        onfocus="showGradeSuggestions(this)"
                                        onblur="hideSuggestions(this)"
                                        onpaste="handleInputPaste(event)"
                                        maxlength="3"
                                        title="${isDisabled ? disabledReason : ''}"
                                    >
                                    <div class="grade-suggestions">
                                        <!-- Dynamic options will appear here -->
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="grade-status-${student.id} status-badge ${getStatusClass(periodGrade)}">
                                    ${isDisabled ? disabledReason : getStatus(periodGrade)}
                                </span>
                            </td>
                        </tr>
                    `;
                }).join('');

                // Disable save button if grades are confirmed or previous periods aren't confirmed
                const saveButton = document.querySelector('.save-grades-button');
                if (saveButton) {
                    const shouldDisableButton = isConfirmed || (!allPreviousPeriodsConfirmed && currentPeriodNum > 1);
                    saveButton.disabled = shouldDisableButton;
                    saveButton.classList.toggle('opacity-50', shouldDisableButton);
                    if (isConfirmed) {
                        saveButton.title = 'Grades have been confirmed by the adviser';
                    } else if (!allPreviousPeriodsConfirmed && currentPeriodNum > 1) {
                        saveButton.title = `Previous ${periodType} must be confirmed first`;
                    }
                }

                // Update current grades
                window.currentGrades = {};
                data.students.forEach(student => {
                    const periodGrade = student.grades[`${periodType}_${currentPeriod}`];
                    if (periodGrade !== null && periodGrade !== undefined) {
                        window.currentGrades[student.id] = periodGrade;
                    }
                });
            });
        })
        .catch(error => {
            console.error('Error fetching students:', error);
            studentsList.innerHTML = `<tr><td colspan="4" class="text-center p-4 text-red-600">
                <i class='bx bx-error-circle'></i> Error: ${error.message || 'Failed to fetch students. Please try again.'}
            </td></tr>`;
        });
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

function updatePeriodDisplay() {
    if (window.currentSubject) {
        fetchStudents(window.currentSubject);
    }
}

function updateGrade(studentId, grade) {
    // If grades are confirmed, don't allow updates
    if (isGradesConfirmed) {
        return;
    }

    // Convert numeric string to number if it's not 'drp' or 'trf'
    const finalGrade = ['drp', 'trf'].includes(grade) ? grade : Math.floor(parseFloat(grade) || 0);
    window.currentGrades[studentId] = finalGrade;

    // Update the input value to ensure it shows as a whole number
    const input = document.querySelector(`input[data-student-id="${studentId}"]`);
    if (input && typeof finalGrade === 'number') {
        input.value = finalGrade.toString(); // Remove any decimal points
    }

    const statusElement = document.querySelector(`.grade-status-${studentId}`);
    if (statusElement) {
        statusElement.className = `grade-status-${studentId} status-badge ${getStatusClass(finalGrade)}`;
        statusElement.textContent = getStatus(finalGrade);
    }
}

function getStatus(grade) {
    if (grade === 'drp') return 'Dropped';
    if (grade === 'trf') return 'Transferred';
    if (!grade && grade !== 0) return 'Not Graded';
    if (grade >= 75) return 'Passed';
    return 'Failed';
}

function getStatusClass(grade) {
    if (grade === 'drp') return 'text-gray-600';
    if (grade === 'trf') return 'text-gray-600';
    if (!grade && grade !== 0) return 'text-gray-600';
    if (grade >= 75) return 'text-gray-600';
    return 'text-red-600'; // Keep red for failing grades only
}

function saveGrades() {
    console.log("Save grades triggered. Current level type:", window.currentLevelType);
    
    if (!window.currentSubject) {
        console.error('No subject selected');
        alert('Please select a subject first');
        return;
    }

    const currentPeriod = document.getElementById('period-select').value;
    const periodType = window.currentLevelType === 'junior' ? 'quarter' : 'semester';

    // First check if grades are confirmed
    fetch(`/advisory-class/check-submission-status?period=${currentPeriod}&period_type=${periodType}`)
        .then(response => response.json())
        .then(submissionStatus => {
            if (submissionStatus.submitted) {
                alert('Cannot modify grades. These grades have been confirmed by the adviser.');
                return Promise.reject('Grades are confirmed');
            }

    const grades = [];
            let hasSingleDigitGrade = false;

    // Get all grade inputs
    const gradeInputs = document.querySelectorAll('.grade-input');

    gradeInputs.forEach(input => {
        if (input.value) {
                    // Handle special values (drp/trf)
                    if (['drp', 'trf'].includes(input.value.toLowerCase())) {
                        grades.push({
                            student_id: parseInt(input.dataset.studentId),
                            grade: input.value.toLowerCase(),
                            period: parseInt(currentPeriod),
                            period_type: periodType
                        });
                        return;
                    }

                    // Check if the input is a single digit for numeric grades
                    if (input.value.length === 1) {
                        hasSingleDigitGrade = true;
                        input.classList.add('border-red-500');
                    } else {
                        input.classList.remove('border-red-500');
                        // Ensure whole number grade
                        const grade = Math.floor(parseFloat(input.value));
            if (!isNaN(grade)) {
                            // Update input display to show whole number
                            input.value = grade.toString();
                grades.push({
                    student_id: parseInt(input.dataset.studentId),
                    grade: grade,
                    period: parseInt(currentPeriod),
                    period_type: periodType
                });
                        }
            }
        }
    });

            if (hasSingleDigitGrade) {
                // Show error message
                const studentsList = document.getElementById('students-list');
                const errorMessage = document.createElement('div');
                errorMessage.className = 'p-3 mb-3 bg-red-100 text-red-800 rounded';
                errorMessage.innerHTML = `
                    <i class="bx bx-error-circle"></i> Error: Cannot save incomplete grades. Please enter complete grades (70-100).
                `;
                studentsList.parentNode.insertBefore(errorMessage, studentsList);
                
                // Remove error message after 5 seconds
                setTimeout(() => {
                    errorMessage.remove();
                }, 5000);
                
                return Promise.reject('Incomplete grades');
            }

    if (grades.length === 0) {
        alert('No grades to save');
                return Promise.reject('No grades to save');
    }

    // Get the CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Disable the save button while saving
            const saveButton = document.querySelector('.save-grades-button');
    if (saveButton) {
        saveButton.disabled = true;
                saveButton.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Saving...';
    }

    // Create the request data
    const requestData = {
                subject_id: window.currentSubject,
        grades: grades
    };

            console.log("Saving grade data:", requestData);

            return fetch('/subjects/update-grades', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
            });
    })
    .then(response => {
        if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Failed to save grades');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }

            // Show success message
            const studentsList = document.getElementById('students-list');
            const successMessage = document.createElement('div');
            successMessage.className = 'p-3 mb-3 bg-green-100 text-green-800 rounded';
            successMessage.innerHTML = '<i class="bx bx-check-circle"></i> Grades saved successfully!';
            studentsList.parentNode.insertBefore(successMessage, studentsList);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successMessage.remove();
            }, 3000);

        // Update UI to reflect saved state
        if (data.completed) {
                const subjectBtn = document.querySelector(`.subject-btn[data-id="${window.currentSubject}"]`);
            if (subjectBtn) {
                subjectBtn.classList.add('completed');
            }
        }

        // Refresh the students list to show updated statuses
            fetchStudents(window.currentSubject);
    })
    .catch(error => {
            if (error === 'Grades are confirmed' || error === 'Incomplete grades' || error === 'No grades to save') {
                // These errors are already handled
                return;
            }

        console.error('Error saving grades:', error);
            
            // Show error message
            const studentsList = document.getElementById('students-list');
            const errorMessage = document.createElement('div');
            errorMessage.className = 'p-3 mb-3 bg-red-100 text-red-800 rounded';
            errorMessage.innerHTML = `<i class="bx bx-error-circle"></i> Error: ${error.message || 'Failed to save grades. Please try again.'}`;
            studentsList.parentNode.insertBefore(errorMessage, studentsList);
            
            // Remove error message after 5 seconds
            setTimeout(() => {
                errorMessage.remove();
            }, 5000);
    })
    .finally(() => {
        // Re-enable the save button
            const saveButton = document.querySelector('.save-grades-button');
        if (saveButton) {
            saveButton.disabled = false;
                saveButton.innerHTML = 'Save Grades';
        }
    });
}

function updatePeriodSelector(levelType) {
    console.log("Updating period selector for level type:", levelType);
    const periodSelect = document.getElementById('period-select');
    periodSelect.innerHTML = '';

    // Get the class details from the selected subject's display name
    const selectedSubject = document.querySelector('.subject-btn.selected');
    if (!selectedSubject) return;

    const displayName = selectedSubject.querySelector('.subject-info h3').textContent;
    const yearLevelMatch = displayName.match(/Grade (\d+)/);
    const yearLevel = yearLevelMatch ? parseInt(yearLevelMatch[1]) : null;

    // Get semester information from the subject display
    const semesterInfo = document.querySelector('.subject-btn.selected .text-sm');
    const semesterText = semesterInfo ? semesterInfo.textContent.toLowerCase() : '';
    const isSemesterOne = semesterText.includes('1st sem');
    const isSemesterTwo = semesterText.includes('2nd sem');

    // For senior high (Grade 11-12)
    if (levelType === 'senior' && (yearLevel === 11 || yearLevel === 12)) {
        // Show quarters based on semester
        if (isSemesterOne || isSemesterTwo) {
            // Show quarters for the specific semester
            for (let i = 1; i <= 2; i++) {
                const option = document.createElement('option');
                option.value = i;
                const quarterNum = isSemesterOne ? i : i + 2;
                option.textContent = `${quarterNum}${getOrdinalSuffix(quarterNum)} Quarter`;
                periodSelect.appendChild(option);
            }
        } else {
            // Fallback to showing all quarters if semester info is not available
            for (let i = 1; i <= 4; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.textContent = `${i}${getOrdinalSuffix(i)} Quarter`;
                periodSelect.appendChild(option);
            }
        }
    } else if (levelType === 'junior') {
        // Junior High: 4 quarters
        for (let i = 1; i <= 4; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.textContent = `${i}${getOrdinalSuffix(i)} Quarter`;
            periodSelect.appendChild(option);
        }
    }
}

function showGradeSuggestions(input) {
    // If grades are confirmed, prevent any modifications
    if (isGradesConfirmed) {
        input.blur();
        return;
    }

    let value = input.value.toLowerCase();
    const studentId = input.dataset.studentId;
    
    // Clear any existing warning styles
    input.classList.remove('border-yellow-500');
    input.title = '';
    
    const container = input.parentElement;
    const suggestions = container.querySelector('.grade-suggestions');
    suggestions.innerHTML = '';

    // Handle 'd' or 't' inputs
    if (value === 'd') {
        const div = document.createElement('div');
        div.className = 'grade-suggestion';
        div.textContent = 'drp';
        div.onmousedown = () => {
            input.value = 'drp';
            updateGrade(studentId, 'drp');
            suggestions.style.display = 'none';
        };
        suggestions.appendChild(div);
        suggestions.style.display = 'block';
        return;
    }

    if (value === 't') {
        const div = document.createElement('div');
        div.className = 'grade-suggestion';
        div.textContent = 'trf';
        div.onmousedown = () => {
            input.value = 'trf';
            updateGrade(studentId, 'trf');
            suggestions.style.display = 'none';
        };
        suggestions.appendChild(div);
        suggestions.style.display = 'block';
        return;
    }

    // Handle numeric inputs - remove any non-numeric characters
    if (value) {
        const filteredValue = value.replace(/[^0-9]/g, '');
        if (filteredValue !== value) {
            input.value = filteredValue;
            value = filteredValue;
        }
    }
    
    if (value.length === 1) {
        if (!['7', '8', '9', '1'].includes(value)) {
            input.value = '';
            input.title = 'First digit must be 7, 8, 9, or 1';
            return;
        }
        input.classList.add('border-yellow-500');
        input.title = 'Please enter a complete grade (70-100)';
        
        const digit = parseInt(value);
        let options = [];

        if (digit === 7) options = range(70, 79);
        else if (digit === 8) options = range(80, 89);
        else if (digit === 9) options = range(90, 99);
        else if (digit === 1) options = [100];

        if (options.length > 0) {
            options.forEach(opt => {
                const div = document.createElement('div');
                div.className = 'grade-suggestion';
                div.textContent = opt;
                div.onmousedown = () => {
                    input.value = opt;
                    input.classList.remove('border-yellow-500');
                    input.title = '';
                    updateGrade(studentId, opt);
                    suggestions.style.display = 'none';
                };
                suggestions.appendChild(div);
            });

            // Add separator before special options
            const separator = document.createElement('div');
            separator.className = 'grade-suggestion-separator';
            separator.style.borderTop = '1px solid #e0e0e0';
            separator.style.margin = '4px 0';
            suggestions.appendChild(separator);

            // Add DRP and TRF at the bottom
            const specialOptions = [
                { display: 'drp', value: 'drp' },
                { display: 'trf', value: 'trf' }
            ];

            specialOptions.forEach(opt => {
                const div = document.createElement('div');
                div.className = 'grade-suggestion';
                div.textContent = opt.display;
                div.onmousedown = () => {
                    input.value = opt.value;
                    updateGrade(studentId, opt.value);
                    suggestions.style.display = 'none';
                };
                suggestions.appendChild(div);
            });
        }
            suggestions.style.display = 'block';
            return;
    }

    if (value) {
        const numValue = parseInt(value);
        if (numValue < 70 && value.length >= 2) {
            input.value = '70';
            updateGrade(studentId, 70);
        } else if (numValue > 100) {
            input.value = '100';
            updateGrade(studentId, 100);
        } else if (!isNaN(numValue)) {
            const wholeNumber = Math.floor(numValue);
            input.value = wholeNumber.toString();
            updateGrade(studentId, wholeNumber);
        }
    }

    if (!value || value.length !== 1) {
    suggestions.style.display = 'none';
    }
}

function validateGradeInput(e) {
    // If grades are confirmed, prevent any input
    if (isGradesConfirmed) {
        e.preventDefault();
        return;
    }
    
    // Allow control keys: backspace, delete, tab, escape, enter, arrows
    if ([46, 8, 9, 27, 13].includes(e.keyCode) ||
        // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
        (e.keyCode === 65 && e.ctrlKey === true) ||
        (e.keyCode === 67 && e.ctrlKey === true) ||
        (e.keyCode === 86 && e.ctrlKey === true) ||
        (e.keyCode === 88 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        return;
    }
    
    const input = e.target;
    const keyValue = e.key.toLowerCase();
    const currentValue = input.value.toLowerCase();
    
    // Block decimal point
    if (keyValue === '.') {
            e.preventDefault();
            return;
        }
    
    // If the current value is drp or trf, prevent any additional input
    if (['drp', 'trf'].includes(currentValue)) {
        e.preventDefault();
        return;
    }

    // Allow 'd' or 't' as first character
    if (currentValue.length === 0 && ['d', 't'].includes(keyValue)) {
        return;
    }
    
    const selectionStart = input.selectionStart || 0;
    
    // If this is the first digit being entered
    if (currentValue.length === 0 || (selectionStart === 0 && input.selectionEnd === currentValue.length)) {
        // Only allow 7, 8, 9, 1, d, or t as first character
        if (!['7', '8', '9', '1', 'd', 't'].includes(keyValue)) {
        e.preventDefault();
            input.title = 'First character must be 7, 8, 9, 1, d, or t';
        return;
        }
    }
    
    // If not d or t, ensure it's a number
    if (!['d', 't'].includes(keyValue) && !/^\d$/.test(keyValue)) {
        e.preventDefault();
        return;
    }
    
    // Restrict input to 3 characters max
    if (currentValue.length >= 3 && selectionStart >= 3) {
            e.preventDefault();
            return;
    }
    
    // If it's a number, check the value
    if (/^\d$/.test(keyValue)) {
        const newValue = currentValue.substring(0, selectionStart) + keyValue + currentValue.substring(input.selectionEnd || selectionStart);
        if (newValue.length >= 2) {
            const numValue = parseInt(newValue);
            if (numValue > 100) {
            e.preventDefault();
            return;
            }
        }
    }
}

function handleInputPaste(event) {
    // If grades are confirmed, prevent paste
    if (isGradesConfirmed) {
        event.preventDefault();
        return;
    }
    
    setTimeout(() => {
        const input = event.target;
        let value = input.value.trim().toLowerCase();
        
        // Check for special values first
        if (['drp', 'trf', 'd', 't'].includes(value)) {
            if (value === 'd') value = 'drp';
            if (value === 't') value = 'trf';
            input.value = value;
            updateGrade(input.dataset.studentId, value);
            return;
        }
        
        // Handle numeric values - remove any non-numeric characters
        value = value.replace(/[^0-9]/g, '');
        
        if (value.length > 0) {
            const firstDigit = value[0];
            if (!['7', '8', '9', '1'].includes(firstDigit)) {
            input.value = '';
                input.title = 'First digit must be 7, 8, 9, or 1';
            return;
            }
        }
        
        if (value.length === 1) {
            input.value = value;
            input.classList.add('border-yellow-500');
            input.title = 'Please enter a complete grade (70-100)';
            return;
        }
        
        const numValue = parseInt(value);
        if (numValue < 70) {
            input.value = '70';
            updateGrade(input.dataset.studentId, 70);
        } else if (numValue > 100) {
            input.value = '100';
            updateGrade(input.dataset.studentId, 100);
        } else {
            // Ensure whole number display
            const wholeNumber = Math.floor(numValue);
            input.value = wholeNumber.toString();
            updateGrade(input.dataset.studentId, wholeNumber);
        }
        
        input.classList.remove('border-yellow-500');
        input.title = '';
    }, 0);
}

function hideSuggestions(input) {
    // Small delay to allow click events to register
    setTimeout(() => {
        input.parentElement.querySelector('.grade-suggestions').style.display = 'none';
    }, 200);
}

function range(start, end) {
    return Array.from({length: end - start + 1}, (_, i) => start + i);
}
</script>
@endsection
