<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 1 (SF1) - School Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        .sf1-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        
        .sf1-table th,
        .sf1-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            text-align: center;
            vertical-align: middle;
        }
        
        .sf1-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
        }
        
        .sf1-header {
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
        
        /* Print styles - Hide sidebar and adjust layout */
        @media print {
            @page {
                size: letter portrait;
                margin: 0.4in 0.3in 0.4in 0.3in;
            }
            
            /* Hide sidebar by targeting common sidebar selectors */
            aside,
            nav[class*="w-72"],
            div[class*="w-72"],
            .sidebar,
            #sidebar,
            [class*="sidebar"] {
                display: none !important;
            }
            
            /* Remove left margin from main content */
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
            
            .sf1-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
                width: 100% !important;
            }
            
            /* Ensure table fits on page */
            .sf1-table {
                font-size: 7pt;
                width: 100%;
            }
            
            .sf1-table th,
            .sf1-table td {
                padding: 1px 2px;
            }
        }
        
        .data-cell {
            max-width: 80px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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

    <!-- Include Sidebar - This will be hidden when printing -->
    @include('teacher.includes.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-72 p-6">
        
        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 1 (SF1)</h1>
                <p class="text-slate-500">School Register - Master List of Class Enrollment</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>{{ $schoolYear }}
                </div>
            </div>
        </div>

        <!-- Section Selector -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('teacher.sf1') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Section</label>
                    <select name="section_id" onchange="this.form.submit()" 
                        class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $selectedSection->id == $section->id ? 'selected' : '' }}>
                                {{ $section->gradeLevel->name ?? '' }} - {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                    Load Students
                </button>
            </form>
        </div>

        @if(!$selectedSection)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No section available. Please create a section first.</p>
            </div>
        @endif

        @if($enrollments->isEmpty() && $selectedSection)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-info-circle text-blue-500 text-3xl mb-2"></i>
                <p class="text-blue-800 font-medium">No enrolled students found for {{ $selectedSection->name }} in {{ $schoolYear }}</p>
            </div>
        @endif

        <!-- SF1 Container -->
        <div class="overflow-x-auto pb-4">
        <div class="sf1-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1400px] mx-auto">
            
            <!-- School Header Information -->
            <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School ID:</span>
                        <span class="border-b border-black flex-1 px-1 font-mono text-[10px]">{{ $schoolId }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School Name:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolName }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Division:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolDivision }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Region:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolRegion }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Year:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $schoolYear }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Grade Level:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->gradeLevel->name ?? '___________' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Section:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->name ?? '___________' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Adviser:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase text-[10px] font-bold">{{ $adviserName }}</span>
                    </div>
                </div>
            </div>

            <!-- SF1 Title -->
            <div class="sf1-header mb-0">
                SCHOOL FORM 1 (SF1) SCHOOL REGISTER<br>
                <span class="text-[9px] font-normal">(This replaces Form 1, Master List & STS Form 2-Family Background and Profile)</span>
            </div>

            <!-- Main SF1 Table -->
            <table class="sf1-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 2%;">NO.</th>
                        <th rowspan="2" style="width: 7%;">LRN</th>
                        <th colspan="3" style="width: 14%;">NAME</th>
                        <th rowspan="2" style="width: 2%;">SEX<br>(M/F)</th>
                        <th rowspan="2" style="width: 4%;">BIRTH DATE<br>(mm/dd/yyyy)</th>
                        <th rowspan="2" style="width: 2%;">AGE as of<br>1st Friday<br>of June</th>
                        <th rowspan="2" style="width: 4%;">MOTHER<br>TONGUE</th>
                        <th rowspan="2" style="width: 4%;">IP<br>(Ethnic Group)</th>
                        <th rowspan="2" style="width: 4%;">RELIGION</th>
                        <th colspan="4" style="width: 16%;">ADDRESS</th>
                        <th rowspan="2" style="width: 7%;">FATHER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 7%;">MOTHER'S MAIDEN NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 7%;">GUARDIAN'S NAME<br>(if not parent)<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 3%;">RELATIONSHIP<br>TO GUARDIAN</th>
                        <th rowspan="2" style="width: 4%;">CONTACT NUMBER<br>of Parent or<br>Guardian</th>
                        <th rowspan="2" style="width: 5%;">REMARKS<br>(Please refer to the legend on the last page)</th>
                    </tr>
                    <tr>
                        <th style="width: 4.5%;">Last Name</th>
                        <th style="width: 4.5%;">First Name</th>
                        <th style="width: 5%;">Middle Name</th>
                        <th style="width: 4%;">House #/ Street/ Sitio/ Purok</th>
                        <th style="width: 4%;">Barangay</th>
                        <th style="width: 4%;">Municipality/ City</th>
                        <th style="width: 4%;">Province</th>
                    </tr>
                </thead>
                            <tbody>
                    <!-- MALE Section -->
                    <tr>
                        <td colspan="21" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2 border border-black">MALE</td>
                    </tr>
                    
                    @php
                        $maleEnrollments = $enrollments->filter(function($e) {
                            $gender = strtoupper($e->student->gender ?? '');
                            return $gender == 'MALE' || $gender == 'M';
                        });
                        $maleCounter = 0;
                    @endphp

                    @forelse($maleEnrollments as $enrollment)
                        @php
                            $maleCounter++;
                            $student = $enrollment->student;
                            if(!$student) continue;
                            
                            $user = $student->user;
                            $lastName = $user->last_name ?? '';
                            $firstName = $user->first_name ?? '';
                            $middleName = $user->middle_name ?? '';
                            
                            $age = '';
                            if (isset($student->attributes['calculated_age'])) {
                                $age = $student->attributes['calculated_age'];
                            } elseif ($student->getAttribute('calculated_age')) {
                                $age = $student->getAttribute('calculated_age');
                            } elseif ($student->calculated_age) {
                                $age = $student->calculated_age;
                            }
                            
                            $gender = strtoupper($student->gender ?? '');
                            $sexCode = $gender == 'MALE' || $gender == 'M' ? 'M' : ($gender == 'FEMALE' || $gender == 'F' ? 'F' : '');
                            
                            $houseStreet = trim(($student->street_address ?? ''));
                            $barangay = $student->barangay ?? '';
                            $municipality = $student->city ?? '';
                            $province = $student->province ?? '';
                            
                            $fatherName = $student->father_name ?? '';
                            $motherName = $student->mother_name ?? '';
                            $guardianName = $student->guardian_name ?? '';
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $maleCounter }}</td>
                            <td class="font-mono text-[8px]">{{ $student->lrn ?? '' }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $lastName }}">{{ $lastName }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $firstName }}">{{ $firstName }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $middleName }}">{{ $middleName }}</td>
                            <td class="text-center uppercase text-[8px] font-bold">{{ $sexCode }}</td>
                            <td class="text-center text-[8px]">
                                @if($student->birthdate)
                                    {{ \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') }}
                                @endif
                            </td>
                            <td class="text-center text-[8px] font-bold">{{ $age }}</td>
                            <td class="text-center uppercase text-[8px]">{{ $student->mother_tongue ?? '' }}</td>
                            <td class="text-center uppercase text-[8px]">{{ $student->ethnicity ?? '' }}</td>
                            <td class="text-center text-[8px]">{{ $student->religion ?? '' }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $houseStreet }}">{{ $houseStreet }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $barangay }}">{{ $barangay }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $municipality }}">{{ $municipality }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $province }}">{{ $province }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $fatherName }}">{{ $fatherName }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $motherName }}">{{ $motherName }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $guardianName }}">{{ $guardianName }}</td>
                            <td class="text-center uppercase text-[7px]">{{ $student->guardian_relationship ?? '' }}</td>
                            <td class="text-center text-[7px]">{{ $student->guardian_contact ?? '' }}</td>
                            <td class="text-center text-[8px] font-semibold">{{ $student->remarks ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="21" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                        </tr>
                    @endforelse

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="21" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2 border border-black">FEMALE</td>
                    </tr>

                    @php
                        $femaleEnrollments = $enrollments->filter(function($e) {
                            $gender = strtoupper($e->student->gender ?? '');
                            return $gender == 'FEMALE' || $gender == 'F';
                        });
                        $femaleCounter = 0;
                    @endphp

                    @forelse($femaleEnrollments as $enrollment)
                        @php
                            $femaleCounter++;
                            $student = $enrollment->student;
                            if(!$student) continue;
                            
                            $user = $student->user;
                            $lastName = $user->last_name ?? '';
                            $firstName = $user->first_name ?? '';
                            $middleName = $user->middle_name ?? '';
                            
                            $age = '';
                            if (isset($student->attributes['calculated_age'])) {
                                $age = $student->attributes['calculated_age'];
                            } elseif ($student->getAttribute('calculated_age')) {
                                $age = $student->getAttribute('calculated_age');
                            } elseif ($student->calculated_age) {
                                $age = $student->calculated_age;
                            }
                            
                            $gender = strtoupper($student->gender ?? '');
                            $sexCode = $gender == 'MALE' || $gender == 'M' ? 'M' : ($gender == 'FEMALE' || $gender == 'F' ? 'F' : '');
                            
                            $houseStreet = trim(($student->street_address ?? ''));
                            $barangay = $student->barangay ?? '';
                            $municipality = $student->city ?? '';
                            $province = $student->province ?? '';
                            
                            $fatherName = $student->father_name ?? '';
                            $motherName = $student->mother_name ?? '';
                            $guardianName = $student->guardian_name ?? '';
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $maleCounter + $femaleCounter }}</td>
                            <td class="font-mono text-[8px]">{{ $student->lrn ?? '' }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $lastName }}">{{ $lastName }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $firstName }}">{{ $firstName }}</td>
                            <td class="text-left uppercase text-[8px] data-cell" title="{{ $middleName }}">{{ $middleName }}</td>
                            <td class="text-center uppercase text-[8px] font-bold">{{ $sexCode }}</td>
                            <td class="text-center text-[8px]">
                                @if($student->birthdate)
                                    {{ \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') }}
                                @endif
                            </td>
                            <td class="text-center text-[8px] font-bold">{{ $age }}</td>
                            <td class="text-center uppercase text-[8px]">{{ $student->mother_tongue ?? '' }}</td>
                            <td class="text-center uppercase text-[8px]">{{ $student->ethnicity ?? '' }}</td>
                            <td class="text-center text-[8px]">{{ $student->religion ?? '' }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $houseStreet }}">{{ $houseStreet }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $barangay }}">{{ $barangay }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $municipality }}">{{ $municipality }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $province }}">{{ $province }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $fatherName }}">{{ $fatherName }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $motherName }}">{{ $motherName }}</td>
                            <td class="text-left uppercase text-[7px] data-cell" title="{{ $guardianName }}">{{ $guardianName }}</td>
                            <td class="text-center uppercase text-[7px]">{{ $student->guardian_relationship ?? '' }}</td>
                            <td class="text-center text-[7px]">{{ $student->guardian_contact ?? '' }}</td>
                            <td class="text-center text-[8px] font-semibold">{{ $student->remarks ?? '' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="21" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                        </tr>
                    @endforelse

                    <!-- Empty rows for manual writing -->
                    @php 
                        $totalStudents = $maleCounter + $femaleCounter;
                        $totalRows = max(35, $totalStudents + 5); 
                    @endphp
                    @for($i = $totalStudents; $i < $totalRows; $i++)
                        <tr style="height: 18px;">
                            <td class="text-center text-[8px]">{{ $i + 1 }}</td>
                            @for($j = 0; $j < 20; $j++)<td></td>@endfor
                        </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="mt-3 grid grid-cols-3 gap-3 text-xs border-t-2 border-black pt-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="font-semibold text-[10px]">MALE:</span>
                        <span class="border-b-2 border-black w-14 text-center font-bold text-sm">{{ $maleCount }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold text-[10px]">FEMALE:</span>
                        <span class="border-b-2 border-black w-14 text-center font-bold text-sm">{{ $femaleCount }}</span>
                    </div>
                </div>
                <div class="text-center">
                    <div class="flex items-center justify-center gap-2 mb-1">
                        <span class="font-semibold text-[10px]">TOTAL:</span>
                        <span class="border-b-2 border-black w-20 text-center font-bold text-lg">{{ $maleCount + $femaleCount }}</span>
                    </div>
                </div>
                <div class="text-right text-[8px] space-y-0.5 leading-tight">
                    <p class="font-bold border-b border-black inline-block mb-0.5">Legend for Remarks:</p>
                    <p><strong>TI</strong> - Transferred In | <strong>TO</strong> - Transferred Out | <strong>DO</strong> - Dropped Out</p>
                    <p><strong>LE</strong> - Late Enrollee | <strong>CCT</strong> - CCT Recipient | <strong>BA</strong> - Balik Aral</p>
                    <p><strong>LWD</strong> - Learner With Disability</p>
                </div>
            </div>

            <!-- Certification Signatures -->
            <div class="mt-6 grid grid-cols-2 gap-8 text-xs px-6">
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">CERTIFIED CORRECT:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $adviserName }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature of Class Adviser over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">CERTIFIED CORRECT:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature of School Head over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>Generated through LIS</span>
                <span>Date Generated: {{ now()->format('F d, Y h:i A') }}</span>
            </div>

        </div>
        </div>

        <!-- School Info Panel -->
        <div class="no-print mt-6 max-w-[1400px] mx-auto bg-white border border-slate-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-school text-indigo-500"></i>
                School Information (from Settings)
            </h3>
            <div class="grid grid-cols-3 gap-4 text-sm">
                <div class="space-y-2">
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">DepEd School ID:</span>
                        <span class="font-mono font-medium">{{ $schoolId }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">School Name:</span>
                        <span class="font-medium">{{ $schoolName }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">Division:</span>
                        <span>{{ $schoolDivision }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">Region:</span>
                        <span>{{ $schoolRegion }}</span>
                    </div>
                </div>
                <div class="space-y-2">
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">School Head:</span>
                        <span class="font-medium">{{ $schoolHead }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-1">
                        <span class="text-slate-500">Active School Year:</span>
                        <span class="text-indigo-600 font-medium">{{ $schoolYear }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Floating Print Button - Icon Only -->
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>

</body>
</html>