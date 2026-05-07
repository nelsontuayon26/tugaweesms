<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Quarter extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'quarter_number',
        'name',
        'start_date',
        'end_date',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    /**
     * Get display name (auto-generated if not set)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? match ($this->quarter_number) {
            1 => '1st Quarter',
            2 => '2nd Quarter',
            3 => '3rd Quarter',
            4 => '4th Quarter',
            default => "Quarter {$this->quarter_number}",
        };
    }

    /**
     * Check if a given date falls within this quarter
     */
    public function containsDate(Carbon|string $date): bool
    {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        return $date->between($this->start_date, $this->end_date);
    }

    /**
     * Check if this quarter is currently active (today falls within)
     */
    public function getIsCurrentAttribute(): bool
    {
        return $this->containsDate(now());
    }

    /**
     * Check if this quarter is in the past
     */
    public function getIsPastAttribute(): bool
    {
        if (!$this->end_date) {
            return false;
        }
        return now()->isAfter($this->end_date);
    }

    /**
     * Check if this quarter is in the future
     */
    public function getIsUpcomingAttribute(): bool
    {
        if (!$this->start_date) {
            return false;
        }
        return now()->isBefore($this->start_date);
    }

    /**
     * Get duration in days
     */
    public function getDurationDaysAttribute(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Get progress percentage (how far through the quarter we are)
     */
    public function getProgressPercentAttribute(): ?int
    {
        if (!$this->is_current || !$this->start_date || !$this->end_date) {
            return null;
        }
        
        $totalDays = $this->duration_days;
        $elapsedDays = $this->start_date->diffInDays(now()) + 1;
        
        return min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
    }

    /**
     * Scope: Get quarters for a specific school year
     */
    public function scopeForSchoolYear($query, int $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId)->orderBy('quarter_number');
    }

    /**
     * Scope: Currently active quarter
     */
    public function scopeCurrent($query)
    {
        $today = now()->toDateString();
        return $query->whereDate('start_date', '<=', $today)
                     ->whereDate('end_date', '>=', $today);
    }
}
