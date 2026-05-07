<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - {{ $section->grade_level }} {{ $section->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 font-sans text-slate-800" x-data="{ mobileOpen: false }">

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

<div class="flex min-h-screen">
    
    <!-- Sidebar -->
    @include('teacher.includes.sidebar')

    <!-- Main Content -->
    <div class="flex-1 lg:ml-72 transition-all duration-300">
        
        <!-- Top Navigation -->
        <header class="glass-effect sticky top-0 z-30 border-b border-slate-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 hover:bg-slate-100 rounded-lg transition-colors">
                        <i class="fas fa-bars text-slate-600"></i>
                    </button>
                    <div class="relative hidden md:block">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" placeholder="Search students..." 
                            class="pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all w-64">
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    @include('components.notification-bell')
                    <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-slate-800">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</p>
                            <p class="text-xs text-slate-500">
                                @if($section->grade_level <= 3)
                                    Primary Grade Teacher
                                @elseif($section->grade_level <= 6)
                                    Intermediate Grade Teacher
                                @else
                                    Grade {{ $section->grade_level }} Adviser
                                @endif
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-sm">
                            {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}{{ strtoupper(substr(auth()->user()->last_name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-6 max-w-7xl mx-auto">
            
            <!-- Breadcrumb & Header -->
            <div class="mb-8 animate-fade-in">
                <nav class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                    <a href="{{ route('teacher.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('teacher.sections.index') }}" class="hover:text-indigo-600 transition-colors">My Sections</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-slate-800 font-medium">Grade {{ $section->grade_level }} - {{ $section->name }}</span>
                </nav>
                
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-3xl font-bold text-slate-900">
                                Grade {{ $section->grade_level }} - {{ $section->name }}
                            </h1>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full uppercase tracking-wide">
                                Elementary
                            </span>
                        </div>
                        <p class="text-slate-500 flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-indigo-500"></i>
                            Adviser: <span class="font-medium text-slate-700">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                            <span class="text-slate-300">|</span>
                            <i class="fas fa-school text-indigo-500"></i>
                            School Year: {{ $section->schoolYear->name ?? ' ' }}
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-700 font-medium hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                       
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-fade-in">
                
                <!-- Toolbar -->
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-bold text-slate-800">Class Roster</h2>
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full border border-indigo-100">
                            Grade {{ $section->grade_level }}
                        </span>
                        @if($section->max_capacity)
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs rounded-full">
                                Capacity: {{ $students->total() }}/{{ $section->max_capacity }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" id="searchInput" placeholder="Find student..." 
                                class="pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all w-full sm:w-48">
                        </div>
                        
                        <select id="genderFilter" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                        
                        <select id="statusFilter" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Status</option>
                            <option value="active">Enrolled</option>
                            <option value="dropped">Dropped</option>
                            <option value="transferred">Transferred</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-sm" id="studentsTable">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-left font-semibold text-slate-700 w-16">
                                    <input type="checkbox" id="selectAll" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Learner's Name</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">LRN</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Gender/Age</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Parent/Guardian</th>
                                <th class="px-6 py-4 text-left font-semibold text-slate-700">Status</th>
                                <th class="px-6 py-4 text-right font-semibold text-slate-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($students as $index => $student)
                                @php
                                    $user = $student->user;
                                    $age = $user->birthdate ? \Carbon\Carbon::parse($user->birthdate)->age : 'N/A';
                                    $guardian = $student->guardian_name ?? $user->parent_name ?? 'N/A';
                                    $contact = $student->guardian_contact ?? $user->parent_contact ?? 'N/A';
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group student-row" 
                                    data-gender="{{ strtolower($user->gender ?? '') }}" 
                                    data-status="{{ strtolower($student->enrollment_status ?? 'active') }}">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="student-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" value="{{ $student->id }}">
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                @if($user->gender == 'female')
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-400 to-rose-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                    </div>
                                                @else
                                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                @if($student->enrollment_status == 'active')
                                                    <span class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full" title="Active"></span>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                                    {{ $user->last_name }}, {{ $user->first_name }} {{ $user->middle_name ? substr($user->middle_name, 0, 1) . '.' : '' }}
                                                </p>
                                                <p class="text-xs text-slate-500">
                                                    @if($section->grade_level == 'K')
                                                        Kindergarten
                                                    @else
                                                        Grade {{ $section->grade_level }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span class="font-mono text-xs bg-slate-100 px-3 py-1.5 rounded-lg text-slate-600 font-medium tracking-wide">
                                            {{ $student->lrn ?? $user->lrn ?? 'Not Assigned' }}
                                        </span>
                                    </td>
<td class="px-6 py-4">
    @php
        $age = $student->birthdate 
            ? \Carbon\Carbon::parse($student->birthdate)->age 
            : null;
    @endphp

    <div class="flex flex-col gap-1">
        <span class="text-slate-700 text-sm capitalize">
            @if($student->gender === 'male')
                <i class="fas fa-mars text-blue-400 mr-1"></i>
            @elseif($student->gender === 'female')
                <i class="fas fa-venus text-pink-400 mr-1"></i>
            @endif

            {{ ucfirst($student->gender ?? 'N/A') }}
        </span>

        <span class="text-slate-500 text-xs">
            {{ $age ? $age . ' years old' : 'N/A' }}
        </span>
    </div>
</td>

                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <span class="text-slate-700 text-sm font-medium">
                                                {{ $guardian }}
                                            </span>
                                            @if($contact != 'N/A')
                                                <span class="text-slate-500 text-xs flex items-center gap-1">
                                                    <i class="fas fa-phone text-slate-400 text-xs"></i>
                                                    {{ $contact }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                   <td class="px-6 py-4">
    @php
        $status = strtolower($student->enrollment->status ?? 'pending');

        $statusClasses = [
            'pending' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
            'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
            'enrolled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'rejected' => 'bg-red-50 text-red-700 border-red-200',
        ];

        $statusClass = $statusClasses[$status] ?? $statusClasses['pending'];
    @endphp

    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium border {{ $statusClass }}">
        <span class="w-1.5 h-1.5 rounded-full 
            {{ $status == 'enrolled' ? 'bg-emerald-500' : 
               ($status == 'rejected' ? 'bg-red-500' : 
               ($status == 'approved' ? 'bg-blue-500' : 'bg-yellow-500')) }}">
        </span>

        {{ ucfirst($student->enrollment->status ?? 'Pending') }}
    </span>
</td>

                                  <td class="px-6 py-4 text-right">
    <div class="flex items-center justify-end gap-1">
        <!-- Message -->
        <a href="{{ route('teacher.messenger', ['contact' => $student->user_id]) }}" 
           class="p-2 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors" 
           title="Send Message">
            <i class="fas fa-comment-dots"></i>
        </a>

        <!-- View Profile -->
        <a href="{{ route('teacher.students.show', $student->id) }}" 
           class="p-2 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors" 
           title="View Profile">
            <i class="fas fa-eye"></i>
        </a>

      
    </div>
</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-users text-3xl text-slate-300"></i>
                                            </div>
                                            <p class="text-lg font-medium text-slate-600 mb-1">No students enrolled</p>
                                            <p class="text-sm mb-4">This section currently has no students</p>
                                            <a href="{{ route('teacher.students.create', $section->id) }}" 
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors">
                                                <i class="fas fa-user-plus mr-2"></i>Enroll First Student
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-5 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        Showing <span class="font-medium text-slate-900">{{ $students->firstItem() ?? 0 }}</span> 
                        to <span class="font-medium text-slate-900">{{ $students->lastItem() ?? 0 }}</span> 
                        of <span class="font-medium text-slate-900">{{ $students->total() }}</span> learners
                    </p>
                    
                    @if($students->hasPages())
                        <div class="flex items-center gap-2">
                            {{ $students->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6 animate-fade-in">
                 <a href="{{ route('teacher.sections.attendance', $section) }}" 
                    class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-white relative overflow-hidden group cursor-pointer hover:shadow-xl transition-all block">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4 backdrop-blur-sm">
                            <i class="fas fa-clipboard-check text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold mb-1">Daily Attendance</h3>
                        <p class="text-indigo-100 text-sm mb-4">Mark attendance for Grade {{ $section->grade_level }}</p>
                        <span class="inline-flex items-center gap-2 text-sm font-medium">
                            Take Attendance <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </a>

            <a href="{{ route('teacher.sections.grades', $section) }}" 
                    class="bg-white rounded-2xl p-6 border border-slate-200 relative overflow-hidden group cursor-pointer hover:shadow-xl transition-all hover:border-amber-200 block">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100 rounded-full -mr-10 -mt-10 blur-2xl opacity-50 group-hover:scale-110 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4 text-amber-600">
                            <i class="fas fa-graduation-cap text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Grades & Records</h3>
                        <p class="text-slate-500 text-sm mb-4">Input grades and view academic records</p>
                        <span class="inline-flex items-center gap-2 text-sm font-medium text-amber-600">
                            View Grades <i class="fas fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </div>
                </a>


            </div>
        </main>
    </div>
</div>

<script>
    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(cb => cb.checked = e.target.checked);
    });

    // Search Functionality
    document.getElementById('searchInput')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Gender Filter
    document.getElementById('genderFilter')?.addEventListener('change', function(e) {
        filterTable();
    });

    // Status Filter
    document.getElementById('statusFilter')?.addEventListener('change', function(e) {
        filterTable();
    });

    function filterTable() {
        const genderFilter = document.getElementById('genderFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const gender = row.getAttribute('data-gender');
            const status = row.getAttribute('data-status');
            
            const genderMatch = !genderFilter || gender === genderFilter;
            const statusMatch = !statusFilter || status === statusFilter;
            
            row.style.display = (genderMatch && statusMatch) ? '' : 'none';
        });
    }
</script>

</body>
</html>