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
        Schema::create('announcements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('author_id')->nullable()->index('announcements_author_id_foreign');
            $table->unsignedBigInteger('grade_level_id')->nullable()->index('announcements_grade_level_id_foreign');
            $table->string('title');
            $table->text('message');
            $table->string('priority')->default('normal');
            $table->boolean('pinned')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->unsignedBigInteger('school_year_id')->nullable()->index('announcements_school_year_id_foreign');
            $table->timestamps();
            $table->string('target')->default('students');
            $table->string('scope')->default('school');
            $table->unsignedBigInteger('target_id')->nullable();
            $table->boolean('is_read')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
