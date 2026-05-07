<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EnrollmentApplication extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status',
        'application_type',
        'application_number',
        'school_year_id',
        'grade_level_id',
        'student_first_name',
        'student_middle_name',
        'student_last_name',
        'student_suffix',
        'student_birthdate',
        'student_gender',
        'student_birth_place',
        'student_religion',
        'student_nationality',
        'student_mother_tongue',
        'student_ethnicity',
        'address',
        'barangay',
        'city',
        'province',
        'zip_code',
        'previous_school',
        'previous_school_id',
        'previous_school_address',
        'last_grade_completed',
        'general_average',
        'father_name',
        'father_occupation',
        'father_contact',
        'father_email',
        'mother_name',
        'mother_occupation',
        'mother_contact',
        'mother_email',
        'guardian_name',
        'guardian_relationship',
        'guardian_contact',
        'guardian_email',
        'guardian_address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'has_special_needs',
        'special_needs_details',
        'medical_conditions',
        'allergies',
        'parent_email',
        'parent_password',
        'student_lrn',
        'student_id',
        'account_created',
        'reviewed_by',
        'reviewed_at',
        'admin_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'student_birthdate' => 'date',
        'has_special_needs' => 'boolean',
        'account_created' => 'boolean',
        'reviewed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->application_number)) {
                $application->application_number = self::generateApplicationNumber();
            }
        });
    }

    /**
     * Generate unique application number
     */
    public static function generateApplicationNumber(): string
    {
        $prefix = 'ENR-' . now()->year . '-';
        $last = self::withTrashed()
            ->where('application_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->application_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeNewStudents($query)
    {
        return $query->where('application_type', 'new_student');
    }

    public function scopeTransfers($query)
    {
        return $query->where('application_type', 'transfer');
    }

    public function scopeContinuingStudents($query)
    {
        return $query->where('application_type', 'continuing');
    }

    // Relationships
    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EnrollmentDocument::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    // Accessors
    public function getStudentFullNameAttribute(): string
    {
        $name = $this->student_first_name;
        if ($this->student_middle_name) {
            $name .= ' ' . $this->student_middle_name;
        }
        $name .= ' ' . $this->student_last_name;
        if ($this->student_suffix) {
            $name .= ' ' . $this->student_suffix;
        }
        return $name;
    }

    public function getStudentAgeAttribute(): int
    {
        return $this->student_birthdate->age;
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'gray',
            'pending' => 'yellow',
            'under_review' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'waitlisted' => 'orange',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending',
            'under_review' => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'waitlisted' => 'Waitlisted',
            default => 'Unknown',
        };
    }

    public function getApplicationTypeLabelAttribute(): string
    {
        return match($this->application_type) {
            'new_student' => 'New Student',
            'transfer' => 'Transfer Student',
            'continuing' => 'Continuing Student',
            default => 'Unknown',
        };
    }

    public function scopeContinuing($query)
    {
        return $query->where('application_type', 'continuing');
    }

    // Helper methods
    public function markAsUnderReview(int $reviewerId): void
    {
        $this->update([
            'status' => 'under_review',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);
    }

    public function approve(int $reviewerId, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    public function reject(int $reviewerId, string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
            'admin_notes' => $notes,
        ]);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function hasCompleteDocuments(): bool
    {
        $requiredDocs = ['birth_certificate', 'report_card', 'good_moral'];
        $uploadedDocs = $this->documents->pluck('document_type')->toArray();
        
        foreach ($requiredDocs as $required) {
            if (!in_array($required, $uploadedDocs)) {
                return false;
            }
        }
        return true;
    }
}
