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
        Schema::create('school_year_closures', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_year_id')->unique('unique_school_year_closure');
            $table->enum('status', ['pending', 'ready_to_close', 'closing', 'closed'])->default('pending');
            $table->integer('total_sections')->default(0);
            $table->integer('finalized_sections')->default(0);
            $table->boolean('all_sections_finalized')->default(false);
            $table->timestamp('closure_started_at')->nullable();
            $table->timestamp('closure_completed_at')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable()->index('school_year_closures_closed_by_foreign');
            $table->timestamp('finalization_deadline')->nullable();
            $table->boolean('auto_close_enabled')->default(false);
            $table->timestamp('auto_close_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('closure_summary')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_year_closures');
    }
};
