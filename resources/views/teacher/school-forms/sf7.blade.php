<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 7 (SF7) - School Personnel Assignment List and Basic Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }

        .sf7-container {
            background: white;
            max-width: 1200px;
            margin: 0 auto;
            padding: 16px;
        }

        .sf7-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }

        .sf7-table th,
        .sf7-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            text-align: center;
            vertical-align: middle;
        }

        .sf7-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            font-size: 8px;
        }

        .sf7-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
        }

        .sf7-subtitle {
            font-size: 9px;
            text-align: center;
            font-style: italic;
        }

        .section-label {
            font-size: 8px;
            font-weight: bold;
            text-align: left;
            background: #f3f4f6;
        }

        .school-info-row {
            display: flex;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .school-info-row > div {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .school-info-row label {
            font-weight: 600;
            white-space: nowrap;
        }

        .school-info-row .underline {
            border-bottom: 1px solid #000;
            flex: 1;
            padding: 0 4px;
            font-weight: bold;
            text-transform: uppercase;
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

        @media print {
            @page { size: legal landscape; margin: 0.3in; }
            aside, nav, .sidebar, #sidebar, [class*="sidebar"], .no-print { display: none !important; }
            body { background: white; }
            .sf7-container { box-shadow: none; margin: 0; padding: 0; max-width: 100% !important; }
            .sf7-table { font-size: 8pt; width: 100%; }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen">

    @include('teacher.includes.sidebar')

    <div class="lg:ml-72 p-4">

        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-xl font-bold text-slate-800">School Form 7 (SF7)</h1>
                <p class="text-slate-500 text-sm">School Personnel Assignment List and Basic Profile</p>
            </div>
            <div class="px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                <i class="fas fa-id-card mr-1"></i>SY {{ $activeSchoolYear?->name ?? now()->format('Y') }}
            </div>
        </div>

        <div class="sf7-container rounded-xl shadow-lg border border-slate-200 overflow-x-auto">

            <!-- DepEd Logo + Title -->
            <div class="flex items-start gap-3 mb-2">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/deped-logo.png') }}" alt="DepEd" class="w-14 h-14 object-contain"
                         onerror="this.style.display='none'">
                </div>
                <div class="flex-1">
                    <div class="sf7-title">School Form 7 (SF7) School Personnel Assignment List and Basic Profile</div>
                    <div class="sf7-subtitle">(This replaces Form 12-Monthly Status Report for Teachers, Form 19-Assignment List, Form 29-Teacher Program and Form 31-Summary Information of Teachers)</div>
                </div>
            </div>

            <!-- School Info -->
            <div class="school-info-row">
                <div><label>School ID</label><div class="underline">{{ $schoolId }}</div></div>
                <div><label>Region</label><div class="underline">{{ $schoolRegion }}</div></div>
                <div><label>Division</label><div class="underline">{{ $schoolDivision }}</div></div>
            </div>
            <div class="school-info-row">
                <div><label>School Name</label><div class="underline">{{ $schoolName }}</div></div>
                <div><label>District</label><div class="underline">{{ $schoolDistrict }}</div></div>
                <div><label>School Year</label><div class="underline">{{ $activeSchoolYear?->name ?? '' }}</div></div>
            </div>

            <!-- Sections A, B, C -->
            <table class="sf7-table mt-3">
                <thead>
                    <tr>
                        <th style="width: 30%;" class="section-label">(A) Nationally-Funded Teaching &amp; Teaching Related Items</th>
                        <th style="width: 30%;" class="section-label">(B) Nationally-Funded Non Teaching Items</th>
                        <th style="width: 40%;" class="section-label" colspan="4">(C) Other Appointments and Funding Sources</th>
                    </tr>
                    <tr>
                        <th>Title of Plantilla Position<br><span style="font-weight:normal;font-size:7px;">(as it appears in the appointment document/PSIPOP)</span></th>
                        <th>Number of Incumbent</th>
                        <th>Title of Plantilla Position<br><span style="font-weight:normal;font-size:7px;">(as it appears in the appointment document/PSIPOP)</span></th>
                        <th>Number of Incumbent</th>
                        <th>Title of Designation<br><span style="font-weight:normal;font-size:7px;">(as it appears in the contract/document; Teacher, Clerk, Security Guard, Driver etc.)</span></th>
                        <th>Appointment<br><span style="font-weight:normal;font-size:7px;">(Contractual, Substitute, Volunteer, others specify)</span></th>
                        <th>Fund Source<br><span style="font-weight:normal;font-size:7px;">(SEF, PTA, NGO's etc.)</span></th>
                        <th colspan="2">Number of Incumbent</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-left">HT I-III</td>
                        <td></td>
                        <td class="text-left">Clerk</td>
                        <td></td>
                        <td class="text-left">Para Teacher</td>
                        <td>Contractual</td>
                        <td>Mun. fund</td>
                        <td style="width: 40px;">Teaching</td>
                        <td style="width: 40px;">Non-Teaching</td>
                    </tr>
                    <tr>
                        <td class="text-left">Teacher I-III</td>
                        <td>{{ $teachingCount }}</td>
                        <td class="text-left">Security Guard</td>
                        <td></td>
                        <td class="text-left"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="text-left">Master Teacher I-II</td>
                        <td></td>
                        <td class="text-left">Driver</td>
                        <td></td>
                        <td class="text-left"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- Main Personnel Table -->
            <table class="sf7-table mt-2">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 6%;">Employee No. (or Tax Identification No. - T.I.N.)</th>
                        <th rowspan="2" style="width: 14%;">Name of School Personnel<br><span style="font-weight:normal;font-size:7px;">(Arrange by Position Descending)</span></th>
                        <th rowspan="2" style="width: 3%;">Sex</th>
                        <th rowspan="2" style="width: 5%;">Fund Source</th>
                        <th rowspan="2" style="width: 8%;">Position / Designation</th>
                        <th rowspan="2" style="width: 7%;">Nature of Appointment /<br>Employment Status</th>
                        <th colspan="3" style="width: 14%;">EDUCATIONAL QUALIFICATION</th>
                        <th rowspan="2" style="width: 12%;">Subject Taught<br><span style="font-weight:normal;font-size:7px;">(include Grade &amp; Section, Advisory Class &amp; Other Ancillary Assignments)</span></th>
                        <th colspan="4" style="width: 18%;">Daily Program (time duration)</th>
                        <th rowspan="2" style="width: 8%;">Remarks<br><span style="font-weight:normal;font-size:7px;">(For Deleted Items, indicate name of school/office. For IP = Ethnicity)</span></th>
                    </tr>
                    <tr>
                        <th>Degree / Post Graduate</th>
                        <th>Major / Specialization</th>
                        <th>Minor</th>
                        <th>DAY<br>(M/T/W/Th/F)</th>
                        <th>From<br>(00:00)</th>
                        <th>To<br>(00:00)</th>
                        <th>Total Actual Teaching Minutes per Week</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($personnelList as $person)
                        @php
                            $programCount = $person['daily_program']->count();
                            $rowCount = max(1, $programCount);
                            $subjectsText = $person['subjects_taught']->map(function($s) {
                                return $s['subject'] . ($s['grade_section'] ? ' (' . $s['grade_section'] . ')' : '');
                            })->implode(', ');
                            $advisoryText = $person['advisory_classes']->implode(', ');
                            $assignmentText = '';
                            if ($advisoryText) {
                                $assignmentText .= 'Advisory: ' . $advisoryText;
                            }
                            if ($subjectsText) {
                                $assignmentText .= ($assignmentText ? '; ' : '') . $subjectsText;
                            }
                        @endphp
                        @for($i = 0; $i < $rowCount; $i++)
                            @php
                                $prog = $person['daily_program'][$i] ?? null;
                            @endphp
                            <tr>
                                @if($i === 0)
                                    <td rowspan="{{ $rowCount }}">{{ $person['employee_no'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left font-bold" style="font-size: 9px;">
                                        {{ $person['last_name'] }}, {{ $person['first_name'] }} {{ $person['middle_name'] }}
                                    </td>
                                    <td rowspan="{{ $rowCount }}">{{ $person['sex'] }}</td>
                                    <td rowspan="{{ $rowCount }}">{{ $person['fund_source'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['position'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['nature_of_appointment'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['highest_degree'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['major'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['minor'] }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">
                                        {{ $assignmentText ?: '-' }}
                                    </td>
                                @endif
                                <td>{{ $prog['day'] ?? '' }}</td>
                                <td>{{ $prog['from'] ?? '' }}</td>
                                <td>{{ $prog['to'] ?? '' }}</td>
                                @if($i === 0)
                                    <td rowspan="{{ $rowCount }}">{{ $person['total_minutes'] ?: '' }}</td>
                                    <td rowspan="{{ $rowCount }}" class="text-left" style="font-size: 8px;">{{ $person['remarks'] }}</td>
                                @endif
                            </tr>
                        @endfor
                    @empty
                        <tr>
                            <td colspan="15" class="text-center py-4 text-slate-500">No personnel records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Guidelines -->
            <div class="mt-2 text-[8px] space-y-0.5 leading-tight border-t-2 border-black pt-2">
                <p class="font-bold">GUIDELINES:</p>
                <p>1. This form shall be accomplished at the beginning of the school year.</p>
                <p>2. All school personnel should be included, listed from highest rank to lowest.</p>
                <p>3. Daily Program is for teaching personnel only.</p>
                <p>4. For Pre-school, Kindergarten, ALS teachers and SPED teachers, indicate the program being handled in the subject column.</p>
            </div>

            <!-- Signatures -->
            <div class="mt-4 grid grid-cols-3 gap-6 text-xs px-4">
                <div class="text-center">
                    <p class="font-semibold mb-1 text-[10px] text-left">Prepared by:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ auth()->user()->teacher?->full_name ?? auth()->user()->name }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-1">Date: ___________________</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-1 text-[10px] text-left">Certified Correct:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                        <p class="text-center text-[9px] mt-0.5">(School Head)</p>
                    </div>
                    <p class="text-center text-[9px] mt-1">Date: ___________________</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-1 text-[10px] text-left">Reviewed by:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">Division Supervisor</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-1">Date: ___________________</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-3 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>School Form 7: Page 1 of 1</span>
                <span>Generated: {{ now()->format('F d, Y h:i A') }}</span>
            </div>

        </div>
    </div>

    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html>
