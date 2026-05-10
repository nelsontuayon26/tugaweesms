<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo $__env->make('partials.pwa-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Dashboard - <?php echo e(auth()->user()->student->first_name ?? 'Student'); ?></title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; -webkit-tap-highlight-color: transparent; }
        body { background: #f8fafc; overscroll-behavior: none; }
        .safe-top { padding-top: env(safe-area-inset-top); }
        .safe-bottom { padding-bottom: env(safe-area-inset-bottom); }
        [x-cloak] { display: none !important; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="antialiased">
    <div x-data="studentDashboard()" x-init="init()" class="min-h-screen bg-slate-50 pb-24">
        
        
        <div class="bg-gradient-to-br from-blue-600 to-purple-700 text-white safe-top">
            <div class="px-5 pt-6 pb-8">
                
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-blue-100 text-sm">Good <?php echo e($greeting ?? 'morning'); ?>,</p>
                        <h1 class="text-2xl font-bold"><?php echo e(auth()->user()->first_name ?? 'Student'); ?></h1>
                    </div>
                    <div class="flex items-center space-x-3">
                        
                        <?php echo $__env->make('components.notification-bell', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        
                        <a href="<?php echo e(route('student.profile')); ?>" class="w-10 h-10 rounded-full bg-white/30 flex items-center justify-center overflow-hidden">
                            <?php if(auth()->user()->photo): ?>
                                <img src="<?php echo e(profile_photo_url(auth()->user()->photo)); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="font-bold"><?php echo e(strtoupper(substr(auth()->user()->first_name ?? 'S', 0, 1))); ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
                
                
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-3 text-center">
                        <p class="text-blue-100 text-xs mb-1">Attendance</p>
                        <p class="text-2xl font-bold"><?php echo e($attendanceRate ?? '95'); ?>%</p>
                    </div>
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-3 text-center">
                        <p class="text-blue-100 text-xs mb-1">Average</p>
                        <p class="text-2xl font-bold"><?php echo e($averageGrade ?? '92'); ?></p>
                    </div>
                    <div class="bg-white/20 backdrop-blur rounded-2xl p-3 text-center">
                        <p class="text-blue-100 text-xs mb-1">Rank</p>
                        <p class="text-2xl font-bold"><?php echo e($classRank ?? '5'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="px-4 -mt-4">
            
            
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-slate-800">Today's Classes</h2>
                    <span class="text-sm text-slate-500"><?php echo e(now()->format('l, M d')); ?></span>
                </div>
                <div class="space-y-2">
                    <?php $__empty_1 = true; $__currentLoopData = $todaySchedule ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center space-x-3 p-3 rounded-xl <?php echo e($subject['status'] === 'completed' ? 'bg-slate-50' : 'bg-blue-50'); ?>">
                        <div class="w-12 h-12 rounded-xl <?php echo e($subject['status'] === 'completed' ? 'bg-slate-200' : 'bg-blue-500'); ?> flex items-center justify-center text-white font-bold text-sm">
                            <?php echo e(substr($subject['name'], 0, 2)); ?>

                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800"><?php echo e($subject['name']); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($subject['time']); ?></p>
                        </div>
                        <?php if($subject['status'] === 'completed'): ?>
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        <?php elseif($subject['status'] === 'current'): ?>
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-6 text-slate-400">
                        <i class="fas fa-calendar-day text-3xl mb-2"></i>
                        <p>No classes today</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-slate-800">Recent Grades</h2>
                    <a href="<?php echo e(route('student.grades')); ?>" class="text-sm text-blue-600 font-medium">View All</a>
                </div>
                <div class="space-y-2">
                    <?php $__empty_1 = true; $__currentLoopData = $recentGrades ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                        <div>
                            <p class="font-semibold text-slate-800"><?php echo e($grade['subject']); ?></p>
                            <p class="text-sm text-slate-500"><?php echo e($grade['type']); ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xl font-bold <?php echo e($grade['score'] >= 75 ? 'text-green-600' : 'text-red-600'); ?>"><?php echo e($grade['score']); ?>%</p>
                            <p class="text-xs text-slate-400"><?php echo e($grade['date']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 text-slate-400">
                        <p>No recent grades</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="grid grid-cols-4 gap-3 mb-4">
                <a href="<?php echo e(route('student.grades')); ?>" class="flex flex-col items-center p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-2">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-700">Grades</span>
                </a>
                <a href="<?php echo e(route('student.attendance')); ?>" class="flex flex-col items-center p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-2">
                        <i class="fas fa-clipboard-check text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-700">Attendance</span>
                </a>
                <a href="<?php echo e(route('student.announcements')); ?>" class="flex flex-col items-center p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mb-2">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-700">News</span>
                </a>
                <a href="<?php echo e(route('student.messenger')); ?>" class="flex flex-col items-center p-3 bg-white rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mb-2">
                        <i class="fas fa-comment-dots text-xl"></i>
                    </div>
                    <span class="text-xs font-medium text-slate-700">Messages</span>
                </a>
            </div>

            
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-bold text-slate-800">Announcements</h2>
                    <a href="<?php echo e(route('student.announcements')); ?>" class="text-sm text-blue-600 font-medium">View All</a>
                </div>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $recentAnnouncements ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a href="<?php echo e(route('student.announcements.show', $announcement['id'])); ?>" class="block p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                        <div class="flex items-start space-x-3">
                            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-slate-800 truncate"><?php echo e($announcement['title']); ?></p>
                                <p class="text-sm text-slate-500 line-clamp-2"><?php echo e($announcement['excerpt']); ?></p>
                                <p class="text-xs text-slate-400 mt-1"><?php echo e($announcement['date']); ?></p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-4 text-slate-400">
                        <p>No announcements</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            
            <?php if(!empty($upcomingAssignments)): ?>
            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4">
                <h2 class="font-bold text-slate-800 mb-3">Upcoming Assignments</h2>
                <div class="space-y-2">
                    <?php $__currentLoopData = $upcomingAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center space-x-3 p-3 bg-red-50 rounded-xl border border-red-100">
                        <div class="w-10 h-10 bg-red-100 text-red-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-800"><?php echo e($assignment['title']); ?></p>
                            <p class="text-sm text-red-600">Due <?php echo e($assignment['due_date']); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 safe-bottom z-40">
            <div class="flex justify-around py-2">
                <a href="<?php echo e(route('student.dashboard')); ?>" class="flex flex-col items-center p-2 text-blue-600">
                    <i class="fas fa-home text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Home</span>
                </a>
                <a href="<?php echo e(route('student.subjects')); ?>" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-book text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Subjects</span>
                </a>
                <a href="<?php echo e(route('student.messenger')); ?>" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-comment text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Chat</span>
                </a>
                <a href="<?php echo e(route('student.profile')); ?>" class="flex flex-col items-center p-2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-user text-xl mb-1"></i>
                    <span class="text-[10px] font-medium">Profile</span>
                </a>
            </div>
        </div>

        
        <div x-show="showNotifications" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-full"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-full"
             class="fixed inset-0 z-50 bg-black/50"
             x-cloak>
            <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl max-h-[80vh] overflow-hidden" @click.stop>
                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-slate-800">Notifications</h2>
                    <button @click="showNotifications = false" class="p-2 hover:bg-slate-100 rounded-full">
                        <i class="fas fa-times text-slate-500"></i>
                    </button>
                </div>
                <div class="overflow-y-auto max-h-[60vh] p-4 space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-3 bg-slate-50 rounded-xl <?php echo e($notification['read'] ? '' : 'border-l-4 border-blue-500'); ?>">
                        <p class="font-semibold text-slate-800"><?php echo e($notification['title']); ?></p>
                        <p class="text-sm text-slate-600"><?php echo e($notification['message']); ?></p>
                        <p class="text-xs text-slate-400 mt-1"><?php echo e($notification['time']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-bell-slash text-4xl mb-2"></i>
                        <p>No notifications yet</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function studentDashboard() {
            return {
                showNotifications: false,
                
                init() {
                    // Refresh data periodically
                    setInterval(() => {
                        this.refreshData();
                    }, 60000); // Every minute
                },
                
                async refreshData() {
                    // Could fetch new notifications, grades, etc.
                }
            };
        }
    </script>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\dashboard-mobile.blade.php ENDPATH**/ ?>