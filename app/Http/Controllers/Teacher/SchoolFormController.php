<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Section;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Grade;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Setting;
use App\Models\CoreValue;
use App\Models\KindergartenDomain;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Book;
use App\Models\BookInventory;
use App\Models\TeachingProgram;
use App\Models\StudentHealthRecord;
use App\Services\FinalizationService;

class SchoolFormController extends Controller
{
    protected $finalizationService;

    public function __construct(FinalizationService $finalizationService)
    {
        $this->finalizationService = $finalizationService;
    }

    /**
     * Get logged-in teacher sections
     */
    private function getTeacherSections()
    {
        $teacher = Auth::user()->teacher;

        return Section::with(['students.user', 'gradeLevel', 'teacher.user'])
            ->where('teacher_id', $teacher->id)
            ->where('is_active', 1)
            ->get();
    }

    /**
     * SF1 - School Register (Student List)
     */


public function sf1(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year from is_active flag
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';
    $schoolYearStart = $activeSchoolYear ? Carbon::parse($activeSchoolYear->start_date)->year : Carbon::now()->year;
    
    // Get school settings
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    $schoolAddress = $schoolSettings['school_address'] ?? '';
    
    // Get selected section with teacher
    $selectedSection = $request->section_id 
        ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
        : $sections->first();

    // Get adviser name from section's teacher
    $adviserName = '';
    if ($selectedSection && $selectedSection->teacher) {
        $teacherUser = $selectedSection->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedSection->teacher->name ?? '';
        }
    }

    // Get enrolled students
    $enrollments = collect();
    $maleCount = 0;
    $femaleCount = 0;

    if ($selectedSection && $activeSchoolYear) {
        $enrollments = Enrollment::with(['student.user'])
            ->where('section_id', $selectedSection->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                $student = $enrollment->student;
                if (!$student) {
                    return [2, '', ''];
                }
                
                $gender = strtoupper($student->gender ?? '');
                $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                
                $user = $student->user;
                $lastName = $user->last_name ?? '';
                $firstName = $user->first_name ?? '';
                
                return [$genderOrder, $lastName, $firstName];
            })
            ->values();

        // Calculate age for each student
        foreach ($enrollments as $enrollment) {
            $student = $enrollment->student;
            if ($student && $student->birthdate) {
                $age = $this->calculateAge($student->birthdate, $schoolYearStart);
                $student->calculated_age = $age;
            }
        }

        // Count gender
        $maleCount = $enrollments->filter(function ($e) {
            $gender = strtoupper($e->student->gender ?? '');
            return $gender == 'MALE' || $gender == 'M';
        })->count();

        $femaleCount = $enrollments->filter(function ($e) {
            $gender = strtoupper($e->student->gender ?? '');
            return $gender == 'FEMALE' || $gender == 'F';
        })->count();
    }

    return view('teacher.school-forms.sf1', compact(
        'sections',
        'selectedSection',
        'adviserName',
        'enrollments',
        'schoolYear',
        'schoolYearStart',
        'schoolId',
        'schoolName',
        'schoolDivision',
        'schoolRegion',
        'schoolHead',
        'maleCount',
        'femaleCount',
        'activeSchoolYear'
    ));
}

/**
 * Calculate age as of first Friday of June
 */
public function calculateAge($birthDate, $year)
{
    if (!$birthDate) {
        return '';
    }
    
    try {
        $birth = Carbon::parse($birthDate);
        $juneFirst = Carbon::create($year, 6, 1);
        
        // Find first Friday of June
        if ($juneFirst->isFriday()) {
            $firstFriday = $juneFirst;
        } else {
            $firstFriday = $juneFirst->copy()->next(Carbon::FRIDAY);
        }
        
        return floor($birth->diffInYears($firstFriday));
        
    } catch (\Exception $e) {
        return '';
    }
}

    /**
     * SF2 - Daily Attendance
     */
   
# Create the updated SF2 Controller method



public function sf2(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year from is_active flag (not from settings)
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';
    
    // Get school settings (only for school info, not for active year)
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    
    // Get selected section
    $selectedSection = $request->section_id 
        ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
        : $sections->first();
    
    // Get selected month (default to June)
    $selectedMonth = $request->month ?? 'June';
    
    // Get adviser name from section's teacher
    $adviserName = '';
    if ($selectedSection && $selectedSection->teacher) {
        $teacherUser = $selectedSection->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedSection->teacher->name ?? '';
        }
    }

    // Get enrolled students sorted by gender (Male first) then alphabetically
    $enrollments = collect();
    
    if ($selectedSection && $activeSchoolYear) {
        $enrollments = Enrollment::with(['student.user'])
            ->where('section_id', $selectedSection->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                $student = $enrollment->student;
                if (!$student) {
                    return [2, '', ''];
                }
                
                $gender = strtoupper($student->gender ?? '');
                $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                
                $user = $student->user;
                $lastName = $user->last_name ?? '';
                $firstName = $user->first_name ?? '';
                
                return [$genderOrder, $lastName, $firstName];
            })
            ->values();
    }

    // Month and year for view (set early)
    // School year spans across calendar years (e.g., June 2024 - March 2025)
    // For months June-Dec: use start_date year, for Jan-Mar: use end_date year
    $monthNum = date('n', strtotime($selectedMonth));
    if ($activeSchoolYear) {
        $startYear = Carbon::parse($activeSchoolYear->start_date)->year;
        // If end_date is not set, assume it's the next year (typical school year)
        $endYear = $activeSchoolYear->end_date 
            ? Carbon::parse($activeSchoolYear->end_date)->year 
            : $startYear + 1;
        // Months 1-3 (Jan-Mar) use end year, months 6-12 (June-Dec) use start year
        $year = ($monthNum >= 1 && $monthNum <= 3) ? $endYear : $startYear;
    } else {
        $year = date('Y');
    }
    
    // Get attendances for the selected month
    $attendances = collect();
    $schoolDaysConfig = null;
    
    \Log::info('SF2 Debug', [
        'has_selected_section' => (bool)$selectedSection,
        'has_active_school_year' => (bool)$activeSchoolYear,
        'enrollments_count' => $enrollments->count(),
        'enrollments_is_empty' => $enrollments->isEmpty(),
        'month_num' => $monthNum,
        'year' => $year,
    ]);
    
    if ($selectedSection && $activeSchoolYear && $enrollments->isNotEmpty()) {
        // Get student IDs
        $studentIds = $enrollments->pluck('student.id')->toArray();
        
        \Log::info('SF2 Student IDs', ['student_ids' => $studentIds]);
        
        // Get attendances - filter by section and students for the selected month
        $attendances = Attendance::where('section_id', $selectedSection->id)
            ->whereIn('student_id', $studentIds)
            ->whereMonth('date', $monthNum)
            ->get();
        
        // Debug log
        \Log::info('SF2 Attendance Query', [
            'section_id' => $selectedSection->id,
            'student_ids' => $studentIds,
            'month' => $monthNum,
            'count' => $attendances->count(),
            'sample' => $attendances->first() ? [
                'student_id' => $attendances->first()->student_id,
                'date' => $attendances->first()->date,
                'status' => $attendances->first()->status,
            ] : null,
        ]);
        
        // Get school days configuration
        $schoolDaysConfig = \App\Models\AttendanceSchoolDay::where([
            'section_id' => $selectedSection->id,
            'school_year_id' => $activeSchoolYear->id,
            'month' => $monthNum,
            'year' => Carbon::now()->year,
        ])->first();
    } else {
        \Log::warning('SF2 Skipped attendance query', [
            'reason' => 'Missing required data',
            'has_section' => (bool)$selectedSection,
            'has_school_year' => (bool)$activeSchoolYear,
            'has_enrollments' => $enrollments->isNotEmpty(),
        ]);
    }

    // Calculate summary statistics
    $lateEnrollments = 0;
    $consecutiveAbsences = 0;
    $dropoutMale = 0;
    $dropoutFemale = 0;
    $transferredOutMale = 0;
    $transferredOutFemale = 0;
    $transferredInMale = 0;
    $transferredInFemale = 0;
    $averageDailyAttendance = '';
    $attendancePercentage = '';

    return view('teacher.school-forms.sf2', compact(
        'sections',
        'selectedSection',
        'adviserName',
        'enrollments',
        'attendances',
        'schoolYear',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolHead',
        'selectedMonth',
        'lateEnrollments',
        'consecutiveAbsences',
        'dropoutMale',
        'dropoutFemale',
        'transferredOutMale',
        'transferredOutFemale',
        'transferredInMale',
        'transferredInFemale',
        'averageDailyAttendance',
        'attendancePercentage',
        'schoolDaysConfig',
        'monthNum',
        'year'
    ));
}
 
/**
 * SF3 - Books Issued and Returned
 */
public function sf3(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year from is_active flag
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';
    
    // Get school settings
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    
    // Get selected section
    $selectedSection = $request->section_id 
        ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
        : $sections->first();
    
    // Get adviser name from section's teacher
    $adviserName = '';
    if ($selectedSection && $selectedSection->teacher) {
        $teacherUser = $selectedSection->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedSection->teacher->name ?? '';
        }
    }

    // Get enrolled students sorted by gender (Male first) then alphabetically
    $enrollments = collect();
    $books = collect();
    $bookInventories = collect();

    if ($selectedSection && $activeSchoolYear) {
        $enrollments = Enrollment::with(['student.user'])
            ->where('section_id', $selectedSection->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get();

        // Separate Male and Female
        $males = $enrollments->filter(function ($enrollment) {
            $gender = strtoupper($enrollment->student?->gender ?? '');
            return $gender === 'MALE' || $gender === 'M';
        })->sortBy(function ($enrollment) {
            return [
                $enrollment->student?->user?->last_name ?? '',
                $enrollment->student?->user?->first_name ?? ''
            ];
        });

        $females = $enrollments->filter(function ($enrollment) {
            $gender = strtoupper($enrollment->student?->gender ?? '');
            return $gender === 'FEMALE' || $gender === 'F';
        })->sortBy(function ($enrollment) {
            return [
                $enrollment->student?->user?->last_name ?? '',
                $enrollment->student?->user?->first_name ?? ''
            ];
        });

        // Merge: Male first, then Female
        $enrollments = $males->concat($females)->values();

        // Get books for enrolled students
        if ($enrollments->isNotEmpty()) {
            $studentIds = $enrollments->pluck('student.id')->filter()->values();
            
            $books = Book::whereIn('student_id', $studentIds)
                ->where('school_year_id', $activeSchoolYear->id)
                ->get()
                ->groupBy('student_id');
        }

        // Get book inventory for the grade level
        if ($selectedSection->gradeLevel) {
            $bookInventories = BookInventory::where('grade_level', $selectedSection->gradeLevel->name)
                ->orWhere('grade_level', 'All')
                ->orderBy('subject_area')
                ->orderBy('title')
                ->get();
        }
    }

    // Calculate summary statistics
    $totalBooksIssued = 0;
    $totalBooksReturned = 0;
    $totalBooksDamaged = 0;
    $totalBooksLost = 0;
    
    foreach ($books as $studentBooks) {
        foreach ($studentBooks as $book) {
            $totalBooksIssued++;
            if ($book->date_returned) {
                $totalBooksReturned++;
            }
            if ($book->status == 'damaged') {
                $totalBooksDamaged++;
            }
            if ($book->status == 'lost') {
                $totalBooksLost++;
            }
        }
    }

    return view('teacher.school-forms.sf3', compact(
        'sections',
        'selectedSection',
        'adviserName',
        'enrollments',
        'books',
        'bookInventories',
        'schoolYear',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolDivision',
        'schoolDistrict',
        'schoolRegion',
        'totalBooksIssued',
        'totalBooksReturned',
        'totalBooksDamaged',
        'totalBooksLost'
    ));
}


     /**
     * SF4 - Monthly Attendance Report (Teacher Level)
     * Shows monthly summary per student based on SF2 data
     */
    public function sf4(Request $request)
    {
        $sections = $this->getTeacherSections();
        
        // Get active school year (same logic as SF2)
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }
        
        // Get school settings (same as SF2)
        $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
        
        // Get selected section and month
        $selectedSection = $request->section_id 
            ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
            : $sections->first();
            
        $selectedMonth = $request->month ?? 'June';
        
        // Get adviser name (same logic as SF2)
        $adviserName = '';
        if ($selectedSection && $selectedSection->teacher) {
            $teacherUser = $selectedSection->teacher->user;
            if ($teacherUser) {
                $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                              ($teacherUser->first_name ?? '') . ' ' . 
                              ($teacherUser->middle_name ?? '');
                $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
            } else {
                $adviserName = $selectedSection->teacher->name ?? '';
            }
        }

        // Get enrolled students - SAME FILTERS AS SF2 (status: 'enrolled', not 'completed'/'inactive')
        $enrollments = collect();
        $attendanceSummary = collect();
        
        if ($selectedSection && $activeSchoolYear) {
            $enrollments = Enrollment::with(['student.user'])
                ->where('section_id', $selectedSection->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->whereIn('status', ['enrolled', 'completed'])
                ->get()
                ->sortBy(function ($enrollment) {
                    $student = $enrollment->student;
                    if (!$student) {
                        return [2, '', ''];
                    }
                    
                    $gender = strtoupper($student->gender ?? '');
                    $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                    
                    $user = $student->user;
                    $lastName = $user->last_name ?? '';
                    $firstName = $user->first_name ?? '';
                    
                    return [$genderOrder, $lastName, $firstName];
                })
                ->values();

            // Calculate month date range
            $year = Carbon::parse($activeSchoolYear->start_date)->year;
            $monthNum = date('n', strtotime($selectedMonth));
            
            // Get all school days (Mon-Fri) in the month
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $monthNum, $year);
            $schoolDays = [];
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $monthNum, $day);
                if (!$date->isWeekend()) {
                    $schoolDays[] = $day;
                }
            }

            $totalSchoolDays = count($schoolDays);

            // Get attendances for this section and month
            $attendances = collect();
            if ($enrollments->isNotEmpty()) {
                $attendances = Attendance::whereIn(
                    'student_id',
                    $enrollments->pluck('student.id')
                )
                ->whereYear('date', $year)
                ->whereMonth('date', $monthNum)
                ->get();
            }

            // Calculate attendance summary for each student
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student;
                $user = $student->user ?? null;
                
                $fullName = ($user->last_name ?? '') . ', ' . 
                           ($user->first_name ?? '') . ' ' . 
                           ($user->middle_name ?? '');
                
                $present = 0;
                $absent = 0;
                $tardy = 0;
                $recordedDays = 0;
                
                foreach ($schoolDays as $day) {
                    $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                    
                    $attendance = $attendances->first(function($a) use ($student, $dateStr) {
                        return $a->student_id == $student->id && $a->date == $dateStr;
                    });
                    
                    if ($attendance) {
                        $recordedDays++;
                        switch ($attendance->status) {
                            case 'present':
                                $present++;
                                break;
                            case 'absent':
                                $absent++;
                                break;
                            case 'tardy':
                                $tardy++;
                                break;
                        }
                    }
                    // No record = skip (don't count as anything)
                }

                $attendanceSummary->push([
                    'enrollment' => $enrollment,
                    'student' => $student,
                    'full_name' => $fullName,
                    'gender' => strtoupper($student->gender ?? '') == 'MALE' || strtoupper($student->gender ?? '') == 'M' ? 'M' : 'F',
                    'present' => $present,
                    'absent' => $absent,
                    'tardy' => $tardy,
                    'total_days' => $recordedDays,
                    'attendance_rate' => $recordedDays > 0 ? round(($present / $recordedDays) * 100, 1) : 0
                ]);
            }
        }

        // Calculate summary statistics for the view
        $maleSummary = $attendanceSummary->filter(function($item) {
            return $item['gender'] == 'M';
        });
        
        $femaleSummary = $attendanceSummary->filter(function($item) {
            return $item['gender'] == 'F';
        });

        $monthlyStats = [
            'total_school_days' => $totalSchoolDays ?? 0,
            'total_students' => $attendanceSummary->count(),
            'male_count' => $maleSummary->count(),
            'female_count' => $femaleSummary->count(),
            'male_avg_attendance' => $maleSummary->avg('attendance_rate') ?? 0,
            'female_avg_attendance' => $femaleSummary->avg('attendance_rate') ?? 0,
            'overall_avg_attendance' => $attendanceSummary->avg('attendance_rate') ?? 0,
            'total_absences' => $attendanceSummary->sum('absent'),
            'total_tardy' => $attendanceSummary->sum('tardy'),
        ];

        return view('teacher.school-forms.sf4', compact(
            'sections',
            'selectedSection',
            'adviserName',
            'enrollments',
            'attendanceSummary',
            'activeSchoolYear',
            'schoolId',
            'schoolName',
            'schoolHead',
            'selectedMonth',
            'monthlyStats'
        ));
    }

public function sf5(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year from is_active flag (not from settings)
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';
    
    // Get school settings (only for school info, not for active year)
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    
    // Get selected section
    $selectedSection = $request->section_id 
        ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
        : $sections->first();
    
    // Get adviser name from section's teacher
    $adviserName = '';
    if ($selectedSection && $selectedSection->teacher) {
        $teacherUser = $selectedSection->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedSection->teacher->name ?? '';
        }
    }

    // Get enrolled students sorted by gender (Male first) then alphabetically
    $enrollments = collect();
    $grades = collect();

    if ($selectedSection && $activeSchoolYear) {
        $enrollments = Enrollment::with(['student.user'])
            ->where('section_id', $selectedSection->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                $student = $enrollment->student;
                if (!$student) {
                    return [2, '', ''];
                }
                
                $gender = strtoupper($student->gender ?? '');
                $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                
                $user = $student->user;
                $lastName = $user->last_name ?? '';
                $firstName = $user->first_name ?? '';
                
                return [$genderOrder, $lastName, $firstName];
            })
            ->values();

        // Get grades for enrolled students
        if ($enrollments->isNotEmpty()) {
            $grades = Grade::whereIn(
                'student_id',
                $enrollments->pluck('student.id')
            )->get();
        }
    }

    return view('teacher.school-forms.sf5', compact(
        'sections',
        'selectedSection',
        'adviserName',
        'enrollments',
        'grades',
        'schoolYear',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolDivision',
        'schoolRegion',
        'schoolDistrict',
        'schoolHead'
    ));
}

/**
 * Calculate Final Grade
 */
public function calculateFinal($grade)
{
    $ww = $grade->written_works_avg ?? 0;
    $pt = $grade->performance_tasks_avg ?? 0;

    return round(($ww * 0.4) + ($pt * 0.6), 2);
}

    
 public function sf6(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    // Get school settings (only for school info, not for active year)
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    
    // Selected section
    $selectedSection = $request->section_id 
        ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
        : $sections->first();
        
    // Adviser name
    $adviserName = '';
    if ($selectedSection && $selectedSection->teacher) {
        $teacherUser = $selectedSection->teacher->user;

        if ($teacherUser) {
            $adviserName = trim(
                ($teacherUser->last_name ?? '') . ', ' .
                ($teacherUser->first_name ?? '') . ' ' .
                ($teacherUser->middle_name ?? '')
            ) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedSection->teacher->name ?? '';
        }
    }

    $enrollments = collect();
    $promotionData = collect();

    if ($selectedSection && $activeSchoolYear) {

        // ✅ FIXED: use student.grades instead of enrollment.grades
        $enrollments = Enrollment::with([
                'student.user',
                'student.grades.subject'
            ])
            ->where('section_id', $selectedSection->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->whereIn('status', ['enrolled', 'completed'])
            ->get()
            ->sortBy(function ($enrollment) {
                $student = $enrollment->student;

                if (!$student) return [2, '', ''];

                $gender = strtoupper($student->gender ?? '');
                $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;

                $user = $student->user;
                $lastName = $user->last_name ?? '';
                $firstName = $user->first_name ?? '';

                return [$genderOrder, $lastName, $firstName];
            })
            ->values();

        foreach ($enrollments as $enrollment) {

            $student = $enrollment->student;
            $user = $student->user ?? null;

            $fullName = trim(
                ($user->last_name ?? '') . ', ' .
                ($user->first_name ?? '') . ' ' .
                ($user->middle_name ?? '')
            );

            // ✅ FIXED: get grades via student
            $grades = $student->grades ?? collect();

            // Final average
            $finalAverage = round($grades->avg('final_grade') ?? 0);

            // Proficiency
            $proficiencyLevel = $this->getProficiencyLevel($finalAverage);

            // Promotion
            $promotionStatus = $this->getPromotionStatus($finalAverage, $grades);

            // Words
            $generalAverageWords = $this->numberToWords($finalAverage);

            $promotionData->push([
                'enrollment' => $enrollment,
                'student' => $student,
                'full_name' => $fullName,
                'gender' => (strtoupper($student->gender ?? '') == 'MALE' || strtoupper($student->gender ?? '') == 'M') ? 'M' : 'F',
                'final_average' => $finalAverage,
                'proficiency_level' => $proficiencyLevel,
                'promotion_status' => $promotionStatus,
                'general_average_words' => $generalAverageWords,
                'grades' => $grades,
                'remarks' => $this->getRemarks($promotionStatus, $finalAverage, $selectedSection)
            ]);
        }
    }

    // Summary
    $maleData = $promotionData->where('gender', 'M');
    $femaleData = $promotionData->where('gender', 'F');

    $summaryStats = [
        'total_students' => $promotionData->count(),
        'male_count' => $maleData->count(),
        'female_count' => $femaleData->count(),

        'promoted_male' => $maleData->where('promotion_status', 'Promoted')->count(),
        'promoted_female' => $femaleData->where('promotion_status', 'Promoted')->count(),
        'conditional_male' => $maleData->where('promotion_status', 'Conditional')->count(),
        'conditional_female' => $femaleData->where('promotion_status', 'Conditional')->count(),
        'retained_male' => $maleData->where('promotion_status', 'Retained')->count(),
        'retained_female' => $femaleData->where('promotion_status', 'Retained')->count(),

        'beginning_male' => $maleData->where('proficiency_level', 'Beginning')->count(),
        'beginning_female' => $femaleData->where('proficiency_level', 'Beginning')->count(),
        'developing_male' => $maleData->where('proficiency_level', 'Developing')->count(),
        'developing_female' => $femaleData->where('proficiency_level', 'Developing')->count(),
        'approaching_male' => $maleData->where('proficiency_level', 'Approaching Proficiency')->count(),
        'approaching_female' => $femaleData->where('proficiency_level', 'Approaching Proficiency')->count(),
        'proficient_male' => $maleData->where('proficiency_level', 'Proficient')->count(),
        'proficient_female' => $femaleData->where('proficiency_level', 'Proficient')->count(),
        'advanced_male' => $maleData->where('proficiency_level', 'Advanced')->count(),
        'advanced_female' => $femaleData->where('proficiency_level', 'Advanced')->count(),
    ];

    return view('teacher.school-forms.sf6', compact(
        'sections',
        'selectedSection',
        'adviserName',
        'enrollments',
        'promotionData',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolHead',
        'schoolRegion',
        'schoolDivision',
        'summaryStats'
    ));
}
private function getPromotionStatus($finalAverage, $grades)
{
    $hasIncomplete = $grades->contains(function ($g) {
        return is_null($g->final_grade);
    });

    if ($hasIncomplete) return 'Incomplete';

    return $finalAverage >= 75 ? 'Promoted' : 'Retained';
}
/**
 * Get proficiency level based on final grade
 */
private function getProficiencyLevel($grade)
{
    if ($grade >= 90) return 'Advanced';
    if ($grade >= 85) return 'Proficient';
    if ($grade >= 80) return 'Approaching Proficiency';
    if ($grade >= 75) return 'Developing';
    return 'Beginning';
}
/**
 * Convert number to words (simplified)
 */
private function numberToWords($number)
{
    $words = [
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
        18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty',
        30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
        60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety',
        100 => 'One Hundred'
    ];

    if (isset($words[$number])) {
        return $words[$number];
    }

    if ($number < 100) {
        $tens = floor($number / 10) * 10;
        $ones = $number % 10;

        return $words[$tens] . '-' . strtolower($words[$ones]);
    }

    return (string) $number;
}
private function getRemarks($status, $finalAverage, $selectedSection)
{
    if ($status == 'Retained') {
        return 'Retained in ' . ($selectedSection->gradeLevel->name ?? 'same grade');
    } elseif ($status == 'Promoted') {
        return 'Promoted to next grade level';
    } elseif ($status == 'Conditional') {
        return 'Promoted with deficiencies';
    }
    return '';
}

    /**
     * SF7 - School Personnel Assignment List and Basic Profile
     * Official DepEd SF7 template — school-wide personnel listing
     */
    public function sf7(Request $request)
    {
        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }

        // School settings
        $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
        $schoolId = $schoolSettings['deped_school_id'] ?? '';
        $schoolName = $schoolSettings['school_name'] ?? '';
        $schoolDivision = $schoolSettings['school_division'] ?? '';
        $schoolRegion = $schoolSettings['school_region'] ?? '';
        $schoolDistrict = $schoolSettings['school_district'] ?? '';
        $schoolHead = $schoolSettings['school_head']
            ?? Setting::where('key', 'school_head')->value('value')
            ?? '';

        // Load ALL school personnel (teachers) for the active school year
        $personnel = Teacher::with(['user', 'sections.gradeLevel'])
            ->whereNotIn('status', ['inactive'])
            ->orderByRaw("
                FIELD(position, 'School Principal', 'Principal', 'Head Teacher', 'Master Teacher II', 'Master Teacher I', 'Teacher III', 'Teacher II', 'Teacher I'),
                last_name ASC
            ")
            ->get();

        // Load teaching programs for all personnel in active year
        $allPrograms = TeachingProgram::with(['teacher', 'section.gradeLevel'])
            ->where('school_year_id', $activeSchoolYear?->id)
            ->orderByRaw("FIELD(day, 'M', 'T', 'W', 'TH', 'F')")
            ->orderBy('time_from')
            ->get()
            ->groupBy('teacher_id');

        // Build personnel rows
        $personnelList = $personnel->map(function ($teacher) use ($allPrograms, $activeSchoolYear) {
            $user = $teacher->user;
            $programs = $allPrograms->get($teacher->id, collect());

            // Format subjects taught (include grade & section, advisory, ancillary)
            $subjectsTaught = collect();
            $advisoryClasses = $teacher->sections->pluck('name')->filter()->unique()->values();

            foreach ($programs as $prog) {
                $gradeSection = '';
                if ($prog->section && $prog->section->gradeLevel) {
                    $gradeSection = $prog->section->gradeLevel->name . ' - ' . $prog->section->name;
                }
                $subjectsTaught->push([
                    'subject' => $prog->subject ?? $prog->activity ?? 'Teaching',
                    'grade_section' => $gradeSection,
                ]);
            }

            // Remove duplicate subject entries
            $subjectsTaught = $subjectsTaught->unique(function ($item) {
                return $item['subject'] . '|' . $item['grade_section'];
            })->values();

            // Format daily program
            $dailyProgram = $programs->map(function ($prog) {
                return [
                    'day' => $prog->day,
                    'from' => $prog->time_from ? Carbon::parse($prog->time_from)->format('h:i A') : '',
                    'to' => $prog->time_to ? Carbon::parse($prog->time_to)->format('h:i A') : '',
                    'minutes' => $prog->minutes ?? 0,
                ];
            });

            $totalMinutes = $dailyProgram->sum('minutes');

            return [
                'employee_no' => $teacher->teacher_id ?? $teacher->deped_id ?? $teacher->tin_id ?? 'N/A',
                'tin' => $teacher->tin_id ?? '',
                'full_name' => $user ? trim("{$user->last_name}, {$user->first_name} {$user->middle_name}") : $teacher->full_name,
                'last_name' => $user->last_name ?? $teacher->last_name ?? '',
                'first_name' => $user->first_name ?? $teacher->first_name ?? '',
                'middle_name' => $user->middle_name ?? $teacher->middle_name ?? '',
                'sex' => (strtoupper($user->gender ?? $teacher->gender ?? '') == 'MALE' || strtoupper($user->gender ?? $teacher->gender ?? '') == 'M') ? 'M' : 'F',
                'fund_source' => 'National', // Default; no fund_source column exists
                'position' => $teacher->position ?? $teacher->designation ?? 'Teacher I',
                'nature_of_appointment' => $teacher->employment_status ?? 'Permanent',
                'highest_degree' => $teacher->highest_education ?? $teacher->degree_program ?? '',
                'major' => $teacher->major ?? '',
                'minor' => $teacher->minor ?? '',
                'subjects_taught' => $subjectsTaught,
                'advisory_classes' => $advisoryClasses,
                'daily_program' => $dailyProgram,
                'total_minutes' => $totalMinutes,
                'remarks' => $teacher->remarks ?? '',
            ];
        });

        // Summary counts for sections A, B, C
        // Teachers with no position set default to teaching staff
        $teachingCount = $personnel->filter(function ($t) {
            $pos = strtolower($t->position ?? $t->designation ?? '');
            return empty($pos)
                || str_contains($pos, 'teacher')
                || str_contains($pos, 'adviser')
                || str_contains($pos, 'head teacher')
                || str_contains($pos, 'master teacher')
                || str_contains($pos, 'principal');
        })->count();

        $nonTeachingCount = $personnel->filter(function ($t) {
            $pos = strtolower($t->position ?? $t->designation ?? '');
            if (empty($pos)) return false; // defaults to teaching
            return !str_contains($pos, 'teacher')
                && !str_contains($pos, 'adviser')
                && !str_contains($pos, 'head teacher')
                && !str_contains($pos, 'master teacher')
                && !str_contains($pos, 'principal');
        })->count();

        return view('teacher.school-forms.sf7', compact(
            'activeSchoolYear',
            'schoolId',
            'schoolName',
            'schoolHead',
            'schoolRegion',
            'schoolDivision',
            'schoolDistrict',
            'personnelList',
            'teachingCount',
            'nonTeachingCount'
        ));
    }

    /**
     * Store teaching program
     */
    public function storeTeachingProgram(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'day' => 'required|in:M,T,W,TH,F',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'subject' => 'nullable|string|max:255',
            'activity' => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacher;
        $activeSchoolYear = SchoolYear::where('is_active', true)->first() 
            ?? SchoolYear::latest('start_date')->first();

        // Calculate minutes
        $from = Carbon::createFromFormat('H:i', $validated['time_from']);
        $to = Carbon::createFromFormat('H:i', $validated['time_to']);
        $minutes = $from->diffInMinutes($to);

        TeachingProgram::create([
            'teacher_id' => $teacher->id,
            'section_id' => $validated['section_id'],
            'school_year_id' => $activeSchoolYear->id,
            'day' => $validated['day'],
            'time_from' => $validated['time_from'],
            'time_to' => $validated['time_to'],
            'subject' => $validated['subject'],
            'activity' => $validated['activity'],
            'minutes' => $minutes,
        ]);

        return back()->with('success', 'Teaching program added successfully.');
    }

    /**
     * Update teaching program
     */
    public function updateTeachingProgram(Request $request, TeachingProgram $program)
    {
        // Verify ownership
        if ($program->teacher_id !== auth()->user()->teacher?->id) {
            abort(403);
        }

        $validated = $request->validate([
            'day' => 'required|in:M,T,W,TH,F',
            'time_from' => 'required|date_format:H:i',
            'time_to' => 'required|date_format:H:i|after:time_from',
            'subject' => 'nullable|string|max:255',
            'activity' => 'nullable|string',
        ]);

        // Calculate minutes
        $from = Carbon::createFromFormat('H:i', $validated['time_from']);
        $to = Carbon::createFromFormat('H:i', $validated['time_to']);
        $minutes = $from->diffInMinutes($to);

        $program->update([
            'day' => $validated['day'],
            'time_from' => $validated['time_from'],
            'time_to' => $validated['time_to'],
            'subject' => $validated['subject'],
            'activity' => $validated['activity'],
            'minutes' => $minutes,
        ]);

        return back()->with('success', 'Teaching program updated successfully.');
    }

    /**
     * Delete teaching program
     */
    public function deleteTeachingProgram(TeachingProgram $program)
    {
        if ($program->teacher_id !== auth()->user()->teacher?->id) {
            abort(403);
        }

        $program->delete();

        return back()->with('success', 'Teaching program deleted successfully.');
    }



       /**
     * SF8 - Learner's Basic Health and Nutrition Report
     */
    public function sf8(Request $request)
    {
        $sections = $this->getTeacherSections();
        
        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }
        
    // Get school settings (only for school info, not for active year)
    $schoolSettings = Setting::where('group', 'school')->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';


        
        // Get selected section and period
        $selectedSection = $request->section_id 
            ? Section::with(['gradeLevel', 'teacher.user'])->find($request->section_id)
            : $sections->first()?->load(['gradeLevel', 'teacher.user']);
            
        $selectedPeriod = $request->period ?? 'bosy'; // bosy or eosy
        
        // Get adviser name
        $adviserName = '';
        if ($selectedSection && $selectedSection->teacher) {
            $teacherUser = $selectedSection->teacher->user;
            if ($teacherUser) {
                $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                              ($teacherUser->first_name ?? '') . ' ' . 
                              ($teacherUser->middle_name ?? '');
                $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
            } else {
                $adviserName = $selectedSection->teacher->name ?? '';
            }
        }

        // Get enrolled students with health records
        $healthData = collect();
        $summaryStats = [];
        
        if ($selectedSection && $activeSchoolYear) {
            // Get enrolled students only (not completed/inactive)
            $enrollments = Enrollment::with(['student.user', 'student.healthRecords'])
                ->where('section_id', $selectedSection->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->whereIn('status', ['enrolled', 'completed'])
                ->get()
                ->sortBy(function ($enrollment) {
                    $student = $enrollment->student;
                    if (!$student) return [2, '', ''];
                    
                    $gender = strtoupper($student->gender ?? '');
                    $genderOrder = ($gender == 'MALE' || $gender == 'M') ? 0 : 1;
                    
                    $user = $student->user;
                    return [$genderOrder, $user->last_name ?? '', $user->first_name ?? ''];
                });

            // Process each student
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student;
                $user = $student->user ?? null;
                
                $fullName = ($user->last_name ?? '') . ', ' . 
                           ($user->first_name ?? '') . ' ' . 
                           ($user->middle_name ?? '');
                
                // Get birthdate and calculate age
                $birthdate = $student->birthdate ? Carbon::parse($student->birthdate) : null;
                $age = $birthdate ? $birthdate->age : '';
                $ageFormatted = $birthdate ? $birthdate->diff(Carbon::now())->format('%y.%m') : '';
                
                // Get existing health record for this period
                $healthRecord = $student->healthRecords
                    ->where('section_id', $selectedSection->id)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->where('period', $selectedPeriod)
                    ->first();
                
                $healthData->push([
                    'enrollment' => $enrollment,
                    'student' => $student,
                    'full_name' => $fullName,
                    'gender' => strtoupper($student->gender ?? '') == 'MALE' || strtoupper($student->gender ?? '') == 'M' ? 'M' : 'F',
                    'lrn' => $student->lrn ?? '',
                    'birthdate' => $birthdate ? $birthdate->format('m/d/Y') : '',
                    'age' => $age,
                    'age_formatted' => $ageFormatted,
                    'weight' => $healthRecord?->weight,
                    'height' => $healthRecord?->height,
                    'height_squared' => $healthRecord?->height ? round(pow($healthRecord->height, 2), 2) : null,
                    'bmi' => $healthRecord?->bmi,
                    'nutritional_status' => $healthRecord?->nutritional_status,
                    'height_for_age' => $healthRecord?->height_for_age,
                    'remarks' => $healthRecord?->remarks,
                    'health_record_id' => $healthRecord?->id,
                ]);
            }

            // Calculate summary statistics
            $maleData = $healthData->where('gender', 'M')->whereNotNull('bmi');
            $femaleData = $healthData->where('gender', 'F')->whereNotNull('bmi');
            
            $summaryStats = [
                'total_students' => $healthData->count(),
                'assessed_count' => $healthData->whereNotNull('bmi')->count(),
                'male_count' => $maleData->count(),
                'female_count' => $femaleData->count(),
                
                // Nutritional Status - Male
                'male_severely_wasted' => $maleData->where('nutritional_status', 'Severely Wasted')->count(),
                'male_wasted' => $maleData->where('nutritional_status', 'Wasted')->count(),
                'male_normal' => $maleData->where('nutritional_status', 'Normal')->count(),
                'male_overweight' => $maleData->where('nutritional_status', 'Overweight')->count(),
                'male_obese' => $maleData->where('nutritional_status', 'Obese')->count(),
                
                // Nutritional Status - Female
                'female_severely_wasted' => $femaleData->where('nutritional_status', 'Severely Wasted')->count(),
                'female_wasted' => $femaleData->where('nutritional_status', 'Wasted')->count(),
                'female_normal' => $femaleData->where('nutritional_status', 'Normal')->count(),
                'female_overweight' => $femaleData->where('nutritional_status', 'Overweight')->count(),
                'female_obese' => $femaleData->where('nutritional_status', 'Obese')->count(),
                
                // Height for Age - Male
                'male_severely_stunted' => $maleData->where('height_for_age', 'Severely Stunted')->count(),
                'male_stunted' => $maleData->where('height_for_age', 'Stunted')->count(),
                'male_normal_hfa' => $maleData->where('height_for_age', 'Normal')->count(),
                'male_tall' => $maleData->where('height_for_age', 'Tall')->count(),
                
                // Height for Age - Female
                'female_severely_stunted' => $femaleData->where('height_for_age', 'Severely Stunted')->count(),
                'female_stunted' => $femaleData->where('height_for_age', 'Stunted')->count(),
                'female_normal_hfa' => $femaleData->where('height_for_age', 'Normal')->count(),
                'female_tall' => $femaleData->where('height_for_age', 'Tall')->count(),
            ];
        }

        return view('teacher.school-forms.sf8', compact(
            'sections',
            'selectedSection',
            'selectedPeriod',
            'adviserName',
            'healthData',
            'activeSchoolYear',
            'schoolId',
            'schoolName',
            'schoolHead',
            'schoolRegion',
            'schoolDivision',
            'schoolDistrict',
            'summaryStats'
        ));
    }

    /**
     * Store or update health record
     */
    public function storeHealthRecord(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:sections,id',
            'period' => 'required|in:bosy,eosy',
            'weight' => 'required|numeric|min:0|max:200',
            'height' => 'required|numeric|min:0|max:3',
            'remarks' => 'nullable|string',
            'date_of_assessment' => 'required|date',
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', true)->first() 
            ?? SchoolYear::latest('start_date')->first();

        // Calculate BMI
        $weight = $validated['weight'];
        $height = $validated['height'];
        $bmi = $height > 0 ? round($weight / pow($height, 2), 2) : null;
        
        // Determine nutritional status based on BMI (WHO Child Growth Standards simplified)
        $nutritionalStatus = $this->getNutritionalStatus($bmi, $request->age);
        
        // Determine height for age (simplified - would need reference tables)
        $heightForAge = $this->getHeightForAge($height, $request->age, $request->gender);

        StudentHealthRecord::updateOrCreate(
            [
                'student_id' => $validated['student_id'],
                'section_id' => $validated['section_id'],
                'school_year_id' => $activeSchoolYear->id,
                'period' => $validated['period'],
            ],
            [
                'weight' => $weight,
                'height' => $height,
                'bmi' => $bmi,
                'nutritional_status' => $nutritionalStatus,
                'height_for_age' => $heightForAge,
                'remarks' => $validated['remarks'],
                'date_of_assessment' => $validated['date_of_assessment'],
                'assessed_by' => auth()->id(),
            ]
        );

        return back()->with('success', 'Health record saved successfully.');
    }

    /**
     * Delete health record
     */
    public function deleteHealthRecord(StudentHealthRecord $record)
    {
        // Verify teacher owns this section
        $teacher = auth()->user()->teacher;
        if ($record->section->teacher_id !== $teacher?->id) {
            abort(403);
        }

        $record->delete();
        return back()->with('success', 'Health record deleted.');
    }

    /**
     * Get nutritional status based on BMI
     */
    private function getNutritionalStatus($bmi, $age)
    {
        // Simplified WHO BMI-for-age categories (would need age/sex specific tables for accuracy)
        if ($bmi < 14) return 'Severely Wasted';
        if ($bmi < 15) return 'Wasted';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }

    /**
     * Get height for age status
     */
    private function getHeightForAge($height, $age, $gender)
    {
        // Simplified - would need WHO growth reference tables
        // This is placeholder logic
        if ($height < 1.20 && $age > 10) return 'Stunted';
        if ($height < 1.10 && $age > 8) return 'Severely Stunted';
        if ($height > 1.70 && $age < 15) return 'Tall';
        return 'Normal';
    }





public function sf9(Request $request)
{
    $sections = $this->getTeacherSections();
    
    // Get active school year from is_active flag (not from settings)
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';
    
    // Get school settings (only for school info, not for active year)
    $schoolSettings = Setting::whereIn('group', ['school', 'general'])->get()->keyBy('key')->map->value;
    
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    $schoolAddress = $schoolSettings['school_address'] ?? '';
    
    // Language setting for Kindergarten (cebuano or english) - persisted in session
    if ($request->has('lang')) {
        $lang = $request->get('lang');
        if (in_array($lang, ['cebuano', 'english'])) {
            session(['kindergarten_lang' => $lang]);
        }
    }
    $lang = session('kindergarten_lang', 'cebuano');
    
    // Get all students from teacher's sections
    $students = collect();
    foreach ($sections as $section) {
        $sectionStudents = Student::with(['user', 'section.gradeLevel'])
            ->whereHas('enrollments', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id)
                  ->whereIn('status', ['enrolled', 'completed']);
            })
            // Filter out students with completed or inactive status
            ->whereNotIn('status', ['completed', 'inactive'])
            ->where('section_id', $section->id)
            ->get();
        $students = $students->merge($sectionStudents);
    }
    $students = $students->unique('id')->sortBy('user.last_name');
    
    // Get selected student
    $selectedStudent = $request->student_id
        ? Student::with(['user', 'section.gradeLevel.subjects', 'section.teacher.user'])->find($request->student_id)
        : null;
    
    // Get adviser name
    $adviserName = '';
    if ($selectedStudent && $selectedStudent->section && $selectedStudent->section->teacher) {
        $teacherUser = $selectedStudent->section->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedStudent->section->teacher->name ?? '';
        }
    }

    // Get grades for selected student
    $subjectGrades = collect();
    $attendances = collect();
    $generalAverage = null;
    $coreValues = collect();
    $kindergartenDomains = collect();
    $isKindergarten = false;
    
    if ($selectedStudent) {
        // Check if student is in Kindergarten
        $gradeLevelName = $selectedStudent->section->gradeLevel->name ?? '';
        $isKindergarten = (stripos($gradeLevelName, 'kinder') !== false);
        
        // Get attendance records
        $attendances = Attendance::where('student_id', $selectedStudent->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->get();
        
        // Get core values records - GROUPED by core_value and quarter
        $coreValues = CoreValue::where('student_id', $selectedStudent->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->get()
            ->groupBy(['core_value', 'statement_key']);
        
        if ($isKindergarten) {
            // Get kindergarten developmental domains
            $kindergartenDomains = KindergartenDomain::where('student_id', $selectedStudent->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->get()
                ->groupBy(['domain', 'indicator_key']);
        } else {
            // Regular Grades 1-6 - Build subject grades array from grade level subjects
            $gradeLevelSubjects = $selectedStudent->section->gradeLevel->subjects ?? collect();
            
            $totalFinalGrade = 0;
            $gradedSubjectsCount = 0;
            
            foreach ($gradeLevelSubjects as $subject) {
                // Get all final_grade records for this subject (quarters 1-4 and year-end)
                $allGrades = Grade::where([
                    'student_id' => $selectedStudent->id,
                    'subject_id' => $subject->id,
                    'school_year_id' => $activeSchoolYear->id,
                    'component_type' => 'final_grade',
                ])->get()->keyBy('quarter');
                
                // Extract quarter grades (quarter 1-4)
                $q1 = $allGrades->get(1)?->final_grade;
                $q2 = $allGrades->get(2)?->final_grade;
                $q3 = $allGrades->get(3)?->final_grade;
                $q4 = $allGrades->get(4)?->final_grade;
                
                // Get year-end final grade (quarter = NULL or 0)
                $yearEndGrade = $allGrades->get(null)?->final_grade ?? $allGrades->get(0)?->final_grade;
                
                // If no year-end grade, calculate average of available quarters
                $finalGrade = $yearEndGrade;
                if (!$finalGrade) {
                    $quarters = array_filter([$q1, $q2, $q3, $q4], fn($q) => $q !== null);
                    if (count($quarters) > 0) {
                        $finalGrade = round(array_sum($quarters) / count($quarters));
                    }
                }
                
                $remarks = '';
                if ($finalGrade !== null) {
                    $remarks = $finalGrade >= 75 ? 'Passed' : 'Failed';
                    $totalFinalGrade += $finalGrade;
                    $gradedSubjectsCount++;
                }
                
                $subjectGrades->push([
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'subject_code' => $subject->code,
                    'quarter_1' => $q1,
                    'quarter_2' => $q2,
                    'quarter_3' => $q3,
                    'quarter_4' => $q4,
                    'final_grade' => $finalGrade,
                    'remarks' => $remarks,
                ]);
            }
            
            // Calculate general average
            $generalAverage = $gradedSubjectsCount > 0 ? round($totalFinalGrade / $gradedSubjectsCount) : null;
        }
    }

    return view('teacher.school-forms.sf9', compact(
        'students',
        'selectedStudent',
        'adviserName',
        'subjectGrades',
        'generalAverage',
        'attendances',
        'coreValues',
        'kindergartenDomains',
        'isKindergarten',
        'lang',
        'schoolYear',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolDivision',
        'schoolRegion',
        'schoolDistrict',
        'schoolHead'
    ));
}


    /**
     * SF10 - Learner's Permanent Academic Record (Form 137)
     */




public function sf10(Request $request)
{
    // Get teacher's sections
    $sections = $this->getTeacherSections();

    // Get active school year from is_active flag (not from settings)
    $activeSchoolYear = SchoolYear::where('is_active', true)->first();
    
    // If no active school year found, get the latest one
    if (!$activeSchoolYear) {
        $activeSchoolYear = SchoolYear::latest('start_date')->first();
    }
    
    $schoolYear = $activeSchoolYear->name ?? '';

    // School settings (only for school info, not for active year)
    $schoolSettings = Setting::whereIn('group', ['school', 'general'])
        ->get()->keyBy('key')->map->value;
    $schoolId = $schoolSettings['deped_school_id'] ?? '';
    $schoolName = $schoolSettings['school_name'] ?? '';
    $schoolDivision = $schoolSettings['school_division'] ?? '';
    $schoolRegion = $schoolSettings['school_region'] ?? '';
    $schoolDistrict = $schoolSettings['school_district'] ?? '';
    $schoolHead = $schoolSettings['school_head'] 
        ?? Setting::where('key', 'school_head')->value('value') 
        ?? '';
    $schoolAddress = $schoolSettings['school_address'] ?? '';
    
    // Language setting for Kindergarten (cebuano or english) - persisted in session
    if ($request->has('lang')) {
        $lang = $request->get('lang');
        if (in_array($lang, ['cebuano', 'english'])) {
            session(['kindergarten_lang' => $lang]);
        }
    }
    $lang = session('kindergarten_lang', 'cebuano');

    // Get all students from teacher's sections
    $students = collect();
    foreach ($sections as $section) {
        $sectionStudents = Student::with(['user', 'section.gradeLevel'])
            ->whereHas('enrollments', function($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id)
                  ->whereIn('status', ['enrolled', 'completed']);
            })
            // Filter out students with completed or inactive status
            ->whereNotIn('status', ['completed', 'inactive'])
            ->where('section_id', $section->id)
            ->get();
        $students = $students->merge($sectionStudents);
    }
    $students = $students->unique('id')->sortBy('user.last_name');

    // Selected student
    $selectedStudent = $request->student_id
        ? Student::with(['user', 'section.gradeLevel.subjects', 'section.teacher.user'])->find($request->student_id)
        : null;

    // Get adviser name
    $adviserName = '';
    if ($selectedStudent && $selectedStudent->section && $selectedStudent->section->teacher) {
        $teacherUser = $selectedStudent->section->teacher->user;
        if ($teacherUser) {
            $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                          ($teacherUser->first_name ?? '') . ' ' . 
                          ($teacherUser->middle_name ?? '');
            $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
        } else {
            $adviserName = $selectedStudent->section->teacher->name ?? '';
        }
    }

    // Define all elementary grade levels (Kinder to Grade 6)
    $allGradeLevels = ['Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'];
    
    // Get student's current grade level
    $currentGradeLevel = null;
    if ($selectedStudent && $selectedStudent->section && $selectedStudent->section->gradeLevel) {
        $currentGradeLevel = $selectedStudent->section->gradeLevel->name;
    }

    // Load data for all grade levels
    $subjectsByGrade = [];
    $historicalGrades = [];
    $schoolHistory = [];
    $kinderDomainsByGrade = []; // Store kindergarten domain data

    if ($selectedStudent) {
        // Get all grade levels from the database
        $gradeLevels = \App\Models\GradeLevel::whereIn('name', $allGradeLevels)
            ->orderByRaw("FIELD(name, 'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6')")
            ->get()
            ->keyBy('name');

        foreach ($allGradeLevels as $gradeLevelName) {
            $gradeLevel = $gradeLevels[$gradeLevelName] ?? null;
            $isKindergartenGrade = (stripos($gradeLevelName, 'kinder') !== false);
            
            if ($isKindergartenGrade) {
                // For Kindergarten, load developmental domains
                $kinderDomains = KindergartenDomain::where('student_id', $selectedStudent->id)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->get()
                    ->groupBy(['domain', 'indicator_key']);
                
                $kinderDomainsByGrade[$gradeLevelName] = $kinderDomains;
                $subjectsByGrade[$gradeLevelName] = collect(); // No subjects for kinder
                $historicalGrades[$gradeLevelName] = collect();
            } else {
                // For Grades 1-6, load subjects and grades
                if ($gradeLevel) {
                    $subjectsByGrade[$gradeLevelName] = Subject::where('grade_level_id', $gradeLevel->id)
                        ->orderBy('name')
                        ->get();
                } else {
                    $subjectsByGrade[$gradeLevelName] = collect();
                }

                // Get grades for this student in this grade level
                $grades = Grade::with(['subject', 'section.gradeLevel'])
                    ->where('student_id', $selectedStudent->id)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->whereHas('section.gradeLevel', function ($q) use ($gradeLevelName) {
                        $q->where('name', $gradeLevelName);
                    })
                    ->where('component_type', 'final_grade')
                    ->get();

                // Group grades by subject_id and quarter for easy access
                $groupedGrades = $grades->groupBy('subject_id')->map(function($subjectGrades) {
                    return $subjectGrades->keyBy('quarter');
                });

                $historicalGrades[$gradeLevelName] = $groupedGrades;
                $kinderDomainsByGrade[$gradeLevelName] = collect();
            }

            // Build school history for this grade level
            if ($isKindergartenGrade) {
                $kinderRecord = KindergartenDomain::where('student_id', $selectedStudent->id)
                    ->where('school_year_id', $activeSchoolYear->id)
                    ->first();
                
                $isCurrentGrade = ($gradeLevelName === $currentGradeLevel);
                $schoolHistory[$gradeLevelName] = (object)[
                    'school_name' => $schoolName,
                    'school_id' => $schoolId,
                    'district' => $schoolDistrict,
                    'division' => $schoolDivision,
                    'region' => $schoolRegion,
                    'section' => $isCurrentGrade ? ($selectedStudent->section->name ?? '') : '',
                    'school_year' => $isCurrentGrade ? $schoolYear : '',
                    'adviser' => $isCurrentGrade ? $adviserName : ''
                ];
            } else {
                // Get first grade record to extract section info
                // $historicalGrades is grouped by subject_id, then by quarter
                $subjectGrades = $historicalGrades[$gradeLevelName]->first();
                $gradeRecord = $subjectGrades ? $subjectGrades->first() : null;
                
                if ($gradeRecord && $gradeRecord->section) {
                    $sectionTeacher = $gradeRecord->section->teacher;
                    $teacherFullName = '';
                    if ($sectionTeacher && $sectionTeacher->user) {
                        $tUser = $sectionTeacher->user;
                        $teacherFullName = ($tUser->last_name ?? '') . ', ' . 
                                         ($tUser->first_name ?? '') . ' ' . 
                                         ($tUser->middle_name ?? '');
                        $teacherFullName = trim($teacherFullName) ?: ($tUser->name ?? '');
                    }
                    
                    $schoolHistory[$gradeLevelName] = (object)[
                        'school_name' => $schoolName,
                        'school_id' => $schoolId,
                        'district' => $schoolDistrict,
                        'division' => $schoolDivision,
                        'region' => $schoolRegion,
                        'section' => $gradeRecord->section->name ?? '',
                        'school_year' => $schoolYear,
                        'adviser' => $teacherFullName
                    ];
                } else {
                    $isCurrentGrade = ($gradeLevelName === $currentGradeLevel);
                    $schoolHistory[$gradeLevelName] = (object)[
                        'school_name' => $schoolName,
                        'school_id' => $schoolId,
                        'district' => $schoolDistrict,
                        'division' => $schoolDivision,
                        'region' => $schoolRegion,
                        'section' => $isCurrentGrade ? ($selectedStudent->section->name ?? '') : '',
                        'school_year' => $isCurrentGrade ? $schoolYear : '',
                        'adviser' => $isCurrentGrade ? $adviserName : ''
                    ];
                }
            }
        }
    }

    return view('teacher.school-forms.sf10', compact(
        'students',
        'selectedStudent',
        'adviserName',
        'subjectsByGrade',
        'historicalGrades',
        'schoolHistory',
        'kinderDomainsByGrade',
        'schoolYear',
        'activeSchoolYear',
        'schoolId',
        'schoolName',
        'schoolDivision',
        'schoolRegion',
        'schoolDistrict',
        'schoolHead',
        'schoolAddress',
        'currentGradeLevel',
        'allGradeLevels',
        'lang'
    ));
}

    /**
     * Kindergarten Developmental Domain Assessment
     */
    public function kindergartenAssessment(Request $request)
    {
        $sections = $this->getTeacherSections();
        
        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::latest('start_date')->first();
        }
        
        // Filter sections to only show Kindergarten sections
        $kinderSections = $sections->filter(function($section) {
            $gradeName = $section->gradeLevel->name ?? '';
            return stripos($gradeName, 'kinder') !== false;
        });
        
        // Get selected section
        $selectedSection = $request->section_id
            ? Section::with(['gradeLevel', 'students.user'])->find($request->section_id)
            : $kinderSections->first();
        
        // Get selected student
        $selectedStudent = null;
        if ($request->student_id) {
            $selectedStudent = Student::with(['user', 'section.gradeLevel'])->find($request->student_id);
        } elseif ($selectedSection && $selectedSection->students->isNotEmpty()) {
            $selectedStudent = $selectedSection->students->first();
        }
        
        // Get selected quarter (default to 1)
        $selectedQuarter = $request->quarter ?? 1;
        
        // Language setting - persisted in session
        if ($request->has('lang')) {
            $lang = $request->get('lang');
            if (in_array($lang, ['cebuano', 'english'])) {
                session(['kindergarten_lang' => $lang]);
            }
        }
        $lang = session('kindergarten_lang', 'cebuano');
        
        // Get kindergarten config
        $kinderConfig = config('kindergarten.domains');
        $ratingScale = config('kindergarten.rating_scale');
        
        // Get existing ratings for this student and quarter
        $existingRatings = collect();
        if ($selectedStudent) {
            $existingRatings = KindergartenDomain::where('student_id', $selectedStudent->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->where('quarter', $selectedQuarter)
                ->get()
                ->keyBy(function($item) {
                    return $item->domain . '.' . $item->indicator_key;
                });
        }
        
        // Get students for dropdown
        $students = collect();
        if ($selectedSection) {
            $students = Student::with('user')
                ->where('section_id', $selectedSection->id)
                ->whereHas('enrollments', function($q) use ($activeSchoolYear) {
                    $q->where('school_year_id', $activeSchoolYear->id)
                      ->whereIn('status', ['enrolled', 'completed']);
                })
                ->whereNotIn('status', ['completed', 'inactive'])
                ->orderBy('created_at')
                ->get();
        }
        
        // Check finalization status for the selected section
        $isEditable = true;
        $finalization = null;
        if ($selectedSection && $activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization(
                $selectedSection->id, 
                $activeSchoolYear->id
            );
            $isEditable = !$finalization->is_locked && !$finalization->grades_finalized;
        }
        
        return view('teacher.school-forms.kindergarten-assessment', compact(
            'kinderSections',
            'selectedSection',
            'students',
            'selectedStudent',
            'selectedQuarter',
            'kinderConfig',
            'ratingScale',
            'existingRatings',
            'activeSchoolYear',
            'lang',
            'isEditable',
            'finalization'
        ));
    }

    /**
     * Store Kindergarten Domain Assessment
     */
    public function storeKindergartenDomain(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'quarter' => 'required|in:1,2,3,4',
            'ratings' => 'required|array',
            'ratings.*.*' => 'nullable|in:B,D,C', // domain_key.indicator_key => rating
        ]);

        $activeSchoolYear = SchoolYear::where('is_active', true)->first()
            ?? SchoolYear::latest('start_date')->first();

        // Check if section is finalized/locked
        $student = Student::find($validated['student_id']);
        if ($student && $student->section_id && $activeSchoolYear) {
            $finalization = $this->finalizationService->getOrCreateFinalization(
                $student->section_id, 
                $activeSchoolYear->id
            );
            
            if ($finalization->is_locked || $finalization->grades_finalized) {
                return back()->with('error', 'Kindergarten assessments have been finalized and are locked. Contact the administrator if you need to make changes.');
            }
        }

        $kinderConfig = config('kindergarten.domains');
        $studentId = $validated['student_id'];
        $quarter = $validated['quarter'];

        // Process each rating
        foreach ($validated['ratings'] as $domainKey => $indicators) {
            foreach ($indicators as $indicatorKey => $rating) {
                if (empty($rating)) continue;

                // Get the indicator text from config
                $indicatorText = '';
                if (isset($kinderConfig[$domainKey]['subdomains'])) {
                    foreach ($kinderConfig[$domainKey]['subdomains'] as $subdomain) {
                        if (isset($subdomain['indicators'][$indicatorKey])) {
                            $indicatorText = $subdomain['indicators'][$indicatorKey];
                            break 2;
                        }
                    }
                }

                // Update or create the rating
                KindergartenDomain::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'domain' => $domainKey,
                        'indicator_key' => $indicatorKey,
                        'quarter' => $quarter,
                        'school_year_id' => $activeSchoolYear->id,
                    ],
                    [
                        'indicator' => $indicatorText,
                        'rating' => $rating,
                        'recorded_by' => auth()->id(),
                    ]
                );
            }
        }

        // Preserve language preference in redirect
        $lang = session('kindergarten_lang', 'cebuano');
        
        return redirect()
            ->route('teacher.kindergarten.assessment', [
                'section_id' => $request->section_id,
                'student_id' => $studentId,
                'quarter' => $quarter,
                'lang' => $lang,
            ])
            ->with('success', 'Kindergarten assessment saved successfully!');
    }

    /**
     * Delete Kindergarten Domain Rating
     */
    public function deleteKindergartenDomain(KindergartenDomain $domain)
    {
        // Verify the teacher owns this section
        $teacher = auth()->user()->teacher;
        $student = Student::find($domain->student_id);
        
        if (!$student || $student->section->teacher_id !== $teacher?->id) {
            abort(403, 'Unauthorized');
        }

        $domain->delete();

        return back()->with('success', 'Rating deleted successfully.');
    }

    /**
     * Finalize Kindergarten Assessments for a section
     */
    public function finalizeKindergarten(Request $request, Section $section)
    {
        if ($section->teacher_id !== auth()->user()->teacher?->id) {
            abort(403);
        }

        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        if ($activeSchoolYear && $section->school_year_id !== $activeSchoolYear->id) {
            abort(403, 'This section is not in the active school year.');
        }

        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No active school year found.']);
            }
            return back()->with('error', 'No active school year found.');
        }

        $result = $this->finalizationService->finalizeKindergarten(
            $section->id,
            $activeSchoolYear->id,
            auth()->id()
        );

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        // Regular form submission fallback
        if ($result['success']) {
            return back()->with('success', $result['message']);
        } else {
            return back()->with('error', $result['message'])->with('validation_errors', $result['errors'] ?? []);
        }
    }
}