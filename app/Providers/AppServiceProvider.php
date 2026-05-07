<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Section;
use App\Models\Teacher;
   use App\Models\User;
   use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot()
{
    // Force HTTPS for all generated URLs in production
    if ($this->app->environment('production')) {
        URL::forceScheme('https');
    }

    View::composer('admin.includes.sidebar', function ($view) {
        $sidebarSectionCount = \Illuminate\Support\Facades\Cache::remember('sidebar_section_count', 300, function () {
            return Section::count();
        });
        $view->with('sidebarSectionCount', $sidebarSectionCount);
    });

    View::composer('teacher.includes.sidebar', function ($view) {
        $user = auth()->user();

        if ($user) {
            $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
            $teacher = Teacher::with(['sections.gradeLevel'])->where('user_id', $user->id)->first();
            $sections = $teacher ? $teacher->sections->filter(function ($section) use ($activeSchoolYear) {
                return !$activeSchoolYear || $section->school_year_id == $activeSchoolYear->id;
            }) : collect([]);
        } else {
            $sections = collect([]);
        }

        $view->with('sections', $sections);
    });

    // Admin sidebar data
    View::composer('admin.includes.sidebar', function ($view) {
        $counts = \Illuminate\Support\Facades\Cache::remember('sidebar_admin_counts', 300, function () {
            return [
                'user_count' => User::count(),
                'section_count' => Section::count(),
            ];
        });

        $view->with([
            'sidebarUserCount' => $counts['user_count'],
            'sidebarSectionCount' => $counts['section_count'],
        ]);
    });


        View::composer('student.*', function ($view) {
        $student = Student::with('user')
            ->where('user_id', Auth::id())
            ->first();

        $view->with('student', $student);
    });
}
}
