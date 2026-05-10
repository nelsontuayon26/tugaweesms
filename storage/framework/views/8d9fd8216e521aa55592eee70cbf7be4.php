<?php $__env->startSection('title', 'Performance Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Class Performance Analytics</h1>
            <p class="text-slate-500"><?php echo e($section->name); ?> • <?php echo e($section->gradeLevel?->name); ?></p>
        </div>
        <form method="GET" class="flex items-center gap-3">
            <select name="quarter" onchange="this.form.submit()"
                    class="px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <?php for($i = 1; $i <= 4; $i++): ?>
                    <option value="<?php echo e($i); ?>" <?php echo e($quarter == $i ? 'selected' : ''); ?>>Quarter <?php echo e($i); ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500 mb-1">Class Average</p>
            <p class="text-3xl font-bold <?php echo e($analytics['class_average'] >= 75 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                <?php echo e(number_format($analytics['class_average'], 1)); ?>

            </p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500 mb-1">Passing Rate</p>
            <p class="text-3xl font-bold <?php echo e($analytics['passing_rate'] >= 75 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                <?php echo e($analytics['passing_rate']); ?>%
            </p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500 mb-1">Highest Grade</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo e(number_format($analytics['highest_grade'], 1)); ?></p>
        </div>
        <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500 mb-1">Lowest Grade</p>
            <p class="text-3xl font-bold text-amber-600"><?php echo e(number_format($analytics['lowest_grade'], 1)); ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Grade Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-bold text-slate-900 mb-4">Grade Distribution</h3>
            <div class="space-y-3">
                <?php
                    $distributionLabels = [
                        'excellent' => ['label' => 'Excellent (90-100)', 'color' => 'emerald'],
                        'very_good' => ['label' => 'Very Good (85-89)', 'color' => 'blue'],
                        'good' => ['label' => 'Good (80-84)', 'color' => 'indigo'],
                        'satisfactory' => ['label' => 'Satisfactory (75-79)', 'color' => 'amber'],
                        'needs_improvement' => ['label' => 'Fair (70-74)', 'color' => 'orange'],
                        'poor' => ['label' => 'Poor (Below 70)', 'color' => 'rose'],
                    ];
                    $totalGrades = array_sum($analytics['grade_distribution']);
                ?>
                <?php $__currentLoopData = $distributionLabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $count = $analytics['grade_distribution'][$key]; ?>
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-slate-600"><?php echo e($info['label']); ?></span>
                            <span class="font-semibold text-slate-800"><?php echo e($count); ?></span>
                        </div>
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-<?php echo e($info['color']); ?>-500 rounded-full" 
                                 style="width: <?php echo e($totalGrades > 0 ? ($count / $totalGrades) * 100 : 0); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-amber-500"></i>
                Top Performers
            </h3>
            <?php if($analytics['top_performers']->isNotEmpty()): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $analytics['top_performers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-lg">
                            <div class="w-8 h-8 rounded-full <?php echo e($index < 3 ? 'bg-amber-100 text-amber-600' : 'bg-slate-200 text-slate-600'); ?> flex items-center justify-center font-bold text-sm">
                                <?php echo e($index + 1); ?>

                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800"><?php echo e($student['name']); ?></p>
                            </div>
                            <span class="font-bold text-emerald-600"><?php echo e(number_format($student['average'], 1)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="text-slate-400 text-center py-8">No data available</p>
            <?php endif; ?>
        </div>

        <!-- Needs Improvement -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-rose-500"></i>
                Needs Attention
            </h3>
            <?php if($analytics['needs_help']->isNotEmpty()): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $analytics['needs_help']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center gap-3 p-3 bg-rose-50 rounded-lg">
                            <div class="w-8 h-8 rounded-full bg-rose-200 flex items-center justify-center">
                                <i class="fas fa-user text-rose-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-slate-800"><?php echo e($student['name']); ?></p>
                            </div>
                            <span class="font-bold text-rose-600"><?php echo e(number_format($student['average'], 1)); ?></span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                    <p class="text-sm text-amber-800">
                        <i class="fas fa-info-circle mr-1"></i>
                        Consider scheduling parent conferences for these students.
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <p class="text-emerald-600 font-medium">All students are passing!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Subject Averages -->
    <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4">Subject Performance</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Subject</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Average</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Highest</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Lowest</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $analytics['subject_averages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="px-4 py-3 font-medium text-slate-800"><?php echo e($subject['subject']); ?></td>
                            <td class="px-4 py-3 text-center font-bold <?php echo e($subject['average'] >= 75 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                <?php echo e(number_format($subject['average'], 1)); ?>

                            </td>
                            <td class="px-4 py-3 text-center text-slate-600"><?php echo e(number_format($subject['highest'], 1)); ?></td>
                            <td class="px-4 py-3 text-center text-slate-600"><?php echo e(number_format($subject['lowest'], 1)); ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php if($subject['average'] >= 75): ?>
                                    <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">On Track</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-medium">Needs Focus</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                No grade data available for this quarter
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\analytics\index.blade.php ENDPATH**/ ?>