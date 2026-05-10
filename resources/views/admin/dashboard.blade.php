<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Dark mode scrollbar */
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #475569; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        .dashboard-container { display: flex; min-height: 100vh; }
        
        .sidebar {
            width: 280px;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: white;
            border-right: 1px solid #e2e8f0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .main-header {
            min-height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            flex-shrink: 0;
            z-index: 40;
        }
        
        .main-content {
            flex: 1;
            padding: 1.5rem;
            background: #f8fafc;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1023px) {
            .sidebar { 
                transform: translateX(-100%);
            }
            .sidebar.mobile-open { 
                transform: translateX(0);
            }
            .main-wrapper { 
                margin-left: 0; 
            }
            .main-header {
                padding: 0.75rem 1rem;
                min-height: 64px;
            }
            .main-content {
                padding: 1rem;
            }
        }
        
        /* Stats Cards */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }
        .stat-card:hover::before {
            transform: scaleX(1);
        }
        
        .nav-item {
            position: relative;
            transition: all 0.2s ease;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleY(0);
            width: 3px;
            height: 60%;
            background: linear-gradient(180deg, #3b82f6, #8b5cf6);
            border-radius: 0 4px 4px 0;
            transition: transform 0.2s ease;
        }
        .nav-item:hover::before,
        .nav-item.active::before {
            transform: translateY(-50%) scaleY(1);
        }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.1) 0%, transparent 100%);
        }
        
        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        
        .chart-container {
            position: relative;
            height: 280px;
            width: 100%;
        }
        @media (min-width: 768px) {
            .chart-container { height: 320px; }
        }
        
        .fab {
            box-shadow: 0 10px 40px -10px rgba(37, 99, 235, 0.5);
            transition: all 0.3s ease;
        }
        .fab:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 50px -10px rgba(37, 99, 235, 0.6);
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transition: width 1s ease-out;
        }
        
        .custom-checkbox {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .custom-checkbox:checked {
            background: #3b82f6;
            border-color: #3b82f6;
        }
        
        .status-badge {
            @apply px-2 py-1 rounded-full text-xs font-semibold;
        }
        .status-active { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .status-inactive { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        .status-pending { background: #fffbeb; color: #b45309; border: 1px solid #fcd34d; }
        
        #dashboard-notif-list::-webkit-scrollbar { width: 6px; }
        #dashboard-notif-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
        }
        
        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }
        
        .school-year-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        
        /* Table Responsive */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    @php
        // Get active school year once at the top
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        $activeSchoolYearId = $activeSchoolYear ? $activeSchoolYear->id : null;
        $activeSchoolYearName = $activeSchoolYear ? $activeSchoolYear->name : 'No Active School Year';
        
        // Find the most recent Monday that has attendance data (for subtitle)
        $today = now();
        $monday = $today->isWeekend() 
            ? $today->copy()->previous(\Carbon\Carbon::MONDAY)
            : $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        
        $weeksToCheck = 12;
        $foundMonday = null;
        
        for ($w = 0; $w < $weeksToCheck; $w++) {
            $checkMonday = $monday->copy()->subWeeks($w);
            $weekDates = collect(range(0, 4))->map(function($i) use ($checkMonday) {
                return $checkMonday->copy()->addDays($i)->format('Y-m-d');
            });
            
            $hasData = \App\Models\Attendance::whereIn('date', $weekDates->toArray())
                ->when($activeSchoolYearId, function($q) use ($activeSchoolYearId) {
                    return $q->whereHas('student', function($eq) use ($activeSchoolYearId) {
                        $eq->where('school_year_id', $activeSchoolYearId);
                    });
                })
                ->exists();
            
            if ($hasData) {
                $foundMonday = $checkMonday;
                break;
            }
        }
        
        if (!$foundMonday) {
            $foundMonday = $monday;
        }
        
        $weekRangeLabel = $foundMonday->format('M d') . ' – ' . $foundMonday->copy()->addDays(4)->format('M d, Y');
    @endphp

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         style="display: none;"></div>

    <div class="dashboard-container">
        <!-- Sidebar -->
        @include('admin.includes.sidebar')

        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Header -->
            <header class="main-header">
                <div class="flex items-center justify-between w-full gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2.5 hover:bg-slate-100 rounded-xl transition-colors flex-shrink-0">
                            <i class="fas fa-bars text-slate-600"></i>
                        </button>
                        <div class="min-w-0">
                            <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-slate-900 tracking-tight truncate">Dashboard Overview</h2>
                            <div class="flex flex-wrap items-center gap-2 mt-0.5">
                                <p class="text-xs sm:text-sm text-slate-500 font-medium flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse flex-shrink-0"></span>
                                    <span class="truncate">{{ now()->format('l, F j, Y') }}</span>
                                </p>
                                @if($activeSchoolYear)
                                    <span class="school-year-badge flex-shrink-0">
                                        <i class="fas fa-graduation-cap text-[10px]"></i>
                                        <span class="truncate max-w-[100px] sm:max-w-none">{{ $activeSchoolYearName }}</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 sm:gap-4 flex-shrink-0">
                        <div class="hidden md:flex items-center bg-white border border-slate-200 rounded-2xl px-3 py-2 shadow-sm focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all relative" id="admin-search-wrapper">
                            <i class="fas fa-search text-slate-400 mr-2 text-sm"></i>
                            <input type="text" id="admin-search-input" placeholder="Search students, teachers, sections..." autocomplete="off" class="bg-transparent border-none outline-none text-sm w-32 lg:w-56 placeholder:text-slate-400">
                            <kbd class="hidden lg:inline-block px-2 py-0.5 text-[10px] font-semibold text-slate-400 bg-slate-100 rounded border border-slate-200">⌘K</kbd>

                            <!-- Search Results Dropdown -->
                            <div id="admin-search-dropdown" class="hidden fixed bg-white rounded-xl shadow-[0_25px_50px_-12px_rgba(0,0,0,0.25)] border border-slate-200 z-[9999] overflow-hidden" style="min-width: 320px;">
                                <div class="flex items-center justify-between px-4 py-2 border-b border-slate-100 bg-slate-50">
                                    <h3 class="font-semibold text-xs text-slate-500 uppercase tracking-wider">Results</h3>
                                    <span id="admin-search-query" class="text-xs text-slate-400"></span>
                                </div>
                                <div id="admin-search-list" class="max-h-[60vh] overflow-y-auto" style="scrollbar-width: thin;">
                                    <div class="p-6 text-center text-sm text-slate-500">
                                        <i class="fas fa-search text-slate-300 text-2xl mb-2 block"></i>
                                        Type at least 2 characters to search...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative" id="dashboard-notif-wrapper">
                            <button id="dashboard-notif-btn" class="relative p-2 sm:p-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                                <i class="fas fa-bell text-lg"></i>
                                <span id="dashboard-notif-badge" class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-rose-500 rounded-full border-2 border-white flex items-center justify-center">0</span>
                            </button>
                            <div id="dashboard-notif-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 z-50 overflow-hidden">
                                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                                    <h3 class="font-semibold text-sm text-slate-800">Notifications</h3>
                                    <button id="dashboard-mark-all" class="hidden text-xs text-indigo-600 hover:text-indigo-800 font-medium">Mark all read</button>
                                </div>
                                <div id="dashboard-notif-list" class="max-h-80 overflow-y-auto" style="scrollbar-width: thin;">
                                    <div class="py-3 px-4">
                                        <x-skeleton-loader type="notification" count="3" />
                                    </div>
                                </div>
                                <a href="{{ route('notifications.index') }}" class="block text-center px-4 py-2 text-xs font-medium text-indigo-600 bg-slate-50 hover:bg-slate-100 border-t border-slate-100">View all notifications</a>
                            </div>
                        </div>

                        <button onclick="openQuickActions()" class="fab bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold transition-all flex items-center gap-2 flex-shrink-0">
                            <i class="fas fa-plus text-sm"></i>
                            <span class="hidden sm:inline">Quick Add</span>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="main-content">
                
                @if(!$activeSchoolYear)
                    <div class="mb-4 sm:mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-3 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-3 animate-fade-in-up">
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 flex-shrink-0">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-amber-800 text-sm">No Active School Year</p>
                            <p class="text-xs text-amber-600">Please set an active school year in settings to see accurate data.</p>
                        </div>
                        <a href="{{ route('admin.school-years.index') }}" class="w-full sm:w-auto px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg font-medium text-sm transition-colors text-center flex-shrink-0">
                            Manage School Years
                        </a>
                    </div>
                @endif

                <!-- Welcome Banner -->
                <div class="mb-6 sm:mb-8 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden animate-fade-in-up">
                    <div class="absolute top-0 right-0 w-32 h-32 sm:w-64 sm:h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 sm:w-48 sm:h-48 bg-purple-500/20 rounded-full translate-y-1/2 -translate-x-1/2 blur-2xl"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="min-w-0">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 sm:mb-2">Welcome back, {{ auth()->user()->first_name ?? auth()->user()->name ?? 'Admin' }}! 👋</h1>
                            <p class="text-blue-100 text-sm sm:text-base lg:text-lg">Here's what's happening at Tugawe Elementary today.</p>
                            @if($activeSchoolYear)
                                <p class="text-blue-200 text-xs sm:text-sm mt-2">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    Active School Year: <span class="font-semibold text-white">{{ $activeSchoolYearName }}</span>
                                </p>
                            @endif
                        </div>
                        <div class="hidden sm:block text-right flex-shrink-0">
                            <p class="text-2xl sm:text-3xl lg:text-4xl font-bold" id="liveClock">09:32 AM</p>
                            <p class="text-blue-200 text-xs sm:text-sm">Philippine Standard Time</p>
                        </div>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8" style="display: none;">
                    <!-- Stats cards hidden -->
                </div>

                <!-- Charts & Analytics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <!-- Attendance Chart -->
                    <div class="lg:col-span-2 glass-card rounded-2xl p-4 sm:p-6 shadow-sm animate-fade-in-up" style="animation-delay: 0.5s;">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-6">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Attendance Trends</h3>
                                <p class="text-xs sm:text-sm text-slate-500">{{ $weekRangeLabel }} • {{ $activeSchoolYearName }}</p>
                            </div>
                            <button class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-all self-start sm:self-auto">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Grade Distribution & Events -->
                    <div class="space-y-4 sm:space-y-6 animate-fade-in-up" style="animation-delay: 0.6s;">
                        <div class="glass-card rounded-2xl p-4 sm:p-6 shadow-sm">
                            <h3 class="text-base sm:text-lg font-bold text-slate-900 mb-4">Grade Distribution</h3>
                            <p class="text-xs text-slate-500 mb-4">{{ $activeSchoolYearName }}</p>
                            <div class="space-y-3">
                                @php
                                    $gradeDistribution = $activeSchoolYearId
                                        ? \App\Models\Enrollment::where('school_year_id', $activeSchoolYearId)
                                            ->whereIn('status', ['approved', 'enrolled', 'completed'])
                                            ->join('grade_levels', 'enrollments.grade_level_id', '=', 'grade_levels.id')
                                            ->selectRaw('grade_levels.name as grade, count(*) as count')
                                            ->groupBy('grade_levels.name')
                                            ->orderBy('grade_levels.name')
                                            ->pluck('count', 'grade')
                                            ->toArray()
                                        : \App\Models\Student::where('status', 'active')
                                            ->join('grade_levels', 'students.grade_level_id', '=', 'grade_levels.id')
                                            ->selectRaw('grade_levels.name as grade, count(*) as count')
                                            ->groupBy('grade_levels.name')
                                            ->orderBy('grade_levels.name')
                                            ->pluck('count', 'grade')
                                            ->toArray();

                                    $maxCount = !empty($gradeDistribution) ? max($gradeDistribution) : 1;
                                @endphp
                                @forelse($gradeDistribution as $grade => $count)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs sm:text-sm shadow-md flex-shrink-0">
                                            {{ $grade }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center mb-1">
                                                <span class="font-semibold text-slate-700 text-sm truncate">{{ $grade }}</span>
                                                <span class="font-bold text-slate-900 text-sm">{{ $count }}</span>
                                            </div>
                                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full progress-bar" style="width: {{ ($count / $maxCount) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-sm text-center py-4">No grade distribution data</p>
                                @endforelse
                            </div>
                        </div>

                        <!-- Upcoming Events -->
                        <div class="glass-card rounded-2xl p-4 sm:p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-base sm:text-lg font-bold text-slate-900">Upcoming Events</h3>
                                <a href="{{ route('admin.events.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All</a>
                            </div>
                            <div class="space-y-3">
                                @php
                                    // Use controller-passed upcomingEvents if available, otherwise fall back to inline query
                                    if (!isset($upcomingEvents)) {
                                        $upcomingEvents = \App\Models\Event::where('date', '>=', today())
                                            ->orderBy('date')
                                            ->limit(3)
                                            ->get();
                                    }
                                @endphp
                                @forelse($upcomingEvents as $event)
                                    <div class="flex items-start gap-3 p-2 sm:p-3 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 text-blue-600 flex flex-col items-center justify-center font-bold text-xs border border-blue-200 group-hover:shadow-md transition-all flex-shrink-0">
                                            <span class="text-[10px] uppercase">{{ $event->date->format('M') }}</span>
                                            <span class="text-base sm:text-lg">{{ $event->date->format('d') }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">{{ $event->title }}</p>
                                            <p class="text-slate-500 text-xs mt-0.5">{{ $event->date->format('l, F j, Y') }}</p>
                                        </div>
                                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-2 flex-shrink-0"></div>
                                    </div>
                                @empty
                                    <p class="text-slate-400 text-sm text-center py-4">No upcoming events</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Students Table -->
                <div class="glass-card rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden animate-fade-in-up" style="animation-delay: 0.7s;">
                    <div class="p-4 sm:p-6 border-b border-slate-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-r from-slate-50 to-white">
                        <div>
                            <h3 class="text-base sm:text-lg font-bold text-slate-900">Recent Enrollments</h3>
                            <p class="text-xs sm:text-sm text-slate-500">{{ $activeSchoolYearName }} • Latest student registrations</p>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <button class="px-3 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                                <i class="fas fa-filter mr-1.5"></i><span class="hidden sm:inline">Filter</span>
                            </button>
                            <a href="{{ route('admin.students.index') }}" class="bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-2 rounded-xl font-semibold text-sm transition-all flex items-center gap-1.5">
                                <span class="hidden sm:inline">View All</span> <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="w-full text-left min-w-[640px]">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <input type="checkbox" class="custom-checkbox">
                                            <span>Student</span>
                                        </div>
                                    </th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade & Section</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">LRN</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Enrolled</th>
                                    <th class="px-4 sm:px-6 py-3 sm:py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @php
                                    $recentStudents = $activeSchoolYearId
                                        ? \App\Models\Student::with('section')
                                            ->whereHas('enrollments', function($eq) use ($activeSchoolYearId) {
                                                $eq->where('school_year_id', $activeSchoolYearId)->whereIn('status', ['approved', 'enrolled', 'completed']);
                                            })
                                            ->latest()
                                            ->limit(5)
                                            ->get()
                                        : \App\Models\Student::with('section')->where('status', 'active')->latest()->limit(5)->get();
                                    
                                    $totalStudentsCount = $activeSchoolYearId
                                        ? \App\Models\Enrollment::where('school_year_id', $activeSchoolYearId)->whereIn('status', ['approved', 'enrolled', 'completed'])->count()
                                        : \App\Models\Student::where('status', 'active')->count();
                                @endphp
                                @forelse($recentStudents as $student)
                                    <tr class="group hover:bg-slate-50 transition-colors">
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" class="custom-checkbox flex-shrink-0">
                                                <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=random&color=fff' }}" 
                                                     alt="" 
                                                     class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-sm flex-shrink-0">
                                                <div class="min-w-0">
                                                    <p class="font-bold text-slate-900 text-sm group-hover:text-blue-600 transition-colors truncate">{{ $student->full_name }}</p>
                                                    <p class="text-xs text-slate-500">{{ $student->gender }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-semibold text-xs sm:text-sm">
                                                {{ $student->grade_level }}-{{ $student->section->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span class="font-mono text-xs sm:text-sm text-slate-600 bg-slate-50 px-2 py-1 rounded border border-slate-200">{{ $student->lrn ?? 'N/A' }}</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                                            <span class="status-badge status-{{ $student->status }} capitalize">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5 inline-block"></span>
                                                {{ $student->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-600 text-xs sm:text-sm font-medium">
                                            {{ $student->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-3 sm:py-4 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <a href="{{ route('admin.students.show', $student) }}" class="p-1.5 sm:p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View">
                                                    <i class="fas fa-eye text-sm"></i>
                                                </a>
                                                <a href="{{ route('admin.students.edit', $student) }}" class="p-1.5 sm:p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                                <i class="fas fa-inbox text-2xl text-slate-400"></i>
                                            </div>
                                            <p class="text-slate-500 font-medium">No students found</p>
                                            <p class="text-slate-400 text-sm mt-1">No recent enrollments for {{ $activeSchoolYearName }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($recentStudents->count() > 0)
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3">
                            <p class="text-xs sm:text-sm text-slate-500">Showing <span class="font-semibold text-slate-900">1-{{ $recentStudents->count() }}</span> of <span class="font-semibold text-slate-900">{{ $totalStudentsCount }}</span> students</p>
                            <div class="flex items-center gap-2">
                                <button class="px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-lg transition-all disabled:opacity-50" disabled>
                                    Previous
                                </button>
                                <a href="{{ route('admin.students.index') }}" class="px-3 py-1.5 text-xs sm:text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-lg transition-all">
                                    Next
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </main>
        </div>
    </div>

    <!-- Quick Actions Modal -->
    <div id="quickActionsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/30 backdrop-blur-sm" onclick="closeQuickActions()"></div>
        <div class="absolute top-16 sm:top-24 right-4 sm:right-6 bg-white rounded-2xl shadow-2xl p-2 w-72 sm:w-80 animate-fade-in-up border border-slate-200">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-900">Quick Actions</h3>
                <p class="text-sm text-slate-500">What would you like to do?</p>
            </div>
            <div class="p-2 space-y-1">
                <a href="{{ route('admin.students.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 text-sm">Add Student</p>
                        <p class="text-xs text-slate-500">Enroll a new student</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 ml-auto text-xs group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                </a>
                <a href="{{ route('admin.teachers.create') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 text-sm">Add Teacher</p>
                        <p class="text-xs text-slate-500">Register new faculty</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 ml-auto text-xs group-hover:text-purple-500 group-hover:translate-x-1 transition-all"></i>
                </a>
                <a href="{{ route('admin.sections.index') }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition-all group">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform">
                        <i class="fas fa-th-large text-sm"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900 text-sm">Sections</p>
                        <p class="text-xs text-slate-500">Manage class sections</p>
                    </div>
                    <i class="fas fa-chevron-right text-slate-300 ml-auto text-xs group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"></i>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Quick Actions Modal
        function openQuickActions() {
            document.getElementById('quickActionsModal').classList.remove('hidden');
        }

        function closeQuickActions() {
            document.getElementById('quickActionsModal').classList.add('hidden');
        }

        // Live Clock
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
            const clockElement = document.getElementById('liveClock');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Attendance Chart
        @php
            $today = now();
            
            // Find the most recent Monday that has attendance data
            // Go back up to 12 weeks to find data
            $monday = $today->isWeekend() 
                ? $today->copy()->previous(\Carbon\Carbon::MONDAY)
                : $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
            
            $weeksToCheck = 12;
            $foundMonday = null;
            
            for ($w = 0; $w < $weeksToCheck; $w++) {
                $checkMonday = $monday->copy()->subWeeks($w);
                $weekDates = collect(range(0, 4))->map(function($i) use ($checkMonday) {
                    return $checkMonday->copy()->addDays($i)->format('Y-m-d');
                });
                
                $hasData = \App\Models\Attendance::whereIn('date', $weekDates->toArray())
                    ->when($activeSchoolYearId, function($q) use ($activeSchoolYearId) {
                        return $q->whereHas('student', function($eq) use ($activeSchoolYearId) {
                            $eq->where('school_year_id', $activeSchoolYearId);
                        });
                    })
                    ->exists();
                
                if ($hasData) {
                    $foundMonday = $checkMonday;
                    break;
                }
            }
            
            // Fallback to current week if no data found anywhere
            if (!$foundMonday) {
                $foundMonday = $monday;
            }
            
            $lastWeekdays = collect(range(0, 4))->map(function($i) use ($foundMonday) {
                return $foundMonday->copy()->addDays($i)->format('Y-m-d');
            })->values();
            


            $attendanceLabels = $lastWeekdays->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('l'); // Full day name: Monday, Tuesday, etc.
            });

            $presentData = $lastWeekdays->map(function($date) use ($activeSchoolYearId) {
                return \App\Models\Attendance::whereDate('date', $date)
                    ->where('status', 'present')
                    ->when($activeSchoolYearId, function($q) use ($activeSchoolYearId) {
                        return $q->whereHas('student', function($eq) use ($activeSchoolYearId) {
                            $eq->where('school_year_id', $activeSchoolYearId);
                        });
                    })->count();
            });

            $absentData = $lastWeekdays->map(function($date) use ($activeSchoolYearId) {
                return \App\Models\Attendance::whereDate('date', $date)
                    ->where('status', 'absent')
                    ->when($activeSchoolYearId, function($q) use ($activeSchoolYearId) {
                        return $q->whereHas('student', function($eq) use ($activeSchoolYearId) {
                            $eq->where('school_year_id', $activeSchoolYearId);
                        });
                    })->count();
            });
        @endphp

        const ctx = document.getElementById('attendanceChart').getContext('2d');
        
        const gradientPresent = ctx.createLinearGradient(0, 0, 0, 400);
        gradientPresent.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradientPresent.addColorStop(1, 'rgba(59, 130, 246, 0.0)');
        
        const gradientAbsent = ctx.createLinearGradient(0, 0, 0, 400);
        gradientAbsent.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
        gradientAbsent.addColorStop(1, 'rgba(239, 68, 68, 0.0)');

        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';
        const tickColor = isDark ? '#94a3b8' : '#64748b';
        const pointBorder = isDark ? '#1e293b' : '#fff';

        const attendanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($attendanceLabels),
                datasets: [{
                    label: 'Present',
                    data: @json($presentData),
                    borderColor: '#3b82f6',
                    backgroundColor: gradientPresent,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: pointBorder,
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }, {
                    label: 'Absent',
                    data: @json($absentData),
                    borderColor: '#ef4444',
                    backgroundColor: gradientAbsent,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: pointBorder,
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 12, family: 'Plus Jakarta Sans' },
                            color: tickColor
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: { font: { family: 'Plus Jakarta Sans' }, color: tickColor }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans' }, color: tickColor }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Update chart colors when theme changes
        window.addEventListener('themechange', function(e) {
            const dark = e.detail.theme === 'dark';
            const newGrid = dark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.05)';
            const newTick = dark ? '#94a3b8' : '#64748b';
            const newPointBorder = dark ? '#1e293b' : '#fff';

            attendanceChart.options.scales.y.grid.color = newGrid;
            attendanceChart.options.scales.y.ticks.color = newTick;
            attendanceChart.options.scales.x.ticks.color = newTick;
            attendanceChart.options.plugins.legend.labels.color = newTick;

            attendanceChart.data.datasets.forEach(function(ds) {
                ds.pointBorderColor = newPointBorder;
            });

            attendanceChart.update('none');
        });
    </script>

    <!-- Notification Dropdown Script -->
    <script>
    (function(){
        const btn = document.getElementById('dashboard-notif-btn');
        const dropdown = document.getElementById('dashboard-notif-dropdown');
        const badge = document.getElementById('dashboard-notif-badge');
        const list = document.getElementById('dashboard-notif-list');
        const markAll = document.getElementById('dashboard-mark-all');
        const csrf = document.querySelector('meta[name=csrf-token]')?.content || '';
        let unreadCount = 0;

        function timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            if (seconds < 60) return 'Just now';
            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return minutes + 'm ago';
            const hours = Math.floor(minutes / 60);
            if (hours < 24) return hours + 'h ago';
            const days = Math.floor(hours / 24);
            if (days < 7) return days + 'd ago';
            return date.toLocaleDateString();
        }

        function updateBadge(count) {
            unreadCount = count;
            if (count > 0) {
                badge.classList.remove('hidden');
                badge.textContent = count > 9 ? '9+' : count;
                markAll.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
                markAll.classList.add('hidden');
            }
        }

        function renderNotifications(notifications) {
            if (!notifications || notifications.length === 0) {
                list.innerHTML = '<div class="p-6 text-center text-sm text-slate-500"><i class="fas fa-bell-slash text-slate-300 text-2xl mb-2 block"></i>No notifications yet</div>';
                return;
            }
            list.innerHTML = notifications.map(n => {
                const unread = !n.read_at;
                return `<div class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 cursor-pointer transition-colors ${unread ? 'bg-indigo-50/30' : 'opacity-70'}" data-id="${n.id}">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0 ${unread ? 'bg-indigo-500' : 'bg-slate-300'}"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800 leading-tight">${escapeHtml(n.title)}</p>
                            <p class="text-xs text-slate-600 mt-0.5 line-clamp-2">${escapeHtml(n.body)}</p>
                            <p class="text-[10px] text-slate-400 mt-1">${timeAgo(n.created_at)}</p>
                        </div>
                    </div>
                </div>`;
            }).join('');

            list.querySelectorAll('[data-id]').forEach(el => {
                el.addEventListener('click', function(){
                    const id = this.dataset.id;
                    const n = notifications.find(item => String(item.id) === id);
                    if (n && !n.read_at) {
                        fetch(`{{ url('notifications') }}/${id}/read`, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf }
                        }).then(() => {
                            n.read_at = new Date().toISOString();
                            updateBadge(Math.max(0, unreadCount - 1));
                            renderNotifications(notifications);
                        }).catch(() => {});
                    }
                    if (n && n.data && n.data.url) {
                        window.location.href = n.data.url;
                    }
                });
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function fetchUnreadCount() {
            fetch('{{ route('notifications.unread-count') }}')
                .then(r => r.json())
                .then(data => updateBadge(data.count || 0))
                .catch(() => {});
        }

        function fetchRecent() {
            list.innerHTML = '<div class="py-3 px-4"><div class="flex items-start gap-3 py-3 px-4 animate-pulse"><div class="w-2 h-2 mt-2 rounded-full bg-slate-200 flex-shrink-0"></div><div class="flex-1 min-w-0 space-y-2"><div class="h-3.5 w-3/4 bg-slate-200 rounded"></div><div class="h-2.5 w-full bg-slate-200 rounded"></div><div class="h-2 w-16 bg-slate-200 rounded"></div></div></div><div class="flex items-start gap-3 py-3 px-4 animate-pulse"><div class="w-2 h-2 mt-2 rounded-full bg-slate-200 flex-shrink-0"></div><div class="flex-1 min-w-0 space-y-2"><div class="h-3.5 w-2/3 bg-slate-200 rounded"></div><div class="h-2.5 w-5/6 bg-slate-200 rounded"></div><div class="h-2 w-14 bg-slate-200 rounded"></div></div></div></div>';
            fetch('{{ route('notifications.recent') }}')
                .then(r => r.json())
                .then(data => {
                    updateBadge(data.unread_count || 0);
                    renderNotifications(data.notifications || []);
                })
                .catch(() => {
                    list.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Failed to load</div>';
                });
        }

        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden');
            if (isHidden) fetchRecent();
        });

        document.addEventListener('click', function(e) {
            if (!dropdown.classList.contains('hidden') && !dropdown.contains(e.target) && e.target !== btn) {
                dropdown.classList.add('hidden');
            }
        });

        markAll.addEventListener('click', function(e) {
            e.stopPropagation();
            fetch('{{ route('notifications.mark-all-read') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf }
            })
            .then(r => r.json())
            .then(() => {
                updateBadge(0);
                fetchRecent();
            })
            .catch(() => {});
        });

        fetchUnreadCount();
        setInterval(fetchUnreadCount, 30000);
    })();
    </script>

    <!-- Global Search Script -->
    <script>
    (function(){
        const wrapper = document.getElementById('admin-search-wrapper');
        const input = document.getElementById('admin-search-input');
        const dropdown = document.getElementById('admin-search-dropdown');
        const list = document.getElementById('admin-search-list');
        const queryLabel = document.getElementById('admin-search-query');
        let searchTimeout = null;
        let selectedIndex = -1;
        let currentResults = [];

        const typeConfig = {
            student: { label: 'Students', icon: 'fa-user-graduate', color: 'text-blue-600', bg: 'bg-blue-50' },
            teacher: { label: 'Teachers', icon: 'fa-chalkboard-teacher', color: 'text-purple-600', bg: 'bg-purple-50' },
            section: { label: 'Sections', icon: 'fa-th-large', color: 'text-emerald-600', bg: 'bg-emerald-50' },
            announcement: { label: 'Announcements', icon: 'fa-bullhorn', color: 'text-amber-600', bg: 'bg-amber-50' },
        };

        function positionDropdown() {
            const rect = wrapper.getBoundingClientRect();
            const minWidth = 420;
            const maxWidth = Math.min(520, window.innerWidth - 32);
            let width = Math.max(rect.width, minWidth);
            width = Math.min(width, maxWidth);
            let left = rect.left;

            // If it would overflow the right edge, align to the right of the wrapper
            if (left + width > window.innerWidth - 16) {
                left = rect.right - width;
            }
            // Ensure it doesn't go off the left edge
            if (left < 16) {
                left = 16;
            }

            dropdown.style.top = (rect.bottom + 6) + 'px';
            dropdown.style.left = left + 'px';
            dropdown.style.width = width + 'px';
            dropdown.style.maxWidth = maxWidth + 'px';
        }

        function showDropdown() {
            dropdown.classList.remove('hidden');
            positionDropdown();
        }

        function hideDropdown() {
            dropdown.classList.add('hidden');
            selectedIndex = -1;
        }

        function renderLoading() {
            list.innerHTML = '<div class="p-4"><div class="flex items-center gap-3 py-2 animate-pulse"><div class="w-8 h-8 rounded-lg bg-slate-200"></div><div class="flex-1 space-y-2"><div class="h-3 w-3/4 bg-slate-200 rounded"></div><div class="h-2 w-1/2 bg-slate-200 rounded"></div></div></div></div>';
        }

        function renderEmpty(query) {
            list.innerHTML = `<div class="p-6 text-center text-sm text-slate-500"><i class="fas fa-search text-slate-300 text-2xl mb-2 block"></i>No results found for "<span class="font-semibold text-slate-700">${escapeHtml(query)}</span>"</div>`;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function renderResults(data) {
            currentResults = [];
            const categories = ['students', 'teachers', 'sections', 'announcements'];
            let hasAny = false;
            let html = '';

            categories.forEach(cat => {
                const items = data[cat] || [];
                if (items.length === 0) return;
                hasAny = true;
                const cfg = typeConfig[items[0].type];

                html += `<div class="px-3 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 border-b border-slate-100">${cfg.label}</div>`;

                items.forEach((item, idx) => {
                    const globalIdx = currentResults.length;
                    currentResults.push(item);
                    html += `
                        <a href="${item.url}" data-index="${globalIdx}" class="global-search-result flex items-center gap-3 px-4 py-2.5 hover:bg-slate-50 border-b border-slate-50 transition-colors cursor-pointer ${globalIdx === 0 ? 'bg-slate-50/50' : ''}">
                            <div class="w-9 h-9 rounded-lg ${cfg.bg} ${cfg.color} flex items-center justify-center flex-shrink-0">
                                <i class="fas ${item.icon} text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <p class="text-sm font-semibold text-slate-800 break-words leading-tight">${escapeHtml(item.title)}</p>
                                <p class="text-xs text-slate-500 break-words mt-0.5">${escapeHtml(item.subtitle)}${item.meta ? '<span class="text-slate-400"> · ' + escapeHtml(item.meta) + '</span>' : ''}</p>
                            </div>
                            <i class="fas fa-chevron-right text-slate-300 text-xs"></i>
                        </a>
                    `;
                });
            });

            if (!hasAny) {
                renderEmpty(data.query || '');
                return;
            }

            list.innerHTML = html;
            selectedIndex = 0;
            highlightSelected();
            positionDropdown();
        }

        function highlightSelected() {
            const items = list.querySelectorAll('.global-search-result');
            items.forEach((el, i) => {
                if (i === selectedIndex) {
                    el.classList.add('bg-slate-100');
                } else {
                    el.classList.remove('bg-slate-100');
                }
            });
        }

        function performSearch(query) {
            if (!query || query.length < 2) {
                list.innerHTML = '<div class="p-6 text-center text-sm text-slate-500"><i class="fas fa-search text-slate-300 text-2xl mb-2 block"></i>Type at least 2 characters to search...</div>';
                queryLabel.textContent = '';
                return;
            }

            renderLoading();
            queryLabel.textContent = 'Searching...';

            fetch(`{{ route('admin.search') }}?q=` + encodeURIComponent(query))
                .then(r => r.json())
                .then(data => {
                    queryLabel.textContent = `"${escapeHtml(query)}"`;
                    renderResults(data);
                })
                .catch(() => {
                    list.innerHTML = '<div class="p-4 text-center text-sm text-red-500">Search failed. Please try again.</div>';
                    queryLabel.textContent = '';
                });
        }

        input.addEventListener('focus', function() {
            showDropdown();
            if (input.value.trim().length >= 2) {
                performSearch(input.value.trim());
            }
        });

        input.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => performSearch(query), 250);
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (selectedIndex < currentResults.length - 1) {
                    selectedIndex++;
                    highlightSelected();
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (selectedIndex > 0) {
                    selectedIndex--;
                    highlightSelected();
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && currentResults[selectedIndex]) {
                    window.location.href = currentResults[selectedIndex].url;
                }
            } else if (e.key === 'Escape') {
                hideDropdown();
                input.blur();
            }
        });

        // Keyboard shortcut: Cmd/Ctrl + K
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                input.focus();
                showDropdown();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!wrapper.contains(e.target) && !dropdown.contains(e.target)) {
                hideDropdown();
            }
        });

        // Hide dropdown on scroll or resize since it's fixed positioned
        window.addEventListener('scroll', hideDropdown, { passive: true });
        window.addEventListener('resize', hideDropdown, { passive: true });
    })();
    </script>
</body>
</html>
