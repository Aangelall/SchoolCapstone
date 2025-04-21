
@extends('layouts.app')

@section('content')
<div class="home-section">
<div class="container">
    <div class="main-card">
        <!-- Level Selection Header -->
        <div class="level-selection-container">
            <h1>Sections & Subjects</h1>
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

        <!-- Search and Add Class -->
        <div class="search-add-container">
            <div class="search-container">
                <i class='bx bx-search search-icon'></i>
                <input type="text" class="search-input" placeholder="Search LRN...">
            </div>
            <button class="btn btn-primary add-class-btn">Add Class</button>
        </div>

        <!-- Year/Grade Level Buttons -->
        <div id="junior-high-buttons" class="level-buttons-container">
            <div class="button-group">
                <div class="button-wrapper">
                    @foreach($juniorHighYears as $year)
                        <button class="year-btn btn {{ $year == 'ALL' ? 'btn-primary active' : 'btn-secondary' }}" data-year="{{ $year }}">
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

        <!-- Section Buttons -->
        <div class="section-buttons-container">
            <div class="button-group">
                <div class="button-wrapper">
                    @foreach($sections as $section)
                        <button class="section-btn btn {{ $section == 'A' ? 'btn-primary active' : 'btn-secondary' }}" data-section="{{ $section }}">
                            {{ $section }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Adviser Selection -->
        <div class="adviser-selection">
            <label for="adviser-select" class="block text-sm font-medium text-gray-700 mb-2">Select Adviser:</label>
            <select id="adviser-select" class="form-select w-full md:w-auto">
                <option value="">Select an adviser</option>
                @foreach($advisers as $adviser)
                    <option value="{{ $adviser->id }}">{{ $adviser->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Class Information -->
        <div class="class-info">
            <p class="class-adviser">Class Adviser: N/A</p>
            <p class="subject-count"># of Subjects: 0</p>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <div id="no-data-message" style="display: none;" class="text-center py-8">
                <h3 class="text-xl font-semibold text-gray-700">No Class Yet</h3>
                <p class="text-gray-500 mt-2">There are no classes available for the selected filters.</p>
            </div>

            <div id="class-content">
                <div class="table-header">
                    <div class="table-header-left">
                        <h2>List of Students</h2>
                        <p class="teacher-name">Teacher Name: <span></span></p>
                    </div>
                    <div class="table-header-right">
                        <div class="subject-dropdown">
                            <span class="subject-label">Choose Subject:</span>
                            <select id="subject-select" class="form-select">
                                <option value="">Select a subject</option>
                            </select>
                        </div>
                        <p class="selected-subject">Subject: <span></span></p>
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
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<style>
    /* Level Buttons Container */
    .level-buttons-container {
        margin: 16px 0;
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
    }

    .section-buttons-container {
        margin-bottom: 16px;
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
    }

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

    .filter-button {
        background-color: #00b050;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background-color 0.2s;
        order: 3;
    }

    .filter-button:hover {
        background-color: #009040;
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

    /* Existing Styles */
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

    .search-add-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {
        .search-add-container {
            flex-direction: row;
            gap: 16px;
        }
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

    .adviser-selection {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .class-info {
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .class-info p {
        margin: 0;
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }

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
        gap: 12px;
    }

    @media (min-width: 768px) {
        .table-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
        }
    }

    .table-header-left h2 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 8px 0;
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

    .form-select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    @media (min-width: 768px) {
        .form-select {
            width: auto;
            min-width: 200px;
        }
    }

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
</style>
<script>
    // Helper function for proper year level display
    function getProperYearLevel(yearLevel) {
        const level = yearLevel - 6;
        if (level === 1) return '1st Year';
        if (level === 2) return '2nd Year';
        if (level === 3) return '3rd Year';
        return `${level}th Year`;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Level Controls
        const levelRadios = document.querySelectorAll('.level-radio');
        const juniorHighButtons = document.getElementById('junior-high-buttons');
        const seniorHighButtons = document.getElementById('senior-high-buttons');

        // Store the current classes data globally
        let currentClassesData = [];

        // Level Radio Button Logic
        levelRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const isJunior = this.value === 'junior';
                juniorHighButtons.style.display = isJunior ? 'block' : 'none';
                seniorHighButtons.style.display = isJunior ? 'none' : 'block';
                updateTable();
            });
        });

        // Search functionality
        const searchInput = document.querySelector('.search-input');
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

        // Function to update table with filtered students
        function updateTableWithStudents(students, isJunior) {
            const tbody = document.querySelector('.student-table tbody');
            if (students.length > 0) {
                tbody.innerHTML = students.map((student, index) => `
                    <tr class="${index % 2 === 0 ? 'even-row' : ''}">
                        <td>${index + 1}</td>
                        <td>${student.lrn}</td>
                        <td>${student.last_name}, ${student.first_name}</td>
                        <td>${student.section}</td>
                        <td>${isJunior ? getProperYearLevel(student.yearLevel) : `G${student.yearLevel}`}</td>
                    </tr>
                `).join('');
            } else {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">No students found</td>
                    </tr>
                `;
            }
        }

        // Function to update adviser list
        function updateAdviserList(classes, availableAdvisers) {
            const adviserSelect = document.getElementById('adviser-select');
            const currentValue = adviserSelect.value;
            adviserSelect.innerHTML = '<option value="">Select an adviser</option>';

            // Add currently assigned advisers
            const assignedAdvisers = new Set();
            classes.forEach(classData => {
                if (classData.adviser) {
                    assignedAdvisers.add(classData.adviser.id);
                    adviserSelect.innerHTML += `
                        <option value="${classData.adviser.id}" ${currentValue == classData.adviser.id ? 'selected' : ''}>
                            ${classData.adviser.name} (Assigned)
                        </option>
                    `;
                }
            });

            // Add available advisers
            availableAdvisers.forEach(adviser => {
                if (!assignedAdvisers.has(adviser.id)) {
                    adviserSelect.innerHTML += `
                        <option value="${adviser.id}" ${currentValue == adviser.id ? 'selected' : ''}>
                            ${adviser.name}
                        </option>
                    `;
                }
            });
        }

        // Function to update subject dropdown based on selected adviser
        function updateSubjectDropdown(selectedAdviserId) {
            const subjectSelect = document.getElementById('subject-select');
            subjectSelect.innerHTML = '<option value="">All Subjects</option>';

            if (!selectedAdviserId) {
                document.querySelector('.teacher-name span').textContent = 'N/A';
                document.querySelector('.selected-subject span').textContent = '';
                return;
            }

            const adviserClasses = currentClassesData.filter(classData =>
                classData.adviser && classData.adviser.id === parseInt(selectedAdviserId)
            );

            const uniqueSubjects = new Set();
            adviserClasses.forEach(classData => {
                if (classData.subjects) {
                    classData.subjects.forEach(subject => {
                        if (!uniqueSubjects.has(subject.name)) {
                            uniqueSubjects.add(subject.name);
                            subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                        }
                    });
                }
            });
        }

        // Update table function
        async function updateTable() {
            const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
            const activeYear = document.querySelector('.year-btn.active')?.dataset.year || 'ALL';
            const activeGrade = document.querySelector('.grade-btn.active')?.dataset.grade || 'ALL';
            const activeSection = document.querySelector('.section-btn.active')?.dataset.section || 'ALL';

            try {
                const response = await fetch(`/filtered-classes?${new URLSearchParams({
                    level_type: isJunior ? 'junior' : 'senior',
                    year_level: activeYear,
                    grade_level: activeGrade,
                    section: activeSection
                })}`);

                if (!response.ok) throw new Error('Failed to fetch data');

                const data = await response.json();
                currentClassesData = data.classes;
                const noDataMessage = document.getElementById('no-data-message');
                const classContent = document.getElementById('class-content');

                if (data.classes.length > 0) {
                    let allStudents = [];
                    let totalSubjects = 0;

                    const isSpecificClass = (isJunior && activeYear !== 'ALL' && activeSection !== 'ALL') ||
                                          (!isJunior && activeGrade !== 'ALL' && activeSection !== 'ALL');

                    if (isSpecificClass && data.classes.length === 1) {
                        const specificClass = data.classes[0];
                        document.querySelector('.class-adviser').textContent =
                            `Class Adviser: ${specificClass.adviser?.name || 'N/A'}`;
                        document.querySelector('.subject-count').textContent =
                            `# of Subjects: ${specificClass.subjects?.length || 0}`;
                    } else {
                        document.querySelector('.class-adviser').textContent =
                            `Total Classes: ${data.classes.length}`;
                        data.classes.forEach(classData => {
                            if (classData.subjects) {
                                totalSubjects += classData.subjects.length;
                            }
                        });
                        document.querySelector('.subject-count').textContent =
                            `Total Subjects: ${totalSubjects}`;
                    }

                    data.classes.forEach(classData => {
                        if (classData.students) {
                            classData.students.forEach(student => {
                                allStudents.push({
                                    ...student,
                                    section: classData.section,
                                    yearLevel: classData.year_level,
                                    classId: classData.id
                                });
                            });
                        }
                    });

                    // Update adviser list
                    updateAdviserList(data.classes, data.availableAdvisers);

                    // Update student table
                    updateTableWithStudents(allStudents, isJunior);

                    // Update subject dropdown based on current adviser selection
                    const selectedAdviserId = document.getElementById('adviser-select').value;
                    updateSubjectDropdown(selectedAdviserId);

                    noDataMessage.style.display = 'none';
                    classContent.style.display = 'block';
                } else {
                    document.querySelector('.class-adviser').textContent = 'Class Adviser: N/A';
                    document.querySelector('.subject-count').textContent = '# of Subjects: 0';
                    document.getElementById('subject-select').innerHTML = '<option value="">Select a subject</option>';
                    document.querySelector('.student-table tbody').innerHTML = '';

                    noDataMessage.style.display = 'block';
                    classContent.style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
                document.querySelector('.class-adviser').textContent = 'Class Adviser: N/A';
                document.querySelector('.subject-count').textContent = '# of Subjects: 0';
                document.getElementById('subject-select').innerHTML = '<option value="">Select a subject</option>';
                document.querySelector('.student-table tbody').innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">Error loading data. Please try again.</td>
                    </tr>
                `;
            }
        }

        // Button event listeners for year/grade/section buttons
        document.querySelectorAll('.year-btn, .grade-btn, .section-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.querySelectorAll('.btn').forEach(b => {
                    b.classList.remove('active', 'btn-primary');
                    b.classList.add('btn-secondary');
                });

                this.classList.remove('btn-secondary');
                this.classList.add('active', 'btn-primary');
                updateTable();
            });
        });

        // Add Class Button
        const addClassBtn = document.querySelector('.add-class-btn');
        addClassBtn.addEventListener('click', function() {
            const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
            window.location.href = isJunior ? "{{ route('addclassjunior') }}" : "{{ route('addclasssenior') }}";
        });

        // Subject dropdown change handler
        const subjectSelect = document.getElementById('subject-select');
        subjectSelect.addEventListener('change', function() {
            const selectedSubjectId = this.value;
            const selectedSubjectName = this.options[this.selectedIndex].text;
            document.querySelector('.selected-subject span').textContent = selectedSubjectName;

            const selectedAdviserId = document.getElementById('adviser-select').value;
            if (!selectedAdviserId) return;

            if (!selectedSubjectId) {
                const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
                let allStudents = [];
                currentClassesData.forEach(classData => {
                    if (classData.adviser && classData.adviser.id === parseInt(selectedAdviserId) && classData.students) {
                        classData.students.forEach(student => {
                            allStudents.push({
                                ...student,
                                section: classData.section,
                                yearLevel: classData.year_level
                            });
                        });
                    }
                });
                updateTableWithStudents(allStudents, isJunior);
                document.querySelector('.teacher-name span').textContent = 'N/A';
                return;
            }

            const isJunior = document.querySelector('.level-radio[value="junior"]').checked;
            let studentsInSubject = [];

            currentClassesData.forEach(classData => {
                if (classData.adviser && classData.adviser.id === parseInt(selectedAdviserId)) {
                    const hasSubject = classData.subjects.some(subject => subject.id === parseInt(selectedSubjectId));
                    if (hasSubject && classData.students) {
                        classData.students.forEach(student => {
                            studentsInSubject.push({
                                ...student,
                                section: classData.section,
                                yearLevel: classData.year_level
                            });
                        });
                    }
                }
            });

            updateTableWithStudents(studentsInSubject, isJunior);

            const selectedSubject = currentClassesData
                .filter(c => c.adviser && c.adviser.id === parseInt(selectedAdviserId))
                .flatMap(c => c.subjects)
                .find(s => s.id === parseInt(selectedSubjectId));

            if (selectedSubject && selectedSubject.teacher) {
                document.querySelector('.teacher-name span').textContent = selectedSubject.teacher.name;
            } else {
                document.querySelector('.teacher-name span').textContent = 'N/A';
            }
        });

        // Adviser dropdown change handler
        const adviserSelect = document.getElementById('adviser-select');
        adviserSelect.addEventListener('change', function() {
            const selectedAdviserId = this.value;
            const isJunior = document.querySelector('.level-radio[value="junior"]').checked;

            // Update subject dropdown based on selected adviser
            updateSubjectDropdown(selectedAdviserId);

            if (selectedAdviserId) {
                const filteredStudents = [];
                currentClassesData.forEach(classData => {
                    if (classData.adviser && classData.adviser.id === parseInt(selectedAdviserId)) {
                        classData.students.forEach(student => {
                            filteredStudents.push({
                                ...student,
                                section: classData.section,
                                yearLevel: classData.year_level
                            });
                        });
                    }
                });
                updateTableWithStudents(filteredStudents, isJunior);
            } else {
                const allStudents = [];
                currentClassesData.forEach(classData => {
                    if (classData.students) {
                        classData.students.forEach(student => {
                            allStudents.push({
                                ...student,
                                section: classData.section,
                                yearLevel: classData.year_level
                            });
                        });
                    }
                });
                updateTableWithStudents(allStudents, isJunior);
            }
        });

        // Initial setup
        updateTable();
    });
</script>
@endsection