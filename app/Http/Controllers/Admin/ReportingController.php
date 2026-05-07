<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReportTemplate;
use App\Models\SavedReport;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Book;
use App\Models\BookInventory;
use App\Models\Subject;
use App\Models\CoreValue;
use App\Models\KindergartenDomain;

use App\Models\StudentHealthRecord;
use App\Models\TeachingProgram;

class ReportingController extends Controller
{
    /**
     * Main reporting dashboard
     */
    public function index()
    {
        $categories = ReportTemplate::getCategories();
        $templates = ReportTemplate::active()->orderBy('name')->get();
        $favorites = SavedReport::favorites()
            ->byUser(auth()->id())
            ->with('template')
            ->limit(5)
            ->get();
        $recentReports = SavedReport::byUser(auth()->id())
            ->recent()
            ->with('template')
            ->limit(10)
            ->get();

        // Real-time stats
        $stats = $this->getDashboardStats();

        return view('admin.reports.index', compact(
            'categories',
            'templates',
            'favorites',
            'recentReports',
            'stats'
        ));
    }

    /**
     * Get real-time dashboard statistics
     */
    protected function getDashboardStats(): array
    {
        $currentSchoolYear = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $currentSchoolYear?->id;

        return [
            'total_students' => $schoolYearId
                ? Enrollment::where('school_year_id', $schoolYearId)->where('status', 'enrolled')->count()
                : Student::where('status', 'active')->count(),
            'total_teachers' => Teacher::count(),
            'total_sections' => Section::where('is_active', true)->count(),
            'active_enrollments' => $schoolYearId ? Enrollment::where('school_year_id', $schoolYearId)->where('status', 'enrolled')->count() : 0,
            'today_attendance' => $this->getTodayAttendanceStats(),
            'grade_averages' => $this->getGradeAverages(),
            'enrollment_trend' => $this->getEnrollmentTrend(),
            'attendance_trend' => $this->getAttendanceTrend(30),
        ];
    }

    /**
     * Get today's attendance statistics
     */
    protected function getTodayAttendanceStats(): array
    {
        $today = now()->toDateString();
        $total = Attendance::whereDate('date', $today)->distinct('student_id')->count('student_id');
        
        if ($total === 0) {
            return [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'total' => 0,
                'rate' => 0,
            ];
        }

        $present = Attendance::whereDate('date', $today)->where('status', 'present')->distinct('student_id')->count('student_id');
        $absent = Attendance::whereDate('date', $today)->where('status', 'absent')->distinct('student_id')->count('student_id');
        $late = Attendance::whereDate('date', $today)->where('status', 'late')->distinct('student_id')->count('student_id');

        return [
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'total' => $total,
            'rate' => round(($present / $total) * 100, 1),
        ];
    }

    /**
     * Get grade averages by subject/level
     */
    protected function getGradeAverages(): array
    {
        // Get average of final grades grouped by student's grade level
        $averages = Grade::select(
            DB::raw('AVG(final_grade) as average'),
            'student_id'
        )
        ->whereNotNull('final_grade')
        ->groupBy('student_id')
        ->with(['student.gradeLevel'])
        ->get()
        ->groupBy('student.gradeLevel.name')
        ->map(function ($grades, $levelName) {
            return round($grades->avg('average'), 2);
        });

        return $averages->toArray();
    }

    /**
     * Get enrollment trend (last 6 months)
     */
    protected function getEnrollmentTrend(): array
    {
        $trend = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $count = Enrollment::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $trend[$month->format('M Y')] = $count;
        }
        return $trend;
    }

    /**
     * Get attendance trend for last N days
     */
    protected function getAttendanceTrend(int $days): array
    {
        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $total = Attendance::whereDate('date', $date)->distinct('student_id')->count('student_id');
            $present = $total > 0 ? Attendance::whereDate('date', $date)->where('status', 'present')->distinct('student_id')->count('student_id') : 0;
            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            $trend[$date] = $rate;
        }
        return $trend;
    }

    /**
     * Show report builder for a template
     */
    public function builder(Request $request, ReportTemplate $template)
    {
        $savedReportId = $request->input('saved_report');
        $savedReport = $savedReportId ? SavedReport::find($savedReportId) : null;

        // Get filter options
        $filterOptions = $this->getFilterOptions($template);

        return view('admin.reports.builder', compact(
            'template',
            'savedReport',
            'filterOptions'
        ));
    }

    /**
     * Get filter options based on template category
     */
    protected function getFilterOptions(ReportTemplate $template): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $students = Student::with('user')->whereNotIn('status', ['completed', 'inactive'])->orderBy('created_at')->get()->map(fn($s) => [
            'id' => $s->id,
            'name' => ($s->user?->last_name ?? '').', '.($s->user?->first_name ?? '').' '.($s->user?->middle_name ?? ''),
            'lrn' => $s->lrn,
            'section_id' => $s->section_id,
        ]);
        $sectionStudents = [];
        if ($activeSchoolYear) {
            $enrollments = Enrollment::where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->get();
            foreach ($enrollments as $enrollment) {
                $sectionStudents[$enrollment->section_id][] = $enrollment->student_id;
            }
        }
        return [
            'school_years' => SchoolYear::orderBy('start_date', 'desc')->get(),
            'grade_levels' => \App\Models\GradeLevel::orderBy('order')->get(),
            'sections' => Section::with(['gradeLevel', 'teacher'])->orderBy('name')->get(),
            'subjects' => \App\Models\Subject::orderBy('name')->get(),
            'teachers' => Teacher::with('user')->get()->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->user?->full_name ?? 'Unknown'
            ]),
            'students' => $students,
            'section_students' => $sectionStudents,
            'genders' => ['male' => 'Male', 'female' => 'Female'],
            'statuses' => ['active' => 'Active', 'inactive' => 'Inactive', 'transferred' => 'Transferred', 'dropped' => 'Dropped'],
        ];
    }

    /**
     * Generate report data
     */
    public function generate(Request $request, ReportTemplate $template)
    {
        $parameters = $request->input('parameters', []);
        $format = $request->input('format', 'html');

        // Generate report data based on template slug
        $data = match($template->slug) {
            'student-masterlist' => $this->generateStudentMasterlist($parameters),
            'grade-summary' => $this->generateGradeSummary($parameters),
            'attendance-summary' => $this->generateAttendanceSummary($parameters),
            'enrollment-statistics' => $this->generateEnrollmentStats($parameters),
            'teacher-workload' => $this->generateTeacherWorkload($parameters),
            'class-performance' => $this->generateClassPerformance($parameters),
            'attendance-trend' => $this->generateAttendanceTrendReport($parameters),
            'honor-roll' => $this->generateHonorRoll($parameters),
            'dropout-risk' => $this->generateDropoutRiskReport($parameters),
            'sf1-school-register' => $this->generateSf1($parameters),
            'sf2-daily-attendance' => $this->generateSf2($parameters),
            'sf3-books' => $this->generateSf3($parameters),
            'sf4-monthly-movement' => $this->generateSf4($parameters),
            'sf5-promotion' => $this->generateSf5($parameters),
            'sf6-summary-promotion' => $this->generateSf6($parameters),
            'sf7-personnel' => $this->generateSf7($parameters),
            'sf8-health-nutrition' => $this->generateSf8($parameters),
            'sf9-report-card' => $this->generateSf9($parameters),
            'sf10-permanent-record' => $this->generateSf10($parameters),
            'kindergarten-assessment' => $this->generateKindergartenAssessment($parameters),
            default => $this->generateGenericReport($template, $parameters),
        };

        // Export based on format
        return match($format) {
            'pdf' => $this->exportPdf($template, $data, $parameters),
            'excel' => $this->exportExcel($template, $data, $parameters),
            'csv' => $this->exportCsv($template, $data, $parameters),
            default => response()->json([
                'success' => true,
                'data' => $data,
                'template' => $template,
                'parameters' => $parameters,
                'generated_at' => now()->toDateTimeString(),
            ]),
        };
    }

    /**
     * Generate Student Masterlist Report
     */
    protected function generateStudentMasterlist(array $parameters): array
    {
        $query = Student::with(['user', 'gradeLevel', 'section', 'enrollments']);

        if (!empty($parameters['grade_level_id'])) {
            $query->where('grade_level_id', $parameters['grade_level_id']);
        }

        if (!empty($parameters['section_id'])) {
            $query->where('section_id', $parameters['section_id']);
        }

        if (!empty($parameters['gender'])) {
            $query->where('gender', $parameters['gender']);
        }

        if (!empty($parameters['status'])) {
            $query->where('status', $parameters['status']);
        }

        $students = $query->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->orderBy('users.last_name')
            ->orderBy('users.first_name')
            ->select('students.*')
            ->get()
            ->map(function ($student) {
            return [
                'lrn' => $student->lrn,
                'name' => $student->full_name,
                'grade_level' => $student->gradeLevel?->name,
                'section' => $student->section?->name,
                'gender' => ucfirst($student->gender),
                'birthdate' => $student->birthdate?->format('M d, Y'),
                'age' => $student->age,
                'contact' => $student->guardian_contact,
                'status' => ucfirst($student->status),
            ];
        });

        return [
            'title' => 'Student Masterlist',
            'rows' => $students,
            'summary' => [
                'Total Students' => $students->count(),
                'Male' => $students->where('gender', 'Male')->count(),
                'Female' => $students->where('gender', 'Female')->count(),
            ],
        ];
    }

    /**
     * Generate Grade Summary Report (Report Card Style)
     * Supports both Kindergarten (developmental domains) and Regular students
     */
    protected function generateGradeSummary(array $parameters): array
    {
        // Get target students first (only filter by section/gender/grade_level, NOT school_year_id on students table)
        $studentQuery = Student::with(['user', 'section', 'gradeLevel']);

        if (!empty($parameters['section_id'])) {
            $studentQuery->where('section_id', $parameters['section_id']);
        }
        if (!empty($parameters['grade_level_id'])) {
            $studentQuery->where('grade_level_id', $parameters['grade_level_id']);
        }
        if (!empty($parameters['gender'])) {
            $studentQuery->where('gender', $parameters['gender']);
        }

        $students = $studentQuery->get();

        $kinderIds = $students
            ->filter(fn($s) => strtolower($s->gradeLevel?->name ?? '') === 'kindergarten' || $s->grade_level_id === 1)
            ->pluck('id')
            ->toArray();

        $regularIds = $students->pluck('id')->diff($kinderIds)->toArray();

        $reportStudents = collect();

        // ========== KINDERGARTEN REPORT CARDS ==========
        if (!empty($kinderIds)) {
            $kinderDomains = \App\Models\KindergartenDomain::whereIn('student_id', $kinderIds)
                ->when(!empty($parameters['school_year_id']), fn($q) => $q->where('school_year_id', $parameters['school_year_id']))
                ->get();

            $kinderConfig = config('kindergarten.domains');
            $lang = 'english';

            foreach ($students->whereIn('id', $kinderIds) as $student) {
                $studentDomains = $kinderDomains->where('student_id', $student->id);
                $domains = [];

                foreach ($kinderConfig as $domainKey => $domainData) {
                    $domainName = $domainData['name'][$lang] ?? $domainData['name']['cebuano'];
                    $indicators = [];

                    foreach ($domainData['indicators'] ?? [] as $indicatorKey => $indicatorData) {
                        $indicatorText = $indicatorData[$lang] ?? $indicatorData['cebuano'];
                        $ratings = $studentDomains->where('domain', $domainKey)->where('indicator_key', $indicatorKey);

                        $indicators[] = [
                            'indicator_text' => $indicatorText,
                            'q1' => $ratings->firstWhere('quarter', 1)?->rating ?? '-',
                            'q2' => $ratings->firstWhere('quarter', 2)?->rating ?? '-',
                            'q3' => $ratings->firstWhere('quarter', 3)?->rating ?? '-',
                            'q4' => $ratings->firstWhere('quarter', 4)?->rating ?? '-',
                        ];
                    }

                    if (!empty($indicators)) {
                        $domains[] = [
                            'domain_name' => $domainName,
                            'indicators' => $indicators,
                        ];
                    }
                }

                $reportStudents->push([
                    'student_name' => $student->full_name,
                    'section' => $student->section?->name ?? 'Unknown',
                    'grade_level' => $student->gradeLevel?->name ?? 'Unknown',
                    'kindergarten' => true,
                    'domains' => $domains,
                ]);
            }
        }

        // ========== REGULAR STUDENTS REPORT CARDS ==========
        if (!empty($regularIds)) {
            $query = Grade::with(['student.user', 'student.gradeLevel', 'subject', 'section'])
                ->where('component_type', 'final_grade')
                ->whereIn('student_id', $regularIds);

            if (!empty($parameters['subject_id'])) {
                $query->where('subject_id', $parameters['subject_id']);
            }

            if (!empty($parameters['school_year_id'])) {
                $query->where('school_year_id', $parameters['school_year_id']);
            }

            $grades = $query->get();

            $regularStudents = $grades->groupBy('student_id')->map(function ($studentGrades, $studentId) {
                $first = $studentGrades->first();
                $subjects = $studentGrades->groupBy('subject_id')->map(function ($subjectGrades, $subjectId) {
                    $subjectName = $subjectGrades->first()?->subject?->name ?? 'Unknown';
                    $q1 = $subjectGrades->firstWhere('quarter', 1)?->final_grade;
                    $q2 = $subjectGrades->firstWhere('quarter', 2)?->final_grade;
                    $q3 = $subjectGrades->firstWhere('quarter', 3)?->final_grade;
                    $q4 = $subjectGrades->firstWhere('quarter', 4)?->final_grade;
                    $avg = collect([$q1, $q2, $q3, $q4])->filter()->avg();
                    return [
                        'subject' => $subjectName,
                        'q1' => $q1 ?? '-',
                        'q2' => $q2 ?? '-',
                        'q3' => $q3 ?? '-',
                        'q4' => $q4 ?? '-',
                        'final' => $avg ? round($avg, 2) : '-',
                    ];
                })->values();

                $overall = collect($subjects)->pluck('final')->filter()->avg();

                return [
                    'student_name' => $first?->student?->full_name ?? 'Unknown',
                    'section' => $first?->section?->name ?? 'Unknown',
                    'grade_level' => $first?->student?->gradeLevel?->name ?? 'Unknown',
                    'kindergarten' => false,
                    'subjects' => $subjects,
                    'general_average' => $overall ? round($overall, 2) : '-',
                ];
            })->values();

            $reportStudents = $reportStudents->merge($regularStudents);
        }

        $allFinals = $reportStudents->where('kindergarten', false)->pluck('subjects')->flatten(1)->pluck('final')->filter();
        $passed = $allFinals->filter(fn($g) => is_numeric($g) && $g >= 75)->count();
        $total = $allFinals->count();

        return [
            'title' => 'Grade Summary Report',
            'report_card' => true,
            'students' => $reportStudents->values(),
            'rows' => [],
            'summary' => [
                'Total Students' => $reportStudents->count(),
                'Kindergarten' => $reportStudents->where('kindergarten', true)->count(),
                'Regular' => $reportStudents->where('kindergarten', false)->count(),
                'Passing Rate' => $total > 0 ? round(($passed / $total) * 100, 2) . '%' : '0%',
                'Class Average' => $total > 0 ? round($allFinals->avg(), 2) : '-',
            ],
        ];
    }

    /**
     * Generate Attendance Summary Report
     */
    protected function generateAttendanceSummary(array $parameters): array
    {
        $startDate = $parameters['start_date'] ?? now()->startOfMonth()->toDateString();
        $endDate = $parameters['end_date'] ?? now()->toDateString();

        $query = Attendance::with(['student.user', 'section'])
            ->whereBetween('date', [$startDate, $endDate]);

        if (!empty($parameters['section_id'])) {
            $query->where('section_id', $parameters['section_id']);
        }
        if (!empty($parameters['grade_level_id'])) {
            $query->whereHas('student', fn($q) => $q->where('grade_level_id', $parameters['grade_level_id']));
        }
        if (!empty($parameters['gender'])) {
            $query->whereHas('student', fn($q) => $q->where('gender', $parameters['gender']));
        }

        $attendances = $query->get();

        $summary = $attendances->groupBy('student_id')->map(function ($records, $studentId) {
            $firstRecord = $records->first();
            return [
                'student_name' => $firstRecord->student?->full_name ?? 'Unknown',
                'section' => $firstRecord->section?->name ?? 'Unknown',
                'present' => $records->where('status', 'present')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
                'total' => $records->count(),
                'attendance_rate' => $records->count() > 0 
                    ? round(($records->whereIn('status', ['present', 'late'])->count() / $records->count()) * 100, 2) 
                    : 0,
            ];
        })->values();

        return [
            'title' => 'Attendance Summary Report',
            'period' => Carbon::parse($startDate)->format('M d, Y') . ' - ' . Carbon::parse($endDate)->format('M d, Y'),
            'rows' => $summary,
            'summary' => [
                'Total Students' => $summary->count(),
                'Average Attendance Rate' => $summary->count() > 0 ? round($summary->avg('attendance_rate'), 2) . '%' : '0%',
                'Perfect Attendance' => $summary->where('attendance_rate', 100)->count(),
            ],
        ];
    }

    /**
     * Generate Enrollment Statistics
     */
    protected function generateEnrollmentStats(array $parameters): array
    {
        $schoolYearId = $parameters['school_year_id'] ?? SchoolYear::where('is_active', true)->value('id');

        $query = Enrollment::with(['student', 'gradeLevel', 'section'])
            ->where('school_year_id', $schoolYearId);

        if (!empty($parameters['section_id'])) {
            $query->where('section_id', $parameters['section_id']);
        }
        if (!empty($parameters['grade_level_id'])) {
            $query->where('grade_level_id', $parameters['grade_level_id']);
        }
        if (!empty($parameters['gender'])) {
            $query->whereHas('student', fn($q) => $q->where('gender', $parameters['gender']));
        }

        $enrollments = $query->get();

        $byGradeLevel = $enrollments->groupBy('grade_level_id')->map(function ($items, $gradeLevelId) {
            $first = $items->first();
            return [
                'grade_level' => $first?->gradeLevel?->name ?? 'Unknown',
                'total' => $items->count(),
                'male' => $items->filter(fn($i) => strtolower($i->student?->gender ?? '') === 'male')->count(),
                'female' => $items->filter(fn($i) => strtolower($i->student?->gender ?? '') === 'female')->count(),
            ];
        })->values();

        $bySection = $enrollments->groupBy('section_id')->map(function ($items, $sectionId) {
            $first = $items->first();
            return [
                'section' => $first?->section?->name ?? 'Unknown',
                'grade_level' => $first?->gradeLevel?->name ?? 'Unknown',
                'total' => $items->count(),
                'adviser' => $first?->section?->teacher?->user?->full_name ?? 'Not Assigned',
            ];
        })->values();

        return [
            'title' => 'Enrollment Statistics',
            'school_year' => SchoolYear::find($schoolYearId)?->name ?? 'Unknown',
            'rows' => $byGradeLevel,
            'by_section' => $bySection,
            'summary' => [
                'Total Enrollments' => $enrollments->count(),
                'Male' => $enrollments->filter(fn($e) => strtolower($e->student?->gender ?? '') === 'male')->count(),
                'Female' => $enrollments->filter(fn($e) => strtolower($e->student?->gender ?? '') === 'female')->count(),
            ],
        ];
    }

    /**
     * Generate Teacher Workload Report
     */
    protected function generateTeacherWorkload(array $parameters): array
    {
        $teachers = Teacher::with(['user', 'sections', 'subjects'])->get();

        $workload = $teachers->map(function ($teacher) {
            return [
                'teacher_name' => $teacher->user?->full_name ?? 'Unknown',
                'specialization' => $teacher->specialization ?? 'N/A',
                'sections_handled' => $teacher->sections->count(),
                'subjects' => $teacher->subjects->pluck('name')->implode(', '),
                'total_students' => $teacher->sections->sum(function ($section) {
                    return $section->students->count();
                }),
            ];
        });

        return [
            'title' => 'Teacher Workload Report',
            'rows' => $workload,
            'summary' => [
                'Total Teachers' => $workload->count(),
                'Average Sections per Teacher' => round($workload->avg('sections_handled'), 2),
                'Average Students per Teacher' => round($workload->avg('total_students'), 2),
            ],
        ];
    }

    /**
     * Generate Class Performance Report
     */
    protected function generateClassPerformance(array $parameters): array
    {
        $sectionId = $parameters['section_id'] ?? null;
        
        $query = Section::with(['gradeLevel', 'teacher.user', 'students']);
        
        if ($sectionId) {
            $query->where('id', $sectionId);
        }
        if (!empty($parameters['grade_level_id'])) {
            $query->where('grade_level_id', $parameters['grade_level_id']);
        }

        $sections = $query->get()->map(function ($section) {
            // Get final grades for this section
            $grades = Grade::where('section_id', $section->id)
                ->where('component_type', 'final_grade')
                ->get();
            
            $avgGrade = $grades->count() > 0 
                ? $grades->avg('final_grade')
                : 0;

            return [
                'section' => $section->name,
                'grade_level' => $section->gradeLevel?->name ?? 'Unknown',
                'adviser' => $section->teacher?->user?->full_name ?? 'Not Assigned',
                'total_students' => $section->students->count(),
                'average_grade' => round($avgGrade, 2),
                'passing_rate' => $this->calculateSectionPassingRate($grades),
            ];
        });

        return [
            'title' => 'Class Performance Report',
            'rows' => $sections,
            'summary' => [
                'Total Sections' => $sections->count(),
                'Highest Average' => round($sections->max('average_grade'), 2),
                'Lowest Average' => round($sections->min('average_grade'), 2),
                'Overall Average' => round($sections->avg('average_grade'), 2),
            ],
        ];
    }

    /**
     * Calculate section passing rate
     */
    protected function calculateSectionPassingRate($grades): float
    {
        if ($grades->count() === 0) return 0;
        
        $passed = $grades->where('final_grade', '>=', 75)->count();

        return round(($passed / $grades->count()) * 100, 2);
    }

    /**
     * Generate Attendance Trend Report
     */
    protected function generateAttendanceTrendReport(array $parameters): array
    {
        $days = $parameters['days'] ?? 30;
        $sectionId = $parameters['section_id'] ?? null;

        $trend = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $query = Attendance::whereDate('date', $date);
            
            if ($sectionId) {
                $query->where('section_id', $sectionId);
            }

            $total = $query->distinct('student_id')->count('student_id');
            $present = $total > 0 ? $query->where('status', 'present')->distinct('student_id')->count('student_id') : 0;
            
            $trend[] = [
                'date' => $date->format('M d'),
                'total' => $total,
                'present' => $present,
                'rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            ];
        }

        return [
            'title' => 'Attendance Trend (Last ' . $days . ' Days)',
            'chart_data' => $trend,
            'summary' => [
                'Average Daily Attendance' => round(collect($trend)->avg('rate'), 2) . '%',
                'Highest Rate' => collect($trend)->max('rate') . '%',
                'Lowest Rate' => collect($trend)->min('rate') . '%',
            ],
        ];
    }

    /**
     * Generate Honor Roll Report
     */
    protected function generateHonorRoll(array $parameters): array
    {
        $minAverage = $parameters['min_average'] ?? 90;
        $schoolYearId = $parameters['school_year_id'] ?? SchoolYear::where('is_active', true)->value('id');

        $honors = Grade::with(['student.user', 'section'])
            ->where('school_year_id', $schoolYearId)
            ->where('component_type', 'final_grade')
            ->get()
            ->groupBy('student_id')
            ->map(function ($grades, $studentId) use ($minAverage) {
                $first = $grades->first();
                $genAvg = $grades->avg('final_grade');
                
                if ($genAvg < $minAverage) return null;

                return [
                    'student_name' => $first->student?->full_name ?? 'Unknown',
                    'section' => $first->section?->name ?? 'Unknown',
                    'general_average' => round($genAvg, 2),
                    'honor' => $genAvg >= 98 ? 'With Highest Honors' : ($genAvg >= 95 ? 'With High Honors' : 'With Honors'),
                ];
            })
            ->filter()
            ->sortByDesc('general_average')
            ->values();

        return [
            'title' => 'Honor Roll',
            'rows' => $honors,
            'summary' => [
                'Total Honorees' => $honors->count(),
                'With Highest Honors' => $honors->where('honor', 'With Highest Honors')->count(),
                'With High Honors' => $honors->where('honor', 'With High Honors')->count(),
                'With Honors' => $honors->where('honor', 'With Honors')->count(),
            ],
        ];
    }

    /**
     * Generate Dropout Risk Report
     */
    protected function generateDropoutRiskReport(array $parameters): array
    {
        $threshold = $parameters['attendance_threshold'] ?? 75;
        $gradeThreshold = $parameters['grade_threshold'] ?? 75;

        // Students with low attendance
        $atRiskStudents = Student::with(['user', 'section', 'attendances'])
            ->get()
            ->map(function ($student) use ($threshold, $gradeThreshold) {
                $totalAttendance = $student->attendances->count();
                $presentAttendance = $student->attendances->whereIn('status', ['present', 'late'])->count();
                $attendanceRate = $totalAttendance > 0 ? ($presentAttendance / $totalAttendance) * 100 : 100;

                // Get student's average final grade
                $avgGrade = Grade::where('student_id', $student->id)
                    ->where('component_type', 'final_grade')
                    ->avg('final_grade') ?? 100;

                $riskFactors = [];
                if ($attendanceRate < $threshold) $riskFactors[] = 'Low Attendance (' . round($attendanceRate, 1) . '%)';
                if ($avgGrade < $gradeThreshold) $riskFactors[] = 'Low Grades (' . round($avgGrade, 1) . ')';

                if (empty($riskFactors)) return null;

                return [
                    'student_name' => $student->full_name,
                    'section' => $student->section?->name ?? 'N/A',
                    'attendance_rate' => round($attendanceRate, 1) . '%',
                    'average_grade' => round($avgGrade, 2),
                    'risk_factors' => implode(', ', $riskFactors),
                    'risk_level' => count($riskFactors) >= 2 ? 'High' : 'Medium',
                ];
            })
            ->filter()
            ->sortByDesc('risk_level')
            ->values();

        return [
            'title' => 'At-Risk Students Report',
            'rows' => $atRiskStudents,
            'summary' => [
                'Total At-Risk Students' => $atRiskStudents->count(),
                'High Risk' => $atRiskStudents->where('risk_level', 'High')->count(),
                'Medium Risk' => $atRiskStudents->where('risk_level', 'Medium')->count(),
            ],
        ];
    }

    /**
     * Generate SF1 - School Register
     */
    protected function generateSf1(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first()
            ?? SchoolYear::latest('start_date')->first();

        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolYearStart = $activeSchoolYear ? Carbon::parse($activeSchoolYear->start_date)->year : Carbon::now()->year;

        $selectedSection = !empty($parameters['section_id'])
            ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id'])
            : null;

        $enrollments = collect();
        $maleCount = 0;
        $femaleCount = 0;

        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])
                ->where('section_id', $selectedSection->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->where('status', 'enrolled')
                ->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))
                ->get()
                ->sortBy(function ($enrollment) {
                    $student = $enrollment->student;
                    if (!$student) return [2, '', ''];
                    $genderOrder = in_array(strtoupper($student->gender ?? ''), ['MALE', 'M']) ? 0 : 1;
                    $user = $student->user;
                    return [$genderOrder, $user->last_name ?? '', $user->first_name ?? ''];
                })
                ->values();

            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student;
                if ($student && $student->birthdate) {
                    $student->calculated_age = $this->calculateAge($student->birthdate, $schoolYearStart);
                }
            }

            $maleCount = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['MALE', 'M']))->count();
            $femaleCount = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['FEMALE', 'F']))->count();
        }

        $html = view('admin.reports.sf1', [
            'selectedSection' => $selectedSection,
            'enrollments' => $enrollments,
            'schoolYear' => $activeSchoolYear?->name ?? '',
            'schoolYearStart' => $schoolYearStart,
            'schoolId' => $schoolSettings['deped_school_id'] ?? '',
            'schoolName' => $schoolSettings['school_name'] ?? '',
            'schoolDivision' => $schoolSettings['school_division'] ?? '',
            'schoolRegion' => $schoolSettings['school_region'] ?? '',
            'schoolHead' => $schoolSettings['school_head'] ?? '',
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
        ])->render();

        return [
            'title' => 'SF1 - School Register',
            'html' => $html,
            'rows' => [],
            'summary' => [
                'Total Students' => $enrollments->count(),
                'Male' => $maleCount,
                'Female' => $femaleCount,
            ],
        ];
    }

    /**
     * Generate SF2 - Daily Attendance
     */
    protected function generateSf2(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first()
            ?? SchoolYear::latest('start_date')->first();

        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;

        $selectedSection = !empty($parameters['section_id'])
            ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id'])
            : null;

        // Determine month from start_date parameter or default to current month
        if (!empty($parameters['start_date'])) {
            $selectedMonth = date('F', strtotime($parameters['start_date']));
        } else {
            $selectedMonth = date('F');
        }
        $monthNum = date('n', strtotime($selectedMonth));

        if ($activeSchoolYear) {
            $startYear = Carbon::parse($activeSchoolYear->start_date)->year;
            $endYear = $activeSchoolYear->end_date ? Carbon::parse($activeSchoolYear->end_date)->year : $startYear + 1;
            $year = ($monthNum >= 1 && $monthNum <= 3) ? $endYear : $startYear;
        } else {
            $year = date('Y');
        }

        $enrollments = collect();
        $attendances = collect();
        $schoolDaysConfig = null;

        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])
                ->where('section_id', $selectedSection->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->where('status', 'enrolled')
                ->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))
                ->get()
                ->sortBy(function ($enrollment) {
                    $student = $enrollment->student;
                    if (!$student) return [2, '', ''];
                    $genderOrder = in_array(strtoupper($student->gender ?? ''), ['MALE', 'M']) ? 0 : 1;
                    $user = $student->user;
                    return [$genderOrder, $user->last_name ?? '', $user->first_name ?? ''];
                })
                ->values();

            if ($enrollments->isNotEmpty()) {
                $studentIds = $enrollments->pluck('student.id')->toArray();
                $attendances = Attendance::where('section_id', $selectedSection->id)
                    ->whereIn('student_id', $studentIds)
                    ->whereMonth('date', $monthNum)
                    ->get();

                $schoolDaysConfig = \App\Models\AttendanceSchoolDay::where([
                    'section_id' => $selectedSection->id,
                    'school_year_id' => $activeSchoolYear->id,
                    'month' => $monthNum,
                    'year' => Carbon::now()->year,
                ])->first();
            }
        }

        $html = view('admin.reports.sf2', [
            'selectedSection' => $selectedSection,
            'enrollments' => $enrollments,
            'attendances' => $attendances,
            'schoolYear' => $activeSchoolYear?->name ?? '',
            'schoolId' => $schoolSettings['deped_school_id'] ?? '',
            'schoolName' => $schoolSettings['school_name'] ?? '',
            'schoolHead' => $schoolSettings['school_head'] ?? '',
            'selectedMonth' => $selectedMonth,
            'monthNum' => $monthNum,
            'year' => $year,
            'schoolDaysConfig' => $schoolDaysConfig,
        ])->render();

        return [
            'title' => 'SF2 - Daily Attendance',
            'html' => $html,
            'rows' => [],
            'summary' => [
                'Total Students' => $enrollments->count(),
                'Month' => $selectedMonth . ' ' . $year,
            ],
        ];
    }

    /**
     * Calculate age as of first Friday of June
     */
    protected function calculateAge($birthDate, $year)
    {
        if (!$birthDate) {
            return null;
        }

        $birthDate = Carbon::parse($birthDate);
        $referenceDate = Carbon::create($year, 6, 1);

        while ($referenceDate->format('l') !== 'Friday') {
            $referenceDate->addDay();
        }

        return $referenceDate->diffInYears($birthDate);
    }

    /**
     * Generic report generator fallback
     */
    protected function generateGenericReport(ReportTemplate $template, array $parameters): array
    {
        return [
            'title' => $template->name,
            'message' => 'This report type is not yet implemented.',
            'rows' => [],
            'chart_data' => [],
            'summary' => [],
            'parameters' => $parameters,
        ];
    }

    /**
     * Save a report configuration
     */
    public function save(Request $request, ReportTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parameters' => 'required|array',
            'format' => 'required|in:html,pdf,excel,csv',
            'is_favorite' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $savedReport = SavedReport::create([
            'name' => $validated['name'],
            'template_id' => $template->id,
            'user_id' => auth()->id(),
            'parameters' => $validated['parameters'],
            'format' => $validated['format'],
            'is_favorite' => $validated['is_favorite'] ?? false,
            'notes' => $validated['notes'],
            'last_run_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Report saved successfully',
            'saved_report' => $savedReport,
        ]);
    }

    /**
     * Run a saved report
     */
    public function runSavedReport(SavedReport $savedReport)
    {
        if ($savedReport->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        $savedReport->update(['last_run_at' => now()]);

        return redirect()->route('admin.reports.builder', [
            $savedReport->template,
            'saved_report' => $savedReport->id,
        ]);
    }

    /**
     * Delete saved report
     */
    public function destroySaved(SavedReport $savedReport)
    {
        if ($savedReport->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $savedReport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Report deleted successfully',
        ]);
    }

    /**
     * Export to PDF
     */
    protected function exportPdf(ReportTemplate $template, array $data, array $parameters)
    {
        $pdf = Pdf::loadView('admin.reports.exports.pdf', compact('template', 'data', 'parameters'));
        return $pdf->download(str_replace(' ', '_', $data['title']) . '_' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Export to Excel
     */
    protected function exportExcel(ReportTemplate $template, array $data, array $parameters)
    {
        // This would use Maatwebsite/Excel - simplified for now
        return response()->json([
            'success' => true,
            'message' => 'Excel export would be implemented with Maatwebsite/Excel',
            'data' => $data,
        ]);
    }

    /**
     * Export to CSV
     */
    protected function exportCsv(ReportTemplate $template, array $data, array $parameters)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . str_replace(' ', '_', $data['title']) . '_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');
            
            // Headers
            if (!empty($data['rows']) && is_array($data['rows']->first())) {
                fputcsv($file, array_keys($data['rows']->first()));
                
                // Data
                foreach ($data['rows'] as $row) {
                    fputcsv($file, $row);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Generate SF3 - Books Issued and Returned
     */
    protected function generateSf3(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolYear = $activeSchoolYear?->name ?? '';
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $enrollments = collect(); $books = collect(); $bookInventories = collect();
        $adviserName = '';
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))->get();
            $males = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']))->sortBy(fn($e) => [strtolower($e->student->user->last_name ?? ''), strtolower($e->student->user->first_name ?? '')]);
            $females = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['FEMALE','F']))->sortBy(fn($e) => [strtolower($e->student->user->last_name ?? ''), strtolower($e->student->user->first_name ?? '')]);
            $enrollments = $males->concat($females)->values();
            if ($enrollments->isNotEmpty()) {
                $studentIds = $enrollments->pluck('student.id');
                $books = Book::whereIn('student_id', $studentIds)->where('school_year_id', $activeSchoolYear->id)->get()->groupBy('student_id');
            }
            if ($selectedSection->gradeLevel) {
                $bookInventories = BookInventory::where('grade_level', $selectedSection->gradeLevel->name)->orWhere('grade_level', 'All')->orWhere('grade_level_id', $selectedSection->grade_level_id)->orderBy('subject_area')->orderBy('title')->get();
            }
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else {
                $adviserName = $selectedSection->teacher->name ?? '';
            }
        }
        $totalBooksIssued = 0; $totalBooksReturned = 0; $totalBooksDamaged = 0; $totalBooksLost = 0;
        foreach ($books as $studentBooks) {
            foreach ($studentBooks as $book) {
                $totalBooksIssued++;
                if ($book->date_returned) $totalBooksReturned++;
                if ($book->status == 'damaged') $totalBooksDamaged++;
                if ($book->status == 'lost') $totalBooksLost++;
            }
        }
        $html = view('admin.reports.sf3', compact('selectedSection','enrollments','books','bookInventories','schoolYear','activeSchoolYear','schoolId','schoolName','schoolDivision','schoolDistrict','schoolRegion','adviserName','totalBooksIssued','totalBooksReturned','totalBooksDamaged','totalBooksLost'))->render();
        return ['title' => 'SF3 - Books Issued and Returned', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $enrollments->count(), 'Books Issued' => $totalBooksIssued, 'Books Returned' => $totalBooksReturned]];
    }

    /**
     * Generate SF4 - Monthly Attendance Report
     */
    protected function generateSf4(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $selectedMonth = !empty($parameters['start_date']) ? date('F', strtotime($parameters['start_date'])) : 'June';
        $adviserName = '';
        $enrollments = collect(); $attendanceSummary = collect(); $monthlyStats = [];
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))->get()->sortBy(fn($e) => [in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']) ? 0 : 1, $e->student->user->last_name ?? '', $e->student->user->first_name ?? ''])->values();
            $year = Carbon::parse($activeSchoolYear->start_date)->year;
            $monthNum = date('n', strtotime($selectedMonth));
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
            $schoolDays = [];
            for ($day = 1; $day <= $daysInMonth; $day++) { $date = Carbon::create($year, $monthNum, $day); if (!$date->isWeekend()) $schoolDays[] = $day; }
            $totalSchoolDays = count($schoolDays);
            $attendances = collect();
            if ($enrollments->isNotEmpty()) {
                $attendances = Attendance::whereIn('student_id', $enrollments->pluck('student.id'))->whereYear('date', $year)->whereMonth('date', $monthNum)->get();
            }
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student; $user = $student->user ?? null;
                $fullName = ($user->last_name ?? '').', '.($user->first_name ?? '').' '.($user->middle_name ?? '');
                $present = 0; $absent = 0; $tardy = 0;
                foreach ($schoolDays as $day) {
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                    $a = $attendances->first(fn($a) => $a->student_id == $student->id && $a->date == $dateStr);
                    if ($a) { switch ($a->status) { case 'present': $present++; break; case 'absent': $absent++; break; case 'tardy': case 'late': $tardy++; break; } } else { $present++; }
                }
                $attendanceSummary->push(['enrollment'=>$enrollment,'student'=>$student,'full_name'=>$fullName,'gender'=>(in_array(strtoupper($student->gender ?? ''), ['MALE','M']) ? 'M' : 'F'),'present'=>$present,'absent'=>$absent,'tardy'=>$tardy,'total_days'=>$totalSchoolDays,'attendance_rate'=>$totalSchoolDays > 0 ? round(($present/$totalSchoolDays)*100,1) : 0]);
            }
            $maleSummary = $attendanceSummary->filter(fn($i)=>$i['gender']=='M');
            $femaleSummary = $attendanceSummary->filter(fn($i)=>$i['gender']=='F');
            $monthlyStats = ['total_school_days'=>$totalSchoolDays,'total_students'=>$attendanceSummary->count(),'male_count'=>$maleSummary->count(),'female_count'=>$femaleSummary->count(),'overall_avg_attendance'=>$attendanceSummary->avg('attendance_rate') ?? 0,'total_absences'=>$attendanceSummary->sum('absent'),'total_tardy'=>$attendanceSummary->sum('tardy')];
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else { $adviserName = $selectedSection->teacher->name ?? ''; }
        }
        $html = view('admin.reports.sf4', compact('selectedSection','adviserName','enrollments','attendanceSummary','activeSchoolYear','schoolId','schoolName','schoolHead','selectedMonth','monthlyStats'))->render();
        return ['title' => 'SF4 - Monthly Attendance', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $enrollments->count(), 'Month' => $selectedMonth]];
    }

    /**
     * Generate SF5 - Report on Learning Progress & Achievement
     */
    protected function generateSf5(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolYear = $activeSchoolYear?->name ?? '';
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $enrollments = collect(); $grades = collect(); $adviserName = '';
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))->get()->sortBy(fn($e) => [in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']) ? 0 : 1, $e->student->user->last_name ?? '', $e->student->user->first_name ?? ''])->values();
            if ($enrollments->isNotEmpty()) {
                $grades = Grade::whereIn('student_id', $enrollments->pluck('student.id'))->get();
            }
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else { $adviserName = $selectedSection->teacher->name ?? ''; }
        }
        $html = view('admin.reports.sf5', compact('selectedSection','adviserName','enrollments','grades','schoolYear','activeSchoolYear','schoolId','schoolName','schoolDivision','schoolRegion','schoolDistrict','schoolHead'))->render();
        return ['title' => 'SF5 - Learning Progress & Achievement', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $enrollments->count()]];
    }

    /**
     * Generate SF6 - Summarized Report on Promotion
     */
    protected function generateSf6(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $enrollments = collect(); $promotionData = collect(); $summaryStats = []; $adviserName = '';
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user', 'student.grades.subject'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))->get()->sortBy(fn($e) => [in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']) ? 0 : 1, $e->student->user->last_name ?? '', $e->student->user->first_name ?? ''])->values();
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student; $user = $student->user ?? null;
                $fullName = trim(($user->last_name ?? '').', '.($user->first_name ?? '').' '.($user->middle_name ?? ''));
                $grades = $student->grades ?? collect();
                $finalAverage = round($grades->avg('final_grade') ?? 0);
                $proficiencyLevel = $this->getProficiencyLevel($finalAverage);
                $promotionStatus = $this->getPromotionStatus($finalAverage, $grades);
                $generalAverageWords = $this->numberToWords($finalAverage);
                $promotionData->push(['enrollment'=>$enrollment,'student'=>$student,'full_name'=>$fullName,'gender'=>(in_array(strtoupper($student->gender ?? ''), ['MALE','M']) ? 'M' : 'F'),'final_average'=>$finalAverage,'proficiency_level'=>$proficiencyLevel,'promotion_status'=>$promotionStatus,'general_average_words'=>$generalAverageWords,'grades'=>$grades,'remarks'=>$this->getRemarks($promotionStatus, $finalAverage, $selectedSection)]);
            }
            $maleData = $promotionData->where('gender', 'M');
            $femaleData = $promotionData->where('gender', 'F');
            $summaryStats = ['total_students'=>$promotionData->count(),'male_count'=>$maleData->count(),'female_count'=>$femaleData->count(),'promoted_male'=>$maleData->where('promotion_status','Promoted')->count(),'promoted_female'=>$femaleData->where('promotion_status','Promoted')->count(),'conditional_male'=>$maleData->where('promotion_status','Conditional')->count(),'conditional_female'=>$femaleData->where('promotion_status','Conditional')->count(),'retained_male'=>$maleData->where('promotion_status','Retained')->count(),'retained_female'=>$femaleData->where('promotion_status','Retained')->count(),'beginning_male'=>$maleData->where('proficiency_level','Beginning')->count(),'beginning_female'=>$femaleData->where('proficiency_level','Beginning')->count(),'developing_male'=>$maleData->where('proficiency_level','Developing')->count(),'developing_female'=>$femaleData->where('proficiency_level','Developing')->count(),'approaching_male'=>$maleData->where('proficiency_level','Approaching Proficiency')->count(),'approaching_female'=>$femaleData->where('proficiency_level','Approaching Proficiency')->count(),'proficient_male'=>$maleData->where('proficiency_level','Proficient')->count(),'proficient_female'=>$femaleData->where('proficiency_level','Proficient')->count(),'advanced_male'=>$maleData->where('proficiency_level','Advanced')->count(),'advanced_female'=>$femaleData->where('proficiency_level','Advanced')->count()];
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else { $adviserName = $selectedSection->teacher->name ?? ''; }
        }
        $html = view('admin.reports.sf6', compact('selectedSection','adviserName','enrollments','promotionData','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','summaryStats'))->render();
        return ['title' => 'SF6 - Summarized Report on Promotion', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $promotionData->count()]];
    }

    /**
     * Generate SF7 - School Personnel Assignment
     */
    protected function generateSf7(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $teacher = null; $teacherProfile = null; $teachingPrograms = collect(); $subjects = collect(); $kinderDomains = collect(); $isKindergarten = false;
        if ($selectedSection) {
            $teacher = $selectedSection->teacher;
            $teacherUser = $teacher?->user;
            $adviserName = '';
            if ($teacherUser) {
                $adviserName = trim(($teacherUser->last_name ?? '').', '.($teacherUser->first_name ?? '').' '.($teacherUser->middle_name ?? '')) ?: ($teacherUser->name ?? '');
            } else { $adviserName = $teacher?->name ?? ''; }
            $enrollments = Enrollment::where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive', 'dropped']))->with('student')->get();
            $totalStudents = $enrollments->count();
            $maleStudents = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']))->count();
            $femaleStudents = $totalStudents - $maleStudents;
            $isKindergarten = in_array(strtolower($selectedSection->gradeLevel->name ?? ''), ['kindergarten', 'kinder']);
            if ($isKindergarten) {
                $kinderDomains = collect(config('kindergarten.domains'))->map(function($domain, $key) {
                    return ['key' => $key, 'name' => $domain['name']['english'] ?? ($domain['name']['cebuano'] ?? $key)];
                })->values();
            } else {
                $subjects = Subject::where('grade_level_id', $selectedSection->grade_level_id)->orderBy('name')->get();
            }
            $teachingPrograms = TeachingProgram::where('teacher_id', $teacher?->id)->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->orderByRaw("FIELD(day, 'M', 'T', 'W', 'TH', 'F')")->orderBy('time_from')->get();
            $teacherProfile = ['employee_no'=>$teacher->employee_no ?? $teacherUser?->id ?? 'N/A','tin'=>$teacher->tin ?? 'N/A','full_name'=>$adviserName,'last_name'=>$teacherUser?->last_name ?? '','first_name'=>$teacherUser?->first_name ?? '','middle_name'=>$teacherUser?->middle_name ?? '','name_extension'=>$teacher->name_extension ?? '','sex'=>(in_array(strtoupper($teacherUser?->gender ?? ''), ['MALE','M']) ? 'M' : 'F'),'birthdate'=>$teacher?->birthdate ? Carbon::parse($teacher->birthdate)->format('m/d/Y') : '','age'=>$teacher?->birthdate ? Carbon::parse($teacher->birthdate)->age : '','contact_no'=>$teacher->contact_no ?? $teacherUser?->phone ?? '','email'=>$teacherUser?->email ?? '','position'=>$teacher->position ?? 'Teacher I','nature_of_appointment'=>$teacher->nature_of_appointment ?? 'Permanent','fund_source'=>$teacher->fund_source ?? 'National','date_of_appointment'=>$teacher->date_of_appointment ? Carbon::parse($teacher->date_of_appointment)->format('m/d/Y') : '','years_in_service'=>$teacher->date_of_appointment ? Carbon::parse($teacher->date_of_appointment)->diffInYears(now()) : '','highest_degree'=>$teacher->highest_degree ?? 'Bachelor of Elementary Education','major'=>$teacher->major ?? 'General Education','minor'=>$teacher->minor ?? '','prc_license_no'=>$teacher->prc_license_no ?? '','prc_validity'=>$teacher->prc_validity ? Carbon::parse($teacher->prc_validity)->format('m/d/Y') : '','section'=>$selectedSection->name ?? '','grade_level'=>$selectedSection->gradeLevel->name ?? '','advisory_class'=>$selectedSection->name ?? '','total_students'=>$totalStudents,'male_students'=>$maleStudents,'female_students'=>$femaleStudents,'ancillary_assignments'=>$teacher->ancillary_assignments ?? 'None','remarks'=>$teacher->remarks ?? ''];
        }
        $html = view('admin.reports.sf7', compact('selectedSection','teacherProfile','teacher','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','schoolDistrict','subjects','teachingPrograms','isKindergarten','kinderDomains'))->render();
        return ['title' => 'SF7 - School Personnel Assignment', 'html' => $html, 'rows' => [], 'summary' => []];
    }

    /**
     * Generate SF8 - Health and Nutrition Report
     */
    protected function generateSf8(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $selectedPeriod = $parameters['period'] ?? 'bosy';
        $adviserName = ''; $healthData = collect(); $summaryStats = [];
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user', 'student.healthRecords'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive', 'dropped']))->get()->sortBy(fn($e) => [in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']) ? 0 : 1, $e->student->user->last_name ?? '', $e->student->user->first_name ?? '']);
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student; $user = $student->user ?? null;
                $fullName = ($user->last_name ?? '').', '.($user->first_name ?? '').' '.($user->middle_name ?? '');
                $birthdate = $student->birthdate ? Carbon::parse($student->birthdate) : null;
                $age = $birthdate ? $birthdate->age : '';
                $ageFormatted = $birthdate ? $birthdate->diff(Carbon::now())->format('%y.%m') : '';
                $healthRecord = $student->healthRecords->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('period', $selectedPeriod)->first();
                $healthData->push(['enrollment'=>$enrollment,'student'=>$student,'full_name'=>$fullName,'gender'=>(in_array(strtoupper($student->gender ?? ''), ['MALE','M']) ? 'M' : 'F'),'lrn'=>$student->lrn ?? '','birthdate'=>$birthdate ? $birthdate->format('m/d/Y') : '','age'=>$age,'age_formatted'=>$ageFormatted,'weight'=>$healthRecord?->weight,'height'=>$healthRecord?->height,'height_squared'=>$healthRecord?->height ? round(pow($healthRecord->height, 2), 2) : null,'bmi'=>$healthRecord?->bmi,'nutritional_status'=>$healthRecord?->nutritional_status,'height_for_age'=>$healthRecord?->height_for_age,'remarks'=>$healthRecord?->remarks]);
            }
            $maleData = $healthData->where('gender', 'M')->whereNotNull('bmi');
            $femaleData = $healthData->where('gender', 'F')->whereNotNull('bmi');
            $summaryStats = ['total_students'=>$healthData->count(),'assessed_count'=>$healthData->whereNotNull('bmi')->count(),'male_count'=>$maleData->count(),'female_count'=>$femaleData->count(),'male_severely_wasted'=>$maleData->where('nutritional_status','Severely Wasted')->count(),'male_wasted'=>$maleData->where('nutritional_status','Wasted')->count(),'male_normal'=>$maleData->where('nutritional_status','Normal')->count(),'male_overweight'=>$maleData->where('nutritional_status','Overweight')->count(),'male_obese'=>$maleData->where('nutritional_status','Obese')->count(),'female_severely_wasted'=>$femaleData->where('nutritional_status','Severely Wasted')->count(),'female_wasted'=>$femaleData->where('nutritional_status','Wasted')->count(),'female_normal'=>$femaleData->where('nutritional_status','Normal')->count(),'female_overweight'=>$femaleData->where('nutritional_status','Overweight')->count(),'female_obese'=>$femaleData->where('nutritional_status','Obese')->count(),'male_severely_stunted'=>$maleData->where('height_for_age','Severely Stunted')->count(),'male_stunted'=>$maleData->where('height_for_age','Stunted')->count(),'male_normal_hfa'=>$maleData->where('height_for_age','Normal')->count(),'male_tall'=>$maleData->where('height_for_age','Tall')->count(),'female_severely_stunted'=>$femaleData->where('height_for_age','Severely Stunted')->count(),'female_stunted'=>$femaleData->where('height_for_age','Stunted')->count(),'female_normal_hfa'=>$femaleData->where('height_for_age','Normal')->count(),'female_tall'=>$femaleData->where('height_for_age','Tall')->count()];
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else { $adviserName = $selectedSection->teacher->name ?? ''; }
        }
        $html = view('admin.reports.sf8', compact('selectedSection','selectedPeriod','adviserName','healthData','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','schoolDistrict','summaryStats'))->render();
        return ['title' => 'SF8 - Health and Nutrition Report', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $healthData->count(), 'Assessed' => $summaryStats['assessed_count'] ?? 0]];
    }

    /**
     * Generate SF9 - Report Card
     */
    protected function generateSf9(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $selectedStudent = !empty($parameters['student_id']) ? Student::with('user')->find($parameters['student_id']) : null;
        $selectedSection = null; $enrollment = null; $grades = collect(); $attendance = collect(); $coreValues = collect(); $signature = null; $adviserName = '';
        $isKindergarten = false; $kindergartenDomains = collect(); $kinderConfig = config('kindergarten.domains'); $lang = 'cebuano';
        $subjectGrades = collect(); $generalAverage = null;
        if ($selectedStudent && $activeSchoolYear) {
            $enrollment = Enrollment::with('section.gradeLevel.subjects')->where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear->id)->first();
            if ($enrollment) {
                $selectedSection = $enrollment->section;
                $attendance = Attendance::where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear->id)->get();
                $coreValues = CoreValue::where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear->id)->get()->groupBy(['core_value', 'statement_key']);
                $isKindergarten = in_array(strtolower($selectedSection->gradeLevel->name ?? ''), ['kindergarten', 'kinder']);
                if ($isKindergarten) {
                    $kindergartenDomains = KindergartenDomain::where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear->id)->get()->groupBy(['domain', 'indicator_key']);
                } else {
                    $gradeLevelSubjects = $selectedSection->gradeLevel->subjects ?? collect();
                    $totalFinalGrade = 0; $gradedSubjectsCount = 0;
                    foreach ($gradeLevelSubjects as $subject) {
                        $allGrades = Grade::where(['student_id' => $selectedStudent->id, 'subject_id' => $subject->id, 'school_year_id' => $activeSchoolYear->id, 'component_type' => 'final_grade'])->get()->keyBy('quarter');
                        $q1 = $allGrades->get(1)?->final_grade; $q2 = $allGrades->get(2)?->final_grade; $q3 = $allGrades->get(3)?->final_grade; $q4 = $allGrades->get(4)?->final_grade;
                        $yearEndGrade = $allGrades->get(null)?->final_grade ?? $allGrades->get(0)?->final_grade;
                        $finalGrade = $yearEndGrade;
                        if (!$finalGrade) {
                            $quarters = array_filter([$q1, $q2, $q3, $q4], fn($q) => $q !== null);
                            if (count($quarters) > 0) { $finalGrade = round(array_sum($quarters) / count($quarters)); }
                        }
                        $remarks = '';
                        if ($finalGrade !== null) { $remarks = $finalGrade >= 75 ? 'Passed' : 'Failed'; $totalFinalGrade += $finalGrade; $gradedSubjectsCount++; }
                        $subjectGrades->push(['subject_name' => $subject->name, 'quarter_1' => $q1, 'quarter_2' => $q2, 'quarter_3' => $q3, 'quarter_4' => $q4, 'final_grade' => $finalGrade, 'remarks' => $remarks]);
                    }
                    if ($gradedSubjectsCount > 0) { $generalAverage = round($totalFinalGrade / $gradedSubjectsCount); }
                }
                if ($selectedSection && $selectedSection->teacher?->user) {
                    $u = $selectedSection->teacher->user;
                    $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
                } elseif ($selectedSection && $selectedSection->teacher) {
                    $adviserName = $selectedSection->teacher->name ?? '';
                }
            }
        }
        $html = view('admin.reports.sf9', compact('selectedStudent','selectedSection','enrollment','grades','attendance','coreValues','signature','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','schoolDistrict','isKindergarten','adviserName','kindergartenDomains','kinderConfig','lang','subjectGrades','generalAverage'))->render();
        return ['title' => 'SF9 - Report Card', 'html' => $html, 'rows' => [], 'summary' => []];
    }

    /**
     * Generate SF10 - Permanent Record
     */
    protected function generateSf10(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $selectedStudent = !empty($parameters['student_id']) ? Student::with('user')->find($parameters['student_id']) : null;
        $academicRecords = collect(); $currentSection = null; $isKindergarten = false; $kindergartenDomains = collect(); $kinderConfig = config('kindergarten.domains');
        if ($selectedStudent) {
            $currentEnrollment = Enrollment::with('section.gradeLevel')->where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear?->id)->first();
            $currentSection = $currentEnrollment?->section;
            $isKindergarten = $currentSection && in_array(strtolower($currentSection->gradeLevel->name ?? ''), ['kindergarten', 'kinder']);
            if ($isKindergarten) {
                $kindergartenDomains = KindergartenDomain::where('student_id', $selectedStudent->id)->where('school_year_id', $activeSchoolYear?->id)->get()->groupBy(['domain', 'indicator_key']);
            } else {
                $academicRecords = Grade::with(['subject', 'schoolYear', 'section.gradeLevel'])->where('student_id', $selectedStudent->id)->where('component_type', 'final_grade')->orderBy('school_year_id', 'desc')->get()->groupBy('school_year_id');
            }
        }
        $html = view('admin.reports.sf10', compact('selectedStudent','academicRecords','currentSection','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','schoolDistrict','isKindergarten','kindergartenDomains','kinderConfig'))->render();
        return ['title' => 'SF10 - Permanent Record', 'html' => $html, 'rows' => [], 'summary' => []];
    }

    /**
     * Generate Kindergarten Assessment - Read-only developmental domain ratings
     */
    protected function generateKindergartenAssessment(array $parameters): array
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() ?? SchoolYear::latest('start_date')->first();
        $schoolSettings = \App\Models\Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $selectedSection = !empty($parameters['section_id']) ? Section::with(['gradeLevel', 'teacher.user'])->find($parameters['section_id']) : null;
        $enrollments = collect(); $assessments = collect(); $adviserName = '';
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])->where('section_id', $selectedSection->id)->where('school_year_id', $activeSchoolYear->id)->where('status', 'enrolled')->whereHas('student', fn($q) => $q->whereNotIn('status', ['completed', 'inactive']))->get()->sortBy(fn($e) => [in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']) ? 0 : 1, $e->student->user->last_name ?? '', $e->student->user->first_name ?? ''])->values();
            if ($enrollments->isNotEmpty()) {
                $assessments = KindergartenDomain::whereIn('student_id', $enrollments->pluck('student.id'))->where('school_year_id', $activeSchoolYear->id)->get()->groupBy('student_id');
            }
            if ($selectedSection->teacher?->user) {
                $u = $selectedSection->teacher->user;
                $adviserName = trim(($u->last_name ?? '').', '.($u->first_name ?? '').' '.($u->middle_name ?? '')) ?: ($u->name ?? '');
            } else { $adviserName = $selectedSection->teacher->name ?? ''; }
        }
        $kinderConfig = config('kindergarten.domains');
        $ratingScale = config('kindergarten.rating_scale');
        $quarters = ['q1'=>'Q1','q2'=>'Q2','q3'=>'Q3','q4'=>'Q4'];
        $html = view('admin.reports.kindergarten_assessment', compact('selectedSection','adviserName','enrollments','assessments','kinderConfig','ratingScale','quarters','activeSchoolYear','schoolId','schoolName','schoolHead','schoolRegion','schoolDivision','schoolDistrict'))->render();
        return ['title' => 'Kindergarten Assessment', 'html' => $html, 'rows' => [], 'summary' => ['Total Students' => $enrollments->count()]];
    }

    /**
     * Helper: Proficiency level from final grade
     */
    protected function getProficiencyLevel(float $grade): string
    {
        return match(true) {
            $grade >= 90 => 'Advanced',
            $grade >= 85 => 'Proficient',
            $grade >= 80 => 'Approaching Proficiency',
            $grade >= 75 => 'Developing',
            default => 'Beginning',
        };
    }

    /**
     * Helper: Promotion status from final grade and grades
     */
    protected function getPromotionStatus(float $finalAverage, $grades): string
    {
        if ($finalAverage >= 75) return 'Promoted';
        $failingCount = is_countable($grades) ? collect($grades)->where('final_grade', '<', 75)->count() : 0;
        return $failingCount >= 3 ? 'Retained' : 'Conditional';
    }

    /**
     * Helper: Number to words (1–99)
     */
    protected function numberToWords(int $number): string
    {
        $words = ['Zero','One','Two','Three','Four','Five','Six','Seven','Eight','Nine','Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];
        if ($number < 21) return $words[$number] ?? (string)$number;
        $tens = intdiv($number, 10);
        $ones = $number % 10;
        return $words[18 + $tens] . ($ones ? '-' . $words[$ones] : '');
    }

    /**
     * Helper: Remarks for SF6
     */
    protected function getRemarks(string $promotionStatus, float $finalAverage, $selectedSection): string
    {
        return match($promotionStatus) {
            'Promoted' => 'Promoted to Grade ' . (($selectedSection?->gradeLevel?->level ?? 0) + 1),
            'Retained' => 'Retained in Grade ' . ($selectedSection?->gradeLevel?->level ?? 1),
            default => 'For summer/remedial class',
        };
    }
}
