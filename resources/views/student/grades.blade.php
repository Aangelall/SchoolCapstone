@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="section-header">
                <div class="header-content">
                    <h1>My Grades</h1>
                    @if(count($gradeHistory) > 0)
                        <div class="school-year-selector">
                            <select id="schoolYearSelect" class="form-select" onchange="showSelectedYear(this.value)">
                                @foreach($gradeHistory as $index => $yearData)
                                <option value="{{ $index }}">
                                    School Year {{ $yearData['school_year'] }} -
                                    @if($yearData['level_type'] === 'junior')
                                        Grade {{ $yearData['year_level'] }}
                                    @else
                                        {{ $yearData['strand'] }} Grade {{ $yearData['year_level'] }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
            </div>

            @if(count($gradeHistory) > 0)
                @foreach($gradeHistory as $index => $yearData)
                    <div id="year-{{ $index }}" class="year-section" style="display: {{ $index === 0 ? 'block' : 'none' }}">
                        <!-- Student Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Class Info -->
                            <div class="info-card bg-blue-50">
                                <h2 class="info-card-title text-blue-800">Class Information</h2>
                                <div class="info-card-content">
                                    <p class="info-item">
                                        <span class="info-label">School Year:</span>
                                        <span class="info-value text-blue-700">{{ $yearData['school_year'] }}</span>
                                    </p>
                                    <p class="info-item">
                                        <span class="info-label">Section:</span>
                                        <span class="info-value text-blue-700">
                                            @if(is_numeric($yearData['section']))
                                                {{ \App\Models\Section::find($yearData['section'])->name ?? $yearData['section'] }}
                                            @else
                                                {{ $yearData['section'] }}
                                            @endif
                                        </span>
                                    </p>
                                    <p class="info-item">
                                        <span class="info-label">Year Level:</span>
                                        <span class="info-value text-blue-700">
                                            @if($yearData['level_type'] === 'junior')
                                            Grade {{ $yearData['year_level'] }}
                                        @else
                                            {{ $yearData['strand'] }} - Grade {{ $yearData['year_level'] }}
                                        @endif
                                        </span>
                                    </p>
                                    <p class="info-item">
                                        <span class="info-label">Adviser:</span>
                                        <span class="info-value text-blue-700">{{ $yearData['adviser_name'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Grades Table -->
                        <div class="table-container">
                            <div class="table-header">
                                <div class="table-header-left">
                                    <h2>Subject Grades</h2>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="grades-table">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Teacher</th>
                                            @foreach($yearData['grades']->first()['grades'] as $index => $grade)
                                                <th class="text-center">
                                                    {{ $index + 1 }}{{ $index === 0 ? 'st' : ($index === 1 ? 'nd' : ($index === 2 ? 'rd' : 'th')) }}
                                                    {{ $yearData['level_type'] === 'junior' ? 'Quarter' : 'Semester' }}
                                                </th>
                                            @endforeach
                                            <th class="text-center">Final Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($yearData['grades'] as $subject)
                                            @php
                                                $validGrades = array_filter($subject['grades'], function($grade) {
                                                    return $grade !== null;
                                                });
                                                $finalRating = count($validGrades) > 0 ? round(array_sum($validGrades) / count($validGrades)) : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $subject['subject_name'] }}</td>
                                                <td>{{ $subject['teacher_name'] }}</td>
                                                @foreach($subject['grades'] as $grade)
                                                    <td class="text-center">
                                                        @if($grade !== null)
                                                            <span class="grade-cell">
                                                                {{ (int)$grade }}
                                                            </span>
                                                        @else
                                                            <span class="grade-cell">--</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    @if($finalRating !== null)
                                                        <span class="grade-cell">
                                                            {{ (int)$finalRating }}
                                                        </span>
                                                    @else
                                                        <span class="grade-cell">--</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Honor Status Table -->
                        <div class="table-container mb-6">
                            <div class="table-header">
                                <div class="table-header-left">
                                    <h2>Academic Performance Summary</h2>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="grades-table">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 text-center">Period</th>
                                            <th class="px-6 py-3 text-center">Average</th>
                                            <th class="px-6 py-3 text-center">Honor Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $periodType = $yearData['level_type'] === 'junior' ? 'Quarter' : 'Semester';
                                            $periods = $yearData['level_type'] === 'junior' ? 4 : 2;
                                            $periodAverages = [];
                                            $totalFinalRating = 0;
                                            $subjectCount = 0;

                                            // Calculate average for each period
                                            for ($i = 0; $i < $periods; $i++) {
                                                $total = 0;
                                                $count = 0;
                                                foreach ($yearData['grades'] as $subject) {
                                                    if (isset($subject['grades'][$i]) && $subject['grades'][$i] !== null) {
                                                        $total += $subject['grades'][$i];
                                                        $count++;
                                                    }
                                                }
                                                $periodAverages[$i] = $count > 0 ? round($total / $count) : null;
                                            }

                                            // Calculate general average
                                            foreach ($yearData['grades'] as $subject) {
                                                $gradesArray = array_values($subject['grades']);
                                                $validGrades = array_filter($gradesArray, function($grade) {
                                                    return $grade !== null;
                                                });
                                                $finalRating = count($validGrades) > 0 ? round(array_sum($validGrades) / count($validGrades)) : null;

                                                if ($finalRating !== null) {
                                                    $totalFinalRating += $finalRating;
                                                    $subjectCount++;
                                                }
                                            }

                                            $generalAverage = $subjectCount > 0 ? round($totalFinalRating / $subjectCount) : null;
                                        @endphp

                                        @for ($i = 0; $i < $periods; $i++)
                                            @php
                                                $average = $periodAverages[$i];
                                                $honorStatus = 'Pending';

                                                if ($average !== null) {
                                                    if ($average >= 98) {
                                                        $honorStatus = 'With Highest Honor';
                                                    } elseif ($average >= 95) {
                                                        $honorStatus = 'With High Honor';
                                                    } elseif ($average >= 90) {
                                                        $honorStatus = 'With Honor';
                                                    } else {
                                                        $honorStatus = '--';
                                                    }
                                                }
                                            @endphp
                                            <tr>
                                                <td class="px-6 py-4 text-center">{{ $i + 1 }}{{ $i === 0 ? 'st' : ($i === 1 ? 'nd' : ($i === 2 ? 'rd' : 'th')) }} {{ $periodType }}</td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($average !== null)
                                                        <span class="grade-cell">{{ (int)$average }}</span>
                                                    @else
                                                        <span class="grade-cell">--</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-center">
                                                    @if ($average !== null)
                                                        <span class="honor-badge">{{ $honorStatus }}</span>
                                                    @else
                                                        <span class="honor-badge">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endfor

                                        <!-- Final Average -->
                                        <tr class="bg-gray-50 font-bold">
                                            <td class="px-6 py-4 text-center">Final Average</td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="grade-cell">
                                                    {{ $generalAverage !== null ? (int)$generalAverage : '--' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $finalHonorStatus = 'Pending';
                                                    if ($generalAverage !== null) {
                                                        if ($generalAverage >= 98) {
                                                            $finalHonorStatus = 'With Highest Honor';
                                                        } elseif ($generalAverage >= 95) {
                                                            $finalHonorStatus = 'With High Honor';
                                                        } elseif ($generalAverage >= 90) {
                                                            $finalHonorStatus = 'With Honor';
                                                        } else {
                                                            $finalHonorStatus = '--';
                                                        }
                                                    }
                                                @endphp
                                                <span class="honor-badge">{{ $finalHonorStatus }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-data-message">
                    <div class="flex items-center justify-center">
                        <i class='bx bx-info-circle text-yellow-400 text-4xl mr-3'></i>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-700">No Class Assigned</h3>
                            <p class="text-gray-500 mt-2">You are currently not assigned to any class.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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

    .school-year-selector {
        margin-left: auto;
    }

    .form-select {
        padding: 0.5rem;
        border-radius: 0.375rem;
        border: 1px solid #d1d5db;
        background-color: white;
        min-width: 200px;
    }

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

    .grades-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    .grades-table th,
    .grades-table td {
        padding: 12px 16px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .grades-table th {
        background-color: #f5f5f5;
        font-weight: 500;
        color: #666;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .grades-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .grade-cell {
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        min-width: 60px;
        text-align: center;
        font-weight: 500;
    }

    .honor-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        display: inline-block;
        min-width: 120px;
        background-color: #f3f4f6;
        color: #6b7280;
    }

    .year-section {
        margin-bottom: 2rem;
    }

    .no-data-message {
        padding: 48px 24px;
        text-align: center;
        background-color: #fff;
        border-radius: 8px;
        border: 2px dashed #e0e0e0;
    }

    @media (max-width: 768px) {
        .grades-table th,
        .grades-table td {
            padding: 8px;
            font-size: 12px;
        }

        .grade-cell {
            min-width: 50px;
            padding: 4px;
            font-size: 12px;
        }

        .info-card {
            padding: 12px;
        }

        .info-card-title {
            font-size: 16px;
        }

        .info-label {
            min-width: 80px;
        }

        .honor-badge {
            min-width: 100px;
            font-size: 11px;
            padding: 4px 8px;
        }
    }
</style>

<script>
function showSelectedYear(index) {
    document.querySelectorAll('.year-section').forEach(section => {
        section.style.display = 'none';
    });
    document.getElementById(`year-${index}`).style.display = 'block';
}
</script>
@endsection