<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeWeight extends Model
{
    protected $fillable = [
        'section_id',
        'subject_id',
        'school_year_id',
        'quarter',
        'ww_weight',
        'pt_weight',
        'qe_weight',
    ];

    protected $casts = [
        'ww_weight' => 'decimal:2',
        'pt_weight' => 'decimal:2',
        'qe_weight' => 'decimal:2',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
