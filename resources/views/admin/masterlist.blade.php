@extends('layouts.app')

{{-- 
IMPORTANT MANUAL CHANGES NEEDED:
1. Remove the entire "School Year Selection" section (around line 92)
2. In the JavaScript, remove the "updateSchoolYear" function (around line 902)
3. Remove any references to schoolYear in the updateTable and updateActiveFilters functions
--}}

@section('content')
<div class="home-section">
<div class="container">
    <div class="main-card">
        <!-- Header with title and filter button -->
        <div class="section-header">
            <div class="header-content">
                <h1>Master List</h1>
            </div>
        </div>

        <!-- Search -->
        <div class="search-add-container">
            <div class="search-container">
                <i class='bx bx-search search-icon'></i>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
        </div>

        <!-- Level Selection Header -->
        <div class="level-selection-container">
            <h1>Master List</h1>
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
                        <button class="grade-btn btn {{ $grade == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" 
                                data-grade="{{ $grade }}"
                                onclick="loadStrandsForGradeLevel('{{ $grade }}')">
                            {{ $grade }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Strand Buttons (for Senior High) -->
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

        <!-- Section Buttons -->
        <div id="section-buttons" class="section-buttons-container" style="display: none;">
            <div class="button-group">
                <div class="button-wrapper">
                    <button class="section-btn btn btn-primary active" data-section="ALL" onclick="filterStudents('ALL')">
                        ALL
                    </button>
                    <!-- Sections will be dynamically added here -->
                </div>
            </div>
        </div>

        <!-- Active Filters Display -->
        <div class="active-filters">
            <span class="filter-label">Active Filters:</span>
            <div id="active-filters-list"></div>
        </div>

        <!-- Student List Table - Grouped by Grade Level -->
        <div class="table-container">
            <div class="table-header">
                <div class="table-header-left">
                    <h2>List of Students by Grade Level</h2>
                </div>
            </div>

            <div class="table-responsive" id="students-by-grade-container">
                <table class="student-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>LRN</th>
                            <th>Student Name</th>
                            <th>Section</th>
                            <!-- Junior High columns (will be shown/hidden via JS) -->
                            <th class="text-center junior-col">Q1</th>
                            <th class="text-center junior-col">Q2</th>
                            <th class="text-center junior-col">Q3</th>
                            <th class="text-center junior-col">Q4</th>
                            <!-- Senior High columns (will be shown/hidden via JS) -->
                            <th class="text-center senior-col" style="display: none;">1st Sem</th>
                            <th class="text-center senior-col" style="display: none;">2nd Sem</th>
                            <th class="text-center">Final Grade</th>
                        </tr>
                    </thead>
                    <tbody id="students-list">
                        <!-- Students will be populated here via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Filter Modal -->
<div id="filter-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Filter Options</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <!-- School Year Selector -->
            <div class="button-group schoolyear-buttons">
                <div class="button-label">School Year:</div>
                <div class="button-wrapper">
                @foreach($schoolYears as $year)
    <button class="schoolyear-btn btn {{ $year == $currentSchoolYear ? 'btn-primary active' : 'btn-secondary' }}" data-schoolyear="{{ $year }}">
        {{ $year }}
    </button>
@endforeach
                </div>
            </div>

            <!-- Level Selector Buttons -->
            <div class="button-group level-buttons">
                <div class="button-label">Level:</div>
                <div class="button-wrapper">
                    <button id="junior-btn" class="btn btn-primary active">Junior High</button>
                    <button id="senior-btn" class="btn btn-secondary">Senior High</button>
                </div>
            </div>

            <!-- Junior High Controls -->
            <div id="junior-high-controls">
                <!-- Year Level Buttons -->
                <div class="button-group year-buttons">
                    <div class="button-label">Year Level:</div>
                    <div class="button-wrapper">
                        @foreach($juniorHighYears as $year)
                            <button class="year-btn btn {{ $year == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" data-year="{{ $year }}">
                                {{ $year }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Section Buttons -->
                <div class="button-group section-buttons">
                    <div class="button-label">Section:</div>
                    <div class="button-wrapper">
                        @foreach($sections as $section)
                            <button class="section-btn btn {{ $section == 'A' ? 'btn-primary active' : 'btn-secondary' }}" data-section="{{ $section }}">
                                {{ $section }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Senior High Controls -->
            <div id="senior-high-controls" style="display: none;">
                <!-- Grade Level Buttons -->
                <div class="button-group grade-buttons">
                    <div class="button-label">Grade Level:</div>
                    <div class="button-wrapper">
                        @foreach($seniorHighGrades as $grade)
                            <button class="grade-btn btn {{ $grade == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" data-grade="{{ $grade }}">
                                {{ $grade }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Strand Buttons -->
                <div class="button-group strand-buttons">
                    <div class="button-label">Strand:</div>
                    <div class="button-wrapper">
                        @foreach($seniorHighStrands as $strand)
                            <button class="strand-btn btn {{ $strand == 'STEM' ? 'btn-primary active' : 'btn-secondary' }}" data-strand="{{ $strand }}">
                                {{ $strand }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Semester Buttons -->
                <div class="button-group semester-buttons">
                    <div class="button-label">Semester:</div>
                    <div class="button-wrapper">
                        @foreach($seniorHighSemesters as $semester)
                            <button class="semester-btn btn {{ $loop->first ? 'btn-primary active' : 'btn-secondary' }}" data-semester="{{ $semester }}">
                                {{ $semester }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Section Buttons -->
                <div class="button-group section-buttons">
                    <div class="button-label">Section:</div>
                    <div class="button-wrapper">
                        @foreach($sections as $section)
                            <button class="section-btn btn {{ $section == 'A' ? 'btn-primary active' : 'btn-secondary' }}" data-section="{{ $section }}">
                                {{ $section }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="apply-filters" class="btn btn-primary">Apply Filters</button>
            </div>
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

    /* Filter Button Styles */
    .filter-button {
        background-color: #00b050;
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s;
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
        max-width: 800px;
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

    .button-group {
        margin-bottom: 16px;
    }

    .button-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .button-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: flex-start;
    }

    .button-wrapper .btn {
        width: auto;
        min-width: initial;
        flex: none;
    }

    /* Modal Footer Button */
    .modal-footer .btn {
        min-width: 120px;
        padding: 10px 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .btn {
            padding: 8px 10px;
            font-size: 13px;
            min-width: 70px;
        }

        .button-wrapper {
            gap: 6px;
        }
    }

    /* Active Filters */
    .active-filters {
        margin: 16px 0;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
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
        display: inline-block;
        min-width: initial;
        flex: none;
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

    .filter-label {
        font-weight: 600;
        color: #333;
        margin-right: 8px;
    }

    #active-filters-list {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .filter-tag {
        background-color: #e0e0e0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        color: #333;
    }

    /* Search Styles */
    .search-add-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 24px;
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

    .search-input:focus {
        outline: none;
        border-color: #00b050;
        box-shadow: 0 0 0 3px rgba(0, 150, 80, 0.2);
    }

    /* Table Styles */
    .table-container {
        background-color: white;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .table-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e0e0e0;
        background-color: #e8f8ee;
    }

    .table-header-left h2 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .table-responsive {
        overflow-x: auto;
    }

    /* Grade Level Sections */
    .grade-level-section {
        margin-bottom: 30px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .grade-level-header {
        background-color: #e8f8ee;
        padding: 12px 20px;
        font-weight: bold;
        font-size: 16px;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .student-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0;
    }
    
    .student-table th,
    .student-table td {
        padding: 12px 16px;
        font-size: 14px;
        border-bottom: 1px solid #e0e0e0;
        text-align: left;
    }
    
    .student-table th {
        background-color: #f5f5f5;
        font-weight: 600;
        color: #333;
    }
    
    .student-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    
    .grade-column {
        background-color: #f9f9f9;
        font-weight: bold;
        text-align: center;
        min-width: 80px;
    }
    
    .student-table tr:last-child td {
        border-bottom: none;
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

    /* Level and Section Buttons Containers */
    .level-buttons-container,
    .section-buttons-container,
    .strand-buttons-container,
    .schoolyear-buttons-container {
        margin: 16px 0;
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
    }

    .button-group {
        margin-bottom: 16px;
    }

    .button-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    /* Button specific styles */
    .year-btn, .grade-btn, .strand-btn, .section-btn {
        width: auto !important;
        min-width: initial !important;
        flex: none !important;
    }

    /* Add these new styles for grade cells */
    .grade-cell {
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        min-width: 40px;
        text-align: center;
        font-weight: 500;
        background-color: #f3f4f6;
        color: #111827;
    }

    /* Remove all color-specific grade classes */
    /* .grade-outstanding {
        background-color: #dcfce7;
        color: #166534;
    }

    .grade-very-good {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .grade-good {
        background-color: #fef9c3;
        color: #854d0e;
    }

    .grade-passed {
        background-color: #f3f4f6;
        color: #374151;
    }

    .grade-failed {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .grade-pending {
        background-color: #f3f4f6;
        color: #6b7280;
    } */

    /* Grade level section styles */
    .grade-level-section {
        margin-bottom: 30px;
    }

    .grade-level-header {
        background-color: #f9fafb;
        padding: 10px 16px;
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 10px;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Store current students data
    let currentStudents = [];

    // Level Controls
    const levelRadios = document.querySelectorAll('.level-radio');
    const juniorHighButtons = document.getElementById('junior-high-buttons');
    const seniorHighButtons = document.getElementById('senior-high-buttons');
    const strandButtonsContainer = document.getElementById('strand-buttons');
    const sectionButtonsContainer = document.getElementById('section-buttons');
    
    // Level Radio Button Logic
    levelRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const isJunior = this.value === 'junior';
            
            // Toggle level buttons containers
            document.getElementById('junior-high-buttons').style.display = isJunior ? 'block' : 'none';
            document.getElementById('senior-high-buttons').style.display = isJunior ? 'none' : 'block';
            
            // Hide strand buttons if switching to junior
            if (isJunior) {
                document.getElementById('strand-buttons').style.display = 'none';
            }
            
            // Toggle table columns for junior/senior high
            const juniorCols = document.querySelectorAll('.junior-col');
            const seniorCols = document.querySelectorAll('.senior-col');
            
            juniorCols.forEach(col => {
                col.style.display = isJunior ? 'table-cell' : 'none';
            });
            
            seniorCols.forEach(col => {
                col.style.display = isJunior ? 'none' : 'table-cell';
            });
            
            // Reset filters and update table
            if (isJunior) {
                document.querySelector('.year-btn[data-year="ALL"]').click();
            } else {
                document.querySelector('.grade-btn[data-grade="ALL"]').click();
            }
        });
    });
    
    // Function to load strands for a specific grade level
    window.loadStrandsForGradeLevel = async function(gradeLevel) {
        // Update grade buttons
        document.querySelectorAll('.grade-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-secondary');
        });
        
        const activeButton = document.querySelector(`.grade-btn[data-grade="${gradeLevel}"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            activeButton.classList.add('active', 'btn-primary');
        }
        
        strandButtonsContainer.style.display = gradeLevel === 'ALL' ? 'none' : 'block';
        
        // Clear existing strand buttons except ALL
        const buttonWrapper = strandButtonsContainer.querySelector('.button-wrapper');
        const allButton = buttonWrapper.querySelector('[data-strand="ALL"]');
        buttonWrapper.innerHTML = '';
        buttonWrapper.appendChild(allButton);
        
        // Set the ALL button as active
        allButton.classList.add('active', 'btn-primary');
        allButton.classList.remove('btn-secondary');
        
        if (gradeLevel === 'ALL') {
            // Hide section buttons when ALL grades are selected
            sectionButtonsContainer.style.display = 'none';
            updateActiveFilters();
            updateTable();
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
                buttonWrapper.appendChild(button);
            });
            
            updateActiveFilters();
            updateTable();
            
        } catch (error) {
            console.error('Error loading strands:', error);
        }
    };
    
    // Function to filter by strand and load sections
    window.filterByStrand = async function(strand) {
        // Update strand buttons
        document.querySelectorAll('.strand-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-secondary');
        });
        
        const activeButton = document.querySelector(`.strand-btn[data-strand="${strand}"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            activeButton.classList.add('active', 'btn-primary');
        }
        
        if (strand === 'ALL') {
            // If ALL strands selected, hide section buttons
            sectionButtonsContainer.style.display = 'none';
            updateActiveFilters();
            updateTable();
            return;
        }
        
        // Get current grade level
        const gradeLevel = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
        
        // Load sections for this strand and grade level
        try {
            const numericGradeLevel = gradeLevel.startsWith('G') ? 
                parseInt(gradeLevel.substring(1)) : 
                parseInt(gradeLevel);
            
            const response = await fetch(`/sections-by-strand?strand=${encodeURIComponent(strand)}&grade_level=${numericGradeLevel}`);
            if (!response.ok) throw new Error('Failed to fetch sections');
            
            const sections = await response.json();
            
            // Clear existing section buttons
            sectionButtonsContainer.style.display = 'block';
            const buttonWrapper = sectionButtonsContainer.querySelector('.button-wrapper');
            buttonWrapper.innerHTML = '';
            
            // Create and add ALL button
            const allButton = document.createElement('button');
            allButton.className = 'section-btn btn btn-primary active';
            allButton.dataset.section = 'ALL';
            allButton.textContent = 'ALL';
            allButton.onclick = () => filterStudents('ALL');
            buttonWrapper.appendChild(allButton);
            
            // Add section buttons
            sections.forEach(section => {
                const button = document.createElement('button');
                button.className = 'section-btn btn btn-secondary';
                button.dataset.section = section.id;
                button.textContent = section.name;
                button.onclick = () => filterStudents(section.id);
                buttonWrapper.appendChild(button);
            });
            
            updateActiveFilters();
            updateTable();
            
        } catch (error) {
            console.error('Error loading sections:', error);
        }
    };
    
    // Function to load sections for a specific year level (for junior high)
    window.loadSectionsForYear = async function(yearLevel) {
        // Update year buttons
        document.querySelectorAll('.year-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-secondary');
        });
        
        const activeButton = document.querySelector(`.year-btn[data-year="${yearLevel}"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            activeButton.classList.add('active', 'btn-primary');
        }
        
        // Show/hide section buttons
        sectionButtonsContainer.style.display = yearLevel === 'ALL' ? 'none' : 'block';
        
        if (yearLevel === 'ALL') {
            updateActiveFilters();
            updateTable();
            return;
        }
        
        try {
            // Get the grade level number
            let gradeLevel;
            if (yearLevel.includes('Year')) {
                const match = yearLevel.match(/(\d+)/);
                gradeLevel = match ? parseInt(match[1]) + 6 : 7; // Default to 7 if parsing fails
            } else if (yearLevel.includes('Grade')) {
                const match = yearLevel.match(/(\d+)/);
                gradeLevel = match ? parseInt(match[1]) : 7;
            }
            
            const response = await fetch(`/sections/by-grade/${gradeLevel}`);
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
            allButton.onclick = () => filterStudents('ALL');
            buttonWrapper.appendChild(allButton);
            
            // Add section buttons
            sections.forEach(section => {
                const button = document.createElement('button');
                button.className = 'section-btn btn btn-secondary';
                button.dataset.section = section.id;
                button.textContent = section.name;
                button.onclick = () => filterStudents(section.id);
                buttonWrapper.appendChild(button);
            });
            
            updateActiveFilters();
            updateTable();
            
        } catch (error) {
            console.error('Error loading sections:', error);
        }
    };
    
    // Function to filter students by section
    window.filterStudents = function(section) {
        // Update section buttons
        document.querySelectorAll('.section-btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-secondary');
        });
        
        const activeButton = document.querySelector(`.section-btn[data-section="${section}"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            activeButton.classList.add('active', 'btn-primary');
        }
        
        updateActiveFilters();
        updateTable();
    };

    // Function to update active filters display
    function updateActiveFilters() {
        const activeFiltersList = document.getElementById('active-filters-list');
        const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
        let filters = [];

        // Add level filter
        filters.push(`Level: ${isJunior ? 'Junior High School' : 'Senior High School'}`);

        if (isJunior) {
            const activeYear = document.querySelector('.year-btn.active')?.dataset.year;
            if (activeYear && activeYear !== 'ALL') {
                filters.push(`Year: ${activeYear}`);
            }
        } else {
            const activeGrade = document.querySelector('.grade-btn.active')?.dataset.grade;
            const activeStrand = document.querySelector('.strand-btn.active')?.dataset.strand;

            if (activeGrade && activeGrade !== 'ALL') filters.push(`Grade: ${activeGrade}`);
            if (activeStrand && activeStrand !== 'ALL') filters.push(`Strand: ${activeStrand}`);
        }

        const activeSection = document.querySelector('.section-btn.active')?.dataset.section;
        if (activeSection && activeSection !== 'ALL') {
            const sectionText = document.querySelector('.section-btn.active').textContent;
            filters.push(`Section: ${sectionText}`);
        }

        activeFiltersList.innerHTML = filters
            .map(filter => `<span class="filter-tag">${filter}</span>`)
            .join('');
    }

    // Function to fetch grades for a student
    async function fetchStudentGrades(studentId) {
        try {
            const response = await fetch(`/student/${studentId}/grades`);
            if (!response.ok) throw new Error('Failed to fetch grades');
            return await response.json();
        } catch (error) {
            console.error('Error fetching grades:', error);
            return null;
        }
    }

    function getGradeClass(grade) {
        // Always return the same class regardless of grade value
        return 'grade-cell';
    }

    // Function to update table with students grouped by grade level
    function updateTableWithStudents(students) {
        // If we have the students-list tbody, update it directly
        const studentsList = document.getElementById('students-list');
        if (studentsList) {
            // Clear existing content
            studentsList.innerHTML = '';
            
            // Determine if this is Junior High (quarters) or Senior High (semesters)
            const isJuniorHigh = document.querySelector('.level-radio[value="junior"]').checked;
            
            if (students.length === 0) {
                studentsList.innerHTML = `<tr><td colspan="9" class="text-center py-4">No students found</td></tr>`;
                return;
            }

            // Sort students by section and then by name
            students.sort((a, b) => {
                if (a.section !== b.section) return a.section.localeCompare(b.section);
                return a.name.localeCompare(b.name);
            });

            // Generate HTML for each student
            students.forEach((student, index) => {
                const row = document.createElement('tr');

                // Calculate final grade based on quarters/semesters
                let finalGrade = '-';
                if (isJuniorHigh) {
                    const q1 = student.quarter1Grade ? parseFloat(student.quarter1Grade) : 0;
                    const q2 = student.quarter2Grade ? parseFloat(student.quarter2Grade) : 0;
                    const q3 = student.quarter3Grade ? parseFloat(student.quarter3Grade) : 0;
                    const q4 = student.quarter4Grade ? parseFloat(student.quarter4Grade) : 0;
                    
                    if (q1 && q2 && q3 && q4) {
                        finalGrade = Math.round((q1 + q2 + q3 + q4) / 4);
                    }
                } else {
                    const sem1 = student.firstSemGrade ? parseFloat(student.firstSemGrade) : 0;
                    const sem2 = student.secondSemGrade ? parseFloat(student.secondSemGrade) : 0;
                    
                    if (sem1 && sem2) {
                        finalGrade = Math.round((sem1 + sem2) / 2);
                    }
                }

                // Format grades with styling
                const formatGrade = (grade) => {
                    return grade ? `<span class="grade-cell">${grade}</span>` : '-';
                };

                // Add different columns based on school level
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${student.lrn || '-'}</td>
                    <td>${student.name || '-'}</td>
                    <td>${student.section || '-'}</td>
                    ${isJuniorHigh ? `
                        <td class="text-center">${formatGrade(student.quarter1Grade)}</td>
                        <td class="text-center">${formatGrade(student.quarter2Grade)}</td>
                        <td class="text-center">${formatGrade(student.quarter3Grade)}</td>
                        <td class="text-center">${formatGrade(student.quarter4Grade)}</td>
                    ` : `
                        <td class="text-center">${formatGrade(student.firstSemGrade)}</td>
                        <td class="text-center">${formatGrade(student.secondSemGrade)}</td>
                    `}
                    <td class="text-center">${formatGrade(finalGrade)}</td>
                `;

                studentsList.appendChild(row);
            });

            return;
        }

        // Original table with grade level grouping (if students-list not found)
        const container = document.getElementById('students-by-grade-container');
        // Determine the selected level (Junior/Senior) ONCE
        const isJuniorHigh = document.querySelector('.level-radio[value="junior"]').checked;
        
        if (students.length === 0) {
            container.innerHTML = `
                <div class="no-students">
                    <p>No students found</p>
                </div>
            `;
            return;
        }

        // Group students by grade level
        const studentsByGrade = {};
        students.forEach(student => {
            const gradeLevel = student.yearLevel || student.gradeLevel || 'Unknown Grade';
            if (!studentsByGrade[gradeLevel]) {
                studentsByGrade[gradeLevel] = [];
            }
            studentsByGrade[gradeLevel].push(student);
        });

        // Sort grade levels in order
        const sortedGrades = Object.keys(studentsByGrade).sort((a, b) => {
            const gradeOrder = {
                'Grade 7': 1, 'Grade 8': 2, 'Grade 9': 3, 'Grade 10': 4,
                'G11': 5, 'G12': 6
            };
            return (gradeOrder[a] || 99) - (gradeOrder[b] || 99);
        });

        // Generate HTML for each grade level
        let html = '';
        sortedGrades.forEach(grade => {
            // Determine if this is Senior High (Grades 11-12)
            // REMOVED: const isSeniorHigh = grade.includes('1') || grade.includes('2');
            
            html += `
                <div class="grade-level-section">
                    <div class="grade-level-header">${grade}</div>
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>LRN</th>
                                <th>Student Name</th>
                                <th>Section</th>
                                ${!isJuniorHigh ? `
                                    <th>Strand</th>
                                    <th class="grade-column">1st Sem</th>
                                    <th class="grade-column">2nd Sem</th>
                                ` : `
                                    <th class="grade-column">Q1</th>
                                    <th class="grade-column">Q2</th>
                                    <th class="grade-column">Q3</th>
                                    <th class="grade-column">Q4</th>
                                `}
                                <th class="grade-column">Final</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${studentsByGrade[grade].map((student, index) => `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${student.lrn}</td>
                                    <td>${student.name}</td>
                                    <td>${student.section}</td>
                                    ${!isJuniorHigh ? `
                                        <td>${student.strand || '-'}</td>
                                        <td class="grade-column">${student.firstSemGrade || '-'}</td>
                                        <td class="grade-column">${student.secondSemGrade || '-'}</td>
                                    ` : `
                                        <td class="grade-column">${student.quarter1Grade || '-'}</td>
                                        <td class="grade-column">${student.quarter2Grade || '-'}</td>
                                        <td class="grade-column">${student.quarter3Grade || '-'}</td>
                                        <td class="grade-column">${student.quarter4Grade || '-'}</td>
                                    `}
                                    <td class="grade-column">${student.finalGrade || '-'}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    // Function to update table
    async function updateTable() {
        const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
        const activeYear = document.querySelector('.year-btn.active')?.dataset.year || 'ALL';
        const activeGrade = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
        const activeStrand = document.querySelector('.strand-btn.active')?.dataset.strand || 'ALL';
        const activeSection = document.querySelector('.section-btn.active')?.dataset.section || 'ALL';

        try {
            const response = await fetch(`/filtered-students?${new URLSearchParams({
                level_type: isJunior ? 'junior' : 'senior',
                year_level: activeYear,
                grade_level: activeGrade,
                strand: activeStrand,
                section: activeSection
            })}`);

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();
            currentStudents = data.students;
            
            // Fetch grades for each student
            for (let i = 0; i < currentStudents.length; i++) {
                const student = currentStudents[i];
                try {
                    // Fetch grades from backend
                    const gradesResponse = await fetch(`/student/${student.id}/grades`);
                    if (gradesResponse.ok) {
                        const gradesData = await gradesResponse.json();
                        
                        // Map grades to student object
                        if (isJunior) {
                            student.quarter1Grade = gradesData.quarters?.q1 || null;
                            student.quarter2Grade = gradesData.quarters?.q2 || null;
                            student.quarter3Grade = gradesData.quarters?.q3 || null;
                            student.quarter4Grade = gradesData.quarters?.q4 || null;
                        } else {
                            student.firstSemGrade = gradesData.semesters?.sem1 || null;
                            student.secondSemGrade = gradesData.semesters?.sem2 || null;
                        }
                    }
                } catch (error) {
                    console.error(`Error fetching grades for student ${student.id}:`, error);
                }
            }
            
            updateTableWithStudents(currentStudents);

        } catch (error) {
            console.error('Error:', error);
            document.getElementById('students-by-grade-container').innerHTML = `
                <div class="no-students">
                    <p>Error loading data. Please try again.</p>
                </div>
            `;
        }
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filteredStudents = currentStudents.filter(student =>
            (student.lrn && student.lrn.toLowerCase().includes(searchTerm)) ||
            (student.name && student.name.toLowerCase().includes(searchTerm)) ||
            (student.section && student.section.toLowerCase().includes(searchTerm))
        );
        updateTableWithStudents(filteredStudents);
    });

    // Initial setup
    updateActiveFilters();
    updateTable();
});
</script>
@endsection