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
        Schema::create('teaching_programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('section_id')->index('teaching_programs_section_id_foreign');
            $table->unsignedBigInteger('school_year_id')->index('teaching_programs_school_year_id_foreign');
            $table->enum('day', ['M', 'T', 'W', 'TH', 'F']);
            $table->time('time_from');
            $table->time('time_to');
            $table->string('subject')->nullable();
            $table->text('activity')->nullable();
            $table->integer('minutes')->default(0);
            $table->timestamps();

            $table->unique(['teacher_id', 'section_id', 'school_year_id', 'day', 'time_from'], 'tp_unique_schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teaching_programs');
    }
};
