<div class="overflow-x-auto pb-4">
    <div class="sf4-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto min-w-[1024px]">
    <style>
        .sf4-table { border-collapse: collapse; width: 100%; font-size: 9px; }
        .sf4-table th, .sf4-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .sf4-table th { background-color: #e5e7eb; font-weight: 600; text-transform: uppercase; font-size: 8px; }
        .sf4-header { background-color: #1e3a8a; color: white; font-weight: bold; font-size: 11px; text-align: center; padding: 8px; border: 1px solid #000; }
        .data-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .summary-box { border: 1px solid #000; padding: 4px 6px; font-size: 9px; }
    </style>

    <!-- School Header -->
    <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">School ID:</span>
                <span class="border-b border-black flex-1 px-1 font-mono text-[10px]"><?php echo e($schoolId); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">School Name:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolName); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Year:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($activeSchoolYear?->name ?? '___________'); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">Grade Level:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection?->gradeLevel?->name ?? '___________'); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Section:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection?->name ?? '___________'); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Adviser:</span>
                <span class="border-b border-black flex-1 px-1 uppercase text-[10px] font-bold"><?php echo e($adviserName); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-32 text-[10px]">Report for the Month of:</span>
                <span class="border-b-2 border-black flex-1 px-1 font-bold text-sm text-center"><?php echo e($selectedMonth); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-32 text-[10px]">No. of School Days:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px] text-center"><?php echo e($monthlyStats['total_school_days'] ?? '____'); ?></span>
            </div>
        </div>
    </div>

    <!-- SF4 Title -->
    <div class="sf4-header mb-0">
        SCHOOL FORM 4 (SF4) MONTHLY ATTENDANCE REPORT OF LEARNERS<br>
        <span class="text-[9px] font-normal">(This replaces Form 3 & STS Form 4 - Absenteeism and Dropout Profile)</span>
    </div>

    <!-- Main SF4 Table -->
    <table class="sf4-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 4%;">NO.</th>
                <th rowspan="2" style="width: 25%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                <th rowspan="2" style="width: 6%;">Sex<br>(M/F)</th>
                <th colspan="3" style="width: 24%;">ATTENDANCE SUMMARY</th>
                <th rowspan="2" style="width: 10%;">Attendance<br>Rate (%)</th>
                <th rowspan="2" style="width: 21%;">REMARKS</th>
            </tr>
            <tr>
                <th style="width: 8%;">Days<br>Present</th>
                <th style="width: 8%;">Days<br>Absent</th>
                <th style="width: 8%;">Days<br>Tardy</th>
            </tr>
        </thead>
        <tbody>
            <!-- MALE Section -->
            <tr>
                <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
            </tr>
            <?php
                $maleData = $attendanceSummary->filter(fn($item) => $item['gender'] == 'M')->sortBy('full_name');
                $maleTotalPresent = 0; $maleTotalAbsent = 0; $maleTotalTardy = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $maleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $maleTotalPresent += $data['present'];
                    $maleTotalAbsent += $data['absent'];
                    $maleTotalTardy += $data['tardy'];
                ?>
                <tr>
                    <td class="text-center font-medium"><?php echo e($index + 1); ?></td>
                    <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                    <td class="text-center text-[9px] font-bold">M</td>
                    <td class="font-bold text-emerald-600 text-[9px]"><?php echo e($data['present']); ?></td>
                    <td class="font-bold text-rose-600 text-[9px]"><?php echo e($data['absent'] > 0 ? $data['absent'] : ''); ?></td>
                    <td class="font-bold text-amber-600 text-[9px]"><?php echo e($data['tardy'] > 0 ? $data['tardy'] : ''); ?></td>
                    <td class="font-bold <?php echo e($data['attendance_rate'] >= 90 ? 'text-emerald-600' : ($data['attendance_rate'] >= 75 ? 'text-amber-600' : 'text-rose-600')); ?> text-[9px]"><?php echo e($data['attendance_rate']); ?>%</td>
                    <td class="text-[8px]"><?php echo e($data['student']->attendance_remarks ?? ''); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                </tr>
            <?php endif; ?>
            <!-- MALE TOTAL ROW -->
            <tr class="bg-gray-50 font-bold">
                <td colspan="3" class="text-right text-[9px] pr-2">MALE TOTAL:</td>
                <td class="text-emerald-700 text-[9px]"><?php echo e($maleTotalPresent); ?></td>
                <td class="text-rose-700 text-[9px]"><?php echo e($maleTotalAbsent > 0 ? $maleTotalAbsent : ''); ?></td>
                <td class="text-amber-700 text-[9px]"><?php echo e($maleTotalTardy > 0 ? $maleTotalTardy : ''); ?></td>
                <td class="text-[9px]"><?php echo e($maleData->count() > 0 ? round($maleData->avg('attendance_rate'), 1) : 0); ?>%</td>
                <td></td>
            </tr>

            <!-- FEMALE Section -->
            <tr>
                <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
            </tr>
            <?php
                $femaleData = $attendanceSummary->filter(fn($item) => $item['gender'] == 'F')->sortBy('full_name');
                $femaleTotalPresent = 0; $femaleTotalAbsent = 0; $femaleTotalTardy = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $femaleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $femaleTotalPresent += $data['present'];
                    $femaleTotalAbsent += $data['absent'];
                    $femaleTotalTardy += $data['tardy'];
                ?>
                <tr>
                    <td class="text-center font-medium"><?php echo e($maleData->count() + $index + 1); ?></td>
                    <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                    <td class="text-center text-[9px] font-bold">F</td>
                    <td class="font-bold text-emerald-600 text-[9px]"><?php echo e($data['present']); ?></td>
                    <td class="font-bold text-rose-600 text-[9px]"><?php echo e($data['absent'] > 0 ? $data['absent'] : ''); ?></td>
                    <td class="font-bold text-amber-600 text-[9px]"><?php echo e($data['tardy'] > 0 ? $data['tardy'] : ''); ?></td>
                    <td class="font-bold <?php echo e($data['attendance_rate'] >= 90 ? 'text-emerald-600' : ($data['attendance_rate'] >= 75 ? 'text-amber-600' : 'text-rose-600')); ?> text-[9px]"><?php echo e($data['attendance_rate']); ?>%</td>
                    <td class="text-[8px]"><?php echo e($data['student']->attendance_remarks ?? ''); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                </tr>
            <?php endif; ?>
            <!-- FEMALE TOTAL ROW -->
            <tr class="bg-gray-50 font-bold">
                <td colspan="3" class="text-right text-[9px] pr-2">FEMALE TOTAL:</td>
                <td class="text-emerald-700 text-[9px]"><?php echo e($femaleTotalPresent); ?></td>
                <td class="text-rose-700 text-[9px]"><?php echo e($femaleTotalAbsent > 0 ? $femaleTotalAbsent : ''); ?></td>
                <td class="text-amber-700 text-[9px]"><?php echo e($femaleTotalTardy > 0 ? $femaleTotalTardy : ''); ?></td>
                <td class="text-[9px]"><?php echo e($femaleData->count() > 0 ? round($femaleData->avg('attendance_rate'), 1) : 0); ?>%</td>
                <td></td>
            </tr>
            <!-- COMBINED TOTAL ROW -->
            <tr class="bg-gray-200 font-bold border-t-2 border-black">
                <td colspan="3" class="text-right text-[9px] pr-2">COMBINED TOTAL:</td>
                <td class="text-emerald-800 text-[9px] border-b-2 border-black"><?php echo e($maleTotalPresent + $femaleTotalPresent); ?></td>
                <td class="text-rose-800 text-[9px] border-b-2 border-black"><?php echo e(($maleTotalAbsent + $femaleTotalAbsent) > 0 ? ($maleTotalAbsent + $femaleTotalAbsent) : ''); ?></td>
                <td class="text-amber-800 text-[9px] border-b-2 border-black"><?php echo e(($maleTotalTardy + $femaleTotalTardy) > 0 ? ($maleTotalTardy + $femaleTotalTardy) : ''); ?></td>
                <td class="text-[9px] border-b-2 border-black"><?php echo e(number_format($monthlyStats['overall_avg_attendance'] ?? 0, 1)); ?>%</td>
                <td class="border-b-2 border-black"></td>
            </tr>
            <!-- Empty rows -->
            <?php $currentRows = 5 + $maleData->count() + $femaleData->count(); $totalRows = max(35, $currentRows + 3); ?>
            <?php for($i = $currentRows; $i < $totalRows; $i++): ?>
                <tr style="height: 18px;">
                    <td class="text-center text-[8px]"><?php echo e($attendanceSummary->count() + ($i - $currentRows + 1)); ?></td>
                    <?php for($j = 0; $j < 7; $j++): ?>
                        <td></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- Summary Section -->
    <div class="mt-3 grid grid-cols-3 gap-4 text-xs border-t-2 border-black pt-3">
        <div class="text-[8px] space-y-1 leading-tight">
            <p class="font-bold">GUIDELINES:</p>
            <p>1. This form consolidates the daily attendance from SF2 into monthly totals.</p>
            <p>2. Data is automatically calculated from SF2 daily attendance entries.</p>
            <p>3. Attendance Rate = (Days Present / Total School Days) × 100</p>
            <p>4. Students with attendance rate below 75% are considered chronic absentees.</p>
            <p>5. Remarks column should indicate transfers, dropouts, or special cases.</p>
        </div>
        <div class="space-y-2">
            <div class="summary-box bg-gray-50">
                <p class="font-bold text-[9px] mb-2">Monthly Attendance Summary</p>
                <div class="space-y-1 text-[8px]">
                    <div class="flex justify-between border-b border-gray-300 pb-1"><span>Total School Days:</span><span class="font-bold"><?php echo e($monthlyStats['total_school_days'] ?? 0); ?></span></div>
                    <div class="flex justify-between border-b border-gray-300 pb-1"><span>Total Enrolled Students:</span><span class="font-bold"><?php echo e($monthlyStats['total_students'] ?? 0); ?></span></div>
                    <div class="flex justify-between border-b border-gray-300 pb-1"><span>Male Students:</span><span class="font-bold"><?php echo e($monthlyStats['male_count'] ?? 0); ?></span></div>
                    <div class="flex justify-between border-b border-gray-300 pb-1"><span>Female Students:</span><span class="font-bold"><?php echo e($monthlyStats['female_count'] ?? 0); ?></span></div>
                    <div class="flex justify-between pt-1"><span>Overall Attendance Rate:</span><span class="font-bold <?php echo e(($monthlyStats['overall_avg_attendance'] ?? 0) >= 90 ? 'text-emerald-600' : 'text-amber-600'); ?>"><?php echo e(number_format($monthlyStats['overall_avg_attendance'] ?? 0, 1)); ?>%</span></div>
                </div>
            </div>
            <div class="summary-box bg-gray-50">
                <p class="font-bold text-[9px] mb-1">Total Absences: <span class="border-b border-black w-8 inline-block text-center"><?php echo e($monthlyStats['total_absences'] ?? ''); ?></span></p>
                <p class="text-[8px] mt-1">Total Tardy: <span class="border-b border-black w-8 inline-block text-center"><?php echo e($monthlyStats['total_tardy'] ?? ''); ?></span></p>
            </div>
        </div>
        <div class="space-y-2">
            <table class="w-full text-[8px] border border-black">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-black p-1">Category</th>
                        <th class="border border-black p-1">M</th>
                        <th class="border border-black p-1">F</th>
                        <th class="border border-black p-1">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="border border-black p-1 font-semibold">Drop out</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center font-bold">____</td></tr>
                    <tr><td class="border border-black p-1 font-semibold">Transferred out</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center font-bold">____</td></tr>
                    <tr><td class="border border-black p-1 font-semibold">Transferred in</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center">____</td><td class="border border-black p-1 text-center font-bold">____</td></tr>
                </tbody>
            </table>
            <div class="text-[8px] space-y-0.5 leading-tight">
                <p class="font-bold">ATTENDANCE CODES (from SF2):</p>
                <p>(blank) = Present | x = Absent | / = Tardy</p>
                <p class="mt-1 font-bold">COLOR LEGEND:</p>
                <p class="text-emerald-600">Green: 90% and above (Good)</p>
                <p class="text-amber-600">Yellow: 75-89% (Warning)</p>
                <p class="text-rose-600">Red: Below 75% (Critical)</p>
            </div>
        </div>
    </div>

    <!-- Certification Signatures -->
    <div class="mt-4 grid grid-cols-2 gap-8 text-xs px-6">
        <div class="text-center">
            <p class="font-semibold mb-4 text-[10px] text-left">I certify that this is a true and correct report.</p>
            <div class="mt-6 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs"><?php echo e($adviserName); ?></p>
                <p class="text-center text-[9px] mt-0.5">(Signature of Teacher over Printed Name)</p>
            </div>
            <p class="text-center text-[9px] mt-2">Date: ___________________</p>
        </div>
        <div class="text-center">
            <p class="font-semibold mb-4 text-[10px] text-left">Attested by:</p>
            <div class="mt-6 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs"><?php echo e($schoolHead); ?></p>
                <p class="text-center text-[9px] mt-0.5">(Signature of School Head over Printed Name)</p>
            </div>
            <p class="text-center text-[9px] mt-2">Date: ___________________</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
        <span>School Form 4: Page ___ of ___</span>
        <span>Generated through LIS | Date Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
    </div>
    </div>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf4.blade.php ENDPATH**/ ?>