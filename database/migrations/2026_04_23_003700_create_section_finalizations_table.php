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
        Schema::create('section_finalizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('teacher_id');
            $table->boolean('grades_finalized')->default(false);
            $table->timestamp('grades_finalized_at')->nullable();
            $table->boolean('attendance_finalized')->default(false);
            $table->timestamp('attendance_finalized_at')->nullable();
            $table->boolean('core_values_finalized')->default(false);
            $table->timestamp('core_values_finalized_at')->nullable();
            $table->boolean('is_fully_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->unsignedBigInteger('finalized_by')->nullable()->index('section_finalizations_finalized_by_foreign');
            $table->boolean('is_locked')->default(false);
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('unlocked_at')->nullable();
            $table->unsignedBigInteger('unlocked_by')->nullable()->index('section_finalizations_unlocked_by_foreign');
            $table->timestamp('grades_unlocked_at')->nullable();
            $table->text('unlock_reason')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->boolean('auto_finalized')->default(false);
            $table->timestamps();
            $table->unsignedBigInteger('grades_unlocked_by')->nullable()->index('section_finalizations_grades_unlocked_by_foreign');
            $table->text('grades_unlock_reason')->nullable();
            $table->timestamp('attendance_unlocked_at')->nullable();
            $table->unsignedBigInteger('attendance_unlocked_by')->nullable()->index('section_finalizations_attendance_unlocked_by_foreign');
            $table->text('attendance_unlock_reason')->nullable();
            $table->timestamp('core_values_unlocked_at')->nullable();
            $table->unsignedBigInteger('core_values_unlocked_by')->nullable()->index('section_finalizations_core_values_unlocked_by_foreign');
            $table->text('core_values_unlock_reason')->nullable();

            $table->index(['school_year_id', 'is_fully_finalized']);
            $table->index(['teacher_id', 'is_fully_finalized']);
            $table->unique(['section_id', 'school_year_id'], 'unique_section_school_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_finalizations');
    }
};
