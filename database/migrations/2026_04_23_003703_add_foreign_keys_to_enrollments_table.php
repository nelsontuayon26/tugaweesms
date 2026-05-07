<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->foreign(['grade_level_id'])->references(['id'])->on('grade_levels')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['school_year_id'], 'fk_enrollment_school_year')->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'], 'fk_enrollment_student')->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign('enrollments_grade_level_id_foreign');
            $table->dropForeign('enrollments_school_year_id_foreign');
            $table->dropForeign('enrollments_section_id_foreign');
            $table->dropForeign('enrollments_student_id_foreign');
            $table->dropForeign('fk_enrollment_school_year');
            $table->dropForeign('fk_enrollment_student');
        });
    }
};
