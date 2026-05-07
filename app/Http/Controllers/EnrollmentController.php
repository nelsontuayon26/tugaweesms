<?php

namespace App\Http\Controllers;

use App\Models\EnrollmentApplication;
use App\Models\EnrollmentDocument;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\User;
use App\Models\Student;
use App\Models\Role;
use App\Models\Section;
use App\Models\Enrollment;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * Show enrollment form (for continuing students)
     */
    public function showForm()
    {
        // Check if enrollment is enabled
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
        if (!$enrollmentEnabled) {
            $response = response()->view('enrollment.closed');
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
            return $response;
        }

        $currentSchoolYear = SchoolYear::where('is_active', true)->first();
        $gradeLevels = GradeLevel::orderBy('order')->get();

        $response = response()->view('enrollment.form', compact('currentSchoolYear', 'gradeLevels'));
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, private');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        return $response;
    }

    /**
     * Submit enrollment application
     */
    public function submit(Request $request)
    {
        $applicationType = $request->input('application_type', 'continuing');

        // Security: continuing student enrollment must be done through the authenticated student portal
        if ($applicationType === 'continuing' && !auth()->check()) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Please log in to your Pupil Portal to enroll as a continuing student.']);
        }

        if ($applicationType === 'continuing') {
            return $this->submitContinuingStudentEnrollment($request);
        }

        return $this->submitNewOrTransferEnrollment($request);
    }

    /**
     * Submit continuing student enrollment (simplified)
     */
    private function submitContinuingStudentEnrollment(Request $request)
    {
        // Check if enrollment is enabled
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
        if (!$enrollmentEnabled) {
            return back()->withErrors(['error' => 'Enrollment is currently closed. Please contact the school administration.']);
        }

        // Get active school year - MUST use active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            return back()->withErrors(['error' => 'No active school year found. Please contact the school administration.']);
        }

        $validated = $request->validate([
            'application_type' => 'required|in:continuing',
            'student_lrn' => 'required|string|size:12|exists:students,lrn',
            'school_year_id' => 'required|exists:school_years,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'parent_email' => 'required|email',
        ]);

        // Force use of active school year (ignore submitted school_year_id)
        $validated['school_year_id'] = $activeSchoolYear->id;

        // Find existing student with user
        $student = Student::where('lrn', $validated['student_lrn'])
            ->with(['user', 'gradeLevel'])
            ->first();

        if (!$student) {
            return back()->withErrors(['student_lrn' => 'Student record not found. Please register as a new student.']);
        }

        // Check if student already has a pending enrollment for this school year
        $existingApplication = EnrollmentApplication::where('student_lrn', $validated['student_lrn'])
            ->where('school_year_id', $validated['school_year_id'])
            ->whereIn('status', ['pending', 'under_review'])
            ->first();

        if ($existingApplication) {
            return back()->withErrors(['student_lrn' => 'You already have a pending enrollment application for this school year. Application #: ' . $existingApplication->application_number]);
        }

        // Create simplified enrollment application for continuing student
        // Get name from user relationship
        $user = $student->user;
        
        $application = EnrollmentApplication::create([
            'application_type' => 'continuing',
            'application_number' => EnrollmentApplication::generateApplicationNumber(),
            'school_year_id' => $validated['school_year_id'],
            'grade_level_id' => $validated['grade_level_id'],
            'student_first_name' => $user?->first_name ?? 'Unknown',
            'student_middle_name' => $user?->middle_name,
            'student_last_name' => $user?->last_name ?? 'Unknown',
            'student_suffix' => $user?->suffix,
            'student_birthdate' => $student->birthdate,
            'student_gender' => strtolower($student->gender) ?? 'male',
            'student_nationality' => $student->nationality ?? 'Filipino',
            'student_lrn' => $validated['student_lrn'],
            'student_id' => $student->id,
            'parent_email' => $validated['parent_email'],
            'parent_password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
            'address' => $student->street_address ?? 'On file',
            'barangay' => $student->barangay ?? 'On file',
            'city' => $student->city ?? 'On file',
            'province' => $student->province ?? 'Negros Oriental',
            'guardian_name' => $student->guardian_name ?? 'On file',
            'guardian_relationship' => $student->guardian_relationship ?? 'Parent',
            'guardian_contact' => $student->guardian_contact ?? 'On file',
            'emergency_contact_name' => $student->guardian_name ?? 'On file',
            'emergency_contact_relationship' => $student->guardian_relationship ?? 'Parent',
            'emergency_contact_number' => $student->guardian_contact ?? 'On file',
            'status' => 'pending',
            'account_created' => true, // Already has account
        ]);

        // Send confirmation email
        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentSubmitted($application));
        } catch (\Exception $e) {
            \Log::error('Failed to send enrollment confirmation: ' . $e->getMessage());
        }

        return redirect()->route('enrollment.success', ['application_number' => $application->application_number])
            ->with('success', 'Your enrollment application has been submitted successfully!');
    }

    /**
     * Submit new or transfer student enrollment (with documents)
     */
    private function submitNewOrTransferEnrollment(Request $request)
    {
        // Check if enrollment is enabled
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
        if (!$enrollmentEnabled) {
            return back()->withErrors(['error' => 'Enrollment is currently closed. Please contact the school administration.']);
        }

        // Get active school year - MUST use active school year
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        if (!$activeSchoolYear) {
            return back()->withErrors(['error' => 'No active school year found. Please contact the school administration.']);
        }

        $validated = $request->validate([
            'application_type' => ['required', Rule::in(['new_student', 'transfer'])],
            'school_year_id' => 'required|exists:school_years,id',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'student_first_name' => 'required|string|max:100',
            'student_middle_name' => 'nullable|string|max:100',
            'student_last_name' => 'required|string|max:100',
            'parent_email' => 'required|email|unique:enrollment_applications,parent_email|unique:users,email',
            'documents' => 'required|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Force use of active school year (ignore submitted school_year_id)
        $validated['school_year_id'] = $activeSchoolYear->id;

        // Use null for optional fields not collected by the simplified form.
        // The enrollment_applications table allows these to be nullable.
        $validated['student_birthdate'] = $validated['student_birthdate'] ?? null;
        $validated['student_gender'] = $validated['student_gender'] ?? null;
        $validated['student_nationality'] = $validated['student_nationality'] ?? 'Filipino';
        $validated['address'] = $validated['address'] ?? null;
        $validated['barangay'] = $validated['barangay'] ?? null;
        $validated['city'] = $validated['city'] ?? null;
        $validated['guardian_name'] = $validated['guardian_name'] ?? null;
        $validated['guardian_relationship'] = $validated['guardian_relationship'] ?? null;
        $validated['guardian_contact'] = $validated['guardian_contact'] ?? null;
        $validated['emergency_contact_name'] = $validated['emergency_contact_name'] ?? null;
        $validated['emergency_contact_relationship'] = $validated['emergency_contact_relationship'] ?? null;
        $validated['emergency_contact_number'] = $validated['emergency_contact_number'] ?? null;
        $validated['parent_password'] = Hash::make(Str::random(16));

        // Create application
        $application = EnrollmentApplication::create($validated);

        // Upload documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $type => $file) {
                if (is_array($file)) {
                    $file = $file[0];
                }
                
                $path = $file->store('enrollment-documents/' . $application->id, 'public');
                
                EnrollmentDocument::create([
                    'enrollment_application_id' => $application->id,
                    'document_type' => $type,
                    'document_name' => EnrollmentDocument::getDocumentTypes()[$type] ?? 'Document',
                    'file_path' => $path,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Send confirmation email
        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentSubmitted($application));
        } catch (\Exception $e) {
            \Log::error('Failed to send enrollment confirmation: ' . $e->getMessage());
        }

        return redirect()->route('enrollment.success', ['application_number' => $application->application_number])
            ->with('success', 'Your enrollment application has been submitted successfully!');
    }

    /**
     * Show success page
     */
    public function success($applicationNumber)
    {
        $application = EnrollmentApplication::where('application_number', $applicationNumber)->firstOrFail();
        return view('enrollment.success', compact('application'));
    }

    /**
     * Show check status form
     */
    public function showCheckForm()
    {
        return view('enrollment.check');
    }

    /**
     * Check application status (public)
     */
    public function checkStatus(Request $request)
    {
        $request->validate([
            'application_number' => 'required|string',
            'parent_email' => 'required|email',
        ]);

        $application = EnrollmentApplication::where('application_number', $request->application_number)
            ->where('parent_email', $request->parent_email)
            ->first();

        if (!$application) {
            return back()->withErrors(['error' => 'Application not found. Please check your application number and email.']);
        }

        return view('enrollment.check', compact('application'));
    }

    /**
     * Admin: List CONTINUING student applications only for ACTIVE school year
     * Similar to Pending Registrations but for students using online enrollment
     */
    public function adminIndex(Request $request)
    {
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        // Get all enrolled continuing students for the active school year, grouped by section
        $sections = \App\Models\Section::with(['gradeLevel', 'teacher.user'])
            ->where('school_year_id', $activeSchoolYear?->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $groupedEnrollments = [];
        foreach ($sections as $section) {
            $enrollments = \App\Models\Enrollment::with(['student.user'])
                ->where('section_id', $section->id)
                ->where('school_year_id', $activeSchoolYear?->id)
                ->where('type', 'continuing')
                ->where('status', 'enrolled')
                ->get()
                ->sortBy([
                    [fn($e) => strcasecmp($e->student->gender, 'Male') === 0 ? 0 : 1, 'asc'],
                    [fn($e) => $e->student->last_name, 'asc'],
                    [fn($e) => $e->student->first_name, 'asc'],
                ]);

            if ($enrollments->isNotEmpty()) {
                $groupedEnrollments[] = [
                    'section' => $section,
                    'students' => $enrollments->values(),
                    'male_count' => $enrollments->filter(fn($e) => strcasecmp($e->student->gender, 'Male') === 0)->count(),
                    'female_count' => $enrollments->filter(fn($e) => strcasecmp($e->student->gender, 'Female') === 0)->count(),
                ];
            }
        }

        // Stats
        $totalEnrolled = \App\Models\Enrollment::where('school_year_id', $activeSchoolYear?->id)
            ->where('type', 'continuing')
            ->where('status', 'enrolled')
            ->count();

        $promotedCount = \App\Models\Enrollment::where('school_year_id', $activeSchoolYear?->id)
            ->where('type', 'continuing')
            ->where('status', 'enrolled')
            ->where('remarks', 'Promoted')
            ->count();

        $retainedCount = \App\Models\Enrollment::where('school_year_id', $activeSchoolYear?->id)
            ->where('type', 'continuing')
            ->where('status', 'enrolled')
            ->where('remarks', 'Retained')
            ->count();

        $stats = [
            'total' => $totalEnrolled,
            'promoted' => $promotedCount,
            'retained' => $retainedCount,
            'sections' => count($groupedEnrollments),
        ];

        return view('enrollment.admin.index', compact(
            'groupedEnrollments',
            'stats',
            'activeSchoolYear'
        ));
    }

    /**
     * Admin: View single application
     */
    public function adminShow(EnrollmentApplication $application)
    {
        $application->load(['documents', 'gradeLevel', 'schoolYear', 'reviewer']);
        
        // Get available sections for this grade level with enrollment counts
        $schoolYearId = $application->school_year_id;
        $gradeLevelId = $application->grade_level_id;
        
        $sections = Section::with(['teacher.user'])
            ->withCount(['enrollments' => function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId)
                      ->where('status', 'enrolled');
            }])
            ->where('grade_level_id', $gradeLevelId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        // Check if student already exists (by LRN for continuing, by email for new)
        $existingStudent = null;
        if ($application->student_lrn) {
            $existingStudent = Student::where('lrn', $application->student_lrn)->first();
        }
        
        // Get all school years for selection
        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();
        
        return view('enrollment.admin.show', compact('application', 'sections', 'existingStudent', 'schoolYears'));
    }

    /**
     * Admin: Update application status
     */
    public function updateStatus(Request $request, EnrollmentApplication $application)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'under_review', 'approved', 'rejected', 'waitlisted'])],
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'required_if:status,rejected|string|max:500',
        ]);

        $oldStatus = $application->status;
        $newStatus = $validated['status'];

        $application->update([
            'status' => $newStatus,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'rejection_reason' => $validated['rejection_reason'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->sendStatusNotification($application, $oldStatus, $newStatus);

        // If approved and not already created, create student account
        if ($newStatus === 'approved' && !$application->account_created && !$application->student_id) {
            $this->createStudentAccount($application);
        }

        // If approved and continuing student, update their enrollment
        if ($newStatus === 'approved' && $application->student_id) {
            $this->updateContinuingStudentEnrollment($application);
        }

        return back()->with('success', 'Application status updated successfully.');
    }

    /**
     * Admin: Verify document
     */
    public function verifyDocument(Request $request, EnrollmentDocument $document)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['verified', 'rejected'])],
            'admin_notes' => 'nullable|string|max:500',
        ]);

        if ($validated['status'] === 'verified') {
            $document->verify(auth()->id(), $validated['admin_notes'] ?? null);
        } else {
            $document->reject(auth()->id(), $validated['admin_notes'] ?? 'Document rejected');
        }

        return back()->with('success', 'Document status updated.');
    }

    /**
     * Admin: Approve application with section assignment (for CONTINUING students)
     * EXACTLY like PendingRegistrationController@approve
     */
    public function approveWithSection(Request $request, EnrollmentApplication $application)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
            'remarks' => 'nullable|string|max:50',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $schoolYearId = $validated['school_year_id'];
        $section = Section::find($validated['section_id']);

        if (!$section || !$section->is_active) {
            return back()->with('error', 'Selected section is not available.');
        }

        // Check capacity for THIS school year only
        $currentCount = Enrollment::where('section_id', $section->id)
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'enrolled')
            ->count();

        if ($currentCount >= $section->capacity) {
            return back()->with('error', 'Selected section is already full for this school year.');
        }

        // MUST have existing student (continuing students only)
        if (!$application->student_id) {
            return back()->with('error', 'No existing student record found for this application.');
        }

        $student = Student::with('user')->find($application->student_id);
        
        if (!$student) {
            return back()->with('error', 'Student not found.');
        }

        DB::beginTransaction();
        try {
            // Check if enrollment already exists for this school year
            $existingEnrollment = Enrollment::where('student_id', $student->id)
                ->where('school_year_id', $schoolYearId)
                ->first();

            if ($existingEnrollment) {
                // Update existing enrollment
                $existingEnrollment->update([
                    'status' => 'enrolled',
                    'section_id' => $validated['section_id'],
                    'grade_level_id' => $application->grade_level_id,
                    'enrollment_date' => now(),
                    'remarks' => $validated['remarks'] ?? null,
                ]);
            } else {
                // Create new enrollment record
                Enrollment::create([
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYearId,
                    'section_id' => $validated['section_id'],
                    'grade_level_id' => $application->grade_level_id,
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                    'type' => 'Continuing',
                    'remarks' => $validated['remarks'] ?? null,
                ]);
            }

            // Update student current info
            $student->update([
                'status' => 'active',
                'section_id' => $validated['section_id'],
                'grade_level_id' => $application->grade_level_id,
                'school_year_id' => $schoolYearId,
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Update application
            $application->update([
                'status' => 'approved',
                'admin_notes' => $validated['admin_notes'] ?? null,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'account_created' => true,
            ]);

            DB::commit();

            // Log the approval
            ActivityLogService::logEnrollmentApproved($application, $section->name);

            $schoolYear = SchoolYear::find($schoolYearId);
            return redirect()->route('admin.enrollment.index')
                ->with('success', "Student {$student->user->last_name} enrolled in {$section->name} for {$schoolYear->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Enrollment approval failed', ['student_id' => $student->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Failed to enroll student: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Reject application
     */
    public function rejectApplication(Request $request, EnrollmentApplication $application)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $application->status;

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'admin_notes' => $validated['admin_notes'] ?? null,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Log the rejection
        ActivityLogService::logRejection($application, 'EnrollmentApplication', $validated['rejection_reason'] ?? null);

        $this->sendStatusNotification($application, $oldStatus, 'rejected');

        return redirect()->route('admin.enrollment.index')
            ->with('success', 'Application has been rejected.');
    }

    /**
     * Admin: Bulk approve applications
     */
    public function bulkApprove(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array',
            'applications.*' => 'exists:enrollment_applications,id',
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $applications = EnrollmentApplication::whereIn('id', $validated['applications'])
            ->where('status', '!=', 'approved')
            ->get();

        $approvedCount = 0;
        $errors = [];

        foreach ($applications as $application) {
            try {
                // Check section capacity
                $section = Section::find($validated['section_id']);
                $currentEnrollments = Enrollment::where('section_id', $section->id)
                    ->where('school_year_id', $validated['school_year_id'])
                    ->where('status', 'enrolled')
                    ->count();

                if ($currentEnrollments >= $section->capacity) {
                    $errors[] = "Section {$section->name} is full.";
                    continue;
                }

                // Approve this application
                $this->approveSingleApplication($application, $validated['section_id'], $validated['school_year_id']);
                $approvedCount++;

            } catch (\Exception $e) {
                $errors[] = "Failed to approve {$application->application_number}: {$e->getMessage()}";
            }
        }

        $message = "{$approvedCount} application(s) approved successfully.";
        if (!empty($errors)) {
            $message .= " Errors: " . implode(', ', $errors);
            return back()->with('warning', $message);
        }

        return back()->with('success', $message);
    }

    /**
     * Admin: Bulk reject applications
     */
    public function bulkReject(Request $request)
    {
        $validated = $request->validate([
            'applications' => 'required|array',
            'applications.*' => 'exists:enrollment_applications,id',
            'rejection_reason' => 'required|string|max:500',
        ]);

        $applications = EnrollmentApplication::whereIn('id', $validated['applications'])
            ->where('status', '!=', 'rejected')
            ->get();

        foreach ($applications as $application) {
            $application->update([
                'status' => 'rejected',
                'rejection_reason' => $validated['rejection_reason'],
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $this->sendStatusNotification($application, $application->status, 'rejected');
        }

        return back()->with('success', count($applications) . ' application(s) rejected.');
    }

    /**
     * Update continuing student enrollment after approval
     */
    private function updateContinuingStudentEnrollment(EnrollmentApplication $application): void
    {
        $student = Student::find($application->student_id);
        
        if ($student) {
            // Update student's current grade and school year
            $student->update([
                'grade_level_id' => $application->grade_level_id,
                'school_year_id' => $application->school_year_id,
                'status' => 'active',
            ]);

            // Create or update enrollment record for the school year
            Enrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $application->school_year_id,
                ],
                [
                    'grade_level_id' => $application->grade_level_id,
                    'section_id' => $student->section_id,
                    'type' => 'continuing',
                    'status' => 'enrolled',
                    'enrollment_date' => now(),
                ]
            );
        }
    }

    /**
     * Create student account after approval
     */
    private function createStudentAccount(EnrollmentApplication $application): void
    {
        // Create user account
        $user = User::create([
            'role_id' => Role::where('name', 'Pupil')->first()->id,
            'first_name' => $application->student_first_name,
            'middle_name' => $application->student_middle_name,
            'last_name' => $application->student_last_name,
            'suffix' => $application->student_suffix,
            'username' => $this->generateUsername($application),
            'email' => $application->parent_email,
            'password' => $application->parent_password,
            'is_active' => true,
        ]);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'lrn' => $this->generateLRN(),
            'first_name' => $application->student_first_name,
            'middle_name' => $application->student_middle_name,
            'last_name' => $application->student_last_name,
            'suffix' => $application->student_suffix,
            'birthdate' => $application->student_birthdate,
            'gender' => $application->student_gender,
            'birth_place' => $application->student_birth_place,
            'religion' => $application->student_religion,
            'nationality' => $application->student_nationality,
            'mother_tongue' => $application->student_mother_tongue,
            'ethnicity' => $application->student_ethnicity,
            'street_address' => $application->address,
            'barangay' => $application->barangay,
            'city' => $application->city,
            'province' => $application->province,
            'zip_code' => $application->zip_code,
            'father_name' => $application->father_name,
            'father_occupation' => $application->father_occupation,
            'father_contact' => $application->father_contact,
            'mother_name' => $application->mother_name,
            'mother_occupation' => $application->mother_occupation,
            'mother_contact' => $application->mother_contact,
            'guardian_name' => $application->guardian_name,
            'guardian_relationship' => $application->guardian_relationship,
            'guardian_contact' => $application->guardian_contact,
            'emergency_contact_name' => $application->emergency_contact_name,
            'emergency_contact_relationship' => $application->emergency_contact_relationship,
            'emergency_contact_number' => $application->emergency_contact_number,
            'grade_level_id' => $application->grade_level_id,
            'school_year_id' => $application->school_year_id,
            'status' => 'active',
        ]);

        $application->update(['account_created' => true, 'student_id' => $student->id]);

        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentApproved($application, $user, $student));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }
    }

    /**
     * Send status change notification
     */
    private function sendStatusNotification(EnrollmentApplication $application, string $oldStatus, string $newStatus): void
    {
        $title = match($newStatus) {
            'under_review' => 'Application Under Review',
            'approved' => 'Application Approved!',
            'rejected' => 'Application Not Approved',
            'waitlisted' => 'Application Waitlisted',
            default => 'Application Status Updated',
        };

        $body = match($newStatus) {
            'under_review' => "Your enrollment application {$application->application_number} is now being reviewed by our admissions team.",
            'approved' => "Congratulations! Your enrollment application {$application->application_number} has been approved. Check your email for login credentials.",
            'rejected' => "We regret to inform you that your enrollment application {$application->application_number} was not approved. Reason: {$application->rejection_reason}",
            'waitlisted' => "Your enrollment application {$application->application_number} has been placed on our waitlist due to full capacity.",
            default => "Your enrollment application {$application->application_number} status has been updated to: {$newStatus}",
        };

        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentStatusUpdated($application, $title, $body));
        } catch (\Exception $e) {
            \Log::error('Failed to send status update email: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique username
     */
    private function generateUsername(EnrollmentApplication $application): string
    {
        $base = strtolower(
            substr($application->student_first_name, 0, 1) . 
            $application->student_last_name
        );
        $base = preg_replace('/[^a-z0-9]/', '', $base);
        
        $username = $base;
        $counter = 1;
        
        while (User::where('username', $username)->exists()) {
            $username = $base . $counter;
            $counter++;
        }
        
        return $username;
    }

    /**
     * Generate unique LRN
     */
    private function generateLRN(): string
    {
        $year = now()->year;
        $prefix = $year;
        
        $lastStudent = Student::where('lrn', 'like', $prefix . '%')->orderByDesc('lrn')->first();
        
        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->lrn, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $lrn = $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
        
        // Ensure uniqueness (retry if collision occurs)
        $attempts = 0;
        while (Student::where('lrn', $lrn)->exists() && $attempts < 100) {
            $newNumber++;
            $lrn = $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
            $attempts++;
        }
        
        return $lrn;
    }

    /**
     * Create student account with section assignment
     */
    private function createStudentAccountWithEnrollment(EnrollmentApplication $application, array $data): array
    {
        // Get Pupil role
        $studentRole = Role::where('name', 'Pupil')->first();
        if (!$studentRole) {
            throw new \Exception('Pupil role not found in the system');
        }

        // Generate password if not set
        $password = $application->parent_password ?: Hash::make(Str::random(16));

        // Create user account
        $user = User::create([
            'role_id' => $studentRole->id,
            'first_name' => $application->student_first_name,
            'middle_name' => $application->student_middle_name,
            'last_name' => $application->student_last_name,
            'suffix' => $application->student_suffix,
            'username' => $this->generateUsername($application),
            'email' => $application->parent_email,
            'password' => $password,
            'is_active' => true,
        ]);

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'lrn' => $application->student_lrn ?: $this->generateLRN(),
            'first_name' => $application->student_first_name,
            'middle_name' => $application->student_middle_name,
            'last_name' => $application->student_last_name,
            'suffix' => $application->student_suffix,
            'birthdate' => $application->student_birthdate,
            'gender' => $application->student_gender,
            'birth_place' => $application->student_birth_place,
            'religion' => $application->student_religion,
            'nationality' => $application->student_nationality,
            'mother_tongue' => $application->student_mother_tongue,
            'ethnicity' => $application->student_ethnicity,
            'street_address' => $application->address,
            'barangay' => $application->barangay,
            'city' => $application->city,
            'province' => $application->province,
            'zip_code' => $application->zip_code,
            'father_name' => $application->father_name,
            'father_occupation' => $application->father_occupation,
            'father_contact' => $application->father_contact,
            'mother_name' => $application->mother_name,
            'mother_occupation' => $application->mother_occupation,
            'mother_contact' => $application->mother_contact,
            'guardian_name' => $application->guardian_name,
            'guardian_relationship' => $application->guardian_relationship,
            'guardian_contact' => $application->guardian_contact,
            'emergency_contact_name' => $application->emergency_contact_name,
            'emergency_contact_relationship' => $application->emergency_contact_relationship,
            'emergency_contact_number' => $application->emergency_contact_number,
            'grade_level_id' => $application->grade_level_id,
            'school_year_id' => $application->school_year_id,
            'section_id' => $data['section_id'] ?? null,
            'status' => 'active',
            'remarks' => $data['remarks'] ?? null,
        ]);

        $application->update([
            'account_created' => true, 
            'student_id' => $student->id
        ]);

        // Send approval email
        try {
            \Mail::to($application->parent_email)->send(new \App\Mail\EnrollmentApproved($application, $user, $student));
        } catch (\Exception $e) {
            \Log::error('Failed to send approval email: ' . $e->getMessage());
        }

        return ['user' => $user, 'student' => $student];
    }

    /**
     * Approve single application for bulk operations
     */
    private function approveSingleApplication(EnrollmentApplication $application, int $sectionId, int $schoolYearId): void
    {
        // Create or get student
        $student = null;
        if (!$application->student_id) {
            $studentData = $this->createStudentAccountWithEnrollment($application, [
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ]);
            $student = $studentData['student'];
        } else {
            $student = Student::find($application->student_id);
            if ($student) {
                $student->update([
                    'section_id' => $sectionId,
                    'status' => 'active',
                ]);
            }
        }

        // Update application
        $application->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'student_id' => $student?->id,
            'account_created' => true,
        ]);

        // Create enrollment record
        Enrollment::create([
            'student_id' => $student->id,
            'school_year_id' => $schoolYearId,
            'section_id' => $sectionId,
            'grade_level_id' => $application->grade_level_id,
            'enrollment_date' => now(),
            'status' => 'enrolled',
            'type' => $application->application_type === 'transfer' ? 'Transfer' : 'New',
        ]);

        // Send notification
        $this->sendStatusNotification($application, $application->status, 'approved');
    }
}
