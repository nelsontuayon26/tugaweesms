<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'grade_level_id', // link to grade level
    ];

    /**
     * Subject belongs to a grade level.
     */
    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    /**
     * Optional: if you have sections that directly have subjects
     * (e.g., many-to-many relationship through a pivot table)
     */
    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_subject', 'subject_id', 'section_id');
    }

    /**
     * Grades associated with this subject
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Helper: get a simple display code, e.g., "MAT1" for grade 1 Math
     */
    public function getDisplayCodeAttribute()
    {
        return strtoupper(substr(preg_replace('/\s+/', '', $this->name), 0, 4)) . ($this->grade_level_id ?? '');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject')
                    ->withPivot('section_id', 'grade_level', 'school_year', 'schedule', 'time_start', 'time_end', 'room')
                    ->withTimestamps();
    }
}