<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 5 (SF5) - Report on Learning Progress & Achievement</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        .sf5-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        
        .sf5-table th,
        .sf5-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf5-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
        }
        
        .sf5-header {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            font-size: 10px;
            text-align: center;
            padding: 6px;
            border: 1px solid #000;
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
            
            .sf5-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .sf5-table {
                font-size: 7pt;
                width: 100%;
            }
            
            .sf5-table th,
            .sf5-table td {
                padding: 1px 2px;
            }
        }
        
        .data-cell {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .summary-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: center;
        }
        
        .grade-input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 9px;
            font-weight: bold;
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
        
        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 5 (SF5)</h1>
                <p class="text-slate-500">Report on Learning Progress & Achievement</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i><?php echo e($schoolYear); ?>

                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="<?php echo e(route('teacher.sf5')); ?>" class="flex items-end gap-4">
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
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Load Grades
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

        <!-- SF5 Container -->
<div class="overflow-x-auto pb-4">       <div class="sf5-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto">
            
            <!-- School Header Information -->
            <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School ID:</span>
                        <span class="border-b border-black flex-1 px-1 font-mono text-[10px]"><?php echo e($schoolId); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Region:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px]"><?php echo e($schoolRegion); ?></span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Division:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px]"><?php echo e($schoolDivision); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">District:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px]"><?php echo e($schoolDistrict); ?></span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Name:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]"><?php echo e($schoolName); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Year:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($schoolYear); ?></span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Grade Level:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->gradeLevel->name ?? '___________'); ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Section:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]"><?php echo e($selectedSection->name ?? '___________'); ?></span>
                    </div>
                </div>
            </div>

            <!-- SF5 Title -->
            <div class="sf5-header mb-0">
                SCHOOL FORM 5 (SF5) REPORT ON LEARNING PROGRESS & ACHIEVEMENT<br>
                <span class="text-[9px] font-normal">(Revised to conform with the instructions of DepEd Order 8, s. 2015)</span>
            </div>

            <div class="flex gap-4">
                <!-- Main Student Table -->
                <div class="flex-1">
                    <table class="sf5-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 3%;">NO.</th>
                                <th rowspan="2" style="width: 5%;">LRN</th>
                                <th rowspan="2" style="width: 20%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                                <th rowspan="2" style="width: 6%;">GENERAL AVERAGE<br>(Whole numbers for non-honor)</th>
                                <th rowspan="2" style="width: 8%;">ACTION TAKEN:<br>PROMOTED,<br>CONDITIONAL,<br>or RETAINED</th>
                                <th rowspan="2" style="width: 15%;">Did Not Meet Expectations of the ff. Learning Area/s as of end of current School Year</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- MALE Section -->
                            <tr>
                                <td colspan="6" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
                            </tr>
                            
                            <?php
                                $maleEnrollments = $enrollments->filter(function($e) {
                                    $gender = strtoupper($e->student->gender ?? '');
                                    return $gender == 'MALE' || $gender == 'M';
                                });
                                $maleCounter = 0;
                                $malePromoted = 0;
                                $maleConditional = 0;
                                $maleRetained = 0;
                                $maleGrades = [];
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $maleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $maleCounter++;
                                    $student = $enrollment->student;
                                    if(!$student) continue;
                                    
                                    $user = $student->user;
                                    $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                                    
                                    // Get grade for this student
                                    $grade = $grades->firstWhere('student_id', $student->id);
                                    $generalAverage = $grade ? round(($grade->written_works_avg * 0.4) + ($grade->performance_tasks_avg * 0.6), 0) : '';
                                    
                                    // Determine action taken
                                    $actionTaken = '';
                                    $failedSubjects = [];
                                    if ($grade && $generalAverage !== '') {
                                        $maleGrades[] = $generalAverage;
                                        
                                        // Check for failed subjects (below 75)
                                        $subjectGrades = [
                                            $grade->filipino ?? 0,
                                            $grade->english ?? 0,
                                            $grade->mathematics ?? 0,
                                            $grade->science ?? 0,
                                            $grade->ap ?? 0,
                                            $grade->esp ?? 0,
                                            $grade->music ?? 0,
                                            $grade->arts ?? 0,
                                            $grade->pe ?? 0,
                                            $grade->health ?? 0,
                                            $grade->tle ?? 0
                                        ];
                                        
                                        $failedCount = 0;
                                        $subjectNames = ['Filipino', 'English', 'Math', 'Science', 'AP', 'ESP', 'Music', 'Arts', 'PE', 'Health', 'TLE'];
                                        foreach ($subjectGrades as $index => $sg) {
                                            if ($sg > 0 && $sg < 75) {
                                                $failedCount++;
                                                $failedSubjects[] = $subjectNames[$index];
                                            }
                                        }
                                        
                                        if ($failedCount == 0) {
                                            $actionTaken = 'PROMOTED';
                                            $malePromoted++;
                                        } elseif ($failedCount <= 2) {
                                            $actionTaken = 'CONDITIONAL';
                                            $maleConditional++;
                                        } else {
                                            $actionTaken = 'RETAINED';
                                            $maleRetained++;
                                        }
                                    }
                                    
                                    $failedSubjectsStr = implode(', ', $failedSubjects);
                                ?>
                                <tr>
                                    <td class="text-center font-medium"><?php echo e($maleCounter); ?></td>
                                    <td class="font-mono text-[8px]"><?php echo e($student->lrn ?? ''); ?></td>
                                    <td class="text-left uppercase text-[8px] data-cell pl-2" title="<?php echo e($fullName); ?>"><?php echo e($fullName); ?></td>
                                    <td class="text-center font-bold text-[9px]"><?php echo e($generalAverage); ?></td>
                                    <td class="text-center text-[8px] font-semibold"><?php echo e($actionTaken); ?></td>
                                    <td class="text-left text-[7px] data-cell pl-1" title="<?php echo e($failedSubjectsStr); ?>"><?php echo e($failedSubjectsStr); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                                </tr>
                            <?php endif; ?>

                            <!-- MALE TOTAL ROW -->
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="3" class="text-right text-[9px] pr-2">TOTAL MALE</td>
                                <td class="text-center text-[9px]"><?php echo e(count($maleGrades) > 0 ? round(array_sum($maleGrades) / count($maleGrades), 0) : ''); ?></td>
                                <td colspan="2"></td>
                            </tr>

                            <!-- FEMALE Section -->
                            <tr>
                                <td colspan="6" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                            </tr>

                            <?php
                                $femaleEnrollments = $enrollments->filter(function($e) {
                                    $gender = strtoupper($e->student->gender ?? '');
                                    return $gender == 'FEMALE' || $gender == 'F';
                                });
                                $femaleCounter = 0;
                                $femalePromoted = 0;
                                $femaleConditional = 0;
                                $femaleRetained = 0;
                                $femaleGrades = [];
                            ?>

                            <?php $__empty_1 = true; $__currentLoopData = $femaleEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $femaleCounter++;
                                    $student = $enrollment->student;
                                    if(!$student) continue;
                                    
                                    $user = $student->user;
                                    $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                                    
                                    // Get grade for this student
                                    $grade = $grades->firstWhere('student_id', $student->id);
                                    $generalAverage = $grade ? round(($grade->written_works_avg * 0.4) + ($grade->performance_tasks_avg * 0.6), 0) : '';
                                    
                                    // Determine action taken
                                    $actionTaken = '';
                                    $failedSubjects = [];
                                    if ($grade && $generalAverage !== '') {
                                        $femaleGrades[] = $generalAverage;
                                        
                                        // Check for failed subjects (below 75)
                                        $subjectGrades = [
                                            $grade->filipino ?? 0,
                                            $grade->english ?? 0,
                                            $grade->mathematics ?? 0,
                                            $grade->science ?? 0,
                                            $grade->ap ?? 0,
                                            $grade->esp ?? 0,
                                            $grade->music ?? 0,
                                            $grade->arts ?? 0,
                                            $grade->pe ?? 0,
                                            $grade->health ?? 0,
                                            $grade->tle ?? 0
                                        ];
                                        
                                        $failedCount = 0;
                                        $subjectNames = ['Filipino', 'English', 'Math', 'Science', 'AP', 'ESP', 'Music', 'Arts', 'PE', 'Health', 'TLE'];
                                        foreach ($subjectGrades as $index => $sg) {
                                            if ($sg > 0 && $sg < 75) {
                                                $failedCount++;
                                                $failedSubjects[] = $subjectNames[$index];
                                            }
                                        }
                                        
                                        if ($failedCount == 0) {
                                            $actionTaken = 'PROMOTED';
                                            $femalePromoted++;
                                        } elseif ($failedCount <= 2) {
                                            $actionTaken = 'CONDITIONAL';
                                            $femaleConditional++;
                                        } else {
                                            $actionTaken = 'RETAINED';
                                            $femaleRetained++;
                                        }
                                    }
                                    
                                    $failedSubjectsStr = implode(', ', $failedSubjects);
                                ?>
                                <tr>
                                    <td class="text-center font-medium"><?php echo e($maleCounter + $femaleCounter); ?></td>
                                    <td class="font-mono text-[8px]"><?php echo e($student->lrn ?? ''); ?></td>
                                    <td class="text-left uppercase text-[8px] data-cell pl-2" title="<?php echo e($fullName); ?>"><?php echo e($fullName); ?></td>
                                    <td class="text-center font-bold text-[9px]"><?php echo e($generalAverage); ?></td>
                                    <td class="text-center text-[8px] font-semibold"><?php echo e($actionTaken); ?></td>
                                    <td class="text-left text-[7px] data-cell pl-1" title="<?php echo e($failedSubjectsStr); ?>"><?php echo e($failedSubjectsStr); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                                </tr>
                            <?php endif; ?>

                            <!-- FEMALE TOTAL ROW -->
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="3" class="text-right text-[9px] pr-2">TOTAL FEMALE</td>
                                <td class="text-center text-[9px]"><?php echo e(count($femaleGrades) > 0 ? round(array_sum($femaleGrades) / count($femaleGrades), 0) : ''); ?></td>
                                <td colspan="2"></td>
                            </tr>

                            <!-- COMBINED TOTAL ROW -->
                            <tr class="bg-gray-200 font-bold border-t-2 border-black">
                                <td colspan="3" class="text-right text-[9px] pr-2">COMBINED TOTAL</td>
                                <td class="text-center text-[9px] border-b-2 border-black">
                                    <?php
                                        $allGrades = array_merge($maleGrades, $femaleGrades);
                                    ?>
                                    <?php echo e(count($allGrades) > 0 ? round(array_sum($allGrades) / count($allGrades), 0) : ''); ?>

                                </td>
                                <td colspan="2" class="border-b-2 border-black"></td>
                            </tr>

                            <!-- Empty rows for manual writing -->
                            <?php 
                                $totalStudents = $maleCounter + $femaleCounter;
                                $totalRows = max(30, $totalStudents + 5); 
                            ?>
                            <?php for($i = $totalStudents; $i < $totalRows; $i++): ?>
                                <tr style="height: 16px;">
                                    <td class="text-center text-[8px]"><?php echo e($i + 1); ?></td>
                                    <?php for($j = 0; $j < 5; $j++): ?>
                                        <td></td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Summary Table -->
                <div class="w-64">
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th colspan="4" class="bg-indigo-600 text-white text-[9px]">SUMMARY TABLE</th>
                            </tr>
                            <tr>
                                <th class="text-[8px]">STATUS</th>
                                <th class="text-[8px]">MALE</th>
                                <th class="text-[8px]">FEMALE</th>
                                <th class="text-[8px]">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-left text-[8px] pl-2">PROMOTED</td>
                                <td class="font-bold text-[9px]"><?php echo e($malePromoted > 0 ? $malePromoted : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e($femalePromoted > 0 ? $femalePromoted : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e(($malePromoted + $femalePromoted) > 0 ? ($malePromoted + $femalePromoted) : ''); ?></td>
                            </tr>
                            <tr>
                                <td class="text-left text-[8px] pl-2">*CONDITIONAL</td>
                                <td class="font-bold text-[9px]"><?php echo e($maleConditional > 0 ? $maleConditional : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e($femaleConditional > 0 ? $femaleConditional : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e(($maleConditional + $femaleConditional) > 0 ? ($maleConditional + $femaleConditional) : ''); ?></td>
                            </tr>
                            <tr>
                                <td class="text-left text-[8px] pl-2">RETAINED</td>
                                <td class="font-bold text-[9px]"><?php echo e($maleRetained > 0 ? $maleRetained : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e($femaleRetained > 0 ? $femaleRetained : ''); ?></td>
                                <td class="font-bold text-[9px]"><?php echo e(($maleRetained + $femaleRetained) > 0 ? ($maleRetained + $femaleRetained) : ''); ?></td>
                            </tr>
                            <tr class="bg-gray-100 font-bold">
                                <td class="text-left text-[8px] pl-2">TOTAL</td>
                                <td class="text-[9px]"><?php echo e($maleCounter > 0 ? $maleCounter : ''); ?></td>
                                <td class="text-[9px]"><?php echo e($femaleCounter > 0 ? $femaleCounter : ''); ?></td>
                                <td class="text-[9px]"><?php echo e($totalStudents > 0 ? $totalStudents : ''); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Learning Progress and Achievement -->
                    <table class="summary-table mt-2">
                        <thead>
                            <tr>
                                <th colspan="4" class="bg-indigo-600 text-white text-[8px]">LEARNING PROGRESS AND ACHIEVEMENT<br>(Based on Learners' General Average)</th>
                            </tr>
                            <tr>
                                <th class="text-[7px]">Descriptors & Grading Scale</th>
                                <th class="text-[7px]">MALE</th>
                                <th class="text-[7px]">FEMALE</th>
                                <th class="text-[7px]">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $descriptors = [
                                    ['Did Not Meet Expectations', 0, 74, 0, 0],
                                    ['Fairly Satisfactory', 75, 79, 0, 0],
                                    ['Satisfactory', 80, 84, 0, 0],
                                    ['Very Satisfactory', 85, 89, 0, 0],
                                    ['Outstanding', 90, 100, 0, 0]
                                ];
                                
                                foreach ($maleGrades as $mg) {
                                    foreach ($descriptors as &$d) {
                                        if ($mg >= $d[1] && $mg <= $d[2]) {
                                            $d[3]++;
                                            break;
                                        }
                                    }
                                }
                                foreach ($femaleGrades as $fg) {
                                    foreach ($descriptors as &$d) {
                                        if ($fg >= $d[1] && $fg <= $d[2]) {
                                            $d[4]++;
                                            break;
                                        }
                                    }
                                }
                            ?>
                            <?php $__currentLoopData = $descriptors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="text-left text-[7px] pl-1"><?php echo e($desc[0]); ?> (<?php echo e($desc[1]); ?>-<?php echo e($desc[2]); ?>)</td>
                                    <td class="font-bold text-[8px]"><?php echo e($desc[3] > 0 ? $desc[3] : ''); ?></td>
                                    <td class="font-bold text-[8px]"><?php echo e($desc[4] > 0 ? $desc[4] : ''); ?></td>
                                    <td class="font-bold text-[8px]"><?php echo e(($desc[3] + $desc[4]) > 0 ? ($desc[3] + $desc[4]) : ''); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Certification Signatures -->
            <div class="mt-4 grid grid-cols-3 gap-4 text-xs">
                <div class="text-center">
                    <p class="font-semibold mb-2 text-[9px] text-left">PREPARED BY:</p>
                    <div class="mt-4 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs"><?php echo e($adviserName); ?></p>
                        <p class="text-center text-[8px] mt-0.5">Class Adviser<br>(Name and Signature)</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-2 text-[9px] text-left">CERTIFIED CORRECT & SUBMITTED:</p>
                    <div class="mt-4 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs"><?php echo e($schoolHead); ?></p>
                        <p class="text-center text-[8px] mt-0.5">School Head<br>(Name and Signature)</p>
                    </div>
                </div>
                <div class="text-left text-[8px] space-y-0.5 leading-tight">
                    <p class="font-bold">GUIDELINES:</p>
                    <p>1. Do not include Dropouts and Transferred Out (D.O.4, 2014)</p>
                    <p>2. To be prepared by the Adviser. Indicate General Average based on Form 138.</p>
                    <p>3. Summary: PROMOTED (75+ all subjects), CONDITIONAL (≤2 failed), RETAINED (3+ failed).</p>
                    <p>4. Did Not Meet Expectations: Learning areas failed as of end of current SY.</p>
                    <p>5. Protocols of validation & submission under Schools Division Superintendent discretion.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>School Form 5: Page ___ of ___</span>
                <span>Generated through LIS | Date Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></span>
            </div>

        </div>

        <!-- Grades Input Panel (No Print) -->
        <div class="no-print mt-6 max-w-[1600px] mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500"></i>
                Grading Information
            </h3>
            <div class="grid grid-cols-2 gap-6 text-sm">
                <div class="space-y-2">
                    <p class="font-medium text-slate-700">Grade Calculation:</p>
                    <p class="text-slate-500">General Average = (Written Works × 0.4) + (Performance Tasks × 0.6)</p>
                    <p class="text-slate-500">Rounded to whole number for non-honor students</p>
                </div>
                <div class="space-y-2">
                    <p class="font-medium text-slate-700">Action Taken Criteria:</p>
                    <ul class="text-slate-500 text-xs space-y-1">
                        <li><strong>PROMOTED:</strong> Final Grade of at least 75 in ALL learning areas</li>
                        <li><strong>CONDITIONAL:</strong> Did Not Meet Expectations in not more than 2 learning areas</li>
                        <li><strong>RETAINED:</strong> Did Not Meet Expectations in 3 or more learning areas</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\school-forms\sf5.blade.php ENDPATH**/ ?>