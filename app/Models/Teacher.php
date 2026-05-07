<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_id', 'deped_id', 'first_name', 'middle_name', 'last_name', 'suffix',
        'date_of_birth', 'place_of_birth', 'gender', 'civil_status', 'nationality',
        'religion', 'blood_type', 'email', 'mobile_number', 'telephone_number',
        'street_address', 'barangay', 'city_municipality', 'province', 'zip_code', 'region',
        'emergency_contact_name', 'emergency_contact_relationship', 'emergency_contact_number',
        'emergency_contact_address', 'employment_status', 'date_hired', 'date_regularized',
        'current_status', 'teaching_level', 'position', 'designation', 'is_class_adviser',
        'advisory_class', 'department', 'highest_education', 'degree_program', 'major', 'minor',
        'school_graduated', 'year_graduated', 'honors_received', 'prc_license_number',
        'prc_license_validity', 'let_passer', 'board_rating', 'tesda_nc', 'tesda_sector',
        'years_of_experience', 'previous_school', 'previous_position',
        'gsis_id', 'pagibig_id', 'philhealth_id', 'sss_id', 'tin_id', 'pagibig_rtn',
        'salary_grade', 'step_increment', 'basic_salary', 'bank_account_number', 'bank_name',
        'spouse_name', 'spouse_occupation', 'spouse_contact', 'number_of_children',
        'father_name', 'father_occupation', 'mother_name', 'mother_occupation',
        'medical_conditions', 'medications', 'covid_vaccinated', 'covid_vaccine_type',
        'covid_vaccine_date', 'photo_path', 'resume_path', 'prc_id_path', 'transcript_path',
        'clearance_path', 'medical_cert_path', 'nbi_clearance_path', 'service_record_path',
        'user_id', 'last_login_at', 'ip_address', 'remarks', 'status',  'school_year_id', 
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_hired' => 'date',
        'date_regularized' => 'date',
        'prc_license_validity' => 'date',
        'covid_vaccine_date' => 'date',
        'year_graduated' => 'integer',
        'years_of_experience' => 'integer',
        'number_of_children' => 'integer',
        'salary_grade' => 'integer',
        'basic_salary' => 'decimal:2',
        'is_class_adviser' => 'boolean',
        'covid_vaccinated' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Teacher.php
public function section()
{
    return $this->hasOne(Section::class);
}

    public function getAvatarAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }
    
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->last_name} {$this->suffix}");
    }

    // ✅ CORRECT: One teacher can have many sections
    public function sections()
    {
        return $this->hasMany(\App\Models\Section::class, 'teacher_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject')
                    ->withPivot('grade_level', 'section_id', 'school_year', 'schedule', 'time_start', 'time_end', 'room')
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(\App\Models\Assignment::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class, 'teacher_id');
    }

    public function interventions()
    {
        return $this->hasMany(\App\Models\Intervention::class, 'teacher_id');
    }
}