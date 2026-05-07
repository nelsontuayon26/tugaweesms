<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReportTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category',
        'type',
        'columns',
        'filters',
        'chart_config',
        'default_params',
        'icon',
        'color',
        'is_system',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'columns' => 'array',
        'filters' => 'array',
        'chart_config' => 'array',
        'default_params' => 'array',
        'is_system' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Categories
    const CATEGORY_ACADEMIC = 'academic';
    const CATEGORY_ATTENDANCE = 'attendance';
    const CATEGORY_ENROLLMENT = 'enrollment';
    const CATEGORY_FINANCIAL = 'financial';
    const CATEGORY_COMPLIANCE = 'compliance';
    const CATEGORY_ANALYTICS = 'analytics';

    // Types
    const TYPE_TABLE = 'table';
    const TYPE_CHART = 'chart';
    const TYPE_COMBINED = 'combined';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function savedReports(): HasMany
    {
        return $this->hasMany(SavedReport::class, 'template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    public static function getCategories(): array
    {
        return [
            self::CATEGORY_ACADEMIC => 'Academic Reports',
            self::CATEGORY_ATTENDANCE => 'Attendance Reports',
            self::CATEGORY_ENROLLMENT => 'Enrollment Reports',
            self::CATEGORY_FINANCIAL => 'Financial Reports',
            self::CATEGORY_COMPLIANCE => 'DepEd Compliance',
            self::CATEGORY_ANALYTICS => 'Analytics & Insights',
        ];
    }

    public static function getTypes(): array
    {
        return [
            self::TYPE_TABLE => 'Table Only',
            self::TYPE_CHART => 'Chart Only',
            self::TYPE_COMBINED => 'Table & Chart',
        ];
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? $this->category;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? $this->type;
    }
}
