<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSchoolDay extends Model
{
    use HasFactory;

    protected $table = 'attendance_school_days';

    protected $fillable = [
        'section_id',
        'school_year_id',
        'month',
        'year',
        'total_school_days',
        'school_dates',
        'non_school_days',
        'teacher_notes',
        'configured_by',
        'configured_at',
        'is_finalized',
    ];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'total_school_days' => 'integer',
        'school_dates' => 'array',
        'non_school_days' => 'array',
        'is_finalized' => 'boolean',
        'configured_at' => 'datetime',
    ];

    // Relationships
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function configuredByUser()
    {
        return $this->belongsTo(User::class, 'configured_by');
    }

    // Scopes
    public function scopeForMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    public function scopeFinalized($query)
    {
        return $query->where('is_finalized', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_finalized', false);
    }

    // Helpers
    public function getMonthName(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getNonSchoolDaysCount(): int
    {
        if (!$this->non_school_days) return 0;
        return count($this->non_school_days);
    }

    public function addNonSchoolDay(string $date, string $reason): void
    {
        $nonSchoolDays = $this->non_school_days ?? [];
        
        // Check if already exists
        $exists = collect($nonSchoolDays)->firstWhere('date', $date);
        if (!$exists) {
            $nonSchoolDays[] = [
                'date' => $date,
                'reason' => $reason,
                'added_at' => now()->toDateTimeString(),
            ];
            $this->non_school_days = $nonSchoolDays;
            $this->recalculateSchoolDays();
        }
    }

    public function removeNonSchoolDay(string $date): void
    {
        $nonSchoolDays = $this->non_school_days ?? [];
        $this->non_school_days = collect($nonSchoolDays)
            ->reject(fn($day) => $day['date'] === $date)
            ->values()
            ->toArray();
        $this->recalculateSchoolDays();
    }

    public function recalculateSchoolDays(): void
    {
        // Get all weekdays in the month
        $weekdays = $this->getWeekdaysInMonth();
        
        // Remove non-school days
        $nonSchoolDates = collect($this->non_school_days ?? [])->pluck('date')->toArray();
        $this->school_dates = array_values(array_diff($weekdays, $nonSchoolDates));
        $this->total_school_days = count($this->school_dates);
    }

    private function getWeekdaysInMonth(): array
    {
        $dates = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $this->year, $this->month, $day);
            $dayOfWeek = date('N', strtotime($date));
            
            // Skip weekends (6 = Saturday, 7 = Sunday)
            if ($dayOfWeek < 6) {
                $dates[] = $date;
            }
        }
        
        return $dates;
    }

    public static function getOrCreateForSection($sectionId, $schoolYearId, $month, $year): self
    {
        $record = self::where([
            'section_id' => $sectionId,
            'school_year_id' => $schoolYearId,
            'month' => $month,
            'year' => $year,
        ])->first();

        if (!$record) {
            $record = new self([
                'section_id' => $sectionId,
                'school_year_id' => $schoolYearId,
                'month' => $month,
                'year' => $year,
            ]);
            $record->recalculateSchoolDays();
            $record->save();
        }

        return $record;
    }
}
