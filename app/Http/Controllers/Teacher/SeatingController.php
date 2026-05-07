<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\SchoolYear;

class SeatingController extends Controller
{
    public function index(Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with('user')
            ->get();

        // Get seating arrangement from section settings
        $seatingArrangement = $section->seating_arrangement ?? [];

        return view('teacher.seating.index', compact('section', 'students', 'seatingArrangement'));
    }

    public function save(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $request->validate([
            'arrangement' => 'required|array',
        ]);

        $section->update([
            'seating_arrangement' => $request->arrangement,
        ]);

        return response()->json(['success' => true, 'message' => 'Seating arrangement saved.']);
    }

    public function roster(Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with(['user', 'gradeLevel'])
            ->orderBy('last_name')
            ->get();

        return view('teacher.seating.roster', compact('section', 'students'));
    }
}
