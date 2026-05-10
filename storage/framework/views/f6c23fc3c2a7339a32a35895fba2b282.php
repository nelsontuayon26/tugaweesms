<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Attendance - <?php echo e($section->name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes modal-pop {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .modal-pop {
            animation: modal-pop 0.3s ease-out;
        }
        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50/50 min-h-screen" x-data="{ mobileOpen: false }">


<?php if(!$activeSchoolYear): ?>
<div id="inactiveSchoolYearModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform scale-100" style="animation: fade-in 0.3s ease-out;">
        <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-900">No Active School Year</h3>
                    <p class="text-sm text-red-600">Attendance cannot be recorded</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <p class="text-slate-600 mb-4">
                There is currently no active school year set in the system. Please contact the administrator to activate a school year before recording attendance.
            </p>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                    <p class="text-sm text-amber-800">
                        Attendance records can only be saved during an active school year period.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-6 pt-0 flex gap-3">
            <a href="<?php echo e(route('teacher.dashboard')); ?>" 
               class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors text-center">
                Go to Dashboard
            </a>
            <a href="<?php echo e(route('teacher.sections.index')); ?>" 
               class="flex-1 px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors text-center">
                Back to Sections
            </a>
        </div>
    </div>
</div>
<?php endif; ?>


<div id="saveAttendanceModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div id="saveModalContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
        
        <div id="saveModalLoading" class="p-8 text-center">
            <div class="w-16 h-16 rounded-full border-4 border-indigo-200 border-t-indigo-600 animate-spin mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-slate-800">Saving Attendance...</h3>
            <p class="text-sm text-slate-500 mt-1">Please wait</p>
        </div>
        
        
        <div id="saveModalSuccess" class="hidden">
            <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-emerald-900">Saved Successfully!</h3>
                <p class="text-sm text-emerald-600 mt-1">Attendance has been recorded</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-slate-600 mb-4" id="successMessage">Attendance for <?php echo e(\Carbon\Carbon::parse($date)->format('F d, Y')); ?> has been saved.</p>
                <button onclick="closeSaveModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                    Continue
                </button>
            </div>
        </div>
        
        
        <div id="saveModalError" class="hidden">
            <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                </div>
                <h3 class="text-xl font-bold text-red-900">Save Failed!</h3>
                <p class="text-sm text-red-600 mt-1">Unable to save attendance</p>
            </div>
            <div class="p-6 text-center">
                <p class="text-slate-600 mb-4" id="errorMessage">An error occurred while saving attendance.</p>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-4">
                    <p class="text-sm text-amber-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        Attendance can only be saved during an active school year.
                    </p>
                </div>
                <button onclick="closeSaveModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                    Try Again
                </button>
            </div>
        </div>
    </div>
</div>

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

<!-- Mobile Toggle Button -->
<button @click="mobileOpen = !mobileOpen" 
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="lg:ml-72 w-full min-h-screen p-8">

        

        <div class="mb-8">
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                <a href="<?php echo e(route('teacher.dashboard')); ?>" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo e(route('teacher.sections.index')); ?>" class="hover:text-indigo-600 transition-colors">Sections</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-700 font-medium"><?php echo e($section->name); ?></span>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-indigo-600 font-medium">Attendance</span>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <i class="fas fa-clipboard-check text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                                Attendance
                            </span>
                            <p class="text-sm font-normal text-slate-500 mt-1">
                                <?php echo e($section->name); ?> • <?php echo e($section->gradeLevel->name ?? 'N/A'); ?>

                            </p>
                        </div>
                    </h1>
                </div>

                <div class="flex gap-3">
                    <div class="bg-white/80 backdrop-blur-sm px-4 py-3 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <i class="fas fa-users text-emerald-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Total Students</p>
                                <p class="text-lg font-bold text-slate-900"><?php echo e(count($students)); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white/80 backdrop-blur-sm px-4 py-3 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <i class="fas fa-calendar-day text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wide">Today</p>
                                <p class="text-lg font-bold text-slate-900"><?php echo e(\Carbon\Carbon::parse($date)->format('M d')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Selector Card -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl shadow-slate-200/50 p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-orange-50 border border-amber-200 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-amber-600 text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Select Date</h3>
                        <p class="text-sm text-slate-500">Choose the attendance date</p>
                    </div>
                </div>
                
                <form method="GET" class="flex items-center gap-3">
                    <div class="relative">
                        <input type="date" name="date" value="<?php echo e($date); ?>" 
                               class="pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all hover:border-slate-300"
                               onchange="this.form.submit()">
                        <i class="fas fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                    
                    <button type="button" onclick="window.location.href='?date=<?php echo e(now()->format('Y-m-d')); ?>'" 
                            class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-medium transition-colors text-sm">
                        Today
                    </button>
                </form>
            </div>
        </div>

        <!-- Attendance Form -->
        <form id="attendanceForm" method="POST" action="<?php echo e(route('teacher.sections.attendance.store', $section)); ?>" class="space-y-6">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="date" value="<?php echo e($date); ?>">

            <?php if(!$activeSchoolYear): ?>
            <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock text-red-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-red-900">Attendance Recording Disabled</h3>
                    <p class="text-sm text-red-700">There is no active school year. Please contact the administrator.</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Bulk Actions Bar -->
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-4 border border-indigo-100 flex flex-wrap items-center justify-between gap-4 <?php echo e(!$activeSchoolYear ? 'opacity-50 pointer-events-none' : ''); ?>">
                <div class="flex items-center gap-3">
                    <i class="fas fa-magic text-indigo-600"></i>
                    <span class="font-medium text-slate-700">Quick Actions:</span>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="setAllStatus('present')" 
                            class="px-4 py-2 bg-white hover:bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-xl text-sm font-medium transition-all hover:shadow-md flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Mark All Present
                    </button>
                    <button type="button" onclick="setAllStatus('absent')" 
                            class="px-4 py-2 bg-white hover:bg-red-50 text-red-700 border border-red-200 rounded-xl text-sm font-medium transition-all hover:shadow-md flex items-center gap-2">
                        <i class="fas fa-times-circle"></i> Mark All Absent
                    </button>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">No.</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Student Information</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Learner's Reference Number</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-48">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-32">Quick Set</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="attendanceTable">
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $currentStatus = $attendance[$student->id]->status ?? 'present';
                                $statusColors = [
                                    'present' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'absent' => 'bg-red-50 text-red-700 border-red-200',
                                    'late' => 'bg-amber-50 text-amber-700 border-amber-200'
                                ];
                            ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group" data-student-id="<?php echo e($student->id); ?>">
                                <td class="px-6 py-4 text-sm text-slate-400 font-medium">
                                    <?php echo e($index + 1); ?>

                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/20">
                                            <?php echo e(strtoupper(substr($student->user->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($student->user->last_name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900 text-base">
                                                <?php echo e($student->user->last_name); ?>, <?php echo e($student->user->first_name); ?> <?php echo e($student->user->middle_name ? substr($student->user->middle_name, 0, 1) . '.' : ''); ?>

                                            </p>
                                            <p class="text-sm text-slate-500">
                                                <?php echo e($student->user->gender ?? 'Student'); ?> • Grade <?php echo e($section->gradeLevel->name ?? ''); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm font-mono">
                                        <?php echo e($student->lrn ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="relative">
                                        <select name="attendance[<?php echo e($student->id); ?>]" 
                                                class="attendance-select w-full appearance-none pl-10 pr-10 py-3 rounded-xl border-2 font-medium transition-all duration-200 focus:outline-none focus:ring-4 cursor-pointer <?php echo e($statusColors[$currentStatus]); ?> <?php echo e(!$activeSchoolYear ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                data-student="<?php echo e($student->id); ?>"
                                                onchange="updateSelectStyle(this)"
                                                <?php echo e(!$activeSchoolYear ? 'disabled' : ''); ?>>
                                            <option value="present" <?php echo e($currentStatus == 'present' ? 'selected' : ''); ?> class="bg-white text-emerald-700">Present</option>
                                            <option value="absent" <?php echo e($currentStatus == 'absent' ? 'selected' : ''); ?> class="bg-white text-red-700">Absent</option>
                                            <option value="late" <?php echo e($currentStatus == 'late' ? 'selected' : ''); ?> class="bg-white text-amber-700">Late</option>
                                        </select>
                                        <i class="fas fa-user-check absolute left-3.5 top-1/2 -translate-y-1/2 status-icon"></i>
                                        <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2 <?php echo e(!$activeSchoolYear ? 'opacity-50 pointer-events-none' : ''); ?>">
                                        <button type="button" onclick="quickSet(<?php echo e($student->id); ?>, 'present')" 
                                                class="w-10 h-10 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-600 transition-colors flex items-center justify-center tooltip" title="Present">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button type="button" onclick="quickSet(<?php echo e($student->id); ?>, 'absent')" 
                                                class="w-10 h-10 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition-colors flex items-center justify-center tooltip" title="Absent">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        <button type="button" onclick="quickSet(<?php echo e($student->id); ?>, 'late')" 
                                                class="w-10 h-10 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-600 transition-colors flex items-center justify-center tooltip" title="Late">
                                            <i class="fas fa-clock"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if(count($students) == 0): ?>
                <div class="p-12 text-center">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                        <i class="fas fa-users-slash text-slate-300 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No Students Found</h3>
                    <p class="text-slate-500">This section doesn't have any enrolled students yet.</p>
                </div>
                <?php endif; ?>

                <!-- Footer Actions -->
                <div class="bg-slate-50/80 border-t border-slate-100 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-slate-500">
                        <i class="fas fa-info-circle mr-2"></i>
                        <?php if($activeSchoolYear): ?>
                            Changes will be saved for <strong><?php echo e(\Carbon\Carbon::parse($date)->format('F d, Y')); ?></strong>
                        <?php else: ?>
                            <span class="text-red-600">Cannot save - no active school year</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex gap-3">
                        <a href="<?php echo e(route('teacher.sections.show', $section)); ?>" 
                           class="px-6 py-3 rounded-xl border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" id="saveAttendanceBtn"
                                class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/40 transform hover:-translate-y-0.5 transition-all flex items-center gap-2 <?php echo e(!$activeSchoolYear ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                <?php echo e(!$activeSchoolYear ? 'disabled' : ''); ?>>
                            <i class="fas fa-save"></i>
                            Save Attendance
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Attendance Summary Preview -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-user-check text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-emerald-700" id="countPresent">0</p>
                    <p class="text-sm text-emerald-600 font-medium">Present</p>
                </div>
            </div>
            
            <div class="bg-red-50/50 border border-red-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <i class="fas fa-user-times text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-700" id="countAbsent">0</p>
                    <p class="text-sm text-red-600 font-medium">Absent</p>
                </div>
            </div>
            
            <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <i class="fas fa-user-clock text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-amber-700" id="countLate">0</p>
                    <p class="text-sm text-amber-600 font-medium">Late</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }
    
    .tooltip {
        position: relative;
    }
    .tooltip:hover::after {
        content: attr(title);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        padding: 4px 8px;
        background: #1e293b;
        color: white;
        font-size: 12px;
        border-radius: 6px;
        white-space: nowrap;
        margin-bottom: 6px;
    }
    
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<script>
    // Sound effects using Web Audio API
    let audioContext = null;
    
    function initAudioContext() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }
        return audioContext;
    }
    
    function playSuccessSound() {
        const ctx = initAudioContext();
        const now = ctx.currentTime;
        const duration = 2.0;
        
        // Success - pleasant ascending chime with sustain
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, now); // A5
        osc.frequency.exponentialRampToValueAtTime(1760, now + 0.1); // A6
        
        gain.gain.setValueAtTime(0, now);
        gain.gain.linearRampToValueAtTime(0.25, now + 0.05);
        gain.gain.setValueAtTime(0.25, now + 0.1);
        gain.gain.exponentialRampToValueAtTime(0.001, now + duration);
        
        osc.start(now);
        osc.stop(now + duration);
    }
    
    function playErrorSound() {
        const ctx = initAudioContext();
        const now = ctx.currentTime;
        const duration = 2.0;
        
        // Error - triple beep with longer sustain
        const interval = 0.4;
        
        // First beep
        const osc1 = ctx.createOscillator();
        const gain1 = ctx.createGain();
        osc1.connect(gain1);
        gain1.connect(ctx.destination);
        
        osc1.type = 'sine';
        osc1.frequency.setValueAtTime(400, now);
        gain1.gain.setValueAtTime(0, now);
        gain1.gain.linearRampToValueAtTime(0.25, now + 0.02);
        gain1.gain.setValueAtTime(0.25, now + 0.1);
        gain1.gain.exponentialRampToValueAtTime(0.001, now + interval);
        osc1.start(now);
        osc1.stop(now + interval);
        
        // Second beep (lower)
        const osc2 = ctx.createOscillator();
        const gain2 = ctx.createGain();
        osc2.connect(gain2);
        gain2.connect(ctx.destination);
        
        osc2.type = 'sine';
        osc2.frequency.setValueAtTime(300, now + interval);
        gain2.gain.setValueAtTime(0, now + interval);
        gain2.gain.linearRampToValueAtTime(0.25, now + interval + 0.02);
        gain2.gain.setValueAtTime(0.25, now + interval + 0.1);
        gain2.gain.exponentialRampToValueAtTime(0.001, now + interval * 2);
        osc2.start(now + interval);
        osc2.stop(now + interval * 2);
        
        // Third beep (lowest) - sustains longer
        const osc3 = ctx.createOscillator();
        const gain3 = ctx.createGain();
        osc3.connect(gain3);
        gain3.connect(ctx.destination);
        
        osc3.type = 'sine';
        osc3.frequency.setValueAtTime(200, now + interval * 2);
        gain3.gain.setValueAtTime(0, now + interval * 2);
        gain3.gain.linearRampToValueAtTime(0.25, now + interval * 2 + 0.02);
        gain3.gain.setValueAtTime(0.25, now + interval * 2 + 0.3);
        gain3.gain.exponentialRampToValueAtTime(0.001, now + duration);
        osc3.start(now + interval * 2);
        osc3.stop(now + duration);
    }
    
    // Initialize audio on first user interaction
    document.addEventListener('click', function initAudio() {
        initAudioContext();
        document.removeEventListener('click', initAudio);
    }, { once: true });

    // Modal functions
    function showSaveModal(type, message) {
        const modal = document.getElementById('saveAttendanceModal');
        const loading = document.getElementById('saveModalLoading');
        const success = document.getElementById('saveModalSuccess');
        const error = document.getElementById('saveModalError');
        const content = document.getElementById('saveModalContent');
        
        modal.classList.remove('hidden');
        loading.classList.add('hidden');
        success.classList.add('hidden');
        error.classList.add('hidden');
        
        if (type === 'loading') {
            loading.classList.remove('hidden');
        } else if (type === 'success') {
            success.classList.remove('hidden');
            if (message) document.getElementById('successMessage').textContent = message;
            playSuccessSound();
        } else if (type === 'error') {
            error.classList.remove('hidden');
            content.classList.add('shake-animation');
            setTimeout(() => content.classList.remove('shake-animation'), 500);
            if (message) document.getElementById('errorMessage').textContent = message;
            playErrorSound();
        }
    }
    
    function closeSaveModal() {
        document.getElementById('saveAttendanceModal').classList.add('hidden');
    }

    // Form submission handler
    document.getElementById('attendanceForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        <?php if(!$activeSchoolYear): ?>
            showSaveModal('error', 'No active school year. Please contact the administrator.');
            return;
        <?php endif; ?>
        
        showSaveModal('loading');
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            // Always try to parse as JSON first
            let data;
            const contentType = response.headers.get('content-type');
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                // If not JSON, check if it's a redirect (success) or error page
                const text = await response.text();
                if (text.includes('success') || text.includes('Success') || response.redirected) {
                    data = { success: true, message: 'Attendance saved successfully!' };
                } else if (text.includes('error') || text.includes('Error') || text.includes('No active school year')) {
                    data = { success: false, message: 'Failed to save attendance. Date is outside the active school year.' };
                } else {
                    data = { success: response.ok, message: response.ok ? 'Saved!' : 'Error saving attendance.' };
                }
            }
            
            // ONLY show success if data.success is explicitly true
            if (data.success === true) {
                showSaveModal('success', data.message || 'Attendance saved successfully!');
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show error for any case where success is not true
                showSaveModal('error', data.message || 'Failed to save attendance. The date may be outside the active school year.');
            }
        } catch (error) {
            console.error('Error:', error);
            showSaveModal('error', 'Network error. Please check your connection and try again.');
        }
    });

    // Update select styling
    function updateSelectStyle(select) {
        const value = select.value;
        const icon = select.parentElement.querySelector('.status-icon');
        
        select.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
        select.classList.remove('bg-red-50', 'text-red-700', 'border-red-200');
        select.classList.remove('bg-amber-50', 'text-amber-700', 'border-amber-200');
        icon.classList.remove('text-emerald-600', 'text-red-600', 'text-amber-600');
        
        if (value === 'present') {
            select.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            icon.className = 'fas fa-check-circle absolute left-3.5 top-1/2 -translate-y-1/2 status-icon text-emerald-600';
        } else if (value === 'absent') {
            select.classList.add('bg-red-50', 'text-red-700', 'border-red-200');
            icon.className = 'fas fa-times-circle absolute left-3.5 top-1/2 -translate-y-1/2 status-icon text-red-600';
        } else if (value === 'late') {
            select.classList.add('bg-amber-50', 'text-amber-700', 'border-amber-200');
            icon.className = 'fas fa-clock absolute left-3.5 top-1/2 -translate-y-1/2 status-icon text-amber-600';
        }
        
        updateCounts();
    }

    function quickSet(studentId, status) {
        const select = document.querySelector(`select[name="attendance[${studentId}]"]`);
        if (select) {
            select.value = status;
            updateSelectStyle(select);
            
            const row = select.closest('tr');
            row.style.backgroundColor = status === 'present' ? '#ecfdf5' : status === 'absent' ? '#fef2f2' : '#fffbeb';
            setTimeout(() => {
                row.style.backgroundColor = '';
                row.style.transition = 'background-color 0.5s ease';
            }, 300);
        }
    }

    function setAllStatus(status) {
        const selects = document.querySelectorAll('.attendance-select');
        selects.forEach((select, index) => {
            setTimeout(() => {
                select.value = status;
                updateSelectStyle(select);
            }, index * 20);
        });
    }

    function updateCounts() {
        const selects = document.querySelectorAll('.attendance-select');
        let present = 0, absent = 0, late = 0;
        
        selects.forEach(select => {
            if (select.value === 'present') present++;
            else if (select.value === 'absent') absent++;
            else if (select.value === 'late') late++;
        });
        
        animateValue('countPresent', parseInt(document.getElementById('countPresent').textContent), present, 300);
        animateValue('countAbsent', parseInt(document.getElementById('countAbsent').textContent), absent, 300);
        animateValue('countLate', parseInt(document.getElementById('countLate').textContent), late, 300);
    }

    function animateValue(id, start, end, duration) {
        const obj = document.getElementById(id);
        const range = end - start;
        const minTimer = 50;
        let stepTime = Math.abs(Math.floor(duration / range));
        stepTime = Math.max(stepTime, minTimer);
        
        let startTime = new Date().getTime();
        let endTime = startTime + duration;
        let timer;
        
        function run() {
            let now = new Date().getTime();
            let remaining = Math.max((endTime - now) / duration, 0);
            let value = Math.round(end - (remaining * range));
            obj.textContent = value;
            if (value == end) {
                clearInterval(timer);
            }
        }
        
        timer = setInterval(run, stepTime);
        run();
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.attendance-select').forEach(select => {
            updateSelectStyle(select);
        });
        updateCounts();
    });

</script>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\attendance\index.blade.php ENDPATH**/ ?>