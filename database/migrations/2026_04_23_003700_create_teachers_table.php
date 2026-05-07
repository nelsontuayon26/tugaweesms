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
        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('teacher_id')->nullable();
            $table->string('deped_id')->nullable();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->string('suffix', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('civil_status', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('blood_type', 5)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('mobile_number', 50)->nullable();
            $table->string('telephone_number', 50)->nullable();
            $table->string('street_address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city_municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->string('region', 50)->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_number', 50)->nullable();
            $table->text('emergency_contact_address')->nullable();
            $table->string('employment_status', 50)->nullable();
            $table->date('date_hired')->nullable();
            $table->date('date_regularized')->nullable();
            $table->string('current_status', 50)->nullable();
            $table->string('teaching_level', 50)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('designation', 100)->nullable();
            $table->boolean('is_class_adviser')->default(false);
            $table->string('advisory_class', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('highest_education')->nullable();
            $table->string('degree_program')->nullable();
            $table->string('major', 100)->nullable();
            $table->string('minor', 100)->nullable();
            $table->string('school_graduated')->nullable();
            $table->integer('year_graduated')->nullable();
            $table->string('honors_received', 150)->nullable();
            $table->string('prc_license_number', 50)->nullable();
            $table->date('prc_license_validity')->nullable();
            $table->boolean('let_passer')->default(false);
            $table->string('board_rating', 50)->nullable();
            $table->string('tesda_nc', 50)->nullable();
            $table->string('tesda_sector', 50)->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_school', 150)->nullable();
            $table->string('previous_position', 100)->nullable();
            $table->string('gsis_id', 50)->nullable();
            $table->string('pagibig_id', 50)->nullable();
            $table->string('philhealth_id', 50)->nullable();
            $table->string('sss_id', 50)->nullable();
            $table->string('tin_id', 50)->nullable();
            $table->string('pagibig_rtn', 50)->nullable();
            $table->integer('salary_grade')->nullable();
            $table->integer('step_increment')->nullable();
            $table->decimal('basic_salary', 10)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('spouse_name', 150)->nullable();
            $table->string('spouse_occupation', 100)->nullable();
            $table->string('spouse_contact', 50)->nullable();
            $table->integer('number_of_children')->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('father_occupation', 100)->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->string('mother_occupation', 100)->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('medications')->nullable();
            $table->boolean('covid_vaccinated')->default(false);
            $table->string('covid_vaccine_type', 100)->nullable();
            $table->date('covid_vaccine_date')->nullable();
            $table->text('photo_path')->nullable();
            $table->text('resume_path')->nullable();
            $table->text('prc_id_path')->nullable();
            $table->text('transcript_path')->nullable();
            $table->text('clearance_path')->nullable();
            $table->text('medical_cert_path')->nullable();
            $table->text('nbi_clearance_path')->nullable();
            $table->text('service_record_path')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('teachers_user_id_foreign');
            $table->timestamp('last_login_at')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['active', 'on_leave', 'inactive'])->nullable()->default('active');
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedBigInteger('school_year_id')->nullable()->index('teachers_school_year_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
