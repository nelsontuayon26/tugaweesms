<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\SchoolYear;

class SectionsController extends Controller
{
    /**
     * Display a specific section
     */
    public function show(Section $section)
    {
        $teacherId = auth()->user()->teacher->id ?? null;

        if ($section->teacher_id != $teacherId) {
            abort(403, 'Unauthorized');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        return view('teacher.sections.show', compact('section'));
    }

    public function index()
    {
        $teacher = auth()->user()->teacher;
        $activeSchoolYear = SchoolYear::getActive();
        $sections = $teacher
            ? \App\Models\Section::with('gradeLevel')
                ->where('teacher_id', $teacher->id)
                ->where('is_active', true)
                ->when($activeSchoolYear, fn($q) => $q->where('school_year_id', $activeSchoolYear->id))
                ->get()
            : collect();
        return view('teacher.sections.index', compact('sections'));
    }

  // Custom method for grades
  /**
     * Show students of a section
     */
    public function students(Section $section)
    {
        $teacherId = auth()->user()->teacher->id ?? null;

        if ($section->teacher_id != $teacherId) {
            abort(403, 'Unauthorized');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $students = $section->students; // assumes Section has students() relationship
        return view('teacher.sections.students', compact('section', 'students'));
    }

    /**
     * Show grades of a section
     */
    public function grades(Section $section)
    {
        $teacherId = auth()->user()->teacher->id ?? null;

        if ($section->teacher_id != $teacherId) {
            abort(403, 'Unauthorized');
        }

        $students = $section->students()->with('grades')->get();
        return view('teacher.sections.grades', compact('section', 'students'));
    }

    public function attendance(Section $section)
{
    $teacherId = auth()->user()->teacher->id ?? null;

    if ($section->teacher_id != $teacherId) {
        abort(403, 'Unauthorized');
    }

    $students = $section->students; // assumes Section model has students() relationship

    // optionally, load attendance records if you have Attendance model
    // $attendanceRecords = Attendance::where('section_id', $section->id)->get();

    return view('teacher.sections.attendance', compact('section', 'students'));
}


}
