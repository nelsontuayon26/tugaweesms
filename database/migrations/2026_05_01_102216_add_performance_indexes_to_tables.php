<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->safeAddIndex('enrollments', 'status', 'enrollments_status_index');
        $this->safeAddIndex('enrollments', 'school_year_id', 'enrollments_school_year_id_index');
        $this->safeAddIndex('enrollments', 'student_id', 'enrollments_student_id_index');
        $this->safeAddIndex('enrollments', 'section_id', 'enrollments_section_id_index');

        $this->safeAddIndex('students', 'status', 'students_status_index');
        $this->safeAddIndex('students', 'user_id', 'students_user_id_index');
        $this->safeAddIndex('students', 'school_year_id', 'students_school_year_id_index');

        $this->safeAddIndex('teachers', 'position', 'teachers_position_index');

        $this->safeAddIndex('school_years', 'is_active', 'school_years_is_active_index');

        $this->safeAddIndex('sections', 'school_year_id', 'sections_school_year_id_index');
        $this->safeAddIndex('sections', 'grade_level_id', 'sections_grade_level_id_index');
        $this->safeAddIndex('sections', 'teacher_id', 'sections_teacher_id_index');

        $this->safeAddIndex('users', 'role_id', 'users_role_id_index');

        $this->safeAddIndex('announcements', 'created_at', 'announcements_created_at_index');
        $this->safeAddIndex('announcements', 'author_id', 'announcements_author_id_index');

        $this->safeAddIndex('events', 'date', 'events_date_index');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->safeDropIndex('enrollments', 'enrollments_status_index');
        $this->safeDropIndex('enrollments', 'enrollments_school_year_id_index');
        $this->safeDropIndex('enrollments', 'enrollments_student_id_index');
        $this->safeDropIndex('enrollments', 'enrollments_section_id_index');

        $this->safeDropIndex('students', 'students_status_index');
        $this->safeDropIndex('students', 'students_user_id_index');
        $this->safeDropIndex('students', 'students_school_year_id_index');

        $this->safeDropIndex('teachers', 'teachers_position_index');

        $this->safeDropIndex('school_years', 'school_years_is_active_index');

        $this->safeDropIndex('sections', 'sections_school_year_id_index');
        $this->safeDropIndex('sections', 'sections_grade_level_id_index');
        $this->safeDropIndex('sections', 'sections_teacher_id_index');

        $this->safeDropIndex('users', 'users_role_id_index');

        $this->safeDropIndex('announcements', 'announcements_created_at_index');
        $this->safeDropIndex('announcements', 'announcements_author_id_index');

        $this->safeDropIndex('events', 'events_date_index');
    }

    private function safeAddIndex(string $table, string $column, string $indexName): void
    {
        try {
            $indexes = collect(DB::select("SHOW INDEX FROM {$table}"))->pluck('Key_name')->toArray();
            if (!in_array($indexName, $indexes)) {
                Schema::table($table, function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            }
        } catch (\Exception $e) {
            // Ignore errors — index may already exist
        }
    }

    private function safeDropIndex(string $table, string $indexName): void
    {
        try {
            $indexes = collect(DB::select("SHOW INDEX FROM {$table}"))->pluck('Key_name')->toArray();
            if (in_array($indexName, $indexes)) {
                Schema::table($table, function (Blueprint $table) use ($indexName) {
                    $table->dropIndex($indexName);
                });
            }
        } catch (\Exception $e) {
            // Ignore errors — index may not exist
        }
    }
};
