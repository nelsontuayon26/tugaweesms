<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class List - Grade <?php echo e($section->gradeLevel->name ?? ''); ?> <?php echo e($section->name); ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 18pt;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 11pt;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            font-size: 11pt;
        }
        .info-item {
            display: flex;
        }
        .info-label {
            font-weight: bold;
            min-width: 120px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-print {
            display: none;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
        @media screen {
            .print-only {
                display: none;
            }
            .no-print {
                display: block;
                margin-bottom: 20px;
                padding: 15px;
                background: #f0f0f0;
                border-radius: 5px;
            }
            .no-print button {
                padding: 10px 20px;
                background: #4a5568;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12pt;
            }
            .no-print button:hover {
                background: #2d3748;
            }
        }
        .gender-m { color: #3182ce; }
        .gender-f { color: #d53f8c; }
        .stats {
            margin-top: 20px;
            padding: 10px;
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            text-align: center;
        }
        .stat-item {
            padding: 10px;
        }
        .stat-value {
            font-size: 24pt;
            font-weight: bold;
            color: #2d3748;
        }
        .stat-label {
            font-size: 10pt;
            color: #718096;
        }
        .signature-section {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">🖨️ Print Class List</button>
        <button onclick="window.close()" style="margin-left: 10px; background: #718096;">✕ Close</button>
    </div>

    <div class="header">
        <h1>CLASS LIST - GRADE <?php echo e($section->gradeLevel->name ?? ''); ?></h1>
        <p>School Year <?php echo e($section->schoolYear->name ?? date('Y') . '-' . (date('Y') + 1)); ?></p>
    </div>

    <div class="info-section">
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Grade Level:</span>
                <span><?php echo e($section->gradeLevel->name ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Section:</span>
                <span><?php echo e($section->name); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Adviser:</span>
                <span><?php echo e($section->teacher->user->name ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Room:</span>
                <span><?php echo e($section->room_number ?? 'N/A'); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Date Generated:</span>
                <span><?php echo e(now()->format('F d, Y')); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Students:</span>
                <span><?php echo e($students->count()); ?></span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th>Student Name</th>
                <th style="width: 100px;">Gender</th>
                <th style="width: 150px;">LRN</th>
                <th style="width: 150px;">Contact Number</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <strong><?php echo e($student->user->last_name); ?></strong>, 
                        <?php echo e($student->user->first_name); ?>

                        <?php echo e($student->user->middle_name ? substr($student->user->middle_name, 0, 1) . '.' : ''); ?>

                    </td>
                    <td class="<?php echo e($student->user->gender === 'male' ? 'gender-m' : 'gender-f'); ?>">
                        <?php echo e(ucfirst($student->user->gender)); ?>

                    </td>
                    <td><?php echo e($student->lrn ?? 'N/A'); ?></td>
                    <td><?php echo e($student->user->phone ?? 'N/A'); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px;">
                        No students enrolled in this section.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="stats">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?php echo e($students->count()); ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($students->where('user.gender', 'male')->count()); ?></div>
                <div class="stat-label">Male</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($students->where('user.gender', 'female')->count()); ?></div>
                <div class="stat-label">Female</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?php echo e($section->capacity - $students->count()); ?></div>
                <div class="stat-label">Available Slots</div>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div>
            <div class="signature-line">
                <strong><?php echo e($section->teacher->user->name ?? '_____________________'); ?></strong><br>
                Class Adviser
            </div>
        </div>
        <div>
            <div class="signature-line">
                <strong>_____________________</strong><br>
                School Principal
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\seating\roster.blade.php ENDPATH**/ ?>