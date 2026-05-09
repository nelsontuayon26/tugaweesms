<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolYear;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Section;
use App\Models\Grade;
use App\Models\SchoolYearQrCode;
use App\Models\PromotionHistory;
use App\Models\SchoolYearClosure;
use App\Models\SectionFinalization;
use App\Services\QrCodeService;
use App\Services\FinalizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SchoolYearController extends Controller
{
    protected $qrCodeService;
    protected $finalizationService;

    public function __construct(QrCodeService $qrCodeService, FinalizationService $finalizationService)
    {
        $this->qrCodeService = $qrCodeService;
        $this->finalizationService = $finalizationService;
    }

    /**
     * Display school year management page
     */
    public function index()
    {
        $schoolYears = SchoolYear::with(['quarters', 'closure'])->orderBy('start_date', 'desc')->paginate(10);
        $activeSchoolYear = SchoolYear::with('quarters')->where('is_active', true)->first();

        // Get closure status if there's an active school year
        $closure = null;
        if ($activeSchoolYear) {
            $closure = $this->finalizationService->getOrCreateClosure($activeSchoolYear->id);
            $closure->updateProgress();
        }

        return view('admin.school-years.index', compact('schoolYears', 'activeSchoolYear', 'closure'));
    }

    /**
     * Display school year closure dashboard
     */
    public function closureDashboard(Request $request)
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();

        if (!$activeSchoolYear) {
            return redirect()->route('admin.school-years.index')
                ->with('error', 'No active school year found.');
        }

        $closure = $this->finalizationService->getOrCreateClosure($activeSchoolYear->id);
        $closure->updateProgress();

        $sectionsStatus = $this->finalizationService->getAllSectionsStatus($activeSchoolYear->id);
        
        $canEnd = $this->finalizationService->canEndSchoolYear($activeSchoolYear->id);

        return view('admin.school-years.closure', compact(
            'activeSchoolYear',
            'closure',
            'sectionsStatus',
            'canEnd'
        ));
    }

    /**
     * Set finalization deadline
     */
    public function setDeadline(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'deadline' => 'required|date|after:today',
            'auto_finalize' => 'boolean',
        ]);

        $deadline = Carbon::parse($request->deadline);
        $autoFinalize = $request->boolean('auto_finalize', false);

        $result = $this->finalizationService->setDeadline(
            $request->school_year_id,
            $deadline,
            $autoFinalize
        );

        if ($result['success']) {
            return redirect()->back()->with('success', 
                'Finalization deadline set to ' . $deadline->format('F d, Y') . 
                ($autoFinalize ? '. Auto-finalization enabled.' : '')
            );
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Unlock a section for editing
     */
    public function unlockSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
            'reason' => 'required|string|max:500',
        ]);

        $result = $this->finalizationService->unlockSection(
            $request->section_id,
            $request->school_year_id,
            auth()->id(),
            $request->reason
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Re-lock a section after admin edits
     */
    public function relockSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $result = $this->finalizationService->relockSection(
            $request->section_id,
            $request->school_year_id
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Unlock a specific component for editing (admin only)
     */
    public function unlockComponent(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
            'component' => 'required|in:grades,attendance,core_values',
            'reason' => 'required|string|max:500',
        ]);

        $result = $this->finalizationService->unlockComponent(
            $request->section_id,
            $request->school_year_id,
            $request->component,
            auth()->id(),
            $request->reason
        );

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Unlock all components at once (admin only)
     */
    public function unlockAllComponents(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
            'reason' => 'required|string|max:500',
        ]);

        $result = $this->finalizationService->unlockAllComponents(
            $request->section_id,
            $request->school_year_id,
            auth()->id(),
            $request->reason
        );

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Re-lock a specific component after admin edits (admin only)
     */
    public function relockComponent(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year_id' => 'required|exists:school_years,id',
            'component' => 'required|in:grades,attendance,core_values',
        ]);

        $result = $this->finalizationService->relockComponent(
            $request->section_id,
            $request->school_year_id,
            $request->component
        );

        if ($result['success']) {
            return redirect()->back()->with('success', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Force end school year (admin override)
     */
    public function forceEndSchoolYear(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
            'reason' => 'required|string|max:1000',
        ]);

        $activeSchoolYear = SchoolYear::findOrFail($request->school_year_id);

        if (!$activeSchoolYear->is_active) {
            return redirect()->back()->with('error', 'This school year is not active.');
        }

        // Log the force end action
        Log::warning('Force ending school year', [
            'school_year_id' => $activeSchoolYear->id,
            'admin_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        // Update closure record
        $closure = $this->finalizationService->getOrCreateClosure($activeSchoolYear->id);
        $closure->update([
            'status' => 'closed',
            'closure_completed_at' => now(),
            'closed_by' => auth()->id(),
            'admin_notes' => $request->reason,
        ]);

        // Proceed with normal end school year logic
        return $this->executeEndSchoolYear($activeSchoolYear);
    }

    /**
     * Assign student to an available section for a grade level
     */
    private function assignSection(int $gradeLevelId, int $schoolYearId): ?int
    {
        $sections = Section::where('grade_level_id', $gradeLevelId)
            ->where('is_active', true)
            ->get();

        foreach ($sections as $section) {
            $count = Enrollment::where('section_id', $section->id)
                ->where('school_year_id', $schoolYearId)
                ->where('status', 'enrolled')
                ->count();

            if ($count < $section->capacity) {
                return $section->id;
            }
        }

        return null;
    }

    /**
     * Start a school year
     */
    public function startSchoolYear(Request $request)
    {
        $request->validate(['school_year_id' => 'required|exists:school_years,id']);

        $schoolYear = SchoolYear::findOrFail($request->school_year_id);

        if ($schoolYear->is_active) {
            return redirect()->back()->with('warning', 'This school year is already active.');
        }

        try {
            DB::beginTransaction();

            // Deactivate other active years
            SchoolYear::where('is_active', true)->update(['is_active' => false]);

            // Activate the selected school year
            $schoolYear->update(['is_active' => true]);

            // Auto-create quarters if they don't exist
            $schoolYear->ensureQuartersExist();

            // Generate QR Code
            $qrCode = $this->qrCodeService->generateForSchoolYear($schoolYear);

            // Initialize closure record
            $closure = $this->finalizationService->getOrCreateClosure($schoolYear->id);
            $closure->update([
                'total_sections' => Section::where('is_active', true)->count(),
                'status' => 'pending',
            ]);

            DB::commit();

            return redirect()->route('admin.school-years.index')
                ->with('success', "School year '{$schoolYear->name}' started. QR code generated.")
                ->with('qr_code', $qrCode);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Start school year failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to start school year.');
        }
    }

    /**
     * End school year with finalization validation
     */
    public function endSchoolYear(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $activeSchoolYear = SchoolYear::findOrFail($request->school_year_id);

        if (!$activeSchoolYear->is_active) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This school year is not active.'
                ], 422);
            }
            return redirect()->back()->with('error', 'This school year is not active.');
        }

        // Check if all sections are finalized
        $canEnd = $this->finalizationService->canEndSchoolYear($activeSchoolYear->id);

        // STRICT VALIDATION: Cannot end if not all sections are finalized
        if (!$canEnd['all_finalized']) {
            $pendingSections = $canEnd['pending_sections'] ?? collect();
            $pendingDetails = [];
            
            foreach ($pendingSections as $pf) {
                $sectionName = $pf->section->name ?? 'Unknown Section';
                $teacherName = $pf->section->teacher->user->full_name ?? 'No Adviser';
                $missing = [];
                
                if (!$pf->grades_finalized) $missing[] = 'grades';
                if (!$pf->core_values_finalized) $missing[] = 'core values';
                
                $pendingDetails[] = [
                    'section' => $sectionName,
                    'teacher' => $teacherName,
                    'missing' => $missing
                ];
            }
            
            $message = 'Cannot end school year. ' . $canEnd['pending_count'] . ' section(s) still pending finalization: ';
            $message .= collect($pendingDetails)->map(function($d) {
                return $d['section'] . ' (missing: ' . implode(', ', $d['missing']) . ')';
            })->implode(', ');

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'pending_count' => $canEnd['pending_count'],
                    'pending_details' => $pendingDetails
                ], 422);
            }
            
            return redirect()->route('admin.school-year.closure')
                ->with('error', $message);
        }

        // Update closure record
        $closure = $canEnd['closure'];
        $closure->update([
            'status' => 'closing',
            'closure_started_at' => now(),
        ]);

        return $this->executeEndSchoolYear($activeSchoolYear, $request);
    }

    /**
     * Execute the actual end school year logic
     */
    private function executeEndSchoolYear(SchoolYear $activeSchoolYear, Request $request = null)
    {
        try {
            DB::beginTransaction();

            // Prevent double-processing an already-closed school year
            $existingClosure = SchoolYearClosure::where('school_year_id', $activeSchoolYear->id)
                ->where('status', 'closed')
                ->first();
            if ($existingClosure) {
                DB::rollBack();
                $message = 'This school year has already been closed.';
                if ($request && ($request->ajax() || $request->wantsJson())) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            $nextSchoolYear = SchoolYear::where('start_date', '>', $activeSchoolYear->start_date)
                ->orderBy('start_date')
                ->first();

            if (!$nextSchoolYear) {
                DB::rollBack();
                $message = 'Please create the next school year first.';
                if ($request && ($request->ajax() || $request->wantsJson())) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            $GRADE_6_ID = 7; // Adjust according to your DB
            $PASSING_GRADE = 75; // Minimum grade to pass

            $enrollments = Enrollment::where('school_year_id', $activeSchoolYear->id)
                ->where('status', 'enrolled')
                ->get();

            $graduatedIds = [];
            $promotedCount = 0;
            $retainedCount = 0;
            $retainedDetails = [];

            foreach ($enrollments as $enrollment) {
                // Calculate student's general average across all subjects and quarters
                $generalAverage = $this->calculateStudentGeneralAverage(
                    $enrollment->student_id, 
                    $activeSchoolYear->id
                );

                $isPassing = $generalAverage >= $PASSING_GRADE;

                // Handle Grade 6 students (Graduation)
                if ($enrollment->grade_level_id == $GRADE_6_ID) {
                    // All Grade 6 students graduate, but track if they passed or failed
                    $graduatedIds[] = $enrollment->student_id;
                    
                    // Update current enrollment status based on passing/failing
                    $enrollment->update([
                        'status' => 'completed',
                        'remarks' => $isPassing ? 'Graduated' : 'Graduated (Below 75% average)'
                    ]);
                    continue;
                }

                // Determine if student is promoted or retained
                if ($isPassing) {
                    // PROMOTED: Student moves to next grade level
                    $nextGradeLevelId = $enrollment->grade_level_id + 1;

                    // Skip if enrollment already exists for next year
                    if (Enrollment::where('student_id', $enrollment->student_id)
                        ->where('school_year_id', $nextSchoolYear->id)
                        ->exists()) {
                        continue;
                    }

                    $sectionId = $this->assignSection($nextGradeLevelId, $nextSchoolYear->id);

                    // Create new enrollment for next year
                    Enrollment::create([
                        'student_id' => $enrollment->student_id,
                        'school_year_id' => $nextSchoolYear->id,
                        'grade_level_id' => $nextGradeLevelId,
                        'section_id' => $sectionId,
                        'type' => 'continuing',
                        'status' => 'pending',
                        'previous_school' => null,
                        'enrollment_date' => now(),
                    ]);

                    // Record promotion history
                    PromotionHistory::create([
                        'student_id' => $enrollment->student_id,
                        'from_school_year_id' => $activeSchoolYear->id,
                        'to_school_year_id' => $nextSchoolYear->id,
                        'from_grade_level_id' => $enrollment->grade_level_id,
                        'to_grade_level_id' => $nextGradeLevelId,
                    ]);

                    // Update current enrollment status
                    $enrollment->update([
                        'status' => 'completed',
                        'remarks' => 'Promoted (GA: ' . number_format($generalAverage, 2) . ')'
                    ]);

                    // Update student record
                    Student::where('id', $enrollment->student_id)->update([
                        'status' => 'inactive',
                        'grade_level_id' => $nextGradeLevelId,
                    ]);

                    $promotedCount++;
                } else {
                    // RETAINED: Student stays in same grade level
                    $currentGradeLevelId = $enrollment->grade_level_id;

                    // Skip if enrollment already exists for next year
                    if (Enrollment::where('student_id', $enrollment->student_id)
                        ->where('school_year_id', $nextSchoolYear->id)
                        ->exists()) {
                        continue;
                    }

                    $sectionId = $this->assignSection($currentGradeLevelId, $nextSchoolYear->id);

                    // Create new enrollment for next year (same grade level)
                    Enrollment::create([
                        'student_id' => $enrollment->student_id,
                        'school_year_id' => $nextSchoolYear->id,
                        'grade_level_id' => $currentGradeLevelId,
                        'section_id' => $sectionId,
                        'type' => 'retained', // Mark as retained student
                        'status' => 'pending',
                        'previous_school' => null,
                        'enrollment_date' => now(),
                    ]);

                    // Record retention in promotion history (to_grade same as from_grade)
                    PromotionHistory::create([
                        'student_id' => $enrollment->student_id,
                        'from_school_year_id' => $activeSchoolYear->id,
                        'to_school_year_id' => $nextSchoolYear->id,
                        'from_grade_level_id' => $currentGradeLevelId,
                        'to_grade_level_id' => $currentGradeLevelId, // Same grade = retained
                    ]);

                    // Update current enrollment status
                    $enrollment->update([
                        'status' => 'completed',
                        'remarks' => 'Retained (GA: ' . number_format($generalAverage, 2) . ')'
                    ]);

                    // Update student record (stay in same grade)
                    Student::where('id', $enrollment->student_id)->update([
                        'status' => 'inactive',
                        'grade_level_id' => $currentGradeLevelId, // Keep same grade level
                    ]);

                    // Track retained students for reporting
                    $retainedDetails[] = [
                        'student_id' => $enrollment->student_id,
                        'name' => $enrollment->student->full_name ?? 'Unknown',
                        'grade_level' => $currentGradeLevelId,
                        'general_average' => $generalAverage,
                    ];

                    $retainedCount++;
                }
            }

            // Update graduated students' status
            if (!empty($graduatedIds)) {
                Student::whereIn('id', $graduatedIds)->update(['status' => 'graduated']);
            }

            // Deactivate QR codes and school year
            SchoolYearQrCode::where('school_year_id', $activeSchoolYear->id)
                ->update(['is_active' => false]);

            $activeSchoolYear->update(['is_active' => false]);

            // Update closure record with detailed summary
            $closureSummary = "{$promotedCount} promoted, {$retainedCount} retained, " . count($graduatedIds) . " graduated.";
            
            $closure = SchoolYearClosure::where('school_year_id', $activeSchoolYear->id)->first();
            if ($closure) {
                $closure->update([
                    'status' => 'closed',
                    'closure_completed_at' => now(),
                    'closed_by' => auth()->id(),
                    'closure_summary' => $closureSummary,
                    'retained_students_count' => $retainedCount,
                    'promoted_students_count' => $promotedCount,
                    'graduated_students_count' => count($graduatedIds),
                ]);
            }

            DB::commit();

            $successMessage = "{$promotedCount} promoted, {$retainedCount} retained, " . count($graduatedIds) . " graduated 🎓.";

            // Return JSON for AJAX requests
            if ($request && ($request->ajax() || $request->wantsJson())) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage,
                    'promoted_count' => $promotedCount,
                    'retained_count' => $retainedCount,
                    'graduated_count' => count($graduatedIds),
                    'retained_details' => $retainedDetails,
                ]);
            }

            return redirect()->route('admin.school-years.index')->with('success', $successMessage);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('End school year failed', ['error' => $e->getMessage()]);
            
            $errorMessage = 'Failed to end school year.';
            
            if ($request && ($request->ajax() || $request->wantsJson())) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    public function setActive(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $schoolYear = SchoolYear::findOrFail($request->school_year_id);

        if ($schoolYear->is_active) {
            return redirect()->back()->with('warning', 'This school year is already active.');
        }

        // Prevent reactivation of closed school years
        $closure = SchoolYearClosure::where('school_year_id', $schoolYear->id)->first();
        if ($closure && $closure->status === 'closed') {
            return redirect()->back()
                ->with('error', 'This school year has been closed and cannot be reactivated. View its data through Reports instead.');
        }

        try {
            DB::beginTransaction();
            SchoolYear::where('is_active', true)->update(['is_active' => false]);
            $schoolYear->update(['is_active' => true]);
            DB::commit();

            return redirect()->back()->with('success', "School year '{$schoolYear->name}' is now active.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Set active school year failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to set active school year.');
        }
    }

    /**
     * Download QR code
     */
    public function downloadQrCode(SchoolYearQrCode $qrCode)
    {
        $disk = \Illuminate\Support\Facades\Storage::disk('public');

        if (!$disk->exists($qrCode->qr_code_image_path)) {
            abort(404, 'QR Code not found.');
        }

        return $disk->download(
            $qrCode->qr_code_image_path,
            "enrollment-qr-{$qrCode->schoolYear->name}.png"
        );
    }

    /**
     * Regenerate QR code
     */
    public function regenerateQrCode(Request $request)
    {
        $request->validate(['school_year_id' => 'required|exists:school_years,id']);

        $schoolYear = SchoolYear::findOrFail($request->school_year_id);

        if (!$schoolYear->is_active) {
            return redirect()->back()->with('error', 'Cannot generate QR code for inactive school year.');
        }

        try {
            $qrCode = $this->qrCodeService->generateForSchoolYear($schoolYear);

            return redirect()->back()->with('success', 'QR Code regenerated successfully.')
                ->with('qr_code', $qrCode);

        } catch (\Exception $e) {
            Log::error('Regenerate QR failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to generate QR code.');
        }
    }

    /**
     * Show edit form for a school year
     */
    public function edit(SchoolYear $schoolYear)
    {
        return view('admin.school-years.edit', compact('schoolYear'));
    }

    /**
     * Update a school year
     */
    public function update(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_years,name,' . $schoolYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        try {
            $schoolYear->update([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
            ]);

            return redirect()->route('admin.school-years.index')
                ->with('success', "School year '{$schoolYear->name}' updated successfully.");
        } catch (\Exception $e) {
            Log::error('Update school year failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update school year.');
        }
    }

    /**
     * Delete a school year
     */
    public function destroy(SchoolYear $schoolYear)
    {
        try {
            // Prevent deleting active school year
            if ($schoolYear->is_active) {
                return redirect()->back()->with('error', 'Cannot delete an active school year. Please deactivate it first.');
            }

            $name = $schoolYear->name;
            $schoolYear->delete();

            return redirect()->route('admin.school-years.index')
                ->with('success', "School year '{$name}' deleted successfully.");
        } catch (\Exception $e) {
            Log::error('Delete school year failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to delete school year.');
        }
    }

    /**
     * Store a newly created school year
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:school_years',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        try {
            $schoolYear = SchoolYear::create([
                'name' => $request->name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'is_active' => $request->boolean('is_active', false),
            ]);

            // Auto-create default quarters
            $schoolYear->ensureQuartersExist();

            return redirect()->back()->with('success', "School year '{$schoolYear->name}' created successfully.");
        } catch (\Exception $e) {
            Log::error('Create school year failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to create school year.');
        }
    }

    /**
     * Calculate student's general average across all subjects for the school year
     * This averages all final grades from all quarters and all subjects
     */
    private function calculateStudentGeneralAverage(int $studentId, int $schoolYearId): float
    {
        // Get all final grades for the student in this school year
        $grades = Grade::where('student_id', $studentId)
            ->where('school_year_id', $schoolYearId)
            ->where('component_type', 'final_grade')
            ->whereNotNull('final_grade')
            ->pluck('final_grade');

        if ($grades->isEmpty()) {
            return 0; // No grades found
        }

        // Calculate average of all final grades
        return round($grades->avg(), 2);
    }

    /**
     * Update quarter dates for a school year
     */
    public function updateQuarters(Request $request, SchoolYear $schoolYear)
    {
        $request->validate([
            'quarters' => 'required|array',
            'quarters.*.quarter_number' => 'required|integer|in:1,2,3,4',
            'quarters.*.start_date' => 'required|date',
            'quarters.*.end_date' => 'required|date|after_or_equal:quarters.*.start_date',
            'quarters.*.name' => 'nullable|string|max:100',
            'quarters.*.notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->quarters as $qData) {
                $schoolYear->quarters()->updateOrCreate(
                    ['quarter_number' => $qData['quarter_number']],
                    [
                        'name' => $qData['name'] ?? null,
                        'start_date' => $qData['start_date'],
                        'end_date' => $qData['end_date'],
                        'notes' => $qData['notes'] ?? null,
                        'is_active' => true,
                    ]
                );
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quarter dates updated successfully.',
                ]);
            }

            return redirect()->back()->with('success', 'Quarter dates updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update quarters failed', ['error' => $e->getMessage()]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update quarters: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update quarter dates.');
        }
    }

    /**
     * Carry forward sections from one school year to another
     */
    public function carryForwardSections(Request $request)
    {
        $request->validate([
            'source_school_year_id' => 'required|exists:school_years,id',
            'target_school_year_id' => 'required|exists:school_years,id|different:source_school_year_id',
        ]);

        $sourceYearId = $request->source_school_year_id;
        $targetYearId = $request->target_school_year_id;

        // Prevent carrying forward to a school year that already has sections
        $existingSectionsCount = Section::where('school_year_id', $targetYearId)->count();

        DB::beginTransaction();
        try {
            $sourceSections = Section::where('school_year_id', $sourceYearId)->get();
            $copiedCount = 0;
            $skippedCount = 0;

            foreach ($sourceSections as $sourceSection) {
                // Skip if a section with same name + grade_level already exists in target year
                $exists = Section::where('school_year_id', $targetYearId)
                    ->where('name', $sourceSection->name)
                    ->where('grade_level_id', $sourceSection->grade_level_id)
                    ->exists();

                if ($exists) {
                    $skippedCount++;
                    continue;
                }

                Section::create([
                    'name' => $sourceSection->name,
                    'grade_level_id' => $sourceSection->grade_level_id,
                    'school_year_id' => $targetYearId,
                    'room_number' => $sourceSection->room_number,
                    'teacher_id' => $sourceSection->teacher_id,
                    'capacity' => $sourceSection->capacity,
                    'is_active' => $sourceSection->is_active,
                ]);

                $copiedCount++;
            }

            DB::commit();

            $message = "{$copiedCount} section(s) copied successfully.";
            if ($skippedCount > 0) {
                $message .= " {$skippedCount} skipped (already exist in target year).";
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Carry forward sections failed', [
                'source' => $sourceYearId,
                'target' => $targetYearId,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to carry forward sections: ' . $e->getMessage());
        }
    }
}
