@extends('layouts.app')

@section('content')
<style>
    .dashboard-container {
        background: linear-gradient(135deg, #f6f9fc 0%, #ecf3f8 100%);
        min-height: 100vh;
        padding: 2rem;
    }

    .welcome-header {
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .welcome-header h1 {
        font-size: 1.875rem;
        color: #1a365d;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-header p {
        color: #64748b;
        font-size: 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.5rem;
        margin: 0 auto;
        max-width: 1400px;
    }

    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .stat-card {
        position: relative;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        border: 1px solid rgba(226, 232, 240, 0.8);
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-color) 0%, var(--accent-color-light) 100%);
    }

    .stat-card.advisory {
        --accent-color: #3b82f6;
        --accent-color-light: #60a5fa;
    }

    .stat-card.students {
        --accent-color: #10b981;
        --accent-color-light: #34d399;
    }

    .stat-card.teachers {
        --accent-color: #f59e0b;
        --accent-color-light: #fbbf24;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-color-light) 100%);
    }

    .stat-icon svg {
        width: 24px;
        height: 24px;
        color: white;
    }

    .stat-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0.5rem 0;
        line-height: 1;
    }

    .stat-description {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }

    .stat-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--accent-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .stat-link:hover {
        gap: 0.75rem;
        opacity: 0.9;
    }

    .stat-link svg {
        width: 16px;
        height: 16px;
    }
</style>
<div class="home-section" >
<div class="dashboard-container">
    <div class="welcome-header">
        <h1>Welcome to Admin Dashboard</h1>
        <p>Monitor and manage your educational institution's key metrics</p>
    </div>

    <div class="stats-grid">
        <!-- Advisory Classes Card -->
        <div class="stat-card advisory">
            <div class="stat-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="stat-title">Advisory Classes</h2>
            </div>
            <div class="stat-value">{{ \App\Models\Classes::count() }}</div>
            <p class="stat-description">Active advisory classes across all grade levels</p>
            <div class="stat-footer">
                <a href="{{ route('section.subject') }}" class="stat-link">
                    <span>View Classes</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Students Card -->
        <div class="stat-card students">
            <div class="stat-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <h2 class="stat-title">Students</h2>
            </div>
            <div class="stat-value">{{ \App\Models\User::where('role', 'student')->count() }}</div>
            <p class="stat-description">Total enrolled students in the institution</p>
            <div class="stat-footer">
                <a href="{{ route('add.student') }}" class="stat-link">
                    <span>Manage Students</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Teachers Card -->
        <div class="stat-card teachers">
            <div class="stat-header">
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <h2 class="stat-title">Teachers</h2>
            </div>
            <div class="stat-value">{{ \App\Models\User::where('role', 'teacher')->count() }}</div>
            <p class="stat-description">Active teaching staff members</p>
            <div class="stat-footer">
                <a href="{{ route('add.teacher') }}" class="stat-link">
                    <span>Manage Teachers</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection