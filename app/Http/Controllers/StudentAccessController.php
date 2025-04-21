<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class StudentAccessController extends Controller
{
    /**
     * Toggle access for all students
     */
    public function toggleAll(Request $request)
    {
        $enable = $request->input('enable', false);
        
        // Update all students' access
        User::where('role', 'student')
            ->update(['access_enabled' => $enable]);
            
        $status = $enable ? 'enabled' : 'disabled';
        
        return response()->json([
            'success' => true,
            'message' => "Student access has been {$status}",
            'status' => $status
        ]);
    }
    
    /**
     * Get current student access status
     */
    public function status()
    {
        // Check if any students have access enabled
        $anyEnabled = User::where('role', 'student')
            ->where('access_enabled', true)
            ->exists();
            
        // Check if all students have access disabled
        $allDisabled = User::where('role', 'student')
            ->where('access_enabled', false)
            ->count() === User::where('role', 'student')->count();
            
        return response()->json([
            'enabled' => $anyEnabled,
            'fully_disabled' => $allDisabled
        ]);
    }
} 