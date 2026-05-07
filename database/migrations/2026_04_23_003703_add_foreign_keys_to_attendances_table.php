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
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign('attendances_school_year_id_foreign');
            $table->dropForeign('attendances_section_id_foreign');
            $table->dropForeign('attendances_student_id_foreign');
            $table->dropForeign('attendances_teacher_id_foreign');
        });
    }
};
