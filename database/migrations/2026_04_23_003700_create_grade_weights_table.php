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
        Schema::create('grade_weights', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('subject_id')->index('grade_weights_subject_id_foreign');
            $table->unsignedBigInteger('school_year_id')->index('grade_weights_school_year_id_foreign');
            $table->integer('quarter');
            $table->decimal('ww_weight', 5)->default(40);
            $table->decimal('pt_weight', 5)->default(40);
            $table->decimal('qe_weight', 5)->default(20);
            $table->timestamps();

            $table->unique(['section_id', 'subject_id', 'school_year_id', 'quarter'], 'unique_grade_weights');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_weights');
    }
};
