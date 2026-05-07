<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'message',
        'author_id',
        'target',
        'scope',
        'target_id',
        'grade_level_id',
        'priority',
        'pinned',
        'expires_at',
        'school_year_id',
        'is_read',
    ];

    protected $casts = [
        'pinned' => 'boolean',
        'is_read' => 'boolean',
        'expires_at' => 'datetime',
    ];

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function gradeLevel()
    {
        return $this->belongsTo(GradeLevel::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'target_id');
    }

    public function reads()
    {
        return $this->hasMany(AnnouncementRead::class);
    }

    public function attachments()
    {
        return $this->hasMany(AnnouncementAttachment::class);
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'announcement_section');
    }

    // Check if a specific user has read this announcement
    public function isReadBy($userId)
    {
        return $this->reads()->where('user_id', $userId)->exists();
    }

    // Mark as read by a user
    public function markAsReadBy($userId)
    {
        return $this->reads()->firstOrCreate(['user_id' => $userId], ['read_at' => now()]);
    }

    // Get read count
    public function readCount()
    {
        return $this->reads()->count();
    }

    // Scope: only active (not expired)
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    // Scope: pinned first, then by date
    public function scopeOrdered($query)
    {
        return $query->orderByDesc('pinned')->orderByDesc('created_at');
    }

    // Scope: for a specific school year
    public function scopeForSchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    // Scope: announcements visible to a student
    public function scopeVisibleToStudent($query, Student $student = null)
    {
        if (!$student) {
            return $query->where(function ($q) {
                $q->where('target', 'students')
                  ->orWhere('target', 'all');
            });
        }

        return $query->where(function ($q) use ($student) {
            // 'all' target = visible to everyone (admin & teachers)
            $q->where('target', 'all')
              ->orWhere(function ($sq) use ($student) {
                  $sq->where('target', 'students')
                     ->where(function ($sqq) use ($student) {
                         // Admin announcements have no sections → school-wide
                         // Teacher announcements have sections → section-scoped
                         $sqq->doesntHave('sections')
                             ->orWhereHas('sections.students', function ($stq) use ($student) {
                                 $stq->where('students.id', $student->id);
                             });
                     });
              });
        });
    }

    // Scope: announcements visible to a teacher
    public function scopeVisibleToTeacher($query, Teacher $teacher = null)
    {
        return $query->where(function ($q) {
            $q->where('target', 'teachers')
              ->orWhere('target', 'all');
        });
    }

    // Scope: unread by a specific user
    public function scopeUnreadBy($query, $userId)
    {
        return $query->whereDoesntHave('reads', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    // Priority badge color helper
    public function priorityColor()
    {
        return match ($this->priority) {
            'urgent' => 'rose',
            'important' => 'amber',
            default => 'slate',
        };
    }

    // Priority icon helper
    public function priorityIcon()
    {
        return match ($this->priority) {
            'urgent' => 'fa-exclamation-circle',
            'important' => 'fa-star',
            default => 'fa-bullhorn',
        };
    }
}
