<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods for common actions
    public static function log(
        string $action,
        string $entityType,
        ?int $entityId,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function logLogin(): self
    {
        return self::log('login', 'User', auth()->id(), 'User logged in');
    }

    public static function logLogout(): self
    {
        return self::log('logout', 'User', auth()->id(), 'User logged out');
    }

    public static function logCreated($entity, string $entityType): self
    {
        return self::log(
            'created',
            $entityType,
            $entity->id,
            "Created new {$entityType}",
            null,
            $entity->toArray()
        );
    }

    public static function logUpdated($entity, string $entityType, array $oldValues): self
    {
        return self::log(
            'updated',
            $entityType,
            $entity->id,
            "Updated {$entityType}",
            $oldValues,
            $entity->toArray()
        );
    }

    public static function logDeleted($entity, string $entityType): self
    {
        return self::log(
            'deleted',
            $entityType,
            $entity->id,
            "Deleted {$entityType}",
            $entity->toArray(),
            null
        );
    }

    public static function logApproval($entity, string $entityType): self
    {
        return self::log(
            'approved',
            $entityType,
            $entity->id,
            "Approved {$entityType}",
            ['status' => 'pending'],
            ['status' => 'approved']
        );
    }

    public static function logRejection($entity, string $entityType, ?string $reason = null): self
    {
        return self::log(
            'rejected',
            $entityType,
            $entity->id,
            "Rejected {$entityType}" . ($reason ? ": {$reason}" : ''),
            ['status' => 'pending'],
            ['status' => 'rejected']
        );
    }

    // Scope methods
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForEntity($query, string $type, int $id)
    {
        return $query->where('entity_type', $type)->where('entity_id', $id);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'created' => 'emerald',
            'updated' => 'blue',
            'deleted' => 'rose',
            'approved' => 'emerald',
            'rejected' => 'red',
            'login' => 'indigo',
            'logout' => 'slate',
            default => 'gray',
        };
    }

    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'created' => 'fa-plus-circle',
            'updated' => 'fa-edit',
            'deleted' => 'fa-trash-alt',
            'approved' => 'fa-check-circle',
            'rejected' => 'fa-times-circle',
            'login' => 'fa-sign-in-alt',
            'logout' => 'fa-sign-out-alt',
            default => 'fa-circle',
        };
    }
}
