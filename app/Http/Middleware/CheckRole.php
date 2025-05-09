<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // If the user's role is in the allowed roles, proceed
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect based on the user's role with error message
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            case 'teacher':
            case 'student':
                return redirect()->route('user.dashboard')
                    ->with('error', 'You do not have permission to access that page.');
            default:
                return redirect('/');
        }
    }
}