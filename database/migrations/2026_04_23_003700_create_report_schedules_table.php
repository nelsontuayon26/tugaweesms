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
        Schema::create('report_schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('saved_report_id')->index('report_schedules_saved_report_id_foreign');
            $table->string('frequency');
            $table->json('schedule_config');
            $table->json('recipients');
            $table->string('format')->default('pdf');
            $table->string('delivery_method')->default('email');
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamp('next_send_at')->nullable()->index();
            $table->integer('send_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['frequency', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
    }
};
