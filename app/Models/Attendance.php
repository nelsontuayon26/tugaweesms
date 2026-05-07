<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Attendance extends Model
{
     protected $fillable = [
        'section_id', 
        'student_id', 
        'date', 
        'teacher_id', 
        'school_year_id', 
        'status', 
        'remarks',
        'latitude',
        'longitude',
        'accuracy',
        'location_verified',
        'distance_from_school',
        'location_status'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'accuracy' => 'decimal:2',
        'location_verified' => 'boolean',
        'distance_from_school' => 'decimal:2',
    ];


    public function section()
    {
        return $this->belongsTo(Section::class);
    }

       public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function records()
    {
        return $this->hasMany(AttendanceRecord::class);
    }
}
