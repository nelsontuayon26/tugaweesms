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
        Schema::create('enrollment_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('status', ['draft', 'pending', 'under_review', 'approved', 'rejected', 'waitlisted'])->default('pending');
            $table->enum('application_type', ['new_student', 'transfer', 'returning', 'continuing'])->nullable()->default('new_student');
            $table->string('application_number')->index();
            $table->unsignedBigInteger('school_year_id')->index('enrollment_applications_school_year_id_foreign');
            $table->unsignedBigInteger('grade_level_id')->index('enrollment_applications_grade_level_id_foreign');
            $table->string('student_first_name');
            $table->string('student_middle_name')->nullable();
            $table->string('student_last_name');
            $table->string('student_suffix')->nullable();
            $table->date('student_birthdate');
            $table->enum('student_gender', ['male', 'female']);
            $table->string('student_birth_place')->nullable();
            $table->string('student_religion')->nullable();
            $table->string('student_nationality')->default('Filipino');
            $table->string('student_mother_tongue')->nullable();
            $table->string('student_ethnicity')->nullable();
            $table->text('address');
            $table->string('barangay');
            $table->string('city');
            $table->string('province')->default('Negros Oriental');
            $table->string('zip_code')->nullable();
            $table->string('previous_school')->nullable();
            $table->string('previous_school_id')->nullable();
            $table->string('previous_school_address')->nullable();
            $table->string('last_grade_completed')->nullable();
            $table->string('general_average')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_contact')->nullable();
            $table->string('father_email')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_contact')->nullable();
            $table->string('mother_email')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_relationship');
            $table->string('guardian_contact');
            $table->string('guardian_email')->nullable();
            $table->text('guardian_address')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_relationship');
            $table->string('emergency_contact_number');
            $table->boolean('has_special_needs')->default(false);
            $table->text('special_needs_details')->nullable();
            $table->text('medical_conditions')->nullable();
            $table->text('allergies')->nullable();
            $table->string('parent_email');
            $table->string('parent_password');
            $table->unsignedBigInteger('student_id')->nullable()->index('enrollment_applications_student_id_foreign');
            $table->string('student_lrn', 12)->nullable();
            $table->boolean('account_created')->default(false);
            $table->unsignedBigInteger('reviewed_by')->nullable()->index('enrollment_applications_reviewed_by_foreign');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_applications');
    }
};
