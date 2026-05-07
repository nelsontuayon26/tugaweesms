<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KindergartenDomain extends Model
{
    use HasFactory;

    protected $table = 'kindergarten_domains';

    protected $fillable = [
        'student_id',
        'domain',
        'indicator_key',
        'indicator',
        'rating',
        'remarks',
        'quarter',
        'school_year_id',
        'recorded_by',
    ];

    /**
     * Get rating description in Cebuano
     */
    public function getRatingDescriptionAttribute(): string
    {
        return match($this->rating) {
            'B' => 'Beginning (Sinugdan)',
            'D' => 'Developing (Nagpalambo)',
            'C' => 'Consistent (Dili magbalbal)',
            default => $this->rating,
        };
    }

    /**
     * Get rating full text
     */
    public function getRatingFullAttribute(): string
    {
        return match($this->rating) {
            'B' => 'B - Beginning',
            'D' => 'D - Developing',
            'C' => 'C - Consistent',
            default => $this->rating,
        };
    }

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
