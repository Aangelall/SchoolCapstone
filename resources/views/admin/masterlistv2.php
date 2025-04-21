@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <div class="main-card">
        <!-- Header with title and filter button -->
        <div class="section-header">
            <div class="header-content">
                <h1>Master List</h1>
                <button id="filter-btn" class="filter-button">
                    <i class='bx bx-filter'></i>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="search-add-container">
            <div class="search-container">
                <i class='bx bx-search search-icon'></i>
                <input type="text" class="search-input" placeholder="Search...">
            </div>
        </div>

        <!-- Active Filters Display -->
        <div class="active-filters">
            <span class="filter-label">Active Filters:</span>
            <div id="active-filters-list"></div>
        </div>

        <!-- Student List Table -->
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
                            <th>Section</th>
                            <th>Year Level</th>
                            <th>Strand</th>
                            <th>Semester</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body will be populated by JavaScript -->
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

    /* Active Filters */
    .active-filters {
        margin: 16px 0;
        padding: 12px;
        background-color: #f8f9fa;
        border-radius: 6px;
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

    .student-table {
        width: 100%;
        border-collapse: collapse;
    }

    .student-table th,
    .student-table td {
        padding: 12px 24px;
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Elements
    const modal = document.getElementById('filter-modal');
    const filterBtn = document.getElementById('filter-btn');
    const closeBtn = document.querySelector('.close-modal');
    const applyFiltersBtn = document.getElementById('apply-filters');

    // Level Controls
    const juniorBtn = document.getElementById('junior-btn');
    const seniorBtn = document.getElementById('senior-btn');
    const juniorHighControls = document.getElementById('junior-high-controls');
    const seniorHighControls = document.getElementById('senior-high-controls');

    // Store current students data
    let currentStudents = [];

    // Modal Event Listeners
    filterBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Function to update active filters display
    function updateActiveFilters() {
        const activeFiltersList = document.getElementById('active-filters-list');
        const isJunior = document.getElementById('junior-btn').classList.contains('active');
        let filters = [];

        filters.push(`Level: ${isJunior ? 'Junior High' : 'Senior High'}`);

        if (isJunior) {
            const activeYear = document.querySelector('.year-btn.active')?.dataset.year;
            if (activeYear && activeYear !== 'ALL') {
                filters.push(`Year: ${activeYear}`);
            }
        } else {
            const activeGrade = document.querySelector('.grade-btn.active')?.dataset.grade;
            const activeStrand = document.querySelector('.strand-btn.active')?.dataset.strand;
            const activeSemester = document.querySelector('.semester-btn.active')?.dataset.semester;

            if (activeGrade && activeGrade !== 'ALL') filters.push(`Grade: ${activeGrade}`);
            if (activeStrand && activeStrand !== 'ALL') filters.push(`Strand: ${activeStrand}`);
            if (activeSemester) filters.push(`Semester: ${activeSemester}`);
        }

        const activeSection = document.querySelector('.section-btn.active')?.dataset.section;
        if (activeSection && activeSection !== 'ALL') {
            filters.push(`Section: ${activeSection}`);
        }

        activeFiltersList.innerHTML = filters
            .map(filter => `<span class="filter-tag">${filter}</span>`)
            .join('');
    }

    // Function to update table with students
    function updateTableWithStudents(students) {
        const tbody = document.querySelector('.student-table tbody');
        if (students.length > 0) {
            tbody.innerHTML = students.map((student, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${student.lrn}</td>
                    <td>${student.name}</td>
                    <td>${student.section}</td>
                    <td>${student.yearLevel}</td>
                    <td>${student.strand || '-'}</td>
                    <td>${student.semester || '-'}</td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">No students found</td>
                </tr>
            `;
        }
    }

    // Function to update table
    async function updateTable() {
        const isJunior = document.getElementById('junior-btn').classList.contains('active');
        const activeYear = document.querySelector('.year-btn.active')?.dataset.year || 'ALL';
        const activeGrade = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
        const activeStrand = document.querySelector('.strand-btn.active')?.dataset.strand || 'ALL';
        const activeSemester = document.querySelector('.semester-btn.active')?.dataset.semester || '';
        const activeSection = document.querySelector('.section-btn.active')?.dataset.section || 'ALL';

        try {
            const response = await fetch(`/filtered-students?${new URLSearchParams({
                level_type: isJunior ? 'junior' : 'senior',
                year_level: activeYear,
                grade_level: activeGrade,
                strand: activeStrand,
                semester: activeSemester,
                section: activeSection
            })}`);

            if (!response.ok) throw new Error('Failed to fetch data');

            const data = await response.json();
            currentStudents = data.students;
            updateTableWithStudents(currentStudents);

        } catch (error) {
            console.error('Error:', error);
            document.querySelector('.student-table tbody').innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-4">Error loading data. Please try again.</td>
                </tr>
            `;
        }
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const filteredStudents = currentStudents.filter(student =>
            student.lrn.toLowerCase().includes(searchTerm) ||
            student.name.toLowerCase().includes(searchTerm)
        );
        updateTableWithStudents(filteredStudents);
    });

    // Level button event listeners
    juniorBtn.addEventListener('click', function() {
        juniorBtn.classList.add('active', 'btn-primary');
        juniorBtn.classList.remove('btn-secondary');
        seniorBtn.classList.remove('active', 'btn-primary');
        seniorBtn.classList.add('btn-secondary');
        juniorHighControls.style.display = 'block';
        seniorHighControls.style.display = 'none';
    });

    seniorBtn.addEventListener('click', function() {
        seniorBtn.classList.add('active', 'btn-primary');
        seniorBtn.classList.remove('btn-secondary');
        juniorBtn.classList.remove('active', 'btn-primary');
        juniorBtn.classList.add('btn-secondary');
        juniorHighControls.style.display = 'none';
        seniorHighControls.style.display = 'block';
    });

    // Button click handlers
    document.querySelectorAll('.year-btn, .grade-btn, .strand-btn, .semester-btn, .section-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            this.parentElement.querySelectorAll('.btn').forEach(b => {
                b.classList.remove('active', 'btn-primary');
                b.classList.add('btn-secondary');
            });
            this.classList.remove('btn-secondary');
            this.classList.add('active', 'btn-primary');
        });
    });

    // Apply filters button event listener
    applyFiltersBtn.addEventListener('click', () => {
        updateActiveFilters();
        updateTable();
        modal.style.display = 'none';
    });

    // Initial setup
    updateActiveFilters();
    updateTable();
});
</script>
@endsection
