<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreValue extends Model
{
    use HasFactory;

    // ✅ Table name (optional if follows Laravel convention)
    protected $table = 'core_values';

    // ✅ Mass-assignable fields
    protected $fillable = [
        'student_id',
        'core_value',
        'statement_key',
        'behavior_statement',
        'rating',
        'remarks',
        'quarter',
        'school_year_id',
        'recorded_by',
    ];

    // ✅ Relationships

    // Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // School Year
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    // User who recorded
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}