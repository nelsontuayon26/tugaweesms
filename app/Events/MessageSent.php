<?php

namespace App\Events;

use App\Models\Message;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\SchoolYear;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message->load(['sender', 'recipient', 'attachments']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        if ($this->message->is_group_chat && $this->message->section_id) {
            // Broadcast to all section members
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();

            $studentIds = Enrollment::where('section_id', $this->message->section_id)
                ->where('status', 'enrolled')
                ->when($activeSchoolYear, fn($q) => $q->where('school_year_id', $activeSchoolYear->id))
                ->pluck('student_id');

            $memberUserIds = Student::whereIn('id', $studentIds)
                ->whereNotNull('user_id')
                ->pluck('user_id')
                ->toArray();

            // Also include teacher
            $section = \App\Models\Section::find($this->message->section_id);
            if ($section && $section->teacher_id) {
                $teacherUser = \App\Models\User::whereHas('teacher', fn($q) => $q->where('id', $section->teacher_id))->first();
                if ($teacherUser) {
                    $memberUserIds[] = $teacherUser->id;
                }
            }

            $memberUserIds = array_unique($memberUserIds);

            foreach ($memberUserIds as $userId) {
                $channels[] = new PrivateChannel('user.' . $userId);
            }
        } else {
            // 1-to-1 direct message
            $channels[] = new PrivateChannel('user.' . $this->message->recipient_id);
            $channels[] = new PrivateChannel('user.' . $this->message->sender_id);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'subject' => $this->message->subject,
            'body' => $this->message->body,
            'preview' => $this->message->preview,
            'is_read' => $this->message->is_read,
            'is_bulk' => $this->message->is_bulk,
            'is_group_chat' => $this->message->is_group_chat,
            'section_id' => $this->message->section_id,
            'sender' => [
                'id' => $this->message->sender->id,
                'name' => $this->message->sender->full_name,
                'first_name' => $this->message->sender->first_name,
                'last_name' => $this->message->sender->last_name,
            ],
            'recipient' => $this->message->recipient ? [
                'id' => $this->message->recipient->id,
                'name' => $this->message->recipient->full_name,
            ] : null,
            'attachments_count' => $this->message->attachments->count(),
            'created_at' => $this->message->created_at->toISOString(),
            'created_at_human' => $this->message->created_at->diffForHumans(),
        ];
    }
}
