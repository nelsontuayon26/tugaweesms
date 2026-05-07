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
        Schema::table('section_finalizations', function (Blueprint $table) {
            $table->foreign(['attendance_unlocked_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['core_values_unlocked_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['finalized_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['grades_unlocked_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('set null');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['teacher_id'])->references(['id'])->on('teachers')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['unlocked_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('section_finalizations', function (Blueprint $table) {
            $table->dropForeign('section_finalizations_attendance_unlocked_by_foreign');
            $table->dropForeign('section_finalizations_core_values_unlocked_by_foreign');
            $table->dropForeign('section_finalizations_finalized_by_foreign');
            $table->dropForeign('section_finalizations_grades_unlocked_by_foreign');
            $table->dropForeign('section_finalizations_school_year_id_foreign');
            $table->dropForeign('section_finalizations_section_id_foreign');
            $table->dropForeign('section_finalizations_teacher_id_foreign');
            $table->dropForeign('section_finalizations_unlocked_by_foreign');
        });
    }
};
