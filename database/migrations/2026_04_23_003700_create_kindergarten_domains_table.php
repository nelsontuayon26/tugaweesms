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
        Schema::create('kindergarten_domains', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->string('domain');
            $table->string('indicator_key');
            $table->text('indicator');
            $table->string('rating', 10);
            $table->text('remarks')->nullable();
            $table->unsignedTinyInteger('quarter');
            $table->unsignedBigInteger('school_year_id')->index('kindergarten_domains_school_year_id_foreign');
            $table->unsignedBigInteger('recorded_by');
            $table->timestamps();

            $table->unique(['student_id', 'domain', 'indicator_key', 'quarter', 'school_year_id'], 'kindergarten_domains_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kindergarten_domains');
    }
};
