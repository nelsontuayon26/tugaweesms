<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserNotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'email_new_message',
        'email_announcement',
        'email_grade_posted',
        'email_attendance_alert',
        'email_assignment_due',
        'sms_new_message',
        'sms_announcement',
        'sms_grade_posted',
        'sms_attendance_alert',
        'sms_assignment_due',
        'phone_number',
        'phone_verified',
    ];

    protected $casts = [
        'email_new_message' => 'boolean',
        'email_announcement' => 'boolean',
        'email_grade_posted' => 'boolean',
        'email_attendance_alert' => 'boolean',
        'email_assignment_due' => 'boolean',
        'sms_new_message' => 'boolean',
        'sms_announcement' => 'boolean',
        'sms_grade_posted' => 'boolean',
        'sms_attendance_alert' => 'boolean',
        'sms_assignment_due' => 'boolean',
        'phone_verified' => 'boolean',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function wantsEmail(string $type): bool
    {
        return match($type) {
            'message' => $this->email_new_message,
            'announcement' => $this->email_announcement,
            'grade' => $this->email_grade_posted,
            'attendance' => $this->email_attendance_alert,
            'assignment' => $this->email_assignment_due,
            default => false,
        };
    }

    public function wantsSms(string $type): bool
    {
        return match($type) {
            'message' => $this->sms_new_message,
            'announcement' => $this->sms_announcement,
            'grade' => $this->sms_grade_posted,
            'attendance' => $this->sms_attendance_alert,
            'assignment' => $this->sms_assignment_due,
            default => false,
        };
    }

    // Get or create settings for a user
    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );
    }
}
