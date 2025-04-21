@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="section-header">
                <div class="header-content">
                    <h1>Class Achievers</h1>
                </div>
            </div>

            <!-- Period Selector -->
            <div class="period-selector mb-4">
                <label for="period-select" class="block text-sm font-medium text-gray-700 mb-2">Select Period:</label>
                <select id="period-select" class="form-select w-full" onchange="fetchAchievers()">
                    <!-- Options will be populated dynamically -->
                </select>
            </div>

            <!-- Achievers Table -->
            <div id="achievers-container" class="hidden">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-header-left">
                            <h2>Achievers List</h2>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="student-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Average Grade</th>
                                    <th>Honor</th>
                                </tr>
                            </thead>
                            <tbody id="achievers-list" class="divide-y divide-gray-200">
                                <!-- Achievers will be populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- No Data Message -->
            <div id="no-data-message" class="no-data-message">
                <div class="flex items-center justify-center">
                    <i class='bx bx-info-circle text-yellow-400 text-4xl mr-3'></i>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-700">No Data</h3>
                        <p class="text-gray-500 mt-2">There are no achievers available for the selected period.</p>
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

.subject-button.selected {
    background-color: #009040;
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
}

/* Status Badge Styles */
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
    display: inline-block;
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

/* Add new styles for completed subjects */
.subject-button.completed {
    background-color: #059669;
}

.subject-button.completed:hover {
    background-color: #047857;
}
.achiever-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-align: center;
    display: inline-block;
}

.highest-honor {
    background-color: #059669;
    color: white;
}

.high-honor {
    background-color: #2563eb;
    color: white;
}

.with-honor {
    background-color: #9333ea;
    color: white;
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
</style>

<script>
    let currentPeriod = 1;

    // Initialize period selector based on class type
    function initializePeriodSelector() {
        const periodSelect = document.getElementById('period-select');
        periodSelect.innerHTML = '';

        // We'll determine if it's junior/senior from the backend
        fetch('/advisory-class/info')
            .then(response => response.json())
            .then(data => {
                const isJunior = data.level_type === 'junior';
                const periods = isJunior ? 4 : 2;
                const periodType = isJunior ? 'Quarter' : 'Semester';

                for (let i = 1; i <= periods; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.textContent = `${i}${getOrdinalSuffix(i)} ${periodType}`;
                    periodSelect.appendChild(option);
                }
                fetchAchievers(); // Initial fetch after setting up periods
            })
            .catch(error => {
                console.error('Error fetching class info:', error);
            });
    }

    function fetchAchievers() {
        const periodSelect = document.getElementById('period-select');
        currentPeriod = periodSelect.value;

        fetch(`/advisory-class/achievers?period=${currentPeriod}`)
            .then(response => response.json())
            .then(data => {
                const achieversList = document.getElementById('achievers-list');
                const noDataMessage = document.getElementById('no-data-message');
                const achieversContainer = document.getElementById('achievers-container');

                if (data && data.length > 0) {
                    achieversList.innerHTML = data.map(achiever => {
                        return `
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">${achiever.name}</div>
                                            <div class="text-gray-500">${achiever.lrn}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-semibold">${achiever.average_grade}</span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="achiever-badge ${getHonorClass(achiever.average_grade)}">
                                        ${getHonorLabel(achiever.average_grade)}
                                    </span>
                                </td>
                            </tr>
                        `;
                    }).join('');
                    achieversContainer.classList.remove('hidden');
                    noDataMessage.style.display = 'none';
                } else {
                    achieversContainer.classList.add('hidden');
                    noDataMessage.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error fetching achievers:', error);
                alert('Failed to fetch achievers. Please try again.');
            });
    }

    function getHonorClass(averageGrade) {
        if (averageGrade >= 98) return 'highest-honor';
        if (averageGrade >= 95) return 'high-honor';
        if (averageGrade >= 90) return 'with-honor';
        return '';
    }

    function getHonorLabel(averageGrade) {
        if (averageGrade >= 98) return 'Highest Honor';
        if (averageGrade >= 95) return 'High Honor';
        if (averageGrade >= 90) return 'With Honor';
        return 'Not Qualified';
    }

    function getOrdinalSuffix(i) {
        const j = i % 10,
              k = i % 100;
        if (j == 1 && k != 11) return "st";
        if (j == 2 && k != 12) return "nd";
        if (j == 3 && k != 13) return "rd";
        return "th";
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', initializePeriodSelector);
</script>
@endsection
