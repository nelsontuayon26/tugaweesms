<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Pending Tasks Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-tasks text-indigo-500"></i>
            Pending Tasks
        </h3>
        <div class="space-y-3">
            <?php $__currentLoopData = $pendingTasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($task['route']); ?>" class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-<?php echo e($task['color']); ?>-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-<?php echo e($task['color']); ?>-100 flex items-center justify-center">
                            <i class="fas <?php echo e($task['icon']); ?> text-<?php echo e($task['color']); ?>-600"></i>
                        </div>
                        <span class="font-medium text-slate-700 group-hover:text-<?php echo e($task['color']); ?>-700"><?php echo e($task['title']); ?></span>
                    </div>
                    <?php if($task['count'] > 0): ?>
                        <span class="px-3 py-1 bg-<?php echo e($task['color']); ?>-100 text-<?php echo e($task['color']); ?>-700 rounded-full text-sm font-bold">
                            <?php echo e($task['count']); ?>

                        </span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Recent Activity Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i>
            Recent Activity
        </h3>
        <div class="space-y-3 max-h-64 overflow-y-auto">
            <?php $__empty_1 = true; $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50">
                    <div class="w-8 h-8 rounded-full bg-<?php echo e($activity->action_color); ?>-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas <?php echo e($activity->action_icon); ?> text-<?php echo e($activity->action_color); ?>-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700 truncate"><?php echo e($activity->description); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e($activity->user?->name ?? 'System'); ?> • <?php echo e($activity->created_at->diffForHumans()); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-slate-400 text-sm text-center py-4">No recent activity</p>
            <?php endif; ?>
        </div>
        <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="block text-center text-sm text-indigo-600 hover:text-indigo-700 mt-3 pt-3 border-t border-slate-100">
            View All Activity
        </a>
    </div>

    <!-- Today's Birthdays Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-birthday-cake text-rose-500"></i>
            Today's Birthdays
            <span class="text-xs font-normal text-slate-400"><?php echo e(now()->format('M d')); ?></span>
        </h3>
        <div class="space-y-3">
            <?php $__empty_1 = true; $__currentLoopData = $todaysBirthdays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-rose-50">
                    <img src="<?php echo e($student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=random'); ?>" 
                         alt="" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-700 truncate"><?php echo e($student->full_name); ?></p>
                        <p class="text-xs text-slate-400"><?php echo e($student->gradeLevel?->name ?? 'N/A'); ?></p>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar text-slate-400"></i>
                    </div>
                    <p class="text-slate-400 text-sm">No birthdays today</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\components\dashboard-widgets.blade.php ENDPATH**/ ?>