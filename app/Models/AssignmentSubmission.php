<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'attachments',
        'score',
        'feedback',
        'submitted_at',
        'graded_at',
        'graded_by',
        'status',
    ];

    protected $casts = [
        'attachments' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function grader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    public function getIsLateAttribute(): bool
    {
        if (!$this->assignment) return false;
        
        $dueDateTime = $this->assignment->due_date->copy();
        if ($this->assignment->due_time) {
            $dueDateTime->setTimeFrom($this->assignment->due_time);
        } else {
            $dueDateTime->setTime(23, 59, 59);
        }
        
        return $this->submitted_at->greaterThan($dueDateTime);
    }

    public function getPercentageScoreAttribute(): ?float
    {
        if ($this->score === null) return null;
        return round(($this->score / $this->assignment->total_points) * 100, 1);
    }
}
