<?php if($selectedStudent): ?>
<?php $user = $selectedStudent->user; ?>
<div class="overflow-x-auto pb-4">
    <div class="sf10-admin-wrapper min-w-[1024px]" style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; font-size: 8pt; line-height: 1.25; color: #000; background: #fff; padding: 8px;">
    <style>
        .sf10-admin-wrapper .u { border-bottom: 1px solid #000; display: inline-block; min-width: 30px; font-weight: 600; }
        .sf10-admin-wrapper .cb { width: 10px; height: 10px; border: 1px solid #000; display: inline-block; margin-right: 3px; vertical-align: middle; }
        .sf10-section { border: 1px solid #000; margin-bottom: 4px; }
        .sf10-section-title { background: #d9d9d9; color: #000; font-size: 8pt; font-weight: bold; text-align: center; padding: 2px 4px; border-bottom: 1px solid #000; letter-spacing: 0.5px; }
        .sf10-section-body { padding: 3px 5px; }
        .scholastic-columns { column-count: 2; column-gap: 6px; }
        .scholastic-columns .scholastic-block { break-inside: avoid; margin-bottom: 4px; }
        .scholastic-block { border: 1px solid #000; margin-bottom: 4px; font-size: 7pt; }
        .scholastic-block-header { padding: 2px 4px; border-bottom: 1px solid #000; line-height: 1.35; }
        .scholastic-block-header-row { display: flex; flex-wrap: wrap; gap: 2px 10px; }
        .grades-table { border-collapse: collapse; width: 100%; font-size: 7pt; }
        .grades-table th, .grades-table td { border: 1px solid #000; padding: 1px 3px; text-align: center; vertical-align: middle; }
        .grades-table th { font-weight: bold; background: #fff; }
        .grades-table td.text-left { text-align: left; }
        .remedial-section { border-top: 1px solid #000; font-size: 6.5pt; }
        .remedial-header { padding: 1px 4px; border-bottom: 1px solid #000; display: flex; justify-content: space-between; align-items: center; }
        .remedial-table { border-collapse: collapse; width: 100%; font-size: 6.5pt; }
        .remedial-table th, .remedial-table td { border: 1px solid #000; padding: 1px 2px; text-align: center; vertical-align: middle; }
        .form-footer { text-align: right; font-size: 7pt; margin-top: 4px; font-weight: 600; }
    </style>

    <!-- Header -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 4px;">
        <tr>
            <td style="width: 60px; text-align: center; vertical-align: middle; border: none; padding: 0;">
                <div style="font-size: 7pt; text-align: center; margin-bottom: 2px;">SF10-ES</div>
                <img src="<?php echo e(asset('images/edukasyon.jpg')); ?>" alt="DepEd Logo" style="width: 50px; height: auto;">
            </td>
            <td style="text-align: center; vertical-align: middle; border: none; padding: 0 8px;">
                <div style="font-size: 9pt; line-height: 1.3;">
                    <div>Republic of the Philippines</div>
                    <div style="font-weight: bold;">Department of Education</div>
                    <div style="font-weight: bold; font-size: 11pt; margin-top: 2px;">Learner Permanent Academic Record for Elementary School (SF10-ES)</div>
                    <div style="font-size: 8pt; font-style: italic;">(Formerly Form 137)</div>
                </div>
            </td>
            <td style="width: 60px; text-align: center; vertical-align: middle; border: none; padding: 0;">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="DepEd" style="width: 55px; height: auto;">
            </td>
        </tr>
    </table>

    <!-- LEARNER'S PERSONAL INFORMATION -->
    <div class="sf10-section">
        <div class="sf10-section-title">LEARNER'S PERSONAL INFORMATION</div>
        <div class="sf10-section-body">
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
                <tr>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; width: 1%;">LAST NAME:</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; font-weight: bold; width: 22%;"><?php echo e(strtoupper($user->last_name ?? '')); ?></td>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">FIRST NAME:</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; font-weight: bold; width: 22%;"><?php echo e(strtoupper($user->first_name ?? '')); ?></td>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">NAME EXTN. (Jr,II,III)</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 8%;"><?php echo e(strtoupper($user->suffix ?? '')); ?></td>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">MIDDLE NAME:</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 18%;"><?php echo e(strtoupper($user->middle_name ?? '')); ?></td>
                </tr>
            </table>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; width: 1%;">Learner Reference Number (LRN):</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 28%;"><?php echo e($selectedStudent->lrn ?? ''); ?></td>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 10px; width: 1%;">Birthdate (mm/dd/yyyy):</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 22%;"><?php echo e($selectedStudent->birthdate ? \Carbon\Carbon::parse($selectedStudent->birthdate)->format('m/d/Y') : ''); ?></td>
                    <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 10px; width: 1%;">Sex:</td>
                    <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 12%;"><?php echo e(strtoupper($selectedStudent->gender ?? '')); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- ELIGIBILITY -->
    <div class="sf10-section">
        <div class="sf10-section-title">ELIGIBILITY FOR ELEMENTARY SCHOOL ENROLLMENT</div>
        <div class="sf10-section-body">
            <div style="display: flex; flex-wrap: wrap; gap: 4px 16px; align-items: center; margin-bottom: 3px;">
                <span style="font-style: italic;">Credential Presented for Grade 1:</span>
                <span><span class="cb"></span> Kinder Progress Report</span>
                <span><span class="cb"></span> ECCD Checklist</span>
                <span><span class="cb"></span> Kindergarten Certificate of Completion</span>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 4px 16px; margin-bottom: 2px;">
                <span>Name of School: <span class="u" style="min-width: 120px;"><?php echo e($schoolName); ?></span></span>
                <span>School ID: <span class="u" style="min-width: 50px;"><?php echo e($schoolId); ?></span></span>
                <span>Address of School: <span class="u" style="min-width: 150px;"></span></span>
            </div>
            <div style="margin-bottom: 2px; font-size: 7.5pt;">Other Credential Presented</div>
            <div style="display: flex; flex-wrap: wrap; gap: 4px 16px; align-items: center; margin-bottom: 2px;">
                <span><span class="cb"></span> PEPT Passer Rating: <span class="u" style="min-width: 40px;"></span></span>
                <span>Date of Examination/Assessment (mm/dd/yyyy): <span class="u" style="min-width: 80px;"></span></span>
                <span><span class="cb"></span> Others (Pls. Specify): <span class="u" style="min-width: 80px;"></span></span>
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 4px 16px;">
                <span>Name and Address of Testing Center: <span class="u" style="min-width: 200px;"></span></span>
                <span>Remark: <span class="u" style="min-width: 100px;"></span></span>
            </div>
        </div>
    </div>

    <!-- SCHOLASTIC RECORD -->
    <div class="sf10-section" style="border-bottom: none;">
        <div class="sf10-section-title">SCHOLASTIC RECORD</div>
    </div>
    <div class="sf10-section" style="border-top: none;">
        <div class="sf10-section-body" style="padding: 2px;">
            <?php if($isKindergarten): ?>
                <?php
                    $getKinderRatingSF10 = function($domainKey, $indicatorKey, $quarter) use ($kindergartenDomains) {
                        $domainData = $kindergartenDomains->get($domainKey);
                        if (!$domainData) return '';
                        $indicatorData = $domainData->get($indicatorKey);
                        if (!$indicatorData) return '';
                        $record = $indicatorData->firstWhere('quarter', $quarter);
                        return $record ? $record->rating : '';
                    };
                ?>
                <div class="scholastic-columns">
                    <div class="scholastic-block">
                                <div class="scholastic-block-header">
                                    <div class="scholastic-block-header-row">
                                        <span>School: <span class="u" style="min-width: 100px;"><?php echo e($schoolName); ?></span></span>
                                        <span>School ID: <span class="u" style="min-width: 50px;"><?php echo e($schoolId); ?></span></span>
                                    </div>
                                    <div class="scholastic-block-header-row">
                                        <span>District: <span class="u" style="min-width: 60px;"><?php echo e($schoolDistrict); ?></span></span>
                                        <span>Division: <span class="u" style="min-width: 80px;"><?php echo e($schoolDivision); ?></span></span>
                                        <span>Region: <span class="u" style="min-width: 40px;"><?php echo e($schoolRegion); ?></span></span>
                                    </div>
                                    <div class="scholastic-block-header-row">
                                        <span>Classified as Grade: <span class="u" style="min-width: 25px;">K</span></span>
                                        <span>Section: <span class="u" style="min-width: 60px;"><?php echo e($currentSection?->name ?? ''); ?></span></span>
                                        <span>School Year: <span class="u" style="min-width: 60px;"><?php echo e($activeSchoolYear?->name ?? ''); ?></span></span>
                                    </div>
                                    <div class="scholastic-block-header-row">
                                        <span>Name of Adviser/Teacher: <span class="u" style="min-width: 120px;"></span></span>
                                        <span>Signature: <span class="u" style="min-width: 80px;"></span></span>
                                    </div>
                                </div>
                                <table class="grades-table">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" style="width: 50%; text-align: left; padding-left: 4px;">DOMAINS</th>
                                            <th colspan="4">QUARTER</th>
                                        </tr>
                                        <tr>
                                            <th style="width: 12.5%;">1</th>
                                            <th style="width: 12.5%;">2</th>
                                            <th style="width: 12.5%;">3</th>
                                            <th style="width: 12.5%;">4</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $kinderConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainKey => $domainData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr>
                                                <td colspan="5" style="text-align: left; padding-left: 4px; font-weight: bold; font-size: 6.5pt; background: #f3f4f6; text-transform: uppercase;">
                                                    <?php echo e($domainData['name']['english'] ?? ($domainData['name']['cebuano'] ?? $domainKey)); ?>

                                                </td>
                                            </tr>
                                            <?php if(isset($domainData['indicators'])): ?>
                                                <?php $__currentLoopData = $domainData['indicators']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorKey => $indicatorText): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td class="text-left" style="font-size: 6.5pt; padding-left: 10px;">
                                                            <?php echo e($indicatorText['english'] ?? ($indicatorText['cebuano'] ?? $indicatorKey)); ?>

                                                        </td>
                                                        <td style="font-weight: bold;"><?php echo e($getKinderRatingSF10($domainKey, $indicatorKey, 1)); ?></td>
                                                        <td style="font-weight: bold;"><?php echo e($getKinderRatingSF10($domainKey, $indicatorKey, 2)); ?></td>
                                                        <td style="font-weight: bold;"><?php echo e($getKinderRatingSF10($domainKey, $indicatorKey, 3)); ?></td>
                                                        <td style="font-weight: bold;"><?php echo e($getKinderRatingSF10($domainKey, $indicatorKey, 4)); ?></td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr><td colspan="5" style="text-align: center; font-size: 7pt; color: #6b7280;">No kindergarten domain data available</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <div class="remedial-section">
                                    <div style="padding: 2px 4px; font-size: 6.5pt;">
                                        <span style="font-weight: bold;">RATING SCALE:</span> B = Beginning | D = Developing | C = Consistent
                                    </div>
                                </div>
                            </div>
    </div>
            <?php elseif($academicRecords->isNotEmpty()): ?>
                <div class="scholastic-columns">
                    <?php $__currentLoopData = $academicRecords; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolYearId => $yearGrades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $schoolYearName = $yearGrades->first()?->schoolYear?->name ?? '';
                            $sectionName = $yearGrades->first()?->section?->name ?? '';
                            $gradeLevelName = $yearGrades->first()?->section?->gradeLevel?->name ?? '';
                            $subjectGroups = $yearGrades->groupBy(fn($g) => $g->subject?->name ?? 'Unknown');
                            $totalFinal = 0;
                            $subjectCount = 0;
                        ?>
                        <div class="scholastic-block">
                                            <div class="scholastic-block-header">
                                                <div class="scholastic-block-header-row">
                                                    <span>School: <span class="u" style="min-width: 100px;"><?php echo e($schoolName); ?></span></span>
                                                    <span>School ID: <span class="u" style="min-width: 50px;"><?php echo e($schoolId); ?></span></span>
                                                </div>
                                                <div class="scholastic-block-header-row">
                                                    <span>District: <span class="u" style="min-width: 60px;"><?php echo e($schoolDistrict); ?></span></span>
                                                    <span>Division: <span class="u" style="min-width: 80px;"><?php echo e($schoolDivision); ?></span></span>
                                                    <span>Region: <span class="u" style="min-width: 40px;"><?php echo e($schoolRegion); ?></span></span>
                                                </div>
                                                <div class="scholastic-block-header-row">
                                                    <span>Classified as Grade: <span class="u" style="min-width: 25px;"><?php echo e(str_replace(['Grade ', 'Kindergarten'], ['', 'K'], $gradeLevelName)); ?></span></span>
                                                    <span>Section: <span class="u" style="min-width: 60px;"><?php echo e($sectionName); ?></span></span>
                                                    <span>School Year: <span class="u" style="min-width: 60px;"><?php echo e($schoolYearName); ?></span></span>
                                                </div>
                                                <div class="scholastic-block-header-row">
                                                    <span>Name of Adviser/Teacher: <span class="u" style="min-width: 120px;"></span></span>
                                                    <span>Signature: <span class="u" style="min-width: 80px;"></span></span>
                                                </div>
                                            </div>
                                            <table class="grades-table">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" style="width: 32%; text-align: left; padding-left: 4px;">LEARNING AREAS</th>
                                                        <th colspan="4">Quarterly Rating</th>
                                                        <th rowspan="2" style="width: 8%;">Final<br>Rating</th>
                                                        <th rowspan="2" style="width: 10%;">Remarks</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 8%;">1</th>
                                                        <th style="width: 8%;">2</th>
                                                        <th style="width: 8%;">3</th>
                                                        <th style="width: 8%;">4</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $__empty_1 = true; $__currentLoopData = $subjectGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectName => $subjectGrades): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <?php
                                                            $q1 = $subjectGrades->firstWhere('quarter', 1)?->final_grade;
                                                            $q2 = $subjectGrades->firstWhere('quarter', 2)?->final_grade;
                                                            $q3 = $subjectGrades->firstWhere('quarter', 3)?->final_grade;
                                                            $q4 = $subjectGrades->firstWhere('quarter', 4)?->final_grade;
                                                            $final = $subjectGrades->firstWhere('quarter', null)?->final_grade ?? $subjectGrades->firstWhere('quarter', 0)?->final_grade;
                                                            if (!$final) {
                                                                $qs = array_filter([$q1, $q2, $q3, $q4], fn($v) => $v !== null);
                                                                $final = count($qs) > 0 ? round(array_sum($qs) / count($qs)) : null;
                                                            }
                                                            if ($final !== null) { $totalFinal += $final; $subjectCount++; }
                                                            $remark = $final !== null ? ($final >= 75 ? 'Passed' : 'Failed') : '';
                                                        ?>
                                                        <tr>
                                                            <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;"><?php echo e($subjectName); ?></td>
                                                            <td><?php echo e($q1 ?? ''); ?></td>
                                                            <td><?php echo e($q2 ?? ''); ?></td>
                                                            <td><?php echo e($q3 ?? ''); ?></td>
                                                            <td><?php echo e($q4 ?? ''); ?></td>
                                                            <td style="font-weight: bold;"><?php echo e($final ?? ''); ?></td>
                                                            <td style="font-size: 6.5pt;"><?php echo e($remark); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr><td colspan="7" style="text-align: center; font-size: 7pt; color: #6b7280;">No subjects found</td></tr>
                                                    <?php endif; ?>
                                                    <?php for($b = 0; $b < 2; $b++): ?>
                                                        <tr>
                                                            <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">&nbsp;</td>
                                                            <td></td><td></td><td></td><td></td><td></td><td></td>
                                                        </tr>
                                                    <?php endfor; ?>
                                                    <tr>
                                                        <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">*Arabic Language</td>
                                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">*Islamic Values Education</td>
                                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                                    </tr>
                                                    <?php if($subjectCount > 0): ?>
                                                        <?php $generalAverage = round($totalFinal / $subjectCount); ?>
                                                        <tr style="font-weight: bold;">
                                                            <td colspan="5" class="text-left" style="padding-left: 4px; font-size: 6.5pt;">General Average</td>
                                                            <td style="font-size: 8pt; border: 1.5px solid #000;"><?php echo e($generalAverage); ?></td>
                                                            <td style="font-size: 6.5pt;"><?php echo e($generalAverage >= 75 ? 'Promoted' : 'Retained'); ?></td>
                                                        </tr>
                                                    <?php else: ?>
                                                        <tr style="font-weight: bold;">
                                                            <td colspan="5" class="text-left" style="padding-left: 4px; font-size: 6.5pt;">General Average</td>
                                                            <td style="font-size: 8pt; border: 1.5px solid #000;"></td>
                                                            <td style="font-size: 6.5pt;"></td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <div class="remedial-section">
                                                <div class="remedial-header">
                                                    <span style="font-weight: bold;">Remedial Classes</span>
                                                    <span>Conducted from: <span class="u" style="min-width: 45px;"></span> to: <span class="u" style="min-width: 45px;"></span></span>
                                                </div>
                                                <table class="remedial-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Learning Areas</th>
                                                            <th>Final Rating</th>
                                                            <th>Remedial Class Mark</th>
                                                            <th>Recomputed Final Grade</th>
                                                            <th>Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
                                                        <tr><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div style="padding: 8px; text-align: center; font-size: 8pt; color: #6b7280; border: 1px dashed #9ca3af;">
                    No academic records found for this student.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="form-footer">
        Revised 2025 based on DepEd Order No. 10, s. 2024
    </div>
    </div>
</div>
<?php else: ?>
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Please select a student to view the permanent record.</p>
    </div>
<?php endif; ?>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf10.blade.php ENDPATH**/ ?>