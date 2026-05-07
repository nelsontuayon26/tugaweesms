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
        Schema::create('student_health_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index('student_health_records_student_id_foreign');
            $table->unsignedBigInteger('section_id')->index('student_health_records_section_id_foreign');
            $table->unsignedBigInteger('school_year_id')->index('student_health_records_school_year_id_foreign');
            $table->enum('period', ['bosy', 'eosy'])->comment('Beginning/End of School Year');
            $table->decimal('weight', 5)->nullable()->comment('in kg');
            $table->decimal('height', 4)->nullable()->comment('in meters');
            $table->decimal('bmi', 5)->nullable()->comment('kg/m²');
            $table->string('nutritional_status')->nullable()->comment('Severely Wasted, Wasted, Normal, Overweight, Obese');
            $table->string('height_for_age')->nullable()->comment('Severely Stunted, Stunted, Normal, Tall');
            $table->text('remarks')->nullable();
            $table->date('date_of_assessment')->nullable();
            $table->unsignedBigInteger('assessed_by')->nullable()->index('student_health_records_assessed_by_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_health_records');
    }
};
