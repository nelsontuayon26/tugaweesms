<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Student;
use App\Models\Grade;
use App\Models\CoreValue;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\SchoolYear;

class ReportCardController extends Controller
{
    public function index(Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $students = $section->students()
            ->whereNotIn('status', ['completed', 'inactive'])
            ->with(['user', 'grades', 'coreValues'])
            ->get();

        // Calculate grade stats for each student
        foreach ($students as $student) {
            $student->average_grade = $this->calculateAverageGrade($student, $section);
            $student->attendance_rate = $this->calculateAttendanceRate($student, $section);
        }

        return view('teacher.report-cards.index', compact('section', 'students'));
    }

    public function generate(Request $request, Section $section, Student $student)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $gradingPeriod = $request->input('grading_period', '1st');
        
        $data = $this->getReportCardData($section, $student, $gradingPeriod);
        
        $pdf = PDF::loadView('teacher.report-cards.template', $data);
        
        $filename = "Report_Card_{$student->user->last_name}_{$student->user->first_name}_{$gradingPeriod}.pdf";
        
        return $pdf->download($filename);
    }

    public function generateBatch(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $gradingPeriod = $request->input('grading_period', '1st');
        $studentIds = $request->input('students', []);

        if (empty($studentIds)) {
            return back()->with('error', 'Please select at least one student.');
        }

        $students = $section->students()
            ->whereIn('id', $studentIds)
            ->with('user')
            ->get();

        $reports = [];
        foreach ($students as $student) {
            $reports[] = $this->getReportCardData($section, $student, $gradingPeriod);
        }

        $pdf = PDF::loadView('teacher.report-cards.batch', compact('reports', 'section', 'gradingPeriod'));
        
        $filename = "Report_Cards_{$section->name}_{$gradingPeriod}_Batch.pdf";
        
        return $pdf->download($filename);
    }

    public function preview(Section $section, Student $student)
    {
        if ($section->teacher_id !== auth()->user()->teacher->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $data = $this->getReportCardData($section, $student, '1st');
        
        return view('teacher.report-cards.preview', $data);
    }

    private function getReportCardData(Section $section, Student $student, $gradingPeriod)
    {
        // Get grades for all subjects
        $grades = Grade::where('student_id', $student->id)
            ->where('grading_period', $gradingPeriod)
            ->with('subject')
            ->get();

        // Get core values
        $coreValues = CoreValue::where('student_id', $student->id)
            ->where('grading_period', $gradingPeriod)
            ->first();

        // Get attendance summary
        $attendanceSummary = $this->getAttendanceSummary($student, $section);

        // Calculate general average
        $generalAverage = $grades->avg('final_grade') ?? 0;

        return [
            'student' => $student,
            'section' => $section,
            'grades' => $grades,
            'coreValues' => $coreValues,
            'attendanceSummary' => $attendanceSummary,
            'generalAverage' => round($generalAverage, 2),
            'gradingPeriod' => $gradingPeriod,
            'schoolYear' => $section->schoolYear,
            'adviser' => $section->teacher,
        ];
    }

    private function calculateAverageGrade($student, $section)
    {
        $average = Grade::where('student_id', $student->id)
            ->avg('final_grade');
        
        return round($average ?? 0, 2);
    }

    private function calculateAttendanceRate($student, $section)
    {
        $total = Attendance::where('student_id', $student->id)
            ->count();
        
        if ($total === 0) return 0;
        
        $present = Attendance::where('student_id', $student->id)
            ->whereIn('status', ['present', 'late'])
            ->count();
        
        return round(($present / $total) * 100, 1);
    }

    private function getAttendanceSummary($student, $section)
    {
        $schoolYearId = $section->school_year_id;
        
        return [
            'days_present' => Attendance::where('student_id', $student->id)
                ->where('status', 'present')
                ->count(),
            'days_absent' => Attendance::where('student_id', $student->id)
                ->where('status', 'absent')
                ->count(),
            'days_late' => Attendance::where('student_id', $student->id)
                ->where('status', 'late')
                ->count(),
            'total_school_days' => $this->getTotalSchoolDays($schoolYearId),
        ];
    }

    private function getTotalSchoolDays($schoolYearId)
    {
        // This is a simplified calculation - you may want to use actual school calendar
        $startDate = Carbon::now()->startOfYear();
        $endDate = Carbon::now();
        
        // Count weekdays (excluding weekends)
        $totalDays = 0;
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            if (!$currentDate->isWeekend()) {
                $totalDays++;
            }
            $currentDate->addDay();
        }
        
        return $totalDays;
    }
}
