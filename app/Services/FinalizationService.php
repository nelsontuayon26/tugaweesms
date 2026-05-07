<?php

namespace App\Services;

use App\Models\Section;
use App\Models\SchoolYear;
use App\Models\SectionFinalization;
use App\Models\SchoolYearClosure;
use App\Models\Grade;
use App\Models\CoreValue;
use App\Models\Attendance;
use App\Models\AttendanceSchoolDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FinalizationService
{
    /**
     * Get or create section finalization record
     */
    public function getOrCreateFinalization(int $sectionId, int $schoolYearId): SectionFinalization
    {
        $section = Section::findOrFail($sectionId);
        
        $finalization = SectionFinalization::firstOrCreate(
            [
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ],
            [
                'teacher_id' => $section->teacher_id,
            ]
        );

        return $finalization;
    }

    /**
     * Get or create school year closure record
     */
    public function getOrCreateClosure(int $schoolYearId): SchoolYearClosure
    {
        $closure = SchoolYearClosure::firstOrCreate(
            ['school_year_id' => $schoolYearId],
            [
                'total_sections' => Section::where('is_active', true)->count(),
                'status' => 'pending',
            ]
        );

        return $closure;
    }

    /**
     * Validate if kindergarten assessments can be finalized for a section
     */
    public function validateKindergartenFinalization(int $sectionId, int $schoolYearId): array
    {
        $section = Section::with(['students' => function($query) {
            $query->whereNotIn('status', ['completed', 'inactive']);
        }])->findOrFail($sectionId);
        
        $students = $section->students;
        
        $errors = [];
        $warnings = [];

        if ($students->isEmpty()) {
            $errors[] = 'No active students found in this section.';
            return ['valid' => false, 'errors' => $errors, 'warnings' => $warnings];
        }

        // Get all kindergarten domains from config
        $kinderConfig = config('kindergarten.domains');
        $studentIds = $students->pluck('id')->toArray();
        
        // Bulk fetch all kindergarten assessments in a single query
        $existingRatings = \App\Models\KindergartenDomain::where('school_year_id', $schoolYearId)
            ->whereIn('student_id', $studentIds)
            ->whereIn('quarter', [1, 2, 3, 4])
            ->whereNotNull('rating')
            ->get()
            ->keyBy(function ($rating) {
                return "{$rating->student_id}-{$rating->domain}-{$rating->indicator_key}-{$rating->quarter}";
            });

        // Check for missing ratings using the pre-loaded collection
        foreach ($students as $student) {
            foreach ($kinderConfig as $domainKey => $domainData) {
                // Check indicators in subdomains
                if (isset($domainData['subdomains'])) {
                    foreach ($domainData['subdomains'] as $subdomainKey => $subdomainData) {
                        foreach ($subdomainData['indicators'] as $indicatorKey => $indicatorText) {
                            for ($quarter = 1; $quarter <= 4; $quarter++) {
                                $key = "{$student->id}-{$domainKey}-{$indicatorKey}-{$quarter}";
                                if (!$existingRatings->has($key)) {
                                    $errors[] = "Missing rating for {$student->user->full_name} - {$domainData['name']['cebuano']} (Q{$quarter})";
                                }
                            }
                        }
                    }
                }
                // Check direct indicators
                elseif (isset($domainData['indicators'])) {
                    foreach ($domainData['indicators'] as $indicatorKey => $indicatorText) {
                        for ($quarter = 1; $quarter <= 4; $quarter++) {
                            $key = "{$student->id}-{$domainKey}-{$indicatorKey}-{$quarter}";
                            if (!$existingRatings->has($key)) {
                                $errors[] = "Missing rating for {$student->user->full_name} - {$domainData['name']['cebuano']} (Q{$quarter})";
                            }
                        }
                    }
                }
            }
        }

        // Limit errors to prevent overwhelming output
        if (count($errors) > 10) {
            $remaining = count($errors) - 10;
            $errors = array_slice($errors, 0, 10);
            $errors[] = "... and {$remaining} more missing ratings.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Finalize kindergarten assessments for a section
     */
    public function finalizeKindergarten(int $sectionId, int $schoolYearId, int $userId): array
    {
        $validation = $this->validateKindergartenFinalization($sectionId, $schoolYearId);
        
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Cannot finalize kindergarten assessments. Please fix the errors.',
                'errors' => $validation['errors'],
            ];
        }

        try {
            DB::beginTransaction();

            $finalization = $this->getOrCreateFinalization($sectionId, $schoolYearId);
            $finalization->update([
                'grades_finalized' => true,
                'grades_finalized_at' => now(),
            ]);

            $this->checkAndUpdateFullFinalization($finalization, $userId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Kindergarten assessments finalized successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to finalize kindergarten assessments', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to finalize kindergarten assessments: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate if grades can be finalized for a section
     */
    public function validateGradesFinalization(int $sectionId, int $schoolYearId): array
    {
        $section = Section::with(['students' => function($query) {
            $query->whereNotIn('status', ['completed', 'inactive']);
        }, 'gradeLevel.subjects'])->findOrFail($sectionId);
        
        $students = $section->students;
        $subjects = $section->gradeLevel->subjects ?? collect();
        
        $errors = [];
        $warnings = [];

        if ($students->isEmpty()) {
            $errors[] = 'No active students found in this section.';
            return ['valid' => false, 'errors' => $errors, 'warnings' => $warnings];
        }

        // Bulk fetch all grades in a single query
        $studentIds = $students->pluck('id')->toArray();
        $subjectIds = $subjects->pluck('id')->toArray();
        
        $existingGrades = Grade::where('section_id', $sectionId)
            ->where('school_year_id', $schoolYearId)
            ->where('component_type', 'final_grade')
            ->whereIn('student_id', $studentIds)
            ->whereIn('subject_id', $subjectIds)
            ->whereIn('quarter', [1, 2, 3, 4])
            ->whereNotNull('final_grade')
            ->get()
            ->keyBy(function ($grade) {
                return "{$grade->student_id}-{$grade->subject_id}-{$grade->quarter}";
            });

        // Check for missing grades using the pre-loaded collection
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                for ($quarter = 1; $quarter <= 4; $quarter++) {
                    $key = "{$student->id}-{$subject->id}-{$quarter}";
                    if (!$existingGrades->has($key)) {
                        $errors[] = "Missing grade for {$student->user->full_name} - {$subject->name} (Q{$quarter})";
                    }
                }
            }
        }

        // Limit errors to prevent overwhelming output
        if (count($errors) > 10) {
            $remaining = count($errors) - 10;
            $errors = array_slice($errors, 0, 10);
            $errors[] = "... and {$remaining} more missing grades.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Validate if attendance can be finalized for a section
     */
    public function validateAttendanceFinalization(int $sectionId, int $schoolYearId): array
    {
        $section = Section::with(['students' => function($query) {
            $query->whereNotIn('status', ['completed', 'inactive']);
        }])->findOrFail($sectionId);
        
        $students = $section->students;
        
        $errors = [];
        $warnings = [];

        // Check if school days are configured for each month
        $schoolYear = SchoolYear::findOrFail($schoolYearId);
        $startDate = Carbon::parse($schoolYear->start_date);
        $endDate = Carbon::parse($schoolYear->end_date ?? now());
        
        // Bulk fetch all school days for the section in one query
        $schoolDaysRecords = AttendanceSchoolDay::where([
            'section_id' => $sectionId,
            'school_year_id' => $schoolYearId,
        ])->get()->keyBy(function ($record) {
            return "{$record->year}-{$record->month}";
        });
        
        $current = $startDate->copy();
        while ($current->lessThanOrEqualTo($endDate)) {
            $key = "{$current->year}-{$current->month}";
            if (!$schoolDaysRecords->has($key)) {
                $warnings[] = "School days not configured for {$current->format('F Y')}";
            }
            $current->addMonth();
        }

        // Bulk check attendance records
        if ($students->isNotEmpty()) {
            $studentIds = $students->pluck('id')->toArray();
            
            $attendanceCounts = Attendance::where('school_year_id', $schoolYearId)
                ->whereIn('student_id', $studentIds)
                ->selectRaw('student_id, COUNT(*) as count')
                ->groupBy('student_id')
                ->pluck('count', 'student_id');
            
            foreach ($students as $student) {
                if (!$attendanceCounts->has($student->id)) {
                    $warnings[] = "No attendance records for {$student->user->full_name}";
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Validate if core values can be finalized for a section
     */
    public function validateCoreValuesFinalization(int $sectionId, int $schoolYearId): array
    {
        $section = Section::with(['students' => function($query) {
            $query->whereNotIn('status', ['completed', 'inactive']);
        }])->findOrFail($sectionId);
        
        $students = $section->students;
        
        $errors = [];
        $warnings = [];
        $coreValues = ['Maka-Diyos', 'Makatao', 'Maka-Kalikasan', 'Maka-bansa'];

        // Bulk fetch all core value ratings in one query
        $studentIds = $students->pluck('id')->toArray();
        
        $existingRatings = CoreValue::where('school_year_id', $schoolYearId)
            ->whereIn('student_id', $studentIds)
            ->whereIn('core_value', $coreValues)
            ->whereIn('quarter', [1, 2, 3, 4])
            ->get()
            ->keyBy(function ($rating) {
                return "{$rating->student_id}-{$rating->core_value}-{$rating->quarter}";
            });

        // Check for missing ratings using the pre-loaded collection
        foreach ($students as $student) {
            foreach ($coreValues as $coreValue) {
                for ($quarter = 1; $quarter <= 4; $quarter++) {
                    $key = "{$student->id}-{$coreValue}-{$quarter}";
                    if (!$existingRatings->has($key)) {
                        $errors[] = "Missing core value rating for {$student->user->full_name} - {$coreValue} (Q{$quarter})";
                    }
                }
            }
        }

        // Limit errors
        if (count($errors) > 10) {
            $remaining = count($errors) - 10;
            $errors = array_slice($errors, 0, 10);
            $errors[] = "... and {$remaining} more missing ratings.";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }

    /**
     * Finalize grades for a section
     */
    public function finalizeGrades(int $sectionId, int $schoolYearId, int $userId): array
    {
        $validation = $this->validateGradesFinalization($sectionId, $schoolYearId);
        
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Cannot finalize grades. Please fix the errors.',
                'errors' => $validation['errors'],
            ];
        }

        try {
            DB::beginTransaction();

            $finalization = $this->getOrCreateFinalization($sectionId, $schoolYearId);
            $finalization->update([
                'grades_finalized' => true,
                'grades_finalized_at' => now(),
            ]);

            $this->checkAndUpdateFullFinalization($finalization, $userId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Grades finalized successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to finalize grades', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to finalize grades: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Finalize attendance for a section
     */
    public function finalizeAttendance(int $sectionId, int $schoolYearId, int $userId): array
    {
        $validation = $this->validateAttendanceFinalization($sectionId, $schoolYearId);
        
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Cannot finalize attendance.',
                'errors' => $validation['errors'],
            ];
        }

        try {
            DB::beginTransaction();

            $finalization = $this->getOrCreateFinalization($sectionId, $schoolYearId);
            $finalization->update([
                'attendance_finalized' => true,
                'attendance_finalized_at' => now(),
            ]);

            $this->checkAndUpdateFullFinalization($finalization, $userId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Attendance finalized successfully.',
                'warnings' => $validation['warnings'],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to finalize attendance', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to finalize attendance: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Finalize core values for a section
     */
    public function finalizeCoreValues(int $sectionId, int $schoolYearId, int $userId): array
    {
        $validation = $this->validateCoreValuesFinalization($sectionId, $schoolYearId);
        
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => 'Cannot finalize core values. Please fix the errors.',
                'errors' => $validation['errors'],
            ];
        }

        try {
            DB::beginTransaction();

            $finalization = $this->getOrCreateFinalization($sectionId, $schoolYearId);
            $finalization->update([
                'core_values_finalized' => true,
                'core_values_finalized_at' => now(),
            ]);

            $this->checkAndUpdateFullFinalization($finalization, $userId);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Core values finalized successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to finalize core values', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to finalize core values: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if all components are finalized and update full finalization status
     */
    private function checkAndUpdateFullFinalization(SectionFinalization $finalization, int $userId): void
    {
        if ($finalization->grades_finalized && 
            $finalization->core_values_finalized) {
            
            $finalization->update([
                'is_fully_finalized' => true,
                'finalized_at' => now(),
                'finalized_by' => $userId,
                'is_locked' => true,
                'locked_at' => now(),
            ]);

            // Update closure progress
            $closure = $this->getOrCreateClosure($finalization->school_year_id);
            $closure->updateProgress();
        }
    }

    /**
     * Unlock a section for editing (admin only)
     */
    public function unlockSection(int $sectionId, int $schoolYearId, int $adminId, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $finalization = SectionFinalization::where([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ])->first();

            if (!$finalization) {
                return [
                    'success' => false,
                    'message' => 'No finalization record found.',
                ];
            }

            $finalization->update([
                'is_locked' => false,
                'unlocked_at' => now(),
                'unlocked_by' => $adminId,
                'unlock_reason' => $reason,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Section unlocked successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to unlock section', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to unlock section: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Re-lock a section after admin edits
     */
    public function relockSection(int $sectionId, int $schoolYearId): array
    {
        try {
            DB::beginTransaction();

            $finalization = SectionFinalization::where([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ])->first();

            if (!$finalization) {
                return [
                    'success' => false,
                    'message' => 'No finalization record found.',
                ];
            }

            $finalization->update([
                'is_locked' => true,
                'locked_at' => now(),
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Section re-locked successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to relock section', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to relock section: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Unlock a specific component for editing (admin only)
     * 
     * @param int $sectionId
     * @param int $schoolYearId
     * @param string $component - 'grades', 'attendance', or 'core_values'
     * @param int $adminId
     * @param string|null $reason
     * @return array
     */
    public function unlockComponent(int $sectionId, int $schoolYearId, string $component, int $adminId, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $finalization = SectionFinalization::where([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ])->first();

            if (!$finalization) {
                return [
                    'success' => false,
                    'message' => 'No finalization record found.',
                ];
            }

            $validComponents = ['grades', 'attendance', 'core_values', 'kindergarten'];
            if (!in_array($component, $validComponents)) {
                return [
                    'success' => false,
                    'message' => 'Invalid component. Must be: grades, attendance, core_values, or kindergarten.',
                ];
            }
            
            // Map kindergarten to grades since they share the same field
            $dbComponent = $component === 'kindergarten' ? 'grades' : $component;

            // Update the specific component's finalization status
            $updateData = [
                "{$dbComponent}_finalized" => false,
                "{$dbComponent}_finalized_at" => null,
                "{$dbComponent}_unlocked_at" => now(),
                "{$dbComponent}_unlocked_by" => $adminId,
                "{$dbComponent}_unlock_reason" => $reason,
                'is_locked' => false, // Also unlock the section temporarily
                'unlocked_at' => now(),
                'unlocked_by' => $adminId,
                'unlock_reason' => $reason,
            ];

            // Reset full finalization status
            $updateData['is_fully_finalized'] = false;
            $updateData['finalized_at'] = null;

            $finalization->update($updateData);

            DB::commit();

            $componentNames = [
                'grades' => 'Grades',
                'attendance' => 'Attendance',
                'core_values' => 'Core Values',
                'kindergarten' => 'Kindergarten Assessments',
            ];

            return [
                'success' => true,
                'message' => "{$componentNames[$component]} have been unlocked successfully.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to unlock component', ['error' => $e->getMessage(), 'component' => $component]);
            return [
                'success' => false,
                'message' => 'Failed to unlock component: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Unlock all components at once (admin only)
     * 
     * @param int $sectionId
     * @param int $schoolYearId
     * @param int $adminId
     * @param string|null $reason
     * @return array
     */
    public function unlockAllComponents(int $sectionId, int $schoolYearId, int $adminId, ?string $reason = null): array
    {
        try {
            DB::beginTransaction();

            $finalization = SectionFinalization::where([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ])->first();

            if (!$finalization) {
                return [
                    'success' => false,
                    'message' => 'No finalization record found.',
                ];
            }

            $now = now();
            
            // Unlock all components
            $finalization->update([
                'grades_finalized' => false,
                'grades_finalized_at' => null,
                'grades_unlocked_at' => $now,
                'grades_unlocked_by' => $adminId,
                'grades_unlock_reason' => $reason,
                
                'attendance_finalized' => false,
                'attendance_finalized_at' => null,
                'attendance_unlocked_at' => $now,
                'attendance_unlocked_by' => $adminId,
                'attendance_unlock_reason' => $reason,
                
                'core_values_finalized' => false,
                'core_values_finalized_at' => null,
                'core_values_unlocked_at' => $now,
                'core_values_unlocked_by' => $adminId,
                'core_values_unlock_reason' => $reason,
                
                'is_fully_finalized' => false,
                'finalized_at' => null,
                'is_locked' => false,
                'unlocked_at' => $now,
                'unlocked_by' => $adminId,
                'unlock_reason' => $reason,
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'All components (Grades/Kindergarten, Attendance, and Core Values) have been unlocked successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to unlock all components', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to unlock all components: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Re-lock a specific component after admin edits (admin only)
     * 
     * @param int $sectionId
     * @param int $schoolYearId
     * @param string $component - 'grades', 'attendance', or 'core_values'
     * @return array
     */
    public function relockComponent(int $sectionId, int $schoolYearId, string $component): array
    {
        try {
            DB::beginTransaction();

            $finalization = SectionFinalization::where([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
            ])->first();

            if (!$finalization) {
                return [
                    'success' => false,
                    'message' => 'No finalization record found.',
                ];
            }

            $validComponents = ['grades', 'attendance', 'core_values', 'kindergarten'];
            if (!in_array($component, $validComponents)) {
                return [
                    'success' => false,
                    'message' => 'Invalid component. Must be: grades, attendance, core_values, or kindergarten.',
                ];
            }
            
            // Map kindergarten to grades since they share the same field
            $dbComponent = $component === 'kindergarten' ? 'grades' : $component;

            // Re-finalize the specific component
            $updateData = [
                "{$dbComponent}_finalized" => true,
                "{$dbComponent}_finalized_at" => now(),
            ];

            // Check if all components are now finalized
            if ($finalization->grades_finalized && $finalization->core_values_finalized) {
                $updateData['is_fully_finalized'] = true;
                $updateData['finalized_at'] = now();
                $updateData['is_locked'] = true;
                $updateData['locked_at'] = now();
            }

            $finalization->update($updateData);

            DB::commit();

            $componentNames = [
                'grades' => 'Grades',
                'attendance' => 'Attendance',
                'core_values' => 'Core Values',
                'kindergarten' => 'Kindergarten Assessments',
            ];

            return [
                'success' => true,
                'message' => "{$componentNames[$component]} have been re-locked successfully.",
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to relock component', ['error' => $e->getMessage(), 'component' => $component]);
            return [
                'success' => false,
                'message' => 'Failed to relock component: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if school year can be ended
     */
    public function canEndSchoolYear(int $schoolYearId): array
    {
        $closure = $this->getOrCreateClosure($schoolYearId);
        $closure->updateProgress();

        $pendingSections = SectionFinalization::where('school_year_id', $schoolYearId)
            ->where('is_fully_finalized', false)
            ->with('section', 'section.teacher.user')
            ->get()
            ->filter(function ($finalization) use ($schoolYearId) {
                $section = $finalization->section;
                return $section && $section->is_active && $section->school_year_id == $schoolYearId;
            });

        $allFinalized = $pendingSections->isEmpty();

        return [
            'can_end' => $allFinalized || $closure->isReadyToClose(),
            'all_finalized' => $allFinalized,
            'pending_count' => $pendingSections->count(),
            'pending_sections' => $pendingSections,
            'closure' => $closure,
        ];
    }

    /**
     * Set finalization deadline
     */
    public function setDeadline(int $schoolYearId, Carbon $deadline, bool $autoFinalize = false): array
    {
        try {
            $closure = $this->getOrCreateClosure($schoolYearId);
            
            // Update all pending finalizations with deadline
            SectionFinalization::where('school_year_id', $schoolYearId)
                ->where('is_fully_finalized', false)
                ->update(['deadline_at' => $deadline]);

            $closure->update([
                'finalization_deadline' => $deadline,
                'auto_close_enabled' => $autoFinalize,
                'auto_close_at' => $autoFinalize ? $deadline : null,
            ]);

            return [
                'success' => true,
                'message' => 'Deadline set successfully.',
            ];
        } catch (\Exception $e) {
            Log::error('Failed to set deadline', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Failed to set deadline: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Process auto-finalization for overdue sections
     */
    public function processAutoFinalization(): void
    {
        $overdueFinalizations = SectionFinalization::where('is_fully_finalized', false)
            ->whereNotNull('deadline_at')
            ->where('deadline_at', '<', now())
            ->where('auto_finalized', false)
            ->get();

        foreach ($overdueFinalizations as $finalization) {
            try {
                DB::beginTransaction();

                // Auto-finalize all components
                $finalization->update([
                    'grades_finalized' => true,
                    'grades_finalized_at' => now(),
                    'attendance_finalized' => true,
                    'attendance_finalized_at' => now(),
                    'core_values_finalized' => true,
                    'core_values_finalized_at' => now(),
                    'is_fully_finalized' => true,
                    'finalized_at' => now(),
                    'is_locked' => true,
                    'locked_at' => now(),
                    'auto_finalized' => true,
                ]);

                // Update closure progress
                $closure = $this->getOrCreateClosure($finalization->school_year_id);
                $closure->updateProgress();

                DB::commit();

                Log::info('Auto-finalized section', [
                    'section_id' => $finalization->section_id,
                    'school_year_id' => $finalization->school_year_id,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to auto-finalize section', [
                    'section_id' => $finalization->section_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Check if a section is editable
     */
    public function isSectionEditable(int $sectionId, int $schoolYearId): bool
    {
        $finalization = SectionFinalization::where([
            'section_id' => $sectionId,
            'school_year_id' => $schoolYearId,
        ])->first();

        if (!$finalization) {
            return true;
        }

        return !$finalization->is_locked;
    }

    /**
     * Get finalization status for all sections
     */
    public function getAllSectionsStatus(int $schoolYearId): array
    {
        $sections = Section::with(['teacher.user'])
            ->where('is_active', true)
            ->where('school_year_id', $schoolYearId)
            ->get();

        $finalizations = SectionFinalization::where('school_year_id', $schoolYearId)
            ->get()
            ->keyBy('section_id');

        $result = [];
        foreach ($sections as $section) {
            $finalization = $finalizations->get($section->id);
            
            $result[] = [
                'section' => $section,
                'finalization' => $finalization,
                'status' => $finalization ? $finalization->getStatusBadge() : ['text' => 'Pending', 'class' => 'bg-slate-100 text-slate-600'],
                'completion' => $finalization ? $finalization->getCompletionPercentage() : 0,
            ];
        }

        return $result;
    }
}
