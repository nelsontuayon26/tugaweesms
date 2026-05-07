<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 9 (SF9) - Learner's Progress Report Card</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        
        /* Student Selector */
        .student-selector-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #d1d5db;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 min-h-screen" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
     class="fixed inset-0 z-40 lg:hidden bg-gray-900/50 backdrop-blur-sm"></div>

<!-- Mobile Hamburger -->
<button @click="mobileOpen = !mobileOpen"
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-xl shadow-lg flex items-center justify-center text-gray-600 hover:text-blue-900 transition-all border border-gray-200">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    @include('teacher.includes.sidebar')

    <main class="lg:ml-72 w-full min-h-screen p-6">

        <!-- Header -->
        <div class="mb-6 flex items-center justify-between no-print">
            <div>
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <a href="{{ route('teacher.dashboard') }}" class="hover:text-blue-900 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-blue-900 font-medium">SF9 - Progress Report Card</span>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">School Form 9</h1>
                <p class="text-sm text-gray-500">Learner's Progress Report Card</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-700">{{ $schoolYear }}</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6" style="align-items: flex-start;">
            
            <!-- LEFT SIDEBAR (always visible) -->
            <div class="no-print w-full lg:w-[300px] flex-shrink-0">
                
                <!-- Student Selector -->
                <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden">
                    <div class="bg-blue-900 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-user-graduate"></i>
                        <span class="font-semibold">Select Student</span>
                    </div>
                    <div class="p-4">
                        <form method="GET" action="{{ route('teacher.sf9') }}">
                            <select name="student_id" onchange="this.form.submit()" 
                                class="w-full px-3 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 text-gray-700 text-sm bg-gray-50 mb-3">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <noscript>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-900 text-white rounded-lg hover:bg-blue-800 transition font-semibold text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-search"></i> Load
                                </button>
                            </noscript>
                        </form>
                    </div>
                </div>

                @if($selectedStudent)
                @if($isKindergarten)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden">
                    <div class="bg-purple-900 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-child"></i>
                        <span class="font-semibold">Kindergarten Progress</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700"><i class="fas fa-puzzle-piece"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Developmental Domains</p>
                                <p class="text-xs text-gray-500">{{ count(config('kindergarten.domains', [])) }} areas tracked</p>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden">
                    <div class="bg-blue-900 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-chart-bar"></i>
                        <span class="font-semibold">Grade Summary</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700"><i class="fas fa-book"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Subjects</p>
                                <p class="text-xs text-gray-500">{{ $subjectGrades->count() }} learning areas</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-700"><i class="fas fa-check-circle"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Passed</p>
                                <p class="text-xs text-gray-500">{{ $subjectGrades->where('remarks', 'Passed')->count() }} subjects</p>
                            </div>
                        </div>
                        @if($generalAverage !== null)
                        <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-700"><i class="fas fa-award"></i></div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">General Average</p>
                                <p class="text-lg font-bold text-purple-700">{{ $generalAverage }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($selectedStudent && !$isKindergarten)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden">
                    <div class="bg-gray-800 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-list-ol"></i>
                        <span class="font-semibold">Subject Grades</span>
                    </div>
                    <div class="p-4 space-y-2 max-h-80 overflow-y-auto">
                        @forelse($subjectGrades as $subjectGrade)
                        <div class="flex items-center justify-between p-2 rounded-lg {{ $subjectGrade['final_grade'] !== null ? ($subjectGrade['final_grade'] >= 75 ? 'bg-green-50' : 'bg-red-50') : 'bg-gray-50' }}">
                            <div>
                                <p class="text-sm font-medium text-gray-700">{{ $subjectGrade['subject_name'] }}</p>
                                <p class="text-xs text-gray-500">Q{{ $subjectGrade['quarter_1'] ? '1:'.$subjectGrade['quarter_1'] : '' }} {{ $subjectGrade['quarter_2'] ? 'Q2:'.$subjectGrade['quarter_2'] : '' }} {{ $subjectGrade['quarter_3'] ? 'Q3:'.$subjectGrade['quarter_3'] : '' }} {{ $subjectGrade['quarter_4'] ? 'Q4:'.$subjectGrade['quarter_4'] : '' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold {{ $subjectGrade['final_grade'] !== null ? ($subjectGrade['final_grade'] >= 75 ? 'text-green-700' : 'text-red-700') : 'text-gray-400' }}">{{ $subjectGrade['final_grade'] ?: '-' }}</p>
                                <p class="text-xs {{ $subjectGrade['remarks'] == 'Passed' ? 'text-green-600' : ($subjectGrade['remarks'] == 'Failed' ? 'text-red-600' : 'text-gray-400') }}">{{ $subjectGrade['remarks'] ?: 'No Grade' }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500 text-center py-4">No grades available</p>
                        @endforelse
                    </div>
                </div>
                @endif

                @if($isKindergarten)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 mb-4 overflow-hidden">
                    <div class="bg-amber-700 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-language"></i>
                        <span class="font-semibold">Language</span>
                    </div>
                    <div class="p-4">
                        <form method="GET" action="{{ route('teacher.sf9') }}">
                            <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">
                            <div class="flex gap-2">
                                <button type="submit" name="lang" value="cebuano" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition {{ $lang == 'cebuano' ? 'bg-amber-100 text-amber-800 border-2 border-amber-500' : 'bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200' }}">Cebuano</button>
                                <button type="submit" name="lang" value="english" class="flex-1 px-3 py-2 rounded-lg text-sm font-medium transition {{ $lang == 'english' ? 'bg-amber-100 text-amber-800 border-2 border-amber-500' : 'bg-gray-100 text-gray-700 border border-gray-200 hover:bg-gray-200' }}">English</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                @endif

                <button onclick="window.print()" class="no-print w-full px-4 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-900 transition font-semibold flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i> Print Report Card
                </button>
            </div>
            <!-- /LEFT SIDEBAR -->
            
            <!-- RIGHT: Report Card Area -->
            <div class="flex-1 min-w-0 overflow-x-auto">
            @if(!$selectedStudent)
            <div class="sf9-container" style="display: flex; align-items: center; justify-content: center; min-height: 4in;">
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                    <p class="text-amber-800 font-medium">Please select a student to view their report card.</p>
                </div>
            </div>
            @endif

            @if($selectedStudent)
            @php
                $user = $selectedStudent->user;
                $section = $selectedStudent->section;
                $gradeLevel = $section->gradeLevel ?? null;
                $age = '';
                if ($selectedStudent->birthdate) {
                    $birth = \Carbon\Carbon::parse($selectedStudent->birthdate);
                    $now = \Carbon\Carbon::now();
                    $age = $birth->diffInYears($now);
                }
            @endphp

            <div class="sf9-container">
            
            @if($isKindergarten)
                <!-- KINDERGARTEN LAYOUT -->
                <div class="sf9-main">
                    <div class="sf9-left">
                        @php
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
                        @endphp
                        
                        <div class="section-title-left">{{ $lang == 'cebuano' ? 'Report sa Kalambuan sa Bata' : 'Developmental Progress Report' }}</div>
                        @foreach($kinderConfig as $domainKey => $domainData)
                        <div style="border: 1px solid #000; margin-bottom: 4px;">
                            <div style="background: #f3f4f6; padding: 3px 6px; border-bottom: 1px solid #000; font-size: 8pt; font-weight: bold; text-transform: uppercase;">
                                {{ $domainData['name'][$lang] ?? $domainData['name']['cebuano'] }}
                            </div>
                            <table class="sf9-table" style="font-size: 7.5pt;">
                                <thead>
                                    <tr style="background: #f9fafb;">
                                        <th style="width: 50%; text-align: left; padding-left: 6px;">{{ $lang == 'cebuano' ? 'Mga Tigpasiunod' : 'Indicators' }}</th>
                                        <th style="width: 12.5%;">1</th><th style="width: 12.5%;">2</th><th style="width: 12.5%;">3</th><th style="width: 12.5%;">4</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($domainData['indicators']))
                                        @foreach($domainData['indicators'] as $indicatorKey => $indicatorData)
                                        <tr>
                                            <td style="font-size: 7pt; text-align: left; padding: 3px 6px;">{{ $indicatorData[$lang] ?? $indicatorData['cebuano'] }}</td>
                                            <td style="font-weight: bold;">{{ $getKinderRating($domainKey, $indicatorKey, 1) }}</td>
                                            <td style="font-weight: bold;">{{ $getKinderRating($domainKey, $indicatorKey, 2) }}</td>
                                            <td style="font-weight: bold;">{{ $getKinderRating($domainKey, $indicatorKey, 3) }}</td>
                                            <td style="font-weight: bold;">{{ $getKinderRating($domainKey, $indicatorKey, 4) }}</td>
                                        </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                        
                        <div style="border: 1px solid #000; padding: 5px;">
                            <div class="section-title" style="margin-top:0; border-bottom: 1px solid #000; padding-bottom: 2px;">{{ $lang == 'cebuano' ? 'Rating Scale' : 'Rating Scale' }}</div>
                            <table class="grading-scale-table">
                                <tr><td style="width: 15%; text-align: center; font-weight: bold;">B</td><td><strong>{{ $ratingScale['B']['label'][$lang] ?? $ratingScale['B']['label']['cebuano'] }}</strong> - {{ $ratingScale['B']['description'][$lang] ?? $ratingScale['B']['description']['cebuano'] }}</td></tr>
                                <tr><td style="text-align: center; font-weight: bold;">D</td><td><strong>{{ $ratingScale['D']['label'][$lang] ?? $ratingScale['D']['label']['cebuano'] }}</strong> - {{ $ratingScale['D']['description'][$lang] ?? $ratingScale['D']['description']['cebuano'] }}</td></tr>
                                <tr><td style="text-align: center; font-weight: bold;">C</td><td><strong>{{ $ratingScale['C']['label'][$lang] ?? $ratingScale['C']['label']['cebuano'] }}</strong> - {{ $ratingScale['C']['description'][$lang] ?? $ratingScale['C']['description']['cebuano'] }}</td></tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="sf9-right">
                        <div class="sf9-header" style="border-bottom: 2px solid #000; padding-bottom: 6px;">
                            <img src="{{ asset('images/edukasyon.jpg') }}" alt="DepEd Logo" class="logo">
                            <div class="header-center">
                                <p>Republic of the Philippines</p>
                                <p style="font-weight:bold;">Department of Education</p>
                                <p>{{ $schoolRegion ?? 'Region ______' }}</p>
                                <p>Division of {{ $schoolDivision ?? '____________________' }}</p>
                                <p>District of {{ $schoolDistrict ?? '__________' }}</p>
                                <p class="school-name">{{ $schoolName ?? 'SCHOOL NAME' }}</p>
                                <p class="report-title">Progress Report Card</p>
                                <p class="school-year">School Year {{ $schoolYear }}</p>
                            </div>
                            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="logo">
                        </div>
                        
                        <div class="student-info-box">
                            <div class="info-row"><span class="label">Name:</span><span class="value">{{ $user->last_name ?? '' }}, {{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }}</span></div>
                            <div class="info-row"><span class="label">Age:</span><span class="value" style="flex:0 0 50px;">{{ is_numeric($age) ? floor($age) : '' }}</span><span class="label" style="margin-left:10px;">Sex:</span><span class="value" style="flex:0 0 60px;">{{ $selectedStudent->gender ?? '' }}</span></div>
                            <div class="info-row"><span class="label">Grade:</span><span class="value" style="flex:0 0 80px;">{{ $gradeLevel->name ?? '' }}</span><span class="label" style="margin-left:10px;">Section:</span><span class="value" style="flex:0 0 80px;">{{ $section->name ?? '' }}</span></div>
                            <div class="info-row"><span class="label">LRN:</span><span class="value">{{ $selectedStudent->lrn ?? '' }}</span></div>
                        </div>
                        
                        <div class="dear-parent">
                            <strong>Dear Parent,</strong>
                            <p style="margin-top:3px;">This report card shows the ability and the progress your child has made in the different learning areas as well as his/her core values.</p>
                            <p style="margin-top:2px;">The school welcomes you should you desire to know more about your child's progress.</p>
                        </div>
                        
                        <div class="teacher-sig">
                            <p class="name">{{ $adviserName ?? '' }}</p>
                            <p class="title">Teacher</p>
                        </div>
                        
                        <div style="border: 1px solid #000; padding: 6px; margin-top: 6px;">
                            <p style="font-size: 8pt; line-height: 1.3;">
                                @if($lang == 'cebuano')
                                    Gipasabot niini nga si <strong>{{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }}</strong> sa <strong>{{ $section->name ?? '' }}</strong> niini nga tulunghaan, nakalampos sa Kindergarten Curriculum Guide.
                                @else
                                    This certifies that <strong>{{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }}</strong> of <strong>{{ $section->name ?? '' }}</strong> has completed the Kindergarten Curriculum Guide.
                                @endif
                            </p>
                            <div style="margin-top: 8px; display: flex; justify-content: space-between;">
                                <div style="text-align: center;">
                                    <p style="font-weight: bold; text-transform: uppercase; font-size: 8pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;">{{ $adviserName ?? '' }}</p>
                                    <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">{{ $lang == 'cebuano' ? 'Magtutudlo' : 'Teacher' }}</p>
                                </div>
                                <div style="text-align: center;">
                                    <p style="font-weight: bold; text-transform: uppercase; font-size: 8pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;">{{ $schoolHead ?? '' }}</p>
                                    <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Principal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- GRADES 1-6 LAYOUT -->
                <div class="sf9-main">
                    <!-- LEFT COLUMN -->
                    <div class="sf9-left">
                        
                        <!-- Report on Learner's Observed Values -->
                        <div class="section-title-left">Report on Learner's Observed Values</div>
                        @php
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
                        @endphp
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
                                @php $cvNum = 1; @endphp
                                @foreach($sortedCoreValues as $coreValue => $statements)
                                    @php $statementKeys = $statements->keys()->sort()->values(); @endphp
                                    @foreach($statementKeys as $idx => $statementKey)
                                    <tr>
                                        @if($idx === 0)
                                            <td rowspan="{{ $statementKeys->count() }}" style="font-weight: bold; font-size: 7pt; vertical-align: top;">{{ $cvNum }}. {{ $coreValue }}</td>
                                        @endif
                                        <td style="font-size: 6pt; text-align: left; line-height: 1.15;">{{ $getBehaviorStatement($coreValue, $statementKey) }}</td>
                                        <td style="font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 1) }}</td>
                                        <td style="font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 2) }}</td>
                                        <td style="font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 3) }}</td>
                                        <td style="font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 4) }}</td>
                                    </tr>
                                    @endforeach
                                    @php $cvNum++; @endphp
                                @endforeach
                                @if($sortedCoreValues->isEmpty())
                                    <tr><td colspan="6" style="text-align: center; padding: 6px; color: #666;">No core values records found.</td></tr>
                                @endif
                            </tbody>
                        </table>
                        <div style="font-size: 6.5pt; margin-top: 3px; text-align: center;">
                            <strong>Marking:</strong> AO - Always Observed | SO - Sometimes Observed | RO - Rarely Observed | NO - Not Observed
                        </div>
                        
                        <!-- Parent/Guardian's Signature -->
                        <div class="section-title-left" style="margin-top: 4px;">Parent/Guardian's Signature</div>
                        <div class="parent-sig-grid">
                            @foreach(['First Quarter', 'Second Quarter', 'Third Quarter', 'Fourth Quarter'] as $quarter)
                            <div class="sig-box"><div class="line"></div><div class="label">{{ $quarter }}</div></div>
                            @endforeach
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
                                            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;">{{ $adviserName ?? '' }}</p>
                                            <p style="font-size: 7.5pt; margin: 0; line-height: 1.1;">Adviser</p>
                                        </div>
                                        <div style="text-align: center;">
                                            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;">{{ $schoolHead ?? '' }}</p>
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
                                            <p style="font-weight: bold; text-transform: uppercase; font-size: 7.5pt; margin: 0; border-bottom: 1px solid #000; padding-bottom: 2px; line-height: 1.1;">{{ $schoolHead ?? '' }}</p>
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
                            <img src="{{ asset('images/edukasyon.jpg') }}" alt="DepEd Logo" class="logo">
                            <div class="header-center">
                                <p>Republic of the Philippines</p>
                                <p style="font-weight:bold;">Department of Education</p>
                                <p>{{ $schoolRegion ?? 'Region ______' }}</p>
                                <p>Division of {{ $schoolDivision ?? '____________________' }}</p>
                                <p>District of {{ $schoolDistrict ?? '__________' }}</p>
                                <p class="school-name">{{ $schoolName ?? 'SCHOOL NAME' }}</p>
                                <p class="report-title">Progress Report Card</p>
                                <p class="school-year">School Year {{ $schoolYear }}</p>
                            </div>
                            <img src="{{ asset('images/logo.png') }}" alt="School Logo" class="logo">
                        </div>
                        
                        <!-- Student Info -->
                        <div class="student-info-box">
                            <div class="info-row"><span class="label">Name:</span><span class="value">{{ $user->last_name ?? '' }}, {{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }}</span></div>
                            <div class="info-row"><span class="label">Age:</span><span class="value" style="flex:0 0 50px;">{{ is_numeric($age) ? floor($age) : '' }}</span><span class="label" style="margin-left:10px;">Sex:</span><span class="value" style="flex:0 0 60px;">{{ $selectedStudent->gender ?? '' }}</span></div>
                            <div class="info-row"><span class="label">Grade:</span><span class="value" style="flex:0 0 80px;">{{ $gradeLevel->name ?? '' }}</span><span class="label" style="margin-left:10px;">Section:</span><span class="value" style="flex:0 0 80px;">{{ $section->name ?? '' }}</span></div>
                            <div class="info-row"><span class="label">LRN:</span><span class="value">{{ $selectedStudent->lrn ?? '' }}</span></div>
                        </div>
                        
                        <!-- Dear Parent -->
                        <div class="dear-parent">
                            <strong>Dear Parent,</strong>
                            <p style="margin-top:3px;">This report card shows the ability and the progress your child has made in the different learning areas as well as his/her core values.</p>
                            <p style="margin-top:2px;">The school welcomes you should you desire to know more about your child's progress.</p>
                        </div>
                        
                        <!-- Teacher Signature -->
                        <div class="teacher-sig">
                            <p class="name">{{ $adviserName ?? '' }}</p>
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
                                @forelse($subjectGrades as $subjectGrade)
                                <tr>
                                    <td style="text-align: left; padding-left: 4px; font-size: 7.5pt;">{{ $subjectGrade['subject_name'] }}</td>
                                    <td>{{ $subjectGrade['quarter_1'] ?: '' }}</td>
                                    <td>{{ $subjectGrade['quarter_2'] ?: '' }}</td>
                                    <td>{{ $subjectGrade['quarter_3'] ?: '' }}</td>
                                    <td>{{ $subjectGrade['quarter_4'] ?: '' }}</td>
                                    <td style="font-weight: bold;">{{ $subjectGrade['final_grade'] ?: '' }}</td>
                                    <td>{{ $subjectGrade['remarks'] ?: '' }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="7" style="padding: 6px; color: #666;">No subjects found.</td></tr>
                                @endforelse
                                @if($generalAverage !== null)
                                <tr style="font-weight: bold; background: #f9fafb;">
                                    <td colspan="5" style="text-align: right; padding-right: 6px;">General Average</td>
                                    <td>{{ $generalAverage }}</td>
                                    <td>{{ $generalAverage >= 75 ? 'Passed' : 'Failed' }}</td>
                                </tr>
                                @endif
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
                        @php
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
                        @endphp
                        <table class="sf9-table attendance-table">
                            <thead>
                                <tr>
                                    <th style="width: 22%; font-size: 7pt;"></th>
                                    @foreach($months as $month)
                                        <th style="width: 6%; font-size: 6.5pt; padding: 1px;">{{ $month }}</th>
                                    @endforeach
                                    <th style="width: 6%; font-size: 7pt;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of School Days</td>
                                    @foreach($months as $month)<td>{{ $attendanceData[$month]['days'] }}</td>@endforeach
                                    <td style="font-weight: bold;">{{ $totalSchoolDays > 0 ? $totalSchoolDays : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Days Present</td>
                                    @foreach($months as $month)<td>{{ $attendanceData[$month]['present'] }}</td>@endforeach
                                    <td style="font-weight: bold;">{{ $totalPresent > 0 ? $totalPresent : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Days Absent</td>
                                    @foreach($months as $month)<td>{{ $attendanceData[$month]['absent'] }}</td>@endforeach
                                    <td style="font-weight: bold;">{{ $totalAbsent > 0 ? $totalAbsent : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; font-size: 7pt; padding-left: 4px;">No. of Times Tardy</td>
                                    @foreach($months as $month)<td>{{ $attendanceData[$month]['late'] }}</td>@endforeach
                                    <td style="font-weight: bold;">{{ $totalLate > 0 ? $totalLate : '' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

                <!-- Footer -->
                <div style="text-align: center; margin-top: 8px; font-size: 7pt; color: #666;">
                    <p><strong>DepEd School Form 9 (SF9)</strong> | Page 1 of 1</p>
                    <p>Generated: {{ now()->format('F d, Y h:i A') }}</p>
                </div>
            </div>
            @endif
            </div>
            <!-- /RIGHT: Report Card Area -->
        </div>
    </main>
</div>

<button onclick="window.print()" class="print-btn no-print">
    <i class="fas fa-print text-white text-xl"></i>
</button>

</body>
</html>
