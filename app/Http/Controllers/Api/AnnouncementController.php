<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Get unread announcement count for the current user.
     */
    public function unreadCount(Request $request)
    {
        $user = Auth::user();
        $roleName = strtolower($user->role?->name ?? '');
        $activeSchoolYear = SchoolYear::getActive();

        $query = Announcement::active()
            ->unreadBy($user->id)
            ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id));

        if ($roleName === 'pupil' && $user->student) {
            $query->visibleToStudent($user->student);
        } elseif ($roleName === 'teacher' && $user->teacher) {
            $query->visibleToTeacher($user->teacher);
        } else {
            // Admin or other: see school-wide and all
            $query->whereIn('scope', ['school', 'all']);
        }

        return response()->json([
            'count' => $query->count(),
        ]);
    }

    /**
     * Mark all visible announcements as read for the current user.
     */
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        $roleName = strtolower($user->role?->name ?? '');
        $activeSchoolYear = SchoolYear::getActive();

        $query = Announcement::active()
            ->unreadBy($user->id)
            ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id));

        if ($roleName === 'pupil' && $user->student) {
            $query->visibleToStudent($user->student);
        } elseif ($roleName === 'teacher' && $user->teacher) {
            $query->visibleToTeacher($user->teacher);
        } else {
            $query->whereIn('scope', ['school', 'all']);
        }

        $announcements = $query->get();
        foreach ($announcements as $announcement) {
            $announcement->markAsReadBy($user->id);
        }

        return response()->json([
            'success' => true,
            'marked' => $announcements->count(),
        ]);
    }
}
