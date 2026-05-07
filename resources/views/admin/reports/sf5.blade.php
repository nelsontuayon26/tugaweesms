<div class="overflow-x-auto pb-4">
    <div class="sf5-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1600px] mx-auto min-w-[1024px]">
    <style>
        .sf5-table { border-collapse: collapse; width: 100%; font-size: 8px; }
        .sf5-table th, .sf5-table td { border: 1px solid #000; padding: 2px 3px; text-align: center; vertical-align: middle; }
        .sf5-table th { background-color: #e5e7eb; font-weight: 600; text-transform: uppercase; font-size: 7px; }
        .sf5-header { background-color: #1e3a8a; color: white; font-weight: bold; font-size: 10px; text-align: center; padding: 6px; border: 1px solid #000; }
        .data-cell { max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .summary-table { border-collapse: collapse; width: 100%; font-size: 8px; }
        .summary-table th, .summary-table td { border: 1px solid #000; padding: 2px 4px; text-align: center; }
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
                <span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolRegion }}</span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Division:</span>
                <span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolDivision }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">District:</span>
                <span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolDistrict }}</span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Name:</span>
                <span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolName }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-24 text-[10px]">School Year:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $schoolYear }}</span>
            </div>
        </div>
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Grade Level:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->gradeLevel->name ?? '___________' }}</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="font-semibold w-20 text-[10px]">Section:</span>
                <span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->name ?? '___________' }}</span>
            </div>
        </div>
    </div>

    <!-- SF5 Title -->
    <div class="sf5-header mb-0">
        SCHOOL FORM 5 (SF5) REPORT ON LEARNING PROGRESS & ACHIEVEMENT<br>
        <span class="text-[9px] font-normal">(Revised to conform with the instructions of DepEd Order 8, s. 2015)</span>
    </div>

    <div class="flex gap-4">
        <!-- Main Student Table -->
        <div class="flex-1">
            <table class="sf5-table">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 3%;">NO.</th>
                        <th rowspan="2" style="width: 5%;">LRN</th>
                        <th rowspan="2" style="width: 20%;">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" style="width: 6%;">GENERAL AVERAGE</th>
                        <th rowspan="2" style="width: 8%;">ACTION TAKEN</th>
                        <th rowspan="2" style="width: 15%;">Did Not Meet Expectations of the ff. Learning Area/s</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- MALE Section -->
                    <tr>
                        <td colspan="6" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">MALE</td>
                    </tr>
                    @php
                        $maleEnrollments = $enrollments->filter(function($e) { $gender = strtoupper($e->student->gender ?? ''); return $gender == 'MALE' || $gender == 'M'; });
                        $maleCounter = 0; $malePromoted = 0; $maleConditional = 0; $maleRetained = 0; $maleGrades = [];
                    @endphp
                    @forelse($maleEnrollments as $enrollment)
                        @php
                            $maleCounter++;
                            $student = $enrollment->student;
                            if(!$student) continue;
                            $user = $student->user;
                            $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                            $grade = $grades->firstWhere('student_id', $student->id);
                            $generalAverage = $grade ? round(($grade->written_works_avg * 0.4) + ($grade->performance_tasks_avg * 0.6), 0) : '';
                            $actionTaken = '';
                            $failedSubjects = [];
                            if ($grade && $generalAverage !== '') {
                                $maleGrades[] = $generalAverage;
                                $subjectGrades = [$grade->filipino ?? 0, $grade->english ?? 0, $grade->mathematics ?? 0, $grade->science ?? 0, $grade->ap ?? 0, $grade->esp ?? 0, $grade->music ?? 0, $grade->arts ?? 0, $grade->pe ?? 0, $grade->health ?? 0, $grade->tle ?? 0];
                                $failedCount = 0;
                                $subjectNames = ['Filipino', 'English', 'Math', 'Science', 'AP', 'ESP', 'Music', 'Arts', 'PE', 'Health', 'TLE'];
                                foreach ($subjectGrades as $index => $sg) { if ($sg > 0 && $sg < 75) { $failedCount++; $failedSubjects[] = $subjectNames[$index]; } }
                                if ($failedCount == 0) { $actionTaken = 'PROMOTED'; $malePromoted++; } elseif ($failedCount <= 2) { $actionTaken = 'CONDITIONAL'; $maleConditional++; } else { $actionTaken = 'RETAINED'; $maleRetained++; }
                            }
                            $failedSubjectsStr = implode(', ', $failedSubjects);
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $maleCounter }}</td>
                            <td class="font-mono text-[8px]">{{ $student->lrn ?? '' }}</td>
                            <td class="text-left uppercase text-[8px] data-cell pl-2" title="{{ $fullName }}">{{ $fullName }}</td>
                            <td class="text-center font-bold text-[9px]">{{ $generalAverage }}</td>
                            <td class="text-center text-[8px] font-semibold">{{ $actionTaken }}</td>
                            <td class="text-left text-[7px] data-cell pl-1" title="{{ $failedSubjectsStr }}">{{ $failedSubjectsStr }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-2 text-slate-400 text-[8px]">No male students</td></tr>
                    @endforelse
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="text-right text-[9px] pr-2">TOTAL MALE</td>
                        <td class="text-center text-[9px]">{{ count($maleGrades) > 0 ? round(array_sum($maleGrades) / count($maleGrades), 0) : '' }}</td>
                        <td colspan="2"></td>
                    </tr>

                    <!-- FEMALE Section -->
                    <tr>
                        <td colspan="6" class="text-left font-bold bg-gray-100 text-[9px] uppercase pl-2">FEMALE</td>
                    </tr>
                    @php
                        $femaleEnrollments = $enrollments->filter(function($e) { $gender = strtoupper($e->student->gender ?? ''); return $gender == 'FEMALE' || $gender == 'F'; });
                        $femaleCounter = 0; $femalePromoted = 0; $femaleConditional = 0; $femaleRetained = 0; $femaleGrades = [];
                    @endphp
                    @forelse($femaleEnrollments as $enrollment)
                        @php
                            $femaleCounter++;
                            $student = $enrollment->student;
                            if(!$student) continue;
                            $user = $student->user;
                            $fullName = ($user->last_name ?? '') . ', ' . ($user->first_name ?? '') . ' ' . ($user->middle_name ?? '');
                            $grade = $grades->firstWhere('student_id', $student->id);
                            $generalAverage = $grade ? round(($grade->written_works_avg * 0.4) + ($grade->performance_tasks_avg * 0.6), 0) : '';
                            $actionTaken = '';
                            $failedSubjects = [];
                            if ($grade && $generalAverage !== '') {
                                $femaleGrades[] = $generalAverage;
                                $subjectGrades = [$grade->filipino ?? 0, $grade->english ?? 0, $grade->mathematics ?? 0, $grade->science ?? 0, $grade->ap ?? 0, $grade->esp ?? 0, $grade->music ?? 0, $grade->arts ?? 0, $grade->pe ?? 0, $grade->health ?? 0, $grade->tle ?? 0];
                                $failedCount = 0;
                                $subjectNames = ['Filipino', 'English', 'Math', 'Science', 'AP', 'ESP', 'Music', 'Arts', 'PE', 'Health', 'TLE'];
                                foreach ($subjectGrades as $index => $sg) { if ($sg > 0 && $sg < 75) { $failedCount++; $failedSubjects[] = $subjectNames[$index]; } }
                                if ($failedCount == 0) { $actionTaken = 'PROMOTED'; $femalePromoted++; } elseif ($failedCount <= 2) { $actionTaken = 'CONDITIONAL'; $femaleConditional++; } else { $actionTaken = 'RETAINED'; $femaleRetained++; }
                            }
                            $failedSubjectsStr = implode(', ', $failedSubjects);
                        @endphp
                        <tr>
                            <td class="text-center font-medium">{{ $maleCounter + $femaleCounter }}</td>
                            <td class="font-mono text-[8px]">{{ $student->lrn ?? '' }}</td>
                            <td class="text-left uppercase text-[8px] data-cell pl-2" title="{{ $fullName }}">{{ $fullName }}</td>
                            <td class="text-center font-bold text-[9px]">{{ $generalAverage }}</td>
                            <td class="text-center text-[8px] font-semibold">{{ $actionTaken }}</td>
                            <td class="text-left text-[7px] data-cell pl-1" title="{{ $failedSubjectsStr }}">{{ $failedSubjectsStr }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-2 text-slate-400 text-[8px]">No female students</td></tr>
                    @endforelse
                    <tr class="bg-gray-50 font-bold">
                        <td colspan="3" class="text-right text-[9px] pr-2">TOTAL FEMALE</td>
                        <td class="text-center text-[9px]">{{ count($femaleGrades) > 0 ? round(array_sum($femaleGrades) / count($femaleGrades), 0) : '' }}</td>
                        <td colspan="2"></td>
                    </tr>
                    <tr class="bg-gray-200 font-bold border-t-2 border-black">
                        <td colspan="3" class="text-right text-[9px] pr-2">COMBINED TOTAL</td>
                        <td class="text-center text-[9px] border-b-2 border-black">
                            @php $allGrades = array_merge($maleGrades, $femaleGrades); @endphp
                            {{ count($allGrades) > 0 ? round(array_sum($allGrades) / count($allGrades), 0) : '' }}
                        </td>
                        <td colspan="2" class="border-b-2 border-black"></td>
                    </tr>
                    @php $totalStudents = $maleCounter + $femaleCounter; $totalRows = max(30, $totalStudents + 5); @endphp
                    @for($i = $totalStudents; $i < $totalRows; $i++)
                        <tr style="height: 16px;">
                            <td class="text-center text-[8px]">{{ $i + 1 }}</td>
                            @for($j = 0; $j < 5; $j++)
                                <td></td>
                            @endfor
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Summary Table -->
        <div class="w-64">
            <table class="summary-table">
                <thead>
                    <tr><th colspan="4" class="bg-indigo-600 text-white text-[9px]">SUMMARY TABLE</th></tr>
                    <tr><th class="text-[8px]">STATUS</th><th class="text-[8px]">MALE</th><th class="text-[8px]">FEMALE</th><th class="text-[8px]">TOTAL</th></tr>
                </thead>
                <tbody>
                    <tr><td class="text-left text-[8px] pl-2">PROMOTED</td><td class="font-bold text-[9px]">{{ $malePromoted > 0 ? $malePromoted : '' }}</td><td class="font-bold text-[9px]">{{ $femalePromoted > 0 ? $femalePromoted : '' }}</td><td class="font-bold text-[9px]">{{ ($malePromoted + $femalePromoted) > 0 ? ($malePromoted + $femalePromoted) : '' }}</td></tr>
                    <tr><td class="text-left text-[8px] pl-2">*CONDITIONAL</td><td class="font-bold text-[9px]">{{ $maleConditional > 0 ? $maleConditional : '' }}</td><td class="font-bold text-[9px]">{{ $femaleConditional > 0 ? $femaleConditional : '' }}</td><td class="font-bold text-[9px]">{{ ($maleConditional + $femaleConditional) > 0 ? ($maleConditional + $femaleConditional) : '' }}</td></tr>
                    <tr><td class="text-left text-[8px] pl-2">RETAINED</td><td class="font-bold text-[9px]">{{ $maleRetained > 0 ? $maleRetained : '' }}</td><td class="font-bold text-[9px]">{{ $femaleRetained > 0 ? $femaleRetained : '' }}</td><td class="font-bold text-[9px]">{{ ($maleRetained + $femaleRetained) > 0 ? ($maleRetained + $femaleRetained) : '' }}</td></tr>
                    <tr class="bg-gray-100 font-bold"><td class="text-left text-[8px] pl-2">TOTAL</td><td class="text-[9px]">{{ $maleCounter > 0 ? $maleCounter : '' }}</td><td class="text-[9px]">{{ $femaleCounter > 0 ? $femaleCounter : '' }}</td><td class="text-[9px]">{{ $totalStudents > 0 ? $totalStudents : '' }}</td></tr>
                </tbody>
            </table>

            <table class="summary-table mt-2">
                <thead>
                    <tr><th colspan="4" class="bg-indigo-600 text-white text-[8px]">LEARNING PROGRESS AND ACHIEVEMENT</th></tr>
                    <tr><th class="text-[7px]">Descriptors & Grading Scale</th><th class="text-[7px]">MALE</th><th class="text-[7px]">FEMALE</th><th class="text-[7px]">TOTAL</th></tr>
                </thead>
                <tbody>
                    @php
                        $descriptors = [
                            ['Did Not Meet Expectations', 0, 74, 0, 0],
                            ['Fairly Satisfactory', 75, 79, 0, 0],
                            ['Satisfactory', 80, 84, 0, 0],
                            ['Very Satisfactory', 85, 89, 0, 0],
                            ['Outstanding', 90, 100, 0, 0]
                        ];
                        foreach ($maleGrades as $mg) {
                            foreach ($descriptors as &$d) { if ($mg >= $d[1] && $mg <= $d[2]) { $d[3]++; break; } }
                        }
                        foreach ($femaleGrades as $fg) {
                            foreach ($descriptors as &$d) { if ($fg >= $d[1] && $fg <= $d[2]) { $d[4]++; break; } }
                        }
                    @endphp
                    @foreach($descriptors as $desc)
                        <tr>
                            <td class="text-left text-[7px] pl-1">{{ $desc[0] }} ({{ $desc[1] }}-{{ $desc[2] }})</td>
                            <td class="font-bold text-[8px]">{{ $desc[3] > 0 ? $desc[3] : '' }}</td>
                            <td class="font-bold text-[8px]">{{ $desc[4] > 0 ? $desc[4] : '' }}</td>
                            <td class="font-bold text-[8px]">{{ ($desc[3] + $desc[4]) > 0 ? ($desc[3] + $desc[4]) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Certification Signatures -->
    <div class="mt-4 grid grid-cols-3 gap-4 text-xs">
        <div class="text-center">
            <p class="font-semibold mb-2 text-[9px] text-left">PREPARED BY:</p>
            <div class="mt-4 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs">{{ $adviserName }}</p>
                <p class="text-center text-[8px] mt-0.5">Class Adviser<br>(Name and Signature)</p>
            </div>
        </div>
        <div class="text-center">
            <p class="font-semibold mb-2 text-[9px] text-left">CERTIFIED CORRECT & SUBMITTED:</p>
            <div class="mt-4 border-t border-black pt-1">
                <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                <p class="text-center text-[8px] mt-0.5">School Head<br>(Name and Signature)</p>
            </div>
        </div>
        <div class="text-left text-[8px] space-y-0.5 leading-tight">
            <p class="font-bold">GUIDELINES:</p>
            <p>1. Do not include Dropouts and Transferred Out (D.O.4, 2014)</p>
            <p>2. To be prepared by the Adviser. Indicate General Average based on Form 138.</p>
            <p>3. Summary: PROMOTED (75+ all subjects), CONDITIONAL (≤2 failed), RETAINED (3+ failed).</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
        <span>School Form 5: Page ___ of ___</span>
        <span>Generated through LIS | Date Generated: {{ now()->format('F d, Y h:i A') }}</span>
    </div>
    </div>
</div>
