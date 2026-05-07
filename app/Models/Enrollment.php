<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
protected $fillable = [
    'school_year_id',
    'grade_level_id',
    'student_id',
    'section_id',
    'type',
    'status',
    'previous_school',
    'enrollment_date',
    'remarks',

     // Store current school info at time of enrollment
    'school_name',
    'school_id',
    'school_district',
    'school_division',
    'school_region',
];

protected $casts = [
    'enrollment_date' => 'datetime', // or 'date'
];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function grades()
{
    return $this->hasMany(Grade::class); // or whatever table/model you want
}

    /*
    |--------------------------------------------------------------------------
    | SCOPES (Helpful Queries)
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('status', 'enrolled');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isNew()
    {
        return $this->type === 'new';
    }

    public function isContinuing()
    {
        return $this->type === 'continuing';
    }

    public function isTransferee()
    {
        return $this->type === 'transferee';
    }

    public function isEnrolled()
    {
        return $this->status === 'enrolled';
    }
}