<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\CoreValue;
use App\Models\SchoolYear;
use App\Models\Setting;
use App\Models\KindergartenDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradesController extends Controller
{
    /**
     * Display SF9-style grades report for the authenticated student
     */
    public function index(Request $request)
    {
        // Get authenticated student with relationships
        $user = Auth::user();
        $student = Student::with(['user', 'section.gradeLevel.subjects', 'section.teacher.user'])
            ->where('user_id', $user->id)
            ->first();

        if (!$student) {
            abort(404, 'Student record not found');
        }

        // Get active school year from is_active flag (consistent with teacher's approach)
        $activeSchoolYear = SchoolYear::with('quarters')->where('is_active', true)->first();
        
        // If no active school year found, get the latest one
        if (!$activeSchoolYear) {
            $activeSchoolYear = SchoolYear::with('quarters')->latest('start_date')->first();
        }
        
        // Get current quarter info
        $currentQuarter = $activeSchoolYear?->currentQuarter();

        $schoolYear = $activeSchoolYear->name ?? date('Y') . '-' . (date('Y') + 1);

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

        // Calculate age from birthdate (using student's birthdate column)
        $age = null;
        if ($student->birthdate) {
            $age = \Carbon\Carbon::parse($student->birthdate)->age;
        }

        // Get adviser name from section teacher (same logic as teacher's version)
        $adviserName = '';
        if ($student->section && $student->section->teacher) {
            $teacherUser = $student->section->teacher->user;
            if ($teacherUser) {
                $adviserName = ($teacherUser->last_name ?? '') . ', ' . 
                              ($teacherUser->first_name ?? '') . ' ' . 
                              ($teacherUser->middle_name ?? '');
                $adviserName = trim($adviserName) ?: ($teacherUser->name ?? '');
            } else {
                $adviserName = $student->section->teacher->name ?? '';
            }
        }

        // Check if student is in Kindergarten
        $gradeLevelName = $student->section->gradeLevel->name ?? '';
        $isKindergarten = stripos($gradeLevelName, 'kinder') !== false || 
                          stripos($gradeLevelName, 'pre-school') !== false ||
                          strtolower($gradeLevelName) === 'k';

        // Get kindergarten domains data if applicable
        $kindergartenDomains = collect();
        $lang = 'english';
        if ($isKindergarten) {
            $kindergartenDomains = KindergartenDomain::where('student_id', $student->id)
                ->where('school_year_id', $activeSchoolYear->id)
                ->get()
                ->groupBy(['domain_key', 'indicator_key']);
        }

        // Get attendance records for the school year
        $attendances = Attendance::where('student_id', $student->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->get();

        // Get core values records - GROUPED by core_value and statement_key
        $coreValues = CoreValue::where('student_id', $student->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->get()
            ->groupBy(['core_value', 'statement_key']);

        // Build subject grades from grade level subjects
        $subjectGrades = collect();
        $gradeLevelSubjects = $student->section->gradeLevel->subjects ?? collect();
        
        $totalFinalGrade = 0;
        $gradedSubjectsCount = 0;
        
        foreach ($gradeLevelSubjects as $subject) {
            // Get all final_grade records for this subject (quarters 1-4 and year-end)
            $allGrades = Grade::where([
                'student_id' => $student->id,
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

        // Calculate attendance rate for stats
        $totalSchoolDays = $attendances->sum('school_days') ?? 0;
        $totalPresent = $attendances->sum('days_present') ?? 0;
        $attendanceRate = $totalSchoolDays > 0 ? round(($totalPresent / $totalSchoolDays) * 100, 1) : 0;

        // Alias for blade compatibility with teacher SF9 layout
        $selectedStudent = $student;

        return view('student.grades.index', compact(
            'student',
            'selectedStudent',
            'schoolYear',
            'activeSchoolYear',
            'currentQuarter',
            'schoolId',
            'schoolName',
            'schoolDivision',
            'schoolRegion',
            'schoolDistrict',
            'schoolHead',
            'age',
            'adviserName',
            'subjectGrades',
            'generalAverage',
            'attendances',
            'coreValues',
            'attendanceRate',
            'isKindergarten',
            'kindergartenDomains',
            'lang'
        ));
    }
}