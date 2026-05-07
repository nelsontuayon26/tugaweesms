<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Models\GradeLevel;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use App\Events\AnnouncementPosted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * List all announcements created by this teacher.
     */
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $activeSchoolYear = SchoolYear::getActive();
        $sections = $teacher
            ? $teacher->sections()->with('gradeLevel')->where('school_year_id', $activeSchoolYear?->id)->get()
            : collect();
        $gradeLevels = GradeLevel::orderBy('order')->get();

        $announcements = Announcement::where('author_id', Auth::id())
            ->with(['schoolYear', 'section', 'gradeLevel', 'reads', 'sections.gradeLevel'])
            ->withCount('reads')
            ->ordered()
            ->paginate(10);

        return view('teacher.announcements.index', compact(
            'announcements',
            'sections',
            'gradeLevels'
        ));
    }

    /**
     * Show announcements received by this teacher.
     */
    public function received()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $activeSchoolYear = SchoolYear::getActive();

        $query = Announcement::visibleToTeacher($teacher)
            ->active()
            ->where('author_id', '!=', Auth::id())
            ->with(['author', 'attachments', 'reads' => function ($q) {
                $q->where('user_id', Auth::id());
            }])
            ->ordered();

        if ($activeSchoolYear) {
            $query->forSchoolYear($activeSchoolYear->id);
        }

        $announcements = $query->paginate(10);

        return view('teacher.announcements.received', compact('announcements'));
    }

    /**
     * Show the form to create a new announcement.
     */
    public function create()
    {
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $activeSchoolYear = SchoolYear::getActive();
        $sections = $teacher
            ? $teacher->sections()->with('gradeLevel')->where('school_year_id', $activeSchoolYear?->id)->get()
            : collect();
        $gradeLevels = GradeLevel::orderBy('order')->get();

        return view('teacher.announcements.create', compact(
            'sections',
            'gradeLevels',
            'activeSchoolYear'
        ));
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'priority' => 'required|in:normal,important,urgent',
            'pinned' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $activeSchoolYear = SchoolYear::getActive();

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'author_id' => Auth::id(),
            'scope' => 'school',
            'target_id' => null,
            'grade_level_id' => null,
            'priority' => $validated['priority'],
            'pinned' => $request->boolean('pinned'),
            'expires_at' => $validated['expires_at'] ?? null,
            'school_year_id' => $activeSchoolYear?->id,
            'target' => 'students',
        ]);

        // Attach teacher's assigned sections so only their pupils see it
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $sectionIds = $teacher
            ? $teacher->sections()->where('school_year_id', $activeSchoolYear?->id)->pluck('id')->toArray()
            : [];
        if (!empty($sectionIds)) {
            $announcement->sections()->attach($sectionIds);
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcement-attachments', 'public');
                AnnouncementAttachment::create([
                    'announcement_id' => $announcement->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Broadcast to all users
        broadcast(new AnnouncementPosted($announcement))->toOthers();

        // Send database notifications to targeted users
        try {
            $userIds = [];

            if ($announcement->target === 'all') {
                $userIds = \App\Models\User::whereHas('role', function($q) {
                    $q->whereRaw('LOWER(name) IN (?, ?)', ['student', 'teacher']);
                })->pluck('id')->toArray();
            } elseif ($announcement->target === 'students') {
                // Only notify students in the teacher's assigned sections
                $sectionIds = $announcement->sections()->pluck('sections.id')->toArray();
                $userIds = \App\Models\User::whereHas('role', function($q) {
                    $q->whereRaw('LOWER(name) = ?', ['student']);
                })->whereHas('student', function($q) use ($sectionIds) {
                    $q->whereIn('section_id', $sectionIds);
                })->pluck('id')->toArray();
            } elseif ($announcement->target === 'teachers') {
                $userIds = \App\Models\User::whereHas('role', function($q) {
                    $q->whereRaw('LOWER(name) = ?', ['teacher']);
                })->pluck('id')->toArray();
            }

            // Exclude the creator from receiving their own notification
            $userIds = array_diff($userIds, [Auth::id()]);

            if (!empty($userIds)) {
                \App\Services\NotificationService::notifyMany(
                    $userIds,
                    'announcement',
                    "New Announcement: {$announcement->title}",
                    strip_tags($announcement->message),
                    [
                        'url' => route('student.announcements.show', $announcement),
                        'announcement_id' => $announcement->id,
                    ]
                );
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send announcement notifications: ' . $e->getMessage());
        }

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement posted successfully!');
    }

    /**
     * Show the form to edit an announcement.
     */
    public function edit(Announcement $announcement)
    {
        if ($announcement->author_id !== Auth::id()) {
            abort(403);
        }

        $teacher = Teacher::where('user_id', Auth::id())->first();
        $activeSchoolYear = SchoolYear::getActive();
        $sections = $teacher
            ? $teacher->sections()->with('gradeLevel')->where('school_year_id', $activeSchoolYear?->id)->get()
            : collect();
        $gradeLevels = GradeLevel::orderBy('order')->get();
        $activeSchoolYear = SchoolYear::getActive();

        $announcement->load('attachments');

        return view('teacher.announcements.edit', compact(
            'announcement',
            'sections',
            'gradeLevels',
            'activeSchoolYear'
        ));
    }

    /**
     * Update an announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        if ($announcement->author_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:10000',
            'priority' => 'required|in:normal,important,urgent',
            'pinned' => 'boolean',
            'expires_at' => 'nullable|date',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'remove_attachments' => 'nullable|array',
            'remove_attachments.*' => 'integer|exists:announcement_attachments,id',
        ]);

        // Teachers can only send announcements to pupils (students)
        $announcement->update([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'scope' => 'school',
            'target' => 'students',
            'priority' => $validated['priority'],
            'pinned' => $request->boolean('pinned'),
            'expires_at' => $validated['expires_at'] ?? null,
        ]);

        // Remove selected attachments
        if ($request->has('remove_attachments')) {
            foreach ($request->input('remove_attachments') as $attId) {
                $att = AnnouncementAttachment::where('id', $attId)->where('announcement_id', $announcement->id)->first();
                if ($att) {
                    Storage::disk('public')->delete($att->file_path);
                    $att->delete();
                }
            }
        }

        // Handle new attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcement-attachments', 'public');
                AnnouncementAttachment::create([
                    'announcement_id' => $announcement->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Show a single announcement with read stats.
     */
    public function show(Announcement $announcement)
    {
        // Authorization: teacher can only view their own announcements
        if ($announcement->author_id !== Auth::id()) {
            abort(403);
        }

        $announcement->load(['author', 'schoolYear', 'section.gradeLevel', 'gradeLevel', 'attachments', 'sections']);

        $activeSchoolYear = SchoolYear::getActive();

        // Get read stats based on target audience
        $readStats = [];
        if ($announcement->target === 'students') {
            // Teacher announcements are scoped to their sections
            $sectionIds = $announcement->sections->pluck('id')->toArray();
            if (!empty($sectionIds)) {
                $totalRecipients = Student::whereIn('section_id', $sectionIds)
                    ->where('status', 'active')
                    ->count();
            } else {
                // Admin announcements (no sections) = all enrolled students
                $totalRecipients = Student::whereHas('enrollments', function ($q) use ($activeSchoolYear) {
                    $q->where('school_year_id', $activeSchoolYear?->id)->where('status', 'enrolled');
                })->count();
            }
            $readCount = $announcement->readCount();
            $readStats = ['total' => $totalRecipients, 'read' => $readCount, 'unread' => max(0, $totalRecipients - $readCount)];
        } elseif ($announcement->target === 'teachers') {
            $totalRecipients = Teacher::where('status', 'active')->count();
            $readCount = $announcement->readCount();
            $readStats = ['total' => $totalRecipients, 'read' => $readCount, 'unread' => max(0, $totalRecipients - $readCount)];
        } elseif ($announcement->target === 'all') {
            $totalStudents = Student::whereHas('enrollments', function ($q) use ($activeSchoolYear) {
                $q->where('school_year_id', $activeSchoolYear?->id)->where('status', 'enrolled');
            })->count();
            $totalTeachers = Teacher::where('status', 'active')->count();
            $totalRecipients = $totalStudents + $totalTeachers;
            $readCount = $announcement->readCount();
            $readStats = ['total' => $totalRecipients, 'read' => $readCount, 'unread' => max(0, $totalRecipients - $readCount)];
        }

        return view('teacher.announcements.show', compact('announcement', 'readStats'));
    }

    /**
     * Toggle pin status.
     */
    public function togglePin(Announcement $announcement)
    {
        if ($announcement->author_id !== Auth::id()) {
            abort(403);
        }

        $announcement->update(['pinned' => !$announcement->pinned]);

        return back()->with('success', $announcement->pinned ? 'Announcement pinned.' : 'Announcement unpinned.');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement)
    {
        if ($announcement->author_id !== Auth::id()) {
            abort(403);
        }

        // Delete attachments from storage
        foreach ($announcement->attachments as $att) {
            Storage::disk('public')->delete($att->file_path);
        }

        $announcement->delete();

        return redirect()->route('teacher.announcements.index')
            ->with('success', 'Announcement deleted.');
    }
}
