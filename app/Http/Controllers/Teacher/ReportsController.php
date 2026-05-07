<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;

class ReportsController extends Controller
{
    //
    public function index()
{
    return view('teacher.reports.index');
}

  public function classRecord(Request $request)
    {
        $sectionId = $request->query('section_id');
        $section = Section::find($sectionId);

        if (!$section) {
            return redirect()->back()->with('error', 'Section not found.');
        }

        // Load grades, students, etc.
        $students = $section->students; // adjust according to your relationships

        return view('teacher.reports.class-record', compact('section', 'students'));
    }
}
