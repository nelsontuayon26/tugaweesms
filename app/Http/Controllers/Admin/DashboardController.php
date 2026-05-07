<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Section;
use App\Models\SchoolYear;

class DashboardController extends Controller
{
    /**
     * Load the dashboard view with counts filtered by active school year
     */
    public function index()
    {
        // Get the active school year - EXACTLY like your report page
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        // If no active school year, handle gracefully
        if (!$activeSchoolYear) {
            return redirect()->back()->with('error', 'No active school year set. Please configure an active school year first.');
        }

        // Filter ALL counts by active school year
        $totalStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)->count();
        
        $totalTeachers = \App\Models\User::whereHas('role', function($q) {
                $q->where('name', 'teacher');
            })
            ->whereHas('teacher.sections', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->count();

        $totalSections = \App\Models\Section::where('school_year_id', $activeSchoolYear->id)->count();
        $sidebarSectionCount = $totalSections;

        // Additional filtered data for the dashboard
        $maleStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->where('gender', 'Male')
            ->count();
            
        $femaleStudents = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->where('gender', 'Female')
            ->count();

        $activeTeachers = \App\Models\Teacher::where('status', 'active')
            ->whereHas('sections', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->count();

        $teachersOnLeave = \App\Models\Teacher::where('status', 'on_leave')
            ->whereHas('sections', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->count();

        // Today's attendance filtered by active school year
        $today = now()->format('Y-m-d');
        $todayAttendance = \App\Models\Attendance::query()
            ->selectRaw('status, COUNT(*) as count')
            ->whereDate('date', $today)
            ->whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->whereIn('status', ['present', 'absent', 'late'])
            ->groupBy('status')
            ->pluck('count', 'status');

        $presentToday = $todayAttendance->get('present', 0);
        $absentToday = $todayAttendance->get('absent', 0);
        $lateToday = $todayAttendance->get('late', 0);

        // Grades filtered by active school year
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

        // Grade distribution by grade level for active school year
        $gradeDistribution = \App\Models\Student::where('school_year_id', $activeSchoolYear->id)
            ->join('grade_levels', 'students.grade_level_id', '=', 'grade_levels.id')
            ->selectRaw('grade_levels.name as grade, count(*) as count')
            ->groupBy('grade_levels.name')
            ->orderBy('grade_levels.name')
            ->pluck('count', 'grade')
            ->toArray();

        // Recent enrollments for active school year
        $recentStudents = \App\Models\Student::with('section')
            ->where('school_year_id', $activeSchoolYear->id)
            ->latest()
            ->limit(5)
            ->get();

        // Upcoming events - show all future events regardless of school year
        $upcomingEvents = \App\Models\Event::where('date', '>=', today())
            ->orderBy('date')
            ->limit(3)
            ->get();

        // Attendance chart data (last 7 days) filtered by active school year
        $last7Days = collect(range(0, 6))->map(function($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values();

        $attendanceLabels = $last7Days->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('D');
        });

        // Batch attendance counts into a single grouped query
        $attendanceRaw = \App\Models\Attendance::query()
            ->selectRaw('DATE(date) as date_only, status, COUNT(*) as count')
            ->whereDate('date', '>=', $last7Days->first())
            ->whereDate('date', '<=', $last7Days->last())
            ->whereHas('student', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id);
            })
            ->whereIn('status', ['present', 'absent'])
            ->groupBy('date_only', 'status')
            ->get()
            ->keyBy(fn($row) => $row->date_only . '|' . $row->status);

        $presentData = $last7Days->map(function($date) use ($attendanceRaw) {
            return $attendanceRaw->get($date . '|present')?->count ?? 0;
        });

        $absentData = $last7Days->map(function($date) use ($attendanceRaw) {
            return $attendanceRaw->get($date . '|absent')?->count ?? 0;
        });

        return view('admin.dashboard', compact(
            'activeSchoolYear',
            'totalStudents',
            'totalTeachers',
            'totalSections',
            'sidebarSectionCount',
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
            'attendanceLabels',
            'presentData',
            'absentData'
        ));
    }
}