<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Sections - Teacher Portal</title>
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
                        <h1 class="text-xl font-bold text-slate-800">My Sections</h1>
                        <p class="text-sm text-slate-500">Select a section to manage</p>
                    </div>
                </div>
            </div>
        </header>

        <div class="p-6">
            <?php if($sections->isEmpty()): ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chalkboard text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700 mb-2">No Sections Assigned</h3>
                    <p class="text-slate-500">You don't have any active sections assigned to you.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('teacher.sections.show', $section)); ?>" 
                           class="block bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-all hover:border-indigo-200">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-users-class text-indigo-600 text-lg"></i>
                                </div>
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                    <?php echo e($section->gradeLevel->name ?? 'N/A'); ?>

                                </span>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg mb-1"><?php echo e($section->name); ?></h3>
                            <p class="text-sm text-slate-500 mb-4">School Year <?php echo e($section->schoolYear->name ?? 'N/A'); ?></p>
                            <div class="flex items-center gap-4 text-sm text-slate-600">
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-users text-xs text-slate-400"></i>
                                    <?php echo e($section->students->count() ?? 0); ?> Students
                                </span>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\sections\index.blade.php ENDPATH**/ ?>