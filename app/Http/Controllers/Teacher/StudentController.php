<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\Student;
use App\Models\GradeLevel;
use App\Models\SchoolYear;

class StudentController extends Controller
{
    /**
     * Display students under a teacher's section
     */
    public function index(Section $section)
    {
        // Ensure teacher only accesses their own section
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403, 'Unauthorized access.');
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        // Load only active/enrolled students - exclude completed/inactive
        $students = $section->students()
            ->whereHas('enrollment', function($query) {
                // Exclude students with completed or inactive enrollment status
                $query->whereNotIn('status', ['completed', 'inactive']);
            })
            ->where('students.status', '!=', 'inactive') // Also check students table status
            ->with(['user', 'enrollment']) // eager load relationships
            ->paginate(10);

        return view('teacher.students.index', compact('section', 'students'));
    }

    public function create($sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $gradeLevels = GradeLevel::orderBy('order')->get();

        return view('teacher.students.create', compact('section', 'gradeLevels'));
    }

    public function show(Student $student)
    {
        // Optional: Check if student is still active before showing
        if ($student->status === 'inactive' || 
            (isset($student->enrollment) && in_array($student->enrollment->status, ['completed', 'inactive']))) {
            abort(404, 'Student record not found or inactive.');
        }

        return view('teacher.students.show', compact('student'));
    }

    // Show edit form
    public function edit(Student $student)
    {
        // Optional: Prevent editing inactive/completed students
        if ($student->status === 'inactive' || 
            (isset($student->enrollment) && in_array($student->enrollment->status, ['completed', 'inactive']))) {
            return redirect()->route('teacher.students.index', $student->section_id)
                ->with('error', 'Cannot edit inactive or completed student records.');
        }

        return view('teacher.students.edit', compact('student'));
    }

    // Update student
    public function update(Request $request, Student $student)
    {
        // Optional: Prevent updating inactive/completed students
        if ($student->status === 'inactive' || 
            (isset($student->enrollment) && in_array($student->enrollment->status, ['completed', 'inactive']))) {
            return redirect()->route('teacher.students.index', $student->section_id)
                ->with('error', 'Cannot update inactive or completed student records.');
        }

        $student->update($request->only([
            'first_name', 'middle_name', 'last_name', 'gender',
            'lrn', 'section_id', 'birthdate', 'birth_place',
            'nationality', 'religion', 'contact_number',
            'address', 'guardian_name', 'guardian_contact',
            'email', 'photo'
        ]));
        return redirect()->route('teacher.students.show', $student)->with('success', 'Student updated successfully.');
    }

    // Delete student
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('teacher.students.index')->with('success', 'Student deleted successfully.');
    }
}