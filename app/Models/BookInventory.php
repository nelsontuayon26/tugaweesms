<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookInventory extends Model
{
    protected $fillable = [
        'title',
        'subject_area',
        'grade_level',
        'book_code',
        'isbn',
        'publisher',
        'publication_year',
        'total_copies',
        'available_copies',
        'issued_copies',
        'damaged_copies',
        'lost_copies',
        'replacement_cost',
        'remarks',
    ];

    /**
     * One inventory → many issued books
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}