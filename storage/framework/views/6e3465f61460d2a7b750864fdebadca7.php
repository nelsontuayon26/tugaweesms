<?php
$teacher = auth()->user()->teacher;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Received Announcements - Teacher Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="{ mobileOpen: false }" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<div class="flex h-screen">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Received Announcements</h1>
                <p class="text-sm text-slate-500 mt-0.5">Announcements addressed to you</p>
            </div>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6">
            <?php if(session('success')): ?>
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($announcements->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bullhorn text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700">No announcements yet</h3>
                    <p class="text-sm text-slate-400 mt-1 max-w-sm">When admin or other teachers send you an announcement, it will appear here.</p>
                </div>
            <?php else: ?>
                <div class="space-y-4 max-w-4xl mx-auto">
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow <?php echo e($announcement->pinned ? 'ring-1 ring-amber-200' : ''); ?>">
                            <div class="p-5">
                                <div class="flex items-start gap-4">
                                    
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                        <?php echo e($announcement->priority === 'urgent' ? 'bg-rose-100 text-rose-600' : ($announcement->priority === 'important' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500')); ?>">
                                        <i class="fas <?php echo e($announcement->priorityIcon()); ?>"></i>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <?php if($announcement->pinned): ?>
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                    <i class="fas fa-thumbtack mr-1"></i>Pinned
                                                </span>
                                            <?php endif; ?>
                                            <span class="px-2 py-0.5 bg-<?php echo e($announcement->priorityColor()); ?>-100 text-<?php echo e($announcement->priorityColor()); ?>-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                <?php echo e($announcement->priority); ?>

                                            </span>
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                <?php echo e($announcement->target === 'all' ? 'Teachers & Pupils' : ($announcement->target === 'students' ? 'Pupils' : 'Teachers')); ?>

                                            </span>
                                        </div>

                                        <h3 class="font-semibold text-slate-900 mb-1"><?php echo e($announcement->title); ?></h3>
                                        <p class="text-sm text-slate-500 line-clamp-2"><?php echo e(strip_tags($announcement->message)); ?></p>

                                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-user"></i>
                                                <?php echo e($announcement->author?->name ?? 'Unknown'); ?>

                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-clock"></i>
                                                <?php echo e($announcement->created_at->diffForHumans()); ?>

                                            </span>
                                            <?php if($announcement->attachments->count() > 0): ?>
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-paperclip"></i>
                                                    <?php echo e($announcement->attachments->count()); ?> attachment<?php echo e($announcement->attachments->count() > 1 ? 's' : ''); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="px-5 pb-5">
                                <div class="prose prose-sm max-w-none text-slate-600 bg-slate-50 rounded-lg p-4">
                                    <?php echo e($announcement->message); ?>

                                </div>
                                <?php if($announcement->attachments->count() > 0): ?>
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <?php $__currentLoopData = $announcement->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="<?php echo e($att->url()); ?>" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-xs text-slate-600 hover:bg-slate-200 transition-colors border border-slate-200">
                                                <i class="fas fa-file"></i>
                                                <span class="truncate max-w-[150px]"><?php echo e($att->file_name); ?></span>
                                            </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-6">
                    <?php echo e($announcements->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\announcements\received.blade.php ENDPATH**/ ?>