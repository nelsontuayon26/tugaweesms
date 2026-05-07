<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SavedReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'template_id',
        'user_id',
        'parameters',
        'column_visibility',
        'format',
        'schedule_frequency',
        'schedule_config',
        'last_run_at',
        'next_run_at',
        'is_favorite',
        'is_scheduled',
        'notes',
    ];

    protected $casts = [
        'parameters' => 'array',
        'column_visibility' => 'array',
        'schedule_config' => 'array',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'is_favorite' => 'boolean',
        'is_scheduled' => 'boolean',
    ];

    // Formats
    const FORMAT_HTML = 'html';
    const FORMAT_PDF = 'pdf';
    const FORMAT_EXCEL = 'excel';
    const FORMAT_CSV = 'csv';

    // Frequencies
    const FREQ_DAILY = 'daily';
    const FREQ_WEEKLY = 'weekly';
    const FREQ_MONTHLY = 'monthly';
    const FREQ_QUARTERLY = 'quarterly';

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedule(): HasOne
    {
        return $this->hasOne(ReportSchedule::class, 'saved_report_id');
    }

    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('last_run_at', 'desc');
    }

    public static function getFormats(): array
    {
        return [
            self::FORMAT_HTML => 'Web View',
            self::FORMAT_PDF => 'PDF Document',
            self::FORMAT_EXCEL => 'Excel Spreadsheet',
            self::FORMAT_CSV => 'CSV File',
        ];
    }

    public static function getFrequencies(): array
    {
        return [
            self::FREQ_DAILY => 'Daily',
            self::FREQ_WEEKLY => 'Weekly',
            self::FREQ_MONTHLY => 'Monthly',
            self::FREQ_QUARTERLY => 'Quarterly',
        ];
    }

    public function getFormatLabelAttribute(): string
    {
        return self::getFormats()[$this->format] ?? $this->format;
    }

    public function getFrequencyLabelAttribute(): ?string
    {
        return $this->schedule_frequency ? (self::getFrequencies()[$this->schedule_frequency] ?? $this->schedule_frequency) : null;
    }
}
