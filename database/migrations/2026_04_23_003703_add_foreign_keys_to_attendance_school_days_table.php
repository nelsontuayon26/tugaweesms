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
        Schema::table('attendance_school_days', function (Blueprint $table) {
            $table->foreign(['configured_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_school_days', function (Blueprint $table) {
            $table->dropForeign('attendance_school_days_configured_by_foreign');
            $table->dropForeign('attendance_school_days_school_year_id_foreign');
            $table->dropForeign('attendance_school_days_section_id_foreign');
        });
    }
};
