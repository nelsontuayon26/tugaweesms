<?php

namespace App\View\Components\Teacher;

use App\Models\Attendance;
use App\Models\SchoolYear;
use Illuminate\View\Component;

class AttendanceWidget extends Component
{
    public $section;
    public $today;
    public $stats;
    public $attendanceData;
    public $unmarkedStudents;

    public function __construct($section)
    {
        $this->section = $section;
        $this->today = now()->format('Y-m-d');
        
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        // Get students in section
        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with('user')
            ->get();

        // Get today's attendance records
        $attendanceRecords = Attendance::where('section_id', $section->id)
            ->whereDate('date', $this->today)
            ->where('school_year_id', $activeSchoolYear?->id)
            ->get()
            ->keyBy('student_id');

        // Calculate stats
        $present = $attendanceRecords->where('status', 'present')->count();
        $absent = $attendanceRecords->where('status', 'absent')->count();
        $late = $attendanceRecords->where('status', 'late')->count();
        $marked = $attendanceRecords->count();
        $total = $students->count();
        $unmarked = $total - $marked;

        $this->stats = [
            'total' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'unmarked' => $unmarked,
            'marked' => $marked,
            'present_pct' => $total > 0 ? round(($present / $total) * 100) : 0,
        ];

        // Prepare attendance data for each student
        $this->attendanceData = $students->map(function($student) use ($attendanceRecords) {
            $attendance = $attendanceRecords->get($student->id);
            return [
                'student' => $student,
                'status' => $attendance?->status,
                'marked_at' => $attendance?->created_at,
            ];
        });

        $this->unmarkedStudents = $this->attendanceData->whereNull('status');
    }

    public function render()
    {
        return view('components.teacher.attendance-widget');
    }
}
