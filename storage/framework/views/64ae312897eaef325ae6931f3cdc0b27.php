<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Tugawe ES - Teacher Dashboard | <?php echo e($teacher->user->name ?? 'Teacher'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --sidebar-width: 280px;
        }
        
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        body {
            background: #f1f5f9;
            color: #1e293b;
        }
        
        /* Glassmorphism Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 
                        0 2px 4px -1px rgba(0, 0, 0, 0.02),
                        0 0 0 1px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .glass-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 
                        0 10px 10px -5px rgba(0, 0, 0, 0.01);
        }
        
        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Attendance Buttons */
        .attendance-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .attendance-btn.active-present {
            background: #10b981;
            border-color: #10b981;
            color: white;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .attendance-btn.active-absent {
            background: #ef4444;
            border-color: #ef4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }
        .attendance-btn.active-late {
            background: #f59e0b;
            border-color: #f59e0b;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        }
        
        /* Animations */
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.2); opacity: 0; }
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-in { animation: slideIn 0.3s ease-out; }
        
        /* Status Badges */
        .status-excellent { background: #d1fae5; color: #059669; }
        .status-good { background: #fef3c7; color: #d97706; }
        .status-warning { background: #fee2e2; color: #dc2626; }
        .status-info { background: #dbeafe; color: #2563eb; }
        
        /* Loading State */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        /* Toast Notifications */
        .toast {
            animation: slideIn 0.3s ease-out;
            backdrop-filter: blur(12px);
        }
        
        /* Teacher Profile Header */
        .teacher-profile {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }
        .teacher-profile::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            pointer-events: none;
        }
        
        /* Calendar Styles */
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .calendar-day:hover {
            background: #f1f5f9;
        }
        .calendar-day.today {
            background: #6366f1;
            color: white;
            font-weight: 600;
        }
        .calendar-day.has-data {
            position: relative;
        }
        .calendar-day.has-data::after {
            content: '';
            position: absolute;
            bottom: 4px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: currentColor;
        }
        
        /* Progress Ring */
        .progress-ring {
            transform: rotate(-90deg);
        }
        .progress-ring-circle {
            transition: stroke-dashoffset 0.5s ease-in-out;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased h-screen overflow-hidden" x-data="{ mobileOpen: false }">

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

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

    <!-- Main Layout -->
    <div class="flex h-screen">
        
        <!-- Sidebar -->
        <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="lg:ml-72 w-full h-screen flex flex-col overflow-hidden">
            
            <!-- Header -->
            <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-16 flex-shrink-0">
                <div class="flex items-center justify-between h-full px-4 lg:px-8">
                    <div class="flex items-center gap-4">
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="hidden sm:block">
                            <h2 class="text-lg font-bold text-slate-800">Teacher Dashboard</h2>
                            <div class="flex items-center gap-2 text-xs text-slate-500 mt-0.5">
                              <span>S.Y. <?php echo e($activeSchoolYear->name ?? now()->format('Y') . '-' . (now()->format('Y') + 1)); ?></span>
                                <?php if($currentQuarter): ?>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <span class="text-indigo-600 font-semibold"><?php echo e($currentQuarter); ?> Quarter</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Real-time Clock -->
                        <div class="hidden md:flex items-center gap-3 bg-slate-100 px-4 py-2 rounded-2xl border border-slate-200">
                            <i class="fas fa-clock text-indigo-600"></i>
                            <div class="flex flex-col">
                                <span id="liveClock" class="text-sm font-bold text-slate-800 font-mono tabular-nums">00:00:00</span>
                                <span id="liveDate" class="text-[10px] text-slate-500 uppercase tracking-wider"><?php echo e(now()->format('M d, Y')); ?></span>
                            </div>
                        </div>
                        
                        <!-- Notifications -->
                        <?php echo $__env->make('components.notification-bell', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        
                        <!-- User Profile -->
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                            <div class="hidden lg:block text-right">
                                <p class="text-sm font-bold text-slate-800"><?php echo e($teacher->user->first_name); ?> <?php echo e($teacher->user->middle_name ?? ' '); ?> <?php echo e($teacher->user->last_name); ?></p>
                                <p class="text-xs text-slate-500">Class Adviser</p>
                            </div>
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg ring-2 ring-white">
                                    <?php echo e(strtoupper(substr($teacher->user->name ?? 'T', 0, 1))); ?>

                                </div>
                                <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-8 bg-slate-50 custom-scrollbar">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    <!-- Teacher Welcome Banner -->
                    <div class="teacher-profile rounded-3xl p-6 lg:p-8 text-white shadow-2xl relative overflow-hidden animate-fade-in">
                        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-16 -mb-16 blur-3xl"></div>
                        
                        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-sm border border-white/20">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i> Class Adviser
                                    </span>
                                    <span class="px-3 py-1 bg-white/20 rounded-full text-xs font-medium backdrop-blur-sm border border-white/20">
                                        <?php echo e($activeSection->gradeLevel->name ?? 'Grade'); ?> - <?php echo e($activeSection->name ?? 'Section'); ?>

                                    </span>
                                </div>
                               
<?php
    // Use Carbon with explicit timezone (adjust to your timezone)
    $hour = now()->timezone('Asia/Manila')->format('H'); // or your local timezone
    
    if ($hour >= 5 && $hour < 12) {
        $greeting = 'Maayong Buntag'; // Good Morning (5:00 AM - 11:59 AM)
    } elseif ($hour >= 12 && $hour < 18) {
        $greeting = 'Maayong Hapon'; // Good Afternoon (12:00 PM - 5:59 PM)
    } else {
        $greeting = 'Maayong Gabie'; // Good Evening (6:00 PM - 4:59 AM)
    }
?>
                                   <h1 class="text-2xl lg:text-4xl font-bold mb-2 tracking-tight">
    <?php echo e($greeting); ?>, <?php echo e(explode(' ', $teacher->user->first_name ?? 'Teacher')[0]); ?>! 
</h1>
                                <p class="text-indigo-100 text-lg max-w-2xl">Ready to make a difference today?</p>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 text-center min-w-[100px] border border-white/20">
                                    <div class="text-3xl font-bold"><?php echo e($totalStudents ?? 0); ?></div>
                                    <div class="text-xs text-indigo-200 uppercase tracking-wider mt-1">Pupils</div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Main Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        
                        <?php
                            $attendanceDate = request('date', now()->format('Y-m-d'));
                        ?>
                        <!-- Daily Attendance Section -->
                        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-clipboard-list text-indigo-500"></i>
                                        Daily Attendance
                                    </h3>
                                    <div class="flex items-center gap-3 mt-1">
                                        <input type="date" 
                                               value="<?php echo e($attendanceDate); ?>"
                                               onchange="window.location.href='<?php echo e(route('teacher.dashboard')); ?>?section_id=<?php echo e($activeSection->id ?? ''); ?>&date=' + this.value"
                                               class="text-sm bg-white border border-slate-200 rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                        <button type="button" 
                                                onclick="window.location.href='<?php echo e(route('teacher.dashboard')); ?>?section_id=<?php echo e($activeSection->id ?? ''); ?>'"
                                                class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-lg hover:bg-slate-200 transition-colors">
                                            Today
                                        </button>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <button onclick="markAllPresent()" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-xl hover:bg-emerald-200 transition-all text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-check"></i> All Present
                                    </button>
                                    <button type="button" onclick="saveAttendance()" id="saveAttendanceBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm font-medium shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                            
                            <form id="attendanceForm" onsubmit="event.preventDefault();">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="date" id="attendanceDateInput" value="<?php echo e($attendanceDate); ?>">
                                <input type="hidden" name="section_id" value="<?php echo e($activeSection->id ?? ''); ?>">
                                <input type="hidden" name="teacher_id" value="<?php echo e($teacher->id ?? ''); ?>">
                                
                                <div class="overflow-x-auto max-h-[400px] overflow-y-auto rounded-xl border border-slate-200 custom-scrollbar bg-white">
                                    <table class="w-full text-sm">
                                        <thead class="bg-slate-50 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Learner Name</th>
                                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-12">Sex</th>
                                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Status</th>
                                                <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100" id="attendanceTableBody">
                                            <?php
                                                $sortedStudents = collect($students ?? [])->sortBy(function($student) {
                                                    $gender = strtoupper($student->gender ?? '');
                                                    $isMale = ($gender === 'MALE' || $gender === 'M');
                                                    $lastName = $student->user->last_name ?? $student->last_name ?? '';
                                                    $firstName = $student->user->first_name ?? $student->first_name ?? '';
                                                    return [
                                                        $isMale ? 0 : 1,
                                                        strtolower($lastName),
                                                        strtolower($firstName)
                                                    ];
                                                })->values();
                                                
                                                $maleCount = $sortedStudents->filter(function($s) {
                                                    $g = strtoupper($s->gender ?? '');
                                                    return $g === 'MALE' || $g === 'M';
                                                })->count();
                                                
                                                $displayIndex = 0;
                                            ?>

                                            <?php $__empty_1 = true; $__currentLoopData = $sortedStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php
                                                    $todayRecord = $student->attendances->where('date', $attendanceDate)->first();
                                                    $currentStatus = $todayRecord ? $todayRecord->status : null;
                                                    
                                                    $gender = strtoupper($student->gender ?? '');
                                                    $sex = ($gender === 'MALE' || $gender === 'M') ? 'M' : 'F';
                                                    $isFirstFemale = ($sex === 'F' && $displayIndex === $maleCount && $maleCount > 0);
                                                    $displayIndex++;
                                                ?>
                                                
                                                <?php if($isFirstFemale): ?>
                                                    <tr class="bg-slate-100">
                                                        <td colspan="4" class="px-4 py-2 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-y border-slate-200">
                                                            <i class="fas fa-venus text-pink-500 mr-2"></i>Female Students
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                
                                                <?php if($displayIndex === 1 && $maleCount > 0): ?>
                                                    <tr class="bg-slate-100">
                                                        <td colspan="4" class="px-4 py-2 text-center text-xs font-bold text-slate-600 uppercase tracking-wider border-y border-slate-200">
                                                            <i class="fas fa-mars text-blue-500 mr-2"></i>Male Students
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                                
                                                <tr class="hover:bg-slate-50 transition-colors" data-student-id="<?php echo e($student->id); ?>">
                                                    <td class="px-4 py-3">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                                                <?php echo e(strtoupper(substr($student->user->first_name ?? $student->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($student->user->last_name ?? $student->last_name, 0, 1))); ?>

                                                            </div>
                                                            <div>
                                                                <p class="font-semibold text-slate-800 text-sm">
                                                                    <?php echo e($student->user->last_name ?? $student->last_name); ?>, 
                                                                    <?php echo e($student->user->first_name ?? $student->first_name); ?>

                                                                </p>
                                                                <p class="text-xs text-slate-500 font-mono">LRN: <?php echo e($student->lrn ?? 'Not Assigned'); ?></p>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="attendance[<?php echo e($student->id); ?>][student_id]" value="<?php echo e($student->id); ?>">
                                                    </td>
                                                    <td class="px-4 py-3 text-center">
                                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold <?php echo e($sex === 'M' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700'); ?>">
                                                            <?php echo e($sex); ?>

                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="flex justify-center gap-2">
                                                            <button type="button" 
                                                                    class="attendance-btn w-9 h-9 rounded-full border-2 <?php echo e($currentStatus === 'present' ? 'active-present' : 'border-slate-200 hover:border-emerald-400 bg-white'); ?> flex items-center justify-center text-xs" 
                                                                    data-status="present"
                                                                    onclick="setAttendance(this, '<?php echo e($student->id); ?>', 'present')"
                                                                    title="Present">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    class="attendance-btn w-9 h-9 rounded-full border-2 <?php echo e($currentStatus === 'absent' ? 'active-absent' : 'border-slate-200 hover:border-rose-400 bg-white'); ?> flex items-center justify-center text-xs" 
                                                                    data-status="absent"
                                                                    onclick="setAttendance(this, '<?php echo e($student->id); ?>', 'absent')"
                                                                    title="Absent">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <button type="button" 
                                                                    class="attendance-btn w-9 h-9 rounded-full border-2 <?php echo e($currentStatus === 'late' ? 'active-late' : 'border-slate-200 hover:border-amber-400 bg-white'); ?> flex items-center justify-center text-xs" 
                                                                    data-status="late"
                                                                    onclick="setAttendance(this, '<?php echo e($student->id); ?>', 'late')"
                                                                    title="Late">
                                                                <i class="fas fa-clock"></i>
                                                            </button>
                                                        </div>
                                                        <input type="hidden" name="attendance[<?php echo e($student->id); ?>][status]" id="status-<?php echo e($student->id); ?>" value="<?php echo e($currentStatus); ?>">
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <input type="text" 
                                                               name="attendance[<?php echo e($student->id); ?>][remarks]" 
                                                               class="w-full text-xs border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 px-3 py-2 bg-slate-50 focus:bg-white transition-all" 
                                                               placeholder="Add remarks..."
                                                               value="<?php echo e($todayRecord->remarks ?? ''); ?>">
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="4" class="px-4 py-12 text-center">
                                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                            <i class="fas fa-users-slash text-2xl text-slate-400"></i>
                                                        </div>
                                                        <p class="text-slate-700 font-medium">No students enrolled</p>
                                                        <a href="<?php echo e(route('teacher.sections.index')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm mt-2 inline-block">Select a section</a>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>

                        <!-- Right Sidebar -->
                        <div class="space-y-6">
                            <!-- Section Info -->
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-chalkboard text-indigo-500"></i>
                                    Active Section
                                </h3>
                                <form action="<?php echo e(route('teacher.dashboard')); ?>" method="GET" id="sectionForm" class="flex items-center gap-2">
                                    <select name="section_id" onchange="document.getElementById('sectionForm').submit()" 
                                            class="flex-1 px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white text-sm font-medium text-slate-700 cursor-pointer">
                                        <?php $__currentLoopData = $sections ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($section->id); ?>" <?php echo e(($activeSection->id ?? '') == $section->id ? 'selected' : ''); ?>>
                                                Grade <?php echo e($section->gradeLevel->name ?? 'N/A'); ?> - <?php echo e($section->name); ?> (<?php echo e($section->students->count()); ?> students)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php if($activeSection ?? false): ?>
                                    <a href="<?php echo e(route('teacher.sections.show', $activeSection)); ?>" 
                                       title="Go to Section Page"
                                       class="shrink-0 w-10 h-10 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center transition-all border border-indigo-100 hover:border-indigo-200">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                </form>
                                
                                <?php if($activeSection ?? false): ?>
                                <div class="mt-4 space-y-3">
                                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-medium text-slate-600">Adviser</span>
                                        <span class="text-xs font-bold text-slate-800"><?php echo e($activeSection->teacher->full_name ?? 'N/A'); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-medium text-slate-600">Room</span>
                                        <span class="text-xs font-bold text-slate-800"><?php echo e($activeSection->room_number ?? 'TBA'); ?></span>
                                    </div>
                                    <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-medium text-slate-600">School Year</span>
                                        <span class="text-xs font-bold text-slate-800"><?php echo e($schoolYear ?? now()->format('Y') . '-' . (now()->format('Y') + 1)); ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>



                            <!-- Upcoming Events -->
                            <div class="glass-card rounded-2xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                        <i class="fas fa-calendar-star text-purple-500"></i>
                                        Upcoming Events
                                    </h3>
                                    <a href="<?php echo e(route('teacher.events.index')); ?>" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold">View All</a>
                                </div>
                                <div class="space-y-3">
                                    <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 text-purple-600 flex flex-col items-center justify-center font-bold text-xs border border-purple-200 group-hover:shadow-md transition-all flex-shrink-0">
                                                <span class="text-[10px] uppercase"><?php echo e($event->date->format('M')); ?></span>
                                                <span class="text-base"><?php echo e($event->date->format('d')); ?></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-slate-800 text-sm truncate group-hover:text-purple-600 transition-colors"><?php echo e($event->title); ?></p>
                                                <p class="text-slate-500 text-xs mt-0.5"><?php echo e($event->date->format('l, F j, Y')); ?></p>
                                            </div>
                                            <div class="w-2 h-2 rounded-full bg-purple-500 mt-2 flex-shrink-0"></div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <p class="text-slate-400 text-sm text-center py-4">No upcoming events</p>
                                    <?php endif; ?>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- Subject Performance -->
                    <div class="glass-card rounded-2xl p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-chart-line text-indigo-500"></i>
                                    Subject Performance Overview
                                </h3>
                                <p class="text-sm text-slate-500 mt-1"><?php echo e($currentQuarter ? $currentQuarter . ' Quarter' : 'Current Quarter'); ?> • Real-time grade analysis</p>
                            </div>
                            <div class="flex gap-2">
                                <select id="subjectFilter" onchange="filterSubjects(this.value)" class="px-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 bg-white cursor-pointer">
                                    <option value="all">All Subjects</option>
                                    <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <a href="<?php echo e(route('teacher.exports.sf9', ['section_id' => $activeSection->id ?? ''])); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm font-medium shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2">
                                    <i class="fas fa-download"></i> SF9
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6" id="subjectCards">
                            <?php $__currentLoopData = $subjectStats ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stats): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="subject-card border border-slate-200 rounded-xl p-4 hover:shadow-lg transition-all hover:border-indigo-300 cursor-pointer bg-white" data-subject-id="<?php echo e($stats['subject_id']); ?>">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 bg-<?php echo e($stats['color'] ?? 'indigo'); ?>-100 rounded-lg flex items-center justify-center text-<?php echo e($stats['color'] ?? 'indigo'); ?>-600">
                                        <i class="fas <?php echo e($stats['icon'] ?? 'fa-book'); ?>"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 text-sm truncate"><?php echo e($stats['name']); ?></h4>
                                        <p class="text-[10px] text-slate-500">WW:<?php echo e($stats['ww_weight'] ?? 40); ?>% PT:<?php echo e($stats['pt_weight'] ?? 40); ?>%</p>
                                    </div>
                                </div>
                                <div class="flex justify-between items-end mb-2">
                                    <div>
                                        <p class="text-2xl font-bold text-slate-800"><?php echo e(number_format($stats['class_average'] ?? 0, 1)); ?></p>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Class Average</p>
                                    </div>
                                    <div class="text-right">
                                        <?php
                                            $avg = $stats['class_average'] ?? 0;
                                            if($avg >= 90) { $label = 'Advanced'; $class = 'status-excellent'; }
                                            elseif($avg >= 85) { $label = 'Proficient'; $class = 'status-info'; }
                                            elseif($avg >= 80) { $label = 'Approaching'; $class = 'status-good'; }
                                            elseif($avg >= 75) { $label = 'Developing'; $class = 'bg-orange-100 text-orange-700'; }
                                            else { $label = 'Beginning'; $class = 'status-warning'; }
                                        ?>
                                        <span class="<?php echo e($class); ?> px-2 py-1 rounded-lg text-[10px] font-bold"><?php echo e($label); ?></span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 mb-2">
                                    <div class="bg-<?php echo e($stats['color'] ?? 'indigo'); ?>-500 h-1.5 rounded-full transition-all duration-700" style="width: <?php echo e(min($avg, 100)); ?>%"></div>
                                </div>
                                <div class="flex justify-between text-[10px] text-slate-500">
                                    <span><?php echo e($stats['encoded_count'] ?? 0); ?>/<?php echo e($totalStudents ?? 0); ?> encoded</span>
                                    <span class="<?php echo e(($stats['at_risk_count'] ?? 0) > 0 ? 'text-rose-600 font-bold' : ''); ?>"><?php echo e($stats['at_risk_count'] ?? 0); ?> at risk</span>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- At Risk Students Table -->
                        <?php if(($atRiskStudents ?? collect())->count() > 0): ?>
                        <div class="border-t border-slate-100 pt-6">
                            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="fas fa-user-shield text-rose-500"></i>
                                Pupils Requiring Intervention
                            </h4>
                            <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white custom-scrollbar">
                                <table class="w-full text-sm">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Learner</th>
                                            <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <th class="px-4 py-3 text-center text-xs font-bold text-slate-600 uppercase"><?php echo e($subject->code); ?></th>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <th class="px-4 py-3 text-center text-xs font-bold text-slate-600 uppercase">Absences</th>
                                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-600 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        <?php $__currentLoopData = $atRiskStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-rose-50/30 transition-colors">
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                                        <?php echo e(strtoupper(substr($student->user->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($student->user->last_name, 0, 1))); ?>

                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-slate-800 text-sm"><?php echo e($student->user->last_name); ?>, <?php echo e($student->user->first_name); ?></p>
                                                        <p class="text-[10px] text-slate-500 font-mono">LRN: <?php echo e($student->lrn ?? 'Not Assigned'); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $grade = $student->grades->where('subject_id', $subject->id)->where('quarter', $currentQuarterNumber ?? 1)->where('component_type', 'final_grade')->first();
                                                    $finalGrade = $grade?->final_grade;
                                                ?>
                                                <td class="px-4 py-3 text-center">
                                                    <?php if($finalGrade): ?>
                                                        <span class="px-2 py-1 rounded-lg text-xs font-bold <?php echo e($finalGrade < 75 ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700'); ?>">
                                                            <?php echo e(number_format($finalGrade, 0)); ?>

                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-slate-300">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="px-4 py-3 text-center">
                                                <?php
                                                    $absenceCount = $student->attendances->where('status', 'absent')->whereBetween('date', [$schoolYearStart ?? now()->subYear(), now()])->count();
                                                    $absenceRate = ($absenceCount / max($daysCompleted ?? 1, 1)) * 100;
                                                ?>
                                                <span class="px-2 py-1 rounded-lg text-xs font-bold <?php echo e($absenceRate > 20 ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700'); ?>">
                                                    <?php echo e($absenceCount); ?> (<?php echo e(number_format($absenceRate, 0)); ?>%)
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <button onclick="openInterventionModal('<?php echo e($student->id); ?>', '<?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>')" 
                                                        class="text-indigo-600 hover:text-indigo-800 font-medium text-xs bg-indigo-50 px-3 py-2 rounded-lg hover:bg-indigo-100 transition-all">
                                                    Plan Intervention
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Bottom Section -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Recent Activity -->
                        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-history text-indigo-500"></i>
                                    Recent Grade Encoding
                                </h3>
                                <a href="<?php echo e(route('teacher.grades.index')); ?>" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View All</a>
                            </div>
                            
                            <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                                <?php $__empty_1 = true; $__currentLoopData = $recentGrades ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl hover:bg-indigo-50 transition-all border border-transparent hover:border-indigo-200 cursor-pointer group">
                                    <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <i class="fas fa-file-signature"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-slate-800 text-sm"><?php echo e($grade->student->user->last_name); ?>, <?php echo e($grade->student->user->first_name); ?></h4>
                                                <p class="text-xs text-slate-500"><?php echo e($grade->subject->name); ?> • <?php echo e($grade->quarter); ?> Quarter</p>
                                            </div>
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium">Encoded</span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-3 text-xs">
                                            <span class="bg-white px-2 py-1 rounded border border-slate-200">WW: <?php echo e($grade->ww_weighted ?? 0); ?>%</span>
                                            <span class="bg-white px-2 py-1 rounded border border-slate-200">PT: <?php echo e($grade->pt_weighted ?? 0); ?>%</span>
                                            <span class="font-bold text-slate-700 bg-indigo-50 px-2 py-1 rounded border border-indigo-100">Final: <?php echo e($grade->final_grade ?? 0); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-8 text-slate-500">
                                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-clipboard-list text-2xl text-slate-400"></i>
                                    </div>
                                    <p class="font-medium text-slate-700">No recent grades</p>
                                    <a href="<?php echo e($activeSection ? route('teacher.sections.grades', $activeSection) : '#'); ?>"  class="mt-4 inline-block px-6 py-2 bg-indigo-600 text-white rounded-xl text-sm font-medium hover:bg-indigo-700 transition-all">Encode Grades</a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Actions & Deadlines -->
                        <div class="space-y-6">
                            <div class="glass-card rounded-2xl p-6">
                                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                                    <i class="fas fa-bolt text-amber-500"></i>
                                    Quick Actions
                                </h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <a href="<?php echo e($activeSection ? route('teacher.sections.grades', $activeSection) : '#'); ?>"  class="p-4 bg-indigo-50 hover:bg-indigo-100 rounded-xl text-center transition-all group border border-transparent hover:border-indigo-200">
                                        <i class="fas fa-plus-circle text-2xl text-indigo-600 mb-2 group-hover:scale-110 transition-transform inline-block"></i>
                                        <p class="text-xs font-bold text-indigo-800">Encode Grades</p>
                                    </a>
                                <a href="<?php echo e(route('teacher.exports.sf1')); ?>?section_id=<?php echo e($activeSection->id ?? ''); ?>"
   class="p-4 bg-emerald-50 hover:bg-emerald-100 rounded-xl text-center transition-all group border border-transparent hover:border-emerald-200">
    <i class="fas fa-file-excel text-2xl text-emerald-600 mb-2 group-hover:scale-110 transition-transform inline-block"></i>
    <p class="text-xs font-bold text-emerald-800">Export SF1</p>
</a>
                                    <a href="<?php echo e(route('teacher.reports.index')); ?>" class="p-4 bg-violet-50 hover:bg-violet-100 rounded-xl text-center transition-all group border border-transparent hover:border-violet-200">
                                        <i class="fas fa-chart-pie text-2xl text-violet-600 mb-2 group-hover:scale-110 transition-transform inline-block"></i>
                                        <p class="text-xs font-bold text-violet-800">View Reports</p>
                                    </a>
                                    <button onclick="printClassRecord()" class="p-4 bg-amber-50 hover:bg-amber-100 rounded-xl text-center transition-all group border border-transparent hover:border-amber-200">
                                        <i class="fas fa-print text-2xl text-amber-600 mb-2 group-hover:scale-110 transition-transform inline-block"></i>
                                        <p class="text-xs font-bold text-amber-800">Print Record</p>
                                    </button>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Intervention Modal -->
    <div id="interventionModal" class="fixed inset-0 bg-slate-900/50 hidden items-center justify-center z-50 backdrop-blur-sm opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 p-6 transform scale-95 transition-transform duration-300" id="interventionModalContent">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-user-shield text-indigo-500"></i>
                    Learning Intervention Plan
                </h3>
                <button onclick="closeInterventionModal()" class="text-slate-400 hover:text-slate-600 transition-colors p-2 hover:bg-slate-100 rounded-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="interventionForm" action="<?php echo e(route('teacher.interventions.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="student_id" id="interventionStudentId">
                <input type="hidden" name="teacher_id" value="<?php echo e($teacher->id ?? ''); ?>">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Learner</label>
                        <input type="text" id="interventionStudentName" class="w-full px-4 py-3 border border-slate-200 rounded-xl bg-slate-50 text-slate-700 font-semibold" readonly>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subject <span class="text-rose-500">*</span></label>
                        <select name="subject_id" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer">
                            <option value="">Select Subject</option>
                            <?php $__currentLoopData = $subjects ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Intervention Type</label>
                        <select name="intervention_type" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer">
                            <option value="remedial">Remedial Classes</option>
                            <option value="peer_tutoring">Peer Tutoring</option>
                            <option value="parent_conference">Parent Conference</option>
                            <option value="module_based">Module-Based Learning</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Schedule <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="schedule" required class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white cursor-pointer">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Strategy <span class="text-rose-500">*</span></label>
                        <textarea name="description" required rows="3" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none bg-white" placeholder="Describe specific intervention strategies..."></textarea>
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeInterventionModal()" class="flex-1 px-4 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition-all font-medium text-sm">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all font-medium text-sm">Save Plan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        
        // Real-time Clock
function updateClock() {
    const now = new Date();

    let hours = now.getHours();
    let minutes = now.getMinutes();
    let seconds = now.getSeconds();

    // AM / PM
    const ampm = hours >= 12 ? 'PM' : 'AM';

    // Convert to 12-hour format
    hours = hours % 12;
    hours = hours ? hours : 12; // 0 becomes 12

    // Add leading zeros
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    seconds = String(seconds).padStart(2, '0');

    const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;

    document.getElementById('liveClock').textContent = timeString;
}

// Run immediately + every second
updateClock();
setInterval(updateClock, 1000);


        // Attendance Functions
function setAttendance(btn, studentId, status) {
    const row = btn.closest('tr');
    row.querySelectorAll('.attendance-btn').forEach(b => {
        b.className = 'attendance-btn w-9 h-9 rounded-full border-2 border-slate-200 hover:border-indigo-400 bg-white flex items-center justify-center text-xs text-slate-600';
    });
    
    btn.className = `attendance-btn w-9 h-9 rounded-full border-2 active-${status} flex items-center justify-center text-xs`;
    
    const input = document.getElementById(`status-${studentId}`);
    if (input) input.value = status;
    

}
        
      function markAllPresent() {
    window.isBulkOperation = true; // Add this line
    document.querySelectorAll('tr[data-student-id]').forEach(row => {
        const studentId = row.dataset.studentId;
        const presentBtn = row.querySelector('[data-status="present"]');
        if (presentBtn) setAttendance(presentBtn, studentId, 'present');
    });
    window.isBulkOperation = false; // Add this line
    showToast('All students marked present', 'success');
}

        
function saveAttendance() {
    const form = document.getElementById('attendanceForm');
    const btn = document.getElementById('saveAttendanceBtn');
    
    const unmarked = Array.from(document.querySelectorAll('input[name^="attendance["][name$="[status]"]')).filter(input => !input.value);
    
    if (unmarked.length > 0 && !confirm(`${unmarked.length} student(s) unmarked. Continue?`)) return;
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
    
    // Use hardcoded URL instead of form.action
    fetch('<?php echo e(route("teacher.attendance.bulk-store")); ?>', {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        return response.json().then(data => {
            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Failed to save attendance');
            }
            return data;
        });
    })
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save mr-2"></i>Save';
        
        showToast(data.message || 'Attendance saved!', 'success');
        if (data.summary) updateCalendar(data.summary);
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-save mr-2"></i>Save';
        console.error('Error:', error);
        showToast(error.message || 'Network error', 'error');
    });
    
    return false; // Extra safety
}
        
        // Mini Calendar
function generateCalendar() {
    const calendar = document.getElementById("miniCalendar");
    calendar.innerHTML = "";

    const now = new Date();
    const year = now.getFullYear();
    const month = now.getMonth();

    const today = now.getDate();

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Empty slots
    for (let i = 0; i < firstDay; i++) {
        calendar.innerHTML += `<div></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {

        let isToday = day === today;

        calendar.innerHTML += `
            <div class="
                flex items-center justify-center
                w-9 h-9 text-xs font-semibold
                rounded-xl cursor-pointer
                transition-all duration-200
                ${isToday 
                    ? 'bg-indigo-600 text-white shadow-md scale-105' 
                    : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-600'}
            ">
                ${day}
            </div>
        `;
    }
}

generateCalendar();

        
        // Modal Functions
        function openInterventionModal(studentId, studentName) {
            const modal = document.getElementById('interventionModal');
            const content = document.getElementById('interventionModalContent');
            
            document.getElementById('interventionStudentId').value = studentId;
            document.getElementById('interventionStudentName').value = studentName;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }
        
        function closeInterventionModal() {
            const modal = document.getElementById('interventionModal');
            const content = document.getElementById('interventionModalContent');
            
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }
        
        // Filter Functions
        function filterSubjects(subjectId) {
            document.querySelectorAll('.subject-card').forEach(card => {
                if (subjectId === 'all' || card.dataset.subjectId === subjectId) {
                    card.style.display = 'block';
                    card.classList.add('animate-fade-in');
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // UI Functions
        
function printClassRecord() {
    // Redirect to the class record route in the same tab
    window.location.href = `<?php echo e(route('teacher.reports.class-record')); ?>?section_id=<?php echo e($activeSection->id ?? ''); ?>`;
}
        // Toast Notifications
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            if (!container) return;
            
            const colors = {
                success: 'bg-emerald-500',
                error: 'bg-rose-500',
                info: 'bg-indigo-500',
                warning: 'bg-amber-500'
            };
            
            const icons = {
                success: 'fa-check-circle',
                error: 'fa-exclamation-circle',
                info: 'fa-info-circle',
                warning: 'fa-exclamation-triangle'
            };
            
            const toast = document.createElement('div');
            toast.className = `toast flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white ${colors[type]} min-w-[300px] cursor-pointer transform transition-all duration-300 hover:scale-105`;
            toast.innerHTML = `
                <i class="fas ${icons[type]}"></i>
                <span class="font-medium text-sm">${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-auto hover:opacity-75">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            toast.onclick = () => toast.remove();
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        // Event Listeners
        document.getElementById('interventionModal')?.addEventListener('click', e => {
            if (e.target === e.currentTarget) closeInterventionModal();
        });
        
        document.getElementById('interventionForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            
            fetch(this.action, {
                method: 'POST',
                body: new FormData(this),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                
                if (data.success) {
                    showToast('Intervention plan saved!', 'success');
                    closeInterventionModal();
                    this.reset();
                } else {
                    showToast(data.message || 'Error saving', 'error');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
                showToast('Network error', 'error');
            });
        });
        
        // Keyboard Shortcuts
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeInterventionModal();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                if (document.getElementById('attendanceForm')) saveAttendance();
            }
        });
    </script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/teacher/dashboard.blade.php ENDPATH**/ ?>