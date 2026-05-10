<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($section->name); ?> - My Section</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 font-sans text-slate-800" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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

<div class="flex min-h-screen">
    
    <!-- Sidebar -->
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-72 transition-all duration-300">
        
        <!-- Top Navigation -->
        <header class="bg-white/95 backdrop-blur-xl sticky top-0 z-30 border-b border-slate-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fas fa-bars text-slate-600"></i>
                    </button>
                    <div>
                        <h1 class="text-xl font-bold text-slate-800">My Section</h1>
                        <p class="text-sm text-slate-500"><?php echo e($section->name); ?></p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-2">
                    <a href="<?php echo e(route('teacher.sections.index')); ?>" class="text-slate-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <h2 class="text-2xl font-bold text-slate-800"><?php echo e($section->name); ?></h2>
                </div>
                <p class="text-slate-500 ml-8"><?php echo e($section->gradeLevel->name ?? 'Grade Level'); ?> | School Year <?php echo e($section->schoolYear->name ?? 'N/A'); ?></p>
            </div>

            <!-- Quick Stats -->
            <?php
                $studentCount = $section->students->count() ?? 0;
                $todayAttendance = $section->students->flatMap->attendances->where('date', now()->format('Y-m-d'));
                $presentToday = $todayAttendance->where('status', 'present')->count();
                $attendanceRate = $studentCount > 0 ? round(($presentToday / $studentCount) * 100) : 0;
            ?>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800"><?php echo e($studentCount); ?></p>
                            <p class="text-xs text-slate-500">Students</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800"><?php echo e($presentToday); ?></p>
                            <p class="text-xs text-slate-500">Present Today</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-percentage text-amber-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800"><?php echo e($attendanceRate); ?>%</p>
                            <p class="text-xs text-slate-500">Attendance Rate</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-door-open text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800"><?php echo e($section->capacity ?? 'N/A'); ?></p>
                            <p class="text-xs text-slate-500">Capacity</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Action Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <!-- Attendance Card -->
                <a href="<?php echo e(route('teacher.sections.attendance', $section)); ?>" class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-amber-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center group-hover:bg-amber-500 transition-colors">
                            <i class="fas fa-calendar-check text-amber-600 text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Attendance</h3>
                            <p class="text-sm text-slate-500">Daily attendance records</p>
                        </div>
                    </div>
                </a>

                <!-- Grades Card -->
                <a href="<?php echo e(route('teacher.sections.grades', $section)); ?>" class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-emerald-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center group-hover:bg-emerald-500 transition-colors">
                            <i class="fas fa-clipboard-list text-emerald-600 text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Grades</h3>
                            <p class="text-sm text-slate-500">Manage student grades</p>
                        </div>
                    </div>
                </a>

                <!-- Core Values Card -->
                <a href="<?php echo e(route('teacher.sections.core-values.index', $section)); ?>" class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md hover:border-rose-200 transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-100 rounded-lg flex items-center justify-center group-hover:bg-rose-500 transition-colors">
                            <i class="fas fa-heart text-rose-600 text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-slate-800">Core Values</h3>
                            <p class="text-sm text-slate-500">Behavior & character ratings</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Section Info -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Section Information</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-slate-500 text-xs mb-1">Grade Level</p>
                        <p class="font-medium text-slate-800"><?php echo e($section->gradeLevel->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-slate-500 text-xs mb-1">School Year</p>
                        <p class="font-medium text-slate-800"><?php echo e($section->schoolYear->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-slate-500 text-xs mb-1">Adviser</p>
                        <p class="font-medium text-slate-800"><?php echo e($section->teacher->full_name ?? 'N/A'); ?></p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <p class="text-slate-500 text-xs mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($section->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($section->is_active ? 'bg-emerald-500' : 'bg-red-500'); ?>"></span>
                            <?php echo e($section->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\sections\show.blade.php ENDPATH**/ ?>