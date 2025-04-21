<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Magallanes National High School</title>
        <link rel="icon" href="{{ asset('img/LOGO.png') }}" type="image/x-icon" />
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            @media (max-width: 768px) {
                .min-h-screen {
                    min-height: 100vh;
                    padding-left: 0; /* Remove padding for mobile */
                }

                .content-wrapper {
                    padding-top: 64px; /* Add padding for the navbar */
                    width: 100% !important;
                    margin-left: 0 !important;
                }

                .sidebar.open ~ .content-wrapper {
                    margin-left: 0 !important;
                    transform: translateX(250px);
                }
            }

            .content-wrapper {
                position: relative;
                min-height: calc(100vh - 64px);
                margin-left: 50px;
                transition: all 0.3s ease;
                width: calc(100% - 78px);
            }

            .sidebar.open ~ .content-wrapper {
                margin-left: 210px;
                width: calc(100% - 250px);
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen" style="background-color: #e6f0f9;">

            <!-- Page Heading -->
            @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endisset

            <!-- Page Content -->
            <main>
                @include('layouts.navigation')
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </main>
        </div>
    </body>
</html>
