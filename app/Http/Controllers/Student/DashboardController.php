<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Schedule;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student || $student->status !== 'active') {
            Auth::logout();
            return redirect()->route('auth.pending')
                ->withErrors([
                    'login' => 'Your registration is pending admin approval.'
                ]);
        }

        $section = $student->section;
        $teacher = $section?->teacher;

        $classmates = $section
            ? $section->students()->where('id', '!=', $student->id)->get()
            : collect();

        $attendanceData = $this->getAttendanceData($student->id);
        $gradesData = $this->getGradesData($student->id);
        $assignmentsData = $this->getAssignmentsData($student->id, $student->section_id, $student->grade_level);
        $todaySchedule = $this->getTodaySchedule($student->section_id);
        $notificationsData = $this->getNotificationsData($student->id, $student->grade_level, $user->id);
        $recentActivities = $this->getRecentActivities($student->id);

        $subjectCount = $section ? $section->subjects()->count() : 0;
        $achievementCount = $student->achievements()->count();

        $upcomingEvents = \App\Models\Event::where('date', '>=', today())
            ->orderBy('date')
            ->limit(3)
            ->get();

        $activeSchoolYear = \App\Models\SchoolYear::getActive();
        $announcements = Announcement::visibleToStudent($student)
            ->active()
            ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id))
            ->ordered()
            ->limit(5)
            ->get();

        return view('student.dashboard', array_merge(
            compact(
                'student',
                'section',
                'teacher',
                'classmates',
                'todaySchedule',
                'recentActivities',
                'subjectCount',
                'achievementCount',
                'upcomingEvents',
                'announcements'
            ),
            $attendanceData,
            $gradesData,
            $assignmentsData,
            $notificationsData
        ));
    }

    public function idCard()
    {
        $student = Auth::user()->student;
        if (!$student) {
            abort(404);
        }
        return view('student.id-card', compact('student'));
    }

    private function getGradesData($studentId)
    {
        $currentQuarter = $this->getCurrentQuarter();

        $grades = Grade::where('student_id', $studentId)
            ->where('quarter', $currentQuarter)
            ->with('subject')
            ->get();

        // ✅ FIXED
        $generalAverageNumeric = $grades->avg('final_grade') ?? 0;
        $generalAverage = $this->convertToLetterGrade($generalAverageNumeric);

        $gradeRank = $this->calculateGradeRank($studentId, $generalAverageNumeric);

        return compact(
            'grades',
            'generalAverage',
            'generalAverageNumeric',
            'gradeRank',
            'currentQuarter'
        );
    }

    private function getCurrentQuarter()
    {
        $month = now()->month;

        if ($month >= 6 && $month <= 8) return 1;
        if ($month >= 9 && $month <= 11) return 2;
        if ($month >= 12 || $month <= 2) return 3;
        return 4;
    }

    private function calculateGradeRank($studentId, $average)
    {
        $student = Auth::user()->student;

        if (!$student->section_id) return null;

        $sectionStudentIds = \App\Models\Student::where('section_id', $student->section_id)
            ->pluck('id')
            ->toArray();

        // ✅ FIXED
        $averages = Grade::whereIn('student_id', $sectionStudentIds)
            ->where('quarter', $this->getCurrentQuarter())
            ->selectRaw('student_id, AVG(final_grade) as average')
            ->groupBy('student_id')
            ->orderByDesc('average')
            ->pluck('average')
            ->values();

        if ($averages->isEmpty()) return null;

        $position = $averages->search(function ($avg) use ($average) {
            return $average >= $avg;
        });

        if ($position === false) {
            $position = $averages->count() - 1;
        }

        $percentile = (($position + 1) / $averages->count()) * 100;

        return round($percentile);
    }

    private function convertToLetterGrade($numericGrade)
    {
        if ($numericGrade >= 98) return 'A+';
        if ($numericGrade >= 95) return 'A';
        if ($numericGrade >= 92) return 'A-';
        if ($numericGrade >= 89) return 'B+';
        if ($numericGrade >= 86) return 'B';
        if ($numericGrade >= 83) return 'B-';
        if ($numericGrade >= 80) return 'C+';
        if ($numericGrade >= 77) return 'C';
        if ($numericGrade >= 75) return 'C-';
        if ($numericGrade >= 70) return 'D';
        return 'F';
    }

    // -----------------------------
// Attendance Data (RESTORED)
// -----------------------------
private function getAttendanceData($studentId)
{
    $currentMonth = now()->month;
    $currentYear = now()->year;

    $totalSchoolDays = Attendance::where('student_id', $studentId)
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->count();

    $presentDays = Attendance::where('student_id', $studentId)
        ->where('status', 'present')
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->count();

    $absentDays = Attendance::where('student_id', $studentId)
        ->where('status', 'absent')
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->count();

    $lateDays = Attendance::where('student_id', $studentId)
        ->where('status', 'late')
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->count();

    $attendanceRate = $totalSchoolDays > 0
        ? ($presentDays / $totalSchoolDays) * 100
        : 0;

    // Build date-keyed attendance map for calendar indicators
    $monthlyAttendance = Attendance::where('student_id', $studentId)
        ->whereYear('date', $currentYear)
        ->whereMonth('date', $currentMonth)
        ->pluck('status', 'date')
        ->toArray();

    return compact(
        'attendanceRate',
        'presentDays',
        'absentDays',
        'lateDays',
        'totalSchoolDays',
        'monthlyAttendance'
    );
}

// -----------------------------
// Assignments Data (RESTORED)
// -----------------------------
private function getAssignmentsData($studentId, $sectionId, $gradeLevel)
{
    $assignments = Assignment::where(function ($query) use ($sectionId, $gradeLevel) {
            $query->where('section_id', $sectionId)
                  ->orWhere('grade_level', $gradeLevel);
        })
        ->get();

    $totalAssignments = $assignments->count();

    $submittedAssignmentIds = Submission::where('student_id', $studentId)
        ->pluck('assignment_id')
        ->toArray();

    $submittedAssignments = count($submittedAssignmentIds);
    $pendingAssignments = $totalAssignments - $submittedAssignments;

    return compact(
        'totalAssignments',
        'submittedAssignments',
        'pendingAssignments'
    );
}

// -----------------------------
// Today Schedule (RESTORED)
// -----------------------------
private function getTodaySchedule($sectionId)
{
    if (!$sectionId) return collect();

    $today = now()->format('l');

    return Schedule::where('section_id', $sectionId)
        ->where('day', $today)
        ->with(['subject', 'teacher'])
        ->orderBy('start_time')
        ->get();
}

// -----------------------------
// Notifications (RESTORED)
// -----------------------------
private function getNotificationsData($studentId, $gradeLevel, $userId)
{
    $unreadMessages = Message::where('recipient_id', $userId)
        ->where('read', false)
        ->count();

    $student = Auth::user()->student;
    $activeSchoolYear = \App\Models\SchoolYear::getActive();

    $unreadAnnouncements = Announcement::visibleToStudent($student)
        ->active()
        ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id))
        ->unreadBy($userId)
        ->count();

    $unreadNotifications = $unreadMessages + $unreadAnnouncements;

    return compact(
        'unreadMessages',
        'unreadAnnouncements',
        'unreadNotifications'
    );
}

// -----------------------------
// Recent Activities (RESTORED)
// -----------------------------
private function getRecentActivities($studentId)
{
    return Activity::where('student_id', $studentId)
        ->latest()
        ->take(5)
        ->get();
}
}