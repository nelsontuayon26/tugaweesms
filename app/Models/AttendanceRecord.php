<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = ['attendance_id', 'student_id', 'status'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
