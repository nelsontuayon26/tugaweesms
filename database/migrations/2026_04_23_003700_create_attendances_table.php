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
        Schema::create('attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('section_id')->index('attendances_section_id_foreign');
            $table->unsignedBigInteger('student_id')->index('attendances_student_id_foreign');
            $table->unsignedBigInteger('school_year_id')->nullable()->index('attendances_school_year_id_foreign');
            $table->date('date');
            $table->string('status', 20)->nullable();
            $table->unsignedBigInteger('teacher_id')->nullable()->index('attendances_teacher_id_foreign');
            $table->text('remarks')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('accuracy')->nullable()->comment('Accuracy in meters');
            $table->boolean('location_verified')->default(false);
            $table->decimal('distance_from_school')->nullable()->comment('Distance in meters');
            $table->string('location_status', 20)->nullable()->comment('within_range, out_of_range, no_signal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
