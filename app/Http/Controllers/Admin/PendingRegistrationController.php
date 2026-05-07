<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PendingRegistrationController extends Controller
{
       public function index(Request $request)
    {
        $selectedSchoolYearId = $request->get('school_year');
        $currentSchoolYear = SchoolYear::where('is_active', true)->first();
        $schoolYearId = $selectedSchoolYearId ?? $currentSchoolYear?->id;
        $schoolYear = SchoolYear::find($schoolYearId);

        // Get pending students for selected school year
        $students = Student::with(['user', 'gradeLevel', 'enrollments'])
            ->whereHas('enrollments', function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId)
                      ->where('status', 'pending');
            })
            ->where('status', 'inactive')
            ->latest()
            ->paginate(10);

        // Get ALL active sections (reused across years) with enrollment count for selected year
        // REMOVED: The grade level filtering that was causing the issue
        $sections = Section::with(['gradeLevel', 'teacher.user'])
            ->withCount(['enrollments' => function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId)
                      ->where('status', 'enrolled');
            }])
            ->where('is_active', true)
            ->orderBy('grade_level_id')
            ->orderBy('name')
            ->get();

        $schoolYears = SchoolYear::orderBy('name', 'desc')->get();

        // Stats
        $sidebarStudentCount = Student::whereHas('enrollments', function ($q) use ($schoolYearId) {
            $q->where('school_year_id', $schoolYearId)
              ->where('status', 'enrolled'); // Only count enrolled, not pending
        })->count();
        
        $sidebarTeacherCount = \App\Models\Teacher::count();
        $sidebarSectionCount = Section::where('is_active', true)->count(); // All active sections
        
        $enrolledTodayCount = Enrollment::where('school_year_id', $schoolYearId)
            ->where('status', 'enrolled')
            ->whereDate('updated_at', today())
            ->count();

        return view('admin.pending-registrations.index', compact(
            'students',
            'sections',
            'schoolYears',
            'schoolYear',
            'selectedSchoolYearId',
            'sidebarStudentCount',
            'sidebarTeacherCount',
            'sidebarSectionCount',
            'enrolledTodayCount'
        ));
    }

    public function details(Request $request, $student)
    {
        try {
            $studentModel = Student::with(['user', 'gradeLevel'])->find($student);
            
            if (!$studentModel) {
                return response()->json(['error' => 'Student not found'], 404);
            }

            $schoolYearId = $request->get('school_year_id') ?? SchoolYear::where('is_active', true)->first()?->id;
            
            if (!$schoolYearId) {
                return response()->json(['error' => 'No school year specified'], 400);
            }

            $enrollment = Enrollment::where('student_id', $studentModel->id)
                ->where('school_year_id', $schoolYearId)
                ->where('status', 'pending')
                ->first();

            if (!$enrollment) {
                return response()->json(['error' => 'No pending enrollment found'], 404);
            }

            if (!$studentModel->user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $user = $studentModel->user;

            // Helper function to safely format dates
            $formatDate = function($date, $format = 'Y-m-d') {
                if (!$date) return null;
                if ($date instanceof \Carbon\Carbon) {
                    return $date->format($format);
                }
                try {
                    return \Carbon\Carbon::parse($date)->format($format);
                } catch (\Exception $e) {
                    return $date;
                }
            };

            $data = [
                'student' => [
                    'id' => $studentModel->id,
                    'lrn' => $studentModel->lrn,
                    'gender' => $studentModel->gender,
                    'birthdate' => $formatDate($studentModel->birthdate),
                    'birth_place' => $studentModel->birth_place,
                    'nationality' => $studentModel->nationality,
                    'ethnicity' => $studentModel->ethnicity,
                    'mother_tongue' => $studentModel->mother_tongue,
                    'remarks' => $studentModel->remarks,
                    'religion' => $studentModel->religion,
                    'street_address' => $studentModel->street_address,
                    'barangay' => $studentModel->barangay,
                    'city' => $studentModel->city,
                    'province' => $studentModel->province,
                    'zip_code' => $studentModel->zip_code,
                    'guardian_name' => $studentModel->guardian_name,
                    'guardian_relationship' => $studentModel->guardian_relationship,
                    'guardian_contact' => $studentModel->guardian_contact,
                    'father_name' => $studentModel->father_name,
                    'father_occupation' => $studentModel->father_occupation,
                    'father_contact' => $studentModel->father_contact,
                    'mother_name' => $studentModel->mother_name,
                    'mother_occupation' => $studentModel->mother_occupation,
                    'mother_contact' => $studentModel->mother_contact,
                    'emergency_contact_name' => $studentModel->emergency_contact_name,
                    'emergency_contact_relationship' => $studentModel->emergency_contact_relationship,
                    'emergency_contact_number' => $studentModel->emergency_contact_number,
                    'type' => $enrollment->type ?? 'N/A',
                    'previous_school' => $enrollment->previous_school ?? null,
                    'enrollment_date' => $formatDate($enrollment->enrollment_date),
                    'created_at' => $formatDate($studentModel->created_at, 'Y-m-d H:i:s'),
                    'user' => [
                        'first_name' => $user->first_name,
                        'last_name' => $user->last_name,
                        'middle_name' => $user->middle_name,
                        'suffix' => $user->suffix,
                        'email' => $user->email,
                        'username' => $user->username,
                        'birthday' => $formatDate($user->birthday),
                    ],
                    'grade_level' => $studentModel->gradeLevel ? ['id' => $studentModel->gradeLevel->id, 'name' => $studentModel->gradeLevel->name] : null,
                    'documents' => [
                        'birth_certificate' => $studentModel->birth_certificate_path ? $this->getDocumentUrl($request, $studentModel->birth_certificate_path) : null,
                        'report_card' => $studentModel->report_card_path ? $this->getDocumentUrl($request, $studentModel->report_card_path) : null,
                        'good_moral' => $studentModel->good_moral_path ? $this->getDocumentUrl($request, $studentModel->good_moral_path) : null,
                        'transfer_credential' => $studentModel->transfer_credential_path ? $this->getDocumentUrl($request, $studentModel->transfer_credential_path) : null,
                    ],
                ],
                'full_name' => trim("{$user->last_name}, {$user->first_name} " . ($user->middle_name ? substr($user->middle_name, 0, 1) . '.' : '')),
                'age' => $studentModel->birthdate ? \Carbon\Carbon::parse($studentModel->birthdate)->age : null,
                'photo_url' => $user->photo ? profile_photo_url($user->photo) : null,
            ];

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Exception in details method', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function approve(Request $request, Student $student)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'remarks' => 'nullable|string|max:50',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $schoolYearId = $validated['school_year_id'];

        $section = Section::find($validated['section_id']);

        if (!$section || !$section->is_active) {
            return redirect()->back()->with('error', 'Selected section is not available.');
        }

        // Check capacity for THIS school year only
        $currentCount = Enrollment::where('section_id', $section->id)
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'enrolled')
            ->count();

        if ($currentCount >= $section->capacity) {
            return redirect()->back()->with('error', 'Selected section is already full for this school year.');
        }

        $enrollment = $student->enrollments()
            ->where('school_year_id', $schoolYearId)
            ->where('status', 'pending')
            ->first();

        if (!$enrollment) {
            return redirect()->back()->with('error', 'No pending enrollment found for this school year.');
        }

        DB::beginTransaction();
        try {
            // Update student status and current section
            $student->update([
                'status' => 'active',
                'section_id' => $validated['section_id'],
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Update enrollment record
            $enrollment->update([
                'status' => 'enrolled',
                'section_id' => $validated['section_id'],
                'enrollment_date' => now(),
                'remarks' => $validated['remarks'] ?? null,
            ]);

            // Activate the user account so they can log in
            if ($student->user) {
                $student->user->update([
                    'status' => 'active',
                    'is_active' => true,
                ]);
            }

            DB::commit();

            $lastName = $student->user?->last_name ?? $student->last_name ?? 'Student';
            $schoolYear = SchoolYear::find($schoolYearId);
            return redirect()->back()->with('success', "Student {$lastName} enrolled in {$section->name} for {$schoolYear->name}.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Enrollment failed', ['student_id' => $student->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to enroll student.');
        }
    }

    public function reject(Request $request, Student $student)
    {
        $schoolYearId = $request->input('school_year_id') ?? SchoolYear::where('is_active', true)->first()?->id;

        DB::beginTransaction();
        try {
            $enrollment = $student->enrollments()
                ->where('school_year_id', $schoolYearId)
                ->where('status', 'pending')
                ->first();

            if ($enrollment) {
                $enrollment->update(['status' => 'rejected']);
            }

            // Only deactivate student if no pending enrollments in ANY school year
            if (!$student->enrollments()->where('status', 'pending')->exists()) {
                $student->update(['status' => 'inactive']);
            }

            // Deactivate the user account so they appear as inactive on the Users page
            if ($student->user) {
                $student->user->update([
                    'status' => 'inactive',
                    'is_active' => false,
                ]);
            }

            DB::commit();

            $lastName = $student->user?->last_name ?? $student->last_name ?? 'Student';
            return redirect()->back()->with('success', "Student {$lastName} rejected.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Rejection failed', ['student_id' => $student->id, 'error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to reject student.');
        }
    }

    public function destroy(Student $student)
    {
        if ($student->status !== 'inactive') {
            return redirect()->back()->with('error','Only inactive registrations can be deleted.');
        }

        try {
            if ($student->user) {
                // Photo is stored as base64 in DB, no file to delete
                $student->user->delete();
            }
            $student->delete();

            return redirect()->back()->with('success','Registration deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error','Failed to delete registration.');
        }
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $schoolYearId = $request->school_year_id;
        $schoolYear = SchoolYear::find($schoolYearId);

        // Get all pending enrollments for this school year
        $pendingEnrollments = Enrollment::where('school_year_id', $schoolYearId)
            ->where('status', 'pending')
            ->with(['student.gradeLevel'])
            ->get();

        // Load active sections with their enrollment counts for this school year
        $sections = Section::with(['gradeLevel'])
            ->withCount(['enrollments' => function ($query) use ($schoolYearId) {
                $query->where('school_year_id', $schoolYearId)
                      ->where('status', 'enrolled');
            }])
            ->where('is_active', true)
            ->get();

        $approved = 0;
        $skipped = 0;
        $skippedNoSection = 0;

        foreach ($pendingEnrollments as $enrollment) {
            $student = $enrollment->student;

            if (!$student) {
                $skipped++;
                continue;
            }

            // Find available sections for the student's grade level
            $gradeLevelId = $enrollment->grade_level_id ?? $student->grade_level_id;

            if (!$gradeLevelId) {
                $skipped++;
                continue;
            }

            // Find an available section for the student's grade level
            $section = null;
            foreach ($sections->where('grade_level_id', $gradeLevelId)->sortBy('enrollments_count') as $candidate) {
                $currentCount = Enrollment::where('section_id', $candidate->id)
                    ->where('school_year_id', $schoolYearId)
                    ->where('status', 'enrolled')
                    ->count();
                if ($currentCount < $candidate->capacity) {
                    $section = $candidate;
                    break;
                }
            }

            if (!$section) {
                $skippedNoSection++;
                continue;
            }

            DB::beginTransaction();
            try {
                // Approve enrollment
                $enrollment->update([
                    'status' => 'enrolled',
                    'section_id' => $section->id,
                    'enrollment_date' => now(),
                ]);

                // Update student
                $student->update([
                    'status' => 'active',
                    'section_id' => $section->id,
                ]);

                // Activate the user account
                if ($student->user) {
                    $student->user->update([
                        'status' => 'active',
                        'is_active' => true,
                    ]);
                }

                DB::commit();
                $approved++;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Bulk approval failed for student', ['student_id' => $student->id, 'error' => $e->getMessage()]);
                $skipped++;
            }
        }

        $message = "{$approved} students approved successfully for {$schoolYear->name}.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped (error or no matching section).";
        }
        if ($skippedNoSection > 0) {
            $message .= " {$skippedNoSection} skipped (no available section for grade level).";
        }

        return back()->with('success', $message);
    }

    /**
     * Generate a document URL that works for both HTTP (local) and HTTPS (production).
     */
    private function getDocumentUrl(Request $request, string $path): string
    {
        return '//' . $request->getHttpHost() . $request->getBasePath() . '/storage/' . $path;
    }
}