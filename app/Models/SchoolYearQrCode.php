<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYearQrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'qr_code_token',
        'qr_code_image_path',
        'is_active',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function isValid()
    {
        return $this->is_active && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}