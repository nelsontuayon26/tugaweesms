<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 10 (SF10-ES) - Learner's Permanent Academic Record</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            background: #f1f5f9;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        /* ── Form Container ── */
        .sf10-container {
            width: 8.5in;
            min-height: 11in;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            padding: 0.2in 0.3in;
            box-sizing: border-box;
            position: relative;
            font-size: 8pt;
            line-height: 1.25;
            color: #000;
        }

        /* ── Header ── */
        .sf10-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
        }
        .sf10-header-table td {
            border: none;
            vertical-align: middle;
            padding: 0;
        }

        /* ── Section Boxes ── */
        .sf10-section {
            border: 1px solid #000;
            margin-bottom: 4px;
        }
        .sf10-section-title {
            background: #d9d9d9;
            color: #000;
            font-size: 8pt;
            font-weight: bold;
            text-align: center;
            padding: 2px 4px;
            border-bottom: 1px solid #000;
            letter-spacing: 0.5px;
        }
        .sf10-section-body {
            padding: 3px 5px;
        }

        /* ── Underline field ── */
        .u {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 30px;
            font-weight: 600;
        }

        /* ── Checkbox ── */
        .cb {
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 3px;
            vertical-align: middle;
        }

        /* ── Scholastic Columns ── */
        .scholastic-columns {
            column-count: 2;
            column-gap: 6px;
        }
        .scholastic-columns .scholastic-block {
            break-inside: avoid;
            margin-bottom: 4px;
        }

        /* ── Scholastic Block ── */
        .scholastic-block {
            border: 1px solid #000;
            margin-bottom: 4px;
            font-size: 7pt;
            page-break-inside: avoid;
        }
        .scholastic-block-header {
            padding: 2px 4px;
            border-bottom: 1px solid #000;
            line-height: 1.35;
        }
        .scholastic-block-header-row {
            display: flex;
            flex-wrap: wrap;
            gap: 2px 10px;
        }

        /* ── Grades Table ── */
        .grades-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 7pt;
        }
        .grades-table th,
        .grades-table td {
            border: 1px solid #000;
            padding: 1px 3px;
            text-align: center;
            vertical-align: middle;
        }
        .grades-table th {
            font-weight: bold;
            background: #fff;
        }
        .grades-table td.text-left {
            text-align: left;
        }

        /* ── Remedial Section ── */
        .remedial-section {
            border-top: 1px solid #000;
            font-size: 6.5pt;
        }
        .remedial-header {
            padding: 1px 4px;
            border-bottom: 1px solid #000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .remedial-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 6.5pt;
        }
        .remedial-table th,
        .remedial-table td {
            border: 1px solid #000;
            padding: 1px 2px;
            text-align: center;
            vertical-align: middle;
        }

        /* ── Footer ── */
        .form-footer {
            text-align: right;
            font-size: 7pt;
            margin-top: 4px;
            font-weight: 600;
        }

        /* ── Print Button ── */
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

        /* ── Print Styles ── */
        @media print {
            @page {
                size: letter portrait;
                margin: 0.2in 0.25in;
            }
            body {
                background: #fff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            /* Hide sidebars & UI chrome */
            aside, nav, .sidebar, [class*="sidebar"], #sidebar,
            .no-print, .fixed.w-72, .fixed[class*="w-72"],
            [x-show*="mobileOpen"], .lg\:translate-x-0, .backdrop-blur-xl {
                display: none !important;
                visibility: hidden !important;
                width: 0 !important; height: 0 !important;
                position: absolute !important; left: -9999px !important;
            }
            /* Reset main content */
            .ml-72, [class*="ml-72"], [class*="ml-"],
            main, .main-content, #main-content {
                margin-left: 0 !important; padding-left: 0 !important;
                width: 100% !important; max-width: 100% !important;
            }
            /* Container */
            .sf10-container {
                box-shadow: none !important;
                margin: 0 !important; padding: 0 !important;
                width: 100% !important; max-width: 100% !important;
                border: none !important; min-height: auto !important;
            }
            /* Fix two-column layout */
            .flex.gap-6 { display: block !important; }
            .flex.gap-6 > div:last-child { display: none !important; }
            .flex-1 { width: 100% !important; max-width: 100% !important; flex: none !important; }
            /* Scholastic blocks */
            .scholastic-block { page-break-inside: avoid !important; margin-bottom: 4px !important; }
            .scholastic-columns { column-count: 2 !important; column-gap: 4px !important; }
            /* Font size reductions */
            .sf10-section-title { font-size: 7.5pt !important; padding: 2px 4px !important; }
            .scholastic-block-header, .scholastic-block-header span { font-size: 6.5pt !important; }
            .grades-table { font-size: 6.5pt !important; }
            .grades-table th, .grades-table td { padding: 1px 2px !important; }
            .remedial-section, .remedial-table { font-size: 6pt !important; }
            /* Tighten spacing */
            .sf10-section { margin-bottom: 3px !important; }
            .sf10-section-body { padding: 2px 4px !important; }
            .gap-6 { gap: 0.5rem !important; }
            .mt-1, .mt-2, .mt-3 { margin-top: 0.125rem !important; }
            .mb-3 { margin-bottom: 0.25rem !important; }
            .p-2 { padding: 0.25rem !important; }
            .p-3 { padding: 0.25rem !important; }
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
    @include('teacher.includes.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-72 p-6" id="main-content">

        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 10 (SF10-ES)</h1>
                <p class="text-slate-500">Learner's Permanent Academic Record</p>
            </div>
            <div class="px-4 py-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium">
                <i class="fas fa-calendar-alt mr-2"></i>{{ $schoolYear }}
            </div>
        </div>

        @if(!$selectedStudent)
        <!-- Student Selector -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('teacher.sf10') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Student</label>
                    <select name="student_id" onchange="this.form.submit()"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }} ({{ $student->lrn ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <noscript>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Load Permanent Record
                    </button>
                </noscript>
            </form>
        </div>
        @endif

        @if(!$selectedStudent)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">Please select a student to view their permanent academic record.</p>
            </div>
        @endif

        @if($selectedStudent)
        @php
            $user = $selectedStudent->user;
            $nameExtension = $user->suffix ?? '';
        @endphp

        <!-- TWO COLUMN LAYOUT: Report (Left) + Sidebar (Right) -->
        <div class="flex gap-6" style="align-items: flex-start;">

            <!-- LEFT COLUMN: Official SF10 -->
            <div class="flex-1">
<div class="overflow-x-auto pb-4">`n            <div class="sf10-container">

                <!-- ═══════════════════════════════════════ -->
                <!-- HEADER                                 -->
                <!-- ═══════════════════════════════════════ -->
                <table class="sf10-header-table">
                    <tr>
                        <td style="width: 70px; text-align: center; vertical-align: middle;">
                            <div style="font-size: 7pt; text-align: center; margin-bottom: 2px;">SF10-ES</div>
                            <img src="{{ asset('images/edukasyon.jpg') }}" alt="DepEd Logo" style="width: 55px; height: auto;">
                        </td>
                        <td style="text-align: center; vertical-align: middle; padding: 0 8px;">
                            <div style="font-size: 9pt; line-height: 1.3;">
                                <div>Republic of the Philippines</div>
                                <div style="font-weight: bold;">Department of Education</div>
                                <div style="font-weight: bold; font-size: 11pt; margin-top: 2px;">Learner Permanent Academic Record for Elementary School (SF10-ES)</div>
                                <div style="font-size: 8pt; font-style: italic;">(Formerly Form 137)</div>
                            </div>
                        </td>
                        <td style="width: 70px; text-align: center; vertical-align: middle;">
                            <img src="{{ asset('images/logo.png') }}" alt="DepEd" style="width: 65px; height: auto;">
                        </td>
                    </tr>
                </table>

                <!-- ═══════════════════════════════════════ -->
                <!-- LEARNER'S PERSONAL INFORMATION         -->
                <!-- ═══════════════════════════════════════ -->
                <div class="sf10-section">
                    <div class="sf10-section-title">LEARNER'S PERSONAL INFORMATION</div>
                    <div class="sf10-section-body">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2px;">
                            <tr>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; width: 1%;">LAST NAME:</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; font-weight: bold; width: 22%;">{{ strtoupper($user->last_name ?? '') }}</td>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">FIRST NAME:</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; font-weight: bold; width: 22%;">{{ strtoupper($user->first_name ?? '') }}</td>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">NAME EXTN. (Jr,II,III)</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 8%;">{{ strtoupper($nameExtension) }}</td>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 8px; width: 1%;">MIDDLE NAME:</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 18%;">{{ strtoupper($user->middle_name ?? '') }}</td>
                            </tr>
                        </table>
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; width: 1%;">Learner Reference Number (LRN):</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 28%;">{{ $selectedStudent->lrn ?? '' }}</td>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 10px; width: 1%;">Birthdate (mm/dd/yyyy):</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 22%;">{{ $selectedStudent->birthdate ? \Carbon\Carbon::parse($selectedStudent->birthdate)->format('m/d/Y') : '' }}</td>
                                <td style="border: none; padding: 1px 0; white-space: nowrap; padding-left: 10px; width: 1%;">Sex:</td>
                                <td style="border: none; padding: 1px 0; border-bottom: 1px solid #000; width: 12%;">{{ strtoupper($selectedStudent->gender ?? '') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════ -->
                <!-- ELIGIBILITY                            -->
                <!-- ═══════════════════════════════════════ -->
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
                            <span>Name of School: <span class="u" style="min-width: 120px;">{{ $schoolName }}</span></span>
                            <span>School ID: <span class="u" style="min-width: 50px;">{{ $schoolId }}</span></span>
                            <span>Address of School: <span class="u" style="min-width: 150px;">{{ $schoolAddress ?? '' }}</span></span>
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

                <!-- ═══════════════════════════════════════ -->
                <!-- SCHOLASTIC RECORDS                     -->
                <!-- ═══════════════════════════════════════ -->
                <div class="sf10-section" style="border-bottom: none;">
                    <div class="sf10-section-title">SCHOLASTIC RECORD</div>
                </div>
                <div class="sf10-section" style="border-top: none;">
                    <div class="sf10-section-body" style="padding: 2px;">
                        @if(isset($allGradeLevels) && count($allGradeLevels) > 0)
                            <div class="scholastic-columns">
                                @foreach($allGradeLevels as $gradeLevel)
                                    @php
                                                        $subjectsForGrade = $subjectsByGrade[$gradeLevel] ?? collect();
                                                        $gradeRecords = $historicalGrades[$gradeLevel] ?? collect();
                                                        $schoolInfo = $schoolHistory[$gradeLevel] ?? null;
                                                        $isKindergartenGrade = (stripos($gradeLevel, 'kinder') !== false);
                                                        $kinderConfig = config('kindergarten.domains');
                                                        $kinderDomainData = $kinderDomainsByGrade[$gradeLevel] ?? collect();

                                                        $getKinderRatingSF10 = function($domainKey, $indicatorKey, $quarter) use ($kinderDomainData) {
                                                            $domainData = $kinderDomainData->get($domainKey);
                                                            if (!$domainData) return '';
                                                            $indicatorData = $domainData->get($indicatorKey);
                                                            if (!$indicatorData) return '';
                                                            $record = $indicatorData->firstWhere('quarter', $quarter);
                                                            return $record ? $record->rating : '';
                                                        };
                                                    @endphp

                                                    <div class="scholastic-block">
                                                        <!-- Block Header -->
                                                        <div class="scholastic-block-header">
                                                            <div class="scholastic-block-header-row">
                                                                <span>School: <span class="u" style="min-width: 100px;">{{ $schoolInfo->school_name ?? $schoolName }}</span></span>
                                                                <span>School ID: <span class="u" style="min-width: 50px;">{{ $schoolInfo->school_id ?? $schoolId }}</span></span>
                                                            </div>
                                                            <div class="scholastic-block-header-row">
                                                                <span>District: <span class="u" style="min-width: 60px;">{{ $schoolInfo->district ?? $schoolDistrict }}</span></span>
                                                                <span>Division: <span class="u" style="min-width: 80px;">{{ $schoolInfo->division ?? $schoolDivision }}</span></span>
                                                                <span>Region: <span class="u" style="min-width: 40px;">{{ $schoolInfo->region ?? $schoolRegion }}</span></span>
                                                            </div>
                                                            <div class="scholastic-block-header-row">
                                                                <span>Classified as Grade: <span class="u" style="min-width: 25px;">{{ str_replace(['Grade ', 'Kindergarten'], ['', 'K'], $gradeLevel) }}</span></span>
                                                                <span>Section: <span class="u" style="min-width: 60px;">{{ $schoolInfo->section ?? '' }}</span></span>
                                                                <span>School Year: <span class="u" style="min-width: 60px;">{{ $schoolInfo->school_year ?? '' }}</span></span>
                                                            </div>
                                                            <div class="scholastic-block-header-row">
                                                                <span>Name of Adviser/Teacher: <span class="u" style="min-width: 120px;">{{ $schoolInfo->adviser ?? '' }}</span></span>
                                                                <span>Signature: <span class="u" style="min-width: 80px;"></span></span>
                                                            </div>
                                                        </div>

                                                        @if($isKindergartenGrade)
                                                            <!-- KINDERGARTEN DOMAINS -->
                                                            <table class="grades-table">
                                                                <thead>
                                                                    <tr>
                                                                        <th rowspan="2" style="width: 50%; text-align: left; padding-left: 4px;">
                                                                            {{ $lang == 'cebuano' ? 'MGA KAHILIAN (DOMAINS)' : 'DOMAINS' }}
                                                                        </th>
                                                                        <th colspan="4">{{ $lang == 'cebuano' ? 'MARKAHAN (QUARTER)' : 'QUARTER' }}</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th style="width: 12.5%;">1</th>
                                                                        <th style="width: 12.5%;">2</th>
                                                                        <th style="width: 12.5%;">3</th>
                                                                        <th style="width: 12.5%;">4</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse($kinderConfig as $domainKey => $domainData)
                                                                        <tr>
                                                                            <td colspan="5" style="text-align: left; padding-left: 4px; font-weight: bold; font-size: 6.5pt; background: #f3f4f6; text-transform: uppercase;">
                                                                                {{ $domainData['name'][$lang] ?? $domainData['name']['cebuano'] }}
                                                                            </td>
                                                                        </tr>
                                                                        @if(isset($domainData['subdomains']))
                                                                            @foreach($domainData['subdomains'] as $subdomainKey => $subdomainData)
                                                                                @foreach($subdomainData['indicators'] as $indicatorKey => $indicatorText)
                                                                                <tr>
                                                                                    <td class="text-left" style="font-size: 6.5pt; padding-left: 10px; line-height: 1.3;">
                                                                                        {{ $indicatorText[$lang] ?? $indicatorText['cebuano'] }}
                                                                                    </td>
                                                                                    <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 1) }}</td>
                                                                                    <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 2) }}</td>
                                                                                    <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 3) }}</td>
                                                                                    <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 4) }}</td>
                                                                                </tr>
                                                                                @endforeach
                                                                            @endforeach
                                                                        @elseif(isset($domainData['indicators']))
                                                                            @foreach($domainData['indicators'] as $indicatorKey => $indicatorText)
                                                                            <tr>
                                                                                <td class="text-left" style="font-size: 6.5pt; padding-left: 10px; line-height: 1.3;">
                                                                                    {{ $indicatorText[$lang] ?? $indicatorText['cebuano'] }}
                                                                                </td>
                                                                                <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 1) }}</td>
                                                                                <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 2) }}</td>
                                                                                <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 3) }}</td>
                                                                                <td style="font-weight: bold;">{{ $getKinderRatingSF10($domainKey, $indicatorKey, 4) }}</td>
                                                                            </tr>
                                                                            @endforeach
                                                                        @endif
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="5" style="text-align: center; font-size: 7pt; color: #6b7280;">No kindergarten domain data available</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                            <div class="remedial-section">
                                                                <div style="padding: 2px 4px; font-size: 6.5pt;">
                                                                    @if($lang == 'cebuano')
                                                                        <span style="font-weight: bold;">MARKAHAN:</span> B = Sinugdan (Beginning) | D = Nagpalambo (Developing) | C = Kusgan (Consistent)
                                                                    @else
                                                                        <span style="font-weight: bold;">RATING SCALE:</span> B = Beginning (Sinugdan) | D = Developing (Nagpalambo) | C = Consistent (Kusgan)
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @else
                                                            <!-- GRADES 1-6 SUBJECTS -->
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
                                                                    @php
                                                                        $totalFinal = 0;
                                                                        $subjectCount = 0;
                                                                    @endphp
                                                                    @forelse($subjectsForGrade as $subject)
                                                                        @php
                                                                            $subjectGrades = $gradeRecords[$subject->id] ?? collect();
                                                                            $q1 = $subjectGrades->get(1)?->final_grade;
                                                                            $q2 = $subjectGrades->get(2)?->final_grade;
                                                                            $q3 = $subjectGrades->get(3)?->final_grade;
                                                                            $q4 = $subjectGrades->get(4)?->final_grade;
                                                                            $yearEndGrade = $subjectGrades->get(null)?->final_grade ?? $subjectGrades->get(0)?->final_grade;
                                                                            $final = $yearEndGrade;
                                                                            if (!$final) {
                                                                                $quarters = array_filter([$q1, $q2, $q3, $q4], fn($q) => $q !== null);
                                                                                if (count($quarters) > 0) {
                                                                                    $final = round(array_sum($quarters) / count($quarters));
                                                                                }
                                                                            }
                                                                            if ($final !== null) {
                                                                                $totalFinal += $final;
                                                                                $subjectCount++;
                                                                            }
                                                                            $remark = '';
                                                                            if ($final !== null) {
                                                                                $remark = $final >= 75 ? 'Passed' : 'Failed';
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">{{ $subject->name }}</td>
                                                                            <td>{{ $q1 ?? '' }}</td>
                                                                            <td>{{ $q2 ?? '' }}</td>
                                                                            <td>{{ $q3 ?? '' }}</td>
                                                                            <td>{{ $q4 ?? '' }}</td>
                                                                            <td style="font-weight: bold;">{{ $final ?? '' }}</td>
                                                                            <td style="font-size: 6.5pt;">{{ $remark }}</td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="7" style="text-align: center; font-size: 7pt; color: #6b7280;">No subjects found for {{ $gradeLevel }}</td>
                                                                        </tr>
                                                                    @endforelse
                                                                    <!-- Blank rows to match template -->
                                                                    @for($b = 0; $b < 2; $b++)
                                                                        <tr>
                                                                            <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">&nbsp;</td>
                                                                            <td></td><td></td><td></td><td></td><td></td><td></td>
                                                                        </tr>
                                                                    @endfor
                                                                    <!-- Optional subjects -->
                                                                    <tr>
                                                                        <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">*Arabic Language</td>
                                                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="text-left" style="font-size: 6.5pt; padding-left: 4px;">*Islamic Values Education</td>
                                                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                                                    </tr>
                                                                    <!-- General Average -->
                                                                    @if($subjectCount > 0)
                                                                        @php $generalAverage = round($totalFinal / $subjectCount); @endphp
                                                                        <tr style="font-weight: bold;">
                                                                            <td colspan="5" class="text-left" style="padding-left: 4px; font-size: 6.5pt;">General Average</td>
                                                                            <td style="font-size: 8pt; border: 1.5px solid #000;">{{ $generalAverage }}</td>
                                                                            <td style="font-size: 6.5pt;">{{ $generalAverage >= 75 ? 'Promoted' : 'Retained' }}</td>
                                                                        </tr>
                                                                    @else
                                                                        <tr style="font-weight: bold;">
                                                                            <td colspan="5" class="text-left" style="padding-left: 4px; font-size: 6.5pt;">General Average</td>
                                                                            <td style="font-size: 8pt; border: 1.5px solid #000;"></td>
                                                                            <td style="font-size: 6.5pt;"></td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                            <!-- Remedial Classes -->
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
                                                        @endif
                                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 8px; text-align: center; font-size: 8pt; color: #6b7280; border: 1px dashed #9ca3af;">
                                No grade level information available.
                            </div>
                        @endif
                    </div>
                </div>

</div>`n`n                <!-- Footer -->
                <div class="form-footer">
                    Revised 2025 based on DepEd Order No. 10, s. 2024
                </div>

            </div>
            </div>
            <!-- /LEFT COLUMN -->

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="no-print" style="width: 300px; flex-shrink: 0;">

                <!-- Student Selector -->
                <div class="bg-white rounded-lg shadow-md border border-slate-200 mb-4 overflow-hidden">
                    <div class="bg-blue-600 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-user-graduate"></i>
                        <span class="font-semibold">Select Student</span>
                    </div>
                    <div class="p-4">
                        <form method="GET" action="{{ route('teacher.sf10') }}">
                            <select name="student_id" onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-slate-700 text-sm bg-slate-50 mb-3">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $selectedStudent && $selectedStudent->id == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->last_name ?? '' }}, {{ $student->user->first_name ?? '' }} {{ $student->user->middle_name ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                            <noscript>
                                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-search"></i> Load
                                </button>
                            </noscript>
                        </form>
                    </div>
                </div>

                <!-- Student Info -->
                <div class="bg-white rounded-lg shadow-md border border-slate-200 mb-4 overflow-hidden">
                    <div class="bg-slate-100 text-slate-800 px-4 py-2 text-sm font-semibold border-b">Student Information</div>
                    <div class="p-4 text-sm">
                        <div class="mb-2"><span class="text-slate-500">Name:</span> <span class="font-semibold">{{ $user->last_name ?? '' }}, {{ $user->first_name ?? '' }}</span></div>
                        <div class="mb-2"><span class="text-slate-500">LRN:</span> <span class="font-semibold">{{ $selectedStudent->lrn ?? 'N/A' }}</span></div>
                        <div class="mb-2"><span class="text-slate-500">Birthdate:</span> <span class="font-semibold">{{ $selectedStudent->birthdate ? \Carbon\Carbon::parse($selectedStudent->birthdate)->format('m/d/Y') : 'N/A' }}</span></div>
                        <div><span class="text-slate-500">Sex:</span> <span class="font-semibold">{{ $selectedStudent->gender ?? 'N/A' }}</span></div>
                    </div>
                </div>

                <!-- Academic History -->
                <div class="bg-white rounded-lg shadow-md border border-slate-200 mb-4 overflow-hidden">
                    <div class="bg-blue-600 text-white px-4 py-3 flex items-center gap-2">
                        <i class="fas fa-history"></i>
                        <span class="font-semibold">Academic History</span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Grade Levels</p>
                                <p class="text-lg font-bold text-slate-800">{{ count(array_filter($allGradeLevels ?? [], fn($g) => isset($historicalGrades[$g]) && $historicalGrades[$g]->count() > 0)) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-700">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Status</p>
                                <p class="text-sm font-bold text-green-700">Active</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /RIGHT COLUMN -->

        </div>
        <!-- /TWO COLUMN LAYOUT -->
        @endif

    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print print-btn bg-blue-600 text-white hover:bg-blue-700 transition shadow-xl shadow-blue-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html>
