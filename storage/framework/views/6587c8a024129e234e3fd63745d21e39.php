<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 9 (SF9) - Learner's Progress Report Card</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Arial:wght@400;700&display=swap');
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f3f4f6;
        }
        
        /* SF9 Container - Landscape Letter Size */
        .sf9-container {
            width: 11in;
            min-height: 8.5in;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid #000;
            padding: 0.2in;
            position: relative;
            box-sizing: border-box;
            font-size: 8.5pt;
            line-height: 1.25;
        }
        
        /* Official Table Styling */
        .sf9-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8pt;
        }
        
        .sf9-table th,
        .sf9-table td {
            border: 1px solid #000;
            padding: 2px 4px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf9-table th {
            background: #fff;
            color: #000;
            font-weight: bold;
            font-size: 7.5pt;
        }
        
        /* Two Column Main Layout */
        .sf9-main {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .sf9-left, .sf9-right {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        /* Header with Logos */
        .sf9-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            padding-bottom: 4px;
        }
        
        .sf9-header .logo {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }
        
        .sf9-header .header-center {
            text-align: center;
            flex: 1;
        }
        
        .sf9-header .header-center p {
            margin: 0;
            font-size: 7.5pt;
            line-height: 1.3;
        }
        
        .sf9-header .header-center .school-name {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
        }
        
        .sf9-header .header-center .report-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 2px 0;
            letter-spacing: 1px;
        }
        
        .sf9-header .header-center .school-year {
            font-size: 8pt;
            font-weight: bold;
        }
        
        /* Section Titles */
        .section-title {
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            margin-bottom: 3px;
            text-align: center;
        }
        
        .section-title-left {
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            margin-bottom: 3px;
            text-align: left;
        }
        
        /* Student Info */
        .student-info-box {
            border: 1px solid #000;
            padding: 6px;
            font-size: 8.5pt;
        }
        
        .student-info-box .info-row {
            display: flex;
            gap: 8px;
            margin-bottom: 2px;
        }
        
        .student-info-box .info-row .label {
            white-space: nowrap;
        }
        
        .student-info-box .info-row .value {
            border-bottom: 1px solid #000;
            flex: 1;
            min-width: 60px;
            padding-left: 2px;
            font-weight: bold;
        }
        
        /* Dear Parent */
        .dear-parent {
            font-size: 8pt;
            text-align: justify;
            line-height: 1.3;
            margin: 4px 0;
        }
        
        /* Teacher Signature */
        .teacher-sig {
            text-align: center;
            margin-top: 6px;
        }
        
        .teacher-sig .name {
            font-weight: bold;
            font-size: 8.5pt;
            text-transform: uppercase;
            margin: 0;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            display: inline-block;
            min-width: 160px;
            line-height: 1.1;
        }
        
        .teacher-sig .title {
            font-size: 7.5pt;
            margin: 0;
            line-height: 1.1;
        }
        
        /* Parent Signature */
        .parent-sig-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        
        .parent-sig-grid .sig-box {
            text-align: center;
        }
        
        .parent-sig-grid .sig-box .line {
            border-bottom: 1px solid #000;
            height: 20px;
            margin-bottom: 1px;
        }
        
        .parent-sig-grid .sig-box .label {
            font-size: 7pt;
        }
        
        /* Grading Scale */
        .grading-scale-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }
        
        .grading-scale-table td {
            padding: 1px 3px;
            border: none;
        }
        
        .grading-scale-table td:first-child { text-align: left; }
        .grading-scale-table td:nth-child(2),
        .grading-scale-table td:nth-child(3) { text-align: center; }
        
        /* Core Values */
        .core-values-table {
            font-size: 7pt;
        }
        
        .core-values-table th,
        .core-values-table td {
            padding: 1px 3px;
        }
        
        .core-values-table td:nth-child(1) {
            font-weight: bold;
            text-align: left;
            vertical-align: top;
            font-size: 7pt;
        }
        
        .core-values-table td:nth-child(2) {
            text-align: left;
            font-size: 6.5pt;
        }
        
        /* Certificate */
        .cert-box {
            border: 1px solid #000;
            padding: 6px;
        }
        
        .cert-box .cert-title {
            font-weight: bold;
            font-size: 8pt;
            text-transform: uppercase;
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }
        
        .underline-field {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 80px;
            flex: 1;
        }
        
        /* Print Button */
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
            background: #1e40af;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }
        
        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4);
        }
        
        /* Print Styles */
        @media print {
            @page {
                size: landscape;
                margin: 0.2in;
            }
            
            body {
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            aside, nav, .sidebar, [class*="sidebar"], #sidebar, .no-print, .fixed.w-72, .fixed[class*="w-72"], [x-show*="mobileOpen"], .lg\:translate-x-0, .backdrop-blur-xl {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important;
                height: 0 !important;
                position: absolute !important;
                left: -9999px !important;
            }
            
            .ml-72, [class*="ml-72"], [class*="ml-"], main, .main-content, #main-content {
                margin-left: 0 !important;
                padding-left: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .sf9-container {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0.15in !important;
                width: 100% !important;
                max-width: 100% !important;
                border: 1px solid #000 !important;
                page-break-inside: avoid;
                box-sizing: border-box;
                min-height: auto !important;
            }
            
            .sf9-table {
                font-size: 7.5pt !important;
                width: 100% !important;
            }
            
            .sf9-table th, .sf9-table td {
                padding: 1px 2px !important;
                border: 1px solid #000 !important;
            }
            
            .print-btn {
                display: none !important;
            }
            
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          init() {
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = false;
              } else {
                  this.mobileOpen = false;
              }
          }
      }"
      x-init="init()"
      @resize.window="
          if (window.innerWidth < 1024) {
              sidebarCollapsed = false;
          }
      ">

<div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
     class="fixed inset-0 z-40 lg:hidden bg-gray-900/50 backdrop-blur-sm"></div>

<button @click="mobileOpen = !mobileOpen"
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center text-gray-600 hover:text-blue-900 transition-all border border-gray-200">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <main class="w-full min-h-screen p-6 transition-all duration-300 ease-out lg:ml-72">

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between no-print">
            <div>
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <a href="<?php echo e(route('student.dashboard')); ?>" class="hover:text-blue-900 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-blue-900 font-medium">SF9 - Progress Report Card</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">School Form 9</h1>
                <p class="text-sm text-gray-500">Learner's Progress Report Card</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-700"><?php echo e($schoolYear); ?></p>
            </div>
        </div>

        <?php
            $user = $selectedStudent->user;
            $section = $selectedStudent->section;
            $gradeLevel = $section->gradeLevel ?? null;
            $age = '';
            if ($selectedStudent->birthdate) {
                $birth = \Carbon\Carbon::parse($selectedStudent->birthdate);
                $now = \Carbon\Carbon::now();
                $age = $birth->diffInYears($now);
            }
        ?>

        
        <?php if($currentQuarter): ?>
        <div class="max-w-[11in] mx-auto mb-3">
            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-2 shadow-sm">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-md bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-xs">
                        Q<?php echo e($currentQuarter->quarter_number); ?>

                    </div>
                    <div>
                        <span class="text-sm font-semibold text-slate-800"><?php echo e($currentQuarter->display_name); ?></span>
                        <span class="text-xs text-slate-500 ml-2"><?php echo e($currentQuarter->start_date?->format('M d')); ?> — <?php echo e($currentQuarter->end_date?->format('M d, Y')); ?></span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>Ongoing
                    </span>
                    <?php if($currentQuarter->progress_percent): ?>
                    <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full" style="width: <?php echo e($currentQuarter->progress_percent); ?>%"></div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-500 w-6"><?php echo e($currentQuarter->progress_percent); ?>%</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="overflow-x-auto pb-4">
        <div class="sf9-container">
        
        <?php if($isKindergarten): ?>
            <!-- KINDERGARTEN LAYOUT -->
            <div class="sf9-main">
                <div class="sf9-left">
                    <?php
                    $kinderConfig = config('kindergarten.domains');
                    $ratingScale = config('kindergarten.rating_scale');
                    $getKinderRating = function($domainKey, $indicatorKey, $quarter) use ($kindergartenDomains) {
                        $domainData = $kindergartenDomains->get($domainKey);
                        if (!$domainData) return '';
                        $indicatorData = $domainData->get($indicatorKey);
                        if (!$indicatorData) return '';
                        $record = $indicatorData->firstWhere('quarter', $quarter);
                        return $record ? $record->rating : '';
                    };
                    ?>
                    
                    <div class="section-title-left"><?php echo e($lang == 'cebuano' ? 'Report sa Kalambuan sa Bata' : 'Developmental Progress Report'); ?></div>
                    <?php $__currentLoopData = $kinderConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainKey => $domainData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div style="border: 1px solid #000; margin-bottom: 4px;">
                        <div style="background: #f3f4f6; padding: 3px 6px; border-bottom: 1px solid #000; font-size: 8pt; font-weight: bold; text-transform: uppercase;">
                            <?php echo e($domainData['name'][$lang] ?? $domainData['name']['cebuano']); ?>

                        </div>
                        <table class="sf9-table" style="font-size: 7.5pt;">
                            <thead>
                                <tr style="background: #f9fafb;">
                                    <th style="width: 50%; text-align: left; padding-left: 6px;"><?php echo e($lang == 'cebuano' ? 'Mga Tigpasiunod' : 'Indicators'); ?></th>
                                    <th style="width: 12.5%;">1</th><th style="width: 12.5%;">2</th><th style="width: 12.5%;">3</th><th style="width: 12.5%;">4</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($domainData['indicators'])): ?>
                                    <?php $__currentLoopData = $domainData['indicators']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorKey => $indicatorData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="font-size: 7pt; text-align: left; padding: 3px 6px;"><?php echo e($indicatorData[$lang] ?? $indicatorData['cebuano']); ?></td>
                                        <td style="font-weight: bold;"><?php echo e($getKinderRating($domainKey, $indicatorKey, 1)); ?></td>
                                        <td style="font-weight: bold;"><?php echo e($getKinderRating($domainKey, $indicatorKey, 2)); ?></td>
                                        <td style="font-weight: bold;"><?php echo e($getKinderRating($domainKey, $indicatorKey, 3)); ?></td>
                                        <td style="font-weight: bold;"><?php echo e($getKinderRating($domainKey, $indicatorKey, 4)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <div style="border: 1px solid #000; padding: 5px;">
                        <div class="section-title" style="margin-top:0; border-bottom: 1px solid #000; padding-bottom: 2px;"><?php echo e($lang == 'cebuano' ? 'Rating Scale' : 'Rating Scale'); ?></div>
                        <table class="grading-scale-table">
                            <tr><td style="width: 15%; text-align: center; font-weight: bold;">B</td><td><strong><?php echo e($ratingScale['B']['label'][$lang] ?? $ratingScale['B']['label']['cebuano']); ?></strong> - <?php echo e($ratingScale['B']['description'][$lang] ?? $ratingScale['B']['description']['cebuano']); ?></td></tr>
                            <tr><td style="text-align: center; font-weight: bold;">D</td><td><strong><?php echo e($ratingScale['D']['label'][$lang] ?? $ratingScale['D']['label']['cebuano']); ?></strong> - <?php echo e($ratingScale['D']['description'][$lang] ?? $ratingScale['D']['description']['cebuano']); ?></td></tr>
                            <tr><td style="text-align: center; font-weight: bold;">C</td><td><strong><?php echo e($ratingScale['C']['label'][$lang] ?? $ratingScale['C']['label']['cebuano']); ?></strong> - <?php echo e($ratingScale['C']['description'][$lang] ?? $ratingScale['C']['description']['cebuano']); ?></td></tr>
                        </table>
                    </div>
                </div>
                
                <div class="sf9-right">
                    <div class="sf9-header" style="border-bottom: 2px solid #000; padding-bottom: 6px;">
                        <img src="<?php echo e(asset('images/edukasyon.jpg')); ?>" alt="DepEd Logo" class="logo">
                        <div class="header-center">
                            <p>Republic of the Philippines</p>
                            <p style="font-weight:bold;">Department of Education</p>
                            <p><?php echo e($schoolRegion ?? 'Region ______'); ?></p>
                            <p>Division of <?php echo e($schoolDivision ?? '____________________'); ?></p>
                            <p>District of <?php echo e($schoolDistrict ?? '__________'); ?></p>
                            <p class="school-name"><?php echo e($schoolName ?? 'SCHOOL NAME'); ?></p>
                            <p class="report-title">Progress Report Card</p>
                            <p class="school-year">School Year <?php echo e($schoolYear); ?></p>
                        </div>
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="School Logo" class="logo">
                    </div>
                    
                    <div class="student-info-box">
                        <div class="info-row"><span class="label">Name:</span><span class="value"><?php echo e($user->last_name ?? ''); ?>, <?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">Age:</span><span class="value" style="flex:0 0 50px;"><?php echo e(is_numeric($age) ? floor($age) : ''); ?></span><span class="label" style="margin-left:10px;">Sex:</span><span class="value" style="flex:0 0 60px;"><?php echo e($selectedStudent->gender ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">Grade:</span><span class="value" style="flex:0 0 80px;"><?php echo e($gradeLevel->name ?? ''); ?></span><span class="label" style="margin-left:10px;">Section:</span><span class="value" style="flex:0 0 80px;"><?php echo e($section->name ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">LRN:</span><span class="value"><?php echo e($selectedStudent->lrn ?? ''); ?></span></div>
                    </div>
                    
                    <div class="dear-parent">
                        <strong>Dear Parent,</strong>
                        <p style="margin-top:3px;">This report card shows the ability and the progress your child has made in the different learning areas as well as his/her core values.</p>
                        <p style="margin-top:2px;">The school welcomes you should you desire to know more about your child's progress.</p>
                    </div>
                    
                    <div class="teacher-sig">
                        <p class="name"><?php echo e($adviserName ?? ''); ?></p>
                        <p class="title">Teacher</p>
                    </div>
                    
                    <div style="border: 1px solid #000; padding: 6px; margin-top: 6px;">
                        <p style="font-size: 8pt; line-height: 1.3;">
                            <?php if($lang == 'cebuano'): ?>
                                Gipasabot niini nga si <strong><?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?> <?php echo e($user->last_name ?? ''); ?></strong> sa <strong><?php echo e($section->name ?? ''); ?></strong> niini nga tulunghaan, nakalampos sa Kindergarten Curriculum Guide.
                            <?php else: ?>
                                This certifies that <strong><?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?> <?php echo e($user->last_name ?? ''); ?></strong> of <strong><?php echo e($section->name ?? ''); ?></strong> has completed the Kindergarten Curriculum Guide.
                            <?php endif; ?>
                        </p>
                        <div style="margin-top: 8px; display: flex; justify-content: space-between;">
                            <div style="text-align: center;">
                                <p style="font-weight: bold; text-transform: uppercase; font-size: 8pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;"><?php echo e($adviserName ?? ''); ?></p>
                                <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;"><?php echo e($lang == 'cebuano' ? 'Magtutudlo' : 'Teacher'); ?></p>
                            </div>
                            <div style="text-align: center;">
                                <p style="font-weight: bold; text-transform: uppercase; font-size: 8pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;"><?php echo e($schoolHead ?? ''); ?></p>
                                <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Principal</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- GRADES 1-6 LAYOUT -->
            <div class="sf9-main">
                <!-- LEFT COLUMN -->
                <div class="sf9-left">
                    
                    <!-- Report on Learner's Observed Values -->
                    <div class="section-title-left">Report on Learner's Observed Values</div>
                    <?php
                    $coreValueOrder = ['Maka-Diyos', 'Makatao', 'Maka-Kalikasan', 'Maka-bansa'];
                    $sortedCoreValues = collect($coreValueOrder)->mapWithKeys(function($cv) use ($coreValues) {
                        return $coreValues->has($cv) ? [$cv => $coreValues[$cv]] : [];
                    });
                    foreach ($coreValues as $cv => $statements) {
                        if (!in_array($cv, $coreValueOrder)) $sortedCoreValues[$cv] = $statements;
                    }
                    $getCoreValueRating = function($coreValue, $statementKey, $quarter) use ($sortedCoreValues) {
                        $cvData = $sortedCoreValues->get($coreValue);
                        if (!$cvData) return '';
                        $statementData = $cvData->get($statementKey);
                        if (!$statementData) return '';
                        $record = $statementData->firstWhere('quarter', $quarter);
                        return $record ? $record->rating : '';
                    };
                    $getBehaviorStatement = function($coreValue, $statementKey) use ($sortedCoreValues) {
                        $cvData = $sortedCoreValues->get($coreValue);
                        if (!$cvData) return '';
                        $statementData = $cvData->get($statementKey);
                        if (!$statementData || $statementData->isEmpty()) return '';
                        return $statementData->first()->behavior_statement ?? '';
                    };
                    ?>
                    <table class="sf9-table core-values-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 10%; font-size: 6.5pt;">Core<br>Values</th>
                                <th rowspan="2" style="width: 56%; font-size: 6.5pt; text-align: left;">Behavioral Statement</th>
                                <th colspan="4" style="font-size: 6.5pt;">Quarter</th>
                            </tr>
                            <tr>
                                <th style="width: 8.5%; font-size: 6.5pt;">1</th>
                                <th style="width: 8.5%; font-size: 6.5pt;">2</th>
                                <th style="width: 8.5%; font-size: 6.5pt;">3</th>
                                <th style="width: 8.5%; font-size: 6.5pt;">4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cvNum = 1; ?>
                            <?php $__currentLoopData = $sortedCoreValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coreValue => $statements): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $statementKeys = $statements->keys()->sort()->values(); ?>
                                <?php $__currentLoopData = $statementKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $statementKey): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if($idx === 0): ?>
                                        <td rowspan="<?php echo e($statementKeys->count()); ?>" style="font-weight: bold; font-size: 7pt; vertical-align: top;"><?php echo e($cvNum); ?>. <?php echo e($coreValue); ?></td>
                                    <?php endif; ?>
                                    <td style="font-size: 6pt; text-align: left; line-height: 1.15;"><?php echo e($getBehaviorStatement($coreValue, $statementKey)); ?></td>
                                    <td style="font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 1)); ?></td>
                                    <td style="font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 2)); ?></td>
                                    <td style="font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 3)); ?></td>
                                    <td style="font-weight: bold;"><?php echo e($getCoreValueRating($coreValue, $statementKey, 4)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php $cvNum++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($sortedCoreValues->isEmpty()): ?>
                                <tr><td colspan="6" style="text-align: center; padding: 6px; color: #666;">No core values records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div style="font-size: 6.5pt; margin-top: 3px; text-align: center;">
                        <strong>Marking:</strong> AO - Always Observed | SO - Sometimes Observed | RO - Rarely Observed | NO - Not Observed
                    </div>
                    
                    <!-- Parent/Guardian's Signature -->
                    <div class="section-title-left" style="margin-top: 4px;">Parent/Guardian's Signature</div>
                    <div class="parent-sig-grid">
                        <?php $__currentLoopData = ['First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $quarter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="sig-box"><div class="line"></div><div class="label"><?php echo e($quarter); ?></div></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    
                    <!-- Certificates pushed to bottom -->
                    <div style="margin-top: auto;">
                        
                        <!-- Certificate of Transfer -->
                        <div class="cert-box" style="margin-top: 6px;">
                            <div class="cert-title">Certificate of Transfer</div>
                            <div style="font-size: 8pt; line-height: 1.4;">
                                <div style="display: flex; gap: 8px; margin-bottom: 3px;">
                                    <span>Admitted to Grade:</span><span class="underline-field"></span>
                                    <span>Section:</span><span class="underline-field" style="flex: 0 0 100px;"></span>
                                </div>
                                <div style="display: flex; gap: 8px; margin-bottom: 3px;">
                                    <span>Eligible for admission to Grade:</span><span class="underline-field"></span>
                                </div>
                                <div style="display: flex; gap: 8px; margin-bottom: 3px;">
                                    <span>Approved:</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-top: 6px;">
                                    <div style="text-align: center;">
                                        <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;"><?php echo e($adviserName ?? ''); ?></p>
                                        <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Adviser</p>
                                    </div>
                                    <div style="text-align: center;">
                                        <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;"><?php echo e($schoolHead ?? ''); ?></p>
                                        <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Principal</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Cancellation of Eligibility -->
                        <div class="cert-box" style="margin-top: 4px;">
                            <div class="cert-title">Cancellation of Eligibility to Transfer</div>
                            <div style="font-size: 8pt; line-height: 1.4;">
                                <div style="display: flex; gap: 8px; margin-bottom: 3px;">
                                    <span>Admitted in:</span><span class="underline-field"></span>
                                    <span>Date:</span><span class="underline-field" style="flex: 0 0 100px;"></span>
                                </div>
                                <div style="display: flex; justify-content: flex-end; margin-top: 6px;">
                                    <div style="text-align: center;">
                                        <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;"><?php echo e($schoolHead ?? ''); ?></p>
                                        <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Principal</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- RIGHT COLUMN -->
                <div class="sf9-right">
                    <!-- Header with Logos -->
                    <div class="sf9-header" style="border-bottom: 2px solid #000; padding-bottom: 6px;">
                        <img src="<?php echo e(asset('images/edukasyon.jpg')); ?>" alt="DepEd Logo" class="logo">
                        <div class="header-center">
                            <p>Republic of the Philippines</p>
                            <p style="font-weight:bold;">Department of Education</p>
                            <p><?php echo e($schoolRegion ?? 'Region ______'); ?></p>
                            <p>Division of <?php echo e($schoolDivision ?? '____________________'); ?></p>
                            <p>District of <?php echo e($schoolDistrict ?? '__________'); ?></p>
                            <p class="school-name"><?php echo e($schoolName ?? 'SCHOOL NAME'); ?></p>
                            <p class="report-title">Progress Report Card</p>
                            <p class="school-year">School Year <?php echo e($schoolYear); ?></p>
                        </div>
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="School Logo" class="logo">
                    </div>
                    
                    <!-- Student Info -->
                    <div class="student-info-box">
                        <div class="info-row"><span class="label">Name:</span><span class="value"><?php echo e($user->last_name ?? ''); ?>, <?php echo e($user->first_name ?? ''); ?> <?php echo e($user->middle_name ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">Age:</span><span class="value" style="flex:0 0 50px;"><?php echo e(is_numeric($age) ? floor($age) : ''); ?></span><span class="label" style="margin-left:10px;">Sex:</span><span class="value" style="flex:0 0 60px;"><?php echo e($selectedStudent->gender ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">Grade:</span><span class="value" style="flex:0 0 80px;"><?php echo e($gradeLevel->name ?? ''); ?></span><span class="label" style="margin-left:10px;">Section:</span><span class="value" style="flex:0 0 80px;"><?php echo e($section->name ?? ''); ?></span></div>
                        <div class="info-row"><span class="label">LRN:</span><span class="value"><?php echo e($selectedStudent->lrn ?? ''); ?></span></div>
                    </div>
                    
                    <!-- Dear Parent -->
                    <div class="dear-parent">
                        <strong>Dear Parent,</strong>
                        <p style="margin-top:3px;">This report card shows the ability and the progress your child has made in the different learning areas as well as his/her core values.</p>
                        <p style="margin-top:2px;">The school welcomes you should you desire to know more about your child's progress.</p>
                    </div>
                    
                    <!-- Teacher Signature -->
                    <div class="teacher-sig">
                        <p class="name"><?php echo e($adviserName ?? ''); ?></p>
                        <p class="title">Teacher</p>
                    </div>
                    
                    <!-- Report on Learning Progress and Achievement -->
                    <div class="section-title" style="margin-top: 6px;">Report on Learning Progress and Achievement</div>
                    <table class="sf9-table">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 32%; text-align: left; padding-left: 5px; font-size: 7.5pt;">Learning Areas</th>
                                <th colspan="4" style="font-size: 7.5pt;">Quarter</th>
                                <th rowspan="2" style="width: 10%; font-size: 7.5pt;">Final<br>Rating</th>
                                <th rowspan="2" style="width: 10%; font-size: 7.5pt;">Remarks</th>
                            </tr>
                            <tr>
                                <th style="width: 9%; font-size: 7.5pt;">1</th>
                                <th style="width: 9%; font-size: 7.5pt;">2</th>
                                <th style="width: 9%; font-size: 7.5pt;">3</th>
                                <th style="width: 9%; font-size: 7.5pt;">4</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $subjectGrades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subjectGrade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="text-align: left; padding-left: 4px; font-size: 7.5pt;"><?php echo e($subjectGrade['subject_name']); ?></td>
                                <td><?php echo e($subjectGrade['quarter_1'] ?: ''); ?></td>
                                <td><?php echo e($subjectGrade['quarter_2'] ?: ''); ?></td>
                                <td><?php echo e($subjectGrade['quarter_3'] ?: ''); ?></td>
                                <td><?php echo e($subjectGrade['quarter_4'] ?: ''); ?></td>
                                <td style="font-weight: bold;"><?php echo e($subjectGrade['final_grade'] ?: ''); ?></td>
                                <td><?php echo e($subjectGrade['remarks'] ?: ''); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="7" style="padding: 6px; color: #666;">No subjects found.</td></tr>
                            <?php endif; ?>
                            <?php if($generalAverage !== null): ?>
                            <tr style="font-weight: bold; background: #f9fafb;">
                                <td colspan="5" style="text-align: right; padding-right: 6px;">General Average</td>
                                <td><?php echo e($generalAverage); ?></td>
                                <td><?php echo e($generalAverage >= 75 ? 'Passed' : 'Failed'); ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <!-- Descriptors and Grading Scale -->
                    <div class="section-title" style="margin-top: 4px;">Descriptors and Grading Scale</div>
                    <table class="grading-scale-table">
                        <tr><td style="width: 50%;">Outstanding</td><td style="width: 25%; font-weight: bold;">90-100</td><td style="width: 25%;">Passed</td></tr>
                        <tr><td>Very Satisfactory</td><td style="font-weight: bold;">85-89</td><td>Passed</td></tr>
                        <tr><td>Satisfactory</td><td style="font-weight: bold;">80-84</td><td>Passed</td></tr>
                        <tr><td>Fairly Satisfactory</td><td style="font-weight: bold;">75-79</td><td>Passed</td></tr>
                        <tr><td>Did Not Meet Expectations</td><td style="font-weight: bold;">Below 75</td><td style="font-weight: bold;">Failed</td></tr>
                    </table>
                    
                    <!-- Report on Attendance (at bottom) -->
                    <div style="margin-top: auto;">
                    <div class="section-title" style="margin-top: 0;">Report on Attendance</div>
                    <?php
                        $months = ['JUN','JUL','AUG','SEP','OCT','NOV','DEC','JAN','FEB','MAR','APR'];
                        $attendanceData = []; $totalPresent = 0; $totalAbsent = 0; $totalLate = 0; $totalSchoolDays = 0;
                        foreach ($months as $month) {
                            $monthAttendances = $attendances->filter(function($a) use ($month) {
                                return strtoupper(date('M', strtotime($a->date))) === $month;
                            });
                            $present = $monthAttendances->where('status', 'present')->count();
                            $absent = $monthAttendances->where('status', 'absent')->count();
                            $late = $monthAttendances->where('status', 'late')->count();
                            $schoolDays = $present + $absent + $late;
                            $attendanceData[$month] = [
                                'days' => $schoolDays > 0 ? $schoolDays : '',
                                'present' => $present > 0 ? $present : '',
                                'absent' => $absent > 0 ? $absent : '',
                                'late' => $late > 0 ? $late : ''
                            ];
                            $totalPresent += $present; $totalAbsent += $absent; $totalLate += $late; $totalSchoolDays += $schoolDays;
                        }
                    ?>
                    <table class="sf9-table attendance-table">
                        <thead>
                            <tr>
                                <th style="width: 22%; font-size: 7pt;"></th>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <th style="width: 6%; font-size: 6.5pt; padding: 1px;"><?php echo e($month); ?></th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <th style="width: 6%; font-size: 7pt;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of School Days</td>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($attendanceData[$month]['days']); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="font-weight: bold;"><?php echo e($totalSchoolDays > 0 ? $totalSchoolDays : ''); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Days Present</td>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($attendanceData[$month]['present']); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="font-weight: bold;"><?php echo e($totalPresent > 0 ? $totalPresent : ''); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Days Absent</td>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($attendanceData[$month]['absent']); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="font-weight: bold;"><?php echo e($totalAbsent > 0 ? $totalAbsent : ''); ?></td>
                            </tr>
                            <tr>
                                <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Times Tardy</td>
                                <?php $__currentLoopData = $months; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><td><?php echo e($attendanceData[$month]['late']); ?></td><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <td style="font-weight: bold;"><?php echo e($totalLate > 0 ? $totalLate : ''); ?></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

            <!-- Footer -->
            <div style="text-align: center; margin-top: 8px; font-size: 7pt; color: #666;">
                <p><strong>DepEd School Form 9 (SF9)</strong> | Page 1 of 1</p>
                <p>Generated: <?php echo e(now()->format('F d, Y h:i A')); ?></p>
            </div>
        </div>
        </div>
    </main>
</div>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\grades\index.blade.php ENDPATH**/ ?>