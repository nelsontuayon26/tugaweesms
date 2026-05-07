<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_school_year_id',
        'to_school_year_id',
        'from_grade_level_id',
        'to_grade_level_id',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromSchoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'from_school_year_id');
    }

    public function toSchoolYear()
    {
        return $this->belongsTo(SchoolYear::class, 'to_school_year_id');
    }

    public function fromGradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'from_grade_level_id');
    }

    public function toGradeLevel()
    {
        return $this->belongsTo(GradeLevel::class, 'to_grade_level_id');
    }
}