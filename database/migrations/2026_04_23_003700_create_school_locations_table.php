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
        Schema::create('school_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('type')->default('main_campus')->comment('main_campus, annex, etc.');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_meters')->default(100)->comment('Allowed radius for attendance');
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('require_location')->default(true)->comment('Require location verification for attendance');
            $table->text('allowed_schedules')->nullable()->comment('JSON array of allowed time slots');
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_locations');
    }
};
