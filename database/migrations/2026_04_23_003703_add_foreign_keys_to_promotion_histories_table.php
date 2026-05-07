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
        Schema::table('promotion_histories', function (Blueprint $table) {
            $table->foreign(['from_grade_level_id'])->references(['id'])->on('grade_levels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['from_school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['to_grade_level_id'])->references(['id'])->on('grade_levels')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['to_school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotion_histories', function (Blueprint $table) {
            $table->dropForeign('promotion_histories_from_grade_level_id_foreign');
            $table->dropForeign('promotion_histories_from_school_year_id_foreign');
            $table->dropForeign('promotion_histories_student_id_foreign');
            $table->dropForeign('promotion_histories_to_grade_level_id_foreign');
            $table->dropForeign('promotion_histories_to_school_year_id_foreign');
        });
    }
};
