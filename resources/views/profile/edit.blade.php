@extends('layouts.app')

@section('content')
<div class="home-section">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Profile Information
                    </h2>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">
                        Update Password
                    </h2>
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .home-section {
        position: relative;
        background: #e6f0f9;
        min-height: 100vh;
        top: 0;
        left: 78px;
        width: calc(100% - 78px);
        transition: all 0.3s ease;
        z-index: 2;
        margin-top: 64px;
        padding: 20px;
    }

    .sidebar.open ~ .home-section {
        left: 250px;
        width: calc(100% - 250px);
    }

    @media (max-width: 768px) {
        .home-section {
            left: 0;
            width: 100%;
        }

        .sidebar.open ~ .home-section {
            left: 0;
            width: 100%;
            transform: translateX(250px);
        }
    }
</style>
@endsection
