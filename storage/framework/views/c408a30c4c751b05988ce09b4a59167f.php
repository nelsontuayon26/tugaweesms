<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 2 (SF2) - Daily Attendance</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        .sf2-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        
        .sf2-table th,
        .sf2-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf2-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
        }
        
        .sf2-header {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
        }
        
        .day-header {
            font-size: 8px;
            font-weight: bold;
        }
        
        .date-header {
            font-size: 7px;
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 50;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Print styles */
        @media print {
            @page {
                size: letter landscape;
                margin: 0.3in 0.2in 0.3in 0.2in;
            }
            
            aside,
            nav[class*="w-72"],
            div[class*="w-72"],
            .sidebar,
            #sidebar,
            [class*="sidebar"] {
                display: none !important;
            }
            
            .ml-72,
            [class*="ml-72"] {
                margin-left: 0 !important;
                padding-left: 0 !important;
                width: 100% !important;
            }
            
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
                font-size: 8pt;
            }
            
            .sf2-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .sf2-table {
                font-size: 7pt;
                width: 100%;
            }
            
            .sf2-table th,
            .sf2-table td {
                padding: 1px 2px;
            }
        }
        
        .data-cell {
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .attendance-cell {
            width: 20px;
            height: 20px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .summary-box {
            border: 1px solid #000;
            padding: 2px 4px;
            font-size: 8px;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Include Sidebar -->
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="lg:ml-72 p-6">
        
        
        <?php
            // Generate school days for the month (Mon-Fri only, excluding non-school days)
            // School year spans across calendar years (e.g., June 2024 - March 2025)
            // For months June-Dec: use start_date year, for Jan-Mar: use end_date year
            $monthNum = date('n', strtotime($selectedMonth));
            if ($activeSchoolYear) {
                $startYear = \Carbon\Carbon::parse($activeSchoolYear->start_date)->year;
                // If end_date is not set, assume it's the next year (typical school year)
                $endYear = $activeSchoolYear->end_date 
                    ? \Carbon\Carbon::parse($activeSchoolYear->end_date)->year 
                    : $startYear + 1;
                // Months 1-3 (Jan-Mar) use end year, months 6-12 (June-Dec) use start year
                $year = ($monthNum >= 1 && $monthNum <= 3) ? $endYear : $startYear;
            } else {
                $year = date('Y');
            }
            $daysInMonth = date('t', strtotime("$year-$monthNum-01"));
            $schoolDays = [];
            
            // Get non-school days from configuration
            $nonSchoolDays = [];
            if (isset($schoolDaysConfig) && $schoolDaysConfig && $schoolDaysConfig->non_school_days) {
                $nonSchoolDays = collect($schoolDaysConfig->non_school_days)->pluck('date')->map(function($date) {
                    return \Carbon\Carbon::parse($date)->day;
                })->toArray();
            }
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = \Carbon\Carbon::create($year, $monthNum, $day);
                // Only add if it's a weekday AND not a non-school day
                if (!$date->isWeekend() && !in_array($day, $nonSchoolDays)) {
                    $schoolDays[] = $day;
                }
            }
            
            // Limit to max 25 school days for display
            $displayDays = array_slice($schoolDays, 0, 25);
            $dayLetters = ['M', 'T', 'W', 'TH', 'F'];
        ?>
        
        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 2 (SF2)</h1>
                <p class="text-slate-500">Daily Attendance Report of Learners</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i><?php echo e($schoolYear); ?>

                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="<?php echo e(route('teacher.sf2')); ?>" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Section</label>
                    <select name="section_id" onchange="this.form.submit()" 
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>" <?php echo e($selectedSection && $selectedSection->id == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->gradeLevel->name ?? ''); ?> - <?php echo e($section->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Report Month</label>
                    <select name="month" onchange="this.form.submit()"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <?php $__currentLoopData = ['June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($m); ?>" <?php echo e($selectedMonth == $m ? 'selected' : ''); ?>><?php echo e($m); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Load Attendance
                </button>
            </form>
        </div>

        <?php if(!$selectedSection): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No section available. Please create a section first.</p>
            </div>
        <?php endif; ?>

        <?php if($enrollments->isEmpty() && $selectedSection): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-info-circle text-blue-500 text-3xl mb-2"></i>
                <p class="text-blue-800 font-medium">No enrolled students found for <?php echo e($selectedSection->name); ?> in <?php echo e($schoolYear); ?></p>
            </div>
        <?php endif; ?>

        <?php if($selectedSection && !$schoolDaysConfig): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-xl mb-2"></i>
                <p class="text-amber-800 font-medium text-sm">School days not configured for <?php echo e($selectedMonth); ?></p>
                <p class="text-amber-600 text-xs mt-1">
                    <a href="<?php echo e(route('teacher.sections.attendance.school-days', $selectedSection)); ?>?month=<?php echo e($monthNum); ?>&year=<?php echo e($year); ?>" class="underline hover:text-amber-800">
                        Click here to configure school days (holidays, suspensions, etc.)
                    </a>
                </p>
            </div>
        <?php endif; ?>

        <!-- SF2 Container -->
<div class="overflow-x-auto pb-4">       <div class="sf2-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto">
            
            <!-- School Header Information -->
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
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($schoolYear); ?></span>
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
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-32 text-[10px]">Report for the Month of:</span>
                        <span class="border-b-2 border-black flex-1 px-1 font-bold text-sm text-center"><?php echo e($selectedMonth); ?></span>
                    </div>
                </div>
            </div>

            <!-- SF2 Title -->
            <div class="sf2-header mb-0">
                SCHOOL FORM 2 (SF2) DAILY ATTENDANCE REPORT OF LEARNERS<br>
                <span class="text-[9px] font-normal">(This replaces Form 1, Form 2 & STS Form 4 - Absenteeism and Dropout Profile)</span>
            </div>

            <!-- Main SF2 Table -->
            <table class="sf2-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%;">NO.</th>
                        <th rowspan="2" style="width: 15%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="date-header" style="width: 2.5%;"><?php echo e($day); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $remainingCols = 25 - count($displayDays);
                        ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <th class="date-header" style="width: 2.5%;"></th>
                        <?php endfor; ?>
                        <th colspan="2" style="width: 6%;">Total for the<br>Month</th>
                        <th rowspan="2" style="width: 12%;">REMARKS<br>(If DROPPED OUT, state reason.<br>If TRANSFERRED IN/OUT, write name of School)</th>
                    </tr>
                    <tr>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $date = \Carbon\Carbon::create($year, $monthNum, $day);
                                $dayLetter = $dayLetters[$date->dayOfWeek - 1] ?? '';
                            ?>
                            <th class="day-header"><?php echo e($dayLetter); ?></th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <th class="day-header"></th>
                        <?php endfor; ?>
                        <th>ABSENT</th>
                        <th>TARDY</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- MALE Section -->
                    <tr>
                        <td colspan="<?php echo e(29 + count($displayDays)); ?>" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
                    </tr>
                    
                    <?php
                        $maleEnrollments = $enrollments->filter(function($e) {
                            $gender = strtoupper($e->student->gender ?? '');
                            return $gender == 'MALE' || $gender == 'M';
                        })->sortBy(function($e) {
                            return $e->student->user->last_name ?? '';
                        });
                        $maleTotalAbsent = 0;
                        $maleTotalTardy = 0;
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $maleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $student = $enrollment->student;
                            $user = $student->user;
                            $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                            
                            // Calculate attendance totals
                            $absentCount = 0;
                            $tardyCount = 0;
                            foreach ($displayDays as $day) {
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $attendance = $attendances->first(function($a) use ($student, $dateStr) {
                                    $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                    return $a->student_id == $student->id && $attendanceDate == $dateStr;
                                });
                                if ($attendance) {
                                    if ($attendance->status == 'absent') $absentCount++;
                                    if ($attendance->status == 'late') $tardyCount++;
                                }
                            }
                            $maleTotalAbsent += $absentCount;
                            $maleTotalTardy += $tardyCount;
                        ?>
                        <tr>
                            <td class="text-center font-medium"><?php echo e($index + 1); ?></td>
                            <td class="text-left uppercase text-[8px] data-cell pl-2" title="<?php echo e($fullName); ?>"><?php echo e($fullName); ?> (ID:<?php echo e($student->id); ?>)</td>
                            <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                    
                                    // Find attendance for this student and date
                                    $attendance = null;
                                    foreach ($attendances as $a) {
                                        $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                        if ($a->student_id == $student->id && $attendanceDate == $dateStr) {
                                            $attendance = $a;
                                            break;
                                        }
                                    }
                                    
                                    $mark = '';
                                    if ($attendance) {
                                        if ($attendance->status == 'absent') $mark = 'x';
                                        elseif ($attendance->status == 'late') $mark = '/';
                                    }
                                ?>
                                <td class="attendance-cell" title="<?php echo e($dateStr); ?>"><?php echo e($mark); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php for($i = 0; $i < $remainingCols; $i++): ?>
                                <td class="attendance-cell"></td>
                            <?php endfor; ?>
                            <td class="font-bold text-[9px]"><?php echo e($absentCount > 0 ? $absentCount : ''); ?></td>
                            <td class="font-bold text-[9px]"><?php echo e($tardyCount > 0 ? $tardyCount : ''); ?></td>
                            <td class="text-[8px]"><?php echo e($student->attendance_remarks ?? ''); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(29 + count($displayDays)); ?>" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                        </tr>
                    <?php endif; ?>

                    <!-- MALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="text-right text-[9px] pr-2">MALE TOTAL PER DAY:</td>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $maleDailyAbsent = $attendances->filter(function($a) use ($maleEnrollments, $dateStr) {
                                    $studentIds = $maleEnrollments->pluck('student.id')->toArray();
                                    $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                    return in_array($a->student_id, $studentIds) && $attendanceDate == $dateStr && $a->status == 'absent';
                                })->count();
                                $malePresent = $maleEnrollments->count() - $maleDailyAbsent;
                            ?>
                            <td class="text-[8px]"><?php echo e($malePresent); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <td class="text-[8px]"></td>
                        <?php endfor; ?>
                        <td class="text-[9px]"><?php echo e($maleTotalAbsent > 0 ? $maleTotalAbsent : ''); ?></td>
                        <td class="text-[9px]"><?php echo e($maleTotalTardy > 0 ? $maleTotalTardy : ''); ?></td>
                        <td></td>
                    </tr>

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="<?php echo e(29 + count($displayDays)); ?>" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                    </tr>

                    <?php
                        $femaleEnrollments = $enrollments->filter(function($e) {
                            $gender = strtoupper($e->student->gender ?? '');
                            return $gender == 'FEMALE' || $gender == 'F';
                        })->sortBy(function($e) {
                            return $e->student->user->last_name ?? '';
                        });
                        $femaleTotalAbsent = 0;
                        $femaleTotalTardy = 0;
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $femaleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $student = $enrollment->student;
                            $user = $student->user;
                            $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                            
                            $absentCount = 0;
                            $tardyCount = 0;
                            foreach ($displayDays as $day) {
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $attendance = $attendances->first(function($a) use ($student, $dateStr) {
                                    $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                    return $a->student_id == $student->id && $attendanceDate == $dateStr;
                                });
                                if ($attendance) {
                                    if ($attendance->status == 'absent') $absentCount++;
                                    if ($attendance->status == 'late') $tardyCount++;
                                }
                            }
                            $femaleTotalAbsent += $absentCount;
                            $femaleTotalTardy += $tardyCount;
                        ?>
                        <tr>
                            <td class="text-center font-medium"><?php echo e($maleEnrollments->count() + $index + 1); ?></td>
                            <td class="text-left uppercase text-[8px] data-cell pl-2" title="<?php echo e($fullName); ?>"><?php echo e($fullName); ?> (ID:<?php echo e($student->id); ?>)</td>
                            <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                    
                                    // Find attendance for this student and date
                                    $attendance = null;
                                    foreach ($attendances as $a) {
                                        $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                        if ($a->student_id == $student->id && $attendanceDate == $dateStr) {
                                            $attendance = $a;
                                            break;
                                        }
                                    }
                                    
                                    $mark = '';
                                    if ($attendance) {
                                        if ($attendance->status == 'absent') $mark = 'x';
                                        elseif ($attendance->status == 'late') $mark = '/';
                                    }
                                ?>
                                <td class="attendance-cell" title="<?php echo e($dateStr); ?>"><?php echo e($mark); ?></td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php for($i = 0; $i < $remainingCols; $i++): ?>
                                <td class="attendance-cell"></td>
                            <?php endfor; ?>
                            <td class="font-bold text-[9px]"><?php echo e($absentCount > 0 ? $absentCount : ''); ?></td>
                            <td class="font-bold text-[9px]"><?php echo e($tardyCount > 0 ? $tardyCount : ''); ?></td>
                            <td class="text-[8px]"><?php echo e($student->attendance_remarks ?? ''); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="<?php echo e(29 + count($displayDays)); ?>" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                        </tr>
                    <?php endif; ?>

                    <!-- FEMALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="text-right text-[9px] pr-2">FEMALE TOTAL PER DAY:</td>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $femaleDailyAbsent = $attendances->filter(function($a) use ($femaleEnrollments, $dateStr) {
                                    $studentIds = $femaleEnrollments->pluck('student.id')->toArray();
                                    $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                    return in_array($a->student_id, $studentIds) && $attendanceDate == $dateStr && $a->status == 'absent';
                                })->count();
                                $femalePresent = $femaleEnrollments->count() - $femaleDailyAbsent;
                            ?>
                            <td class="text-[8px]"><?php echo e($femalePresent); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <td class="text-[8px]"></td>
                        <?php endfor; ?>
                        <td class="text-[9px]"><?php echo e($femaleTotalAbsent > 0 ? $femaleTotalAbsent : ''); ?></td>
                        <td class="text-[9px]"><?php echo e($femaleTotalTardy > 0 ? $femaleTotalTardy : ''); ?></td>
                        <td></td>
                    </tr>

                    <!-- COMBINED TOTAL ROW -->
                    <tr class="bg-gray-200 font-bold border-t-2 border-black">
                        <td colspan="<?php echo e(2 + count($displayDays) + $remainingCols); ?>" class="text-right text-[9px] pr-2">COMBINED TOTAL PER DAY:</td>
                        <?php $__currentLoopData = $displayDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dateStr = sprintf('%04d-%02d-%02d', $year, $monthNum, $day);
                                $totalDailyAbsent = $attendances->filter(function($a) use ($enrollments, $dateStr) {
                                    $studentIds = $enrollments->pluck('student.id')->toArray();
                                    $attendanceDate = is_string($a->date) ? $a->date : $a->date->format('Y-m-d');
                                    return in_array($a->student_id, $studentIds) && $attendanceDate == $dateStr && $a->status == 'absent';
                                })->count();
                                $totalPresent = $enrollments->count() - $totalDailyAbsent;
                            ?>
                            <td class="text-[8px] border-b-2 border-black"><?php echo e($totalPresent); ?></td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php for($i = 0; $i < $remainingCols; $i++): ?>
                            <td class="text-[8px] border-b-2 border-black"></td>
                        <?php endfor; ?>
                        <td class="text-[9px] border-b-2 border-black"><?php echo e(($maleTotalAbsent + $femaleTotalAbsent) > 0 ? ($maleTotalAbsent + $femaleTotalAbsent) : ''); ?></td>
                        <td class="text-[9px] border-b-2 border-black"><?php echo e(($maleTotalTardy + $femaleTotalTardy) > 0 ? ($maleTotalTardy + $femaleTotalTardy) : ''); ?></td>
                        <td class="border-b-2 border-black"></td>
                    </tr>

                    <!-- Empty rows for manual writing -->
                    <?php 
                        $currentRows = 4 + $maleEnrollments->count() + $femaleEnrollments->count(); 
                        $totalRows = max(40, $currentRows + 5);
                    ?>
                    <?php for($i = $currentRows; $i < $totalRows; $i++): ?>
                        <tr style="height: 16px;">
                            <td class="text-center text-[8px]"><?php echo e($enrollments->count() + ($i - $currentRows + 1)); ?></td>
                            <?php for($j = 0; $j < (28 + count($displayDays) + $remainingCols); $j++): ?>
                                <td></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="mt-3 grid grid-cols-3 gap-4 text-xs border-t-2 border-black pt-3">
                <!-- Left: Guidelines -->
                <div class="text-[8px] space-y-1 leading-tight">
                    <p class="font-bold">GUIDELINES:</p>
                    <p>1. The attendance shall be accomplished daily. Refer to codes for checking attendance:</p>
                    <p class="pl-2">(blank) - Present; (x) - Absent; Tardy (half shaded = Upper for Late Comer, Lower for Cutting Classes)</p>
                    <p>2. Dates shall be written in the columns after Learner's Name.</p>
                    <p>3. To compute the following:</p>
                    <p class="pl-2">a. % of Enrolment = (Registered Learners as of End of Month / Enrolment as of 1st Friday of June) x 100</p>
                    <p class="pl-2">b. Average Daily Attendance = Total Daily Attendance / Number of School Days in reporting month</p>
                    <p class="pl-2">c. % of Attendance for the month = (Average Daily Attendance / Registered Learners as of End of Month) x 100</p>
                </div>

                <!-- Middle: Summary Box -->
                <div class="space-y-2">
                    <div class="summary-box bg-gray-50">
                        <p class="font-bold text-[9px] mb-1">Summary for the Month</p>
                        <div class="grid grid-cols-2 gap-1 text-[8px]">
                            <div class="flex justify-between">
                                <span>Enrolment as of (1st Friday of June):</span>
                                <span class="font-bold border-b border-black w-8 text-center"><?php echo e($enrollments->count()); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Late Enrollment during the month:</span>
                                <span class="font-bold border-b border-black w-8 text-center"><?php echo e($lateEnrollments ?? '0'); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Registered Learner as of end of month:</span>
                                <span class="font-bold border-b border-black w-8 text-center"><?php echo e($enrollments->count()); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>% of Enrolment as of end of month:</span>
                                <span class="font-bold border-b border-black w-8 text-center">100%</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Average Daily Attendance:</span>
                                <span class="font-bold border-b border-black w-8 text-center"><?php echo e($averageDailyAttendance ?? ''); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>% of Attendance for the month:</span>
                                <span class="font-bold border-b border-black w-8 text-center"><?php echo e($attendancePercentage ?? ''); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-box bg-gray-50">
                        <p class="font-bold text-[9px] mb-1">No. of Days of Classes: <span class="border-b border-black w-8 inline-block text-center"><?php echo e($schoolDaysConfig ? $schoolDaysConfig->total_school_days : count($displayDays)); ?></span></p>
                        <?php if($schoolDaysConfig && $schoolDaysConfig->getNonSchoolDaysCount() > 0): ?>
                        <p class="text-[8px] text-rose-600 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            <?php echo e($schoolDaysConfig->getNonSchoolDaysCount()); ?> non-school day(s) configured (holidays/suspensions)
                        </p>
                        <?php endif; ?>
                        <p class="text-[8px] mt-1">Number of students with 5 consecutive days of absences: <span class="border-b border-black w-8 inline-block text-center"><?php echo e($consecutiveAbsences ?? ''); ?></span></p>
                    </div>
                </div>

                <!-- Right: Dropouts/Transfers -->
                <div class="space-y-2">
                    <table class="w-full text-[8px] border border-black">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-black p-1"></th>
                                <th class="border border-black p-1">M</th>
                                <th class="border border-black p-1">F</th>
                                <th class="border border-black p-1">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Drop out</td>
                                <td class="border border-black p-1 text-center"><?php echo e($dropoutMale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center"><?php echo e($dropoutFemale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center font-bold"><?php echo e(($dropoutMale + $dropoutFemale) > 0 ? ($dropoutMale + $dropoutFemale) : ''); ?></td>
                            </tr>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Transferred out</td>
                                <td class="border border-black p-1 text-center"><?php echo e($transferredOutMale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center"><?php echo e($transferredOutFemale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center font-bold"><?php echo e(($transferredOutMale + $transferredOutFemale) > 0 ? ($transferredOutMale + $transferredOutFemale) : ''); ?></td>
                            </tr>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Transferred in</td>
                                <td class="border border-black p-1 text-center"><?php echo e($transferredInMale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center"><?php echo e($transferredInFemale ?? ''); ?></td>
                                <td class="border border-black p-1 text-center font-bold"><?php echo e(($transferredInMale + $transferredInFemale) > 0 ? ($transferredInMale + $transferredInFemale) : ''); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-[8px] space-y-0.5 leading-tight">
                        <p class="font-bold">2. REASONS/CAUSES FOR DROPPING OUT:</p>
                        <p>a. Domestic-Related: a.1 Care of siblings, a.2 Early marriage/pregnancy, a.3 Parents' attitude, a.4 Family problems</p>
                        <p>b. Individual-Related: b.1 Illness, b.2 Overage, b.3 Death, b.4 Drug Abuse, b.5 Poor academic performance, b.6 Lack of interest, b.7 Hunger/Malnutrition</p>
                        <p>c. School-Related: c.1 Teacher Factor, c.2 Physical condition, c.3 Peer influence</p>
                        <p>d. Geographic/Environmental: d.1 Distance, d.2 Armed conflict, d.3 Calamities/Disasters</p>
                        <p>e. Financial-Related: e.1 Child labor, work | f. Others</p>
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
                <span>School Form 2: Page ___ of ___</span>
                <span>Generated through LIS | Date Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
            </div>

        </div>

        <!-- Attendance Input Panel (No Print) -->
        <div class="no-print mt-6 max-w-[1600px] mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-500"></i>
                Quick Attendance Entry
            </h3>
            <p class="text-sm text-slate-500 mb-4">Click on any attendance cell in the table above to mark present, absent (x), or tardy (/).</p>
            
            <div class="flex gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 border border-gray-400 rounded flex items-center justify-center text-xs font-bold"></span>
                    <span>Present (blank)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 border border-gray-400 rounded flex items-center justify-center text-xs font-bold">x</span>
                    <span>Absent</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 border border-gray-400 rounded flex items-center justify-center text-xs font-bold">/</span>
                    <span>Tardy</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\school-forms\sf2.blade.php ENDPATH**/ ?>