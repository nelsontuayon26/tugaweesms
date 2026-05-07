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
        Schema::table('student_health_records', function (Blueprint $table) {
            $table->foreign(['assessed_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['student_id'])->references(['id'])->on('students')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_health_records', function (Blueprint $table) {
            $table->dropForeign('student_health_records_assessed_by_foreign');
            $table->dropForeign('student_health_records_school_year_id_foreign');
            $table->dropForeign('student_health_records_section_id_foreign');
            $table->dropForeign('student_health_records_student_id_foreign');
        });
    }
};
