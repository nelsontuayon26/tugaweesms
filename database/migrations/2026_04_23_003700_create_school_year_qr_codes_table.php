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
        Schema::create('school_year_qr_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_year_id')->index('school_year_qr_codes_school_year_id_foreign');
            $table->string('qr_code_token')->unique();
            $table->string('qr_code_image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dateTime('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_year_qr_codes');
    }
};
