<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Events\MessageSent;
use App\Events\MessageRead;
use App\Services\NotificationService;

class CommunicationController extends Controller
{
    /**
     * Show communications dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Auto-create teacher record if missing (same as DashboardController)
        $teacher = Teacher::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => $user->first_name ?? 'Teacher',
                'last_name'  => $user->last_name ?? '',
                'email'      => $user->email,
            ]
        );
        
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        
        // Load teacher's sections for the active school year with enrolled students
        $sectionsQuery = $teacher->sections()->with('gradeLevel');
        if ($activeSchoolYear) {
            $sectionsQuery->where('school_year_id', $activeSchoolYear->id);
        }
        $sections = $sectionsQuery->get();
        
        // Eager-load enrolled students for each section via enrollments
        foreach ($sections as $section) {
            $section->load(['enrollments' => function($q) use ($activeSchoolYear) {
                $q->where('status', 'enrolled');
                if ($activeSchoolYear) {
                    $q->where('school_year_id', $activeSchoolYear->id);
                }
                $q->with(['student.user']);
            }]);
        }
        
        $tab = $request->get('tab', 'inbox');
        $search = $request->get('search');

        // Get ALL conversations where user is either sender or recipient (unified inbox)
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
        $unreadCount = Message::receivedBy($user->id)->unread()->count();

        // Stats for dashboard cards
        $sentCount = Message::where('sender_id', $user->id)
            ->whereDate('created_at', today())
            ->count();
        
        $parentCount = 0;
        foreach ($sections as $section) {
            foreach ($section->enrollments as $enrollment) {
                if ($enrollment->student && $enrollment->student->user_id) {
                    $parentCount++;
                }
            }
        }
        // Make unique count (a parent could have multiple children)
        $parentCount = $sections->flatMap(function ($s) {
            return $s->enrollments->pluck('student.user_id')->filter();
        })->unique()->count();
        
        $announcementCount = Message::where('sender_id', $user->id)
            ->where('is_bulk', true)
            ->count();

        return view('teacher.communications.index', compact(
            'sections',
            'messages',
            'unreadCount',
            'tab',
            'search',
            'sentCount',
            'parentCount',
            'announcementCount'
        ));
    }

    /**
     * Send message to student(s)
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:individual,section,multiple',
            'recipient_id' => 'required_if:recipient_type,individual|exists:users,id',
            'section_id' => 'required_if:recipient_type,section|exists:sections,id',
            'recipient_ids' => 'required_if:recipient_type,multiple|array|min:1',
            'recipient_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'body' => 'required|string|max:5000',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        if ($request->recipient_type === 'individual') {
            return $this->sendIndividualMessage($request);
        } elseif ($request->recipient_type === 'section') {
            return $this->sendBulkMessage($request);
        } else {
            return $this->sendMultipleMessages($request);
        }
    }

    private function sendIndividualMessage(Request $request)
    {
        $message = Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'is_read' => false,
            'is_bulk' => false,
        ]);

        $this->handleAttachments($request, $message);

        // Broadcast the message in real-time (optional - doesn't fail if Reverb is down)
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Silently fail if Reverb is not running
            \Log::info('Broadcast failed (Reverb may not be running): ' . $e->getMessage());
        }

        return redirect()->route('teacher.communications.index', ['tab' => 'sent'])
            ->with('success', 'Message sent successfully!');
    }

    private function sendMultipleMessages(Request $request)
    {
        $sentCount = 0;

        foreach ($request->recipient_ids as $userId) {
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $userId,
                'subject' => $request->subject,
                'body' => $request->body,
                'is_read' => false,
                'is_bulk' => true,
            ]);

            $this->handleAttachments($request, $message);
            
            // Broadcast to each recipient (optional)
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::info('Broadcast failed (Reverb may not be running): ' . $e->getMessage());
            }
            
            $sentCount++;
        }

        return redirect()->route('teacher.communications.index', ['tab' => 'sent'])
            ->with('success', "Message sent to {$sentCount} parent(s)!");
    }

    private function sendBulkMessage(Request $request)
    {
        $section = Section::findOrFail($request->section_id);
        $currentSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        
        // Get students through enrollments (the reliable way)
        $query = Student::whereHas('enrollments', function ($q) use ($section, $currentSchoolYear) {
            $q->where('section_id', $section->id)
              ->where('status', 'enrolled');
            if ($currentSchoolYear) {
                $q->where('school_year_id', $currentSchoolYear->id);
            }
        })->whereHas('user');
        
        $students = $query->with('user')->get();

        $sentCount = 0;

        foreach ($students as $student) {
            $message = Message::create([
                'sender_id' => Auth::id(),
                'recipient_id' => $student->user_id,
                'section_id' => $section->id,
                'subject' => $request->subject,
                'body' => $request->body,
                'is_read' => false,
                'is_bulk' => true,
            ]);

            $this->handleAttachments($request, $message);
            
            // Broadcast to each student (optional)
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $e) {
                \Log::info('Broadcast failed (Reverb may not be running): ' . $e->getMessage());
            }
            
            $sentCount++;
        }

        return redirect()->route('teacher.communications.index', ['tab' => 'sent'])
            ->with('success', "Message sent to {$sentCount} students in {$section->name}!");
    }

    private function handleAttachments(Request $request, Message $message)
    {
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
    }

    /**
     * Show message details
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        if ($message->recipient_id === $user->id && !$message->is_read) {
            $message->markAsRead();
            // Broadcast read receipt to sender (optional)
            try {
                broadcast(new MessageRead($message))->toOthers();
            } catch (\Exception $e) {
                \Log::info('Broadcast failed (Reverb may not be running): ' . $e->getMessage());
            }
        }

        $message->load(['sender', 'recipient', 'attachments', 'replies.sender', 'replies.attachments']);

        return view('teacher.communications.show', compact('message'));
    }

    /**
     * Reply to a message
     */
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

        // Broadcast the reply in real-time (optional)
        try {
            broadcast(new MessageSent($reply))->toOthers();
        } catch (\Exception $e) {
            \Log::info('Broadcast failed (Reverb may not be running): ' . $e->getMessage());
        }

        // Send notification to recipient
        try {
            NotificationService::notifyNewMessage(
                $recipientId,
                $user->full_name,
                Str::limit($request->body, 100),
                $reply->id
            );
        } catch (\Exception $e) {
            \Log::info('Notification failed: ' . $e->getMessage());
        }

        return redirect()->route('teacher.communications.show', $message->parent_id ?? $message)
            ->with('success', 'Reply sent successfully!');
    }

    /**
     * Delete a message
     */
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

        return redirect()->route('teacher.communications.index')
            ->with('success', 'Message deleted successfully!');
    }

    /**
     * Download attachment
     */
    public function downloadAttachment(MessageAttachment $attachment)
    {
        $user = Auth::user();
        $message = $attachment->message;

        if ($message->recipient_id !== $user->id && $message->sender_id !== $user->id) {
            abort(403);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Get students for section (AJAX)
     */
    public function getSectionStudents(Section $section)
    {
        // Get current school year
        $currentSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        
        \Log::info('Loading students for section: ' . $section->id . ', school year: ' . ($currentSchoolYear ? $currentSchoolYear->id : 'none'));
        
        // Query students through enrollments (the reliable way)
        $query = Student::whereHas('enrollments', function ($q) use ($section, $currentSchoolYear) {
            $q->where('section_id', $section->id)
              ->where('status', 'enrolled');
            if ($currentSchoolYear) {
                $q->where('school_year_id', $currentSchoolYear->id);
            }
        })->whereHas('user');
        
        $students = $query->with('user')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->user_id,
                    'name' => $student->user->full_name,
                    'lrn' => $student->lrn,
                ];
            });
        
        \Log::info('Found ' . $students->count() . ' students');

        return response()->json($students);
    }
}
