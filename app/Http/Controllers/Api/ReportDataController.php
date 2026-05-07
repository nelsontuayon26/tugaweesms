<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportDataController extends Controller
{
    /**
     * Get dashboard chart data
     */
    public function dashboardCharts()
    {
        return response()->json([
            'enrollment_trend' => $this->getEnrollmentTrendData(),
            'attendance_trend' => $this->getAttendanceTrendData(),
            'grade_distribution' => $this->getGradeDistributionData(),
            'gender_distribution' => $this->getGenderDistributionData(),
        ]);
    }

    /**
     * Get enrollment trend data
     */
    protected function getEnrollmentTrendData(): array
    {
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->format('M Y');
            
            $count = DB::table('enrollments')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'New Enrollments',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                    'fill' => true,
                ]
            ]
        ];
    }

    /**
     * Get attendance trend data
     */
    protected function getAttendanceTrendData(): array
    {
        $labels = [];
        $present = [];
        $absent = [];
        $late = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('M d');

            $present[] = Attendance::whereDate('date', $date)->where('status', 'present')->count();
            $absent[] = Attendance::whereDate('date', $date)->where('status', 'absent')->count();
            $late[] = Attendance::whereDate('date', $date)->where('status', 'late')->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Present',
                    'data' => $present,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.8)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Late',
                    'data' => $late,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.8)',
                    'borderColor' => 'rgb(245, 158, 11)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Absent',
                    'data' => $absent,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.8)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'borderWidth' => 1,
                ],
            ]
        ];
    }

    /**
     * Get grade distribution data
     */
    protected function getGradeDistributionData(): array
    {
        $ranges = [
            '90-100' => [90, 100],
            '85-89' => [85, 89.99],
            '80-84' => [80, 84.99],
            '75-79' => [75, 74.99],
            'Below 75' => [0, 74.99],
        ];

        $labels = array_keys($ranges);
        $data = [];
        $colors = [
            'rgba(34, 197, 94, 0.8)',   // Green
            'rgba(59, 130, 246, 0.8)',  // Blue
            'rgba(245, 158, 11, 0.8)',  // Amber
            'rgba(249, 115, 22, 0.8)',  // Orange
            'rgba(239, 68, 68, 0.8)',   // Red
        ];

        foreach ($ranges as $label => $range) {
            $count = Grade::where('component_type', 'final_grade')
                ->whereBetween('final_grade', $range)
                ->count();
            $data[] = $count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ]
            ]
        ];
    }

    /**
     * Get gender distribution data
     */
    protected function getGenderDistributionData(): array
    {
        $male = Student::where('gender', 'male')->count();
        $female = Student::where('gender', 'female')->count();

        return [
            'labels' => ['Male', 'Female'],
            'datasets' => [
                [
                    'data' => [$male, $female],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(236, 72, 153, 0.8)',
                    ],
                    'borderWidth' => 0,
                ]
            ]
        ];
    }

    /**
     * Get real-time statistics
     */
    public function realtimeStats()
    {
        $currentSchoolYear = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $currentSchoolYear?->id;

        // Today's attendance
        $today = now()->toDateString();
        $todayTotal = Attendance::whereDate('date', $today)->distinct('student_id')->count('student_id');
        $todayPresent = $todayTotal > 0 ? Attendance::whereDate('date', $today)->where('status', 'present')->distinct('student_id')->count('student_id') : 0;
        $todayRate = $todayTotal > 0 ? round(($todayPresent / $todayTotal) * 100, 1) : 0;

        return response()->json([
            'students' => [
                'total' => $schoolYearId
                    ? DB::table('enrollments')->where('school_year_id', $schoolYearId)->where('status', 'enrolled')->count()
                    : Student::where('status', 'active')->count(),
                'active' => $schoolYearId ? DB::table('enrollments')->where('school_year_id', $schoolYearId)->where('status', 'enrolled')->count() : 0,
                'new_today' => Student::whereDate('created_at', $today)->count(),
            ],
            'teachers' => [
                'total' => DB::table('teachers')->count(),
                'active' => DB::table('teachers')->where('employment_status', 'active')->count(),
            ],
            'sections' => [
                'total' => Section::where('is_active', true)->count(),
                'active' => $schoolYearId ? Section::where('school_year_id', $schoolYearId)->where('is_active', true)->count() : 0,
            ],
            'attendance_today' => [
                'total' => $todayTotal,
                'present' => $todayPresent,
                'rate' => $todayRate,
                'trend' => $this->getAttendanceTrendDirection(),
            ],
            'grades' => [
                'average' => round(Grade::where('component_type', 'final_grade')->avg('final_grade'), 2),
                'passing_rate' => $this->calculatePassingRate(),
            ],
        ]);
    }

    /**
     * Get attendance trend direction
     */
    protected function getAttendanceTrendDirection(): string
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $todayRate = $this->getAttendanceRateForDate($today);
        $yesterdayRate = $this->getAttendanceRateForDate($yesterday);

        if ($todayRate > $yesterdayRate) return 'up';
        if ($todayRate < $yesterdayRate) return 'down';
        return 'stable';
    }

    /**
     * Get attendance rate for a specific date
     */
    protected function getAttendanceRateForDate(string $date): float
    {
        $total = Attendance::whereDate('date', $date)->distinct('student_id')->count('student_id');
        $present = $total > 0 ? Attendance::whereDate('date', $date)->where('status', 'present')->distinct('student_id')->count('student_id') : 0;
        
        return $total > 0 ? ($present / $total) * 100 : 0;
    }

    /**
     * Calculate overall passing rate
     */
    protected function calculatePassingRate(): float
    {
        $total = Grade::where('component_type', 'final_grade')->count();
        if ($total === 0) return 0;

        $passed = Grade::where('component_type', 'final_grade')->where('final_grade', '>=', 75)->count();
        
        return round(($passed / $total) * 100, 2);
    }

    /**
     * Get filter options for report builder
     */
    public function filterOptions()
    {
        $currentSchoolYear = SchoolYear::where('is_active', true)->first();

        return response()->json([
            'school_years' => SchoolYear::orderBy('start_date', 'desc')->get(['id', 'name']),
            'grade_levels' => DB::table('grade_levels')->orderBy('order')->get(['id', 'name']),
            'sections' => Section::with(['gradeLevel:id,name'])
                ->when($currentSchoolYear, fn($q) => $q->where('school_year_id', $currentSchoolYear->id))
                ->orderBy('name')
                ->get(['id', 'name', 'grade_level_id']),
            'subjects' => DB::table('subjects')->orderBy('name')->get(['id', 'name']),
            'teachers' => DB::table('teachers')
                ->join('users', 'teachers.user_id', '=', 'users.id')
                ->select('teachers.id', DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"))
                ->orderBy('users.last_name')
                ->get(),
            'genders' => [
                ['value' => 'male', 'label' => 'Male'],
                ['value' => 'female', 'label' => 'Female'],
            ],
            'statuses' => [
                ['value' => 'active', 'label' => 'Active'],
                ['value' => 'inactive', 'label' => 'Inactive'],
                ['value' => 'transferred', 'label' => 'Transferred'],
                ['value' => 'dropped', 'label' => 'Dropped'],
            ],
        ]);
    }

    /**
     * Get preview data for report builder
     */
    public function preview(Request $request)
    {
        $template = $request->get('template');
        $parameters = $request->get('parameters', []);
        $limit = $request->get('limit', 10);

        // Quick preview - return limited data
        switch ($template) {
            case 'student-masterlist':
                $query = Student::with(['user', 'gradeLevel', 'section']);
                
                if (!empty($parameters['grade_level_id'])) {
                    $query->where('grade_level_id', $parameters['grade_level_id']);
                }
                
                $data = $query->limit($limit)->get()->map(fn($s) => [
                    'lrn' => $s->lrn,
                    'name' => $s->full_name,
                    'grade' => $s->gradeLevel?->name,
                    'section' => $s->section?->name,
                ]);
                break;

            case 'grade-summary':
                $data = Grade::with(['student.user', 'subject'])
                    ->where('component_type', 'final_grade')
                    ->limit($limit)
                    ->get()
                    ->map(fn($g) => [
                        'student' => $g->student?->full_name,
                        'subject' => $g->subject?->name,
                        'final' => $g->final_grade,
                    ]);
                break;

            default:
                $data = [];
        }

        return response()->json([
            'success' => true,
            'preview_data' => $data,
            'total_records' => $data->count(),
        ]);
    }
}
