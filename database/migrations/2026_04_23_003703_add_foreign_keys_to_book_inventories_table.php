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
        Schema::table('book_inventories', function (Blueprint $table) {
            $table->foreign(['grade_level_id'])->references(['id'])->on('grade_levels')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_inventories', function (Blueprint $table) {
            $table->dropForeign('book_inventories_grade_level_id_foreign');
        });
    }
};
