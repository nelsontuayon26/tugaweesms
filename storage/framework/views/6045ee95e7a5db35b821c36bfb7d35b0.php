<div class="kindergarten-assessment-container bg-white p-6 rounded-xl shadow-lg border border-slate-200">
    <style>
        .ka-header { background: #1e3a8a; color: white; font-weight: bold; font-size: 14px; text-align: center; padding: 10px; border: 1px solid #000; }
        .ka-table { border-collapse: collapse; width: 100%; font-size: 10px; margin-bottom: 12px; }
        .ka-table th, .ka-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .ka-table th { background-color: #f3f4f6; font-weight: 600; font-size: 9px; }
        .ka-domain-header { background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: left; padding-left: 8px; font-size: 10px; }
        .ka-student-header { background-color: #e0e7ff; font-weight: bold; font-size: 11px; text-align: left; padding: 6px 8px; margin-top: 16px; border: 1px solid #000; border-bottom: none; }
        .rating-B { color: #991b1b; font-weight: bold; }
        .rating-D { color: #92400e; font-weight: bold; }
        .rating-C { color: #166534; font-weight: bold; }
    </style>

    <!-- School Header -->
    <div class="text-center mb-4">
        <p class="text-sm font-semibold">School ID: <?php echo e($schoolId); ?></p>
        <h2 class="text-xl font-bold uppercase"><?php echo e($schoolName); ?></h2>
        <p class="text-sm">District: <?php echo e($schoolDistrict); ?> | Division: <?php echo e($schoolDivision); ?> | Region: <?php echo e($schoolRegion); ?></p>
        <p class="text-sm font-semibold mt-1">School Year: <?php echo e($activeSchoolYear?->name ?? ''); ?> | Section: <?php echo e($selectedSection?->name ?? ''); ?> | Adviser: <?php echo e($adviserName); ?></p>
    </div>

    <!-- Title -->
    <div class="ka-header mb-4">
        KINDERGARTEN ASSESSMENT — DEVELOPMENTAL DOMAIN RATINGS<br>
        <span class="text-[10px] font-normal">(Read-Only View)</span>
    </div>

    <!-- Rating Legend -->
    <div class="flex gap-6 justify-center text-xs mb-4">
        <span><strong class="rating-B">B</strong> = <?php echo e($ratingScale['B']['label']['english'] ?? 'Beginning'); ?></span>
        <span><strong class="rating-D">D</strong> = <?php echo e($ratingScale['D']['label']['english'] ?? 'Developing'); ?></span>
        <span><strong class="rating-C">C</strong> = <?php echo e($ratingScale['C']['label']['english'] ?? 'Consistent'); ?></span>
    </div>

    <?php if($enrollments->isEmpty()): ?>
        <div class="text-center py-8 text-slate-500 text-sm">
            No enrolled Kindergarten students found in this section.
        </div>
    <?php else: ?>
        <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $student = $enrollment->student;
                $user = $student->user ?? null;
                $studentAssessments = $assessments->get($student->id) ?? collect();
            ?>

            <div class="ka-student-header">
                <?php echo e($user?->last_name ?? ''); ?>, <?php echo e($user?->first_name ?? ''); ?> <?php echo e($user?->middle_name ?? ''); ?>

                <span class="text-[9px] font-normal ml-2">LRN: <?php echo e($student->lrn ?? 'N/A'); ?></span>
            </div>

            <?php $__currentLoopData = $kinderConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainKey => $domainData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <table class="ka-table">
                    <thead>
                        <tr>
                            <th colspan="5" class="ka-domain-header">
                                <?php echo e($domainData['name']['english'] ?? ($domainData['name']['cebuano'] ?? $domainKey)); ?>

                            </th>
                        </tr>
                        <tr>
                            <th style="width: 50%; text-align: left; padding-left: 8px;">Indicator</th>
                            <th style="width: 12.5%;">Q1</th>
                            <th style="width: 12.5%;">Q2</th>
                            <th style="width: 12.5%;">Q3</th>
                            <th style="width: 12.5%;">Q4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $domainData['indicators'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorKey => $indicatorData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $indicatorText = $indicatorData['english'] ?? ($indicatorData['cebuano'] ?? $indicatorKey);
                                $getRating = function($q) use ($studentAssessments, $domainKey, $indicatorKey) {
                                    $record = $studentAssessments->first(function($a) use ($domainKey, $indicatorKey, $q) {
                                        return $a->domain === $domainKey && $a->indicator_key === $indicatorKey && (int)$a->quarter === $q;
                                    });
                                    return $record?->rating ?? '';
                                };
                            ?>
                            <tr>
                                <td class="text-left pl-2 text-[9px]"><?php echo e($indicatorText); ?></td>
                                <?php $__currentLoopData = [1,2,3,4]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $rating = $getRating($q); ?>
                                    <td class="<?php echo e($rating ? 'rating-'.$rating : 'text-slate-400'); ?>">
                                        <?php echo e($rating ?: '—'); ?>

                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

    <!-- Footer -->
    <div class="mt-6 pt-3 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
        <span>Kindergarten Assessment | Date Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
        <span>Prepared by: <?php echo e($adviserName); ?></span>
    </div>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\kindergarten_assessment.blade.php ENDPATH**/ ?>