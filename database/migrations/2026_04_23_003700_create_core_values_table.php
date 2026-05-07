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
        Schema::create('core_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->string('core_value');
            $table->string('statement_key');
            $table->text('behavior_statement');
            $table->string('rating');
            $table->text('remarks')->nullable();
            $table->unsignedTinyInteger('quarter');
            $table->unsignedBigInteger('school_year_id')->index('core_values_school_year_id_foreign');
            $table->unsignedBigInteger('recorded_by');
            $table->timestamps();

            $table->unique(['student_id', 'core_value', 'statement_key', 'quarter', 'school_year_id'], 'core_values_unique_full');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('core_values');
    }
};
