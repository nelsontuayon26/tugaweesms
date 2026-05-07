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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_year_id')->nullable()->index('fk_enrollment_school_year');
            $table->unsignedBigInteger('grade_level_id')->nullable()->index('enrollments_grade_level_id_foreign');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('section_id')->nullable()->index('enrollments_section_id_foreign');
            $table->enum('type', ['new', 'continuing', 'transferee']);
            $table->enum('status', ['pending', 'approved', 'enrolled', 'completed', 'dropped', 'rejected'])->default('pending');
            $table->string('previous_school')->nullable();
            $table->string('school_name')->nullable();
            $table->string('school_id')->nullable();
            $table->string('school_district')->nullable();
            $table->string('school_division')->nullable();
            $table->string('school_region')->nullable();
            $table->date('enrollment_date')->nullable();
            $table->timestamps();
            $table->string('remarks', 50)->nullable();

            $table->unique(['student_id', 'school_year_id'], 'unique_student_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
