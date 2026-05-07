<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Form 7 (SF7) - School Personnel Assignment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        
        .sf7-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 9px;
        }
        .sf7-table th, .sf7-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
            vertical-align: middle;
        }
        .sf7-table th {
            background-color: #e5e7eb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 8px;
        }
        .sf7-header {
            background-color: #1e3a8a;
            color: white;
            font-weight: bold;
            font-size: 11px;
            text-align: center;
            padding: 8px;
            border: 1px solid #000;
        }
        .section-header {
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: left;
            padding-left: 10px;
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
            @page { size: letter portrait; margin: 0.3in 0.2in 0.3in 0.2in; }
            aside, nav[class*="w-72"], div[class*="w-72"], .sidebar, #sidebar, [class*="sidebar"], .no-print { display: none !important; }
            .ml-72, [class*="ml-72"] { margin-left: 0 !important; padding-left: 0 !important; width: 100% !important; }
            body { background: white; font-size: 9pt; }
            .sf7-container { box-shadow: none; margin: 0; padding: 0; max-width: 100% !important; width: 100% !important; }
            .sf7-table { font-size: 8pt; width: 100%; }
        }
        .data-cell { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
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
                <h1 class="text-2xl font-bold text-slate-800">School Form 7 (SF7)</h1>
                <p class="text-slate-500">School Personnel Assignment List and Basic Profile</p>
            </div>
            <div class="flex gap-3">
                <div class="px-4 py-2 rounded-lg bg-indigo-50 border border-indigo-200 text-indigo-700 text-sm font-medium">
                    <i class="fas fa-id-card mr-2"></i>SY {{ $activeSchoolYear?->name ?? now()->format('Y') }}
                </div>
            </div>
        </div>

        <!-- Controls Panel -->
        <div class="no-print mb-4 bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <form method="GET" action="{{ route('teacher.sf7') }}" class="flex items-end gap-4">
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
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                    Load Profile
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="no-print mb-4 bg-emerald-50 border border-emerald-200 rounded-lg p-4 text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if(!$teacherProfile)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-4">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">No teacher profile found for this section.</p>
            </div>
        @endif

        @if($teacherProfile)
<div class="overflow-x-auto pb-4">`n        <div class="sf7-container bg-white p-4 rounded-xl shadow-lg border border-slate-200 max-w-[1200px] mx-auto">
            
            <!-- School Header -->
            <div class="grid grid-cols-3 gap-3 mb-3 text-xs">
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
            </div>

            <!-- SF7 Title -->
            <div class="sf7-header mb-0">
                SCHOOL FORM 7 (SF7) SCHOOL PERSONNEL ASSIGNMENT LIST AND BASIC PROFILE<br>
                <span class="text-[9px] font-normal">(This replaces Form 12, Form 19, Form 29 & Form 31)</span>
            </div>

            <!-- Section A: Personal Info -->
            <table class="sf7-table mt-2">
                <thead><tr><th colspan="6" class="section-header">A. PERSONAL INFORMATION</th></tr></thead>
                <tbody>
                    <tr>
                        <td class="text-left font-semibold w-20">Employee No.:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['employee_no'] }}</td>
                        <td class="text-left font-semibold w-16">TIN:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['tin'] }}</td>
                        <td class="text-left font-semibold w-16">Sex:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['sex'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">Name:</td>
                        <td colspan="3" class="text-left font-bold uppercase text-[10px]">
                            {{ $teacherProfile['last_name'] }}, {{ $teacherProfile['first_name'] }} {{ $teacherProfile['middle_name'] }} {{ $teacherProfile['name_extension'] }}
                        </td>
                        <td class="text-left font-semibold">Birthdate:</td>
                        <td class="text-[10px]">{{ $teacherProfile['birthdate'] }} (Age: {{ $teacherProfile['age'] }})</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">Position:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['position'] }}</td>
                        <td class="text-left font-semibold">Nature:</td>
                        <td class="text-[10px]">{{ $teacherProfile['nature_of_appointment'] }}</td>
                        <td class="text-left font-semibold">Fund Source:</td>
                        <td class="text-[10px]">{{ $teacherProfile['fund_source'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">Date of Appointment:</td>
                        <td class="text-[10px]">{{ $teacherProfile['date_of_appointment'] }}</td>
                        <td class="text-left font-semibold">Years in Service:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['years_in_service'] }}</td>
                        <td class="text-left font-semibold">Contact:</td>
                        <td class="text-[10px]">{{ $teacherProfile['contact_no'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Section B: Educational Qualification -->
            <table class="sf7-table mt-2">
                <thead><tr><th colspan="4" class="section-header">B. EDUCATIONAL QUALIFICATION</th></tr></thead>
                <tbody>
                    <tr>
                        <td class="text-left font-semibold w-32">Highest Degree:</td>
                        <td colspan="3" class="text-left font-bold text-[10px]">{{ $teacherProfile['highest_degree'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">Major:</td>
                        <td class="text-left text-[10px] w-1/3">{{ $teacherProfile['major'] }}</td>
                        <td class="text-left font-semibold w-20">Minor:</td>
                        <td class="text-left text-[10px]">{{ $teacherProfile['minor'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">PRC License No.:</td>
                        <td class="text-[10px]">{{ $teacherProfile['prc_license_no'] }}</td>
                        <td class="text-left font-semibold">Validity:</td>
                        <td class="text-[10px]">{{ $teacherProfile['prc_validity'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Section C: Assignment Info -->
            <table class="sf7-table mt-2">
                <thead><tr><th colspan="6" class="section-header">C. ASSIGNMENT INFORMATION</th></tr></thead>
                <tbody>
                    <tr>
                        <td class="text-left font-semibold w-24">Grade Level:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['grade_level'] }}</td>
                        <td class="text-left font-semibold w-20">Section:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['section'] }}</td>
                        <td class="text-left font-semibold w-24">Advisory:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['advisory_class'] }}</td>
                    </tr>
                    <tr>
                        <td class="text-left font-semibold">Total Students:</td>
                        <td class="font-bold text-[10px]">{{ $teacherProfile['total_students'] }}</td>
                        <td class="text-left font-semibold">Male:</td>
                        <td class="text-[10px]">{{ $teacherProfile['male_students'] }}</td>
                        <td class="text-left font-semibold">Female:</td>
                        <td class="text-[10px]">{{ $teacherProfile['female_students'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Section D: REAL Subjects from Database -->
            <table class="sf7-table mt-2">
                <thead>
                    <tr><th colspan="3" class="section-header">D. SUBJECTS TAUGHT (Based on Grade Level Curriculum)</th></tr>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 60%;">Subject Name</th>
                        <th style="width: 35%;">Subject Code</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $index => $subject)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left pl-2">{{ $subject->name }}</td>
                        <td>{{ $subject->code }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-2 text-slate-400">No subjects found for this grade level</td>
                    </tr>
                    @endforelse
                    <tr>
                        <td colspan="3" class="text-left font-semibold bg-gray-50">
                            Ancillary Assignments: <span class="font-normal">{{ $teacherProfile['ancillary_assignments'] }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Section E: Teaching Program (Editable) -->
            <table class="sf7-table mt-2">
                <thead>
                    <tr>
                        <th colspan="6" class="section-header flex justify-between items-center">
                            <span>E. DAILY TEACHING PROGRAM</span>
                            <button onclick="openAddModal()" class="no-print bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                <i class="fas fa-plus mr-1"></i>Add Schedule
                            </button>
                        </th>
                    </tr>
                    <tr>
                        <th>Day</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Subject/Activity</th>
                        <th>Minutes</th>
                        <th class="no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalMinutes = 0; @endphp
                    @forelse($teachingPrograms as $program)
                    @php $totalMinutes += $program->minutes; @endphp
                    <tr>
                       <td>
    @switch($program->day)
        @case('M') Monday @break
        @case('T') Tuesday @break
        @case('W') Wednesday @break
        @case('TH') Thursday @break
        @case('F') Friday @break
        @default {{ $program->day }}
    @endswitch
</td>
                        <td>{{ \Carbon\Carbon::parse($program->time_from)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($program->time_to)->format('h:i A') }}</td>
                        <td class="text-left pl-2">{{ $program->subject ?? $program->activity ?? 'Teaching' }}</td>
                        <td>{{ $program->minutes }}</td>
                        <td class="no-print">
                            <button onclick="openEditModal({{ $program->id }}, '{{ $program->day }}', '{{ $program->time_from }}', '{{ $program->time_to }}', '{{ $program->subject }}', '{{ $program->activity }}')" class="text-blue-600 hover:text-blue-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('teacher.sf7.program.delete', $program) }}" method="POST" class="inline" onsubmit="return confirm('Delete this schedule?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-slate-400">
                            No teaching schedule added yet.<br>
                            <button onclick="openAddModal()" class="no-print mt-2 text-indigo-600 hover:text-indigo-800 text-xs">
                                <i class="fas fa-plus mr-1"></i>Add your first schedule
                            </button>
                        </td>
                    </tr>
                    @endforelse
                    @if($teachingPrograms->isNotEmpty())
                    <tr class="font-bold bg-gray-50">
                        <td colspan="4" class="text-right pr-2">Total Teaching Minutes per Week:</td>
                        <td>{{ $totalMinutes }}</td>
                        <td class="no-print"></td>
                    </tr>
                    <tr class="font-bold bg-gray-50">
                        <td colspan="4" class="text-right pr-2">Average Minutes per Day:</td>
                        <td>{{ round($totalMinutes / 5) }}</td>
                        <td class="no-print"></td>
                    </tr>
                    @endif
                </tbody>
            </table>

            <!-- Remarks -->
            <table class="sf7-table mt-2">
                <tbody>
                    <tr>
                        <td class="text-left font-semibold w-20">Remarks:</td>
                        <td class="text-left text-[10px] h-12 align-top">{{ $teacherProfile['remarks'] }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Guidelines -->
            <div class="mt-3 text-[8px] space-y-1 leading-tight border-t-2 border-black pt-2">
                <p class="font-bold">GUIDELINES:</p>
                <p>1. This form shall be accomplished at the beginning of the school year.</p>
                <p>2. All school personnel should be included, listed from highest rank to lowest.</p>
                <p>3. Daily Program is for teaching personnel only - add your schedule using the button above.</p>
            </div>

            <!-- Signatures -->
            <div class="mt-4 grid grid-cols-2 gap-8 text-xs px-6">
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Prepared by:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $teacherProfile['full_name'] }}</p>
                        <p class="text-center text-[9px] mt-0.5">(Signature over Printed Name)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
                <div class="text-center">
                    <p class="font-semibold mb-4 text-[10px] text-left">Certified Correct:</p>
                    <div class="mt-6 border-t border-black pt-1">
                        <p class="text-center font-bold uppercase text-xs">{{ $schoolHead }}</p>
                        <p class="text-center text-[9px] mt-0.5">(School Head)</p>
                    </div>
                    <p class="text-center text-[9px] mt-2">Date: ___________________</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-2 border-t border-slate-300 text-[9px] text-slate-500 flex justify-between">
                <span>School Form 7: Page 1 of 1</span>
                <span>Generated: {{ now()->format('F d, Y h:i A') }}</span>
            </div>

        </div>
        @endif

    </div>

    <!-- Add/Edit Modal -->
    <div id="programModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 no-print">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 id="modalTitle" class="text-lg font-bold mb-4">Add Teaching Schedule</h3>
            <form id="programForm" method="POST" action="{{ route('teacher.sf7.program.store') }}">
                @csrf
                <input type="hidden" name="section_id" value="{{ $selectedSection?->id }}">
                <input type="hidden" id="programId" name="_method" value="POST">
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Day</label>
                        <select name="day" id="day" required class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                            <option value="M">Monday</option>
                            <option value="T">Tuesday</option>
                            <option value="W">Wednesday</option>
                            <option value="TH">Thursday</option>
                            <option value="F">Friday</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">From</label>
                            <input type="time" name="time_from" id="time_from" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">To</label>
                            <input type="time" name="time_to" id="time_to" required class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Subject</label>
                        <select name="subject" id="subject" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                            <option value="">-- Select Subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                            @endforeach
                            <option value="Flag Ceremony">Flag Ceremony</option>
                            <option value="Recess">Recess</option>
                            <option value="Lunch Break">Lunch Break</option>
                            <option value="Homeroom">Homeroom</option>
                            <option value="Club Time">Club Time</option>
                            <option value="Other">Other Activity</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Activity (if not subject)</label>
                        <input type="text" name="activity" id="activity" placeholder="e.g., Faculty Meeting, Planning" class="w-full px-3 py-2 border border-slate-300 rounded-lg">
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-slate-600 hover:text-slate-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>

    @if($teacherProfile)
    <button onclick="window.print()" class="no-print print-btn bg-indigo-600 text-white hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/40">
        <i class="fas fa-print text-xl"></i>
    </button>
    @endif

    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Teaching Schedule';
            document.getElementById('programForm').action = '{{ route("teacher.sf7.program.store") }}';
            document.getElementById('programId').name = '_method';
            document.getElementById('programId').value = 'POST';
            
            // Reset form
            document.getElementById('day').value = 'M';
            document.getElementById('time_from').value = '';
            document.getElementById('time_to').value = '';
            document.getElementById('subject').value = '';
            document.getElementById('activity').value = '';
            
            document.getElementById('programModal').classList.remove('hidden');
            document.getElementById('programModal').classList.add('flex');
        }
        
        function openEditModal(id, day, timeFrom, timeTo, subject, activity) {
            document.getElementById('modalTitle').textContent = 'Edit Teaching Schedule';
            document.getElementById('programForm').action = '{{ url("teacher/sf7/program") }}/' + id;
            document.getElementById('programId').name = '_method';
            document.getElementById('programId').value = 'PUT';
            
            document.getElementById('day').value = day;
            document.getElementById('time_from').value = timeFrom.substring(0, 5);
            document.getElementById('time_to').value = timeTo.substring(0, 5);
            document.getElementById('subject').value = subject || '';
            document.getElementById('activity').value = activity || '';
            
            document.getElementById('programModal').classList.remove('hidden');
            document.getElementById('programModal').classList.add('flex');
        }
        
        function closeModal() {
            document.getElementById('programModal').classList.add('hidden');
            document.getElementById('programModal').classList.remove('flex');
        }
    </script>

</body>
</html>