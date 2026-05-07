<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Admin\StudentController as BaseController;
use App\Http\Controllers\Principal\Concerns\SwapsToPrincipalView;
use App\Models\GradeLevel;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends BaseController
{
    use SwapsToPrincipalView;

    public function index(Request $request)
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        $schoolYearId = $request->get('school_year_id', $activeSchoolYear?->id);
        $schoolYear = \App\Models\SchoolYear::find($schoolYearId) ?? $activeSchoolYear;

        $isActiveYear = $schoolYearId == $activeSchoolYear?->id;
        $enrollmentStatuses = $isActiveYear ? ['enrolled'] : ['enrolled', 'completed'];

        $query = Student::with([
                'user',
                'section',
                'gradeLevel',
                'enrollments' => function ($query) use ($schoolYearId, $enrollmentStatuses) {
                    $query->where('school_year_id', $schoolYearId)
                          ->whereIn('status', $enrollmentStatuses);
                },
                'enrollments.section.gradeLevel',
            ])
            ->whereHas('enrollments', function ($query) use ($schoolYearId, $enrollmentStatuses) {
                $query->where('school_year_id', $schoolYearId)
                      ->whereIn('status', $enrollmentStatuses)
                      ->whereNotNull('section_id');
            });

        if ($isActiveYear) {
            $query->where('students.status', 'active');
        }

        $query->join('users', 'users.id', '=', 'students.user_id');

        // Grade filter
        if ($request->filled('grade')) {
            $gradeName = $request->grade;
            $query->whereHas('enrollments.section.gradeLevel', function ($q) use ($gradeName) {
                $q->where('name', $gradeName);
            });
        }

        // Section filter
        if ($request->filled('section')) {
            $sectionName = $request->section;
            $query->whereHas('enrollments.section', function ($q) use ($sectionName) {
                $q->where('name', $sectionName);
            });
        }

        // Principal default sort: males first → alphabetical by last name → first name
        $query->orderByRaw("CASE WHEN students.gender = 'male' THEN 0 WHEN students.gender = 'Male' THEN 0 ELSE 1 END")
              ->orderBy('users.last_name', 'asc')
              ->orderBy('users.first_name', 'asc');

        $students = $query
            ->select('students.*')
            ->paginate(10)
            ->appends($request->only(['grade', 'section', 'school_year_id']));

        $gradeLevels = GradeLevel::orderBy('name')->get();

        return $this->swapView(
            view('admin.students.index', compact('students', 'activeSchoolYear', 'schoolYear', 'gradeLevels'))
        );
    }

    public function show(\App\Models\Student $student)
    {
        return $this->swapView(parent::show($student));
    }
}
