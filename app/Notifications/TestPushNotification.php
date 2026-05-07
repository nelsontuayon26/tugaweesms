<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class TestPushNotification extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    /**
     * Get the web push representation of the notification.
     */
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('TESSMS Test Notification')
            ->body('Push notifications are working correctly! 🎉')
            ->icon('/icons/icon-192x192.png')
            ->badge('/icons/badge-72x72.png')
            ->action('Open Dashboard', '/dashboard')
            ->data(['url' => '/dashboard', 'type' => 'test'])
            ->requireInteraction(true);
    }
}
