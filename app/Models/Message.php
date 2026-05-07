<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'section_id',
        'parent_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'is_bulk',
        'is_group_chat',
        'is_edited',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_bulk' => 'boolean',
        'is_group_chat' => 'boolean',
        'is_edited' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id')->orderBy('created_at');
    }

    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class);
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('recipient_id', $userId)
              ->orWhere('sender_id', $userId);
        });
    }

    public function scopeReceivedBy($query, $userId)
    {
        return $query->where('recipient_id', $userId);
    }

    public function scopeSentBy($query, $userId)
    {
        return $query->where('sender_id', $userId);
    }

    public function scopeBetweenUsers($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where(function ($sq) use ($userId1, $userId2) {
                $sq->where('sender_id', $userId1)->where('recipient_id', $userId2);
            })->orWhere(function ($sq) use ($userId1, $userId2) {
                $sq->where('sender_id', $userId2)->where('recipient_id', $userId1);
            });
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%")
              ->orWhereHas('sender', function ($sq) use ($search) {
                  $sq->where('first_name', 'like', "%{$search}%")
                     ->orWhere('last_name', 'like', "%{$search}%");
              });
        });
    }

    // Helpers
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function getPreviewAttribute()
    {
        return substr(strip_tags($this->body), 0, 100) . (strlen($this->body) > 100 ? '...' : '');
    }

    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }
}
