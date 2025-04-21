@extends('layouts.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="home-section">
<div class="container">
    <div class="main-card">
        <!-- Search and Add Class -->
        <div class="search-add-container">
            <div class="search-container">
                <i class='bx bx-search search-icon'></i>
                <input type="text" class="search-input" placeholder="Search LRN...">
            </div>
            <button class="btn btn-primary add-class-btn">Add Class</button>
        </div>

        <!-- Level Selection Header -->
        <div class="level-selection-container">
            <h1>Class Selection</h1>
            <div class="level-radio-buttons">
                <label class="level-radio-label">
                    <input type="radio" name="level" value="junior" checked class="level-radio">
                    <span class="radio-custom"></span>
                    Junior High School
                </label>
                <label class="level-radio-label">
                    <input type="radio" name="level" value="senior" class="level-radio">
                    <span class="radio-custom"></span>
                    Senior High School
                </label>
            </div>
        </div>
        
        <!-- Year/Grade Level Buttons -->
        <div id="junior-high-buttons" class="level-buttons-container">
            <div class="button-group">
                <div class="button-wrapper">
                    @foreach($juniorHighYears as $year)
                        <button class="year-btn btn {{ $year == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" 
                                data-year="{{ $year }}"
                                onclick="loadSectionsForYear('{{ $year }}')">
                            {{ $year }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="senior-high-buttons" class="level-buttons-container" style="display: none;">
            <div class="button-group">
                <div class="button-wrapper">
                    @foreach($seniorHighGrades as $grade)
                        <button class="grade-btn btn {{ $grade == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" data-grade="{{ $grade }}">
                            {{ $grade }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Strand Buttons (Added for filtering) -->
        <div id="strand-buttons" class="strand-buttons-container" style="display: none;">
            <div class="button-group">
                <div class="button-wrapper">
                    <button class="strand-btn btn btn-primary active" data-strand="ALL" onclick="filterByStrand('ALL')">
                        ALL
                    </button>
                    <!-- Strands will be dynamically added here -->
                </div>
            </div>
        </div>

        <!-- Section Buttons (Updated to be dynamic) -->
        <div id="section-buttons" class="section-buttons-container" style="display: none;">
            <div class="button-group">
                <div class="button-wrapper">
                    <button class="section-btn btn btn-primary active" data-section="ALL" onclick="filterClasses('ALL')">
                        ALL
                    </button>
                    <!-- Sections will be dynamically added here -->
                </div>
            </div>
        </div>

        <!-- Class Content Container -->
        <div class="class-content-container">
            <div id="no-data-message" style="display: none;" class="text-center py-8">
                <h3 class="text-xl font-semibold text-gray-700">No Classes Available</h3>
                <p class="text-gray-500 mt-2" id="no-data-reason">There are no classes available for the selected filters.</p>
                <p class="text-gray-500 mt-2" id="no-data-tip">Try different filters to see more results.</p>
            </div>

            <div id="classes-container">
                <!-- Classes will be dynamically inserted here -->
            </div>
        </div>
    </div>
</div>
</div>

<!-- Edit Student Modal -->
<div id="edit-student-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Student</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="edit-student-form">
                <input type="hidden" id="edit-student-id">
                <input type="hidden" id="edit-class-id">
                <div class="form-group">
                    <label for="edit-lrn">LRN</label>
                    <input type="text" id="edit-lrn" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-first-name">First Name</label>
                    <input type="text" id="edit-first-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit-last-name">Last Name</label>
                    <input type="text" id="edit-last-name" class="form-control" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close-modal">Cancel</button>
            <button type="button" id="save-student-btn" class="btn btn-primary">Save Changes</button>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div id="add-student-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Student</h2>
            <button type="button" class="close-modal" onclick="closeAddStudentModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="add-student-form" onsubmit="event.preventDefault(); submitAddStudentForm();">
                <input type="hidden" id="class-id" name="class_id">
                <div class="form-group">
                    <label for="student-lrn">LRN</label>
                    <input type="text" id="student-lrn" name="lrn" required maxlength="12" class="form-control">
                    <span id="lrn-error" class="error-message"></span>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="student-first-name">First Name</label>
                        <input type="text" id="student-first-name" name="first_name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="student-last-name">Last Name</label>
                        <input type="text" id="student-last-name" name="last_name" required class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label for="student-birthday">Birthday</label>
                    <input type="date" id="student-birthday" name="birthday" required class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddStudentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
 /* Base Styles */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 16px;
}

.main-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    padding: 16px;
}

/* Level and Section Buttons Containers */
.level-buttons-container,
.section-buttons-container {
    margin: 16px 0;
    background-color: #f8f9fa;
    padding: 12px;
    border-radius: 6px;
}

/* Level Selection */
.level-selection-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.level-radio-buttons {
    display: flex;
    gap: 20px;
    order: 2;
    justify-content: center;
    width: 100%;
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

/* Search and Add Container */
.search-add-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 20px;
}

.display-options {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.show-assigned-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #555;
    cursor: pointer;
}

.show-assigned-label input[type="checkbox"] {
    width: 16px;
    height: 16px;
    accent-color: #00b050;
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

/* Class Card Styles */
.class-card {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    overflow: hidden;
}

.class-header {
    background-color: #e8f8ee;
    padding: 16px;
    border-bottom: 1px solid #e0e0e0;
    position: relative;
    padding-bottom: 60px; /* Add space for the button */
}

.add-student-container {
    position: absolute;
    bottom: 16px;
    right: 16px;
    display: flex;
    gap: 8px;
}

.add-student-btn, .assign-teacher-btn {
    padding: 6px 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.add-student-btn i, .assign-teacher-btn i {
    font-size: 16px;
}

.class-info-row {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 12px;
}

.class-info-item {
    flex: 1;
    min-width: 200px;
}

.class-info-label {
    font-weight: 600;
    color: #555;
    margin-bottom: 4px;
    font-size: 14px;
}

.class-info-value {
    color: #333;
    font-size: 15px;
}

.subjects-container {
    margin-top: 12px;
    margin-bottom: 24px;
}

.subjects-title {
    font-weight: 600;
    margin-bottom: 8px;
    color: #555;
}

.subjects-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.subject-tag {
    background-color: #f0f7ff;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 13px;
    color: #2c5282;
    border: 1px solid #bee3f8;
    display: flex;
    align-items: center;
    gap: 8px;
}

.subject-name {
    font-weight: 500;
}

.teacher-name {
    color: #4a5568;
    padding-left: 8px;
    border-left: 1px solid #bee3f8;
}

/* Table Styles */
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

.student-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

/* Button Styles */
.btn {
    padding: 8px 12px;
    border-radius: 4px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
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

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
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
    justify-content: center;
    align-items: center;
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    font-size: 1.5rem;
    color: #333;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #666;
}

.close-modal:hover {
    color: #333;
}

.modal-body {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #555;
    font-weight: 500;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-control:focus {
    border-color: #00b050;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 176, 80, 0.2);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #e0e0e0;
}

/* Add Class Button */
.add-class-btn {
    background-color: #00b050;
    color: white;
    padding: 8px 16px;
    border-radius: 4px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: background-color 0.2s;
}

.add-class-btn:hover {
    background-color: #009040;
}

/* Grade Level Buttons */
.year-btn, .grade-btn {
    background-color: #e0e0e0;
    color: #333;
}

.year-btn.active, .grade-btn.active {
    background-color: #00b050;
    color: white;
}

.year-btn:hover, .grade-btn:hover {
    background-color: #009040;
    color: white;
}

/* Strand and Section Buttons */
.strand-btn, .section-btn {
    background-color: #e0e0e0;
    color: #333;
}

.strand-btn.active, .section-btn.active {
    background-color: #00b050;
    color: white;
}

.strand-btn:hover, .section-btn:hover {
    background-color: #009040;
    color: white;
}

/* Responsive Styles */
@media (min-width: 768px) {
    .container {
        padding: 24px 16px;
    }
    
    .main-card {
        padding: 24px;
    }
    
    .search-add-container {
        flex-direction: row;
        align-items: center;
        gap: 16px;
    }
    
    .display-options {
        flex-grow: 0;
    }
    
    .form-select {
        width: auto;
        min-width: 200px;
    }
}

@media (max-width: 768px) {
    .level-selection-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .level-radio-buttons {
        order: 1;
    }
    
    h1 {
        order: 0;
        width: 100%;
        margin-bottom: 10px;
    }
}

.students-container {
    padding-bottom: 20px;
}

.add-student-btn {
    padding: 6px 12px;
    font-size: 14px;
    margin-left: auto;
}

.add-student-btn i {
    margin-right: 4px;
}

.error-message {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
    display: block;
}

.form-row {
    display: flex;
    gap: 16px;
}

.form-row .form-group {
    flex: 1;
}

.promoted-class {
    position: relative;
    background-color: #fefce8; /* Light yellow background */
    border-left: 4px solid #facc15; /* Yellow border */
}

.promoted-class-badge {
    display: inline-block;
    padding: 2px 6px;
    background-color: #fef08a; /* Yellow background */
    color: #854d0e;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.assigned-class {
    position: relative;
    background-color: #f0fdf4; /* Light green background */
    border-left: 4px solid #22c55e; /* Green border */
}

.assigned-class-badge {
    display: inline-block;
    padding: 2px 6px;
    background-color: #dcfce7; /* Light green background */
    color: #166534;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* Marker to indicate promotion */
.promoted-class::before {
    content: "Promoted Class";
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 3px 8px;
    font-size: 11px;
    background-color: #fde68a;
    color: #92400e;
    border-radius: 4px;
    font-weight: 600;
}

/* Grade 10 Toggle Styles */
.grade10-toggle-container {
    display: none;
}

.grade10-toggle-label {
    display: none;
}

.grade10-toggle-label input[type="checkbox"] {
    display: none;
}

.checkbox-text {
    display: none;
}

/* Grade 10 Card Indicator */
.grade-10-indicator {
    display: none;
}

.subjects-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.add-subject-btn {
    padding: 4px 8px;
    font-size: 13px;
}

.add-subject-btn i {
    margin-right: 4px;
}

.no-subjects {
    color: #666;
    font-style: italic;
    padding: 8px;
}
</style>

<script>
    // Helper function for proper year level display
    function getProperYearLevel(yearLevel) {
        const level = yearLevel - 6;
        if (level === 1) return 'Grade 7';
        if (level === 2) return 'Grade 8';
        if (level === 3) return 'Grade 9';
        return `${level}th Year`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Level Controls
        const levelRadios = document.querySelectorAll('.level-radio');
        const juniorHighButtons = document.getElementById('junior-high-buttons');
        const seniorHighButtons = document.getElementById('senior-high-buttons');
        const strandButtonsContainer = document.getElementById('strand-buttons');
        const sectionButtonsContainer = document.getElementById('section-buttons');

        // Modal elements
        const editStudentModal = document.getElementById('edit-student-modal');
        const closeModalButtons = document.querySelectorAll('.close-modal');
        const editStudentForm = document.getElementById('edit-student-form');
        const saveStudentBtn = document.getElementById('save-student-btn');

        // Store the current classes data globally
        let currentClassesData = [];
        let currentGradeLevel = 'ALL';
        let currentStrand = 'ALL';
        let currentSection = 'ALL';

        // Level Radio Button Logic
        levelRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isJunior = this.value === 'junior';
                juniorHighButtons.style.display = isJunior ? 'block' : 'none';
                seniorHighButtons.style.display = isJunior ? 'none' : 'block';
                
                // Reset filters when changing level
                strandButtonsContainer.style.display = 'none';
                sectionButtonsContainer.style.display = 'none';
                
                // Set default active button
                if (isJunior) {
                    document.querySelector('.year-btn[data-year="ALL"]')?.classList.add('active', 'btn-primary');
                    document.querySelector('.year-btn[data-year="ALL"]')?.classList.remove('btn-secondary');
                } else {
                    document.querySelector('.grade-btn[data-grade="ALL"]')?.classList.add('active', 'btn-primary');
                    document.querySelector('.grade-btn[data-grade="ALL"]')?.classList.remove('btn-secondary');
                }
                
                currentGradeLevel = 'ALL';
                currentStrand = 'ALL';
                currentSection = 'ALL';
                
                updateClassesDisplay();
            });
        });

        // Function to load strands for a specific grade level
        async function loadStrandsForGradeLevel(gradeLevel) {
            strandButtonsContainer.style.display = gradeLevel === 'ALL' ? 'none' : 'block';
            
            // Clear existing strand buttons except ALL
            const buttonWrapper = strandButtonsContainer.querySelector('.button-wrapper');
            const allButton = buttonWrapper.querySelector('[data-strand="ALL"]');
            buttonWrapper.innerHTML = '';
            buttonWrapper.appendChild(allButton);
            
            // Set the ALL button as active
            allButton.classList.add('active', 'btn-primary');
            allButton.classList.remove('btn-secondary');
            // Add click handler to the ALL button
            allButton.onclick = () => filterByStrand('ALL');
            
            if (gradeLevel === 'ALL') {
                // Hide section buttons when ALL grades are selected
                sectionButtonsContainer.style.display = 'none';
                currentSection = 'ALL';
                return;
            }
            
            try {
                // Convert G11/G12 to just the number
                const numericGradeLevel = gradeLevel.startsWith('G') ? 
                    parseInt(gradeLevel.substring(1)) : 
                    parseInt(gradeLevel);
                
                const response = await fetch(`/strands-by-grade?grade_level=${numericGradeLevel}`);
                if (!response.ok) throw new Error('Failed to fetch strands');
                
                const strands = await response.json();
                
                // Add strand buttons
                strands.forEach(strand => {
                    const button = document.createElement('button');
                    button.className = 'strand-btn btn btn-secondary';
                    button.dataset.strand = strand.name;
                    button.textContent = strand.name;
                    button.onclick = () => filterByStrand(strand.name);
                    
                    // Add hover effect
                    button.addEventListener('mouseover', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.add('btn-primary');
                            button.classList.remove('btn-secondary');
                        }
                    });
                    
                    button.addEventListener('mouseout', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-secondary');
                        }
                    });
                    
                    buttonWrapper.appendChild(button);
                });
                
                // Set current strand to ALL
                currentStrand = 'ALL';
                
                // Filter classes for the selected grade level with ALL strands
                filterClasses('ALL');
                
            } catch (error) {
                console.error('Error loading strands:', error);
            }
        }
        
        // Function to filter by strand and load sections
        async function filterByStrand(strand) {
            currentStrand = strand;
            
            // Update active state of strand buttons
            document.querySelectorAll('.strand-btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-secondary');
            });
            
            // Set the clicked strand as active
            const activeButton = document.querySelector(`.strand-btn[data-strand="${strand}"]`);
            if (activeButton) {
                activeButton.classList.remove('btn-secondary');
                activeButton.classList.add('active', 'btn-primary');
            }
            
            // Load sections for this strand and grade level
            if (strand === 'ALL') {
                // If ALL strands selected, hide section buttons
                sectionButtonsContainer.style.display = 'none';
                currentSection = 'ALL';
            } else {
                await loadSectionsForStrand(strand, currentGradeLevel);
            }
            
            // Filter classes based on all selected filters
            filterClasses(currentSection);
        }
        
        // Function to load sections for a specific strand and grade level
        async function loadSectionsForStrand(strand, gradeLevel) {
            sectionButtonsContainer.style.display = 'block';
            
            // Convert grade level format if needed
            let numericGradeLevel = gradeLevel;
            if (gradeLevel.startsWith('G')) {
                numericGradeLevel = parseInt(gradeLevel.substring(1));
            }
            
            try {
                const response = await fetch(`/sections-by-strand?strand=${encodeURIComponent(strand)}&grade_level=${numericGradeLevel}`);
                if (!response.ok) throw new Error('Failed to fetch sections');
                
                const sections = await response.json();
                
                // Clear existing section buttons
                const buttonWrapper = sectionButtonsContainer.querySelector('.button-wrapper');
                buttonWrapper.innerHTML = '';
                
                // Create and add ALL button
                const allButton = document.createElement('button');
                allButton.className = 'section-btn btn btn-primary active';
                allButton.dataset.section = 'ALL';
                allButton.textContent = 'ALL';
                allButton.onclick = () => filterClasses('ALL');
                buttonWrapper.appendChild(allButton);
                
                // Add section buttons
                    sections.forEach(section => {
                        const button = document.createElement('button');
                        button.className = 'section-btn btn btn-secondary';
                    button.dataset.section = section.id;
                    button.textContent = section.name;
                    button.onclick = () => filterClasses(section.id);
                    
                    // Add hover effect
                    button.addEventListener('mouseover', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.add('btn-primary');
                            button.classList.remove('btn-secondary');
                        }
                    });
                    
                    button.addEventListener('mouseout', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-secondary');
                        }
                    });
                    
                        buttonWrapper.appendChild(button);
                    });
                    
                // Set current section to ALL
                currentSection = 'ALL';
                
            } catch (error) {
                console.error('Error loading sections:', error);
                
                // Show empty state
                const buttonWrapper = sectionButtonsContainer.querySelector('.button-wrapper');
                buttonWrapper.innerHTML = '';
                
                const allButton = document.createElement('button');
                allButton.className = 'section-btn btn btn-primary active';
                allButton.dataset.section = 'ALL';
                allButton.textContent = 'ALL';
                allButton.onclick = () => filterClasses('ALL');
                buttonWrapper.appendChild(allButton);
                
                const noSectionsMessage = document.createElement('span');
                noSectionsMessage.textContent = 'No sections found';
                noSectionsMessage.className = 'section-message';
                noSectionsMessage.style.marginLeft = '10px';
                noSectionsMessage.style.color = '#666';
                buttonWrapper.appendChild(noSectionsMessage);
            }
        }

        // Function to load sections for a specific year level (for junior high)
        async function loadSectionsForYear(yearLevel) {
            currentGradeLevel = yearLevel;
            strandButtonsContainer.style.display = 'none'; // Hide strand buttons for junior high
            const sectionButtons = document.getElementById('section-buttons');
            const buttonWrapper = sectionButtons.querySelector('.button-wrapper');
            
            // Show the section buttons container if not ALL
            sectionButtons.style.display = yearLevel === 'ALL' ? 'none' : 'block';
            
            // Clear existing section buttons
            buttonWrapper.innerHTML = '';
            
            // Create and add ALL button with hover effect
            const allButton = document.createElement('button');
            allButton.className = 'section-btn btn btn-primary active';
            allButton.dataset.section = 'ALL';
            allButton.textContent = 'ALL';
            allButton.onclick = () => filterClasses('ALL');
            
            // Add hover effect for ALL button
            allButton.addEventListener('mouseover', () => {
                if (!allButton.classList.contains('active')) {
                    allButton.classList.add('btn-primary');
                    allButton.classList.remove('btn-secondary');
                }
            });
            
            allButton.addEventListener('mouseout', () => {
                if (!allButton.classList.contains('active')) {
                    allButton.classList.remove('btn-primary');
                    allButton.classList.add('btn-secondary');
                }
            });
            
            buttonWrapper.appendChild(allButton);
            
            if (yearLevel === 'ALL') {
                // If ALL is selected, show all classes
                filterClasses('ALL');
                return;
            }
            
            try {
                // Convert "Grade X" to just the number
                const gradeLevel = parseInt(yearLevel.replace('Grade ', ''));
                
                const response = await fetch(`/sections/by-grade/${gradeLevel}`);
                if (!response.ok) throw new Error('Failed to fetch sections');
                
                const sections = await response.json();
                
                // Add section buttons
                sections.forEach(section => {
                    const button = document.createElement('button');
                    button.className = 'section-btn btn btn-secondary';
                    button.dataset.section = section.id;
                    button.textContent = section.name;
                    button.onclick = () => filterClasses(section.id);
                    
                    // Add hover effect
                    button.addEventListener('mouseover', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.add('btn-primary');
                            button.classList.remove('btn-secondary');
                        }
                    });
                    
                    button.addEventListener('mouseout', () => {
                        if (!button.classList.contains('active')) {
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-secondary');
                        }
                    });
                    
                    buttonWrapper.appendChild(button);
                });
                
                // Set current section to ALL
                currentSection = 'ALL';
                
                // Filter classes for the selected year level
                filterClasses('ALL');
                
            } catch (error) {
                console.error('Error loading sections:', error);
            }
        }

        // Function to update classes based on current filters
        function updateClassesDisplay() {
            const levelType = document.querySelector('input[name="level"]:checked').value;
            
            if (levelType === 'junior') {
                const yearLevel = document.querySelector('.year-btn.active')?.dataset.year || 'ALL';
                if (yearLevel === 'ALL') {
                    filterClasses('ALL');
                } else {
                    loadSectionsForYear(yearLevel);
                }
            } else {
                const gradeLevel = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
                if (gradeLevel === 'ALL') {
                    filterClasses('ALL');
                } else {
                    loadStrandsForGradeLevel(gradeLevel);
                }
            }
        }

        // Function to filter classes
        async function filterClasses(section) {
            currentSection = section;
            
            // Update active state of section buttons
            document.querySelectorAll('.section-btn').forEach(btn => {
                btn.classList.remove('active', 'btn-primary');
                btn.classList.add('btn-secondary');
            });
            
            // Set the clicked section as active
            const activeButton = document.querySelector(`.section-btn[data-section="${section}"]`);
            if (activeButton) {
                activeButton.classList.remove('btn-secondary');
                activeButton.classList.add('active', 'btn-primary');
            }
            
            const levelType = document.querySelector('input[name="level"]:checked').value;
            let yearLevel, gradeLevel;
            
            if (levelType === 'junior') {
                yearLevel = document.querySelector('.year-btn.active')?.dataset.year || 'ALL';
                gradeLevel = yearLevel;
            } else {
                gradeLevel = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
                yearLevel = gradeLevel;
            }
            
            const params = {
                level_type: levelType,
                year_level: yearLevel,
                section: section
            };
            
            // Add strand filter for senior high
            if (levelType === 'senior' && currentStrand !== 'ALL') {
                params.strand = currentStrand;
            }
            
            // Always show all classes, both assigned and unassigned
            params.show_assigned = true;
            
            try {
                const response = await fetch(`/filtered-classes?${new URLSearchParams(params)}`);
                if (!response.ok) throw new Error('Failed to fetch classes');
                
                const data = await response.json();
                displayClasses(data.classes);
                
            } catch (error) {
                console.error('Error filtering classes:', error);
            }
        }

        // Function to display classes
        function displayClasses(classes) {
            const container = document.getElementById('classes-container');
            const noDataMessage = document.getElementById('no-data-message');
            
            // Filter out promoted classes where all students are already assigned to new classes
            // This prevents duplicate class displays
            const filteredClasses = classes.filter(class_ => {
                // Keep all classes with advisers
                if (class_.adviser !== null) return true;
                
                // For unassigned classes, filter out those where all students are already assigned
                return class_.all_students_already_assigned !== true;
            });
            
            if (filteredClasses.length === 0) {
                container.style.display = 'none';
                noDataMessage.style.display = 'block';
                
                // Update the no-data message
                const noDataReason = document.getElementById('no-data-reason');
                noDataReason.textContent = "There are no classes available for the selected filters.";
                
                return;
            }
            
            container.style.display = 'block';
            noDataMessage.style.display = 'none';
            
            // Clear existing classes
            container.innerHTML = '';
            
            // Add each class to the container
            filteredClasses.forEach(class_ => {
                const classCard = createClassCard(class_);
                container.appendChild(classCard);
            });
        }

        // Function to create a class card
        function createClassCard(class_) {
            const card = document.createElement('div');
            card.className = 'class-card';
            
            // Check if this is a newly promoted class without assigned teacher
            const isPromotedUnassignedClass = class_.adviser === null;
            
            // Check if this is an assigned class
            const isAssignedClass = class_.adviser !== null;
            
            // Check if all students in this class are already assigned to new classes
            // Use the all_students_already_assigned property from the backend
            const studentsAlreadyAssigned = class_.all_students_already_assigned === true;
            
            // Check if this is a Grade 10 class being shown in Senior High view
            const isGrade10InSenior = class_.is_grade_10 === true;
            
            let headerClass = 'class-header';
            if (isPromotedUnassignedClass && !studentsAlreadyAssigned) {
                headerClass += ' promoted-class';
            }
            if (isAssignedClass) {
                headerClass += ' assigned-class';
            }
            
            card.innerHTML = `
                    <div class="${headerClass}">
                        <div class="class-info-row">
                            <div class="class-info-item">
                            <span class="class-info-label">Year Level:</span>
                            <span class="class-info-value">
                                Grade ${class_.year_level}
                                ${isGrade10InSenior ? '<span class="grade-10-indicator"></span>' : ''}
                            </span>
                            </div>
                            <div class="class-info-item">
                            <span class="class-info-label">Section:</span>
                            <span class="class-info-value">${class_.section_name || 'N/A'}</span>
                            </div>
                            ${class_.level_type === 'senior' ? `
                            <div class="class-info-item">
                            <span class="class-info-label">Strand:</span>
                            <span class="class-info-value">${class_.strand || 'N/A'}</span>
                            </div>
                            ${class_.semester ? `
                            <div class="class-info-item">
                            <span class="class-info-label">Semester:</span>
                            <span class="class-info-value">${parseInt(class_.semester) === 1 ? '1st Semester' : '2nd Semester'}</span>
                            </div>
                            ` : ''}
                            ` : ''}
                            <div class="class-info-item">
                            <span class="class-info-label">Adviser:</span>
                            <span class="class-info-value">
                                ${class_.adviser ? 
                                    `<span class="assigned-class-badge">${class_.adviser.name}</span>` : 
                                  (isPromotedUnassignedClass && !studentsAlreadyAssigned ? 
                                    '<span class="promoted-class-badge">Not Assigned</span>' : 
                                    'Not assigned')}
                            </span>
                            </div>
                            <div class="class-info-item">
                            <span class="class-info-label">Total Students:</span>
                            <span class="class-info-value">${class_.students.length}</span>
                            </div>
                        </div>
                        <div class="add-student-container">
                            <button class="btn btn-primary btn-sm add-student-btn" onclick="openAddStudentModal(${class_.id})">
                                <i class='bx bx-plus'></i> Add Student
                            </button>
                        </div>
                </div>
                <div class="class-body" style="margin-left: 10px;">
                        <div class="subjects-container">
                            <div class="subjects-header">
                                <h3 class="subjects-title">Subjects</h3>
                            </div>
                            <div class="subjects-list">
                            ${class_.subjects.length > 0 ? 
                                class_.subjects.map(subject => `
                                    <div class="subject-tag">
                                        <span class="subject-name">${subject.name}</span>
                                        <span class="teacher-name">${subject.teacher ? subject.teacher.name : 
                                          (isPromotedUnassignedClass && !studentsAlreadyAssigned ? 'Not Assigned' : 'Not assigned')}</span>
                                    </div>
                                `).join('') : 
                                '<div class="no-subjects">No subjects added yet</div>'
                            }
                            </div>
                        </div>
                    <div class="students-container">
                    <div class="table-responsive">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>LRN</th>
                                        <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                    ${class_.students.map(student => `
                                        <tr>
                                        <td>${student.lrn}</td>
                                            <td>${student.name}</td>
                                    </tr>
                                    `).join('')}
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            `;
            
            return card;
        }

        // Search functionality
        const searchInput = document.querySelector('.search-input');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.student-table tbody tr');

            rows.forEach(row => {
                const lrn = row.querySelector('td:nth-child(1)')?.textContent.toLowerCase() || '';
                const name = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';

                if (lrn.includes(searchTerm) || name.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
                });
            });
            
        // Button event listeners for year/grade buttons
        document.querySelectorAll('.year-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.btn').forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-secondary');
                });

                this.classList.remove('btn-secondary');
                this.classList.add('active', 'btn-primary');
                
                const yearLevel = this.dataset.year;
                loadSectionsForYear(yearLevel);
                });
            });
            
        document.querySelectorAll('.grade-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.btn').forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-secondary');
                });

                this.classList.remove('btn-secondary');
                this.classList.add('active', 'btn-primary');
                
                const gradeLevel = this.dataset.grade;
                currentGradeLevel = gradeLevel;
                loadStrandsForGradeLevel(gradeLevel);
                });
            });

        // Add Class Button
        const addClassBtn = document.querySelector('.add-class-btn');
        addClassBtn.addEventListener('click', function() {
            const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
            window.location.href = isJunior ? "{{ route('addclassjunior') }}" : "{{ route('addclasssenior') }}";
        });

        // Modal event listeners
        closeModalButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                editStudentModal.style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target === editStudentModal) {
                editStudentModal.style.display = 'none';
            }
        });

        // Make modal functions global
        window.openAddStudentModal = function(classId) {
            const modal = document.getElementById('add-student-modal');
            document.getElementById('class-id').value = classId;
            document.getElementById('add-student-form').reset();
            document.getElementById('lrn-error').textContent = '';
            modal.style.display = 'flex';
            console.log('Opening modal for class:', classId); // Debug log
        }

        window.closeAddStudentModal = function() {
            const modal = document.getElementById('add-student-modal');
            modal.style.display = 'none';
            console.log('Closing modal'); // Debug log
        }

        window.submitAddStudentForm = async function() {
            const form = document.getElementById('add-student-form');
            const formData = new FormData(form);
            
            console.log('Submitting form data:', Object.fromEntries(formData)); // Debug log

            try {
                const response = await fetch('/add-student-to-class', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                console.log('Response status:', response.status); // Debug log

                const result = await response.json();
                console.log('Response data:', result); // Debug log

                if (response.ok) {
                    alert('Student added successfully!');
                    closeAddStudentModal();
                    updateClassesDisplay();
                } else {
                    alert(result.message || 'Failed to add student');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while adding the student');
            }
        }

        // Close modals when clicking outside
        window.addEventListener('click', (event) => {
            const addStudentModal = document.getElementById('add-student-modal');
            if (event.target === addStudentModal) {
                closeAddStudentModal();
            }
        });

        // Initial setup
        updateClassesDisplay();
    });

    function getSectionName(sectionId) {
        // Add a mapping of section IDs to names
        const sectionNames = {
            1: 'Precious Hearth',
            2: 'Faithful',
            3: 'Charity',
            4: 'Hope',
            5: 'Love'
            // Add more sections as needed
        };
        return sectionNames[sectionId] || sectionId;
    }

    // Add this function to check LRN uniqueness
    async function checkLrnUniqueness(lrn) {
        try {
            const response = await fetch(`/check-student-lrn/${lrn}`);
            const data = await response.json();
            
            if (!data.unique) {
                document.getElementById('lrn-error').textContent = 'This LRN already exists';
                return false;
            }
            
            document.getElementById('lrn-error').textContent = '';
            return true;
        } catch (error) {
            console.error('Error checking LRN:', error);
            return false;
        }
    }
</script>
@endsection