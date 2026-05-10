<div class="sf2-report bg-white p-4 rounded-lg border border-gray-200 overflow-x-auto">
    <?php
        use Carbon\Carbon;
        $daysInMonth = date('t', mktime(0, 0, 0, $monthNum, 1, $year));
        $nonSchoolDays = [];
        if ($schoolDaysConfig && $schoolDaysConfig->non_school_days) {
            $nonSchoolDays = collect($schoolDaysConfig->non_school_days)->pluck('date')->map(fn($d) => Carbon::parse($d)->day)->toArray();
        }
        $schoolDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $monthNum, $day);
            if (!$date->isWeekend() && !in_array($day, $nonSchoolDays)) {
                $schoolDays[] = $day;
            }
        }
        $displayDays = array_slice($schoolDays, 0, 25);
        $remainingCols = 25 - count($displayDays);
        $dayLetters = ['M', 'T', 'W', 'TH', 'F'];
        $colspanTotal = 29 + count($displayDays);

        $maleEnrollments = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['MALE','M']));
        $femaleEnrollments = $enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['FEMALE','F']));
    ?>

    <?php if($selectedSection): ?>
        <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">School ID:</span><span class="border-b border-black flex-1 px-1 font-mono text-[10px]"><?php echo e($schoolId); ?></span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">School Name:</span><span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolName); ?></span></div>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-24 text-[10px]">School Year:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($schoolYear); ?></span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-24 text-[10px]">Grade Level:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->gradeLevel->name ?? '___________'); ?></span></div>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">Section:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->name ?? '___________'); ?></span></div>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-32 text-[10px]">Month:</span><span class="border-b-2 border-black flex-1 px-1 font-bold text-sm text-center"><?php echo e($selectedMonth); ?></span></div>
            </div>
        </div>

        <div class="bg-blue-900 text-white font-bold text-xs text-center py-2 border border-black mb-0">
            SCHOOL FORM 2 (SF2) DAILY ATTENDANCE REPORT OF LEARNERS
        </div>

        <table class="min-w-full border-collapse border border-black text-[8px]">
            <thead>
                <tr class="bg-gray-200">
                    <th rowspan="2" class="border border-black px-1 py-1">NO.</th>
                    <th rowspan="2" class="border border-black px-1 py-1">LEARNER'S NAME</th>
                    <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="border border-black px-1 py-1 text-[7px]"><?php echo e($day); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($i = 0; $i < $remainingCols; $i++): ?>
                        <th class="border border-black px-1 py-1 text-[7px]"></th>
                    <?php endfor; ?>
                    <th colspan="2" class="border border-black px-1 py-1">Total</th>
                    <th rowspan="2" class="border border-black px-1 py-1">REMARKS</th>
                </tr>
                <tr class="bg-gray-200">
                    <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $d = Carbon::create($year, $monthNum, $day); ?>
                        <th class="border border-black px-1 py-1 font-bold"><?php echo e($dayLetters[$d->dayOfWeek - 1] ?? ''); ?></th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($i = 0; $i < $remainingCols; $i++): ?>
                        <th class="border border-black px-1 py-1"></th>
                    <?php endfor; ?>
                    <th class="border border-black px-1 py-1">ABS</th>
                    <th class="border border-black px-1 py-1">TAR</th>
                </tr>
            </thead>
            <tbody>
                
                <tr><td colspan="<?php echo e($colspanTotal); ?>" class="text-left font-bold bg-gray-100 uppercase pl-2 border border-black">MALE</td></tr>
                <?php $maleTotalAbsent = 0; $maleTotalTardy = 0; ?>
                <?php $__empty_1 = true; $__currentLoopData = $maleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $student = $enrollment->student;
                        $user = $student->user;
                        $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                        $absentCount = 0; $tardyCount = 0;
                    ?>
                    <tr>
                        <td class="border border-black text-center font-medium"><?php echo e($index + 1); ?></td>
                        <td class="border border-black text-left pl-1 uppercase"><?php echo e($fullName); ?></td>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $a = $attendances->first(function($item) use ($student, $dateStr) {
                                    $ad = is_string($item->date) ? $item->date : $item->date->format('Y-m-d');
                                    return $item->student_id == $student->id && $ad == $dateStr;
                                });
                                $mark = '';
                                if ($a) {
                                    if ($a->status == 'absent') { $mark = 'x'; $absentCount++; }
                                    elseif ($a->status == 'late') { $mark = '/'; $tardyCount++; }
                                }
                            ?>
                            <td class="border border-black text-center font-bold w-5 h-5" title="<?php echo e($dateStr); ?>"><?php echo e($mark); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <td class="border border-black w-5 h-5"></td>
                        <?php endfor; ?>
                        <td class="border border-black text-center font-bold text-[9px]"><?php echo e($absentCount > 0 ? $absentCount : ''); ?></td>
                        <td class="border border-black text-center font-bold text-[9px]"><?php echo e($tardyCount > 0 ? $tardyCount : ''); ?></td>
                        <td class="border border-black text-center text-[8px]"><?php echo e($student->attendance_remarks ?? ''); ?></td>
                    </tr>
                    <?php $maleTotalAbsent += $absentCount; $maleTotalTardy += $tardyCount; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="<?php echo e($colspanTotal); ?>" class="border border-black text-center py-2 text-gray-400">No male students</td></tr>
                <?php endif; ?>

                
                <tr class="bg-gray-50 font-bold">
                    <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="border border-black text-right text-[9px] pr-2">MALE TOTAL PER DAY:</td>
                    <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                            $maleDailyAbsent = $attendances->filter(function($a) use ($maleEnrollments, $dateStr) {
                                $studentIds = $maleEnrollments->pluck('student.id')->toArray();
                                $ad = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                return in_array($a->student_id, $studentIds) && $ad == $dateStr && $a->status == 'absent';
                            })->count();
                            $malePresent = $maleEnrollments->count() - $maleDailyAbsent;
                        ?>
                        <td class="border border-black text-[8px]"><?php echo e($malePresent); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($i = 0; $i < $remainingCols; $i++): ?>
                        <td class="border border-black text-[8px]"></td>
                    <?php endfor; ?>
                    <td class="border border-black text-[9px]"><?php echo e($maleTotalAbsent > 0 ? $maleTotalAbsent : ''); ?></td>
                    <td class="border border-black text-[9px]"><?php echo e($maleTotalTardy > 0 ? $maleTotalTardy : ''); ?></td>
                    <td class="border border-black"></td>
                </tr>

                
                <tr><td colspan="<?php echo e($colspanTotal); ?>" class="text-left font-bold bg-gray-100 uppercase pl-2 border border-black">FEMALE</td></tr>
                <?php $femaleTotalAbsent = 0; $femaleTotalTardy = 0; ?>
                <?php $__empty_1 = true; $__currentLoopData = $femaleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $student = $enrollment->student;
                        $user = $student->user;
                        $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                        $absentCount = 0; $tardyCount = 0;
                    ?>
                    <tr>
                        <td class="border border-black text-center font-medium"><?php echo e($maleEnrollments->count() + $index + 1); ?></td>
                        <td class="border border-black text-left pl-1 uppercase"><?php echo e($fullName); ?></td>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $a = $attendances->first(function($item) use ($student, $dateStr) {
                                    $ad = is_string($item->date) ? $item->date : $item->date->format('Y-m-d');
                                    return $item->student_id == $student->id && $ad == $dateStr;
                                });
                                $mark = '';
                                if ($a) {
                                    if ($a->status == 'absent') { $mark = 'x'; $absentCount++; }
                                    elseif ($a->status == 'late') { $mark = '/'; $tardyCount++; }
                                }
                            ?>
                            <td class="border border-black text-center font-bold w-5 h-5" title="<?php echo e($dateStr); ?>"><?php echo e($mark); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <td class="border border-black w-5 h-5"></td>
                        <?php endfor; ?>
                        <td class="border border-black text-center font-bold text-[9px]"><?php echo e($absentCount > 0 ? $absentCount : ''); ?></td>
                        <td class="border border-black text-center font-bold text-[9px]"><?php echo e($tardyCount > 0 ? $tardyCount : ''); ?></td>
                        <td class="border border-black text-center text-[8px]"><?php echo e($student->attendance_remarks ?? ''); ?></td>
                    </tr>
                    <?php $femaleTotalAbsent += $absentCount; $femaleTotalTardy += $tardyCount; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="<?php echo e($colspanTotal); ?>" class="border border-black text-center py-2 text-gray-400">No female students</td></tr>
                <?php endif; ?>

                
                <tr class="bg-gray-50 font-bold">
                    <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="border border-black text-right text-[9px] pr-2">FEMALE TOTAL PER DAY:</td>
                    <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                            $femaleDailyAbsent = $attendances->filter(function($a) use ($femaleEnrollments, $dateStr) {
                                $studentIds = $femaleEnrollments->pluck('student.id')->toArray();
                                $ad = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                return in_array($a->student_id, $studentIds) && $ad == $dateStr && $a->status == 'absent';
                            })->count();
                            $femalePresent = $femaleEnrollments->count() - $femaleDailyAbsent;
                        ?>
                        <td class="border border-black text-[8px]"><?php echo e($femalePresent); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($i = 0; $i < $remainingCols; $i++): ?>
                        <td class="border border-black text-[8px]"></td>
                    <?php endfor; ?>
                    <td class="border border-black text-[9px]"><?php echo e($femaleTotalAbsent > 0 ? $femaleTotalAbsent : ''); ?></td>
                    <td class="border border-black text-[9px]"><?php echo e($femaleTotalTardy > 0 ? $femaleTotalTardy : ''); ?></td>
                    <td class="border border-black"></td>
                </tr>

                
                <tr class="bg-gray-100 font-bold">
                    <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="border border-black text-right text-[9px] pr-2">COMBINED TOTAL PER DAY:</td>
                    <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                            $dailyAbsent = $attendances->filter(function($a) use ($enrollments, $dateStr) {
                                $studentIds = $enrollments->pluck('student.id')->toArray();
                                $ad = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                return in_array($a->student_id, $studentIds) && $ad == $dateStr && $a->status == 'absent';
                            })->count();
                            $present = $enrollments->count() - $dailyAbsent;
                        ?>
                        <td class="border border-black text-[8px]"><?php echo e($present); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php for($i = 0; $i < $remainingCols; $i++): ?>
                        <td class="border border-black text-[8px]"></td>
                    <?php endfor; ?>
                    <td class="border border-black text-[9px]"><?php echo e(($maleTotalAbsent + $femaleTotalAbsent) > 0 ? ($maleTotalAbsent + $femaleTotalAbsent) : ''); ?></td>
                    <td class="border border-black text-[9px]"><?php echo e(($maleTotalTardy + $femaleTotalTardy) > 0 ? ($maleTotalTardy + $femaleTotalTardy) : ''); ?></td>
                    <td class="border border-black"></td>
                </tr>
            </tbody>
        </table>
        <div class="mt-2 text-[10px] font-semibold">Legend: Blank = Present, x = Absent, / = Tardy</div>
    <?php else: ?>
        <div class="text-center py-8 text-gray-500">
            <p>Please select a section to generate SF2.</p>
        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf2.blade.php ENDPATH**/ ?>