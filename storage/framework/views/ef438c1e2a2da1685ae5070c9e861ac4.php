<?php if($selectedStudent): ?>
<?php
    $user = $selectedStudent->user;
    $section = $selectedSection;
    $gradeLevel = $section?->gradeLevel ?? null;
    $age = '';
    if ($selectedStudent->birthdate) {
        $age = \Carbon\Carbon::parse($selectedStudent->birthdate)->diffInYears(\Carbon\Carbon::now());
    }
?>
<div class="overflow-x-auto pb-4">
    <div class="sf9-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 min-w-[1024px]" style="margin: 0 auto;">
    <style>
        .sf9-table { border-collapse: collapse; width: 100%; font-size: 9pt; }
        .sf9-table th, .sf9-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .sf9-table th { font-weight: bold; font-size: 8.5pt; }
        .header-box { border: 2px solid #000; padding: 10px; text-align: center; background: #fff; margin-bottom: 0; }
        .section-header { background: #1e3a8a; color: white; padding: 5px 8px; font-weight: bold; font-size: 10px; text-transform: uppercase; margin-top: 12px; }
        .info-grid { border: 1px solid #000; border-top: none; }
        .info-row { display: flex; border-bottom: 1px solid #000; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: bold; width: 100px; font-size: 9pt; padding: 4px 8px; border-right: 1px solid #000; background: #f9f9f9; text-transform: uppercase; }
        .info-value { flex: 1; font-size: 9pt; padding: 4px 8px; text-transform: uppercase; }
        .grade-failed { font-weight: bold; }
    </style>

    <!-- Header -->
    <div class="header-box">
        <div class="text-center">
            <p class="text-xs font-normal mb-1">Republic of the Philippines</p>
            <p class="text-sm font-bold uppercase tracking-wide border-b border-black pb-1 mb-1 inline-block">Department of Education</p>
            <div class="flex justify-center gap-8 mt-2 text-xs">
                <span>Region <strong><?php echo e($schoolRegion ?? '__'); ?></strong></span>
                <span>Division of <strong><?php echo e($schoolDivision ?? '____________________'); ?></strong></span>
                <span>District <strong><?php echo e($schoolDistrict ?? '__________'); ?></strong></span>
            </div>
            <p class="text-base font-bold mt-2 uppercase tracking-wide"><?php echo e($schoolName ?? 'SCHOOL NAME'); ?></p>
            <p class="text-lg font-bold mt-2 uppercase tracking-widest border-t-2 border-b-2 border-black inline-block px-6 py-1">Learner's Progress Report Card</p>
            <p class="text-xs mt-2 font-bold">School Year <strong><?php echo e($activeSchoolYear?->name ?? ''); ?></strong></p>
        </div>
    </div>

    <!-- Learner Information -->
    <div class="section-header">Learner's Information</div>
    <div class="info-grid">
        <div class="info-row">
            <span class="info-label">Name:</span>
            <span class="info-value"><?php echo e($user->last_name ?? ''); ?>, <?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?> <span class="text-[9px] text-slate-600 normal-case">(LRN: <?php echo e($selectedStudent->lrn ?? 'N/A'); ?>)</span></span>
        </div>
        <div class="info-row">
            <span class="info-label">Age:</span>
            <span class="info-value"><?php echo e(floor($age)); ?></span>
            <span class="info-label" style="width: 80px; border-left: 1px solid #000;">Sex:</span>
            <span class="info-value" style="width: 140px;"><?php echo e($selectedStudent->gender ?? ''); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Grade Level:</span>
            <span class="info-value"><?php echo e($gradeLevel?->name ?? ''); ?></span>
            <span class="info-label" style="width: 80px; border-left: 1px solid #000;">Section:</span>
            <span class="info-value" style="width: 140px;"><?php echo e($section?->name ?? ''); ?></span>
        </div>
    </div>

    <?php if($isKindergarten): ?>
        <div class="section-header">Developmental Progress Report</div>
        <?php
            $ratingScale = config('kindergarten.rating_scale');
            $getKinderRating = function($domainKey, $indicatorKey, $quarter) use ($kindergartenDomains) {
                $domainData = $kindergartenDomains->get($domainKey);
                if (!$domainData) return '';
                $indicatorData = $domainData->get($indicatorKey);
                if (!$indicatorData) return '';
                $record = $indicatorData->firstWhere('quarter', $quarter);
                return $record ? $record->rating : '';
            };
        ?>
        <?php $__currentLoopData = $kinderConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainKey => $domainData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border border-black mb-2">
                <div class="bg-gray-100 p-2 border-b border-black">
                    <strong style="font-size: 9pt; text-transform: uppercase;"><?php echo e($domainData['name'][$lang] ?? $domainData['name']['cebuano']); ?></strong>
                </div>
                <table class="sf9-table" style="font-size: 8pt;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th rowspan="2" style="width: 50%; text-align: left; padding-left: 8px; vertical-align: middle;"><?php echo e($lang == 'cebuano' ? 'Mga Tigpasiunod (Indicators)' : 'Indicators'); ?></th>
                            <th colspan="4" style="text-align: center;">Quarter</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th style="width: 12.5%;">1</th>
                            <th style="width: 12.5%;">2</th>
                            <th style="width: 12.5%;">3</th>
                            <th style="width: 12.5%;">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(isset($domainData['indicators'])): ?>
                            <?php $__currentLoopData = $domainData['indicators']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorKey => $indicatorData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="<?php echo e($loop->even ? 'background-color: #f9fafb;' : ''); ?>">
                                    <td class="text-left pl-2" style="font-size: 7.5pt; text-align: justify; padding: 5px 8px; line-height: 1.4;"><?php echo e($indicatorData[$lang] ?? $indicatorData['cebuano']); ?></td>
                                    <td class="font-bold"><?php echo e($getKinderRating($domainKey, $indicatorKey, 1)); ?></td>
                                    <td class="font-bold"><?php echo e($getKinderRating($domainKey, $indicatorKey, 2)); ?></td>
                                    <td class="font-bold"><?php echo e($getKinderRating($domainKey, $indicatorKey, 3)); ?></td>
                                    <td class="font-bold"><?php echo e($getKinderRating($domainKey, $indicatorKey, 4)); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="border border-black p-2 mb-3 text-xs">
            <h4 class="font-bold text-xs mb-2 uppercase text-center border-b border-black pb-1">Rating Scale</h4>
            <table style="width:100%; font-size: 8pt;">
                <tbody>
                    <tr>
                        <td class="text-center font-bold" style="width: 15%;">B</td>
                        <td class="text-left"><strong><?php echo e($ratingScale['B']['label'][$lang]); ?> (<?php echo e($ratingScale['B']['label']['cebuano']); ?>)</strong> - <?php echo e($ratingScale['B']['description'][$lang]); ?></td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold">D</td>
                        <td class="text-left"><strong><?php echo e($ratingScale['D']['label'][$lang]); ?> (<?php echo e($ratingScale['D']['label']['cebuano']); ?>)</strong> - <?php echo e($ratingScale['D']['description'][$lang]); ?></td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold">C</td>
                        <td class="text-left"><strong><?php echo e($ratingScale['C']['label'][$lang]); ?> (<?php echo e($ratingScale['C']['label']['cebuano']); ?>)</strong> - <?php echo e($ratingScale['C']['description'][$lang]); ?></td>
                    </tr>
                </tbody>
            </table>
    </div>
    <?php else: ?>
        <div class="section-header">Report on Learning Progress and Achievement</div>
        <table class="sf9-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 32%; text-align: left; padding-left: 8px;">Learning Areas</th>
                    <th colspan="4">Quarterly Rating</th>
                    <th rowspan="2" style="width: 12%;">Final Rating</th>
                    <th rowspan="2" style="width: 12%;">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 9%;">1st</th>
                    <th style="width: 9%;">2nd</th>
                    <th style="width: 9%;">3rd</th>
                    <th style="width: 9%;">4th</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $subjectGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-left pl-2 text-sm" style="font-size: 9pt;"><?php echo e($sg['subject_name']); ?></td>
                        <td><?php echo e($sg['quarter_1'] ?? ''); ?></td>
                        <td><?php echo e($sg['quarter_2'] ?? ''); ?></td>
                        <td><?php echo e($sg['quarter_3'] ?? ''); ?></td>
                        <td><?php echo e($sg['quarter_4'] ?? ''); ?></td>
                        <td class="font-bold <?php echo e($sg['final_grade'] !== null && $sg['final_grade'] < 75 ? 'grade-failed' : ''); ?>"><?php echo e($sg['final_grade'] ?? ''); ?></td>
                        <td class="<?php echo e($sg['remarks'] == 'Failed' ? 'grade-failed' : ''); ?>"><?php echo e($sg['remarks']); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="py-6 text-gray-500 text-center">No subjects found for this grade level.</td></tr>
                <?php endif; ?>
                <?php if($generalAverage !== null): ?>
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td colspan="5" class="text-right pr-4" style="font-size: 9pt;">GENERAL AVERAGE</td>
                        <td class="<?php echo e($generalAverage < 75 ? 'grade-failed' : ''); ?>"><?php echo e($generalAverage); ?></td>
                        <td class="<?php echo e($generalAverage < 75 ? 'grade-failed' : ''); ?>"><?php echo e($generalAverage >= 75 ? 'Passed' : 'Failed'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Core Values -->
    <div class="section-header">Report on Learner's Observed Values</div>
    <?php
    $coreValueOrder = ['Maka-Diyos', 'Makatao', 'Maka-Kalikasan', 'Maka-bansa'];
    $sortedCoreValues = collect($coreValueOrder)->mapWithKeys(function($cv) use ($coreValues) {
        return $coreValues->has($cv) ? [$cv => $coreValues[$cv]] : [];
    });
    foreach ($coreValues as $cv => $statements) {
        if (!in_array($cv, $coreValueOrder)) {
            $sortedCoreValues[$cv] = $statements;
        }
    }
    $getCoreValueRating = function($coreValue, $statementKey, $quarter) use ($sortedCoreValues) {
        $cvData = $sortedCoreValues->get($coreValue);
        if (!$cvData) return '';
        $statementData = $cvData->get($statementKey);
        if (!$statementData) return '';
        $record = $statementData->firstWhere('quarter', $quarter);
        return $record ? $record->rating : '';
    };
    $getBehaviorStatement = function($coreValue, $statementKey) use ($sortedCoreValues) {
        $cvData = $sortedCoreValues->get($coreValue);
        if (!$cvData) return '';
        $statementData = $cvData->get($statementKey);
        if (!$statementData || $statementData->isEmpty()) return '';
        return $statementData->first()->behavior_statement ?? '';
    };
    ?>
    <table class="sf9-table" style="font-size: 8.5pt;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 15%; text-align: left; vertical-align: middle;">Core Values</th>
                <th rowspan="2" style="width: 55%; text-align: left; vertical-align: middle;">Behavior Statements</th>
                <th colspan="4" style="text-align: center;">Quarter</th>
            </tr>
            <tr>
                <th style="width: 8%;">1st</th>
                <th style="width: 8%;">2nd</th>
                <th style="width: 8%;">3rd</th>
                <th style="width: 8%;">4th</th>
            </tr>
        </thead>
        <tbody>
            <?php $rowIndex = 0; $coreValueNumber = 1; ?>
            <?php $__currentLoopData = $sortedCoreValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coreValue => $statements): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $statementKeys = $statements->keys()->sort()->values(); $behaviorCount = $statementKeys->count(); ?>
                <?php $__currentLoopData = $statementKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $statementKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr <?php if($rowIndex % 2 == 1): ?> style="background: #f9fafb;" <?php endif; ?>>
                        <?php if($index === 0): ?>
                            <td rowspan="<?php echo e($behaviorCount); ?>" class="font-bold align-top bg-gray-50" style="text-align: left; vertical-align: top;"><?php echo e($coreValueNumber); ?>. <?php echo e($coreValue); ?></td>
                        <?php endif; ?>
                        <td style="text-align: left; vertical-align: middle;"><?php echo e($getBehaviorStatement($coreValue, $statementKey)); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 1)); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 2)); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 3)); ?></td>
                        <td style="text-align: center; font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 4)); ?></td>
                    </tr>
                    <?php $rowIndex++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php $coreValueNumber++; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php if($sortedCoreValues->isEmpty()): ?>
                <tr><td colspan="6" class="text-center py-4 text-gray-500">No core values records found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="border border-black p-2 mt-1 text-xs">
        <span class="font-bold">Marking:</span>
        <span class="ml-2"><strong>AO</strong> - Always Observed</span>
        <span class="ml-2"><strong>SO</strong> - Sometimes Observed</span>
        <span class="ml-2"><strong>RO</strong> - Rarely Observed</span>
        <span class="ml-2"><strong>NO</strong> - Not Observed</span>
    </div>

    <!-- Attendance -->
    <div class="section-header">Report on Attendance</div>
    <?php
        $months = ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB', 'MAR', 'APR', 'MAY'];
        $attendanceData = []; $totalPresent = 0; $totalAbsent = 0; $totalLate = 0; $totalSchoolDays = 0;
        foreach ($months as $month) {
            $monthAttendances = $attendance->filter(function($a) use ($month) {
                return strtoupper(date('M', strtotime($a->date))) === $month;
            });
            $present = $monthAttendances->where('status', 'present')->count();
            $absent = $monthAttendances->where('status', 'absent')->count();
            $late = $monthAttendances->where('status', 'late')->count();
            $schoolDays = $present + $absent + $late;
            $attendanceData[$month] = [
                'days' => $schoolDays > 0 ? $schoolDays : '',
                'present' => $present > 0 ? $present : '',
                'absent' => $absent > 0 ? $absent : '',
                'late' => $late > 0 ? $late : ''
            ];
            $totalPresent += $present;
            $totalAbsent += $absent;
            $totalLate += $late;
            $totalSchoolDays += $schoolDays;
        }
    ?>
    <table class="sf9-table" style="font-size: 8.5pt;">
        <thead>
            <tr>
                <th class="text-left pl-2" style="width: 20%;">Month</th>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th style="width: 6%;"><?php echo e($m); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th style="width: 8%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left font-bold pl-2">No. of School Days</td>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e($attendanceData[$m]['days']); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="font-bold"><?php echo e($totalSchoolDays > 0 ? $totalSchoolDays : ''); ?></td>
            </tr>
            <tr style="background: #f9fafb;">
                <td class="text-left font-bold pl-2">No. of Days Present</td>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e($attendanceData[$m]['present']); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="font-bold"><?php echo e($totalPresent > 0 ? $totalPresent : ''); ?></td>
            </tr>
            <tr>
                <td class="text-left font-bold pl-2">No. of Days Absent</td>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e($attendanceData[$m]['absent']); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="font-bold"><?php echo e($totalAbsent > 0 ? $totalAbsent : ''); ?></td>
            </tr>
            <tr style="background: #f9fafb;">
                <td class="text-left font-bold pl-2">No. of Times Tardy</td>
                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td><?php echo e($attendanceData[$m]['late']); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td class="font-bold"><?php echo e($totalLate > 0 ? $totalLate : ''); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Parent Signature -->
    <div class="border border-black p-2 mt-3">
        <p class="text-xs font-bold mb-2">PARENT/GUARDIAN'S SIGNATURE</p>
        <div class="grid grid-cols-4 gap-2">
            <?php $__currentLoopData = ['1st Quarter','2nd Quarter','3rd Quarter','4th Quarter']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-black p-2 text-center">
                    <p class="text-[8px] font-bold mb-1"><?php echo e($q); ?></p>
                    <div class="border-b border-black h-6 mb-1"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <!-- Certification -->
    <div class="border border-black p-3 mt-3">
        <p class="text-xs leading-relaxed">
            This certifies that <strong class="underline"><?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?> <?php echo e($user->last_name ?? ''); ?></strong>
            of <strong><?php echo e($section?->name ?? ''); ?></strong> has completed the curriculum for the school year.
        </p>
        <div class="mt-4 flex justify-between">
            <div class="text-center">
                <div class="border-t border-black pt-1 w-48">
                    <p class="font-bold uppercase text-xs"><?php echo e($adviserName ?? '_________________'); ?></p>
                    <p class="text-xs">Teacher</p>
                </div>
            </div>
            <div class="text-center">
                <div class="border-t border-black pt-1 w-48">
                    <p class="font-bold uppercase text-xs"><?php echo e($schoolHead ?? '_________________'); ?></p>
                    <p class="text-xs">School Head</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 pt-2 border-t border-black text-[8px] text-center">
        <span>DepEd School Form 9 | Date Generated: <?php echo e(now()->format('F d, Y')); ?></span>
    </div>
    </div>
</div>
<?php else: ?>
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Please select a student to view the report card.</p>
    </div>
<?php endif; ?>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf9.blade.php ENDPATH**/ ?>