<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * Get all conversations for the authenticated user
     */
    public function index()
    {
        try {
            $user = Auth::user();
            $roleName = strtolower($user->role?->name ?? '');
            
            $conversations = [];
            
            // === 1-to-1 Direct Conversations ===
            $sentTo = Message::where('sender_id', $user->id)
                ->whereNull('is_group_chat')
                ->distinct()
                ->pluck('recipient_id');
                
            $receivedFrom = Message::where('recipient_id', $user->id)
                ->whereNull('is_group_chat')
                ->distinct()
                ->pluck('sender_id');
                
            $userIds = $sentTo->merge($receivedFrom)->unique()->values();
            
            $allowedUserIds = $this->getAllowedContactIds($user, $roleName);
            
            foreach ($userIds as $otherUserId) {
                if (!in_array($otherUserId, $allowedUserIds)) {
                    continue;
                }
                
                $otherUser = User::find($otherUserId);
                if (!$otherUser) continue;
                
                $unreadCount = Message::where('sender_id', $otherUserId)
                    ->where('recipient_id', $user->id)
                    ->where('is_read', false)
                    ->count();
                
                $lastMessage = Message::betweenUsers($user->id, $otherUserId)
                    ->latest()
                    ->first();
                
                $isOnline = Cache::has('user-online-' . $otherUserId);
                
                $conversations[] = [
                    'id' => 'user_' . $otherUserId,
                    'type' => 'user',
                    'user_id' => $otherUserId,
                    'name' => $otherUser->full_name,
                    'initials' => $this->getInitials($otherUser->full_name),
                    'is_online' => $isOnline,
                    'lastMessage' => $lastMessage ? substr($lastMessage->body, 0, 50) : 'No messages yet',
                    'lastMessageTime' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                    'unreadCount' => $unreadCount,
                ];
            }
            
            // === Group Conversations (Sections) ===
            $groupSections = $this->getAllowedGroupSections($user, $roleName);
            
            foreach ($groupSections as $section) {
                try {
                    $lastMessage = Message::where('section_id', $section->id)
                        ->where('is_group_chat', true)
                        ->latest()
                        ->first();
                    
                    $unreadCount = $this->getGroupUnreadCount($user->id, $section->id);
                    
                    $conversations[] = [
                        'id' => 'group_' . $section->id,
                        'type' => 'group',
                        'section_id' => $section->id,
                        'name' => $section->name,
                        'initials' => $this->getInitials($section->name),
                        'is_online' => false,
                        'lastMessage' => $lastMessage ? substr($lastMessage->body, 0, 50) : 'No messages yet',
                        'lastMessageTime' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                        'unreadCount' => $unreadCount,
                    ];
                } catch (\Exception $e) {
                    \Log::warning('ConversationController: group section ' . $section->id . ' query failed: ' . $e->getMessage());
                }
            }
            
            // Sort by last message time (most recent first)
            usort($conversations, function ($a, $b) {
                return strtotime($b['lastMessageTime'] ?? '1970-01-01') <=> strtotime($a['lastMessageTime'] ?? '1970-01-01');
            });
            
            return response()->json(['conversations' => $conversations]);
        } catch (\Exception $e) {
            \Log::error('ConversationController index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return response()->json(['conversations' => [], 'error' => 'Failed to load conversations'], 500);
        }
    }

    /**
     * Get messages for a specific conversation
     */
    public function show(Request $request, $conversationId)
    {
        $user = Auth::user();
        $roleName = strtolower($user->role?->name ?? '');
        
        // Check if this is a group conversation
        if (str_starts_with($conversationId, 'group_')) {
            $sectionId = (int) str_replace('group_', '', $conversationId);
            return $this->showGroupMessages($user, $sectionId);
        }
        
        // 1-to-1 conversation
        $userId = (int) $conversationId;
        $allowedUserIds = $this->getAllowedContactIds($user, $roleName);
        
        \Log::info('API show: user=' . $user->id . ' role=' . $roleName . ' target=' . $userId . ' allowed=' . implode(',', $allowedUserIds));
        
        if (!in_array($userId, $allowedUserIds)) {
            \Log::warning('API show: 403 - user ' . $userId . ' not in allowed list');
            return response()->json([
                'error' => 'You can only message currently enrolled students.',
                'messages' => [],
            ], 403);
        }
        
        try {
            $messages = Message::betweenUsers($user->id, $userId)
                ->with('attachments')
                ->orderBy('created_at', 'asc')
                ->get();

            $formattedMessages = $this->formatMessages($messages);

            $isOnline = Cache::has('user-online-' . $userId);
            $otherUser = User::find($userId);

            return response()->json([
                'messages' => $formattedMessages,
                'has_more' => false,
                'contact' => $otherUser ? [
                    'id' => $otherUser->id,
                    'name' => $otherUser->full_name,
                    'initials' => $this->getInitials($otherUser->full_name),
                    'is_online' => $isOnline,
                ] : null,
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error loading messages: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to load messages',
                'messages' => [],
            ], 500);
        }
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead($conversationId)
    {
        $user = Auth::user();

        if (str_starts_with($conversationId, 'group_')) {
            $sectionId = (int) str_replace('group_', '', $conversationId);
            Cache::put("messenger:group:read:{$user->id}:{$sectionId}", now()->timestamp, now()->addDays(30));
            return response()->json(['success' => true]);
        }

        $userId = (int) $conversationId;

        Message::where('sender_id', $userId)
            ->where('recipient_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json(['success' => true]);
    }

    /**
     * Get IDs of users that the current user is allowed to message.
     * For teachers: only students enrolled in their sections for the active school year.
     * For students: only their teacher from their current enrollment.
     */
    private function getAllowedContactIds($user, $roleName)
    {
        $allowedIds = [];
        
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        
        if ($roleName === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();
            if ($teacher) {
                // Build list of section IDs this teacher is assigned to
                // NOTE: We do NOT filter sections by school_year_id here because
                // sections may be tagged to a different year than their enrollments.
                // We filter enrollments by school year instead.
                $teacherSectionIds = collect();
                
                // 1) Sections where teacher is the adviser (teacher_id)
                $teacherSectionIds = $teacherSectionIds->merge(
                    Section::where('teacher_id', $teacher->id)->pluck('id')
                );
                
                // 2) Sections where teacher is assigned via teacher_sections pivot
                $pivotSectionIds = \DB::table('teacher_sections')
                    ->where('teacher_id', $teacher->id)
                    ->pluck('section_id');
                $teacherSectionIds = $teacherSectionIds->merge($pivotSectionIds)->unique()->values();
                
                \Log::info('API Conversation: Teacher ' . $teacher->id . ' section IDs: ' . $teacherSectionIds->implode(', '));
                
                if ($teacherSectionIds->isNotEmpty()) {
                    // Find enrollments in teacher's sections for active school year
                    $enrollmentQuery = Enrollment::whereIn('section_id', $teacherSectionIds)
                        ->where('status', 'enrolled');
                    
                    if ($activeSchoolYear) {
                        $enrollmentQuery->where('school_year_id', $activeSchoolYear->id);
                    }
                    
                    $studentIds = $enrollmentQuery->pluck('student_id');
                    
                    $userIds = Student::whereIn('id', $studentIds)
                        ->whereNotNull('user_id')
                        ->pluck('user_id');
                    
                    $allowedIds = $userIds->toArray();
                    
                    \Log::info('API Conversation: Found ' . count($allowedIds) . ' allowed contacts');
                }
            }
        } elseif ($roleName === 'pupil') {
            $enrollmentQuery = Enrollment::whereHas('student', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->where('status', 'enrolled')
                ->with('section');
            
            if ($activeSchoolYear) {
                $enrollmentQuery->where('school_year_id', $activeSchoolYear->id);
            }
            
            $enrollment = $enrollmentQuery->first();
            
            if ($enrollment && $enrollment->section && $enrollment->section->teacher_id) {
                $teacherUser = User::whereHas('teacher', function ($q) use ($enrollment) {
                    $q->where('id', $enrollment->section->teacher_id);
                })->first();
                
                if ($teacherUser) {
                    $allowedIds[] = $teacherUser->id;
                }
            }
        }
        
        // Convert all IDs to integers for consistent comparison
        return array_map('intval', $allowedIds);
    }

    /**
     * Load group chat messages for a section
     */
    private function showGroupMessages($user, $sectionId)
    {
        $section = Section::find($sectionId);
        if (!$section) {
            return response()->json(['error' => 'Section not found', 'messages' => []], 404);
        }

        // Security: verify user is a member of this section's group chat
        $allowedSections = $this->getAllowedGroupSections($user, strtolower($user->role?->name ?? ''));
        if (!$allowedSections->contains('id', $sectionId)) {
            return response()->json(['error' => 'You are not a member of this group.', 'messages' => []], 403);
        }

        try {
            $messages = Message::where('section_id', $sectionId)
                ->where('is_group_chat', true)
                ->with(['sender', 'attachments'])
                ->orderBy('created_at', 'asc')
                ->get();

            $formattedMessages = $this->formatMessages($messages);

            // Mark as read when viewing
            Cache::put("messenger:group:read:{$user->id}:{$sectionId}", now()->timestamp, now()->addDays(30));

            return response()->json([
                'messages' => $formattedMessages,
                'has_more' => false,
                'contact' => [
                    'id' => 'group_' . $sectionId,
                    'name' => $section->name,
                    'initials' => $this->getInitials($section->name),
                    'is_online' => false,
                    'is_group' => true,
                    'member_count' => $section->students()->whereNotIn('status', ['completed', 'inactive'])->count() + 1, // +1 for teacher
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading group messages: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load messages', 'messages' => []], 500);
        }
    }

    /**
     * Format messages for API response
     */
    private function formatMessages($messages)
    {
        return $messages->map(function ($msg) {
            return [
                'id' => $msg->id,
                'body' => $msg->body,
                'sender_id' => $msg->sender_id,
                'recipient_id' => $msg->recipient_id,
                'section_id' => $msg->section_id,
                'is_group_chat' => (bool) $msg->is_group_chat,
                'is_read' => (bool) $msg->is_read,
                'is_edited' => (bool) $msg->is_edited,
                'sender' => $msg->sender ? [
                    'id' => $msg->sender->id,
                    'name' => $msg->sender->full_name,
                ] : null,
                'created_at' => $msg->created_at ? $msg->created_at->toISOString() : null,
                'attachments' => $msg->attachments->map(function ($att) {
                    return [
                        'id' => $att->id,
                        'file_name' => $att->file_name,
                        'file_type' => $att->file_type,
                        'url' => route('api.attachments.view', $att),
                        'download_url' => route('api.attachments.download', $att),
                    ];
                }),
            ];
        });
    }

    /**
     * Get sections that the user can participate in as group chats
     */
    private function getAllowedGroupSections($user, $roleName)
    {
        try {
            $activeSchoolYear = SchoolYear::where('is_active', true)->first();

            if ($roleName === 'teacher') {
                $teacher = Teacher::where('user_id', $user->id)->first();
                if (!$teacher) return collect();

                $sectionIds = Section::where('teacher_id', $teacher->id)->pluck('id');
                try {
                    $pivotIds = DB::table('teacher_sections')->where('teacher_id', $teacher->id)->pluck('section_id');
                    $sectionIds = $sectionIds->merge($pivotIds);
                } catch (\Exception $e) {
                    \Log::warning('ConversationController: teacher_sections query failed: ' . $e->getMessage());
                }
                $allSectionIds = $sectionIds->unique();

                return Section::whereIn('id', $allSectionIds)
                    ->when($activeSchoolYear, fn($q) => $q->where('school_year_id', $activeSchoolYear->id))
                    ->get();
            }

            if ($roleName === 'pupil') {
                $enrollment = Enrollment::whereHas('student', fn($q) => $q->where('user_id', $user->id))
                    ->where('status', 'enrolled')
                    ->when($activeSchoolYear, fn($q) => $q->where('school_year_id', $activeSchoolYear->id))
                    ->first();

                if ($enrollment && $enrollment->section_id) {
                    return Section::where('id', $enrollment->section_id)->get();
                }
            }
        } catch (\Exception $e) {
            \Log::error('ConversationController getAllowedGroupSections error: ' . $e->getMessage());
        }

        return collect();
    }

    /**
     * Get unread count for a group conversation
     */
    private function getGroupUnreadCount($userId, $sectionId)
    {
        try {
            $lastRead = Cache::get("messenger:group:read:{$userId}:{$sectionId}");

            $query = Message::where('section_id', $sectionId)
                ->where('is_group_chat', true)
                ->where('sender_id', '!=', $userId);

            if ($lastRead) {
                $query->where('created_at', '>', date('Y-m-d H:i:s', $lastRead));
            }

            return $query->count();
        } catch (\Exception $e) {
            \Log::warning('ConversationController getGroupUnreadCount error: ' . $e->getMessage());
            return 0;
        }
    }

    private function getInitials($name)
    {
        $parts = explode(' ', $name);
        $initials = '';
        foreach ($parts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
            if (strlen($initials) >= 2) break;
        }
        return $initials;
    }
}
