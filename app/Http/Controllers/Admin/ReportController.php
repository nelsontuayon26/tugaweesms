<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Grade;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        
        // CRITICAL: Get selected school year or use active year as default
        $selectedYearId = $request->get('school_year');
        
        if ($selectedYearId) {
            $selectedSchoolYear = SchoolYear::find($selectedYearId);
        } else {
            $selectedSchoolYear = SchoolYear::where('is_active', true)->first();
        }
        
        // If no year found, create a fallback
        if (!$selectedSchoolYear) {
            $selectedSchoolYear = SchoolYear::first() ?? (object)[
                'id' => null,
                'name' => 'All Time',
                'start_date' => now()->subYears(10),
                'end_date' => now()
            ];
        }
        
        $dateRange = $this->getDateRange($period);
        
        // Get all school years for dropdown
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        
        $cacheKey = "reports_{$period}_{$selectedSchoolYear->id}";
        
        $reportData = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($dateRange, $period, $selectedSchoolYear) {
            return $this->compileReportData($dateRange, $period, $selectedSchoolYear);
        });
        
        $reportData['period'] = $period;
        $reportData['schoolYears'] = $schoolYears;
        $reportData['selectedSchoolYear'] = $selectedSchoolYear;
        
        return view('admin.reports.index', $reportData);
    }

    private function getDateRange($period)
    {
        $now = now();
        
        switch ($period) {
            case 'today':
                return [
                    'start' => $now->copy()->startOfDay(),
                    'end' => $now->copy()->endOfDay(),
                    'previous_start' => $now->copy()->subDay()->startOfDay(),
                    'previous_end' => $now->copy()->subDay()->endOfDay()
                ];
            case 'week':
                return [
                    'start' => $now->copy()->startOfWeek(),
                    'end' => $now->copy()->endOfWeek(),
                    'previous_start' => $now->copy()->subWeek()->startOfWeek(),
                    'previous_end' => $now->copy()->subWeek()->endOfWeek()
                ];
            case 'month':
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'previous_start' => $now->copy()->subMonth()->startOfMonth(),
                    'previous_end' => $now->copy()->subMonth()->endOfMonth()
                ];
            case 'quarter':
                return [
                    'start' => $now->copy()->startOfQuarter(),
                    'end' => $now->copy()->endOfQuarter(),
                    'previous_start' => $now->copy()->subQuarter()->startOfQuarter(),
                    'previous_end' => $now->copy()->subQuarter()->endOfQuarter()
                ];
            case 'year':
                return [
                    'start' => $now->copy()->startOfYear(),
                    'end' => $now->copy()->endOfYear(),
                    'previous_start' => $now->copy()->subYear()->startOfYear(),
                    'previous_end' => $now->copy()->subYear()->endOfYear()
                ];
            default:
                return [
                    'start' => $now->copy()->startOfMonth(),
                    'end' => $now->copy()->endOfMonth(),
                    'previous_start' => $now->copy()->subMonth()->startOfMonth(),
                    'previous_end' => $now->copy()->subMonth()->endOfMonth()
                ];
        }
    }

    /**
     * CRITICAL: All queries now filter by school_year_id
     */
    private function compileReportData($dateRange, $period, $schoolYear)
    {
        $yearId = $schoolYear->id;
        
        // Base query scopes for school year filtering
        $studentQuery = Student::when($yearId, function($q) use ($yearId) {
            return $q->where('school_year_id', $yearId);
        });
        
        $teacherQuery = Teacher::when($yearId, function($q) use ($yearId) {
            return $q->where('school_year_id', $yearId);
        });
        
        $sectionQuery = Section::when($yearId, function($q) use ($yearId) {
            return $q->where('school_year_id', $yearId);
        });
        
        $gradeQuery = Grade::when($yearId, function($q) use ($yearId) {
            return $q->whereHas('student', function($sq) use ($yearId) {
                $sq->where('school_year_id', $yearId);
            });
        });

        // Get counts filtered by school year
        $totalStudents = (clone $studentQuery)->count();
        $totalTeachers = (clone $teacherQuery)->count();
        $totalSections = (clone $sectionQuery)->count();
        $totalUsers = User::count(); // Users are not year-specific
        $totalSubjects = Subject::count(); // Subjects are not year-specific
        $pendingRegistrations = (clone $studentQuery)->where('status', 'pending')->count();
        
        // Growth calculations (also filtered by year)
        $studentGrowth = $this->calculateGrowth(
            (clone $studentQuery)->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            (clone $studentQuery)->whereBetween('created_at', [$dateRange['previous_start'], $dateRange['previous_end']])->count()
        );
        
        $teacherGrowth = $this->calculateGrowth(
            (clone $teacherQuery)->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])->count(),
            (clone $teacherQuery)->whereBetween('created_at', [$dateRange['previous_start'], $dateRange['previous_end']])->count()
        );
        
        $averageStudentsPerSection = $totalSections > 0 ? round($totalStudents / $totalSections, 1) : 0;
        
        // Gender distribution (filtered by year)
        $maleCount = (clone $studentQuery)->whereHas('user', function ($q) { 
            $q->where('gender', 'male'); 
        })->count();
        
        $femaleCount = (clone $studentQuery)->whereHas('user', function ($q) { 
            $q->where('gender', 'female'); 
        })->count();
        
        $totalGender = $maleCount + $femaleCount;
        $malePercentage = $totalGender > 0 ? round(($maleCount / $totalGender) * 100, 1) : 0;
        $femalePercentage = $totalGender > 0 ? round(($femaleCount / $totalGender) * 100, 1) : 0;
        
        $enrollmentData = $this->getEnrollmentTrend($period, $yearId);
        $gradeDistribution = $this->getGradeDistribution($yearId);
        $sectionPerformance = $this->getSectionPerformance($yearId);
        $recentActivities = $this->getRecentActivity($yearId);
        $passingRate = $this->calculatePassingRate($yearId);
        
        return [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalSections' => $totalSections,
            'totalUsers' => $totalUsers,
            'totalSubjects' => $totalSubjects,
            'pendingRegistrations' => $pendingRegistrations,
            'studentGrowth' => $studentGrowth,
            'teacherGrowth' => $teacherGrowth,
            'averageStudentsPerSection' => $averageStudentsPerSection,
            'averageClassSize' => $averageStudentsPerSection,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'malePercentage' => $malePercentage,
            'femalePercentage' => $femalePercentage,
            'activeSchoolYear' => $schoolYear->name,
            'enrollmentLabels' => $enrollmentData['labels'],
            'enrollmentData' => $enrollmentData['data'],
            'gradeLevels' => $gradeDistribution['levels'],
            'gradeDistribution' => $gradeDistribution['counts'],
            'sectionNames' => $sectionPerformance['names'],
            'sectionAverages' => $sectionPerformance['averages'],
            'topSections' => $sectionPerformance['top_sections'],
            'recentActivities' => $recentActivities,
            'passingRate' => $passingRate,
            'attendanceRate' => 95,
        ];
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get enrollment trend filtered by school year
     */
    private function getEnrollmentTrend($period, $yearId = null)
    {
        $labels = [];
        $data = [];
        
        $baseQuery = Student::when($yearId, function($q) use ($yearId) {
            return $q->where('school_year_id', $yearId);
        });
        
        if ($period == 'today') {
            for ($i = 0; $i < 24; $i += 4) {
                $labels[] = sprintf('%02d:00', $i);
                $data[] = (clone $baseQuery)
                    ->whereDate('created_at', today())
                    ->whereRaw('HOUR(created_at) >= ? AND HOUR(created_at) < ?', [$i, $i + 4])
                    ->count();
            }
        } elseif ($period == 'week') {
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $startOfWeek = now()->startOfWeek();
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i);
                $labels[] = $days[$i];
                $data[] = (clone $baseQuery)->whereDate('created_at', $date)->count();
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $labels[] = $month->format('M');
                $data[] = (clone $baseQuery)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count();
            }
        }
        
        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Get grade distribution filtered by school year
     */
    private function getGradeDistribution($yearId = null)
    {
        $query = Student::select('grade_level_id', DB::raw('count(*) as count'))
            ->groupBy('grade_level_id')
            ->with('gradeLevel')
            ->when($yearId, function($q) use ($yearId) {
                return $q->where('school_year_id', $yearId);
            })
            ->get();
        
        $levels = [];
        $counts = [];
        
        foreach ($query as $item) {
            $levels[] = $item->gradeLevel ? $item->gradeLevel->name : 'Unknown';
            $counts[] = $item->count;
        }
        
        return ['levels' => $levels, 'counts' => $counts];
    }

    /**
     * Get section performance filtered by school year
     */
    private function getSectionPerformance($yearId = null)
    {
        $query = Section::with(['teacher.user', 'students'])
            ->withAvg('grades', 'final_grade')
            ->when($yearId, function($q) use ($yearId) {
                return $q->where('school_year_id', $yearId);
            })
            ->get();
        
        $names = [];
        $averages = [];
        $topSections = [];
        
        foreach ($query as $section) {
            $names[] = $section->name;
            $avgGrade = $section->grades_avg_final_grade ?? 0;
            $averages[] = round($avgGrade, 1);
            
            $topSections[] = [
                'name' => $section->name,
                'teacher' => $section->teacher && $section->teacher->user ? $section->teacher->user->full_name : 'N/A',
                'students' => $section->students->count(),
                'average' => round($avgGrade, 1)
            ];
        }
        
        usort($topSections, function ($a, $b) {
            return $b['average'] <=> $a['average'];
        });
        
        $topSections = array_slice($topSections, 0, 5);
        
        return ['names' => $names, 'averages' => $averages, 'top_sections' => $topSections];
    }

    /**
     * Get recent activity filtered by school year
     */
    private function getRecentActivity($yearId = null)
    {
        $activities = [];
        
        // Recent students filtered by year
        $recentStudentsQuery = Student::with('user')->latest();
        if ($yearId) {
            $recentStudentsQuery->where('school_year_id', $yearId);
        }
        $recentStudents = $recentStudentsQuery->take(3)->get();
        
        foreach ($recentStudents as $student) {
            $activities[] = [
                'title' => 'New Student Registered',
                'description' => $student->user ? $student->user->full_name : 'Unknown',
                'time' => $student->created_at->diffForHumans(),
                'icon' => 'fa-user-plus',
                'icon_bg' => 'bg-blue-100',
                'icon_color' => 'text-blue-600'
            ];
        }
        
        // Recent grades filtered by year
        $recentGradesQuery = Grade::with(['student.user', 'subject'])->latest();
        if ($yearId) {
            $recentGradesQuery->whereHas('student', function($q) use ($yearId) {
                $q->where('school_year_id', $yearId);
            });
        }
        $recentGrades = $recentGradesQuery->take(3)->get();
        
        foreach ($recentGrades as $grade) {
            $activities[] = [
                'title' => 'Grades Updated',
                'description' => ($grade->student && $grade->student->user ? $grade->student->user->full_name : 'Unknown') . ' - ' . ($grade->subject ? $grade->subject->name : 'Unknown'),
                'time' => $grade->created_at->diffForHumans(),
                'icon' => 'fa-edit',
                'icon_bg' => 'bg-emerald-100',
                'icon_color' => 'text-emerald-600'
            ];
        }
        
        // Recent teachers filtered by year
        $recentTeachersQuery = Teacher::with('user')->latest();
        if ($yearId) {
            $recentTeachersQuery->where('school_year_id', $yearId);
        }
        $recentTeachers = $recentTeachersQuery->take(2)->get();
        
        foreach ($recentTeachers as $teacher) {
            $activities[] = [
                'title' => 'New Teacher Added',
                'description' => $teacher->user ? $teacher->user->full_name : 'Unknown',
                'time' => $teacher->created_at->diffForHumans(),
                'icon' => 'fa-chalkboard-teacher',
                'icon_bg' => 'bg-purple-100',
                'icon_color' => 'text-purple-600'
            ];
        }
        
        usort($activities, function ($a, $b) {
            return strtotime($b['time']) <=> strtotime($a['time']);
        });
        
        return array_slice($activities, 0, 8);
    }

    /**
     * Calculate passing rate filtered by school year
     */
    private function calculatePassingRate($yearId = null)
    {
        $query = Grade::where('component_type', 'final_grade')
            ->when($yearId, function($q) use ($yearId) {
                return $q->whereHas('student', function($sq) use ($yearId) {
                    $sq->where('school_year_id', $yearId);
                });
            });
        
        $totalGrades = (clone $query)->count();
        if ($totalGrades == 0) return 0;
        
        $passingGrades = (clone $query)->where('final_grade', '>=', 75)->count();
        
        return round(($passingGrades / $totalGrades) * 100, 1);
    }

    /**
     * Export reports - also respects school year filter
     */
    public function export(Request $request, $format)
    {
        $allowedFormats = ['pdf', 'excel', 'csv', 'xlsx'];
        if (!in_array(strtolower($format), $allowedFormats)) {
            return response()->json(['error' => 'Invalid format. Allowed: pdf, excel, csv, xlsx'], 400);
        }

        $period = $request->get('period', 'month');
        $selectedYearId = $request->get('school_year');
        
        if ($selectedYearId) {
            $schoolYear = SchoolYear::find($selectedYearId);
        } else {
            $schoolYear = SchoolYear::where('is_active', true)->first();
        }
        
        if (!$schoolYear) {
            $schoolYear = (object)['id' => null, 'name' => 'All Time'];
        }
        
        $dateRange = $this->getDateRange($period);
        $data = $this->compileReportData($dateRange, $period, $schoolYear);
        
        $filename = 'Reports_' . $schoolYear->name . '_' . ucfirst($period) . '_' . now()->format('Y-m-d_H-i-s');

        switch (strtolower($format)) {
            case 'pdf':
                return $this->exportPdf($data, $filename);
            case 'excel':
            case 'xlsx':
                return $this->exportExcel($data, $filename);
            case 'csv':
            default:
                return $this->exportCsv($data, $filename);
        }
    }

    // Export methods remain the same...
    private function exportPdf($data, $filename)
    {
        if (!class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
            $html = view('admin.reports.export_pdf', $data)->render();
            return response($html, 200, [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="' . $filename . '.html"',
            ]);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.reports.export_pdf', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->download($filename . '.pdf');
    }

    private function exportExcel($data, $filename)
    {
        $exportData = $this->prepareExportArray($data);
        
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.xlsx"',
            'Cache-Control' => 'no-cache, must-revalidate',
        ];

        $callback = function() use ($exportData) {
            $file = fopen('php://output', 'w');
            fwrite($file, '<?xml version="1.0" encoding="UTF-8"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
<Worksheet ss:Name="Reports"><Table>');
            
            if (!empty($exportData)) {
                fwrite($file, '<Row>');
                foreach (array_keys($exportData[0]) as $header) {
                    fwrite($file, '<Cell><Data ss:Type="String">' . htmlspecialchars($header) . '</Data></Cell>');
                }
                fwrite($file, '</Row>');
            }
            
            foreach ($exportData as $row) {
                fwrite($file, '<Row>');
                foreach ($row as $value) {
                    $type = is_numeric($value) ? 'Number' : 'String';
                    fwrite($file, '<Cell><Data ss:Type="' . $type . '">' . htmlspecialchars($value) . '</Data></Cell>');
                }
                fwrite($file, '</Row>');
            }
            
            fwrite($file, '</Table></Worksheet></Workbook>');
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function exportCsv($data, $filename, $extension = 'csv')
    {
        $exportData = $this->prepareExportArray($data);
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.' . $extension . '"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache'
        ];
        
        $callback = function() use ($exportData) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            if (!empty($exportData)) {
                fputcsv($file, array_keys($exportData[0]));
            }
            
            foreach ($exportData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function prepareExportArray($data)
    {
        $exportArray = [];

        $exportArray[] = [
            'Category' => 'Summary',
            'Metric' => 'Total Students',
            'Value' => $data['totalStudents'],
            'Percentage' => '',
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];
        
        $exportArray[] = [
            'Category' => 'Summary',
            'Metric' => 'Total Teachers',
            'Value' => $data['totalTeachers'],
            'Percentage' => '',
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];
        
        $exportArray[] = [
            'Category' => 'Summary',
            'Metric' => 'Total Sections',
            'Value' => $data['totalSections'],
            'Percentage' => '',
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];
        
        $exportArray[] = [
            'Category' => 'Summary',
            'Metric' => 'Pending Registrations',
            'Value' => $data['pendingRegistrations'],
            'Percentage' => '',
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];
        
        $exportArray[] = [
            'Category' => 'Summary',
            'Metric' => 'Passing Rate',
            'Value' => $data['passingRate'] . '%',
            'Percentage' => $data['passingRate'],
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];

        $exportArray[] = [
            'Category' => 'Demographics',
            'Metric' => 'Male Students',
            'Value' => $data['maleCount'],
            'Percentage' => $data['malePercentage'],
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];
        
        $exportArray[] = [
            'Category' => 'Demographics',
            'Metric' => 'Female Students',
            'Value' => $data['femaleCount'],
            'Percentage' => $data['femalePercentage'],
            'Period' => $data['period'] ?? 'month',
            'School Year' => $data['activeSchoolYear'] ?? 'N/A'
        ];

        if (!empty($data['gradeLevels'])) {
            foreach ($data['gradeLevels'] as $index => $level) {
                $exportArray[] = [
                    'Category' => 'Grade Distribution',
                    'Metric' => $level,
                    'Value' => $data['gradeDistribution'][$index] ?? 0,
                    'Percentage' => '',
                    'Period' => $data['period'] ?? 'month',
                    'School Year' => $data['activeSchoolYear'] ?? 'N/A'
                ];
            }
        }

        if (!empty($data['topSections'])) {
            foreach ($data['topSections'] as $section) {
                $exportArray[] = [
                    'Category' => 'Top Sections',
                    'Metric' => $section['name'],
                    'Value' => $section['average'] . '%',
                    'Percentage' => $section['average'],
                    'Period' => $data['period'] ?? 'month',
                    'School Year' => $data['activeSchoolYear'] ?? 'N/A'
                ];
            }
        }

        return $exportArray;
    }
}