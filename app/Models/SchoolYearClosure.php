<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYearClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'status',
        'total_sections',
        'finalized_sections',
        'all_sections_finalized',
        'closure_started_at',
        'closure_completed_at',
        'closed_by',
        'finalization_deadline',
        'auto_close_enabled',
        'auto_close_at',
        'admin_notes',
        'closure_summary',
        'promoted_students_count',
        'retained_students_count',
        'graduated_students_count',
    ];

    protected $casts = [
        'all_sections_finalized' => 'boolean',
        'auto_close_enabled' => 'boolean',
        'closure_started_at' => 'datetime',
        'closure_completed_at' => 'datetime',
        'finalization_deadline' => 'datetime',
        'auto_close_at' => 'datetime',
    ];

    // Relationships
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReadyToClose($query)
    {
        return $query->where('status', 'ready_to_close');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    // Helpers
    public function getProgressPercentage(): int
    {
        if ($this->total_sections === 0) return 0;
        return round(($this->finalized_sections / $this->total_sections) * 100);
    }

    public function isReadyToClose(): bool
    {
        return $this->all_sections_finalized || 
               ($this->finalization_deadline && now()->greaterThan($this->finalization_deadline));
    }

    public function canBeClosed(): bool
    {
        return in_array($this->status, ['ready_to_close', 'pending']) &&
               $this->schoolYear &&
               $this->schoolYear->is_active;
    }

    public function updateProgress(): void
    {
        $finalizedCount = SectionFinalization::where('school_year_id', $this->school_year_id)
            ->where('is_fully_finalized', true)
            ->count();
        
        $this->finalized_sections = $finalizedCount;
        $this->all_sections_finalized = ($finalizedCount >= $this->total_sections && $this->total_sections > 0);
        
        if ($this->all_sections_finalized && $this->status === 'pending') {
            $this->status = 'ready_to_close';
        }
        
        $this->save();
    }
}
