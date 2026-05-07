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
        Schema::create('saved_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedBigInteger('template_id');
            $table->unsignedBigInteger('user_id');
            $table->json('parameters');
            $table->json('column_visibility')->nullable();
            $table->string('format')->default('html');
            $table->string('schedule_frequency')->nullable();
            $table->json('schedule_config')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_run_at')->nullable();
            $table->boolean('is_favorite')->default(false);
            $table->boolean('is_scheduled')->default(false)->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['template_id', 'user_id']);
            $table->index(['user_id', 'is_favorite']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saved_reports');
    }
};
