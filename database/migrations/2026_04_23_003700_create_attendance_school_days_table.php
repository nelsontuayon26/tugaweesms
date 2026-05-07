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
        Schema::create('attendance_school_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedTinyInteger('month');
            $table->year('year');
            $table->integer('total_school_days')->default(0);
            $table->json('school_dates')->nullable();
            $table->json('non_school_days')->nullable();
            $table->text('teacher_notes')->nullable();
            $table->unsignedBigInteger('configured_by')->nullable()->index('attendance_school_days_configured_by_foreign');
            $table->timestamp('configured_at')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();

            $table->index(['school_year_id', 'month', 'year']);
            $table->index(['section_id', 'is_finalized']);
            $table->unique(['section_id', 'school_year_id', 'month', 'year'], 'unique_section_month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_school_days');
    }
};
