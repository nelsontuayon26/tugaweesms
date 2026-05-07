<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class MessageReceivedNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $sender)
    {
        $this->message = $message;
        $this->sender = $sender;
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
        $senderName = $this->sender->first_name . ' ' . $this->sender->last_name;
        
        return (new WebPushMessage)
            ->title('New Message from ' . $senderName)
            ->body(str_limit($this->message->content, 100))
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->action('Reply', '/student/messenger')
            ->data([
                'url' => '/student/messenger',
                'type' => 'message',
                'message_id' => $this->message->id,
                'sender_id' => $this->sender->id
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'message',
            'sender' => $this->sender->first_name . ' ' . $this->sender->last_name,
            'message' => str_limit($this->message->content, 200),
            'message_id' => $this->message->id,
        ];
    }
}
