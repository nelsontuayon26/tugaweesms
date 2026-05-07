@if($healthData->isNotEmpty())
<div class="overflow-x-auto pb-4">
    <div class="sf8-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto min-w-[1024px]">
    <style>
        .sf8-table { border-collapse: collapse; width: 100%; font-size: 8px; }
        .sf8-table th, .sf8-table td { border: 1px solid #000; padding: 3px 4px; text-align: center; vertical-align: middle; }
        .sf8-table th { background-color: #e5e7eb; font-weight: 600; text-transform: uppercase; font-size: 7px; }
        .sf8-header { background-color: #1e3a8a; color: white; font-weight: bold; font-size: 11px; text-align: center; padding: 8px; border: 1px solid #000; }
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
            </tr>
            <tr>
                <th style="width: 10%;">BMI Category</th>
                <th style="width: 10%;">Height for Age<br>(HFA)</th>
            </tr>
        </thead>
        <tbody>
            <!-- MALE Section -->
            <tr>
                <td colspan="13" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
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
                    <td class="nutritional-{{ strtolower(str_replace(' ', '-', $data['nutritional_status'])) }} text-[8px]">{{ $data['nutritional_status'] ?? 'Not Assessed' }}</td>
                    <td class="hfa-{{ strtolower(str_replace(' ', '-', $data['height_for_age'])) }} text-[8px]">{{ $data['height_for_age'] ?? 'Not Assessed' }}</td>
                    <td class="text-[8px]">{{ $data['remarks'] ?? '' }}</td>
                </tr>
            @empty
                <tr><td colspan="13" class="text-center py-2 text-slate-400 text-[8px]">No male students</td></tr>
            @endforelse

            <!-- FEMALE Section -->
            <tr>
                <td colspan="13" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
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
                    <td class="nutritional-{{ strtolower(str_replace(' ', '-', $data['nutritional_status'])) }} text-[8px]">{{ $data['nutritional_status'] ?? 'Not Assessed' }}</td>
                    <td class="hfa-{{ strtolower(str_replace(' ', '-', $data['height_for_age'])) }} text-[8px]">{{ $data['height_for_age'] ?? 'Not Assessed' }}</td>
                    <td class="text-[8px]">{{ $data['remarks'] ?? '' }}</td>
                </tr>
            @empty
                <tr><td colspan="13" class="text-center py-2 text-slate-400 text-[8px]">No female students</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary Tables -->
    <div class="mt-4 grid grid-cols-2 gap-4">
        <!-- Nutritional Status Summary -->
        <table class="sf8-table">
            <thead>
                <tr><th colspan="4" class="bg-gray-100 font-bold text-[9px]">NUTRITIONAL STATUS (BMI) SUMMARY TABLE</th></tr>
                <tr><th class="text-[8px]">Category</th><th class="text-[8px]">Male</th><th class="text-[8px]">Female</th><th class="text-[8px]">Total</th></tr>
            </thead>
            <tbody>
                <tr class="nutritional-severely-wasted"><td class="text-left pl-2 font-bold text-[8px]">Severely Wasted</td><td>{{ $summaryStats['male_severely_wasted'] > 0 ? $summaryStats['male_severely_wasted'] : '' }}</td><td>{{ $summaryStats['female_severely_wasted'] > 0 ? $summaryStats['female_severely_wasted'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_severely_wasted'] + $summaryStats['female_severely_wasted']) > 0 ? ($summaryStats['male_severely_wasted'] + $summaryStats['female_severely_wasted']) : '' }}</td></tr>
                <tr class="nutritional-wasted"><td class="text-left pl-2 font-bold text-[8px]">Wasted</td><td>{{ $summaryStats['male_wasted'] > 0 ? $summaryStats['male_wasted'] : '' }}</td><td>{{ $summaryStats['female_wasted'] > 0 ? $summaryStats['female_wasted'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_wasted'] + $summaryStats['female_wasted']) > 0 ? ($summaryStats['male_wasted'] + $summaryStats['female_wasted']) : '' }}</td></tr>
                <tr class="nutritional-normal"><td class="text-left pl-2 font-bold text-[8px]">Normal</td><td>{{ $summaryStats['male_normal'] > 0 ? $summaryStats['male_normal'] : '' }}</td><td>{{ $summaryStats['female_normal'] > 0 ? $summaryStats['female_normal'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_normal'] + $summaryStats['female_normal']) > 0 ? ($summaryStats['male_normal'] + $summaryStats['female_normal']) : '' }}</td></tr>
                <tr class="nutritional-overweight"><td class="text-left pl-2 font-bold text-[8px]">Overweight</td><td>{{ $summaryStats['male_overweight'] > 0 ? $summaryStats['male_overweight'] : '' }}</td><td>{{ $summaryStats['female_overweight'] > 0 ? $summaryStats['female_overweight'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_overweight'] + $summaryStats['female_overweight']) > 0 ? ($summaryStats['male_overweight'] + $summaryStats['female_overweight']) : '' }}</td></tr>
                <tr class="nutritional-obese"><td class="text-left pl-2 font-bold text-[8px]">Obese</td><td>{{ $summaryStats['male_obese'] > 0 ? $summaryStats['male_obese'] : '' }}</td><td>{{ $summaryStats['female_obese'] > 0 ? $summaryStats['female_obese'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_obese'] + $summaryStats['female_obese']) > 0 ? ($summaryStats['male_obese'] + $summaryStats['female_obese']) : '' }}</td></tr>
            </tbody>
        </table>

        <!-- Height for Age Summary -->
        <table class="sf8-table">
            <thead>
                <tr><th colspan="4" class="bg-gray-100 font-bold text-[9px]">HEIGHT FOR AGE (HFA) SUMMARY TABLE</th></tr>
                <tr><th class="text-[8px]">Category</th><th class="text-[8px]">Male</th><th class="text-[8px]">Female</th><th class="text-[8px]">Total</th></tr>
            </thead>
            <tbody>
                <tr class="hfa-severely-stunted"><td class="text-left pl-2 font-bold text-[8px]">Severely Stunted</td><td>{{ $summaryStats['male_severely_stunted'] > 0 ? $summaryStats['male_severely_stunted'] : '' }}</td><td>{{ $summaryStats['female_severely_stunted'] > 0 ? $summaryStats['female_severely_stunted'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_severely_stunted'] + $summaryStats['female_severely_stunted']) > 0 ? ($summaryStats['male_severely_stunted'] + $summaryStats['female_severely_stunted']) : '' }}</td></tr>
                <tr class="hfa-stunted"><td class="text-left pl-2 font-bold text-[8px]">Stunted</td><td>{{ $summaryStats['male_stunted'] > 0 ? $summaryStats['male_stunted'] : '' }}</td><td>{{ $summaryStats['female_stunted'] > 0 ? $summaryStats['female_stunted'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_stunted'] + $summaryStats['female_stunted']) > 0 ? ($summaryStats['male_stunted'] + $summaryStats['female_stunted']) : '' }}</td></tr>
                <tr class="hfa-normal"><td class="text-left pl-2 font-bold text-[8px]">Normal</td><td>{{ $summaryStats['male_normal_hfa'] > 0 ? $summaryStats['male_normal_hfa'] : '' }}</td><td>{{ $summaryStats['female_normal_hfa'] > 0 ? $summaryStats['female_normal_hfa'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_normal_hfa'] + $summaryStats['female_normal_hfa']) > 0 ? ($summaryStats['male_normal_hfa'] + $summaryStats['female_normal_hfa']) : '' }}</td></tr>
                <tr class="hfa-tall"><td class="text-left pl-2 font-bold text-[8px]">Tall</td><td>{{ $summaryStats['male_tall'] > 0 ? $summaryStats['male_tall'] : '' }}</td><td>{{ $summaryStats['female_tall'] > 0 ? $summaryStats['female_tall'] : '' }}</td><td class="font-bold">{{ ($summaryStats['male_tall'] + $summaryStats['female_tall']) > 0 ? ($summaryStats['male_tall'] + $summaryStats['female_tall']) : '' }}</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Guidelines -->
    <div class="mt-3 text-[8px] space-y-1 leading-tight border-t-2 border-black pt-2">
        <p class="font-bold">GUIDELINES:</p>
        <p>1. This form shall be accomplished at the <strong>Beginning of School Year (BoSY)</strong> and <strong>End of School Year (EoSY)</strong>.</p>
        <p>2. <strong>Nutritional Status (BMI)</strong> categories: Severely Wasted, Wasted, Normal, Overweight, Obese.</p>
        <p>3. <strong>Height for Age (HFA)</strong> categories: Severely Stunted, Stunted, Normal, Tall.</p>
        <p>4. Weight in kilograms (kg), Height in meters (m). BMI = Weight / Height².</p>
    </div>

    <!-- Signatures -->
    <div class="mt-4 grid grid-cols-3 gap-4 text-xs px-6">
        <div class="text-center">
            <p class="font-semibold mb-4 text-[10px] text-left">Date of Assessment:</p>
            <div class="mt-6 border-t border-black pt-1"><p class="text-center text-[9px] mt-0.5">___________________</p></div>
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
</div>
@else
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 text-center">
        <p class="text-blue-800 font-medium">No enrolled students found for this section/period.</p>
    </div>
@endif
