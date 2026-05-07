<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SchoolYearRolloverService
{
    /**
     * Process end of school year - promote students or graduate them
     */
    public function processEndOfSchoolYear(int $currentSchoolYearId, int $nextSchoolYearId): array
    {
        $stats = [
            'promoted' => 0,
            'graduated' => 0,
            'pending' => 0,
            'errors' => []
        ];

        DB::beginTransaction();

        try {
            $currentSchoolYear = SchoolYear::findOrFail($currentSchoolYearId);
            $nextSchoolYear = SchoolYear::findOrFail($nextSchoolYearId);

            // Get all active/enrolled students for current school year
            $students = Student::whereHas('enrollments', function ($query) use ($currentSchoolYearId) {
                $query->where('school_year_id', $currentSchoolYearId)
                      ->where('status', 'enrolled');
            })->with(['enrollments' => function ($query) use ($currentSchoolYearId) {
                $query->where('school_year_id', $currentSchoolYearId);
            }, 'gradeLevel'])->get();

            foreach ($students as $student) {
                try {
                    $currentEnrollment = $student->enrollments->first();
                    
                    if (!$currentEnrollment) {
                        continue;
                    }

                    // Check if Grade 6 (or final grade level)
                    if ($this->isFinalGrade($student->grade_level_id)) {
                        // Graduate the student
                        $this->graduateStudent($student, $currentEnrollment);
                        $stats['graduated']++;
                    } else {
                        // Promote to next grade - create pending enrollment for next year
                        $this->promoteStudent($student, $currentEnrollment, $nextSchoolYear);
                        $stats['promoted']++;
                    }

                } catch (\Exception $e) {
                    $stats['errors'][] = "Student ID {$student->id}: " . $e->getMessage();
                    Log::error('Rollover failed for student', [
                        'student_id' => $student->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Handle existing pending students (move their pending status to next year)
            $this->rolloverPendingStudents($currentSchoolYearId, $nextSchoolYear, $stats);

            DB::commit();

            Log::info('School year rollover completed', $stats);

            return $stats;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('School year rollover failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Check if student is in final grade level
     */
    private function isFinalGrade(int $gradeLevelId): bool
    {
        // Adjust based on your grade levels table
        // Assuming Grade 6 is ID 6, or check if it's the highest grade
        $finalGradeId = 6; // Change to your actual Grade 6 ID
        
        return $gradeLevelId === $finalGradeId;
    }

    /**
     * Graduate a Grade 6 student
     */
    private function graduateStudent(Student $student, Enrollment $currentEnrollment): void
    {
        // Update current enrollment to completed
        $currentEnrollment->update([
            'status' => 'completed',
            'completion_date' => now(),
            'remarks' => 'Graduated'
        ]);

        // Update student status
        $student->update([
            'status' => 'graduated',
            'graduation_date' => now(),
            'section_id' => null
        ]);
    }

    /**
     * Promote student to next grade - create pending enrollment for next year
     */
    private function promoteStudent(Student $student, Enrollment $currentEnrollment, SchoolYear $nextSchoolYear): void
    {
        // Complete current enrollment
        $currentEnrollment->update([
            'status' => 'completed',
            'completion_date' => now(),
            'remarks' => 'Promoted to next grade'
        ]);

        // Determine next grade level
        $nextGradeLevelId = $this->getNextGradeLevel($student->grade_level_id);

        // Create new pending enrollment for next school year
        Enrollment::create([
            'student_id' => $student->id,
            'school_year_id' => $nextSchoolYear->id,
            'grade_level_id' => $nextGradeLevelId,
            'section_id' => null, // Will be assigned during enrollment approval
            'status' => 'pending',
            'type' => $currentEnrollment->type, // Retain type (new, transfer, etc.)
            'enrollment_date' => null, // Will be set when approved
            'remarks' => 'Auto-generated: Promoted from previous grade'
        ]);

        // Update student to inactive/pending status for next year
        $student->update([
            'status' => 'inactive',
            'grade_level_id' => $nextGradeLevelId,
            'section_id' => null
        ]);
    }

    /**
     * Get next grade level ID
     */
    private function getNextGradeLevel(int $currentGradeLevelId): int
    {
        // Simple increment - adjust based on your grade level structure
        return $currentGradeLevelId + 1;
    }

    /**
     * Rollover students who were already pending in current year
     */
    private function rolloverPendingStudents(int $currentSchoolYearId, SchoolYear $nextSchoolYear, array &$stats): void
    {
        $pendingStudents = Student::whereHas('enrollments', function ($query) use ($currentSchoolYearId) {
            $query->where('school_year_id', $currentSchoolYearId)
                  ->where('status', 'pending');
        })->with(['enrollments' => function ($query) use ($currentSchoolYearId) {
            $query->where('school_year_id', $currentSchoolYearId)
                  ->where('status', 'pending');
        }])->get();

        foreach ($pendingStudents as $student) {
            $currentEnrollment = $student->enrollments->first();

            if ($currentEnrollment) {
                // Archive old pending enrollment
                $currentEnrollment->update([
                    'status' => 'cancelled',
                    'remarks' => 'Cancelled: School year ended without enrollment'
                ]);

                // Create new pending enrollment for next school year
                Enrollment::create([
                    'student_id' => $student->id,
                    'school_year_id' => $nextSchoolYear->id,
                    'grade_level_id' => $student->grade_level_id,
                    'section_id' => null,
                    'status' => 'pending',
                    'type' => $currentEnrollment->type,
                    'remarks' => 'Rollover: Pending from previous year'
                ]);

                // Keep student as inactive/pending
                $student->update([
                    'status' => 'inactive',
                    'section_id' => null
                ]);

                $stats['pending']++;
            }
        }
    }
}