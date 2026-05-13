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
            $table->id();

            // User relationship
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // LRN (Learner Reference Number)
            $table->string('lrn', 12)->nullable()->unique()->comment('Learner Reference Number');

            // Basic Information (also stored in users table, but kept here for redundancy/backup)
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            // Birth Information
            $table->date('birthdate')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('gender', 10)->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('mother_tongue')->nullable();
            $table->string('ethnicity')->nullable();

            // Parent/Guardian Information
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('guardian_relationship')->nullable();
            $table->string('guardian_contact')->nullable();

            // Emergency Contact
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_number')->nullable();

            // Current Address
            $table->string('street_address')->nullable();
            $table->string('barangay')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('zip_code', 10)->nullable();

            // Permanent Address
            $table->boolean('same_as_current_address')->default(false);
            $table->string('permanent_street_address')->nullable();
            $table->string('permanent_street_name')->nullable();
            $table->string('permanent_barangay')->nullable();
            $table->string('permanent_city')->nullable();
            $table->string('permanent_province')->nullable();
            $table->string('permanent_zip_code', 10)->nullable();

            // Academic Information
            $table->foreignId('grade_level_id')->nullable()->constrained('grade_levels')->onDelete('set null');
            $table->foreignId('section_id')->nullable()->constrained('sections')->onDelete('set null');
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->onDelete('set null');

            // Status
            $table->string('status')->default('pending')->comment('pending, active, inactive, graduated, unenrolled');
            $table->string('remarks', 10)->nullable()->comment('TI, TO, DO, LE, CCT, BA, LWD');

            // Photo
            $table->string('photo')->nullable();

            // Registration Documents
            $table->string('birth_certificate_path')->nullable();
            $table->string('report_card_path')->nullable();
            $table->string('good_moral_path')->nullable();
            $table->string('transfer_credential_path')->nullable();
            $table->string('registration_status')->default('pending')->comment('pending, approved, rejected');
            $table->timestamp('documents_verified_at')->nullable();
            $table->foreignId('documents_verified_by')->nullable()->constrained('users')->onDelete('set null');

            // DepEd Enrollment Fields
            $table->string('psa_birth_cert_no')->nullable();
            $table->boolean('has_lrn')->default(false);
            $table->boolean('is_ip')->default(false)->comment('Indigenous People');
            $table->string('ip_specification')->nullable();
            $table->boolean('is_4ps_beneficiary')->default(false)->comment('4Ps beneficiary');
            $table->string('household_id_4ps')->nullable();
            $table->boolean('is_returning_balik_aral')->default(false);

            // Returning/Transferee Fields (Section 6)
            $table->string('last_grade_level_completed')->nullable();
            $table->string('last_school_year_completed')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_id')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('lrn');
            $table->index('status');
            $table->index('registration_status');
            $table->index(['grade_level_id', 'section_id']);
            $table->index('school_year_id');
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