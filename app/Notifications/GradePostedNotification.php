<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class GradePostedNotification extends Notification
{
    use Queueable;

    protected $subject;
    protected $grade;
    protected $studentName;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $subject, float $grade, string $studentName)
    {
        $this->subject = $subject;
        $this->grade = $grade;
        $this->studentName = $studentName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        $gradeText = number_format($this->grade, 1);
        
        return (new WebPushMessage)
            ->title("New Grade: {$this->subject}")
            ->body("{$this->studentName} received {$gradeText}%")
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->action('View Grades', '/student/grades')
            ->data([
                'url' => '/student/grades',
                'type' => 'grade',
                'subject' => $this->subject,
                'grade' => $this->grade
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'grade',
            'subject' => $this->subject,
            'grade' => $this->grade,
            'student_name' => $this->studentName,
            'message' => "New grade posted for {$this->subject}",
        ];
    }
}
