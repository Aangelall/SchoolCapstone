<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Magallanes National High School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }
        .left-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 55%;
            height: 100%;
            background-color: #e6f0f5;
            clip-path: polygon(0 0, 100% 0, 85% 100%, 0 100%);
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .right-section {
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
        }
        .login-form-container {
            position: relative;
            z-index: 20;
            width: 100%;
            max-width: 320px;
            padding: 0 20px;
        }

        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .left-section {
                width: 100%;
                clip-path: none;
                background-color: rgba(230, 240, 245, 0.9);
            }

            .right-section {
                z-index: 5;
            }

            .login-form-container {
                max-width: 280px;
            }
        }

        /* Small mobile devices */
        @media (max-width: 375px) {
            .login-form-container {
                max-width: 250px;
                padding: 0 10px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Right section with background image -->
        <div class="right-section" style="background-image: url('{{ asset('img/magallanes.png') }}');"></div>

        <!-- Left section with login form -->
        <div class="left-section">
            <div class="login-form-container">
                <!-- Logo -->
                <div class="text-center mb-6">
                    <img src="{{ asset('img/LOGO.png') }}" alt="School Logo" class="h-20 w-20 mx-auto mb-3 sm:h-24 sm:w-24 sm:mb-4">
                    <p class="text-gray-600 mb-1 text-sm sm:text-base">Welcome to</p>
                    <h1 class="text-xl font-bold text-[#2e7d32] mb-1 sm:text-2xl">Magallanes National</h1>
                    <h1 class="text-xl font-bold text-[#2e7d32] mb-4 sm:text-2xl sm:mb-6">High School</h1>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-sm font-medium text-green-600 w-full">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf

                    <!-- Username (Email) -->
                    <div class="mb-4">
                        <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Username" required autofocus autocomplete="username" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-600">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Login Button -->
                    <div class="mb-4">
                        <button type="submit" class="w-full py-2 px-4 bg-[#2e7d32] text-white font-medium rounded-md hover:bg-[#1b5e20] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Login
                        </button>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between text-xs sm:text-sm">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-green-600 hover:text-green-500">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
