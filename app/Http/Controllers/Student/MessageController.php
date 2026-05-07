<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Services\NotificationService;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $tab = $request->get('tab', 'inbox');
        $search = $request->get('search');

        $section = $student ? $student->section : null;
        $gradeLevel = $student ? $student->gradeLevel : null;
        
        // Get ALL conversations where user is either sender or recipient (unified inbox like WhatsApp)
        $query = Message::whereNull('parent_id')
            ->where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                  ->orWhere('recipient_id', $user->id);
            })
            ->with(['sender', 'recipient', 'attachments', 'replies']);

        if ($search) {
            $query->search($search);
        }

        $messages = $query->orderByDesc('created_at')->paginate(20);
        
        // Count unread messages (where user is recipient and not read)
        $unreadCount = Message::receivedBy($user->id)->unread()->count();

        return view('student.messages.index', compact(
            'messages', 'unreadCount', 'tab', 'search', 
            'student', 'section', 'gradeLevel'
        ));
    }

    public function show(Message $message)
    {
        $user = Auth::user();
        $student = $user->student;
        $section = $student ? $student->section : null;
        $gradeLevel = $student ? $student->gradeLevel : null;

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->markAsRead();
            broadcast(new MessageRead($message))->toOthers();
        }

        $message->load(['sender', 'recipient', 'attachments', 'replies.sender', 'replies.attachments']);

        return view('student.messages.show', compact('message', 'student', 'section', 'gradeLevel'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        $section = $student ? $student->section : null;
        $gradeLevel = $student ? $student->gradeLevel : null;
        
        // Determine available teachers
        $teachers = collect();
        $defaultTeacher = null;
        
        if ($section && $section->teacher) {
            $defaultTeacher = $section->teacher->user;
        }
        
        // For Grade 5 and 6, show all teachers for subject-specific concerns
        if ($gradeLevel && in_array($gradeLevel->name, ['Grade 5', 'Grade 6', '5', '6'])) {
            $teachers = User::whereHas('role', function ($q) {
                $q->where('name', 'Teacher');
            })->where('is_active', true)
              ->where('id', '!=', $user->id)
              ->get();
        }

        $replyTo = null;
        if ($request->has('reply_to')) {
            $replyTo = Message::findOrFail($request->reply_to);
        }

        return view('student.messages.create', compact(
            'teachers', 'replyTo', 'student', 'section', 'gradeLevel', 'defaultTeacher'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;
        
        // Validation rules
        $rules = [
            'body' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ];
        
        // For Grade 5-6, teacher selection is required
        $gradeLevel = $student ? $student->gradeLevel : null;
        if ($gradeLevel && in_array($gradeLevel->name, ['Grade 5', 'Grade 6', '5', '6'])) {
            $rules['recipient_id'] = 'required|exists:users,id';
        }
        
        $request->validate($rules);

        // Determine recipient
        if ($request->has('recipient_id')) {
            $recipientId = $request->recipient_id;
        } else {
            // Default to section adviser
            $section = $student ? $student->section : null;
            if (!$section || !$section->teacher) {
                return redirect()->back()->with('error', 'No adviser assigned to your section. Please contact administration.');
            }
            $recipientId = $section->teacher->user_id;
        }

        // Auto-generate subject if not provided
        $subject = $request->subject ?: 'Message from ' . $user->full_name;

        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'parent_id' => $request->parent_id,
            'subject' => $subject,
            'body' => $request->body,
            'is_read' => false,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments/' . $message->id, 'public');
                MessageAttachment::create([
                    'message_id' => $message->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            \Log::info('Broadcast failed: ' . $e->getMessage());
        }

        // Send notification to recipient
        try {
            NotificationService::notifyNewMessage(
                $recipientId,
                $user->full_name,
                Str::limit($request->body, 100),
                $message->id
            );
        } catch (\Exception $e) {
            \Log::info('Notification failed: ' . $e->getMessage());
        }

        return redirect()->route('student.messages.show', $message)
            ->with('success', 'Message sent to your teacher!');
    }

    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'body' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $user = Auth::user();

        // Both sender and recipient can reply - determine who to reply to
        if ($message->sender_id === $user->id) {
            // Current user is the sender, reply goes to recipient
            $recipientId = $message->recipient_id;
        } elseif ($message->recipient_id === $user->id) {
            // Current user is the recipient, reply goes to sender
            $recipientId = $message->sender_id;
        } else {
            abort(403, 'You are not part of this conversation.');
        }

        $reply = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $recipientId,
            'parent_id' => $message->parent_id ?? $message->id,
            'subject' => 'Re: ' . $message->subject,
            'body' => $request->body,
            'is_read' => false,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments/' . $reply->id, 'public');
                MessageAttachment::create([
                    'message_id' => $reply->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        try {
            broadcast(new MessageSent($reply))->toOthers();
        } catch (\Exception $e) {
            \Log::info('Broadcast failed: ' . $e->getMessage());
        }

        return redirect()->route('student.messages.show', $message->parent_id ?? $message)
            ->with('success', 'Reply sent!');
    }

    public function destroy(Message $message)
    {
        $user = Auth::user();

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        foreach ($message->attachments as $attachment) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        $message->delete();

        return redirect()->route('student.messages.index')
            ->with('success', 'Message deleted.');
    }

    public function downloadAttachment(MessageAttachment $attachment)
    {
        $user = Auth::user();
        $message = $attachment->message;

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }
}
