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
        Schema::create('grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('student_id')->index('grades_student_id_foreign');
            $table->unsignedBigInteger('school_year_id')->nullable()->index('grades_school_year_id_foreign');
            $table->unsignedBigInteger('subject_id')->index('grades_subject_id_foreign');
            $table->integer('quarter');
            $table->string('component_type');
            $table->json('scores')->nullable();
            $table->json('titles')->nullable();
            $table->json('total_items')->nullable();
            $table->integer('total_score')->nullable();
            $table->decimal('percentage_score', 5)->nullable();
            $table->decimal('ww_weighted', 5)->nullable();
            $table->decimal('pt_weighted', 5)->nullable();
            $table->decimal('qe_weighted', 5)->nullable();
            $table->decimal('initial_grade', 5)->nullable();
            $table->integer('final_grade')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();

            $table->unique(['section_id', 'student_id', 'subject_id', 'quarter', 'component_type'], 'unique_grade_record');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
