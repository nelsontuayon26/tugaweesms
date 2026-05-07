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
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->foreign(['grade_level_id'])->references(['id'])->on('grade_levels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['reviewed_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->dropForeign('enrollment_applications_grade_level_id_foreign');
            $table->dropForeign('enrollment_applications_reviewed_by_foreign');
            $table->dropForeign('enrollment_applications_school_year_id_foreign');
            $table->dropForeign('enrollment_applications_student_id_foreign');
        });
    }
};
