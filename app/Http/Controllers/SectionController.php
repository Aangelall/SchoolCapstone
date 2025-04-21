<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Strand;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    // Get all sections grouped by grade level
    public function index()
    {
        $sections = Section::orderBy('grade_level')
                          ->orderBy('name')
                          ->get()
                          ->groupBy('grade_level');
        
        return response()->json($sections);
    }

    // Get sections for a specific grade level
    public function getByGradeLevel($gradeLevel)
    {
        // Convert grade level to integer
        $gradeLevel = (int)$gradeLevel;
        
        // Get sections for the specific grade level
        $sections = Section::where('grade_level', $gradeLevel)
            ->orderBy('name')
            ->get(['id', 'name']);
            
        return response()->json($sections);
    }

    public function getSectionsByStrand(Request $request)
    {
        $request->validate([
            'strand' => 'required|string',
            'grade_level' => 'required|in:11,12'
        ]);
        
        $sections = Section::where('strand', $request->strand)
                          ->where('grade_level', $request->grade_level)
                          ->orderBy('name')
                          ->get();
        
        // Return as simple array of section names
        return response()->json($sections->pluck('name'));
        
        // Or return full objects if needed:
        // return response()->json($sections);
    }    

    // Store a new section
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'required|integer|between:7,12',
            'strand_id' => 'sometimes|nullable|exists:strands,id'
        ]);

        $section = Section::create($validated);
        
        return response()->json($section, 201);
    }

    // Update a section
    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $section->update($validated);
        
        return response()->json($section);
    }

    // Delete a section
    public function destroy(Section $section)
    {
        $section->delete();
        
        return response()->json(null, 204);
    }
}
