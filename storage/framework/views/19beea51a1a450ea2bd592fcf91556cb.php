<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 6 (SF6) - Summarized Report on Promotion</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        .sf6-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }
        
        .sf6-table th,
        .sf6-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf6-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 8px;
        }
        
        .sf6-header {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            padding: 8px;
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
                font-size: 9pt;
            }
            
            .sf6-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .sf6-table {
                font-size: 8pt;
                width: 100%;
            }
            
            .sf6-table th,
            .sf6-table td {
                padding: 2px 4px;
            }
        }
        
        .data-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .summary-box {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 9px;
        }
        
        .promoted { color: #059669; font-weight: bold; }
        .conditional { color: #d97706; font-weight: bold; }
        .retained { color: #dc2626; font-weight: bold; }
        .incomplete { color: #6b7280; font-weight: bold; }
        
        .proficiency-advanced { background-color: #dbeafe; color: #1e40af; font-weight: bold; }
        .proficiency-proficient { background-color: #d1fae5; color: #065f46; font-weight: bold; }
        .proficiency-approaching { background-color: #fef3c7; color: #92400e; font-weight: bold; }
        .proficiency-developing { background-color: #ffedd5; color: #9a3412; font-weight: bold; }
        .proficiency-beginning { background-color: #fee2e2; color: #991b1b; font-weight: bold; }
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
                <h1 class="text-2xl font-bold text-slate-800">School Form 6 (SF6)</h1>
                <p class="text-slate-500">Summarized Report on Promotion and Level of Proficiency</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-graduation-cap mr-2"></i>SY <?php echo e($activeSchoolYear?->name ?? now()->format('Y')); ?>

                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="<?php echo e(route('teacher.sf6')); ?>" class="flex items-end gap-4">
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
                    Load Report
                </button>
            </form>
        </div>

        <?php if(!$selectedSection): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No section available. Please create a section first.</p>
            </div>
        <?php endif; ?>

        <?php if($promotionData->isEmpty() && $selectedSection): ?>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-info-circle text-blue-500 text-3xl mb-2"></i>
                <p class="text-blue-800 font-medium">No student data found for <?php echo e($selectedSection->name); ?></p>
                <p class="text-sm text-blue-600 mt-1">Make sure students are enrolled and have grades recorded.</p>
            </div>
        <?php endif; ?>

        <!-- Summary Cards (No Print) -->
        <?php if($promotionData->isNotEmpty()): ?>
        <div class="no-print grid grid-cols-5 gap-3 mb-4">
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold">Total Students</p>
                        <p class="text-2xl font-bold text-slate-800"><?php echo e($summaryStats['total_students']); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="fas fa-users text-indigo-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-emerald-600 uppercase font-semibold">Promoted</p>
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($summaryStats['promoted_male'] + $summaryStats['promoted_female']); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-amber-600 uppercase font-semibold">Conditional</p>
                        <p class="text-2xl font-bold text-amber-600"><?php echo e($summaryStats['conditional_male'] + $summaryStats['conditional_female']); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-amber-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-rose-600 uppercase font-semibold">Retained</p>
                        <p class="text-2xl font-bold text-rose-600"><?php echo e($summaryStats['retained_male'] + $summaryStats['retained_female']); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                        <i class="fas fa-times-circle text-rose-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-600 uppercase font-semibold">Advanced</p>
                        <p class="text-2xl font-bold text-blue-600"><?php echo e($summaryStats['advanced_male'] + $summaryStats['advanced_female']); ?></p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-star text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- SF6 Container -->
<div class="overflow-x-auto pb-4">`n        <div class="sf6-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto">
            
            <!-- School Header Information -->
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
                    
                    <?php
                        $maleData = $promotionData->filter(function($item) {
                            return $item['gender'] == 'M';
                        })->sortBy('full_name');
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $maleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center font-medium"><?php echo e($index + 1); ?></td>
                            <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                            <td class="text-center text-[9px] font-bold">M</td>
                            <td class="font-bold text-[10px]"><?php echo e($data['final_average']); ?></td>
                            <td class="text-[9px] italic"><?php echo e($data['general_average_words']); ?></td>
                            <td class="text-[9px] proficiency-<?php echo e(strtolower(str_replace(' ', '-', $data['proficiency_level']))); ?>">
                                <?php echo e($data['proficiency_level']); ?>

                            </td>
                            <td class="text-[9px] <?php echo e(strtolower($data['promotion_status'])); ?>">
                                <?php echo e($data['promotion_status']); ?>

                            </td>
                            <td class="text-[8px]"><?php echo e($data['remarks']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                        </tr>
                    <?php endif; ?>

                    <!-- MALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="2" class="text-right text-[9px] pr-2">MALE TOTAL:</td>
                        <td class="text-[9px]"><?php echo e($summaryStats['male_count']); ?></td>
                        <td class="text-[9px]"><?php echo e(round($maleData->avg('final_average'))); ?></td>
                        <td class="text-[9px]"></td>
                        <td class="text-[9px]"></td>
                        <td class="text-[9px]">
                            P:<?php echo e($summaryStats['promoted_male']); ?> 
                            C:<?php echo e($summaryStats['conditional_male']); ?> 
                            R:<?php echo e($summaryStats['retained_male']); ?>

                        </td>
                        <td></td>
                    </tr>

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                    </tr>

                    <?php
                        $femaleData = $promotionData->filter(function($item) {
                            return $item['gender'] == 'F';
                        })->sortBy('full_name');
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $femaleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center font-medium"><?php echo e($maleData->count() + $index + 1); ?></td>
                            <td class="text-left uppercase text-[9px] data-cell pl-2" title="<?php echo e($data['full_name']); ?>"><?php echo e($data['full_name']); ?></td>
                            <td class="text-center text-[9px] font-bold">F</td>
                            <td class="font-bold text-[10px]"><?php echo e($data['final_average']); ?></td>
                            <td class="text-[9px] italic"><?php echo e($data['general_average_words']); ?></td>
                            <td class="text-[9px] proficiency-<?php echo e(strtolower(str_replace(' ', '-', $data['proficiency_level']))); ?>">
                                <?php echo e($data['proficiency_level']); ?>

                            </td>
                            <td class="text-[9px] <?php echo e(strtolower($data['promotion_status'])); ?>">
                                <?php echo e($data['promotion_status']); ?>

                            </td>
                            <td class="text-[8px]"><?php echo e($data['remarks']); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                        </tr>
                    <?php endif; ?>

                    <!-- FEMALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="2" class="text-right text-[9px] pr-2">FEMALE TOTAL:</td>
                        <td class="text-[9px]"><?php echo e($summaryStats['female_count']); ?></td>
                        <td class="text-[9px]"><?php echo e(round($femaleData->avg('final_average'))); ?></td>
                        <td class="text-[9px]"></td>
                        <td class="text-[9px]"></td>
                        <td class="text-[9px]">
                            P:<?php echo e($summaryStats['promoted_female']); ?> 
                            C:<?php echo e($summaryStats['conditional_female']); ?> 
                            R:<?php echo e($summaryStats['retained_female']); ?>

                        </td>
                        <td></td>
                    </tr>

                    <!-- COMBINED TOTAL ROW -->
                    <tr class="bg-gray-200 font-bold border-t-2 border-black">
                        <td colspan="2" class="text-right text-[9px] pr-2">COMBINED TOTAL:</td>
                        <td class="text-[9px] border-b-2 border-black"><?php echo e($summaryStats['total_students']); ?></td>
                        <td class="text-[9px] border-b-2 border-black"><?php echo e(round($promotionData->avg('final_average'))); ?></td>
                        <td class="text-[9px] border-b-2 border-black"></td>
                        <td class="text-[9px] border-b-2 border-black"></td>
                        <td class="text-[9px] border-b-2 border-black">
                            P:<?php echo e($summaryStats['promoted_male'] + $summaryStats['promoted_female']); ?> 
                            C:<?php echo e($summaryStats['conditional_male'] + $summaryStats['conditional_female']); ?> 
                            R:<?php echo e($summaryStats['retained_male'] + $summaryStats['retained_female']); ?>

                        </td>
                        <td class="border-b-2 border-black"></td>
                    </tr>

                    <!-- Empty rows for manual writing -->
                    <?php 
                        $currentRows = 5 + $maleData->count() + $femaleData->count(); 
                        $totalRows = max(35, $currentRows + 3);
                    ?>
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
                        <tr class="proficiency-advanced">
                            <td class="text-left pl-2 font-bold">Advanced</td>
                            <td class="font-bold"><?php echo e($summaryStats['advanced_male'] > 0 ? $summaryStats['advanced_male'] : ''); ?></td>
                            <td class="font-bold"><?php echo e($summaryStats['advanced_female'] > 0 ? $summaryStats['advanced_female'] : ''); ?></td>
                            <td class="font-bold"><?php echo e(($summaryStats['advanced_male'] + $summaryStats['advanced_female']) > 0 ? ($summaryStats['advanced_male'] + $summaryStats['advanced_female']) : ''); ?></td>
                            <td class="text-[8px]">90 - 100</td>
                        </tr>
                        <tr class="proficiency-proficient">
                            <td class="text-left pl-2 font-bold">Proficient</td>
                            <td class="font-bold"><?php echo e($summaryStats['proficient_male'] > 0 ? $summaryStats['proficient_male'] : ''); ?></td>
                            <td class="font-bold"><?php echo e($summaryStats['proficient_female'] > 0 ? $summaryStats['proficient_female'] : ''); ?></td>
                            <td class="font-bold"><?php echo e(($summaryStats['proficient_male'] + $summaryStats['proficient_female']) > 0 ? ($summaryStats['proficient_male'] + $summaryStats['proficient_female']) : ''); ?></td>
                            <td class="text-[8px]">85 - 89</td>
                        </tr>
                        <tr class="proficiency-approaching">
                            <td class="text-left pl-2 font-bold">Approaching Proficiency</td>
                            <td class="font-bold"><?php echo e($summaryStats['approaching_male'] > 0 ? $summaryStats['approaching_male'] : ''); ?></td>
                            <td class="font-bold"><?php echo e($summaryStats['approaching_female'] > 0 ? $summaryStats['approaching_female'] : ''); ?></td>
                            <td class="font-bold"><?php echo e(($summaryStats['approaching_male'] + $summaryStats['approaching_female']) > 0 ? ($summaryStats['approaching_male'] + $summaryStats['approaching_female']) : ''); ?></td>
                            <td class="text-[8px]">80 - 84</td>
                        </tr>
                        <tr class="proficiency-developing">
                            <td class="text-left pl-2 font-bold">Developing</td>
                            <td class="font-bold"><?php echo e($summaryStats['developing_male'] > 0 ? $summaryStats['developing_male'] : ''); ?></td>
                            <td class="font-bold"><?php echo e($summaryStats['developing_female'] > 0 ? $summaryStats['developing_female'] : ''); ?></td>
                            <td class="font-bold"><?php echo e(($summaryStats['developing_male'] + $summaryStats['developing_female']) > 0 ? ($summaryStats['developing_male'] + $summaryStats['developing_female']) : ''); ?></td>
                            <td class="text-[8px]">75 - 79</td>
                        </tr>
                        <tr class="proficiency-beginning">
                            <td class="text-left pl-2 font-bold">Beginning</td>
                            <td class="font-bold"><?php echo e($summaryStats['beginning_male'] > 0 ? $summaryStats['beginning_male'] : ''); ?></td>
                            <td class="font-bold"><?php echo e($summaryStats['beginning_female'] > 0 ? $summaryStats['beginning_female'] : ''); ?></td>
                            <td class="font-bold"><?php echo e(($summaryStats['beginning_male'] + $summaryStats['beginning_female']) > 0 ? ($summaryStats['beginning_male'] + $summaryStats['beginning_female']) : ''); ?></td>
                            <td class="text-[8px]">74 and below</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Guidelines Section -->
            <div class="mt-3 grid grid-cols-2 gap-4 text-xs border-t-2 border-black pt-3">
                <!-- Left: Guidelines -->
                <div class="text-[8px] space-y-1 leading-tight">
                    <p class="font-bold">GUIDELINES:</p>
                    <p>1. This form is prepared at the end of the school year.</p>
                    <p>2. Promotion Status:</p>
                    <p class="pl-2">• Promoted - Final average of 75% and above</p>
                    <p class="pl-2">• Conditional - Promoted but with subject deficiencies (K-12)</p>
                    <p class="pl-2">• Retained - Final average below 75%</p>
                    <p>3. Level of Proficiency is based on the final general average.</p>
                    <p>4. This report shall be forwarded to the Division Office.</p>
                </div>

                <!-- Right: Signatures -->
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
                    
                    <div class="grid grid-cols-2 gap-4 text-center mt-4">
                        <div>
                            <p class="font-semibold mb-4 text-[10px] text-left">Reviewed & Validated by:</p>
                            <div class="mt-6 border-t border-black pt-1">
                                <p class="text-center font-bold uppercase text-xs">___________________</p>
                                <p class="text-center text-[9px] mt-0.5">(Division Representative)</p>
                            </div>
                        </div>
                        <div>
                            <p class="font-semibold mb-4 text-[10px] text-left">Noted by:</p>
                            <div class="mt-6 border-t border-black pt-1">
                                <p class="text-center font-bold uppercase text-xs">___________________</p>
                                <p class="text-center text-[9px] mt-0.5">(Schools Division Superintendent)</p>
                            </div>
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

        <!-- Legend Panel (No Print) -->
        <div class="no-print mt-6 max-w-[1600px] mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500"></i>
                SF6 Report Information
            </h3>
            <div class="grid grid-cols-3 gap-6 text-sm">
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">About SF6</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        School Form 6 is the end-of-year report showing student promotion status and proficiency levels 
                        based on final grades. It replaces the old Form 20.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">Promotion Criteria</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Students with final average of 75% and above are promoted. Those below 75% are retained 
                        in the same grade level.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">Proficiency Levels</h4>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-blue-200"></span><span>Advanced (90-100)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-emerald-200"></span><span>Proficient (85-89)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-amber-200"></span><span>Approaching (80-84)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-orange-200"></span><span>Developing (75-79)</span></div>
                        <div class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-rose-200"></span><span>Beginning (74 below)</span></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\school-forms\sf6.blade.php ENDPATH**/ ?>