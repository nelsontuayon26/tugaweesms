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
        Schema::create('book_inventories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('subject_area')->nullable();
            $table->string('grade_level')->nullable();
            $table->string('book_code')->unique();
            $table->string('isbn')->nullable();
            $table->string('publisher')->nullable();
            $table->year('publication_year')->nullable();
            $table->integer('total_copies')->default(0);
            $table->integer('available_copies')->default(0);
            $table->integer('issued_copies')->default(0);
            $table->integer('damaged_copies')->default(0);
            $table->integer('lost_copies')->default(0);
            $table->decimal('replacement_cost', 10)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('grade_level_id')->nullable()->index('book_inventories_grade_level_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_inventories');
    }
};
