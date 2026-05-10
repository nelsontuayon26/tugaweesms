<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Cards - <?php echo e($section->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }
        .report-card {
            width: 210mm;
            min-height: 297mm;
            padding: 15mm;
            margin: 0 auto 20mm;
            border: 2px solid #1e40af;
            background: white;
            page-break-after: always;
        }
        .report-card:last-child {
            page-break-after: avoid;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #1e40af;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
        }
        .school-address {
            font-size: 10pt;
            color: #666;
            margin-top: 5px;
        }
        .report-title {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 10px;
            color: #1e40af;
        }
        .student-info {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .info-row {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 9pt;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .info-value {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
        }
        .grades-section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #1e40af;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        th {
            background: #1e40af;
            color: white;
            font-weight: bold;
        }
        .grade-cell {
            text-align: center;
            font-weight: bold;
        }
        .grade-pass {
            color: #059669;
        }
        .grade-fail {
            color: #dc2626;
        }
        .average-row {
            background: #f1f5f9;
            font-weight: bold;
        }
        .core-values {
            margin-bottom: 20px;
        }
        .core-values-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .core-value-item {
            display: flex;
            justify-content: space-between;
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .rating {
            font-weight: bold;
            color: #1e40af;
        }
        .attendance-section {
            margin-bottom: 20px;
        }
        .attendance-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .attendance-item {
            text-align: center;
            padding: 10px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .attendance-number {
            font-size: 18pt;
            font-weight: bold;
            color: #1e40af;
        }
        .attendance-label {
            font-size: 9pt;
            color: #64748b;
        }
        .remarks-section {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            min-height: 60px;
        }
        .signatures {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 40px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 10pt;
        }
        .date-generated {
            text-align: right;
            font-size: 9pt;
            color: #64748b;
            margin-top: 20px;
        }
        .legend {
            margin-top: 15px;
            font-size: 9pt;
            color: #64748b;
        }
        @media print {
            body {
                background: white;
            }
            .report-card {
                border: none;
                padding: 10mm;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="report-card">
        <!-- Header -->
        <div class="header">
            <div class="school-name"><?php echo e(config('app.school_name', 'Department of Education')); ?></div>
            <div class="school-address"><?php echo e(config('app.school_address', 'Republic of the Philippines')); ?></div>
            <div class="report-title">REPORT CARD</div>
            <div style="font-size: 10pt; color: #666; margin-top: 5px;"><?php echo e($gradingPeriod); ?> Grading Period</div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="info-row">
                <span class="info-label">Student Name</span>
                <span class="info-value"><?php echo e($data['student']->user->last_name); ?>, <?php echo e($data['student']->user->first_name); ?> <?php echo e($data['student']->user->middle_name ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">LRN</span>
                <span class="info-value"><?php echo e($data['student']->lrn ?? 'N/A'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Grade & Section</span>
                <span class="info-value"><?php echo e($data['section']->gradeLevel->name ?? ''); ?> - <?php echo e($data['section']->name); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">School Year</span>
                <span class="info-value"><?php echo e($data['schoolYear']->name ?? date('Y') . '-' . (date('Y') + 1)); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Class Adviser</span>
                <span class="info-value"><?php echo e($data['adviser']->user->name ?? 'TBD'); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Gender</span>
                <span class="info-value"><?php echo e(ucfirst($data['student']->user->gender ?? 'N/A')); ?></span>
            </div>
        </div>

        <!-- Grades Section -->
        <div class="grades-section">
            <div class="section-title">ACADEMIC PERFORMANCE</div>
            <table>
                <thead>
                    <tr>
                        <th style="width: 40%;">Subject</th>
                        <th style="width: 15%;">Quarterly Grade</th>
                        <th style="width: 15%;">Remarks</th>
                        <th>Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $data['grades']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($grade->subject->name ?? 'Unknown Subject'); ?></td>
                            <td class="grade-cell <?php echo e($grade->final_grade >= 75 ? 'grade-pass' : 'grade-fail'); ?>">
                                <?php echo e(number_format($grade->final_grade, 0)); ?>

                            </td>
                            <td class="grade-cell">
                                <?php echo e($grade->final_grade >= 75 ? 'Passed' : 'Failed'); ?>

                            </td>
                            <td><?php echo e($grade->teacher->user->name ?? 'TBD'); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align: center; color: #999;">No grades recorded for this grading period.</td>
                        </tr>
                    <?php endif; ?>
                    <tr class="average-row">
                        <td><strong>GENERAL AVERAGE</strong></td>
                        <td class="grade-cell <?php echo e($data['generalAverage'] >= 75 ? 'grade-pass' : 'grade-fail'); ?>">
                            <strong><?php echo e(number_format($data['generalAverage'], 2)); ?></strong>
                        </td>
                        <td class="grade-cell">
                            <strong><?php echo e($data['generalAverage'] >= 75 ? 'Promoted' : 'Retention'); ?></strong>
                        </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Core Values Section -->
        <div class="core-values">
            <div class="section-title">CORE VALUES & BEHAVIOR</div>
            <div class="core-values-grid">
                <div>
                    <div class="core-value-item">
                        <span>Maka-Diyos (God-loving)</span>
                        <span class="rating"><?php echo e($data['coreValues']->maka_diyos ?? 'N/A'); ?></span>
                    </div>
                    <div class="core-value-item">
                        <span>Maka-Tao (People-oriented)</span>
                        <span class="rating"><?php echo e($data['coreValues']->maka_tao ?? 'N/A'); ?></span>
                    </div>
                    <div class="core-value-item">
                        <span>Maka-Kalikasan (Environment-friendly)</span>
                        <span class="rating"><?php echo e($data['coreValues']->maka_kalikasan ?? 'N/A'); ?></span>
                    </div>
                </div>
                <div>
                    <div class="core-value-item">
                        <span>Maka-Bansa (Patriotic)</span>
                        <span class="rating"><?php echo e($data['coreValues']->maka_bansa ?? 'N/A'); ?></span>
                    </div>
                    <div class="core-value-item">
                        <span>Conduct</span>
                        <span class="rating"><?php echo e($data['coreValues']->conduct ?? 'N/A'); ?></span>
                    </div>
                    <div class="core-value-item">
                        <span>Attendance</span>
                        <span class="rating"><?php echo e($data['attendanceSummary']['days_present'] ?? 0); ?>/<?php echo e($data['attendanceSummary']['total_school_days'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="attendance-section">
            <div class="section-title">ATTENDANCE SUMMARY</div>
            <div class="attendance-grid">
                <div class="attendance-item">
                    <div class="attendance-number"><?php echo e($data['attendanceSummary']['days_present'] ?? 0); ?></div>
                    <div class="attendance-label">Days Present</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-number"><?php echo e($data['attendanceSummary']['days_absent'] ?? 0); ?></div>
                    <div class="attendance-label">Days Absent</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-number"><?php echo e($data['attendanceSummary']['days_late'] ?? 0); ?></div>
                    <div class="attendance-label">Times Late</div>
                </div>
                <div class="attendance-item">
                    <div class="attendance-number"><?php echo e($data['attendanceSummary']['total_school_days'] ?? 0); ?></div>
                    <div class="attendance-label">Total School Days</div>
                </div>
            </div>
        </div>

        <!-- Teacher's Remarks -->
        <div class="remarks-section">
            <div class="section-title">TEACHER'S REMARKS</div>
            <p><?php echo e($data['coreValues']->remarks ?? 'No remarks recorded for this grading period.'); ?></p>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    <strong><?php echo e($data['adviser']->user->name ?? '_____________________'); ?></strong><br>
                    Class Adviser
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    <strong><?php echo e($data['student']->parent_guardian_name ?? '_____________________'); ?></strong><br>
                    Parent/Guardian
                </div>
            </div>
        </div>

        <!-- Date Generated -->
        <div class="date-generated">
            Date Generated: <?php echo e(now()->format('F d, Y')); ?>

        </div>

        <!-- Legend -->
        <div class="legend">
            <strong>Grading Scale:</strong> A (90-100) | B (85-89) | C (80-84) | D (75-79) | F (Below 75)<br>
            <strong>Core Values Scale:</strong> AO - Always Observed | SO - Sometimes Observed | RO - Rarely Observed | NO - Not Observed
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\report-cards\batch.blade.php ENDPATH**/ ?>