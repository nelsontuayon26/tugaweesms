<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Enrollment; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        // Allow admin to override which school year to view
        $selectedSchoolYearId = $request->get('school_year_id', $activeSchoolYear?->id);
        $selectedSchoolYear = $selectedSchoolYearId ? SchoolYear::find($selectedSchoolYearId) : null;
        
        $query = Section::with(['gradeLevel', 'teacher', 'schoolYear']);
        
        // Filter by selected school year (default = active school year)
        if ($selectedSchoolYear) {
            $query->where('school_year_id', $selectedSchoolYear->id);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('room_number', 'like', "%{$search}%")
                  ->orWhereHas('teacher', function($tq) use ($search) {
                      $tq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('gradeLevel', function($gq) use ($search) {
                      $gq->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $sections = $query->orderBy('grade_level_id')->orderBy('name')->paginate(10)->appends($request->query());
        
        // Get all school years for the dropdown
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        
        // Calculate total students - only active enrollments in SELECTED school year
        $totalStudents = 0;
        if ($selectedSchoolYear) {
            $totalStudents = Enrollment::where('school_year_id', $selectedSchoolYear->id)
                ->where('status', 'enrolled')
                ->count();
        }
        
        // Load only active students for each section (for the SELECTED school year)
        foreach ($sections as $section) {
            $section->active_students = collect();
            if ($selectedSchoolYear) {
                $section->active_students = Student::whereHas('enrollments', function($q) use ($section, $selectedSchoolYear) {
                    $q->where('section_id', $section->id)
                      ->where('school_year_id', $selectedSchoolYear->id)
                      ->where('status', 'enrolled'); // Only enrolled, not completed
                })->get();
            }
        }
        
        return view('admin.sections.index', compact(
            'sections', 
            'totalStudents', 
            'activeSchoolYear', 
            'selectedSchoolYear',
            'schoolYears'
        ));
    }

    public function show($id)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        $section = Section::with(['teacher', 'gradeLevel'])->findOrFail($id);
        
        // Load only currently enrolled students in active school year
        $students = collect();
        if ($activeSchoolYear) {
            $students = Student::with(['user', 'enrollments'])
                ->whereHas('enrollments', function($q) use ($section, $activeSchoolYear) {
                    $q->where('section_id', $section->id)
                      ->where('school_year_id', $activeSchoolYear->id)
                      ->where('status', 'enrolled'); // Only enrolled, not completed
                })
                ->get();
        }
        
        $section->setRelation('active_students', $students);

        return view('admin.sections.show', compact('section'));
    }

    public function idCards(Section $section)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        $students = collect();
        if ($activeSchoolYear) {
            $students = Student::with(['user', 'enrollments'])
                ->whereHas('enrollments', function($q) use ($section, $activeSchoolYear) {
                    $q->where('section_id', $section->id)
                      ->where('school_year_id', $activeSchoolYear->id)
                      ->where('status', 'enrolled');
                })
                ->get()
                ->sortBy(function ($student) {
                    return strtolower($student->full_name);
                })
                ->values();
        }
        
        return view('admin.sections.id-cards', compact('section', 'students'));
    }

    public function create()
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        
        // Only teachers without sections in active school year
        $activeYear = SchoolYear::getActive();
        $teachers = Teacher::whereDoesntHave('section', function ($q) use ($activeYear) {
                $q->where('school_year_id', $activeYear?->id);
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $activeSchoolYear = $activeYear;

        return view('admin.sections.create', compact(
            'gradeLevels', 
            'teachers', 
            'schoolYears',
            'activeSchoolYear'
        ));
    }

    public function store(Request $request)
    {
        $activeYear = SchoolYear::getActive();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'school_year_id' => 'nullable|exists:school_years,id',
            'teacher_id' => 'nullable|exists:teachers,id|unique:sections,teacher_id,NULL,id,school_year_id,' . ($request->school_year_id ?? $activeYear?->id),
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1|max:60',
        ], [
            'teacher_id.unique' => 'This teacher is already assigned to a section in this school year.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Auto-assign active school year if none selected
        $data = $validator->validated();
        if (empty($data['school_year_id']) && $activeYear) {
            $data['school_year_id'] = $activeYear->id;
        }

        Section::create($data);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $gradeLevels = GradeLevel::orderBy('name')->get();
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        
        $activeYear = SchoolYear::getActive();
        $teachers = Teacher::whereDoesntHave('section', function ($q) use ($activeYear, $section) {
                $q->where('school_year_id', $activeYear?->id ?? $section->school_year_id)
                  ->where('id', '!=', $section->teacher_id);
            })
            ->orWhere('id', $section->teacher_id)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('admin.sections.edit', compact('section', 'gradeLevels', 'teachers', 'schoolYears'));
    }

    public function update(Request $request, Section $section)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'school_year_id' => 'nullable|exists:school_years,id',
            'teacher_id' => 'nullable|exists:teachers,id|unique:sections,teacher_id,' . $section->id . ',id,school_year_id,' . ($request->school_year_id ?? $section->school_year_id),
            'room_number' => 'nullable|string|max:20',
            'capacity' => 'nullable|integer|min:1|max:60',
        ], [
            'teacher_id.unique' => 'This teacher is already assigned to another section in this school year.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $section->update($validator->validated());

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        DB::beginTransaction();
        try {
            $section->delete();
            DB::commit();
            return redirect()->route('admin.sections.index')
                ->with('success', 'Section deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.sections.index')
                ->with('error', 'Failed to delete section: ' . $e->getMessage());
        }
    }
}