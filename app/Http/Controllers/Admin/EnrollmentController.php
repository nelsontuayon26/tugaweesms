<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\GradeLevel;
use App\Services\QrCodeService;

class EnrollmentController extends Controller
{
    //

     protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show enrollment form (accessed via QR code scan)
     */
    public function showForm(Request $request, string $token)
    {
        // Validate QR code token
        $qrCode = $this->qrCodeService->validateToken($token);

        if (!$qrCode) {
            return view('enrollment.invalid', ['message' => 'Invalid or expired QR code.']);
        }

        $schoolYear = $qrCode->schoolYear;
        
        // Get available grade levels
        $gradeLevels = GradeLevel::orderBy('order')->get();

        return view('enrollment.form', compact('token', 'schoolYear', 'gradeLevels'));
    }

    /**
     * Get subjects for selected grade level (AJAX)
     */
    public function getSubjects(Request $request)
    {
        $request->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
        ]);

        $subjects = Subject::where('grade_level_id', $request->grade_level_id)
            ->get(['id', 'name', 'code', 'description']);

        return response()->json([
            'success' => true,
            'subjects' => $subjects,
        ]);
    }

    /**
     * Get sections for selected grade level (AJAX)
     */
    public function getSections(Request $request)
    {
        $request->validate([
            'grade_level_id' => 'required|exists:grade_levels,id',
        ]);

        $sections = \App\Models\Section::where('grade_level_id', $request->grade_level_id)
            ->where('is_active', true)
            ->get(['id', 'name', 'capacity']);

        return response()->json([
            'success' => true,
            'sections' => $sections,
        ]);
    }

    /**
     * Submit enrollment application
     */
    public function submit(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female',
            'address' => 'required|string',
            'contact_number' => 'required|string',
            'guardian_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string',
            'grade_level_id' => 'required|exists:grade_levels,id',
            'section_id' => 'nullable|exists:sections,id',
            'previous_school' => 'nullable|string|max:255',
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id',
        ]);

        // Validate QR code
        $qrCode = $this->qrCodeService->validateToken($request->qr_token);
        
        if (!$qrCode) {
            return redirect()->back()->with('error', 'Invalid or expired QR code.')->withInput();
        }

        $schoolYear = $qrCode->schoolYear;

        try {
            DB::beginTransaction();

            // Check if student already has pending/enrolled status for this school year
            $existingEnrollment = \App\Models\Enrollment::whereHas('student', function($q) use ($request) {
                $q->where('first_name', $request->first_name)
                  ->where('last_name', $request->last_name)
                  ->where('birthdate', $request->birthdate);
            })
            ->where('school_year_id', $schoolYear->id)
            ->whereIn('status', ['pending', 'enrolled'])
            ->first();

            if ($existingEnrollment) {
                return redirect()->back()
                    ->with('error', 'You already have a pending or approved enrollment for this school year.')
                    ->withInput();
            }

            // Generate LRN for new QR enrollment student
            $lrn = $this->generateLRN();

            // Create or update student record using LRN as unique key
            $student = \App\Models\Student::updateOrCreate(
                [
                    'lrn' => $lrn,
                ],
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'birthdate' => $request->birthdate,
                    'gender' => $request->gender,
                    'address' => $request->address,
                    'contact_number' => $request->contact_number,
                    'guardian_name' => $request->guardian_name,
                    'guardian_contact' => $request->guardian_contact,
                    'status' => 'inactive',
                ]
            );

            // Create enrollment record
            $enrollment = \App\Models\Enrollment::create([
                'school_year_id' => $schoolYear->id,
                'student_id' => $student->id,
                'grade_level_id' => $request->grade_level_id,
                'section_id' => $request->section_id,
                'type' => $student->enrollments()->count() > 1 ? 'continuing' : 'new',
                'status' => 'pending',
                'previous_school' => $request->previous_school,
                'enrollment_date' => now(),
            ]);

            // Attach subjects if provided
            if ($request->has('subjects')) {
                $enrollment->subjects()->attach($request->subjects);
            }

            DB::commit();

            return redirect()->route('enrollment.success.qr', ['reference' => $enrollment->id])
                ->with('success', 'Your enrollment application has been submitted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to submit enrollment: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show success page
     */
    public function success(Request $request)
    {
        $enrollment = \App\Models\Enrollment::with(['student', 'schoolYear', 'gradeLevel'])
            ->findOrFail($request->reference);

        return view('enrollment.qr-success', compact('enrollment'));
    }





    /**
     * Generate unique LRN
     */
    private function generateLRN(): string
    {
        $year = now()->year;
        $prefix = $year;

        $lastStudent = \App\Models\Student::where('lrn', 'like', $prefix . '%')->orderByDesc('lrn')->first();

        if ($lastStudent) {
            $lastNumber = (int) substr($lastStudent->lrn, 4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $lrn = $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);

        // Ensure uniqueness (retry if collision occurs)
        $attempts = 0;
        while (\App\Models\Student::where('lrn', $lrn)->exists() && $attempts < 100) {
            $newNumber++;
            $lrn = $prefix . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
            $attempts++;
        }

        return $lrn;
    }

    public function assignSection(Request $request, $id)
{
    $request->validate([
        'section_id' => 'required|exists:sections,id'
    ]);

    $enrollment = \App\Models\Enrollment::findOrFail($id);
    $student = $enrollment->student;

    // Assign section to enrollment
    $enrollment->update([
        'section_id' => $request->section_id,
        'status' => 'enrolled'
    ]);

    // ALSO assign to student table
    $student->update([
        'section_id' => $request->section_id,
        'status' => 'active'
    ]);

    return back()->with('success', 'Student assigned to section successfully!');
}
}
