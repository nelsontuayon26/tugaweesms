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
        Schema::table('section_subject', function (Blueprint $table) {
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['subject_id'])->references(['id'])->on('subjects')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('section_subject', function (Blueprint $table) {
            $table->dropForeign('section_subject_section_id_foreign');
            $table->dropForeign('section_subject_subject_id_foreign');
        });
    }
};
