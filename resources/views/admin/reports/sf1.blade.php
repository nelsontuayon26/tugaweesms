<div class="sf1-report bg-white p-4 rounded-lg border border-gray-200 overflow-x-auto">
    @if($selectedSection)
        <div class="grid grid-cols-2 gap-3 mb-3 text-xs">
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">School ID:</span><span class="border-b border-black flex-1 px-1 font-mono text-[10px]">{{ $schoolId }}</span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">School Name:</span><span class="border-b border-black flex-1 px-1 uppercase font-bold text-[10px]">{{ $schoolName }}</span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">Division:</span><span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolDivision }}</span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-20 text-[10px]">Region:</span><span class="border-b border-black flex-1 px-1 uppercase text-[10px]">{{ $schoolRegion }}</span></div>
            </div>
            <div class="space-y-1">
                <div class="flex items-center gap-2"><span class="font-semibold w-24 text-[10px]">School Year:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $schoolYear }}</span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-24 text-[10px]">Grade Level:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->gradeLevel->name ?? '___________' }}</span></div>
                <div class="flex items-center gap-2"><span class="font-semibold w-24 text-[10px]">Section:</span><span class="border-b border-black flex-1 px-1 font-bold text-[10px]">{{ $selectedSection->name ?? '___________' }}</span></div>
            </div>
        </div>

        <div class="bg-blue-900 text-white font-bold text-xs text-center py-2 border border-black mb-0">
            SCHOOL FORM 1 (SF1) SCHOOL REGISTER
        </div>

        <table class="min-w-full border-collapse border border-black text-[8px]">
            <thead>
                <tr class="bg-gray-200">
                    <th rowspan="2" class="border border-black px-1 py-1">NO.</th>
                    <th rowspan="2" class="border border-black px-1 py-1">LRN</th>
                    <th colspan="3" class="border border-black px-1 py-1">NAME</th>
                    <th rowspan="2" class="border border-black px-1 py-1">SEX</th>
                    <th rowspan="2" class="border border-black px-1 py-1">BIRTH DATE</th>
                    <th rowspan="2" class="border border-black px-1 py-1">AGE</th>
                    <th rowspan="2" class="border border-black px-1 py-1">MOTHER TONGUE</th>
                    <th rowspan="2" class="border border-black px-1 py-1">IP</th>
                    <th rowspan="2" class="border border-black px-1 py-1">RELIGION</th>
                    <th colspan="4" class="border border-black px-1 py-1">ADDRESS</th>
                    <th rowspan="2" class="border border-black px-1 py-1">FATHER'S NAME</th>
                    <th rowspan="2" class="border border-black px-1 py-1">MOTHER'S MAIDEN NAME</th>
                    <th rowspan="2" class="border border-black px-1 py-1">GUARDIAN'S NAME</th>
                    <th rowspan="2" class="border border-black px-1 py-1">RELATIONSHIP</th>
                    <th rowspan="2" class="border border-black px-1 py-1">CONTACT</th>
                    <th rowspan="2" class="border border-black px-1 py-1">REMARKS</th>
                </tr>
                <tr class="bg-gray-200">
                    <th class="border border-black px-1 py-1">Last Name</th>
                    <th class="border border-black px-1 py-1">First Name</th>
                    <th class="border border-black px-1 py-1">Middle Name</th>
                    <th class="border border-black px-1 py-1">House/Street</th>
                    <th class="border border-black px-1 py-1">Barangay</th>
                    <th class="border border-black px-1 py-1">City</th>
                    <th class="border border-black px-1 py-1">Province</th>
                </tr>
            </thead>
            <tbody>
                <tr><td colspan="21" class="text-left font-bold bg-gray-100 uppercase pl-2 border border-black">MALE</td></tr>
                @php $maleCounter = 0; @endphp
                @forelse($enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['MALE','M'])) as $enrollment)
                    @php
                        $maleCounter++;
                        $student = $enrollment->student;
                        if(!$student) continue;
                        $user = $student->user;
                        $age = $student->getAttribute('calculated_age') ?? $student->calculated_age ?? '';
                    @endphp
                    <tr>
                        <td class="border border-black text-center">{{ $maleCounter }}</td>
                        <td class="border border-black font-mono">{{ $student->lrn ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->last_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->first_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->middle_name ?? '' }}</td>
                        <td class="border border-black text-center font-bold">M</td>
                        <td class="border border-black text-center">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') : '' }}</td>
                        <td class="border border-black text-center font-bold">{{ $age }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->mother_tongue ?? '' }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->ethnicity ?? '' }}</td>
                        <td class="border border-black text-center">{{ $student->religion ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->street_address ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->barangay ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->city ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->province ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->father_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->mother_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->guardian_name ?? '' }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->guardian_relationship ?? '' }}</td>
                        <td class="border border-black text-center">{{ $student->guardian_contact ?? '' }}</td>
                        <td class="border border-black text-center font-semibold">{{ $student->remarks ?? '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="21" class="border border-black text-center py-2 text-gray-400">No male students</td></tr>
                @endforelse

                <tr><td colspan="21" class="text-left font-bold bg-gray-100 uppercase pl-2 border border-black">FEMALE</td></tr>
                @php $femaleCounter = 0; @endphp
                @forelse($enrollments->filter(fn($e) => in_array(strtoupper($e->student->gender ?? ''), ['FEMALE','F'])) as $enrollment)
                    @php
                        $femaleCounter++;
                        $student = $enrollment->student;
                        if(!$student) continue;
                        $user = $student->user;
                        $age = $student->getAttribute('calculated_age') ?? $student->calculated_age ?? '';
                    @endphp
                    <tr>
                        <td class="border border-black text-center">{{ $femaleCounter }}</td>
                        <td class="border border-black font-mono">{{ $student->lrn ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->last_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->first_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $user->middle_name ?? '' }}</td>
                        <td class="border border-black text-center font-bold">F</td>
                        <td class="border border-black text-center">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('m/d/Y') : '' }}</td>
                        <td class="border border-black text-center font-bold">{{ $age }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->mother_tongue ?? '' }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->ethnicity ?? '' }}</td>
                        <td class="border border-black text-center">{{ $student->religion ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->street_address ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->barangay ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->city ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->province ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->father_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->mother_name ?? '' }}</td>
                        <td class="border border-black uppercase">{{ $student->guardian_name ?? '' }}</td>
                        <td class="border border-black text-center uppercase">{{ $student->guardian_relationship ?? '' }}</td>
                        <td class="border border-black text-center">{{ $student->guardian_contact ?? '' }}</td>
                        <td class="border border-black text-center font-semibold">{{ $student->remarks ?? '' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="21" class="border border-black text-center py-2 text-gray-400">No female students</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-2 text-[10px] font-semibold">Total Male: {{ $maleCount }} | Total Female: {{ $femaleCount }} | Grand Total: {{ $maleCount + $femaleCount }}</div>
    @else
        <div class="text-center py-8 text-gray-500">
            <p>Please select a section to generate SF1.</p>
        </div>
    @endif
</div>
