<?php

namespace App\Events;

use App\Models\Announcement;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnouncementPosted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $announcement;

    /**
     * Create a new event instance.
     */
    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement->load(['author', 'attachments']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast on a public channel so all users can listen
        return [
            new Channel('announcements'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'announcement.posted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'message' => \Str::limit($this->announcement->message, 200),
            'scope' => $this->announcement->scope,
            'priority' => $this->announcement->priority,
            'pinned' => $this->announcement->pinned,
            'author' => $this->announcement->author ? [
                'id' => $this->announcement->author->id,
                'name' => $this->announcement->author->full_name ?? $this->announcement->author->name,
            ] : null,
            'created_at' => $this->announcement->created_at->toISOString(),
            'attachment_count' => $this->announcement->attachments->count(),
        ];
    }
}
