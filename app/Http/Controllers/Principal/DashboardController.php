<?php

namespace App\Http\Controllers\Principal;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;

class DashboardController extends Controller
{
    /**
     * Load the principal dashboard with school-wide overview stats
     */
    public function index()
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        if (!$activeSchoolYear) {
            return view('principal.dashboard', [
                'activeSchoolYear' => null,
                'totalStudents' => 0,
                'totalTeachers' => \App\Models\Teacher::count(),
                'totalSections' => 0,
                'maleStudents' => 0,
                'femaleStudents' => 0,
                'activeTeachers' => \App\Models\Teacher::where('status', 'active')->count(),
                'teachersOnLeave' => \App\Models\Teacher::where('status', 'on_leave')->count(),
                'presentToday' => 0,
                'absentToday' => 0,
                'lateToday' => 0,
                'avgGrade' => 0,
                'above90' => 0,
                'below75' => 0,
                'gradeDistribution' => [],
                'recentStudents' => collect(),
                'upcomingEvents' => \App\Models\Event::where('date', '>=', today())->orderBy('date')->limit(3)->get(),
                'pendingRegistrations' => 0,
                'pendingEnrollments' => 0,
                'attendanceLabels' => collect(range(0, 6))->map(function($i) {
                    return \Carbon\Carbon::parse(now()->subDays($i))->format('D');
                })->reverse()->values(),
                'presentData' => collect([0, 0, 0, 0, 0, 0, 0]),
                'absentData' => collect([0, 0, 0, 0, 0, 0, 0]),
            ]);
        }

        // School-wide counts filtered by active school year
        $totalStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)->count();
        $totalTeachers = \App\Models\Teacher::count();
        $totalSections = \App\Models\Section::where('school_year_id', $activeSchoolYear->id)->count();

        $maleStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->where('gender', 'Male')
            ->count();
        $femaleStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->where('gender', 'Female')
            ->count();

        $activeTeachers = \App\Models\Teacher::where('status', 'active')->count();
        $teachersOnLeave = \App\Models\Teacher::where('status', 'on_leave')->count();

        // Today's attendance
        $today = now()->format('Y-m-d');
        $presentToday = \App\Models\Attendance::whereDate('date', $today)
            ->whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('status', 'present')
            ->count();

        $absentToday = \App\Models\Attendance::whereDate('date', $today)
            ->whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('status', 'absent')
            ->count();

        $lateToday = \App\Models\Attendance::whereDate('date', $today)
            ->whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('status', 'late')
            ->count();

        // Grade overview
        $avgGrade = \App\Models\Grade::whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('component_type', 'final_grade')
            ->avg('final_grade') ?? 0;

        $above90 = \App\Models\Grade::whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('component_type', 'final_grade')
            ->where('final_grade', '>=', 90)
            ->count();

        $below75 = \App\Models\Grade::whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->where('component_type', 'final_grade')
            ->where('final_grade', '<', 75)
            ->count();

        // Grade distribution by grade level
        $gradeDistribution = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->join('grade_levels', 'students.grade_level_id', '=', 'grade_levels.id')
            ->selectRaw('grade_levels.name as grade, count(*) as count')
            ->groupBy('grade_levels.name')
            ->orderBy('grade_levels.name')
            ->pluck('count', 'grade')
            ->toArray();

        // Recent enrollments
        $recentStudents = \App\Models\Student::with('section')
            ->where('school_year_id', $activeSchoolYear->id)
            ->latest()
            ->limit(5)
            ->get();

        // Upcoming events
        $upcomingEvents = \App\Models\Event::where('date', '>=', today())
            ->orderBy('date')
            ->limit(3)
            ->get();

        // Pending registrations
        $pendingRegistrations = \App\Models\Enrollment::where('school_year_id', $activeSchoolYear->id)
            ->where('status', 'pending')
            ->count();

        // Online enrollment applications
        $pendingEnrollments = \App\Models\EnrollmentApplication::where('school_year_id', $activeSchoolYear->id)
            ->where('application_type', 'continuing')
            ->where('status', 'pending')
            ->count();

        // Attendance chart data (last 7 days)
        $last7Days = collect(range(0, 6))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values();

        $attendanceLabels = $last7Days->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('D');
        });

        $presentData = $last7Days->map(function($date) use ($activeSchoolYear) {
            return \App\Models\Attendance::whereDate('date', $date)
                ->whereHas('student', function($q) use ($activeSchoolYear) {
                    $q->where('school_year_id', $activeSchoolYear->id);
                })
                ->where('status', 'present')
                ->count();
        });

        $absentData = $last7Days->map(function($date) use ($activeSchoolYear) {
            return \App\Models\Attendance::whereDate('date', $date)
                ->whereHas('student', function($q) use ($activeSchoolYear) {
                    $q->where('school_year_id', $activeSchoolYear->id);
                })
                ->where('status', 'absent')
                ->count();
        });

        return view('principal.dashboard', compact(
            'activeSchoolYear',
            'totalStudents',
            'totalTeachers',
            'totalSections',
            'maleStudents',
            'femaleStudents',
            'activeTeachers',
            'teachersOnLeave',
            'presentToday',
            'absentToday',
            'lateToday',
            'avgGrade',
            'above90',
            'below75',
            'gradeDistribution',
            'recentStudents',
            'upcomingEvents',
            'pendingRegistrations',
            'pendingEnrollments',
            'attendanceLabels',
            'presentData',
            'absentData'
        ));
    }
}
