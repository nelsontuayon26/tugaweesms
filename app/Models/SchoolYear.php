<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active', 'description'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    // Scope for active school year
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get active school year (helper method)
    public static function getActive()
    {
        return self::active()->first();
    }

    public function enrollments()
{
    return $this->hasMany(Enrollment::class);
}

    public function qrCode()
    {
        return $this->hasOne(SchoolYearQrCode::class)->latest();
    }

    public function qrCodes()
    {
        return $this->hasMany(SchoolYearQrCode::class);
    }

    public function quarters()
    {
        return $this->hasMany(Quarter::class)->orderBy('quarter_number');
    }

    public function closure()
    {
        return $this->hasOne(SchoolYearClosure::class);
    }

    /**
     * Get the currently active quarter for this school year
     */
    public function currentQuarter(): ?Quarter
    {
        return $this->quarters()
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->first();
    }

    /**
     * Get a specific quarter by number
     */
    public function quarter(int $number): ?Quarter
    {
        return $this->quarters()->where('quarter_number', $number)->first();
    }

    /**
     * Auto-create default quarters if they don't exist
     */
    public function ensureQuartersExist(): void
    {
        if ($this->quarters()->count() > 0) {
            return;
        }

        if (!$this->start_date || !$this->end_date) {
            return;
        }

        $start = $this->start_date->copy();
        $end = $this->end_date->copy();
        $totalDays = $start->diffInDays($end) + 1;
        $quarterDays = (int) floor($totalDays / 4);

        for ($i = 1; $i <= 4; $i++) {
            $qStart = $start->copy()->addDays(($i - 1) * $quarterDays);
            $qEnd = $i === 4 
                ? $end->copy() 
                : $start->copy()->addDays($i * $quarterDays)->subDay();

            Quarter::create([
                'school_year_id' => $this->id,
                'quarter_number' => $i,
                'start_date' => $qStart,
                'end_date' => $qEnd,
                'is_active' => true,
            ]);
        }
    }
}