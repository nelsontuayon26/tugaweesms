<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    /**
     * Display the student's attendance dashboard
     */
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        
        if (!$student) {
            abort(403, 'Student record not found');
        }

        // Get current month/year or from request
        $currentDate = Carbon::now();
        $month = $request->get('month', $currentDate->month);
        $year = $request->get('year', $currentDate->year);
        
        $selectedDate = Carbon::createFromDate($year, $month, 1);
        
        // Get attendance data for the selected month
        $attendances = $this->getMonthlyAttendance($student->id, $year, $month);
        
        // Calculate statistics
        $stats = $this->calculateMonthlyStats($student->id, $year, $month);
        
        // Get school year info
        $schoolYear = $this->getCurrentSchoolYear();
        
        // Get section info
        $section = $student->section;
        $gradeLevel = $section->gradeLevel ?? null;
        
        // Get recent attendance (last 30 days)
        $recentAttendance = $this->getRecentAttendance($student->id);
        
        // Calculate attendance rate
        $attendanceRate = $this->calculateAttendanceRate($student->id);
        
        return view('student.attendance.index', compact(
            'student',
            'attendances',
            'stats',
            'selectedDate',
            'schoolYear',
            'section',
            'gradeLevel',
            'recentAttendance',
            'attendanceRate',
            'month',
            'year'
        ));
    }

    /**
     * Get attendance data for a specific month
     */
    private function getMonthlyAttendance($studentId, $year, $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'asc')
            ->get()
            ->keyBy(function ($item) {
                return Carbon::parse($item->date)->format('Y-m-d');
            });
    }

    /**
     * Calculate monthly attendance statistics
     */
    private function calculateMonthlyStats($studentId, $year, $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $attendances = Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $schoolDays = $this->countSchoolDays($studentId, $year, $month);

        return [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'excused' => $attendances->where('status', 'excused')->count(),
            'total_school_days' => $schoolDays,
            'attended_days' => $attendances->whereIn('status', ['present', 'late'])->count(),
        ];
    }

    /**
     * Count school days based on actual attendance records.
     * Each unique date with an attendance entry counts as 1 school day.
     */
    private function countSchoolDays($studentId, $year, $month)
    {
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        return Attendance::where('student_id', $studentId)
            ->whereBetween('date', [$startDate, $endDate])
            ->distinct('date')
            ->count('date');
    }

    /**
     * Get recent attendance records (last 30 days)
     */
    private function getRecentAttendance($studentId)
    {
        return Attendance::where('student_id', $studentId)
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Calculate overall attendance rate
     */
    private function calculateAttendanceRate($studentId)
    {
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($currentYear, 6, 1); // School year starts June
        
        if (Carbon::now()->month < 6) {
            $startDate->subYear();
        }

        $attendances = Attendance::where('student_id', $studentId)
            ->where('date', '>=', $startDate)
            ->get();

        $totalDays = $attendances->count();
        $presentDays = $attendances->whereIn('status', ['present', 'late'])->count();

        return $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 1) : 0;
    }

    /**
     * Get current school year
     */
    private function getCurrentSchoolYear()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        if ($currentMonth >= 6) {
            return $currentYear . '-' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '-' . $currentYear;
        }
    }

    /**
     * Get attendance for a specific date (AJAX)
     */
    public function getDailyAttendance(Request $request)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $student = Auth::user()->student;
        $date = Carbon::parse($request->date);

        $attendance = Attendance::where('student_id', $student->id)
            ->whereDate('date', $date)
            ->first();

        return response()->json([
            'date' => $date->format('F d, Y'),
            'status' => $attendance ? $attendance->status : 'no_record',
            'remarks' => $attendance ? $attendance->remarks : null,
            'time_in' => $attendance ? $attendance->time_in : null,
            'time_out' => $attendance ? $attendance->time_out : null,
        ]);
    }

    /**
     * Export attendance report (PDF/Excel)
     */
    public function export(Request $request)
    {
        $student = Auth::user()->student;
        $format = $request->get('format', 'pdf');
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        $attendances = $this->getMonthlyAttendance($student->id, $year, $month);
        $stats = $this->calculateMonthlyStats($student->id, $year, $month);

        if ($format === 'pdf') {
            // Implement PDF export logic
            // return PDF::loadView('student.attendance.pdf', compact(...))->download('attendance.pdf');
        }

        // Implement Excel export logic
        // return Excel::download(new AttendanceExport($attendances), 'attendance.xlsx');
        
        return back()->with('message', 'Export functionality coming soon');
    }
}
