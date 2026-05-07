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
        Schema::create('promotion_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('from_school_year_id')->index('promotion_histories_from_school_year_id_foreign');
            $table->unsignedBigInteger('to_school_year_id')->index('promotion_histories_to_school_year_id_foreign');
            $table->unsignedBigInteger('from_grade_level_id')->index('promotion_histories_from_grade_level_id_foreign');
            $table->unsignedBigInteger('to_grade_level_id')->index('promotion_histories_to_grade_level_id_foreign');
            $table->timestamps();

            $table->unique(['student_id', 'from_school_year_id', 'to_school_year_id'], 'promotion_unique_per_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_histories');
    }
};
