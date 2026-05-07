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
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->date('student_birthdate')->nullable()->change();
            $table->enum('student_gender', ['male', 'female'])->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('barangay')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('guardian_name')->nullable()->change();
            $table->string('guardian_relationship')->nullable()->change();
            $table->string('guardian_contact')->nullable()->change();
            $table->string('emergency_contact_name')->nullable()->change();
            $table->string('emergency_contact_relationship')->nullable()->change();
            $table->string('emergency_contact_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollment_applications', function (Blueprint $table) {
            $table->date('student_birthdate')->nullable(false)->change();
            $table->enum('student_gender', ['male', 'female'])->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('barangay')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('guardian_name')->nullable(false)->change();
            $table->string('guardian_relationship')->nullable(false)->change();
            $table->string('guardian_contact')->nullable(false)->change();
            $table->string('emergency_contact_name')->nullable(false)->change();
            $table->string('emergency_contact_relationship')->nullable(false)->change();
            $table->string('emergency_contact_number')->nullable(false)->change();
        });
    }
};
