<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
protected $fillable = [
    'section_id',
    'student_id',
    'school_year_id',
    'subject_id',
    'quarter',
    'component_type',
    'scores',
    'total_score',
    'percentage_score',
    'ww_weighted',
    'pt_weighted',
    'qe_weighted',
    'initial_grade',
    'final_grade',
    'remarks',
    'titles',
    'total_items',
];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function calculateFinalGrade()
    {
        // Return the stored final grade for final_grade component records
        if ($this->component_type === 'final_grade') {
            return $this->final_grade ?? 0;
        }

        return $this->final_grade ?? 0;
    }

public function section()
{
    return $this->belongsTo(Section::class);
}

public function schoolYear()
{
    return $this->belongsTo(SchoolYear::class);
}

}
