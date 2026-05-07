<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'saved_report_id',
        'frequency',
        'schedule_config',
        'recipients',
        'format',
        'delivery_method',
        'last_sent_at',
        'next_send_at',
        'send_count',
        'is_active',
    ];

    protected $casts = [
        'schedule_config' => 'array',
        'recipients' => 'array',
        'last_sent_at' => 'datetime',
        'next_send_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Delivery methods
    const DELIVERY_EMAIL = 'email';
    const DELIVERY_DOWNLOAD = 'download';
    const DELIVERY_WEBHOOK = 'webhook';

    public function savedReport(): BelongsTo
    {
        return $this->belongsTo(SavedReport::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDue($query)
    {
        return $query->where('next_send_at', '<=', now());
    }

    public static function getDeliveryMethods(): array
    {
        return [
            self::DELIVERY_EMAIL => 'Email',
            self::DELIVERY_DOWNLOAD => 'Download Link',
            self::DELIVERY_WEBHOOK => 'Webhook',
        ];
    }

    public function markAsSent(): void
    {
        $this->update([
            'last_sent_at' => now(),
            'send_count' => $this->send_count + 1,
        ]);
    }
}
