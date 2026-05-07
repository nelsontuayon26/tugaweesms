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
        Schema::create('messages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sender_id')->index('messages_sender_id_foreign');
            $table->unsignedBigInteger('recipient_id')->index('messages_recipient_id_foreign');
            $table->string('subject')->nullable();
            $table->text('body');
            $table->boolean('read')->default(false);
            $table->timestamps();
            $table->boolean('is_read')->default(false);
            $table->boolean('is_edited')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('is_bulk')->default(false);
            $table->unsignedBigInteger('parent_id')->nullable()->index('messages_parent_id_foreign');
            $table->unsignedBigInteger('section_id')->nullable()->index('messages_section_id_foreign');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
