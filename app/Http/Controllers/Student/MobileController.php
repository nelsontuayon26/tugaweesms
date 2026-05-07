<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Announcement;
use App\Models\Grade;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MobileController extends Controller
{
    /**
     * Show mobile-optimized student dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        $student = $user->student;
        
        if (!$student) {
            abort(403, 'Student record not found');
        }

        $section = $student->section;
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

        // Get today's schedule
        $todaySchedule = [];
        if ($section) {
            $dayOfWeek = strtolower(now()->format('l'));
            $todaySchedule = Schedule::where('section_id', $section->id)
                ->where('day', $dayOfWeek)
                ->with('subject')
                ->orderBy('start_time')
                ->get()
                ->map(function ($schedule) {
                    $now = now()->format('H:i:s');
                    $start = $schedule->start_time;
                    $end = $schedule->end_time;
                    
                    $status = 'upcoming';
                    if ($now >= $start && $now <= $end) {
                        $status = 'current';
                    } elseif ($now > $end) {
                        $status = 'completed';
                    }
                    
                    return [
                        'name' => $schedule->subject->name ?? 'Unknown',
                        'time' => Carbon::parse($schedule->start_time)->format('g:i A') . ' - ' . 
                                  Carbon::parse($schedule->end_time)->format('g:i A'),
                        'status' => $status,
                    ];
                });
        }

        // Get attendance rate
        $attendanceRate = 100;
        if ($section && $activeSchoolYear) {
            $totalDays = Attendance::where('section_id', $section->id)
                ->where('student_id', $student->id)
                ->whereHas('schoolYear', function ($q) use ($activeSchoolYear) {
                    $q->where('id', $activeSchoolYear->id);
                })
                ->distinct('date')
                ->count('date');
            
            $presentDays = Attendance::where('section_id', $section->id)
                ->where('student_id', $student->id)
                ->whereIn('status', ['present', 'late'])
                ->distinct('date')
                ->count('date');
            
            if ($totalDays > 0) {
                $attendanceRate = round(($presentDays / $totalDays) * 100);
            }
        }

        // Get average grade
        $averageGrade = 0;
        if ($section) {
            $average = Grade::where('student_id', $student->id)
                ->whereHas('section', function ($q) use ($section) {
                    $q->where('id', $section->id);
                })
                ->avg('final_grade');
            $averageGrade = $average ? round($average) : 0;
        }

        // Get recent grades
        $recentGrades = Grade::where('student_id', $student->id)
            ->with('subject')
            ->orderBy('updated_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($grade) {
                return [
                    'subject' => $grade->subject->name ?? 'Unknown',
                    'score' => round($grade->final_grade ?? 0),
                    'type' => 'Final Grade',
                    'date' => $grade->updated_at->diffForHumans(),
                ];
            });

        // Get announcements
        $recentAnnouncements = Announcement::where('is_published', true)
            ->where(function ($q) use ($section) {
                $q->whereNull('target_sections')
                  ->orWhereJsonContains('target_sections', $section?->id);
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function ($announcement) {
                return [
                    'id' => $announcement->id,
                    'title' => $announcement->title,
                    'excerpt' => str_limit(strip_tags($announcement->content), 100),
                    'date' => $announcement->created_at->diffForHumans(),
                ];
            });

        // Get notifications
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($notification) {
                return [
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'time' => $notification->created_at->diffForHumans(),
                    'read' => $notification->read_at !== null,
                ];
            });

        // Get upcoming assignments
        $upcomingAssignments = []; // TODO: Implement assignments feature

        // Determine greeting
        $hour = now()->hour;
        $greeting = 'morning';
        if ($hour >= 12 && $hour < 17) {
            $greeting = 'afternoon';
        } elseif ($hour >= 17) {
            $greeting = 'evening';
        }

        $unreadNotifications = auth()->user()->unreadNotifications()->count();
        
        return view('student.dashboard-mobile', compact(
            'greeting',
            'todaySchedule',
            'attendanceRate',
            'averageGrade',
            'recentGrades',
            'recentAnnouncements',
            'notifications',
            'upcomingAssignments',
            'unreadNotifications'
        ));
    }
}
