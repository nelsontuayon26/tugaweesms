<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Reports - Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        .report-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }
        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #cbd5e1;
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <div class="flex min-h-screen">
        @include('teacher.includes.sidebar')

        <main class="lg:ml-72 w-full min-h-screen p-6">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <nav class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                        <a href="{{ route('teacher.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <span class="text-indigo-600 font-medium">Reports</span>
                    </nav>
                    <h1 class="text-2xl font-bold text-slate-900">Reports & School Forms</h1>
                    <p class="text-sm text-slate-500 mt-1">Access class records, school forms, and printable reports</p>
                </div>
            </div>

            <!-- Report Categories -->
            <div class="space-y-8">

                <!-- School Forms Section -->
                <div>
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-folder-open text-indigo-500"></i>
                        School Forms
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @php
                            $forms = [
                                ['route' => 'teacher.sf1', 'label' => 'SF1 - School Register', 'icon' => 'fa-clipboard-list', 'color' => 'bg-blue-50 text-blue-600'],
                                ['route' => 'teacher.sf2', 'label' => 'SF2 - Daily Attendance', 'icon' => 'fa-calendar-check', 'color' => 'bg-emerald-50 text-emerald-600'],
                                ['route' => 'teacher.sf3', 'label' => 'SF3 - Books', 'icon' => 'fa-book', 'color' => 'bg-amber-50 text-amber-600'],
                                ['route' => 'teacher.sf4', 'label' => 'SF4 - Monthly Attendance', 'icon' => 'fa-chart-bar', 'color' => 'bg-cyan-50 text-cyan-600'],
                                ['route' => 'teacher.sf5', 'label' => 'SF5 - Learning Progress', 'icon' => 'fa-graduation-cap', 'color' => 'bg-violet-50 text-violet-600'],
                                ['route' => 'teacher.sf6', 'label' => 'SF6 - Promotion', 'icon' => 'fa-trophy', 'color' => 'bg-rose-50 text-rose-600'],
                                ['route' => 'teacher.sf7', 'label' => 'SF7 - Personnel', 'icon' => 'fa-users', 'color' => 'bg-orange-50 text-orange-600'],
                                ['route' => 'teacher.sf8', 'label' => 'SF8 - Health', 'icon' => 'fa-heartbeat', 'color' => 'bg-pink-50 text-pink-600'],
                                ['route' => 'teacher.sf9', 'label' => 'SF9 - Report Card', 'icon' => 'fa-id-card', 'color' => 'bg-indigo-50 text-indigo-600'],
                                ['route' => 'teacher.sf10', 'label' => 'SF10 - Records', 'icon' => 'fa-archive', 'color' => 'bg-teal-50 text-teal-600'],
                            ];
                        @endphp
                        @foreach($forms as $form)
                            <a href="{{ route($form['route']) }}" class="report-card block">
                                <div class="icon-box {{ $form['color'] }}">
                                    <i class="fas {{ $form['icon'] }}"></i>
                                </div>
                                <h3 class="font-semibold text-slate-800 text-sm">{{ $form['label'] }}</h3>
                                <p class="text-xs text-slate-500 mt-1">View / Print</p>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Class Reports Section -->
                <div>
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-violet-500"></i>
                        Class Reports
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('teacher.reports.class-record') }}" class="report-card block">
                            <div class="icon-box bg-violet-50 text-violet-600">
                                <i class="fas fa-table"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Class Record</h3>
                            <p class="text-xs text-slate-500 mt-1">Per-section grade summary</p>
                        </a>

                        <a href="{{ route('teacher.attendance.monthly') }}" class="report-card block">
                            <div class="icon-box bg-sky-50 text-sky-600">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Monthly Attendance</h3>
                            <p class="text-xs text-slate-500 mt-1">Attendance summary by month</p>
                        </a>

                        <a href="{{ route('teacher.exports.sf1') }}" class="report-card block">
                            <div class="icon-box bg-emerald-50 text-emerald-600">
                                <i class="fas fa-file-export"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Export SF1</h3>
                            <p class="text-xs text-slate-500 mt-1">Download school register</p>
                        </a>

                        <a href="{{ route('teacher.exports.sf9') }}" class="report-card block">
                            <div class="icon-box bg-indigo-50 text-indigo-600">
                                <i class="fas fa-file-export"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Export SF9</h3>
                            <p class="text-xs text-slate-500 mt-1">Download report cards</p>
                        </a>
                    </div>
                </div>

                <!-- Quick Access Section -->
                <div>
                    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-bolt text-amber-500"></i>
                        Quick Access
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('teacher.sections.index') }}" class="report-card block">
                            <div class="icon-box bg-amber-50 text-amber-600">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">My Sections</h3>
                            <p class="text-xs text-slate-500 mt-1">View assigned sections</p>
                        </a>

                        <a href="{{ route('teacher.grades.index') }}" class="report-card block">
                            <div class="icon-box bg-rose-50 text-rose-600">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Grades</h3>
                            <p class="text-xs text-slate-500 mt-1">Manage student grades</p>
                        </a>

                        <a href="{{ route('teacher.attendance.index') }}" class="report-card block">
                            <div class="icon-box bg-teal-50 text-teal-600">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <h3 class="font-semibold text-slate-800 text-sm">Attendance</h3>
                            <p class="text-xs text-slate-500 mt-1">Daily attendance entry</p>
                        </a>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>
</html>
