@if($selectedStudent)
@php
    $user = $selectedStudent->user;
    $section = $selectedSection;
    $gradeLevel = $section?->gradeLevel ?? null;
    $age = '';
    if ($selectedStudent->birthdate) {
        $age = \Carbon\Carbon::parse($selectedStudent->birthdate)->diffInYears(\Carbon\Carbon::now());
    }
@endphp
<div class="overflow-x-auto pb-4">
    <div class="sf9-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 min-w-[1024px]" style="margin: 0 auto;">
    <style>
        .sf9-table { border-collapse: collapse; width: 100%; font-size: 9pt; }
        .sf9-table th, .sf9-table td { border: 1px solid #000; padding: 4px 6px; text-align: center; vertical-align: middle; }
        .sf9-table th { font-weight: bold; font-size: 8.5pt; }
        .header-box { border: 2px solid #000; padding: 10px; text-align: center; background: #fff; margin-bottom: 0; }
        .section-header { background: #1e3a8a; color: white; padding: 5px 8px; font-weight: bold; font-size: 10px; text-transform: uppercase; margin-top: 12px; }
        .info-grid { border: 1px solid #000; border-top: none; }
        .info-row { display: flex; border-bottom: 1px solid #000; }
        .info-row:last-child { border-bottom: none; }
        .info-label { font-weight: bold; width: 100px; font-size: 9pt; padding: 4px 8px; border-right: 1px solid #000; background: #f9f9f9; text-transform: uppercase; }
        .info-value { flex: 1; font-size: 9pt; padding: 4px 8px; text-transform: uppercase; }
        .grade-failed { font-weight: bold; }
    </style>

    <!-- Header -->
    <div class="header-box">
        <div class="text-center">
            <p class="text-xs font-normal mb-1">Republic of the Philippines</p>
            <p class="text-sm font-bold uppercase tracking-wide border-b border-black pb-1 mb-1 inline-block">Department of Education</p>
            <div class="flex justify-center gap-8 mt-2 text-xs">
                <span>Region <strong>{{ $schoolRegion ?? '__' }}</strong></span>
                <span>Division of <strong>{{ $schoolDivision ?? '____________________' }}</strong></span>
                <span>District <strong>{{ $schoolDistrict ?? '__________' }}</strong></span>
            </div>
            <p class="text-base font-bold mt-2 uppercase tracking-wide">{{ $schoolName ?? 'SCHOOL NAME' }}</p>
            <p class="text-lg font-bold mt-2 uppercase tracking-widest border-t-2 border-b-2 border-black inline-block px-6 py-1">Learner's Progress Report Card</p>
            <p class="text-xs mt-2 font-bold">School Year <strong>{{ $activeSchoolYear?->name ?? '' }}</strong></p>
        </div>
    </div>

    <!-- Learner Information -->
    <div class="section-header">Learner's Information</div>
    <div class="info-grid">
        <div class="info-row">
            <span class="info-label">Name:</span>
            <span class="info-value">{{ $user->last_name ?? '' }}, {{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }} <span class="text-[9px] text-slate-600 normal-case">(LRN: {{ $selectedStudent->lrn ?? 'N/A' }})</span></span>
        </div>
        <div class="info-row">
            <span class="info-label">Age:</span>
            <span class="info-value">{{ floor($age) }}</span>
            <span class="info-label" style="width: 80px; border-left: 1px solid #000;">Sex:</span>
            <span class="info-value" style="width: 140px;">{{ $selectedStudent->gender ?? '' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Grade Level:</span>
            <span class="info-value">{{ $gradeLevel?->name ?? '' }}</span>
            <span class="info-label" style="width: 80px; border-left: 1px solid #000;">Section:</span>
            <span class="info-value" style="width: 140px;">{{ $section?->name ?? '' }}</span>
        </div>
    </div>

    @if($isKindergarten)
        <div class="section-header">Developmental Progress Report</div>
        @php
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
        @foreach($kinderConfig as $domainKey => $domainData)
            <div class="border border-black mb-2">
                <div class="bg-gray-100 p-2 border-b border-black">
                    <strong style="font-size: 9pt; text-transform: uppercase;">{{ $domainData['name'][$lang] ?? $domainData['name']['cebuano'] }}</strong>
                </div>
                <table class="sf9-table" style="font-size: 8pt;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th rowspan="2" style="width: 50%; text-align: left; padding-left: 8px; vertical-align: middle;">{{ $lang == 'cebuano' ? 'Mga Tigpasiunod (Indicators)' : 'Indicators' }}</th>
                            <th colspan="4" style="text-align: center;">Quarter</th>
                        </tr>
                        <tr style="background-color: #f9fafb;">
                            <th style="width: 12.5%;">1</th>
                            <th style="width: 12.5%;">2</th>
                            <th style="width: 12.5%;">3</th>
                            <th style="width: 12.5%;">4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($domainData['indicators']))
                            @foreach($domainData['indicators'] as $indicatorKey => $indicatorData)
                                <tr style="{{ $loop->even ? 'background-color: #f9fafb;' : '' }}">
                                    <td class="text-left pl-2" style="font-size: 7.5pt; text-align: justify; padding: 5px 8px; line-height: 1.4;">{{ $indicatorData[$lang] ?? $indicatorData['cebuano'] }}</td>
                                    <td class="font-bold">{{ $getKinderRating($domainKey, $indicatorKey, 1) }}</td>
                                    <td class="font-bold">{{ $getKinderRating($domainKey, $indicatorKey, 2) }}</td>
                                    <td class="font-bold">{{ $getKinderRating($domainKey, $indicatorKey, 3) }}</td>
                                    <td class="font-bold">{{ $getKinderRating($domainKey, $indicatorKey, 4) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        @endforeach
        <div class="border border-black p-2 mb-3 text-xs">
            <h4 class="font-bold text-xs mb-2 uppercase text-center border-b border-black pb-1">Rating Scale</h4>
            <table style="width:100%; font-size: 8pt;">
                <tbody>
                    <tr>
                        <td class="text-center font-bold" style="width: 15%;">B</td>
                        <td class="text-left"><strong>{{ $ratingScale['B']['label'][$lang] }} ({{ $ratingScale['B']['label']['cebuano'] }})</strong> - {{ $ratingScale['B']['description'][$lang] }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold">D</td>
                        <td class="text-left"><strong>{{ $ratingScale['D']['label'][$lang] }} ({{ $ratingScale['D']['label']['cebuano'] }})</strong> - {{ $ratingScale['D']['description'][$lang] }}</td>
                    </tr>
                    <tr>
                        <td class="text-center font-bold">C</td>
                        <td class="text-left"><strong>{{ $ratingScale['C']['label'][$lang] }} ({{ $ratingScale['C']['label']['cebuano'] }})</strong> - {{ $ratingScale['C']['description'][$lang] }}</td>
                    </tr>
                </tbody>
            </table>
    </div>
    @else
        <div class="section-header">Report on Learning Progress and Achievement</div>
        <table class="sf9-table">
            <thead>
                <tr>
                    <th rowspan="2" style="width: 32%; text-align: left; padding-left: 8px;">Learning Areas</th>
                    <th colspan="4">Quarterly Rating</th>
                    <th rowspan="2" style="width: 12%;">Final Rating</th>
                    <th rowspan="2" style="width: 12%;">Remarks</th>
                </tr>
                <tr>
                    <th style="width: 9%;">1st</th>
                    <th style="width: 9%;">2nd</th>
                    <th style="width: 9%;">3rd</th>
                    <th style="width: 9%;">4th</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjectGrades as $sg)
                    <tr>
                        <td class="text-left pl-2 text-sm" style="font-size: 9pt;">{{ $sg['subject_name'] }}</td>
                        <td>{{ $sg['quarter_1'] ?? '' }}</td>
                        <td>{{ $sg['quarter_2'] ?? '' }}</td>
                        <td>{{ $sg['quarter_3'] ?? '' }}</td>
                        <td>{{ $sg['quarter_4'] ?? '' }}</td>
                        <td class="font-bold {{ $sg['final_grade'] !== null && $sg['final_grade'] < 75 ? 'grade-failed' : '' }}">{{ $sg['final_grade'] ?? '' }}</td>
                        <td class="{{ $sg['remarks'] == 'Failed' ? 'grade-failed' : '' }}">{{ $sg['remarks'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-6 text-gray-500 text-center">No subjects found for this grade level.</td></tr>
                @endforelse
                @if($generalAverage !== null)
                    <tr style="background: #f3f4f6; font-weight: bold;">
                        <td colspan="5" class="text-right pr-4" style="font-size: 9pt;">GENERAL AVERAGE</td>
                        <td class="{{ $generalAverage < 75 ? 'grade-failed' : '' }}">{{ $generalAverage }}</td>
                        <td class="{{ $generalAverage < 75 ? 'grade-failed' : '' }}">{{ $generalAverage >= 75 ? 'Passed' : 'Failed' }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endif

    <!-- Core Values -->
    <div class="section-header">Report on Learner's Observed Values</div>
    @php
    $coreValueOrder = ['Maka-Diyos', 'Makatao', 'Maka-Kalikasan', 'Maka-bansa'];
    $sortedCoreValues = collect($coreValueOrder)->mapWithKeys(function($cv) use ($coreValues) {
        return $coreValues->has($cv) ? [$cv => $coreValues[$cv]] : [];
    });
    foreach ($coreValues as $cv => $statements) {
        if (!in_array($cv, $coreValueOrder)) {
            $sortedCoreValues[$cv] = $statements;
        }
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
    <table class="sf9-table" style="font-size: 8.5pt;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 15%; text-align: left; vertical-align: middle;">Core Values</th>
                <th rowspan="2" style="width: 55%; text-align: left; vertical-align: middle;">Behavior Statements</th>
                <th colspan="4" style="text-align: center;">Quarter</th>
            </tr>
            <tr>
                <th style="width: 8%;">1st</th>
                <th style="width: 8%;">2nd</th>
                <th style="width: 8%;">3rd</th>
                <th style="width: 8%;">4th</th>
            </tr>
        </thead>
        <tbody>
            @php $rowIndex = 0; $coreValueNumber = 1; @endphp
            @foreach($sortedCoreValues as $coreValue => $statements)
                @php $statementKeys = $statements->keys()->sort()->values(); $behaviorCount = $statementKeys->count(); @endphp
                @foreach($statementKeys as $index => $statementKey)
                    <tr @if($rowIndex % 2 == 1) style="background: #f9fafb;" @endif>
                        @if($index === 0)
                            <td rowspan="{{ $behaviorCount }}" class="font-bold align-top bg-gray-50" style="text-align: left; vertical-align: top;">{{ $coreValueNumber }}. {{ $coreValue }}</td>
                        @endif
                        <td style="text-align: left; vertical-align: middle;">{{ $getBehaviorStatement($coreValue, $statementKey) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 1) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 2) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 3) }}</td>
                        <td style="text-align: center; font-weight: bold;">{{ $getCoreValueRating($coreValue, $statementKey, 4) }}</td>
                    </tr>
                    @php $rowIndex++; @endphp
                @endforeach
                @php $coreValueNumber++; @endphp
            @endforeach
            @if($sortedCoreValues->isEmpty())
                <tr><td colspan="6" class="text-center py-4 text-gray-500">No core values records found.</td></tr>
            @endif
        </tbody>
    </table>
    <div class="border border-black p-2 mt-1 text-xs">
        <span class="font-bold">Marking:</span>
        <span class="ml-2"><strong>AO</strong> - Always Observed</span>
        <span class="ml-2"><strong>SO</strong> - Sometimes Observed</span>
        <span class="ml-2"><strong>RO</strong> - Rarely Observed</span>
        <span class="ml-2"><strong>NO</strong> - Not Observed</span>
    </div>

    <!-- Attendance -->
    <div class="section-header">Report on Attendance</div>
    @php
        $months = ['JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC', 'JAN', 'FEB', 'MAR', 'APR', 'MAY'];
        $attendanceData = []; $totalPresent = 0; $totalAbsent = 0; $totalLate = 0; $totalSchoolDays = 0;
        foreach ($months as $month) {
            $monthAttendances = $attendance->filter(function($a) use ($month) {
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
            $totalPresent += $present;
            $totalAbsent += $absent;
            $totalLate += $late;
            $totalSchoolDays += $schoolDays;
        }
    @endphp
    <table class="sf9-table" style="font-size: 8.5pt;">
        <thead>
            <tr>
                <th class="text-left pl-2" style="width: 20%;">Month</th>
                @foreach($months as $m)
                    <th style="width: 6%;">{{ $m }}</th>
                @endforeach
                <th style="width: 8%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-left font-bold pl-2">No. of School Days</td>
                @foreach($months as $m)
                    <td>{{ $attendanceData[$m]['days'] }}</td>
                @endforeach
                <td class="font-bold">{{ $totalSchoolDays > 0 ? $totalSchoolDays : '' }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td class="text-left font-bold pl-2">No. of Days Present</td>
                @foreach($months as $m)
                    <td>{{ $attendanceData[$m]['present'] }}</td>
                @endforeach
                <td class="font-bold">{{ $totalPresent > 0 ? $totalPresent : '' }}</td>
            </tr>
            <tr>
                <td class="text-left font-bold pl-2">No. of Days Absent</td>
                @foreach($months as $m)
                    <td>{{ $attendanceData[$m]['absent'] }}</td>
                @endforeach
                <td class="font-bold">{{ $totalAbsent > 0 ? $totalAbsent : '' }}</td>
            </tr>
            <tr style="background: #f9fafb;">
                <td class="text-left font-bold pl-2">No. of Times Tardy</td>
                @foreach($months as $m)
                    <td>{{ $attendanceData[$m]['late'] }}</td>
                @endforeach
                <td class="font-bold">{{ $totalLate > 0 ? $totalLate : '' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Parent Signature -->
    <div class="border border-black p-2 mt-3">
        <p class="text-xs font-bold mb-2">PARENT/GUARDIAN'S SIGNATURE</p>
        <div class="grid grid-cols-4 gap-2">
            @foreach(['1st Quarter','2nd Quarter','3rd Quarter','4th Quarter'] as $q)
                <div class="border border-black p-2 text-center">
                    <p class="text-[8px] font-bold mb-1">{{ $q }}</p>
                    <div class="border-b border-black h-6 mb-1"></div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Certification -->
    <div class="border border-black p-3 mt-3">
        <p class="text-xs leading-relaxed">
            This certifies that <strong class="underline">{{ $user->first_name ?? '' }} {{ $user->middle_name ?? '' }} {{ $user->last_name ?? '' }}</strong>
            of <strong>{{ $section?->name ?? '' }}</strong> has completed the curriculum for the school year.
        </p>
        <div class="mt-4 flex justify-between">
            <div class="text-center">
                <div class="border-t border-black pt-1 w-48">
                    <p class="font-bold uppercase text-xs">{{ $adviserName ?? '_________________' }}</p>
                    <p class="text-xs">Teacher</p>
                </div>
            </div>
            <div class="text-center">
                <div class="border-t border-black pt-1 w-48">
                    <p class="font-bold uppercase text-xs">{{ $schoolHead ?? '_________________' }}</p>
                    <p class="text-xs">School Head</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3 pt-2 border-t border-black text-[8px] text-center">
        <span>DepEd School Form 9 | Date Generated: {{ now()->format('F d, Y') }}</span>
    </div>
    </div>
</div>
@else
    <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center">
        <p class="text-amber-800 font-medium">Please select a student to view the report card.</p>
    </div>
@endif
