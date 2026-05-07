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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('students_user_id_foreign');
            $table->string('lrn', 50)->nullable()->unique();
            $table->date('birthdate')->nullable();
            $table->string('birth_place', 150)->nullable();
            $table->string('gender', 20)->nullable();
            $table->enum('status', ['active', 'inactive', 'graduated'])->default('inactive');
            $table->string('birth_certificate_path')->nullable();
            $table->string('report_card_path')->nullable();
            $table->string('good_moral_path')->nullable();
            $table->string('transfer_credential_path')->nullable();
            $table->enum('registration_status', ['pending', 'complete', 'incomplete'])->default('pending');
            $table->timestamp('documents_verified_at')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('nationality', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('father_name', 150)->nullable();
            $table->string('father_occupation', 100)->nullable();
            $table->string('mother_name', 150)->nullable();
            $table->string('mother_occupation', 100)->nullable();
            $table->string('guardian_name', 150)->nullable();
            $table->string('guardian_relationship', 50)->nullable();
            $table->string('guardian_contact', 50)->nullable();
            $table->string('street_address')->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->unsignedBigInteger('grade_level_id')->nullable()->index('students_grade_level_id_foreign');
            $table->unsignedBigInteger('section_id')->nullable()->index('students_section_id_foreign');
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('school_year_id')->nullable()->index('students_school_year_id_foreign');
            $table->string('remarks', 20)->nullable();
            $table->unsignedBigInteger('documents_verified_by')->nullable()->index('students_documents_verified_by_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
