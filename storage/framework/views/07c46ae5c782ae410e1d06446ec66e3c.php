<?php if($teacherProfile): ?>
<div class="overflow-x-auto pb-4">
    <div class="sf7-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1200px] mx-auto min-w-[1024px]">
    <style>
        .sf7-table { border-collapse: collapse; width: 100%; font-size: 9px; }
        .sf7-table th, .sf7-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .sf7-table th { background-color: #e5e7eb; font-weight: 600; text-transform: uppercase; font-size: 8px; }
        .sf7-header { background-color: #1e3a8a; color: white; font-weight: bold; font-size: 11px; text-align: center; padding: 8px; border: 1px solid #000; }
        .section-header { background-color: #f3f4f6; font-weight: bold; text-align: left; padding-left: 10px; }
        .data-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>

    <!-- School Header -->
    <div class="grid grid-cols-3 gap-3 mb-3 text-xs">
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
                <span class="font-semibold w-20 text-[10px]">District:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolDistrict); ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Year:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($activeSchoolYear?->name ?? '___________'); ?></span>
            </div>
        </div>
    </div>

    <!-- SF7 Title -->
    <div class="sf7-header mb-0">
        SCHOOL FORM 7 (SF7) SCHOOL PERSONNEL ASSIGNMENT LIST AND BASIC PROFILE<br>
        <span class="text-[9px] font-normal">(This replaces Form 12, Form 19, Form 29 & Form 31)</span>
    </div>

    <!-- Section A: Personal Info -->
    <table class="sf7-table mt-2">
        <thead><tr><th colspan="6" class="section-header">A. PERSONAL INFORMATION</th></tr></thead>
        <tbody>
            <tr>
                <td class="text-left font-semibold w-20">Employee No.:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['employee_no']); ?></td>
                <td class="text-left font-semibold w-16">TIN:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['tin']); ?></td>
                <td class="text-left font-semibold w-16">Sex:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['sex']); ?></td>
            </tr>
            <tr>
                <td class="text-left font-semibold">Name:</td>
                <td colspan="3" class="text-left font-bold uppercase text-[10px]">
                    <?php echo e($teacherProfile['last_name']); ?>, <?php echo e($teacherProfile['first_name']); ?> <?php echo e($teacherProfile['middle_name']); ?> <?php echo e($teacherProfile['name_extension']); ?>

                </td>
                <td class="text-left font-semibold">Birthdate:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['birthdate']); ?> (Age: <?php echo e($teacherProfile['age']); ?>)</td>
            </tr>
            <tr>
                <td class="text-left font-semibold">Position:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['position']); ?></td>
                <td class="text-left font-semibold">Nature:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['nature_of_appointment']); ?></td>
                <td class="text-left font-semibold">Fund Source:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['fund_source']); ?></td>
            </tr>
            <tr>
                <td class="text-left font-semibold">Date of Appointment:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['date_of_appointment']); ?></td>
                <td class="text-left font-semibold">Years in Service:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['years_in_service']); ?></td>
                <td class="text-left font-semibold">Contact:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['contact_no']); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Section B: Educational Qualification -->
    <table class="sf7-table mt-2">
        <thead><tr><th colspan="4" class="section-header">B. EDUCATIONAL QUALIFICATION</th></tr></thead>
        <tbody>
            <tr>
                <td class="text-left font-semibold w-32">Highest Degree:</td>
                <td colspan="3" class="text-left font-bold text-[10px]"><?php echo e($teacherProfile['highest_degree']); ?></td>
            </tr>
            <tr>
                <td class="text-left font-semibold">Major:</td>
                <td class="text-left text-[10px] w-1/3"><?php echo e($teacherProfile['major']); ?></td>
                <td class="text-left font-semibold w-20">Minor:</td>
                <td class="text-left text-[10px]"><?php echo e($teacherProfile['minor']); ?></td>
            </tr>
            <tr>
                <td class="text-left font-semibold">PRC License No.:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['prc_license_no']); ?></td>
                <td class="text-left font-semibold">Validity:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['prc_validity']); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Section C: Assignment Info -->
    <table class="sf7-table mt-2">
        <thead><tr><th colspan="6" class="section-header">C. ASSIGNMENT INFORMATION</th></tr></thead>
        <tbody>
            <tr>
                <td class="text-left font-semibold w-24">Grade Level:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['grade_level']); ?></td>
                <td class="text-left font-semibold w-20">Section:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['section']); ?></td>
                <td class="text-left font-semibold w-24">Advisory:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['advisory_class']); ?></td>
            </tr>
            <tr>
                <td class="text-left font-semibold">Total Students:</td>
                <td class="font-bold text-[10px]"><?php echo e($teacherProfile['total_students']); ?></td>
                <td class="text-left font-semibold">Male:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['male_students']); ?></td>
                <td class="text-left font-semibold">Female:</td>
                <td class="text-[10px]"><?php echo e($teacherProfile['female_students']); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Section D: Subjects / Kindergarten Domains -->
    <table class="sf7-table mt-2">
        <thead>
            <?php if($isKindergarten): ?>
                <tr><th colspan="2" class="section-header">D. DEVELOPMENTAL DOMAINS (Kindergarten Curriculum)</th></tr>
                <tr><th style="width: 10%;">No.</th><th style="width: 90%;">Domain Name</th></tr>
            <?php else: ?>
                <tr><th colspan="3" class="section-header">D. SUBJECTS TAUGHT (Based on Grade Level Curriculum)</th></tr>
                <tr><th style="width: 5%;">No.</th><th style="width: 60%;">Subject Name</th><th style="width: 35%;">Subject Code</th></tr>
            <?php endif; ?>
        </thead>
        <tbody>
            <?php if($isKindergarten): ?>
                <?php $__empty_1 = true; $__currentLoopData = $kinderDomains; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $domain): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><?php echo e($index + 1); ?></td><td class="text-left pl-2"><?php echo e($domain['name']); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="2" class="text-center py-2 text-slate-400">No developmental domains found</td></tr>
                <?php endif; ?>
                <tr><td colspan="2" class="text-left font-semibold bg-gray-50">Ancillary Assignments: <span class="font-normal"><?php echo e($teacherProfile['ancillary_assignments']); ?></span></td></tr>
            <?php else: ?>
                <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr><td><?php echo e($index + 1); ?></td><td class="text-left pl-2"><?php echo e($subject->name); ?></td><td><?php echo e($subject->code); ?></td></tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="3" class="text-center py-2 text-slate-400">No subjects found for this grade level</td></tr>
                <?php endif; ?>
                <tr><td colspan="3" class="text-left font-semibold bg-gray-50">Ancillary Assignments: <span class="font-normal"><?php echo e($teacherProfile['ancillary_assignments']); ?></span></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Section E: Teaching Program -->
    <table class="sf7-table mt-2">
        <thead>
            <tr><th colspan="5" class="section-header">E. DAILY TEACHING PROGRAM</th></tr>
            <tr><th>Day</th><th>From</th><th>To</th><th>Subject/Activity</th><th>Minutes</th></tr>
        </thead>
        <tbody>
            <?php $totalMinutes = 0; ?>
            <?php $__empty_1 = true; $__currentLoopData = $teachingPrograms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $totalMinutes += $program->minutes; ?>
                <tr>
                    <td>
                        <?php switch($program->day):
                            case ('M'): ?> Monday <?php break; ?>
                            <?php case ('T'): ?> Tuesday <?php break; ?>
                            <?php case ('W'): ?> Wednesday <?php break; ?>
                            <?php case ('TH'): ?> Thursday <?php break; ?>
                            <?php case ('F'): ?> Friday <?php break; ?>
                            <?php default: ?> <?php echo e($program->day); ?>

                        <?php endswitch; ?>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($program->time_from)->format('h:i A')); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($program->time_to)->format('h:i A')); ?></td>
                    <td class="text-left pl-2"><?php echo e($program->subject ?? $program->activity ?? 'Teaching'); ?></td>
                    <td><?php echo e($program->minutes); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-4 text-slate-400">No teaching schedule added yet.</td></tr>
            <?php endif; ?>
            <?php if($teachingPrograms->isNotEmpty()): ?>
                <tr class="font-bold bg-gray-50"><td colspan="4" class="text-right pr-2">Total Teaching Minutes per Week:</td><td><?php echo e($totalMinutes); ?></td></tr>
                <tr class="font-bold bg-gray-50"><td colspan="4" class="text-right pr-2">Average Minutes per Day:</td><td><?php echo e(round($totalMinutes / 5)); ?></td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Remarks -->
    <table class="sf7-table mt-2">
        <tbody>
            <tr><td class="text-left font-semibold w-20">Remarks:</td><td class="text-left text-[10px] h-12 align-top"><?php echo e($teacherProfile['remarks']); ?></td></tr>
        </tbody>
    </table>

    <!-- Guidelines -->
    <div class="mt-3 text-[8px] space-y-1 leading-tight border-t-2 border-black pt-2">
        <p class="font-bold">GUIDELINES:</p>
        <p>1. This form shall be accomplished at the beginning of the school year.</p>
        <p>2. All school personnel should be included, listed from highest rank to lowest.</p>
        <p>3. Daily Program is for teaching personnel only.</p>
    </div>

    <!-- Signatures -->
    <div class="mt-4 grid grid-cols-2 gap-8 text-xs px-6">
        <div class="text-center">
            <p class="font-semibold mb-4 text-[10px] text-left">Prepared by:</p>
            <div class="mt-6 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs"><?php echo e($teacherProfile['full_name']); ?></p>
                <p class="text-center text-[9px] mt-0.5">(Signature over Printed Name)</p>
            </div>
            <p class="text-center text-[9px] mt-2">Date: ___________________</p>
        </div>
        <div class="text-center">
            <p class="font-semibold mb-4 text-[10px] text-left">Certified Correct:</p>
            <div class="mt-6 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs"><?php echo e($schoolHead); ?></p>
                <p class="text-center text-[9px] mt-0.5">(School Head)</p>
            </div>
            <p class="text-center text-[9px] mt-2">Date: ___________________</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
        <span>School Form 7: Page 1 of 1</span>
        <span>Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
    </div>
    </div>
</div>
<?php else: ?>
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">No teacher profile found for this section.</p>
    </div>
<?php endif; ?>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf7.blade.php ENDPATH**/ ?>