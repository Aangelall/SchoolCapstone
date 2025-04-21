@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="container">
        <div class="main-card">
            <!-- Header -->
            <div class="section-header">
                <div class="header-content">
                    <h1>Student Dashboard</h1>
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
                            <span class="info-label">LRN:</span>
                            <span class="info-value text-blue-700">{{ Auth::user()->email }}</span>
                        </p>
                        <!-- <p class="info-item">
                            <span class="info-label">Role:</span>
                            <span class="info-value text-blue-700">{{ ucfirst(Auth::user()->role) }}</span>
                        </p> -->
                    </div>
                </div>

                <!-- Account Info -->
                <div class="info-card bg-green-50">
                    <h2 class="info-card-title text-green-800">Account Details</h2>
                    <div class="info-card-content">
                        <p class="info-item">
                            <span class="info-label">Status:</span>
                            <span class="info-value text-green-700">Active</span>
                        </p>
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
</style>
@endsection
