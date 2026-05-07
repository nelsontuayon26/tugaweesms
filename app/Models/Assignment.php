<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'section_id',
        'subject_id',
        'teacher_id',
        'title',
        'description',
        'type',
        'total_points',
        'due_date',
        'due_time',
        'allow_late_submission',
        'instructions',
        'attachments',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'due_time' => 'datetime:H:i',
        'allow_late_submission' => 'boolean',
        'attachments' => 'array',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function getSubmissionStatsAttribute(): array
    {
        $totalStudents = $this->section->students()->count();
        $submitted = $this->submissions()->count();
        $graded = $this->submissions()->where('status', 'graded')->count();

        return [
            'total' => $totalStudents,
            'submitted' => $submitted,
            'graded' => $graded,
            'pending' => $submitted - $graded,
            'not_submitted' => $totalStudents - $submitted,
            'submission_rate' => $totalStudents > 0 ? round(($submitted / $totalStudents) * 100, 1) : 0,
        ];
    }

    public function getIsOverdueAttribute(): bool
    {
        $dueDateTime = $this->due_date->copy();
        if ($this->due_time) {
            $dueDateTime->setTimeFrom($this->due_time);
        } else {
            $dueDateTime->setTime(23, 59, 59);
        }
        return now()->greaterThan($dueDateTime);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'published' => '<span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">Published</span>',
            'draft' => '<span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">Draft</span>',
            'closed' => '<span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium">Closed</span>',
            default => '',
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'homework' => 'fa-home',
            'quiz' => 'fa-question-circle',
            'project' => 'fa-folder-open',
            'exam' => 'fa-file-alt',
            'activity' => 'fa-running',
            default => 'fa-tasks',
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'homework' => 'blue',
            'quiz' => 'amber',
            'project' => 'purple',
            'exam' => 'rose',
            'activity' => 'emerald',
            default => 'slate',
        };
    }
}
