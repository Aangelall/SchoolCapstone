<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Create role-based Blade directives
        Blade::if('admin', function () {
            return Auth::check() && Auth::user()->role === 'admin';
        });

        Blade::if('teacher', function () {
            return Auth::check() && Auth::user()->role === 'teacher';
        });

        Blade::if('student', function () {
            return Auth::check() && Auth::user()->role === 'student';
        });
    }
}