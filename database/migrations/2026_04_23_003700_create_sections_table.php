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
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->unsignedBigInteger('grade_level_id')->nullable()->index('sections_grade_level_id_foreign');
            $table->unsignedBigInteger('school_year_id')->nullable()->index('school_year_id');
            $table->string('room_number', 50)->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable()->index('sections_teacher_id_foreign');
            $table->integer('capacity')->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
