<?php $__env->startSection('title', 'Report Card Preview - ' . $student->user->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Report Card Preview</h1>
            <p class="text-gray-600"><?php echo e($student->user->name); ?></p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="<?php echo e(route('teacher.report-cards.index', $section)); ?>" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to List
            </a>
            <form method="POST" action="<?php echo e(route('teacher.report-cards.generate', [$section, $student])); ?>" class="inline">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="grading_period" value="<?php echo e($gradingPeriod); ?>">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Download PDF
                </button>
            </form>
        </div>
    </div>

    <!-- Report Card Preview -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="max-width: 800px; margin: 0 auto;">
        <div class="p-8 border-2 border-indigo-900">
            <!-- Header -->
            <div class="text-center border-b-4 border-double border-indigo-900 pb-4 mb-6">
                <div class="text-2xl font-bold text-indigo-900 uppercase"><?php echo e(config('app.school_name', 'Department of Education')); ?></div>
                <div class="text-sm text-gray-600"><?php echo e(config('app.school_address', 'Republic of the Philippines')); ?></div>
                <div class="text-xl font-bold text-indigo-900 mt-2">REPORT CARD</div>
                <div class="text-sm text-gray-600"><?php echo e($gradingPeriod); ?> Grading Period</div>
            </div>

            <!-- Student Info -->
            <div class="grid grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 border border-gray-200">
                <div>
                    <div class="text-xs text-gray-500 uppercase">Student Name</div>
                    <div class="font-bold text-gray-900"><?php echo e($student->user->last_name); ?>, <?php echo e($student->user->first_name); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase">LRN</div>
                    <div class="font-bold text-gray-900"><?php echo e($student->lrn ?? 'N/A'); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase">Grade & Section</div>
                    <div class="font-bold text-gray-900"><?php echo e($section->gradeLevel->name ?? ''); ?> - <?php echo e($section->name); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase">School Year</div>
                    <div class="font-bold text-gray-900"><?php echo e($schoolYear->name ?? date('Y') . '-' . (date('Y') + 1)); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase">Class Adviser</div>
                    <div class="font-bold text-gray-900"><?php echo e($adviser->user->name ?? 'TBD'); ?></div>
                </div>
                <div>
                    <div class="text-xs text-gray-500 uppercase">Gender</div>
                    <div class="font-bold text-gray-900"><?php echo e(ucfirst($student->user->gender ?? 'N/A')); ?></div>
                </div>
            </div>

            <!-- Grades -->
            <div class="mb-6">
                <div class="text-lg font-bold text-indigo-900 border-b-2 border-indigo-900 pb-1 mb-3">ACADEMIC PERFORMANCE</div>
                <table class="w-full border-collapse text-sm">
                    <thead>
                        <tr class="bg-indigo-900 text-white">
                            <th class="p-2 text-left">Subject</th>
                            <th class="p-2 text-center" style="width: 100px;">Grade</th>
                            <th class="p-2 text-center" style="width: 100px;">Remarks</th>
                            <th class="p-2">Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="border-b border-gray-200">
                                <td class="p-2"><?php echo e($grade->subject->name ?? 'Unknown Subject'); ?></td>
                                <td class="p-2 text-center font-bold <?php echo e($grade->final_grade >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                    <?php echo e(number_format($grade->final_grade, 0)); ?>

                                </td>
                                <td class="p-2 text-center">
                                    <?php echo e($grade->final_grade >= 75 ? 'Passed' : 'Failed'); ?>

                                </td>
                                <td class="p-2 text-gray-600"><?php echo e($grade->teacher->user->name ?? 'TBD'); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">No grades recorded for this grading period.</td>
                            </tr>
                        <?php endif; ?>
                        <tr class="bg-gray-100 font-bold">
                            <td class="p-2">GENERAL AVERAGE</td>
                            <td class="p-2 text-center <?php echo e($generalAverage >= 75 ? 'text-green-600' : 'text-red-600'); ?>">
                                <?php echo e(number_format($generalAverage, 2)); ?>

                            </td>
                            <td class="p-2 text-center">
                                <?php echo e($generalAverage >= 75 ? 'Promoted' : 'Retention'); ?>

                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Core Values -->
            <div class="mb-6">
                <div class="text-lg font-bold text-indigo-900 border-b-2 border-indigo-900 pb-1 mb-3">CORE VALUES & BEHAVIOR</div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex justify-between p-2 border-b border-gray-200">
                        <span>Maka-Diyos</span>
                        <span class="font-bold text-indigo-900"><?php echo e($coreValues->maka_diyos ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between p-2 border-b border-gray-200">
                        <span>Maka-Tao</span>
                        <span class="font-bold text-indigo-900"><?php echo e($coreValues->maka_tao ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between p-2 border-b border-gray-200">
                        <span>Maka-Kalikasan</span>
                        <span class="font-bold text-indigo-900"><?php echo e($coreValues->maka_kalikasan ?? 'N/A'); ?></span>
                    </div>
                    <div class="flex justify-between p-2 border-b border-gray-200">
                        <span>Maka-Bansa</span>
                        <span class="font-bold text-indigo-900"><?php echo e($coreValues->maka_bansa ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Attendance -->
            <div class="mb-6">
                <div class="text-lg font-bold text-indigo-900 border-b-2 border-indigo-900 pb-1 mb-3">ATTENDANCE SUMMARY</div>
                <div class="grid grid-cols-4 gap-4 text-center">
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <div class="text-2xl font-bold text-indigo-900"><?php echo e($attendanceSummary['days_present'] ?? 0); ?></div>
                        <div class="text-xs text-gray-500">Days Present</div>
                    </div>
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <div class="text-2xl font-bold text-indigo-900"><?php echo e($attendanceSummary['days_absent'] ?? 0); ?></div>
                        <div class="text-xs text-gray-500">Days Absent</div>
                    </div>
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <div class="text-2xl font-bold text-indigo-900"><?php echo e($attendanceSummary['days_late'] ?? 0); ?></div>
                        <div class="text-xs text-gray-500">Times Late</div>
                    </div>
                    <div class="p-3 bg-gray-50 border border-gray-200">
                        <div class="text-2xl font-bold text-indigo-900"><?php echo e($attendanceSummary['total_school_days'] ?? 0); ?></div>
                        <div class="text-xs text-gray-500">Total School Days</div>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="mb-6">
                <div class="text-lg font-bold text-indigo-900 border-b-2 border-indigo-900 pb-1 mb-3">TEACHER'S REMARKS</div>
                <div class="p-4 bg-gray-50 border border-gray-200 min-h-[80px]">
                    <?php echo e($coreValues->remarks ?? 'No remarks recorded for this grading period.'); ?>

                </div>
            </div>

            <!-- Signatures -->
            <div class="grid grid-cols-2 gap-8 mt-8">
                <div class="text-center">
                    <div class="border-t border-gray-900 pt-2">
                        <div class="font-bold"><?php echo e($adviser->user->name ?? '_____________________'); ?></div>
                        <div class="text-sm text-gray-600">Class Adviser</div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="border-t border-gray-900 pt-2">
                        <div class="font-bold"><?php echo e($student->parent_guardian_name ?? '_____________________'); ?></div>
                        <div class="text-sm text-gray-600">Parent/Guardian</div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-right text-xs text-gray-500">
                Date Generated: <?php echo e(now()->format('F d, Y')); ?>

            </div>
        </div>
    </div>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>This is a preview. Download the PDF for the official report card.</p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\report-cards\preview.blade.php ENDPATH**/ ?>