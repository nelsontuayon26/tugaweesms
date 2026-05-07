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
        Schema::table('messages', function (Blueprint $table) {
            $table->boolean('is_group_chat')->default(false)->after('is_bulk');
            // Make recipient_id nullable for group messages
            $table->unsignedBigInteger('recipient_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('is_group_chat');
            $table->unsignedBigInteger('recipient_id')->nullable(false)->change();
        });
    }
};
