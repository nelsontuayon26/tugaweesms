<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
        }
        
        [x-cloak] { display: none !important; }
        
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        
        .calendar-day {
            min-height: 100px;
            transition: all 0.2s ease;
        }
        .calendar-day:hover {
            background: #f1f5f9;
        }
        
        .status-present { 
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border-color: #86efac;
        }
        .status-absent { 
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            border-color: #fca5a5;
        }
        .status-late { 
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border-color: #fcd34d;
        }
        .status-excused { 
            background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
            border-color: #a5b4fc;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .attendance-row {
            transition: all 0.2s ease;
        }
        .attendance-row:hover {
            background: #f8fafc;
        }
        
        .calendar-nav-btn {
            transition: all 0.2s ease;
        }
        .calendar-nav-btn:hover {
            transform: scale(1.05);
        }
        
        @media print {
            .no-print { display: none !important; }
            aside, nav, .sidebar, .mobile-toggle { display: none !important; }
            main { margin-left: 0 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          viewMode: 'calendar',
          selectedDate: null,
          selectedAttendance: null,
          init() {
              this.handleResize();
              window.addEventListener('resize', () => this.handleResize());
              
              // Listen for sidebar state changes from the sidebar component
              window.addEventListener('sidebar-state-change', (e) => {
                  this.sidebarCollapsed = e.detail.collapsed;
                  this.mobileOpen = e.detail.mobile;
              });
          },
          handleResize() {
              if (window.innerWidth < 1024) {
                  this.sidebarCollapsed = false;
              }
          },
          async showAttendanceDetails(date) {
              this.selectedDate = date;
              try {
                  const response = await fetch(`/student/attendance/daily?date=${date}`);
                  this.selectedAttendance = await response.json();
              } catch (error) {
                  console.error('Error fetching attendance:', error);
              }
          },
          toggleMobileSidebar() {
              this.mobileOpen = !this.mobileOpen;
              // Dispatch to sidebar
              window.dispatchEvent(new CustomEvent('sidebar-state-change', {
                  detail: { collapsed: this.sidebarCollapsed, mobile: this.mobileOpen }
              }));
          }
      }"
      x-init="init()">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="toggleMobileSidebar()"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
         style="display: none;">
    </div>

    <!-- Mobile Toggle Button -->
    <button @click="toggleMobileSidebar()" 
            class="mobile-toggle fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-105 hover:shadow-xl transition-all duration-200 border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <!-- Sidebar -->
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content - Aligned with Sidebar -->
    <main class="min-h-screen transition-all duration-300 ease-out p-4 lg:p-8 lg:ml-72">

        <!-- Page Header -->
        <div class="mb-8 lg:pl-0 pl-14">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                            <i class="fas fa-calendar-check text-xl"></i>
                        </div>
                        <div>
                            My Attendance
                            <p class="text-sm font-normal text-slate-500 mt-1">Track your daily attendance records</p>
                        </div>
                    </h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <!-- View Toggle -->
                    <div class="bg-white rounded-xl p-1 shadow-sm border border-slate-200 flex">
                        <button @click="viewMode = 'calendar'" 
                                :class="viewMode === 'calendar' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-600 hover:bg-slate-50'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            <span class="hidden sm:inline">Calendar</span>
                        </button>
                        <button @click="viewMode = 'list'" 
                                :class="viewMode === 'list' ? 'bg-indigo-100 text-indigo-700' : 'text-slate-600 hover:bg-slate-50'"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition-all flex items-center gap-2">
                            <i class="fas fa-list"></i>
                            <span class="hidden sm:inline">List</span>
                        </button>
                    </div>
                   
                </div>
            </div>
        </div>

        <?php
            $user = $student->user;
            $daysInMonth = $selectedDate->daysInMonth;
            $firstDayOfMonth = $selectedDate->copy()->firstOfMonth()->dayOfWeek;
            $today = \Carbon\Carbon::now();
        ?>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card border-l-4 border-emerald-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Present Days</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['present']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-xs text-emerald-600 font-medium">
                    <?php echo e($stats['total_school_days'] > 0 ? round(($stats['present'] / $stats['total_school_days']) * 100, 1) : 0); ?>% of school days
                </div>
            </div>

            <div class="stat-card border-l-4 border-rose-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Absent Days</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['absent']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center">
                        <i class="fas fa-times-circle text-rose-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-xs text-rose-600 font-medium">
                    <?php echo e($stats['total_school_days'] > 0 ? round(($stats['absent'] / $stats['total_school_days']) * 100, 1) : 0); ?>% of school days
                </div>
            </div>

            <div class="stat-card border-l-4 border-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Late Arrivals</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($stats['late']); ?></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-clock text-amber-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-xs text-amber-600 font-medium">
                    <?php echo e($stats['total_school_days'] > 0 ? round(($stats['late'] / $stats['total_school_days']) * 100, 1) : 0); ?>% of school days
                </div>
            </div>

            <div class="stat-card border-l-4 border-indigo-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Attendance Rate</p>
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($attendanceRate); ?>%</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-xs text-indigo-600 font-medium">
                    Overall this school year
                </div>
            </div>
        </div>

        <!-- Calendar View -->
        <div x-show="viewMode === 'calendar'" x-transition class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Calendar Header -->
            <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-white">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900">
                        <?php echo e($selectedDate->format('F Y')); ?>

                    </h2>
                    <div class="flex items-center gap-2">
                        <a href="<?php echo e(route('student.attendance', ['month' => $selectedDate->copy()->subMonth()->month, 'year' => $selectedDate->copy()->subMonth()->year])); ?>" 
                           class="calendar-nav-btn w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        <a href="<?php echo e(route('student.attendance', ['month' => \Carbon\Carbon::now()->month, 'year' => \Carbon\Carbon::now()->year])); ?>" 
                           class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm font-medium text-slate-600 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600 transition-all">
                            Today
                        </a>
                        <a href="<?php echo e(route('student.attendance', ['month' => $selectedDate->copy()->addMonth()->month, 'year' => $selectedDate->copy()->addMonth()->year])); ?>" 
                           class="calendar-nav-btn w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-indigo-50 hover:border-indigo-200 hover:text-indigo-600">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="p-6">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 gap-2 mb-4">
                    <?php $__currentLoopData = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="text-center py-2 text-sm font-semibold text-slate-500 uppercase tracking-wider">
                            <?php echo e($day); ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Calendar Days -->
                <div class="grid grid-cols-7 gap-2">
                    <?php
                        $dayCounter = 1;
                        $totalCells = 42;
                        $currentCell = 0;
                    ?>

                    <?php for($i = 0; $i < $totalCells; $i++): ?>
                        <?php
                            $isCurrentMonth = ($i >= $firstDayOfMonth && $dayCounter <= $daysInMonth);
                            $cellDate = null;
                            $attendance = null;
                            
                            if ($isCurrentMonth) {
                                $cellDate = $selectedDate->copy()->setDay($dayCounter);
                                $dateKey = $cellDate->format('Y-m-d');
                                $attendance = $attendances->get($dateKey);
                                $dayCounter++;
                            }
                            
                            $isToday = $cellDate && $cellDate->isToday();
                            $isWeekend = $cellDate && ($cellDate->isSaturday() || $cellDate->isSunday());
                        ?>

                        <div class="calendar-day rounded-xl border-2 <?php echo e($isCurrentMonth ? 'bg-white border-slate-100' : 'bg-slate-50 border-transparent'); ?> 
                                    <?php echo e($attendance ? 'status-' . $attendance->status : ''); ?> 
                                    <?php echo e($isToday ? 'ring-2 ring-indigo-500 ring-offset-2' : ''); ?>

                                    <?php echo e($isWeekend ? 'bg-slate-50' : ''); ?>

                                    p-3 relative cursor-pointer"
                             <?php if($isCurrentMonth): ?> @click="showAttendanceDetails('<?php echo e($cellDate->format('Y-m-d')); ?>')" <?php endif; ?>>
                            
                            <?php if($isCurrentMonth): ?>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-semibold <?php echo e($isToday ? 'text-indigo-600' : 'text-slate-700'); ?>">
                                        <?php echo e($dayCounter - 1); ?>

                                    </span>
                                    <?php if($isToday): ?>
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">Today</span>
                                    <?php endif; ?>
                                </div>

                                <?php if($attendance): ?>
                                    <div class="flex items-center gap-1">
                                        <?php if($attendance->status === 'present'): ?>
                                            <i class="fas fa-check-circle text-emerald-500"></i>
                                            <span class="text-xs font-medium text-emerald-700">Present</span>
                                        <?php elseif($attendance->status === 'absent'): ?>
                                            <i class="fas fa-times-circle text-rose-500"></i>
                                            <span class="text-xs font-medium text-rose-700">Absent</span>
                                        <?php elseif($attendance->status === 'late'): ?>
                                            <i class="fas fa-clock text-amber-500"></i>
                                            <span class="text-xs font-medium text-amber-700">Late</span>
                                        <?php elseif($attendance->status === 'excused'): ?>
                                            <i class="fas fa-file-medical text-indigo-500"></i>
                                            <span class="text-xs font-medium text-indigo-700">Excused</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if($attendance->remarks): ?>
                                        <p class="text-xs text-slate-500 mt-1 truncate"><?php echo e($attendance->remarks); ?></p>
                                    <?php endif; ?>
                                <?php elseif(!$isWeekend): ?>
                                    <div class="text-xs text-slate-400">No record</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Legend -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <span class="font-medium text-slate-500">Legend:</span>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-emerald-100 border border-emerald-300"></div>
                        <span class="text-slate-600">Present</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-rose-100 border border-rose-300"></div>
                        <span class="text-slate-600">Absent</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-amber-100 border border-amber-300"></div>
                        <span class="text-slate-600">Late</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-indigo-100 border border-indigo-300"></div>
                        <span class="text-slate-600">Excused</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 rounded bg-slate-100 border border-slate-300"></div>
                        <span class="text-slate-600">Weekend</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- List View -->
        <div x-show="viewMode === 'list'" x-transition class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-white">
                <h2 class="text-xl font-bold text-slate-900">Recent Attendance Records</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Day</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Time In</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Time Out</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        <?php $__empty_1 = true; $__currentLoopData = $recentAttendance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="attendance-row">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-medium text-slate-900">
                                        <?php echo e(\Carbon\Carbon::parse($record->date)->format('M d, Y')); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    <?php echo e(\Carbon\Carbon::parse($record->date)->format('l')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <?php if($record->status === 'present'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            <i class="fas fa-check-circle"></i> Present
                                        </span>
                                    <?php elseif($record->status === 'absent'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                            <i class="fas fa-times-circle"></i> Absent
                                        </span>
                                    <?php elseif($record->status === 'late'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">
                                            <i class="fas fa-clock"></i> Late
                                        </span>
                                    <?php elseif($record->status === 'excused'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                            <i class="fas fa-file-medical"></i> Excused
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-slate-600">
                                    <?php echo e($record->time_in ?? '—'); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-slate-600">
                                    <?php echo e($record->time_out ?? '—'); ?>

                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    <?php echo e($record->remarks ?? '—'); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center">
                                            <i class="fas fa-calendar-times text-slate-400 text-2xl"></i>
                                        </div>
                                        <p>No attendance records found for this period</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Monthly Summary -->
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-indigo-600"></i>
                Monthly Summary
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-emerald-50 rounded-xl">
                        <span class="text-emerald-700 font-medium">School Days</span>
                        <span class="text-emerald-900 font-bold text-lg"><?php echo e($stats['total_school_days']); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-blue-50 rounded-xl">
                        <span class="text-blue-700 font-medium">Days Attended</span>
                        <span class="text-blue-900 font-bold text-lg"><?php echo e($stats['attended_days']); ?></span>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-rose-50 rounded-xl">
                        <span class="text-rose-700 font-medium">Absences</span>
                        <span class="text-rose-900 font-bold text-lg"><?php echo e($stats['absent']); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-amber-50 rounded-xl">
                        <span class="text-amber-700 font-medium">Late Arrivals</span>
                        <span class="text-amber-900 font-bold text-lg"><?php echo e($stats['late']); ?></span>
                    </div>
                </div>

                <div class="flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-indigo-600 mb-1">
                            <?php echo e($stats['total_school_days'] > 0 ? round(($stats['attended_days'] / $stats['total_school_days']) * 100, 1) : 0); ?>%
                        </div>
                        <p class="text-sm text-slate-500">Monthly Attendance Rate</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Detail Modal -->
        <div x-show="selectedAttendance" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4"
             style="display: none;"
             @click.self="selectedAttendance = null">
            
            <div x-show="selectedAttendance"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
                
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-900" x-text="selectedAttendance?.date"></h3>
                    <button @click="selectedAttendance = null" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center gap-4 p-4 rounded-xl"
                         :class="{
                             'bg-emerald-50': selectedAttendance?.status === 'present',
                             'bg-rose-50': selectedAttendance?.status === 'absent',
                             'bg-amber-50': selectedAttendance?.status === 'late',
                             'bg-indigo-50': selectedAttendance?.status === 'excused',
                             'bg-slate-50': selectedAttendance?.status === 'no_record'
                         }">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl"
                             :class="{
                                 'bg-emerald-100 text-emerald-600': selectedAttendance?.status === 'present',
                                 'bg-rose-100 text-rose-600': selectedAttendance?.status === 'absent',
                                 'bg-amber-100 text-amber-600': selectedAttendance?.status === 'late',
                                 'bg-indigo-100 text-indigo-600': selectedAttendance?.status === 'excused',
                                 'bg-slate-200 text-slate-500': selectedAttendance?.status === 'no_record'
                             }">
                            <i class="fas" 
                               :class="{
                                   'fa-check': selectedAttendance?.status === 'present',
                                   'fa-times': selectedAttendance?.status === 'absent',
                                   'fa-clock': selectedAttendance?.status === 'late',
                                   'fa-file-medical': selectedAttendance?.status === 'excused',
                                   'fa-minus': selectedAttendance?.status === 'no_record'
                               }"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Status</p>
                            <p class="font-bold text-lg capitalize" 
                               x-text="selectedAttendance?.status === 'no_record' ? 'No Record' : selectedAttendance?.status"
                               :class="{
                                   'text-emerald-700': selectedAttendance?.status === 'present',
                                   'text-rose-700': selectedAttendance?.status === 'absent',
                                   'text-amber-700': selectedAttendance?.status === 'late',
                                   'text-indigo-700': selectedAttendance?.status === 'excused',
                                   'text-slate-600': selectedAttendance?.status === 'no_record'
                               }"></p>
                        </div>
                    </div>

                    <template x-if="selectedAttendance?.time_in">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-500 mb-1">Time In</p>
                                <p class="font-semibold text-slate-900" x-text="selectedAttendance?.time_in"></p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl">
                                <p class="text-sm text-slate-500 mb-1">Time Out</p>
                                <p class="font-semibold text-slate-900" x-text="selectedAttendance?.time_out || '—'"></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="selectedAttendance?.remarks">
                        <div class="p-4 bg-slate-50 rounded-xl">
                            <p class="text-sm text-slate-500 mb-1">Remarks</p>
                            <p class="font-medium text-slate-900" x-text="selectedAttendance?.remarks"></p>
                        </div>
                    </template>
                </div>

                <button @click="selectedAttendance = null" 
                        class="w-full mt-6 py-3 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors">
                    Close
                </button>
            </div>
        </div>

    </main>

    <!-- Logout Form -->
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\attendance\index.blade.php ENDPATH**/ ?>