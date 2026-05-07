<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 8 (SF8) - Health and Nutrition Report</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        
        .sf8-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 8px;
        }
        .sf8-table th, .sf8-table td {
            border: 1px solid #000;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }
        .sf8-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 7px;
        }
        .sf8-header {
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
        @media print {
            @page { size: letter landscape; margin: 0.3in 0.2in 0.3in 0.2in; }
            aside, nav[class*="w-72"], div[class*="w-72"], .sidebar, #sidebar, [class*="sidebar"], .no-print { display: none !important; }
            .ml-72, [class*="ml-72"] { margin-left: 0 !important; padding-left: 0 !important; width: 100% !important; }
            body { background: white; font-size: 8pt; }
            .sf8-container { box-shadow: none; margin: 0; padding: 0; max-width: 100% !important; width: 100% !important; }
            .sf8-table { font-size: 7pt; width: 100%; }
        }
        .data-cell { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .nutritional-severely-wasted { background-color: #fecaca; color: #991b1b; font-weight: bold; }
        .nutritional-wasted { background-color: #fed7aa; color: #9a3412; font-weight: bold; }
        .nutritional-normal { background-color: #bbf7d0; color: #166534; font-weight: bold; }
        .nutritional-overweight { background-color: #fef08a; color: #854d0e; font-weight: bold; }
        .nutritional-obese { background-color: #fecaca; color: #991b1b; font-weight: bold; }
        .hfa-severely-stunted { background-color: #fecaca; color: #991b1b; font-weight: bold; }
        .hfa-stunted { background-color: #fed7aa; color: #9a3412; font-weight: bold; }
        .hfa-normal { background-color: #bbf7d0; color: #166534; font-weight: bold; }
        .hfa-tall { background-color: #dbeafe; color: #1e40af; font-weight: bold; }
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

    @include('teacher.includes.sidebar')

    <div class="lg:ml-72 p-6">
        
        <!-- Page Header -->
        <div class="mb-4 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">School Form 8 (SF8)</h1>
                <p class="text-slate-500">Learner's Basic Health and Nutrition Report</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-heartbeat mr-2"></i>SY {{ $activeSchoolYear?->name ?? now()->format('Y') }}
                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('teacher.sf8') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Select Section</label>
                    <select name="section_id" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ $selectedSection && $selectedSection->id == $section->id ? 'selected' : '' }}>
                                {{ $section->gradeLevel->name ?? '' }} - {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-48">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Period</label>
                    <select name="period" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500">
                        <option value="bosy" {{ $selectedPeriod == 'bosy' ? 'selected' : '' }}>Beginning of SY (BoSY)</option>
                        <option value="eosy" {{ $selectedPeriod == 'eosy' ? 'selected' : '' }}>End of SY (EoSY)</option>
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    Load Report
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="no-print mb-4 bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(!$selectedSection)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No section available.</p>
            </div>
        @endif

        @if($healthData->isEmpty() && $selectedSection)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-info-circle text-blue-500 text-3xl mb-2"></i>
                <p class="text-blue-800 font-medium">No enrolled students found.</p>
            </div>
        @endif

        <!-- Summary Cards (No Print) -->
        @if($healthData->isNotEmpty())
        <div class="no-print grid grid-cols-5 gap-3 mb-4">
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <p class="text-xs text-slate-500 uppercase font-semibold">Total Students</p>
                <p class="text-2xl font-bold text-slate-800">{{ $summaryStats['total_students'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <p class="text-xs text-emerald-600 uppercase font-semibold">Assessed</p>
                <p class="text-2xl font-bold text-emerald-600">{{ $summaryStats['assessed_count'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <p class="text-xs text-rose-600 uppercase font-semibold">Malnourished</p>
                <p class="text-2xl font-bold text-rose-600">
                    {{ $summaryStats['male_severely_wasted'] + $summaryStats['male_wasted'] + $summaryStats['female_severely_wasted'] + $summaryStats['female_wasted'] }}
                </p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <p class="text-xs text-amber-600 uppercase font-semibold">Stunted</p>
                <p class="text-2xl font-bold text-amber-600">
                    {{ $summaryStats['male_severely_stunted'] + $summaryStats['male_stunted'] + $summaryStats['female_severely_stunted'] + $summaryStats['female_stunted'] }}
                </p>
            </div>
            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
                <p class="text-xs text-blue-600 uppercase font-semibold">Obese/Overweight</p>
                <p class="text-2xl font-bold text-blue-600">
                    {{ $summaryStats['male_overweight'] + $summaryStats['male_obese'] + $summaryStats['female_overweight'] + $summaryStats['female_obese'] }}
                </p>
            </div>
        </div>
        @endif

        @if($healthData->isNotEmpty())
<div class="overflow-x-auto pb-4">`n        <div class="sf8-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto">
            
            <!-- School Header -->
            <div class="grid grid-cols-4 gap-3 mb-3 text-xs">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">School ID:</span>
                        <span class="border-b border-black flex-1 px-1 font-mono text-[10px]">{{ $schoolId }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Region:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolRegion }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Name:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolName }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Division:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolDivision }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">District:</span>
                        <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolDistrict }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">School Year:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $activeSchoolYear?->name ?? '___________' }}</span>
                    </div>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-24 text-[10px]">Grade Level:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->gradeLevel->name ?? '___________' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="font-semibold w-20 text-[10px]">Section:</span>
                        <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->name ?? '___________' }}</span>
                    </div>
                </div>
            </div>

            <!-- SF8 Title -->
            <div class="sf8-header mb-0">
                SCHOOL FORM 8 (SF8) LEARNER'S BASIC HEALTH AND NUTRITION REPORT<br>
                <span class="text-[9px] font-normal">({{ $selectedPeriod == 'bosy' ? 'Beginning' : 'End' }} of School Year)</span>
            </div>

            <!-- Main SF8 Table -->
            <table class="sf8-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%;">NO.</th>
                        <th rowspan="2" style="width: 5%;">LRN</th>
                        <th rowspan="2" style="width: 18%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 6%;">Birthdate<br>(MM/DD/YYYY)</th>
                        <th rowspan="2" style="width: 4%;">Age<br>(YY.MM)</th>
                        <th rowspan="2" style="width: 4%;">Sex<br>(M/F)</th>
                        <th rowspan="2" style="width: 5%;">Weight<br>(kg)</th>
                        <th rowspan="2" style="width: 5%;">Height<br>(m)</th>
                        <th rowspan="2" style="width: 5%;">Height²<br>(m²)</th>
                        <th rowspan="2" style="width: 5%;">BMI<br>(kg/m²)</th>
                        <th colspan="2" style="width: 20%;">NUTRITIONAL STATUS</th>
                        <th rowspan="2" style="width: 15%;">Remarks</th>
                        <th rowspan="2" class="no-print" style="width: 5%;">Action</th>
                    </tr>
                    <tr>
                        <th style="width: 10%;">BMI Category</th>
                        <th style="width: 10%;">Height for Age<br>(HFA)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- MALE Section -->
                    <tr>
                        <td colspan="14" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
                    </tr>
                    
                    @php $maleData = $healthData->where('gender', 'M'); @endphp
                    @forelse($maleData as $index => $data)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-[8px]">{{ $data['lrn'] }}</td>
                        <td class="text-left uppercase text-[8px] data-cell pl-2" title="{{ $data['full_name'] }}">{{ $data['full_name'] }}</td>
                        <td class="text-[8px]">{{ $data['birthdate'] }}</td>
                        <td class="text-[8px]">{{ $data['age_formatted'] }}</td>
                        <td class="font-bold">M</td>
                        <td class="font-bold text-[9px]">{{ $data['weight'] ?? '' }}</td>
                        <td class="text-[8px]">{{ $data['height'] ?? '' }}</td>
                        <td class="text-[8px]">{{ $data['height_squared'] ?? '' }}</td>
                        <td class="font-bold text-[9px]">{{ $data['bmi'] ?? '' }}</td>
                        <td class="nutritional-{{ strtolower(str_replace(' ', '-', $data['nutritional_status'])) }} text-[8px]">
                            {{ $data['nutritional_status'] ?? 'Not Assessed' }}
                        </td>
                        <td class="hfa-{{ strtolower(str_replace(' ', '-', $data['height_for_age'])) }} text-[8px]">
                            {{ $data['height_for_age'] ?? 'Not Assessed' }}
                        </td>
                        <td class="text-[8px]">{{ $data['remarks'] ?? '' }}</td>
                        <td class="no-print">
                            <button onclick="openHealthModal({{ $data['student']->id }}, '{{ $data['full_name'] }}', {{ $data['weight'] ?? 'null' }}, {{ $data['height'] ?? 'null' }}, '{{ $data['remarks'] ?? '' }}', {{ $data['age'] ?? 'null' }}, 'M')" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-{{ $data['health_record_id'] ? 'edit' : 'plus' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-2 text-slate-400 text-[8px]">No male students</td>
                    </tr>
                    @endforelse

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="14" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                    </tr>
                    
                    @php $femaleData = $healthData->where('gender', 'F'); @endphp
                    @forelse($femaleData as $index => $data)
                    <tr>
                        <td>{{ $maleData->count() + $index + 1 }}</td>
                        <td class="text-[8px]">{{ $data['lrn'] }}</td>
                        <td class="text-left uppercase text-[8px] data-cell pl-2" title="{{ $data['full_name'] }}">{{ $data['full_name'] }}</td>
                        <td class="text-[8px]">{{ $data['birthdate'] }}</td>
                        <td class="text-[8px]">{{ $data['age_formatted'] }}</td>
                        <td class="font-bold">F</td>
                        <td class="font-bold text-[9px]">{{ $data['weight'] ?? '' }}</td>
                        <td class="text-[8px]">{{ $data['height'] ?? '' }}</td>
                        <td class="text-[8px]">{{ $data['height_squared'] ?? '' }}</td>
                        <td class="font-bold text-[9px]">{{ $data['bmi'] ?? '' }}</td>
                        <td class="nutritional-{{ strtolower(str_replace(' ', '-', $data['nutritional_status'])) }} text-[8px]">
                            {{ $data['nutritional_status'] ?? 'Not Assessed' }}
                        </td>
                        <td class="hfa-{{ strtolower(str_replace(' ', '-', $data['height_for_age'])) }} text-[8px]">
                            {{ $data['height_for_age'] ?? 'Not Assessed' }}
                        </td>
                        <td class="text-[8px]">{{ $data['remarks'] ?? '' }}</td>
                        <td class="no-print">
                            <button onclick="openHealthModal({{ $data['student']->id }}, '{{ $data['full_name'] }}', {{ $data['weight'] ?? 'null' }}, {{ $data['height'] ?? 'null' }}, '{{ $data['remarks'] ?? '' }}', {{ $data['age'] ?? 'null' }}, 'F')" class="text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-{{ $data['health_record_id'] ? 'edit' : 'plus' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="text-center py-2 text-slate-400 text-[8px]">No female students</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Summary Tables -->
            <div class="mt-4 grid grid-cols-2 gap-4">
                <!-- Nutritional Status Summary -->
                <table class="sf8-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-gray-100 font-bold text-[9px]">NUTRITIONAL STATUS (BMI) SUMMARY TABLE</th>
                        </tr>
                        <tr>
                            <th class="text-[8px]">Category</th>
                            <th class="text-[8px]">Male</th>
                            <th class="text-[8px]">Female</th>
                            <th class="text-[8px]">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="nutritional-severely-wasted">
                            <td class="text-left pl-2 font-bold text-[8px]">Severely Wasted</td>
                            <td>{{ $summaryStats['male_severely_wasted'] > 0 ? $summaryStats['male_severely_wasted'] : '' }}</td>
                            <td>{{ $summaryStats['female_severely_wasted'] > 0 ? $summaryStats['female_severely_wasted'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_severely_wasted'] + $summaryStats['female_severely_wasted']) > 0 ? ($summaryStats['male_severely_wasted'] + $summaryStats['female_severely_wasted']) : '' }}</td>
                        </tr>
                        <tr class="nutritional-wasted">
                            <td class="text-left pl-2 font-bold text-[8px]">Wasted</td>
                            <td>{{ $summaryStats['male_wasted'] > 0 ? $summaryStats['male_wasted'] : '' }}</td>
                            <td>{{ $summaryStats['female_wasted'] > 0 ? $summaryStats['female_wasted'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_wasted'] + $summaryStats['female_wasted']) > 0 ? ($summaryStats['male_wasted'] + $summaryStats['female_wasted']) : '' }}</td>
                        </tr>
                        <tr class="nutritional-normal">
                            <td class="text-left pl-2 font-bold text-[8px]">Normal</td>
                            <td>{{ $summaryStats['male_normal'] > 0 ? $summaryStats['male_normal'] : '' }}</td>
                            <td>{{ $summaryStats['female_normal'] > 0 ? $summaryStats['female_normal'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_normal'] + $summaryStats['female_normal']) > 0 ? ($summaryStats['male_normal'] + $summaryStats['female_normal']) : '' }}</td>
                        </tr>
                        <tr class="nutritional-overweight">
                            <td class="text-left pl-2 font-bold text-[8px]">Overweight</td>
                            <td>{{ $summaryStats['male_overweight'] > 0 ? $summaryStats['male_overweight'] : '' }}</td>
                            <td>{{ $summaryStats['female_overweight'] > 0 ? $summaryStats['female_overweight'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_overweight'] + $summaryStats['female_overweight']) > 0 ? ($summaryStats['male_overweight'] + $summaryStats['female_overweight']) : '' }}</td>
                        </tr>
                        <tr class="nutritional-obese">
                            <td class="text-left pl-2 font-bold text-[8px]">Obese</td>
                            <td>{{ $summaryStats['male_obese'] > 0 ? $summaryStats['male_obese'] : '' }}</td>
                            <td>{{ $summaryStats['female_obese'] > 0 ? $summaryStats['female_obese'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_obese'] + $summaryStats['female_obese']) > 0 ? ($summaryStats['male_obese'] + $summaryStats['female_obese']) : '' }}</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Height for Age Summary -->
                <table class="sf8-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="bg-gray-100 font-bold text-[9px]">HEIGHT FOR AGE (HFA) SUMMARY TABLE</th>
                        </tr>
                        <tr>
                            <th class="text-[8px]">Category</th>
                            <th class="text-[8px]">Male</th>
                            <th class="text-[8px]">Female</th>
                            <th class="text-[8px]">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hfa-severely-stunted">
                            <td class="text-left pl-2 font-bold text-[8px]">Severely Stunted</td>
                            <td>{{ $summaryStats['male_severely_stunted'] > 0 ? $summaryStats['male_severely_stunted'] : '' }}</td>
                            <td>{{ $summaryStats['female_severely_stunted'] > 0 ? $summaryStats['female_severely_stunted'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_severely_stunted'] + $summaryStats['female_severely_stunted']) > 0 ? ($summaryStats['male_severely_stunted'] + $summaryStats['female_severely_stunted']) : '' }}</td>
                        </tr>
                        <tr class="hfa-stunted">
                            <td class="text-left pl-2 font-bold text-[8px]">Stunted</td>
                            <td>{{ $summaryStats['male_stunted'] > 0 ? $summaryStats['male_stunted'] : '' }}</td>
                            <td>{{ $summaryStats['female_stunted'] > 0 ? $summaryStats['female_stunted'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_stunted'] + $summaryStats['female_stunted']) > 0 ? ($summaryStats['male_stunted'] + $summaryStats['female_stunted']) : '' }}</td>
                        </tr>
                        <tr class="hfa-normal">
                            <td class="text-left pl-2 font-bold text-[8px]">Normal</td>
                            <td>{{ $summaryStats['male_normal_hfa'] > 0 ? $summaryStats['male_normal_hfa'] : '' }}</td>
                            <td>{{ $summaryStats['female_normal_hfa'] > 0 ? $summaryStats['female_normal_hfa'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_normal_hfa'] + $summaryStats['female_normal_hfa']) > 0 ? ($summaryStats['male_normal_hfa'] + $summaryStats['female_normal_hfa']) : '' }}</td>
                        </tr>
                        <tr class="hfa-tall">
                            <td class="text-left pl-2 font-bold text-[8px]">Tall</td>
                            <td>{{ $summaryStats['male_tall'] > 0 ? $summaryStats['male_tall'] : '' }}</td>
                            <td>{{ $summaryStats['female_tall'] > 0 ? $summaryStats['female_tall'] : '' }}</td>
                            <td class="font-bold">{{ ($summaryStats['male_tall'] + $summaryStats['female_tall']) > 0 ? ($summaryStats['male_tall'] + $summaryStats['female_tall']) : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Guidelines -->
            <div class="mt-3 text-[8px] space-y-1 leading-tight border-t-2 border-black pt-2">
                <p class="font-bold">GUIDELINES:</p>
                <p>1. This form shall be accomplished at the <strong>Beginning of School Year (BoSY)</strong> and <strong>End of School Year (EoSY)</strong> by the Class Adviser/MAPEH Teacher.</p>
                <p>2. <strong>Nutritional Status (BMI)</strong> categories: Severely Wasted (BMI &lt; 14), Wasted (BMI 14-15), Normal (BMI 15-25), Overweight (BMI 25-30), Obese (BMI &gt; 30).</p>
                <p>3. <strong>Height for Age (HFA)</strong> categories: Severely Stunted, Stunted, Normal, Tall (based on WHO Child Growth Standards).</p>
                <p>4. Weight in kilograms (kg), Height in meters (m). BMI = Weight / Height².</p>
                <p>5. This form shall be submitted to the Division Office through the LIS.</p>
            </div>

            <!-- Signatures -->
            <div class="mt-4 grid grid-cols-3 gap-4 text-xs px-6">
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Date of Assessment:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center text-[9px] mt-0.5">___________________</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Conducted/Assessed By:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $adviserName }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Class Adviser/MAPEH Teacher)</p>
                    </div>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Certified Correct By:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                        <p class="text-center text-[9px] mt-0.5">(School Head)</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>School Form 8: Page 1 of 1</span>
                <span>Generated: {{ now()->format('F d, Y h:i A') }}</span>
            </div>

        </div>
        @endif

    </div>

    <!-- Health Record Modal -->
    <div id="healthModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 no-print">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Add/Edit Health Record</h3>
            <form id="healthForm" method="POST" action="{{ route('teacher.sf8.store') }}">
                @csrf
                <input type="hidden" name="student_id" id="studentId">
                <input type="hidden" name="section_id" value="{{ $selectedSection?->id }}">
                <input type="hidden" name="period" value="{{ $selectedPeriod }}">
                <input type="hidden" name="age" id="studentAge">
                <input type="hidden" name="gender" id="studentGender">

                <div class="mb-4">
                    <p class="text-sm text-slate-600">Student: <span id="studentNameDisplay" class="font-bold"></span></p>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Weight (kg)</label>
                        <input type="number" name="weight" id="weight" step="0.01" min="0" max="200" required 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Height (m)</label>
                        <input type="number" name="height" id="height" step="0.01" min="0" max="3" required 
                            class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Date of Assessment</label>
                    <input type="date" name="date_of_assessment" id="dateOfAssessment" required 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="2" 
                        class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="e.g., Refer to school nurse, Follow-up required"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeHealthModal()" class="px-4 py-2 text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save Record</button>
                </div>
            </form>
        </div>
    </div>

    @if($healthData->isNotEmpty())
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>
    @endif

    <script>
        function openHealthModal(studentId, name, weight, height, remarks, age, gender) {
            document.getElementById('studentId').value = studentId;
            document.getElementById('studentNameDisplay').textContent = name;
            document.getElementById('studentAge').value = age;
            document.getElementById('studentGender').value = gender;
            
            document.getElementById('weight').value = weight ?? '';
            document.getElementById('height').value = height ?? '';
            document.getElementById('remarks').value = remarks ?? '';
            document.getElementById('dateOfAssessment').value = new Date().toISOString().split('T')[0];
            
            document.getElementById('healthModal').classList.remove('hidden');
            document.getElementById('healthModal').classList.add('flex');
        }

        function closeHealthModal() {
            document.getElementById('healthModal').classList.add('hidden');
            document.getElementById('healthModal').classList.remove('flex');
        }
    </script>

</body>
</html>