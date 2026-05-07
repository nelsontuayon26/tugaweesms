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
        Schema::table('school_year_closures', function (Blueprint $table) {
            $table->foreign(['closed_by'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['school_year_id'])->references(['id'])->on('school_years')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('school_year_closures', function (Blueprint $table) {
            $table->dropForeign('school_year_closures_closed_by_foreign');
            $table->dropForeign('school_year_closures_school_year_id_foreign');
        });
    }
};
