<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Student; 
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Subject;
use Carbon\Carbon;
use App\Models\SchoolYear;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // -----------------------------
        // Logged-in user
        // -----------------------------
        $user = auth()->user();

        if (!$user) {
            return redirect('/login')->with('error', 'Please login first.');
        }

        // -----------------------------
        // Teacher record (auto create if missing)
        // -----------------------------
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->first_name ?? 'Teacher',
                'last_name'  => $user->last_name ?? '',
                'email'      => $user->email,
            ]
        );

        // -----------------------------
        // Active School Year
        // -----------------------------
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        // -----------------------------
        // Sections assigned to teacher (adviser OR subject teacher)
        // -----------------------------
        $advisorySections = Section::with(['students.user', 'teacher.user', 'gradeLevel'])
            ->where('teacher_id', $teacher->id)
            ->when($activeSchoolYear, function ($query) use ($activeSchoolYear) {
                $query->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('is_active', true)
            ->get();
        
        $assignedSectionIds = DB::table('teacher_subject')
            ->where('teacher_id', $teacher->id)
            ->when($activeSchoolYear, function ($query) use ($activeSchoolYear) {
                $query->whereIn('section_id', function ($q) use ($activeSchoolYear) {
                    $q->select('id')->from('sections')
                      ->where('school_year_id', $activeSchoolYear->id)
                      ->where('is_active', true);
                });
            })
            ->pluck('section_id')
            ->unique()
            ->values();
        
        $assignedSections = $assignedSectionIds->isNotEmpty()
            ? Section::with(['students.user', 'teacher.user', 'gradeLevel'])
                ->whereIn('id', $assignedSectionIds)
                ->where('is_active', true)
                ->get()
            : collect();
        
        $sections = $advisorySections->merge($assignedSections)->unique('id')->values();

        // -----------------------------
        // Active Section (secured)
        // -----------------------------
        $activeSection = null;

        if ($request->section_id) {
            // Only allow teacher's own sections
            $activeSection = $sections->where('id', $request->section_id)->first();
        }

        // Default to first section
        if (!$activeSection) {
            $activeSection = $sections->first();
        }

        // -----------------------------
        // Students - FILTERED to exclude completed/inactive
        // -----------------------------
        $students = $activeSection
            ? $activeSection->students()
                ->whereHas('enrollment', function($query) {
                    // Exclude students with completed or inactive enrollment status
                    $query->whereNotIn('status', ['completed', 'inactive']);
                })
                ->where('students.status', '!=', 'inactive') // Also check students table status
                ->with('user')
                ->get()
            : collect();

        // -----------------------------
        // Student Counts
        // -----------------------------
        $totalStudents  = $students->count();
        $maleStudents   = $students->where('gender', 'male')->count();
        $femaleStudents = $students->where('gender', 'female')->count();

        // -----------------------------
        // Quarter Logic (based on admin-set quarter dates)
        // -----------------------------
        $currentQuarterObj = $activeSchoolYear?->currentQuarter();
        $currentQuarter = $currentQuarterObj ? $this->ordinal($currentQuarterObj->quarter_number) : null;
        $currentQuarterNumber = $currentQuarterObj?->quarter_number;

        // -----------------------------
        // Attendance Today
        // -----------------------------
        $today = Carbon::today();

        $todayAttendances = Attendance::whereDate('date', $today)
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        $todayStats = [
            'present' => $todayAttendances->where('status', 'present')->count(),
            'absent'  => $todayAttendances->where('status', 'absent')->count(),
            'late'    => $todayAttendances->where('status', 'late')->count(),
        ];

        $todayAttendanceRate = $totalStudents 
            ? round(($todayStats['present'] / $totalStudents) * 100)
            : 0;

        // -----------------------------
        // Grades (filtered to current quarter only)
        // -----------------------------
        $grades = Grade::whereIn('student_id', $students->pluck('id'))
            ->when($currentQuarterNumber, function ($query) use ($currentQuarterNumber) {
                $query->where('quarter', $currentQuarterNumber);
            })
            ->get();

        $pendingGradesCount = $totalStudents - $grades->groupBy('student_id')->count();
        $overdueGrading = 0;

        // -----------------------------
        // Subjects (optimized)
        // -----------------------------
        $subjects = Subject::whereHas('grades', function ($query) use ($students, $currentQuarterNumber) {
            $query->whereIn('student_id', $students->pluck('id'));
            if ($currentQuarterNumber) {
                $query->where('quarter', $currentQuarterNumber);
            }
        })->get();

        $subjectStats = [];

        // Pre-filter to final grade records only for accurate stats
        $finalGrades = $grades->where('component_type', 'final_grade');

        foreach ($subjects as $subject) {
            $subjectFinalGrades = $finalGrades->where('subject_id', $subject->id);

            $avg = $subjectFinalGrades->avg('final_grade');

            // Count unique students with final grades for this subject
            $encodedStudents = $subjectFinalGrades->pluck('student_id')->unique()->count();

            // Count unique students with failing final grades for this subject
            $atRiskStudentsForSubject = $subjectFinalGrades
                ->where('final_grade', '<', 75)
                ->pluck('student_id')
                ->unique()
                ->count();

            $subjectStats[] = [
                'subject_id'    => $subject->id,
                'name'          => $subject->name,
                'code'          => $subject->code ?? '',
                'class_average' => $avg ?? 0,
                'encoded_count' => $encodedStudents,
                'at_risk_count' => $atRiskStudentsForSubject,
                'ww_weight'     => 40,
                'pt_weight'     => 40,
                'color'         => 'blue',
                'icon'          => 'fa-book'
            ];
        }

        // -----------------------------
        // At-Risk Students (based on stored final grades only)
        // -----------------------------
        $atRiskStudentIds = $finalGrades
            ->where('final_grade', '<', 75)
            ->pluck('student_id')
            ->unique();

        $atRiskStudents = $students->whereIn('id', $atRiskStudentIds);
        $atRiskCount = $atRiskStudents->count();

        $failingGradesCount = $finalGrades
            ->where('final_grade', '<', 75)
            ->count();

        $chronicAbsentees = 0;

        // -----------------------------
        // Recent Grades (final grade records only — no duplicates)
        // -----------------------------
        $recentGrades = Grade::with(['student.user', 'subject'])
            ->whereIn('student_id', $students->pluck('id'))
            ->where('component_type', 'final_grade')
            ->latest()
            ->take(5)
            ->get();

        // -----------------------------
        // Upcoming Events
        // -----------------------------
        $upcomingEvents = \App\Models\Event::where('date', '>=', today())
            ->orderBy('date')
            ->limit(3)
            ->get();

        // -----------------------------
        // Misc
        // -----------------------------
        $upcomingDeadlines = collect();
        $schoolDaysTotal = 200;
        $daysCompleted = now()->dayOfYear;

        // -----------------------------
        // Return View
        // -----------------------------
        return view('teacher.dashboard', compact(
            'teacher',
            'sections',
            'students',
            'activeSection',
            'activeSchoolYear',
            'currentQuarter',
            'currentQuarterNumber',
            'todayStats',
            'todayAttendanceRate',
            'totalStudents',
            'maleStudents',
            'femaleStudents',
            'pendingGradesCount',
            'overdueGrading',
            'subjects',
            'subjectStats',
            'atRiskStudents',
            'atRiskCount',
            'failingGradesCount',
            'chronicAbsentees',
            'recentGrades',
            'upcomingEvents',
            'upcomingDeadlines',
            'schoolDaysTotal',
            'daysCompleted'
        ));
    }

    /**
     * Calculate final grade from stored final_grade record
     */
    private function calculateFinalGrade($grade)
    {
        if ($grade->component_type === 'final_grade') {
            return $grade->final_grade ?? 0;
        }

        return $grade->final_grade ?? 0;
    }

    /**
     * Convert number to ordinal string (1 → 1st, 2 → 2nd, etc.)
     */
    private function ordinal(int $number): string
    {
        $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        }
        return $number . ($suffixes[$number % 10] ?? 'th');
    }
}