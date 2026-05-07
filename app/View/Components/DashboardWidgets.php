<?php

namespace App\View\Components;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\EnrollmentApplication;
use App\Services\ActivityLogService;
use Illuminate\View\Component;

class DashboardWidgets extends Component
{
    public $stats;
    public $recentActivity;
    public $todaysBirthdays;
    public $pendingTasks;

    public function __construct()
    {
        $activeSchoolYear = SchoolYear::where('is_active', true)->first();
        $activeSchoolYearId = $activeSchoolYear?->id;

        // Quick stats
        $this->stats = [
            'total_students' => $activeSchoolYearId 
                ? Enrollment::where('school_year_id', $activeSchoolYearId)->whereIn('status', ['enrolled', 'approved'])->count()
                : Student::where('status', 'active')->count(),
            'pending_enrollments' => EnrollmentApplication::where('status', 'pending')->where('application_type', 'continuing')->count(),
            'pending_registrations' => \App\Models\Enrollment::where('status', 'pending')->count(),
        ];

        // Recent activity
        $this->recentActivity = ActivityLogService::getRecentActivity(5);

        // Today's birthdays
        $today = now()->format('m-d');
        $this->todaysBirthdays = Student::whereRaw("DATE_FORMAT(birthdate, '%m-%d') = ?", [$today])
            ->with('user')
            ->limit(5)
            ->get();

        // Pending tasks
        $this->pendingTasks = [
            [
                'title' => 'Pending Enrollments',
                'count' => $this->stats['pending_enrollments'],
                'route' => route('admin.enrollment.index'),
                'icon' => 'fa-file-signature',
                'color' => 'indigo',
            ],
            [
                'title' => 'Pending Registrations',
                'count' => $this->stats['pending_registrations'],
                'route' => route('admin.pending-registrations.index'),
                'icon' => 'fa-user-clock',
                'color' => 'amber',
            ],
        ];
    }

    public function render()
    {
        return view('components.dashboard-widgets');
    }
}
