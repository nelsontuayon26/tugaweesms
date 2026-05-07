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
        Schema::table('announcement_reads', function (Blueprint $table) {
            $table->foreign(['announcement_id'])->references(['id'])->on('announcements')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcement_reads', function (Blueprint $table) {
            $table->dropForeign('announcement_reads_announcement_id_foreign');
            $table->dropForeign('announcement_reads_user_id_foreign');
        });
    }
};
