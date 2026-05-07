<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display the student's announcement feed.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$student) {
            return view('student.announcements.index', [
                'announcements' => collect(),
                'unreadCount' => 0,
            ]);
        }

        $activeSchoolYear = SchoolYear::getActive();

        // Get announcements visible to this student
        $query = Announcement::visibleToStudent($student)
            ->active()
            ->with(['author', 'attachments', 'reads' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        if ($activeSchoolYear) {
            $query->forSchoolYear($activeSchoolYear->id);
        }

        $announcements = $query->ordered()->get();

        // Mark announcements as viewed for unread count calculation
        $unreadCount = Announcement::visibleToStudent($student)
            ->active()
            ->when($activeSchoolYear, fn($q) => $q->forSchoolYear($activeSchoolYear->id))
            ->unreadBy($user->id)
            ->count();

        return view('student.announcements.index', compact(
            'announcements',
            'unreadCount',
            'student'
        ));
    }

    /**
     * Show a single announcement.
     */
    public function show(Announcement $announcement, Request $request)
    {
        $user = Auth::user();
        $student = $user->student;

        // Verify this announcement is visible to the student
        if (!$this->isVisibleToStudent($announcement, $student)) {
            abort(403);
        }

        // Mark as read
        $announcement->markAsReadBy($user->id);

        $announcement->load(['author', 'attachments']);

        return view('student.announcements.show', compact('announcement'));
    }

    /**
     * Mark an announcement as read via AJAX.
     */
    public function markAsRead(Announcement $announcement)
    {
        $user = Auth::user();
        $student = $user->student;

        if (!$this->isVisibleToStudent($announcement, $student)) {
            return response()->json(['error' => 'Not visible'], 403);
        }

        $announcement->markAsReadBy($user->id);

        return response()->json(['success' => true]);
    }

    /**
     * Check if an announcement is visible to a student.
     */
    private function isVisibleToStudent(Announcement $announcement, $student): bool
    {
        if (!$student) return false;

        // 'all' target = visible to everyone
        if ($announcement->target === 'all') {
            return true;
        }

        // 'students' target = check section scoping
        if ($announcement->target === 'students') {
            // No sections attached = school-wide (admin announcement)
            if ($announcement->sections()->count() === 0) {
                return true;
            }
            // Sections attached = only pupils in those sections
            return $announcement->sections()
                ->whereHas('students', function ($q) use ($student) {
                    $q->where('students.id', $student->id);
                })->exists();
        }

        return false;
    }
}
