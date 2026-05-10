<div class="overflow-x-auto pb-4">
    <div class="sf6-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto min-w-[1024px]">
    <style>
        .sf6-table { border-collapse: collapse; width: 100%; font-size: 9px; }
        .sf6-table th, .sf6-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .sf6-table th { background-color: #e5e7eb; font-weight: 600; text-transform: uppercase; font-size: 8px; }
        .sf6-header { background-color: #1e3a8a; color: white; font-weight: bold; font-size: 11px; text-align: center; padding: 8px; border: 1px solid #000; }
        .data-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .promoted { color: #059669; font-weight: bold; }
        .conditional { color: #d97706; font-weight: bold; }
        .retained { color: #dc2626; font-weight: bold; }
        .proficiency-advanced { background-color: #dbeafe; color: #1e40af; font-weight: bold; }
        .proficiency-proficient { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        .proficiency-approaching { background-color: #fef3c7; color: #92400e; font-weight: bold; }
        .proficiency-developing { background-color: #ffedd5; color: #9a3412; font-weight: bold; }
        .proficiency-beginning { background-color: #fee2e2; color: #991b1b; font-weight: bold; }
    </style>

    <!-- School Header -->
    <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">School ID:</span>
                <span class="border-b border-black flex-1 px-1 font-mono text-[10px]"><?php echo e($schoolId); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Region:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolRegion); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Name:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolName); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">Division:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolDivision); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Year:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($activeSchoolYear?->name ?? '___________'); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">Grade Level:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->gradeLevel->name ?? '___________'); ?></span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Section:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->name ?? '___________'); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Adviser:</span>
                <span class="border-b border-black flex-1 px-1 uppercase text-[10px] font-bold"><?php echo e($adviserName); ?></span>
            </div>
        </div>
    </div>

    <!-- SF6 Title -->
    <div class="sf6-header mb-0">
        SCHOOL FORM 6 (SF6) SUMMARIZED REPORT ON PROMOTION AND LEVEL OF PROFICIENCY<br>
        <span class="text-[9px] font-normal">(This replaces Form 20 - Report on Promotion)</span>
    </div>

    <!-- Main SF6 Table -->
    <table class="sf6-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 4%;">NO.</th>
                <th rowspan="2" style="width: 22%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                <th rowspan="2" style="width: 5%;">Sex<br>(M/F)</th>
                <th rowspan="2" style="width: 10%;">General Average<br>(Numeric)</th>
                <th rowspan="2" style="width: 15%;">General Average<br>(In Words)</th>
                <th rowspan="2" style="width: 12%;">Level of Proficiency</th>
                <th rowspan="2" style="width: 12%;">Promotion Status</th>
                <th rowspan="2" style="width: 20%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            <!-- MALE Section -->
            <tr>
                <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
            </tr>
            <?php $maleData = $promotionData->filter(fn($item) => $item['gender'] == 'M')->sortBy('full_name'); ?>
            <?php $__empty_1 = true; $__currentLoopData = $maleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center font-medium"><?php echo e($index + 1); ?></td>
                    <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                    <td class="text-center text-[9px] font-bold">M</td>
                    <td class="font-bold text-[10px]"><?php echo e($data['final_average']); ?></td>
                    <td class="text-[9px] italic"><?php echo e($data['general_average_words']); ?></td>
                    <td class="text-[9px] proficiency-<?php echo e(strtolower(str_replace(' ', '-', $data['proficiency_level']))); ?>"><?php echo e($data['proficiency_level']); ?></td>
                    <td class="text-[9px] <?php echo e(strtolower($data['promotion_status'])); ?>"><?php echo e($data['promotion_status']); ?></td>
                    <td class="text-[8px]"><?php echo e($data['remarks']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No male students</td></tr>
            <?php endif; ?>
            <tr class="bg-gray-50 font-bold">
                <td colspan="2" class="text-right text-[9px] pr-2">MALE TOTAL:</td>
                <td class="text-[9px]"><?php echo e($summaryStats['male_count'] ?? 0); ?></td>
                <td class="text-[9px]"><?php echo e(round($maleData->avg('final_average'))); ?></td>
                <td class="text-[9px]"></td>
                <td class="text-[9px]"></td>
                <td class="text-[9px]">P:<?php echo e($summaryStats['promoted_male'] ?? 0); ?> C:<?php echo e($summaryStats['conditional_male'] ?? 0); ?> R:<?php echo e($summaryStats['retained_male'] ?? 0); ?></td>
                <td></td>
            </tr>

            <!-- FEMALE Section -->
            <tr>
                <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
            </tr>
            <?php $femaleData = $promotionData->filter(fn($item) => $item['gender'] == 'F')->sortBy('full_name'); ?>
            <?php $__empty_1 = true; $__currentLoopData = $femaleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-center font-medium"><?php echo e($maleData->count() + $index + 1); ?></td>
                    <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                    <td class="text-center text-[9px] font-bold">F</td>
                    <td class="font-bold text-[10px]"><?php echo e($data['final_average']); ?></td>
                    <td class="text-[9px] italic"><?php echo e($data['general_average_words']); ?></td>
                    <td class="text-[9px] proficiency-<?php echo e(strtolower(str_replace(' ', '-', $data['proficiency_level']))); ?>"><?php echo e($data['proficiency_level']); ?></td>
                    <td class="text-[9px] <?php echo e(strtolower($data['promotion_status'])); ?>"><?php echo e($data['promotion_status']); ?></td>
                    <td class="text-[8px]"><?php echo e($data['remarks']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No female students</td></tr>
            <?php endif; ?>
            <tr class="bg-gray-50 font-bold">
                <td colspan="2" class="text-right text-[9px] pr-2">FEMALE TOTAL:</td>
                <td class="text-[9px]"><?php echo e($summaryStats['female_count'] ?? 0); ?></td>
                <td class="text-[9px]"><?php echo e(round($femaleData->avg('final_average'))); ?></td>
                <td class="text-[9px]"></td>
                <td class="text-[9px]"></td>
                <td class="text-[9px]">P:<?php echo e($summaryStats['promoted_female'] ?? 0); ?> C:<?php echo e($summaryStats['conditional_female'] ?? 0); ?> R:<?php echo e($summaryStats['retained_female'] ?? 0); ?></td>
                <td></td>
            </tr>

            <!-- COMBINED TOTAL ROW -->
            <tr class="bg-gray-200 font-bold border-t-2 border-black">
                <td colspan="2" class="text-right text-[9px] pr-2">COMBINED TOTAL:</td>
                <td class="text-[9px] border-b-2 border-black"><?php echo e($summaryStats['total_students'] ?? 0); ?></td>
                <td class="text-[9px] border-b-2 border-black"><?php echo e(round($promotionData->avg('final_average'))); ?></td>
                <td class="text-[9px] border-b-2 border-black"></td>
                <td class="text-[9px] border-b-2 border-black"></td>
                <td class="text-[9px] border-b-2 border-black">P:<?php echo e(($summaryStats['promoted_male'] ?? 0) + ($summaryStats['promoted_female'] ?? 0)); ?> C:<?php echo e(($summaryStats['conditional_male'] ?? 0) + ($summaryStats['conditional_female'] ?? 0)); ?> R:<?php echo e(($summaryStats['retained_male'] ?? 0) + ($summaryStats['retained_female'] ?? 0)); ?></td>
                <td class="border-b-2 border-black"></td>
            </tr>

            <?php $currentRows = 5 + $maleData->count() + $femaleData->count(); $totalRows = max(35, $currentRows + 3); ?>
            <?php for($i = $currentRows; $i < $totalRows; $i++): ?>
                <tr style="height: 18px;">
                    <td class="text-center text-[8px]"><?php echo e($promotionData->count() + ($i - $currentRows + 1)); ?></td>
                    <?php for($j = 0; $j < 7; $j++): ?>
                        <td></td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- Summary Table Section -->
    <div class="mt-4 border-t-2 border-black pt-3">
        <p class="font-bold text-[10px] mb-2 text-center">SUMMARY TABLE BY LEVEL OF PROFICIENCY</p>
        <table class="sf6-table" style="width: 80%; margin: 0 auto;">
            <thead>
                <tr>
                    <th style="width: 25%;">Level of Proficiency</th>
                    <th style="width: 15%;">MALE</th>
                    <th style="width: 15%;">FEMALE</th>
                    <th style="width: 15%;">TOTAL</th>
                    <th style="width: 30%;">Grade Range</th>
                </tr>
            </thead>
            <tbody>
                <tr class="proficiency-advanced"><td class="text-left pl-2 font-bold">Advanced</td><td class="font-bold"><?php echo e($summaryStats['advanced_male'] > 0 ? $summaryStats['advanced_male'] : ''); ?></td><td class="font-bold"><?php echo e($summaryStats['advanced_female'] > 0 ? $summaryStats['advanced_female'] : ''); ?></td><td class="font-bold"><?php echo e(($summaryStats['advanced_male'] + $summaryStats['advanced_female']) > 0 ? ($summaryStats['advanced_male'] + $summaryStats['advanced_female']) : ''); ?></td><td class="text-[8px]">90 - 100</td></tr>
                <tr class="proficiency-proficient"><td class="text-left pl-2 font-bold">Proficient</td><td class="font-bold"><?php echo e($summaryStats['proficient_male'] > 0 ? $summaryStats['proficient_male'] : ''); ?></td><td class="font-bold"><?php echo e($summaryStats['proficient_female'] > 0 ? $summaryStats['proficient_female'] : ''); ?></td><td class="font-bold"><?php echo e(($summaryStats['proficient_male'] + $summaryStats['proficient_female']) > 0 ? ($summaryStats['proficient_male'] + $summaryStats['proficient_female']) : ''); ?></td><td class="text-[8px]">85 - 89</td></tr>
                <tr class="proficiency-approaching"><td class="text-left pl-2 font-bold">Approaching Proficiency</td><td class="font-bold"><?php echo e($summaryStats['approaching_male'] > 0 ? $summaryStats['approaching_male'] : ''); ?></td><td class="font-bold"><?php echo e($summaryStats['approaching_female'] > 0 ? $summaryStats['approaching_female'] : ''); ?></td><td class="font-bold"><?php echo e(($summaryStats['approaching_male'] + $summaryStats['approaching_female']) > 0 ? ($summaryStats['approaching_male'] + $summaryStats['approaching_female']) : ''); ?></td><td class="text-[8px]">80 - 84</td></tr>
                <tr class="proficiency-developing"><td class="text-left pl-2 font-bold">Developing</td><td class="font-bold"><?php echo e($summaryStats['developing_male'] > 0 ? $summaryStats['developing_male'] : ''); ?></td><td class="font-bold"><?php echo e($summaryStats['developing_female'] > 0 ? $summaryStats['developing_female'] : ''); ?></td><td class="font-bold"><?php echo e(($summaryStats['developing_male'] + $summaryStats['developing_female']) > 0 ? ($summaryStats['developing_male'] + $summaryStats['developing_female']) : ''); ?></td><td class="text-[8px]">75 - 79</td></tr>
                <tr class="proficiency-beginning"><td class="text-left pl-2 font-bold">Beginning</td><td class="font-bold"><?php echo e($summaryStats['beginning_male'] > 0 ? $summaryStats['beginning_male'] : ''); ?></td><td class="font-bold"><?php echo e($summaryStats['beginning_female'] > 0 ? $summaryStats['beginning_female'] : ''); ?></td><td class="font-bold"><?php echo e(($summaryStats['beginning_male'] + $summaryStats['beginning_female']) > 0 ? ($summaryStats['beginning_male'] + $summaryStats['beginning_female']) : ''); ?></td><td class="text-[8px]">74 and below</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Guidelines Section -->
    <div class="mt-3 grid grid-cols-2 gap-4 text-xs border-t-2 border-black pt-3">
        <div class="text-[8px] space-y-1 leading-tight">
            <p class="font-bold">GUIDELINES:</p>
            <p>1. This form is prepared at the end of the school year.</p>
            <p>2. Promotion Status: Promoted - Final average of 75% and above | Conditional - With subject deficiencies | Retained - Final average below 75%</p>
            <p>3. Level of Proficiency is based on the final general average.</p>
            <p>4. This report shall be forwarded to the Division Office.</p>
        </div>
        <div class="space-y-3">
            <div class="grid grid-cols-2 gap-4 text-center">
                <div>
                    <p class="font-semibold mb-4 text-[10px] text-left">Prepared by:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs"><?php echo e($adviserName); ?></p>
                        <p class="text-center text-[9px] mt-0.5">(Class Adviser)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
                <div>
                    <p class="font-semibold mb-4 text-[10px] text-left">Certified Correct:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs"><?php echo e($schoolHead); ?></p>
                        <p class="text-center text-[9px] mt-0.5">(School Head)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
        <span>School Form 6: Page ___ of ___</span>
        <span>Generated through LIS | Date Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
    </div>
    </div>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf6.blade.php ENDPATH**/ ?>