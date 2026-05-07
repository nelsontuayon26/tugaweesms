<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // The table name (optional if it follows Laravel convention 'events')
    protected $table = 'events';

    // Mass assignable fields
    protected $fillable = [
        'title',
        'description',
        'date',
        'school_year_id',
        'created_by',
    ];

    // Cast 'date' to a Carbon instance
    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}