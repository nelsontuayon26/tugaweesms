<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 4 (SF4) - Monthly Attendance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        .sf4-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }
        
        .sf4-table th,
        .sf4-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf4-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 8px;
        }
        
        .sf4-header {
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
        
        /* Print styles - same as SF2 */
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
            
            .sf4-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            .sf4-table {
                font-size: 8pt;
                width: 100%;
            }
            
            .sf4-table th,
            .sf4-table td {
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
    <div class="lg:ml-72 p-6">
        
        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 4 (SF4)</h1>
                <p class="text-slate-500">Monthly Attendance Report of Learners</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>{{ $activeSchoolYear?->name ?? now()->format('Y') }}
                </div>
            </div>
        </div>

        <!-- Controls Panel - Same as SF2 -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('teacher.sf4') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Section</label>
                    <select name="section_id" onchange="this.form.submit()" 
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $selectedSection && $selectedSection->id == $section->id ? 'selected' : '' }}>
                                {{ $section->gradeLevel->name ?? '' }} - {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Report Month</label>
                    <select name="month" onchange="this.form.submit()"
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach(['June', 'July', 'August', 'September', 'October', 'November', 'December', 'January', 'February', 'March', 'April', 'May'] as $m)
                            <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>{{ $m }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Load Report
                </button>
            </form>
        </div>

        @if(!$selectedSection)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No section available. Please create a section first.</p>
            </div>
        @endif

        @if($attendanceSummary->isEmpty() && $selectedSection)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-info-circle text-blue-500 text-3xl mb-2"></i>
                <p class="text-blue-800 font-medium">No enrolled students found for {{ $selectedSection->name }} in {{ $activeSchoolYear?->name ?? 'this school year' }}</p>
                <p class="text-sm text-blue-600 mt-1">Make sure students are enrolled with status "enrolled" (not "active")</p>
            </div>
        @endif

        <!-- SF4 Container -->
<div class="overflow-x-auto pb-4">      <div class="sf4-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto">
            
            <!-- School Header Information - Same layout as SF2 -->
            <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School ID:</span>
                        <span class="border-b border-black flex-1 px-1 font-mono text-[10px]">{{ $schoolId }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School Name:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolName }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Year:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $activeSchoolYear?->name ?? '___________' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Grade Level:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->gradeLevel->name ?? '___________' }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Section:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->name ?? '___________' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Adviser:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px] font-bold">{{ $adviserName }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-32 text-[10px]">Report for the Month of:</span>
                        <span class="border-b-2 border-black flex-1 px-1 font-bold text-sm text-center">{{ $selectedMonth }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-32 text-[10px]">No. of School Days:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px] text-center">{{ $monthlyStats['total_school_days'] ?? '____' }}</span>
                    </div>
                </div>
            </div>

            <!-- SF4 Title -->
            <div class="sf4-header mb-0">
                SCHOOL FORM 4 (SF4) MONTHLY ATTENDANCE REPORT OF LEARNERS<br>
                <span class="text-[9px] font-normal">(This replaces Form 3 & STS Form 4 - Absenteeism and Dropout Profile)</span>
            </div>

            <!-- Main SF4 Table -->
            <table class="sf4-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 4%;">NO.</th>
                        <th rowspan="2" style="width: 25%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 6%;">Sex<br>(M/F)</th>
                        <th colspan="3" style="width: 24%;">ATTENDANCE SUMMARY</th>
                        <th rowspan="2" style="width: 10%;">Attendance<br>Rate (%)</th>
                        <th rowspan="2" style="width: 21%;">REMARKS<br>(If DROPPED OUT, state reason.<br>If TRANSFERRED IN/OUT, write name of School)</th>
                    </tr>
                    <tr>
                        <th style="width: 8%;">Days<br>Present</th>
                        <th style="width: 8%;">Days<br>Absent</th>
                        <th style="width: 8%;">Days<br>Tardy</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- MALE Section -->
                    <tr>
                        <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
                    </tr>
                    
                    @php
                        $maleData = $attendanceSummary->filter(function($item) {
                            return $item['gender'] == 'M';
                        })->sortBy('full_name');
                        
                        $maleTotalPresent = 0;
                        $maleTotalAbsent = 0;
                        $maleTotalTardy = 0;
                    @endphp

                    @forelse($maleData as $index => $data)
                        @php
                            $maleTotalPresent += $data['present'];
                            $maleTotalAbsent += $data['absent'];
                            $maleTotalTardy += $data['tardy'];
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $index + 1 }}</td>
                            <td class="text-left uppercase text-[9px] data-cell pl-2" title="{{ $data['full_name'] }}">{{ $data['full_name'] }}</td>
                            <td class="text-center text-[9px] font-bold">M</td>
                            <td class="font-bold text-emerald-600 text-[9px]">{{ $data['present'] }}</td>
                            <td class="font-bold text-rose-600 text-[9px]">{{ $data['absent'] > 0 ? $data['absent'] : '' }}</td>
                            <td class="font-bold text-amber-600 text-[9px]">{{ $data['tardy'] > 0 ? $data['tardy'] : '' }}</td>
                            <td class="font-bold {{ $data['attendance_rate'] >= 90 ? 'text-emerald-600' : ($data['attendance_rate'] >= 75 ? 'text-amber-600' : 'text-rose-600') }} text-[9px]">
                                {{ $data['attendance_rate'] }}%
                            </td>
                            <td class="text-[8px]">{{ $data['student']->attendance_remarks ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                        </tr>
                    @endforelse

                    <!-- MALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="text-right text-[9px] pr-2">MALE TOTAL:</td>
                        <td class="text-emerald-700 text-[9px]">{{ $maleTotalPresent }}</td>
                        <td class="text-rose-700 text-[9px]">{{ $maleTotalAbsent > 0 ? $maleTotalAbsent : '' }}</td>
                        <td class="text-amber-700 text-[9px]">{{ $maleTotalTardy > 0 ? $maleTotalTardy : '' }}</td>
                        <td class="text-[9px]">
                            @php
                                $maleAvg = $maleData->count() > 0 ? round($maleData->avg('attendance_rate'), 1) : 0;
                            @endphp
                            {{ $maleAvg }}%
                        </td>
                        <td></td>
                    </tr>

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="8" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                    </tr>

                    @php
                        $femaleData = $attendanceSummary->filter(function($item) {
                            return $item['gender'] == 'F';
                        })->sortBy('full_name');
                        
                        $femaleTotalPresent = 0;
                        $femaleTotalAbsent = 0;
                        $femaleTotalTardy = 0;
                    @endphp

                    @forelse($femaleData as $index => $data)
                        @php
                            $femaleTotalPresent += $data['present'];
                            $femaleTotalAbsent += $data['absent'];
                            $femaleTotalTardy += $data['tardy'];
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $maleData->count() + $index + 1 }}</td>
                            <td class="text-left uppercase text-[9px] data-cell pl-2" title="{{ $data['full_name'] }}">{{ $data['full_name'] }}</td>
                            <td class="text-center text-[9px] font-bold">F</td>
                            <td class="font-bold text-emerald-600 text-[9px]">{{ $data['present'] }}</td>
                            <td class="font-bold text-rose-600 text-[9px]">{{ $data['absent'] > 0 ? $data['absent'] : '' }}</td>
                            <td class="font-bold text-amber-600 text-[9px]">{{ $data['tardy'] > 0 ? $data['tardy'] : '' }}</td>
                            <td class="font-bold {{ $data['attendance_rate'] >= 90 ? 'text-emerald-600' : ($data['attendance_rate'] >= 75 ? 'text-amber-600' : 'text-rose-600') }} text-[9px]">
                                {{ $data['attendance_rate'] }}%
                            </td>
                            <td class="text-[8px]">{{ $data['student']->attendance_remarks ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                        </tr>
                    @endforelse

                    <!-- FEMALE TOTAL ROW -->
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="text-right text-[9px] pr-2">FEMALE TOTAL:</td>
                        <td class="text-emerald-700 text-[9px]">{{ $femaleTotalPresent }}</td>
                        <td class="text-rose-700 text-[9px]">{{ $femaleTotalAbsent > 0 ? $femaleTotalAbsent : '' }}</td>
                        <td class="text-amber-700 text-[9px]">{{ $femaleTotalTardy > 0 ? $femaleTotalTardy : '' }}</td>
                        <td class="text-[9px]">
                            @php
                                $femaleAvg = $femaleData->count() > 0 ? round($femaleData->avg('attendance_rate'), 1) : 0;
                            @endphp
                            {{ $femaleAvg }}%
                        </td>
                        <td></td>
                    </tr>

                    <!-- COMBINED TOTAL ROW -->
                    <tr class="bg-gray-200 font-bold border-t-2 border-black">
                        <td colspan="3" class="text-right text-[9px] pr-2">COMBINED TOTAL:</td>
                        <td class="text-emerald-800 text-[9px] border-b-2 border-black">{{ $maleTotalPresent + $femaleTotalPresent }}</td>
                        <td class="text-rose-800 text-[9px] border-b-2 border-black">{{ ($maleTotalAbsent + $femaleTotalAbsent) > 0 ? ($maleTotalAbsent + $femaleTotalAbsent) : '' }}</td>
                        <td class="text-amber-800 text-[9px] border-b-2 border-black">{{ ($maleTotalTardy + $femaleTotalTardy) > 0 ? ($maleTotalTardy + $femaleTotalTardy) : '' }}</td>
                        <td class="text-[9px] border-b-2 border-black">
                            {{ number_format($monthlyStats['overall_avg_attendance'] ?? 0, 1) }}%
                        </td>
                        <td class="border-b-2 border-black"></td>
                    </tr>

                    <!-- Empty rows for manual writing -->
                    @php 
                        $currentRows = 5 + $maleData->count() + $femaleData->count(); 
                        $totalRows = max(35, $currentRows + 3);
                    @endphp
                    @for($i = $currentRows; $i < $totalRows; $i++)
                        <tr style="height: 18px;">
                            <td class="text-center text-[8px]">{{ $attendanceSummary->count() + ($i - $currentRows + 1) }}</td>
                            @for($j = 0; $j < 7; $j++)
                                <td></td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="mt-3 grid grid-cols-3 gap-4 text-xs border-t-2 border-black pt-3">
                <!-- Left: Guidelines -->
                <div class="text-[8px] space-y-1 leading-tight">
                    <p class="font-bold">GUIDELINES:</p>
                    <p>1. This form consolidates the daily attendance from SF2 into monthly totals.</p>
                    <p>2. Data is automatically calculated from SF2 daily attendance entries.</p>
                    <p>3. Attendance Rate = (Days Present / Total School Days) × 100</p>
                    <p>4. Students with attendance rate below 75% are considered chronic absentees.</p>
                    <p>5. Remarks column should indicate transfers, dropouts, or special cases.</p>
                </div>

                <!-- Middle: Monthly Summary -->
                <div class="space-y-2">
                    <div class="summary-box bg-gray-50">
                        <p class="font-bold text-[9px] mb-2">Monthly Attendance Summary</p>
                        <div class="space-y-1 text-[8px]">
                            <div class="flex justify-between border-b border-gray-300 pb-1">
                                <span>Total School Days:</span>
                                <span class="font-bold">{{ $monthlyStats['total_school_days'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 pb-1">
                                <span>Total Enrolled Students:</span>
                                <span class="font-bold">{{ $monthlyStats['total_students'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 pb-1">
                                <span>Male Students:</span>
                                <span class="font-bold">{{ $monthlyStats['male_count'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 pb-1">
                                <span>Female Students:</span>
                                <span class="font-bold">{{ $monthlyStats['female_count'] ?? 0 }}</span>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span>Overall Attendance Rate:</span>
                                <span class="font-bold {{ ($monthlyStats['overall_avg_attendance'] ?? 0) >= 90 ? 'text-emerald-600' : 'text-amber-600' }}">
                                    {{ number_format($monthlyStats['overall_avg_attendance'] ?? 0, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="summary-box bg-gray-50">
                        <p class="font-bold text-[9px] mb-1">Total Absences: <span class="border-b border-black w-8 inline-block text-center">{{ $monthlyStats['total_absences'] ?? '' }}</span></p>
                        <p class="text-[8px] mt-1">Total Tardy: <span class="border-b border-black w-8 inline-block text-center">{{ $monthlyStats['total_tardy'] ?? '' }}</span></p>
                    </div>
                </div>

                <!-- Right: Dropouts/Transfers -->
                <div class="space-y-2">
                    <table class="w-full text-[8px] border border-black">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="border border-black p-1">Category</th>
                                <th class="border border-black p-1">M</th>
                                <th class="border border-black p-1">F</th>
                                <th class="border border-black p-1">TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Drop out</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center font-bold">____</td>
                            </tr>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Transferred out</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center font-bold">____</td>
                            </tr>
                            <tr>
                                <td class="border border-black p-1 font-semibold">Transferred in</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center">____</td>
                                <td class="border border-black p-1 text-center font-bold">____</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-[8px] space-y-0.5 leading-tight">
                        <p class="font-bold">ATTENDANCE CODES (from SF2):</p>
                        <p>(blank) = Present | x = Absent | / = Tardy</p>
                        <p class="mt-1 font-bold">COLOR LEGEND:</p>
                        <p class="text-emerald-600">Green: 90% and above (Good)</p>
                        <p class="text-amber-600">Yellow: 75-89% (Warning)</p>
                        <p class="text-rose-600">Red: Below 75% (Critical)</p>
                    </div>
                </div>
            </div>

            <!-- Certification Signatures -->
            <div class="mt-4 grid grid-cols-2 gap-8 text-xs px-6">
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">I certify that this is a true and correct report.</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $adviserName }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature of Teacher over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Attested by:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature of School Head over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>School Form 4: Page ___ of ___</span>
                <span>Generated through LIS | Date Generated: {{ now()->format('F d, Y h:i A') }}</span>
            </div>

        </div>

        <!-- Legend Panel (No Print) -->
        <div class="no-print mt-6 max-w-[1600px] mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500"></i>
                SF4 Report Information
            </h3>
            <div class="grid grid-cols-3 gap-6 text-sm">
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">About SF4</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        School Form 4 is the monthly consolidation of daily attendance records from SF2. 
                        It summarizes each student's attendance for the entire month.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">Calculation Method</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Data is automatically calculated from daily attendance entries in SF2. 
                        No manual entry required - this is a report-only form.
                    </p>
                </div>
                <div>
                    <h4 class="font-medium text-slate-700 mb-2">Why no students showing?</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Check that: 1) Students are enrolled with status="enrolled", 2) School year is active, 
                        3) Students are not marked as completed/inactive.
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html>