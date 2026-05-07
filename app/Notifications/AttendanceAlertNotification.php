<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class AttendanceAlertNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $date;
    protected $subject;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $status, string $date, string $subject = '')
    {
        $this->status = $status;
        $this->date = $date;
        $this->subject = $subject;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class, 'database'];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        $title = match($this->status) {
            'absent' => 'Marked Absent',
            'late' => 'Marked Late',
            default => 'Attendance Updated'
        };

        $body = match($this->status) {
            'absent' => "You were marked absent on {$this->date}",
            'late' => "You were marked late on {$this->date}",
            default => "Your attendance was updated for {$this->date}"
        };

        return (new WebPushMessage)
            ->title($title)
            ->body($body . ($this->subject ? " in {$this->subject}" : ''))
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->action('View Attendance', '/student/attendance')
            ->data([
                'url' => '/student/attendance',
                'type' => 'attendance',
                'status' => $this->status
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'attendance',
            'status' => $this->status,
            'date' => $this->date,
            'message' => "Attendance marked as {$this->status} on {$this->date}",
        ];
    }
}
