<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'data',
        'read_at',
        'sent_via_email_at',
        'sent_via_sms_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
        'sent_via_email_at' => 'datetime',
        'sent_via_sms_at' => 'datetime',
    ];

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helpers
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    public function markAsEmailSent(): void
    {
        $this->update(['sent_via_email_at' => now()]);
    }

    public function markAsSmsSent(): void
    {
        $this->update(['sent_via_sms_at' => now()]);
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function getUrlAttribute(): ?string
    {
        return $this->data['url'] ?? null;
    }
}
