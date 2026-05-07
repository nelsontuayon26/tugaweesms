<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\UserNotificationSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user - unified with events and announcements
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = (int) $request->get('per_page', 15);
        $page = (int) $request->get('page', 1);

        // Real database notifications — limit to recent 100 to avoid memory issues
        $dbNotifications = $user->notifications()->latest()->take(100)->get()->map(function ($n) {
            $data = $n->data ?? [];
            $data['url'] = $this->resolveNotificationUrl($data, $n->type) ?? ($data['url'] ?? null);
            return (object) [
                'id' => 'notif_' . $n->id,
                'type' => $n->type,
                'title' => $n->title,
                'body' => $n->body,
                'data' => $data,
                'read_at' => $n->read_at,
                'created_at' => $n->created_at,
                'source' => 'notification',
                'is_real' => true,
            ];
        });

        // Recent events as virtual notifications
        $eventNotifications = \App\Models\Event::where('date', '>=', today()->subDays(7))
            ->where(function ($q) use ($user) {
                $q->whereNull('created_by')->orWhere('created_by', '!=', $user->id);
            })
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($event) {
                return (object) [
                    'id' => 'event_' . $event->id,
                    'type' => 'event',
                    'title' => 'New Event: ' . $event->title,
                    'body' => 'Scheduled on ' . $event->date->format('F j, Y') . ($event->description ? ' — ' . strip_tags($event->description) : ''),
                    'data' => ['url' => $this->resolveNotificationUrl(['event_id' => $event->id], 'event'), 'event_id' => $event->id],
                    'read_at' => null,
                    'created_at' => $event->created_at,
                    'source' => 'event',
                    'is_real' => false,
                ];
            });

        // Recent announcements targeted to this user
        $teacher = $user->teacher;
        $student = $user->student;

        $announcementsQuery = \App\Models\Announcement::active()
            ->where('created_at', '>=', now()->subDays(30))
            ->where('author_id', '!=', $user->id)
            ->orderByDesc('created_at');

        if ($student) {
            $announcementsQuery->visibleToStudent($student);
        } elseif ($teacher) {
            $announcementsQuery->visibleToTeacher($teacher);
        } else {
            $announcementsQuery->whereIn('scope', ['school', 'all']);
        }

        $announcementNotifications = $announcementsQuery->take(50)->get()->map(function ($ann) use ($user) {
            $isRead = $ann->isReadBy($user->id);
            return (object) [
                'id' => 'ann_' . $ann->id,
                'type' => 'announcement',
                'title' => 'Announcement: ' . $ann->title,
                'body' => strip_tags($ann->message),
                'data' => ['url' => $this->resolveNotificationUrl(['announcement_id' => $ann->id], 'announcement'), 'announcement_id' => $ann->id],
                'read_at' => $isRead ? now() : null,
                'created_at' => $ann->created_at,
                'source' => 'announcement',
                'is_real' => false,
            ];
        });

        // Merge and sort
        $all = collect($dbNotifications)
            ->merge($eventNotifications)
            ->merge($announcementNotifications)
            ->sortByDesc('created_at')
            ->values();

        $total = $all->count();
        $unreadCount = $all->whereNull('read_at')->count();

        $items = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'notifications' => $notifications->items(),
                'unread_count' => $unreadCount,
            ]);
        }

        $role = strtolower(Auth::user()->role?->name ?? '');
        $view = match ($role) {
            'teacher' => 'notifications.index-teacher',
            'student' => 'notifications.index-student',
            default => 'notifications.index',
        };

        return view($view, compact('notifications', 'unreadCount', 'total'));
    }

    /**
     * Resolve role-appropriate URL for any notification type
     */
    private function resolveNotificationUrl(array $data, string $type): ?string
    {
        $role = strtolower(Auth::user()->role?->name ?? '');
        $isAdmin = in_array($role, ['admin', 'system admin']);
        $isTeacher = $role === 'teacher';

        // Events
        if (!empty($data['event_id'])) {
            if ($isAdmin) return route('admin.events.show', $data['event_id']);
            if ($isTeacher) return route('teacher.events.show', $data['event_id']);
            return route('student.events.show', $data['event_id']);
        }

        // Announcements
        if (!empty($data['announcement_id'])) {
            if ($isAdmin) return route('admin.announcements.show', $data['announcement_id']);
            if ($isTeacher) return route('teacher.announcements.show', $data['announcement_id']);
            return route('student.announcements.show', $data['announcement_id']);
        }

        // Messages
        if (!empty($data['message_id'])) {
            if ($isAdmin) return route('admin.dashboard');
            if ($isTeacher) return route('teacher.dashboard');
            return route('student.messages.show', $data['message_id']);
        }

        // Grades
        if ($type === 'grade' || str_contains($type, 'grade')) {
            if ($isAdmin) return route('admin.dashboard');
            if ($isTeacher) return route('teacher.dashboard');
            return route('student.grades');
        }

        // Attendance
        if ($type === 'attendance' || str_contains($type, 'attendance')) {
            if ($isAdmin) return route('admin.dashboard');
            if ($isTeacher) return route('teacher.dashboard');
            return route('student.attendance');
        }

        // Fallback to stored URL
        return $data['url'] ?? null;
    }

    /**
     * Get recent notifications (for dropdown) - unified feed including announcements and events
     */
    public function recent(): JsonResponse
    {
        $user = Auth::user();
        
        // Get real database notifications
        $dbNotifications = $user->notifications()
            ->take(10)
            ->get()
            ->map(function ($n) {
                $data = $n->data ?? [];
                $data['url'] = $this->resolveNotificationUrl($data, $n->type) ?? ($data['url'] ?? null);
                return [
                    'id' => 'notif_' . $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'body' => $n->body,
                    'data' => $data,
                    'read_at' => $n->read_at?->toIso8601String(),
                    'created_at' => $n->created_at->toIso8601String(),
                    'source' => 'notification',
                ];
            });

        // Get recent events as virtual notifications (exclude own created events)
        $eventNotifications = \App\Models\Event::where('date', '>=', today()->subDays(7))
            ->where(function ($q) use ($user) {
                $q->whereNull('created_by')->orWhere('created_by', '!=', $user->id);
            })
            ->orderByDesc('created_at')
            ->take(5)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => 'event_' . $event->id,
                    'type' => 'event',
                    'title' => 'New Event: ' . $event->title,
                    'body' => 'Scheduled on ' . $event->date->format('F j, Y') . ($event->description ? ' — ' . strip_tags($event->description) : ''),
                    'data' => ['url' => $this->resolveNotificationUrl(['event_id' => $event->id], 'event'), 'event_id' => $event->id],
                    'read_at' => null,
                    'created_at' => $event->created_at->toIso8601String(),
                    'source' => 'event',
                ];
            });

        // Get recent announcements targeted to this user
        $announcementNotifications = collect();
        $teacher = $user->teacher;
        $student = $user->student;

        $announcementsQuery = \App\Models\Announcement::active()
            ->where('created_at', '>=', now()->subDays(30))
            ->where('author_id', '!=', $user->id)
            ->orderByDesc('created_at');

        if ($student) {
            $announcementsQuery->visibleToStudent($student);
        } elseif ($teacher) {
            $announcementsQuery->visibleToTeacher($teacher);
        } else {
            // Admin or other roles - show school-wide and all-scope
            $announcementsQuery->whereIn('scope', ['school', 'all']);
        }

        $announcementNotifications = $announcementsQuery->take(5)->get()->map(function ($ann) use ($user) {
            $isRead = $ann->isReadBy($user->id);
            return [
                'id' => 'ann_' . $ann->id,
                'type' => 'announcement',
                'title' => 'Announcement: ' . $ann->title,
                'body' => strip_tags($ann->message),
                'data' => ['url' => $this->resolveNotificationUrl(['announcement_id' => $ann->id], 'announcement'), 'announcement_id' => $ann->id],
                'read_at' => $isRead ? now()->toIso8601String() : null,
                'created_at' => $ann->created_at->toIso8601String(),
                'source' => 'announcement',
            ];
        });

        // Merge and sort by created_at desc
        $allNotifications = collect($dbNotifications)
            ->merge($eventNotifications)
            ->merge($announcementNotifications)
            ->sortByDesc('created_at')
            ->values()
            ->take(15);

        $unreadCount = $allNotifications->whereNull('read_at')->count();

        return response()->json([
            'notifications' => $allNotifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read (handles real notifications, announcements, and events)
     */
    public function markAsRead(Request $request, $notificationId): JsonResponse
    {
        $user = Auth::user();

        // Handle virtual announcement notifications
        if (str_starts_with((string) $notificationId, 'ann_')) {
            $annId = (int) substr((string) $notificationId, 4);
            $announcement = \App\Models\Announcement::find($annId);
            if ($announcement) {
                $announcement->markAsReadBy($user->id);
            }
            return response()->json(['success' => true]);
        }

        // Handle virtual event notifications (no persistent read tracking, just acknowledge)
        if (str_starts_with((string) $notificationId, 'event_')) {
            return response()->json(['success' => true]);
        }

        // Handle real notifications
        $notification = Notification::find($notificationId);
        if (!$notification || $notification->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all notifications as read (including announcements)
     */
    public function markAllAsRead(): JsonResponse
    {
        $user = Auth::user();

        // Mark real notifications as read
        NotificationService::markAllAsRead($user->id);

        // Mark all recent targeted announcements as read
        $teacher = $user->teacher;
        $student = $user->student;

        $announcementsQuery = \App\Models\Announcement::active()
            ->where('created_at', '>=', now()->subDays(30));

        if ($student) {
            $announcementsQuery->visibleToStudent($student);
        } elseif ($teacher) {
            $announcementsQuery->visibleToTeacher($teacher);
        } else {
            $announcementsQuery->whereIn('scope', ['school', 'all']);
        }

        $announcementsQuery->chunkById(100, function ($announcements) use ($user) {
            foreach ($announcements as $announcement) {
                $announcement->markAsReadBy($user->id);
            }
        });

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    /**
     * Delete a notification
     */
    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'unread_count' => Auth::user()->unreadNotifications()->count(),
        ]);
    }

    /**
     * Get notification settings
     */
    public function getSettings(): JsonResponse
    {
        $settings = UserNotificationSetting::forUser(Auth::id());

        return response()->json($settings);
    }

    /**
     * Update notification settings
     */
    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email_new_message' => 'boolean',
            'email_announcement' => 'boolean',
            'email_grade_posted' => 'boolean',
            'email_attendance_alert' => 'boolean',
            'email_assignment_due' => 'boolean',
            'sms_new_message' => 'boolean',
            'sms_announcement' => 'boolean',
            'sms_grade_posted' => 'boolean',
            'sms_attendance_alert' => 'boolean',
            'sms_assignment_due' => 'boolean',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $settings = UserNotificationSetting::forUser(Auth::id());
        $settings->update($validated);

        return response()->json([
            'success' => true,
            'settings' => $settings->fresh(),
        ]);
    }

    /**
     * Get unread count (for badge) - includes notifications, events, and announcements
     */
    public function unreadCount(): JsonResponse
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        // Count unread recent events (exclude own created events)
        $eventCount = \App\Models\Event::where('date', '>=', today()->subDays(7))
            ->where('created_at', '>=', now()->subDays(30))
            ->where(function ($q) use ($user) {
                $q->whereNull('created_by')->orWhere('created_by', '!=', $user->id);
            })
            ->count();

        // Count unread recent announcements targeted to user
        $teacher = $user->teacher;
        $student = $user->student;

        $announcementsQuery = \App\Models\Announcement::active()
            ->where('created_at', '>=', now()->subDays(30))
            ->where(function ($q) use ($user) {
                $q->whereDoesntHave('reads', function ($sq) use ($user) {
                    $sq->where('user_id', $user->id);
                });
            });

        if ($student) {
            $announcementsQuery->visibleToStudent($student);
        } elseif ($teacher) {
            $announcementsQuery->visibleToTeacher($teacher);
        } else {
            $announcementsQuery->whereIn('scope', ['school', 'all']);
        }

        $count += $eventCount + $announcementsQuery->count();

        return response()->json([
            'count' => $count,
        ]);
    }
}
