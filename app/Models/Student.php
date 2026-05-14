<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lrn',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'birth_place',
        'gender',
        'nationality',
        'religion',
        'father_name',
        'father_occupation',
        'mother_name',
        'mother_occupation',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'street_address',
        'street_name',
        'barangay',
        'city',
        'province',
        'zip_code',
        'status',
        'grade_level_id',
        'section_id',
        'photo',
        'school_year_id', 
        'mother_tongue',
        'ethnicity', 
        'remarks',
        'father_contact',
        'mother_contact',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        // Registration Documents
        'birth_certificate_path',
        'report_card_path',
        'good_moral_path',
        'transfer_credential_path',
        'registration_status',
        'documents_verified_at',
        'documents_verified_by',

        // DepEd enrollment fields
        'psa_birth_cert_no',
        'has_lrn',
        'is_ip',
        'ip_specification',
        'is_4ps_beneficiary',
        'household_id_4ps',
        'is_returning_balik_aral',

        // Permanent address
        'same_as_current_address',
        'permanent_street_address',
        'permanent_street_name',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_zip_code',

        // Returning/Transferee fields
        'last_grade_level_completed',
        'last_school_year_completed',
        'previous_school',
        'previous_school_id',
    ];
    
    protected $casts = [
        'birthdate' => 'date',
        'status' => 'string',
        'documents_verified_at' => 'datetime',
        'has_lrn' => 'boolean',
        'is_ip' => 'boolean',
        'is_4ps_beneficiary' => 'boolean',
        'is_returning_balik_aral' => 'boolean',
        'same_as_current_address' => 'boolean',
    ];

        // ✅ Add your remarks legend here
    public static $remarksLegend = [
        'TI' => 'Transferred In',
        'TO' => 'Transferred Out',
        'DO' => 'Dropped Out',
        'LE' => 'Late Enrollee',
        'CCT' => 'CCT Recipient',
        'BA' => 'Balik Aral',
        'LWD' => 'Learner With Disability',
    ];

public function healthRecords()
{
    return $this->hasMany(StudentHealthRecord::class);
}
    
public function books()
{
    return $this->hasMany(Book::class);
}
    
public function attendances()
{
    return $this->hasMany(Attendance::class);
}
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

 // Get the latest enrollment's section
public function section()
{
    return $this->hasOneThrough(
        Section::class,
        Enrollment::class,
        'student_id', // Foreign key on enrollments table
        'id',         // Foreign key on sections table
        'id',         // Local key on students table
        'section_id'  // Local key on enrollments table
    )->latestOfMany(); // pick the latest enrollment if multiple
}

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

        public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

   public function achievements()
{
    return $this->hasMany(Achievement::class);
}
    // Full name accessor - gets from user relationship
    public function getFullNameAttribute(): ?string
    {
        if ($this->user) {
            return trim($this->user->first_name . ' ' . ($this->user->middle_name ? $this->user->middle_name . ' ' : '') . $this->user->last_name);
        }
        return ($this->first_name && $this->last_name)
            ? trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name)
            : 'Unknown';
    }



    // Avatar accessor
    public function getAvatarAttribute(): ?string
    {
        return $this->photo ? profile_photo_url($this->photo) : null;
    }


public function getAgeAttribute()
{
    return $this->birthdate 
        ? Carbon::parse($this->birthdate)->age 
        : null;
}

    public function coreValues()
    {
        return $this->hasMany(CoreValue::class);
    }

    public function kindergartenDomains()
    {
        return $this->hasMany(KindergartenDomain::class);
    }

  
    
}