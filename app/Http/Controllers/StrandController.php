<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StrandController extends Controller
{
    public function index()
    {
        $strands = Strand::with(['sections' => function($query) {
            $query->orderBy('grade_level')->orderBy('name');
        }])->get();
        
        return response()->json($strands);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:strands,name'
        ]);

        $strand = Strand::create([
            'name' => $request->name
        ]);

        return response()->json($strand, 201);
    }

    public function destroy(Strand $strand)
    {
        try {
            DB::beginTransaction();
            
            // Delete all sections associated with this strand
            $strand->sections()->delete();
            
            // Delete the strand
            $strand->delete();
            
            DB::commit();
            return response()->json(['message' => 'Strand deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to delete strand'], 500);
        }
    }

    public function storeSection(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'strand_id' => 'required|exists:strands,id',
            'grade_level' => 'required|integer|in:11,12'
        ]);

        $section = Section::create([
            'name' => $request->name,
            'strand_id' => $request->strand_id,
            'grade_level' => $request->grade_level
        ]);

        return response()->json($section, 201);
    }

    public function updateSection(Request $request, Section $section)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'grade_level' => 'sometimes|integer|in:11,12'
        ]);

        $section->update([
            'name' => $request->name,
            'grade_level' => $request->grade_level ?? $section->grade_level
        ]);

        return response()->json($section);
    }

    public function destroySection(Section $section)
    {
        try {
            $section->delete();
            return response()->json(['message' => 'Section deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete section'], 500);
        }
    }
} 