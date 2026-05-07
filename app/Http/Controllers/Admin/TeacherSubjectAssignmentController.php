<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Section;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherSubjectAssignmentController extends Controller
{
    /**
     * Display a listing of teacher-subject assignments.
     */
    public function index(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $selectedSchoolYearId = $request->get('school_year_id', $activeSchoolYear?->id);
        $selectedSchoolYear = $selectedSchoolYearId ? SchoolYear::find($selectedSchoolYearId) : null;

        // Build assignment query with joins for readable names
        $query = DB::table('teacher_subject')
            ->join('teachers', 'teacher_subject.teacher_id', '=', 'teachers.id')
            ->join('subjects', 'teacher_subject.subject_id', '=', 'subjects.id')
            ->leftJoin('sections', 'teacher_subject.section_id', '=', 'sections.id')
            ->leftJoin('grade_levels', 'sections.grade_level_id', '=', 'grade_levels.id')
            ->select(
                'teacher_subject.id',
                'teacher_subject.teacher_id',
                'teacher_subject.subject_id',
                'teacher_subject.section_id',
                'teacher_subject.school_year',
                'teacher_subject.schedule',
                'teacher_subject.time_start',
                'teacher_subject.time_end',
                'teacher_subject.room',
                'teacher_subject.created_at',
                DB::raw("CONCAT(COALESCE(teachers.first_name,''), ' ', COALESCE(teachers.last_name,'')) as teacher_name"),
                'subjects.name as subject_name',
                'subjects.code as subject_code',
                'sections.name as section_name',
                'grade_levels.name as grade_level_name'
            )
            ->orderBy('teachers.last_name')
            ->orderBy('subjects.name');

        // Filter by school year (use school_year varchar or derive from section)
        if ($selectedSchoolYear) {
            $query->where(function ($q) use ($selectedSchoolYear) {
                $q->where('teacher_subject.school_year', $selectedSchoolYear->name)
                  ->orWhere('sections.school_year_id', $selectedSchoolYear->id);
            });
        }

        // Filter by teacher
        if ($request->filled('teacher_id')) {
            $query->where('teacher_subject.teacher_id', $request->teacher_id);
        }

        // Filter by section
        if ($request->filled('section_id')) {
            $query->where('teacher_subject.section_id', $request->section_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('teacher_subject.subject_id', $request->subject_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('teachers.first_name', 'like', "%{$search}%")
                  ->orWhere('teachers.last_name', 'like', "%{$search}%")
                  ->orWhere('subjects.name', 'like', "%{$search}%")
                  ->orWhere('subjects.code', 'like', "%{$search}%")
                  ->orWhere('sections.name', 'like', "%{$search}%");
            });
        }

        $assignments = $query->paginate(20)->appends($request->query());

        // Stats
        $stats = [
            'total' => DB::table('teacher_subject')
                ->when($selectedSchoolYear, fn($q) => $q->where('school_year', $selectedSchoolYear->name))
                ->count(),
            'teachers' => DB::table('teacher_subject')
                ->when($selectedSchoolYear, fn($q) => $q->where('school_year', $selectedSchoolYear->name))
                ->distinct('teacher_id')
                ->count('teacher_id'),
            'sections' => DB::table('teacher_subject')
                ->when($selectedSchoolYear, fn($q) => $q->where('school_year', $selectedSchoolYear->name))
                ->distinct('section_id')
                ->count('section_id'),
        ];

        // Dropdown data for create form
        $teachers = Teacher::with('user')
            ->whereNotNull('first_name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $sections = Section::with(['gradeLevel', 'schoolYear', 'teacher'])
            ->when($selectedSchoolYear, fn($q) => $q->where('school_year_id', $selectedSchoolYear->id))
            ->where('is_active', true)
            ->orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        $subjects = Subject::with('gradeLevel')
            ->orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();

        return view('admin.teacher-subject-assignments.index', compact(
            'assignments',
            'stats',
            'teachers',
            'sections',
            'subjects',
            'schoolYears',
            'activeSchoolYear',
            'selectedSchoolYear'
        ));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'school_year' => 'nullable|string|max:50',
            'schedule' => 'nullable|string|max:100',
            'time_start' => 'nullable|date_format:H:i',
            'time_end' => 'nullable|date_format:H:i|after:time_start',
            'room' => 'nullable|string|max:50',
        ], [
            'time_end.after' => 'End time must be after start time.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        $data = $validator->validated();

        // Default school year to active if not provided
        if (empty($data['school_year'])) {
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();
            $data['school_year'] = $activeSchoolYear?->name;
        }

        // Check for duplicate assignment
        $exists = DB::table('teacher_subject')
            ->where('teacher_id', $data['teacher_id'])
            ->where('subject_id', $data['subject_id'])
            ->where('section_id', $data['section_id'])
            ->when($data['school_year'], fn($q) => $q->where('school_year', $data['school_year']))
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This teacher is already assigned to that subject and section.');
        }

        DB::table('teacher_subject')->insert([
            'teacher_id' => $data['teacher_id'],
            'subject_id' => $data['subject_id'],
            'section_id' => $data['section_id'],
            'school_year' => $data['school_year'],
            'schedule' => $data['schedule'] ?? null,
            'time_start' => $data['time_start'] ?? null,
            'time_end' => $data['time_end'] ?? null,
            'room' => $data['room'] ?? null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.teacher-subject-assignments.index')
            ->with('success', 'Subject assignment created successfully.');
    }

    /**
     * Remove an assignment.
     */
    public function destroy($id)
    {
        $deleted = DB::table('teacher_subject')->where('id', $id)->delete();

        if ($deleted) {
            return redirect()->route('admin.teacher-subject-assignments.index')
                ->with('success', 'Assignment removed successfully.');
        }

        return redirect()->route('admin.teacher-subject-assignments.index')
            ->with('error', 'Assignment not found.');
    }
}
