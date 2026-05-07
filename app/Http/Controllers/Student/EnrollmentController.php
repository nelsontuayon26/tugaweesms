<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentApplication;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Show enrollment form for logged-in continuing students.
     * Data is pre-filled from their existing student record.
     */
    public function index()
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return redirect()->route('student.dashboard')
                ->withErrors(['error' => 'Student record not found. Please contact the school administration.']);
        }

        // Check if enrollment is enabled
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;

        $currentSchoolYear = SchoolYear::where('is_active', true)->first();
        $gradeLevels = GradeLevel::orderBy('order')->get();

        // Check if student already has a pending enrollment application for active school year
        $existingApplication = null;
        if ($currentSchoolYear) {
            $existingApplication = EnrollmentApplication::where('student_lrn', $student->lrn)
                ->where('school_year_id', $currentSchoolYear->id)
                ->whereIn('status', ['pending', 'under_review'])
                ->first();
        }

        // Check if student is already enrolled for the active school year
        $isAlreadyEnrolled = false;
        if ($currentSchoolYear) {
            $isAlreadyEnrolled = $student->enrollments()
                ->where('school_year_id', $currentSchoolYear->id)
                ->where('status', 'enrolled')
                ->exists();
        }

        // Calculate general average from all final grades
        $generalAverage = \App\Models\Grade::where('student_id', $student->id)
            ->where('component_type', 'final_grade')
            ->avg('final_grade') ?? 0;
        $generalAverage = round($generalAverage, 2);
        $isPassing = $generalAverage >= 75;

        // Determine suggested grade level
        $currentGradeLevel = $student->gradeLevel;
        $suggestedGradeLevel = null;
        $isRetained = false;

        if ($currentGradeLevel) {
            if ($isPassing) {
                // Promoted: suggest next grade level
                $suggestedGradeLevel = GradeLevel::where('order', '>', $currentGradeLevel->order)
                    ->orderBy('order')
                    ->first();
            } else {
                // Retained: suggest same grade level
                $suggestedGradeLevel = $currentGradeLevel;
                $isRetained = true;
            }
        }

        return view('student.enrollment.index', compact(
            'student',
            'user',
            'currentSchoolYear',
            'gradeLevels',
            'enrollmentEnabled',
            'existingApplication',
            'isAlreadyEnrolled',
            'generalAverage',
            'isPassing',
            'suggestedGradeLevel',
            'isRetained'
        ));
    }

    /**
     * Submit continuing student enrollment from the student portal.
     * Automatically enrolls the student based on their general average.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return back()->withErrors(['error' => 'Student record not found.']);
        }

        // Check if enrollment is enabled
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
        if (!$enrollmentEnabled) {
            return back()->withErrors(['error' => 'Enrollment is currently closed. Please contact the school administration.']);
        }

        // Get active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            return back()->withErrors(['error' => 'No active school year found. Please contact the school administration.']);
        }

        // Calculate general average from all final grades
        $generalAverage = \App\Models\Grade::where('student_id', $student->id)
            ->where('component_type', 'final_grade')
            ->avg('final_grade') ?? 0;
        $generalAverage = round($generalAverage, 2);
        $isPassing = $generalAverage >= 75;

        // Determine the correct grade level
        $currentGradeLevel = $student->gradeLevel;
        $suggestedGradeLevelId = null;

        if ($currentGradeLevel) {
            if ($isPassing) {
                $nextGrade = GradeLevel::where('order', '>', $currentGradeLevel->order)
                    ->orderBy('order')
                    ->first();
                $suggestedGradeLevelId = $nextGrade?->id;
            } else {
                $suggestedGradeLevelId = $currentGradeLevel->id;
            }
        }

        $validated = $request->validate([
            'application_type' => 'required|in:continuing',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'parent_email' => 'required|email',
        ]);

        // Ensure submitted grade level matches the system suggestion
        if ($suggestedGradeLevelId && $validated['grade_level_id'] != $suggestedGradeLevelId) {
            $suggestedName = GradeLevel::find($suggestedGradeLevelId)?->name ?? 'Unknown';
            return back()->withErrors(['grade_level_id' => 'Based on your general average (' . $generalAverage . '), you must enroll in ' . $suggestedName . '.']);
        }

        // Check if already enrolled
        $alreadyEnrolled = $student->enrollments()
            ->where('school_year_id', $activeSchoolYear->id)
            ->where('status', 'enrolled')
            ->exists();

        if ($alreadyEnrolled) {
            return back()->withErrors(['error' => 'You are already enrolled for the ' . $activeSchoolYear->name . ' school year.']);
        }

        // Auto-assign section: find the section with the lowest enrollment count for this grade level
        $section = \App\Models\Section::where('grade_level_id', $validated['grade_level_id'])
            ->where('school_year_id', $activeSchoolYear->id)
            ->where('is_active', true)
            ->withCount(['enrollments' => function ($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear->id)
                  ->where('status', 'enrolled');
            }])
            ->orderBy('enrollments_count')
            ->orderBy('name')
            ->first();

        if (!$section) {
            return back()->withErrors(['error' => 'No available section found for the selected grade level. Please contact the school administration.']);
        }

        // Check section capacity
        $sectionEnrollmentCount = \App\Models\Enrollment::where('section_id', $section->id)
            ->where('school_year_id', $activeSchoolYear->id)
            ->where('status', 'enrolled')
            ->count();

        if ($section->capacity && $sectionEnrollmentCount >= $section->capacity) {
            return back()->withErrors(['error' => 'All sections for the selected grade level are currently full. Please contact the school administration.']);
        }

        // Auto-enroll the student
        DB::beginTransaction();
        try {
            // Create or update enrollment record
            $enrollment = \App\Models\Enrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $activeSchoolYear->id,
                ],
                [
                    'grade_level_id' => $validated['grade_level_id'],
                    'section_id' => $section->id,
                    'type' => 'continuing',
                    'status' => 'enrolled',
                    'enrollment_date' => now(),
                    'remarks' => $isPassing ? 'Promoted' : 'Retained',
                ]
            );

            // Update student record
            $student->update([
                'grade_level_id' => $validated['grade_level_id'],
                'section_id' => $section->id,
                'school_year_id' => $activeSchoolYear->id,
                'status' => 'active',
            ]);

            // Create approved enrollment application for record keeping
            $application = EnrollmentApplication::create([
                'application_type' => 'continuing',
                'application_number' => EnrollmentApplication::generateApplicationNumber(),
                'school_year_id' => $activeSchoolYear->id,
                'grade_level_id' => $validated['grade_level_id'],
                'student_first_name' => $user->first_name ?? 'Unknown',
                'student_middle_name' => $user->middle_name,
                'student_last_name' => $user->last_name ?? 'Unknown',
                'student_suffix' => $user->suffix,
                'student_birthdate' => $student->birthdate,
                'student_gender' => strtolower($student->gender) ?? 'male',
                'student_nationality' => $student->nationality ?? 'Filipino',
                'student_lrn' => $student->lrn,
                'student_id' => $student->id,
                'parent_email' => $validated['parent_email'],
                'parent_password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                'address' => $student->street_address ?? 'On file',
                'barangay' => $student->barangay ?? 'On file',
                'city' => $student->city ?? 'On file',
                'province' => $student->province ?? null,
                'guardian_name' => $student->guardian_name ?? 'On file',
                'guardian_relationship' => $student->guardian_relationship ?? 'Parent',
                'guardian_contact' => $student->guardian_contact ?? 'On file',
                'emergency_contact_name' => $student->guardian_name ?? 'On file',
                'emergency_contact_relationship' => $student->guardian_relationship ?? 'Parent',
                'emergency_contact_number' => $student->guardian_contact ?? 'On file',
                'status' => 'approved',
                'account_created' => true,
                'general_average' => $generalAverage,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Auto-enrollment failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Enrollment failed. Please contact the school administration.']);
        }

        // Send confirmation email
        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentSubmitted($application));
        } catch (\Exception $e) {
            \Log::error('Failed to send enrollment confirmation: ' . $e->getMessage());
        }

        $statusMessage = $isPassing
            ? 'You have been successfully enrolled for ' . $activeSchoolYear->name . '! You are promoted to ' . ($section->gradeLevel?->name ?? 'the next grade') . '.'
            : 'You have been successfully enrolled for ' . $activeSchoolYear->name . '. You are retained in ' . ($section->gradeLevel?->name ?? 'the same grade') . '. Please work harder this year.';

        return redirect()->route('student.enrollment.index')
            ->with('success', $statusMessage);
    }
}
