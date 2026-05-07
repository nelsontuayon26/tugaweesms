<?php
// app/Models/Book.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'student_id',
        'book_inventory_id',
        'title',
        'subject_area',
        'book_code',
        'reference_code',
        'date_issued',
        'date_returned',
        'status',
        'condition',
        'damage_details',
        'loss_code',
        'action_taken',
        'remarks',
        'book_inventory_id',
        'school_year_id',
        'copy_number',
    ];

    protected $casts = [
        'date_issued' => 'date',
        'date_returned' => 'date',
    ];

    /**
     * Book belongs to student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Book belongs to inventory
     */
    public function inventory()
    {
        return $this->belongsTo(BookInventory::class, 'book_inventory_id');
    }
}