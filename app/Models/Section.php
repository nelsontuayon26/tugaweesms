<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'grade_level_id',
      'school_year_id', 
            'room_number',
        'teacher_id',
        'capacity',
        'is_active',
    ];

    /**
     * SECTION → GRADE LEVEL
     */

    
    // Relationship to GradeLevel
    public function gradeLevel()
    {
        return $this->belongsTo(\App\Models\GradeLevel::class, 'grade_level_id');
    }

    // Relationship to Teacher (adviser)
    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }

    // Relationship to SchoolYear
    public function schoolYear()
    {
        return $this->belongsTo(\App\Models\SchoolYear::class, 'school_year_id');
    }
 


    /**
     * SECTION → TEACHER (Adviser)
     */

       // Relationship to Teachers
    public function teachers()
    {
        return $this->belongsToMany(\App\Models\Teacher::class, 'teacher_sections', 'section_id', 'teacher_id')
                    ->withTimestamps();
    }

    /**
     * SECTION → STUDENTS
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }

        // Section has many Subjects (many-to-many)
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'section_subject', 'section_id', 'subject_id')
                    ->withTimestamps();
    }

public function attendances()
{
    return $this->hasMany(Attendance::class);
}

    /**
     * Check if section is full
     */
    public function isFull()
    {
        if (!$this->capacity) return false;

        return $this->students()->count() >= $this->capacity;
    }

    /**
     * Remaining slots
     */
    public function remainingSlots()
    {
        if (!$this->capacity) return null;

        return $this->capacity - $this->students()->count();
    
    }

    // Scope for active school year sections
    public function scopeActiveYear($query)
    {
        return $query->whereHas('schoolYear', function ($q) {
            $q->where('is_active', true);
        });
    }


    public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}


public function grades()
{
    return $this->hasMany(\App\Models\Grade::class);
}


}