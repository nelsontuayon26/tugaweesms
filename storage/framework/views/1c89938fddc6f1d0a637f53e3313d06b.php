<?php $__env->startSection('title', 'Assignments'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Assignments</h1>
            <p class="text-slate-500"><?php echo e($section->name); ?> • <?php echo e($section->gradeLevel?->name); ?></p>
        </div>
        <a href="<?php echo e(route('teacher.assignments.create', $section)); ?>" 
           class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>New Assignment
        </a>
    </div>

    <!-- Assignments List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="divide-y divide-slate-100">
            <?php $__empty_1 = true; $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-5 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-<?php echo e($assignment->type_color); ?>-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas <?php echo e($assignment->type_icon); ?> text-<?php echo e($assignment->type_color); ?>-600 text-xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-slate-800"><?php echo e($assignment->title); ?></h3>
                                    <?php echo $assignment->status_badge; ?>

                                    <?php if($assignment->is_overdue): ?>
                                        <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded-full text-xs font-medium">Overdue</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-sm text-slate-500 mb-2">
                                    <?php echo e($assignment->subject?->name); ?> • Due <?php echo e($assignment->due_date->format('M d, Y')); ?>

                                    <?php if($assignment->due_time): ?>
                                        at <?php echo e($assignment->due_time->format('h:i A')); ?>

                                    <?php endif; ?>
                                </p>
                                <div class="flex items-center gap-4 text-sm">
                                    <?php $stats = $assignment->submission_stats; ?>
                                    <span class="text-slate-600">
                                        <i class="fas fa-users mr-1 text-slate-400"></i>
                                        <?php echo e($stats['submitted']); ?>/<?php echo e($stats['total']); ?> submitted
                                    </span>
                                    <span class="text-slate-600">
                                        <i class="fas fa-check-circle mr-1 text-slate-400"></i>
                                        <?php echo e($stats['graded']); ?> graded
                                    </span>
                                    <span class="text-emerald-600 font-medium">
                                        <?php echo e($stats['submission_rate']); ?>% submission rate
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('teacher.assignments.show', [$section, $assignment])); ?>" 
                               class="px-3 py-2 bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <form action="<?php echo e(route('teacher.assignments.destroy', [$section, $assignment])); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" onclick="return confirm('Delete this assignment?')" 
                                        class="px-3 py-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clipboard-list text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500 font-medium">No assignments yet</p>
                    <p class="text-slate-400 text-sm mt-1">Create your first assignment to get started</p>
                </div>
            <?php endif; ?>
        </div>

        <?php if($assignments->hasPages()): ?>
            <div class="px-5 py-3 border-t border-slate-200">
                <?php echo e($assignments->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\assignments\index.blade.php ENDPATH**/ ?>