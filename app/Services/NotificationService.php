<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserNotificationSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a notification and send via preferred channels
     */
    public static function notify(
        int $userId,
        string $type,
        string $title,
        string $body,
        array $data = [],
        bool $sendEmail = true,
        bool $sendSms = false
    ): ?Notification {
        // Check global admin settings for this notification type
        $globalSettingKey = match ($type) {
            'announcement', 'message', 'event' => 'notify_announcements',
            'grade' => 'notify_grades',
            'attendance' => 'notify_attendance',
            'student', 'enrollment' => 'notify_new_student',
            default => null,
        };

        if ($globalSettingKey && !(bool) Setting::get($globalSettingKey, true)) {
            Log::info("Notification skipped: Global setting {$globalSettingKey} is disabled");
            return null;
        }

        // Get user and their settings
        $user = User::find($userId);
        if (!$user) {
            Log::warning("Notification failed: User {$userId} not found");
            return null;
        }

        $settings = UserNotificationSetting::forUser($userId);

        // Check if user wants this type of notification
        if (!$settings->wantsEmail($type) && !$settings->wantsSms($type)) {
            Log::info("Notification skipped: User {$userId} disabled {$type} notifications");
            return null;
        }

        // Create database notification
        $notification = Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);

        // Send Email if enabled
        if ($sendEmail && $settings->wantsEmail($type) && $user->email) {
            self::sendEmail($user, $notification);
        }

        // Send SMS if enabled
        if ($sendSms && $settings->wantsSms($type) && $settings->phone_number) {
            self::sendSms($settings->phone_number, $notification);
        }

        return $notification;
    }

    /**
     * Send notification to multiple users
     */
    public static function notifyMany(
        array $userIds,
        string $type,
        string $title,
        string $body,
        array $data = []
    ): int {
        $count = 0;
        foreach ($userIds as $userId) {
            $notification = self::notify($userId, $type, $title, $body, $data);
            if ($notification) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Send email notification
     */
    private static function sendEmail(User $user, Notification $notification): void
    {
        try {
            Mail::raw(
                "{$notification->body}\n\n" .
                "View online: {$notification->url}\n\n" .
                "Tugawe Elementary School",
                function ($message) use ($user, $notification) {
                    $message->to($user->email)
                        ->subject($notification->title);
                }
            );
            
            $notification->markAsEmailSent();
            Log::info("Email notification sent to {$user->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
        }
    }

    /**
     * Send SMS notification
     */
    private static function sendSms(string $phoneNumber, Notification $notification): void
    {
        if (!(bool) Setting::get('sms_enabled', false)) {
            Log::info("SMS skipped: Global sms_enabled setting is disabled");
            return;
        }

        // For now, log the SMS (integrate with real SMS provider later)
        // Philippine SMS providers: Semaphore, Chikka, Twilio, etc.
        
        Log::info("SMS would be sent to {$phoneNumber}: {$notification->title}");
        
        // Example integration with Semaphore (Philippines):
        /*
        try {
            $apiKey = config('services.semaphore.api_key');
            $response = Http::post('https://api.semaphore.co/api/v4/messages', [
                'apikey' => $apiKey,
                'number' => $phoneNumber,
                'message' => $notification->title . "\n" . $notification->body,
                'sendername' => 'TUGAWE',
            ]);
            
            if ($response->successful()) {
                $notification->markAsSmsSent();
            }
        } catch (\Exception $e) {
            Log::error("Failed to send SMS: " . $e->getMessage());
        }
        */
        
        // Mark as sent for demo purposes
        $notification->markAsSmsSent();
    }

    /**
     * Get unread count for a user
     */
    public static function getUnreadCount(int $userId): int
    {
        return Notification::forUser($userId)->unread()->count();
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead(int $userId): void
    {
        Notification::forUser($userId)->unread()->update(['read_at' => now()]);
    }

    /**
     * Send message notification
     */
    public static function notifyNewMessage(int $recipientId, string $senderName, string $messagePreview, int $messageId): void
    {
        self::notify(
            $recipientId,
            'message',
            "New message from {$senderName}",
            $messagePreview,
            [
                'url' => route('student.messages.show', $messageId),
                'message_id' => $messageId,
            ],
            true,  // send email
            false  // don't send SMS for messages (too frequent)
        );
    }

    /**
     * Send announcement notification
     */
    public static function notifyAnnouncement(int $userId, string $title, string $content, int $announcementId): void
    {
        self::notify(
            $userId,
            'announcement',
            "New Announcement: {$title}",
            Str::limit($content, 150),
            [
                'url' => route('student.announcements.show', $announcementId),
                'announcement_id' => $announcementId,
            ],
            true,  // send email
            true   // send SMS for important announcements
        );
    }

    /**
     * Send grade posted notification
     */
    public static function notifyGradePosted(int $studentUserId, string $subject, string $grade): void
    {
        self::notify(
            $studentUserId,
            'grade',
            "Grade Posted: {$subject}",
            "Your grade for {$subject} has been posted: {$grade}",
            [
                'url' => route('student.grades'),
            ],
            true,  // send email
            false  // don't send SMS for grades
        );
    }

    /**
     * Send absence alert to parents
     */
    public static function notifyAbsence(int $parentUserId, string $studentName, string $date, string $status): void
    {
        self::notify(
            $parentUserId,
            'attendance',
            "Absence Alert: {$studentName}",
            "{$studentName} was marked {$status} on {$date}",
            [
                'url' => route('student.attendance'),
            ],
            true,  // send email
            true   // send SMS for absence alerts (urgent)
        );
    }
}
