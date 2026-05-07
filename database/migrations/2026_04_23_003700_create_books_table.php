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
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id')->index();
            $table->string('title')->comment('Title of the textbook or learning material');
            $table->string('subject_area')->nullable()->index()->comment('Subject area (e.g., Math, Science, English)');
            $table->string('book_code')->nullable()->comment('Internal book tracking code');
            $table->string('reference_code')->nullable()->comment('ISBN or official reference number');
            $table->date('date_issued')->nullable()->comment('Date when book was issued to student');
            $table->date('date_returned')->nullable()->comment('Date when book was returned');
            $table->enum('status', ['issued', 'returned', 'lost'])->default('issued')->index()->comment('Current status of the book');
            $table->enum('condition', ['new', 'good', 'used', 'damaged']);
            $table->text('damage_details')->nullable()->comment('Description of damage if applicable');
            $table->enum('loss_code', ['FM', 'TDO', 'NEG'])->nullable()->comment('FM=Force Majeure, TDO=Transferred/Dropout, NEG=Negligence');
            $table->enum('action_taken', ['LLTR', 'TLTR', 'PTL'])->nullable()->comment('LLTR=Letter from Learner, TLTR=Teacher Letter, PTL=Paid');
            $table->text('remarks')->nullable()->comment('Additional notes or comments');
            $table->timestamps();
            $table->unsignedBigInteger('school_year_id')->nullable()->index('books_school_year_id_foreign');
            $table->unsignedBigInteger('book_inventory_id')->nullable()->index('books_book_inventory_id_foreign');

            $table->index(['student_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
