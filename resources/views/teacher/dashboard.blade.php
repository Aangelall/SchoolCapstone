@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="section-header">
                <div class="header-content">
                    <h1>Teacher Dashboard</h1>
                </div>
            </div>

            <!-- Information Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <!-- Profile Info -->
                <div class="info-card bg-blue-50">
                    <h2 class="info-card-title text-blue-800">Profile Information</h2>
                    <div class="info-card-content">
                        <p class="info-item">
                            <span class="info-label">Name:</span>
                            <span class="info-value text-blue-700">{{ Auth::user()->name }}</span>
                        </p>
                        <p class="info-item">
                            <span class="info-label">Username:</span>
                            <span class="info-value text-blue-700">{{ Auth::user()->email }}</span>
                        </p>
                    </div>
                </div>

                <!-- Advisory Class Info -->
                <div class="info-card bg-green-50">
                    <h2 class="info-card-title text-green-800">Advisory Class</h2>
                    <div class="info-card-content" id="advisoryClassInfo">
                        <p class="info-item">
                            <span class="info-label">Loading...</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Performance Visualization Section -->
            <div class="performance-section mb-8">
                <h2 class="section-title mb-4">Student Performance Overview</h2>
                
                <!-- Filters -->
                <div class="filters mb-6 bg-white p-4 rounded-lg shadow">
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <select id="subjectFilter" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="all">All Subjects</option>
                                <!-- Will be populated by JavaScript -->
                            </select>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Period</label>
                            <select id="periodFilter" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="all">All Periods</option>
                                <option value="1">1st Quarter</option>
                                <option value="2">2nd Quarter</option>
                                <option value="3">3rd Quarter</option>
                                <option value="4">4th Quarter</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Grade Range</label>
                            <select id="gradeRangeFilter" class="w-full border-gray-300 rounded-md shadow-sm">
                                <option value="all">All Grades</option>
                                <option value="below75">Below 75</option>
                                <option value="75-79">75-79</option>
                                <option value="80-89">80-89</option>
                                <option value="90-100">90-100</option>
                            </select>
                        </div>
                        <div class="flex-none">
                            <button id="applyFilters" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-offset-2 transition duration-200">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>  
                
                <!-- Graph Container -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Grade Distribution Chart -->
                    <div class="chart-card bg-white p-4 rounded-lg shadow">
                        <h3 class="chart-title">Grade Distribution</h3>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="gradeDistributionChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Low Grades by Subject Chart -->
                    <div class="chart-card bg-white p-4 rounded-lg shadow">
                        <h3 class="chart-title">Low Grades by Subject</h3>
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="lowGradesBySubjectChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Students with Grades Table -->
                <div class="grades-table mt-8 bg-white p-4 rounded-lg shadow">
                    <h3 class="table-title mb-4">Student Grades</h3>
                    <div class="table-responsive">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 text-left">Student Name</th>
                                    <th class="py-2 px-4 text-left">Subject</th>
                                    <th class="py-2 px-4 text-left">Teacher</th>
                                    <th class="py-2 px-4 text-left">Grade</th>
                                    <th class="py-2 px-4 text-left">Period</th>
                                </tr>
                            </thead>
                            <tbody id="gradesTableBody">
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">
                                        Loading student data...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
        justify-content: space-between;
        align-items: center;
    }

    .section-header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin: 0;
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

    /* Grid Layout */
    .grid {
        display: grid;
        gap: 1rem;
    }

    @media (min-width: 768px) {
        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* Performance Section Styles */
    .performance-section {
        margin-top: 2rem;
    }

    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
    }

    .chart-card {
        border: 1px solid #e2e8f0;
    }

    .chart-title {
        font-size: 1rem;
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 1rem;
    }

    /* Table Styles */
    .low-grades-table {
        border: 1px solid #e2e8f0;
    }

    .table-title {
        font-size: 1rem;
        font-weight: 500;
        color: #4a5568;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 0.75rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    th {
        background-color: #f7fafc;
        font-weight: 500;
        color: #4a5568;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-warning {
        background-color: #fef3c7;
        color: #92400e;
    }

    .badge-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .filters {
    border: 1px solid #e2e8f0;
}

select {
    border: 1px solid #d1d5db;
    padding: 0.5rem;
    border-radius: 0.375rem;
    width: 100%;
}

.filters .flex-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: flex-start; /* Align items to the top */
}

.filters .min-w-200px {
    min-width: 200px;
}

.filters .flex-none {
    margin-left: 0; /* Remove auto margin */
    display: flex;
    align-items: center; /* Center the button vertically */
    justify-content: flex-end; /* Align the button to the right */
    width: 100%; /* Ensure it spans the full width */
    margin-top: 18px;
}


#applyFilters {
    height: 42px;
    padding: 0.5rem 1.5rem;
    white-space: nowrap;
    display: inline-block;
    font-weight: 600;
    font-size: 0.875rem;
    background-color: #2563eb; /* Tailwind blue-600 */
    color: #ffffff;
    border: none;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease-in-out;
}

#applyFilters:hover {
    background-color: #1d4ed8; /* Tailwind blue-700 */
    transform: scale(1.02);
}

#applyFilters:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5); /* Tailwind ring-blue-500 */
}

@media (max-width: 767px) {
    .filters .flex-col > div {
        margin-bottom: 1rem;
    }
    #applyFilters {
        width: 100%;
    }
}
@media (min-width: 768px) {
    .filters .flex-none {       
        width: auto; /* Revert to auto width on larger screens */
        margin-left: auto; /* Push the button to the right */
    }
}
/* Ensure filters are in one row */
.filters .flex-wrap {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center; /* Align items vertically */
}

.filters .min-w-200px {
    min-width: 200px;
}
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Global variables to store chart instances
let gradeDistributionChart;
let lowGradesBySubjectChart;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize advisory class info
    fetchAdvisoryClassInfo();
    
    // Initialize filters and fetch initial data
    setupFilters();
    
    // Apply filters button event listener
    document.getElementById('applyFilters').addEventListener('click', function() {
        fetchPerformanceData();
    });
});

function fetchAdvisoryClassInfo() {
    fetch('/api/teacher/advisory-class-info')
        .then(response => response.json())
        .then(data => {
            const advisoryClassInfo = document.getElementById('advisoryClassInfo');
            
            if (data.advisoryClass) {
                advisoryClassInfo.innerHTML = `

                    <p class="info-item">
                        <span class="info-label">Students:</span>
                        <span class="info-value text-green-700">${data.advisoryClass.student_count}</span>
                    </p>
                    <p class="info-item">
                        <span class="info-label">Subjects:</span>
                        <span class="info-value text-green-700">${data.advisoryClass.subject_count}</span>
                    </p>
                `;
            } else {
                advisoryClassInfo.innerHTML = `
                    <p class="info-item">
                        <span class="info-value text-green-700">No advisory class assigned</span>
                    </p>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching advisory class info:', error);
        });
}

function setupFilters() {
    // Fetch subjects for the filter dropdown
    fetch('/api/teacher/subjects')
        .then(response => response.json())
        .then(data => {
            const subjectFilter = document.getElementById('subjectFilter');
            
            // Clear existing options except "All Subjects"
            while (subjectFilter.options.length > 1) {
                subjectFilter.remove(1);
            }
            
            // Add new subject options
            data.subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                subjectFilter.appendChild(option);
            });
            
            // Now that filters are set up, fetch initial data
            fetchPerformanceData();
        })
        .catch(error => {
            console.error('Error fetching subjects:', error);
        });
}

function fetchPerformanceData() {
    const subjectId = document.getElementById('subjectFilter').value;
    const period = document.getElementById('periodFilter').value;
    const gradeRange = document.getElementById('gradeRangeFilter').value;

    // Show loading state
    document.getElementById('gradesTableBody').innerHTML = `
        <tr>
            <td colspan="5" class="py-4 text-center text-gray-500">
                Loading student data...
            </td>
        </tr>
    `;

    // Build query parameters
    const params = new URLSearchParams();
    if (subjectId !== 'all') params.append('subject_id', subjectId);
    if (period !== 'all') params.append('period', period);
    if (gradeRange !== 'all') params.append('grade_range', gradeRange);

    fetch(`/api/teacher/performance-data?${params.toString()}`)
        .then(response => response.json())
        .then(data => {
            // Update charts and table
            updateGradeDistributionChart(data.gradeDistribution);
            updateLowGradesBySubjectChart(data.lowGradesBySubject);
            populateGradesTable(data.studentGrades);
        })
        .catch(error => {
            console.error('Error fetching performance data:', error);
            document.getElementById('gradesTableBody').innerHTML = `
                <tr>
                    <td colspan="5" class="py-4 text-center text-red-500">
                        Failed to load student data. Please try again later.
                    </td>
                </tr>
            `;
        });
}

function updateGradeDistributionChart(data) {
    const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
    
    if (gradeDistributionChart) {
        // Update existing chart
        gradeDistributionChart.data.datasets[0].data = data;
        gradeDistributionChart.update();
    } else {
        // Initialize new chart
        gradeDistributionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['90-100', '85-89', '80-84', '75-79', 'Below 75'],
                datasets: [{
                    label: 'Number of Students',
                    data: data,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(101, 163, 13, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ],
                    borderColor: [
                        'rgba(16, 185, 129, 1)',
                        'rgba(101, 163, 13, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(239, 68, 68, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Grade Range'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }
}

function updateLowGradesBySubjectChart(data) {
    const ctx = document.getElementById('lowGradesBySubjectChart').getContext('2d');
    
    if (lowGradesBySubjectChart) {
        // Update existing chart
        lowGradesBySubjectChart.data.labels = data.labels;
        lowGradesBySubjectChart.data.datasets[0].data = data.values;
        lowGradesBySubjectChart.update();
    } else {
        // Initialize new chart
        lowGradesBySubjectChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(249, 115, 22, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(101, 163, 13, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(5, 150, 105, 0.7)'
                    ],
                    borderColor: [
                        'rgba(239, 68, 68, 1)',
                        'rgba(249, 115, 22, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(101, 163, 13, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(5, 150, 105, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }
}

function populateGradesTable(students) {
    const tableBody = document.getElementById('gradesTableBody');
    
    if (students.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="py-4 text-center text-gray-500">
                    No student grades found matching the selected filters.
                </td>
            </tr>
        `;
        return;
    }
    
    tableBody.innerHTML = students.map(student => {
        let statusClass = '';
        let statusText = '';
        
        if (student.grade < 75) {
            statusClass = 'badge-danger';
            statusText = 'Critical';
        } else if (student.grade < 80) {
            statusClass = 'badge-warning';
            statusText = 'Warning';
        } else {
            statusClass = 'badge-success';
            statusText = 'Good';
        }
        
        return `
            <tr>
                <td class="py-2 px-4">${student.student_name}</td>
                <td class="py-2 px-4">${student.subject_name}</td>
                <td class="py-2 px-4">${student.teacher_name}</td>
                <td class="py-2 px-4">
                    <span class="badge ${statusClass}">${student.grade} (${statusText})</span>
                </td>
                <td class="py-2 px-4">${student.period_type} ${student.period}</td>
            </tr>
        `;
    }).join('');
}
</script>
@endsection