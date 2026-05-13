

{{-- resources/views/admin/students/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pupils | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        body {
            overflow-x: hidden;
        }

        /* Layout: Fixed Sidebar + Scrollable Content */
        .dashboard-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* Scrollable Main Area */
        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f8fafc;
        }

        /* Header - Fixed at top */
        .main-header {
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 32px;
            flex-shrink: 0;
            z-index: 40;
        }

        /* Content */
        .main-content {
            flex: 1;
            overflow-x: hidden;
            padding: 24px 32px;
        }

        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Table Styles */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .modern-table th {
            background: #f8fafc;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 0.75rem;
            color: #64748b;
            padding: 16px 24px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .modern-table td {
            padding: 16px 24px;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .modern-table tr {
            transition: all 0.2s ease;
        }
        
        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .status-inactive {
            background-color: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            opacity: 0;
        }
        
        .modern-table tbody tr:hover .action-btn {
            opacity: 1;
        }
        
        /* Always show action buttons on touch devices */
        @media (hover: none) and (pointer: coarse) {
            .action-btn {
                opacity: 1 !important;
            }
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Custom Checkbox */
        .custom-checkbox {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        
        .custom-checkbox:checked {
            background: #3b82f6;
            border-color: #3b82f6;
        }
        
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Animations */
        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        /* Glass Card */
        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        /* Search Input */
        .search-input {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
        }
        
        .search-input:focus-within {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Mobile Overlay */
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(4px);
        }

        /* Stats Cards */
        .stat-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #f1f5f9;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
        }

        .stat-pill i {
            color: #3b82f6;
        }

        /* Add to your style section */
#searchInput[maxlength="12"]:focus {
    color: #1e3a8a;
    font-weight: 500;
}

/* Floating Action Button */
.fab-student {
    position: fixed;
    bottom: 32px;
    right: 32px;
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    box-shadow: 0 10px 40px -10px rgba(37, 99, 235, 0.5);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 45;
    text-decoration: none;
}

.fab-student:hover {
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 20px 50px -10px rgba(37, 99, 235, 0.6);
}

/* Enhanced Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid transparent;
}

.status-badge.border {
    border-width: 1px;
}

/* Mobile */
@media (max-width: 1024px) {
    .fab-student {
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
    }
}
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         style="display: none;"></div>

    <div class="dashboard-layout">
        <!-- Sidebar -->
        @include('admin.includes.sidebar')

        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Fixed Header -->
            <header class="main-header">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2.5 hover:bg-slate-100 rounded-xl transition-colors">
                            <i class="fas fa-bars text-slate-600"></i>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Pupils Management</h2>
                            <p class="text-sm text-slate-500 font-medium flex items-center gap-2 mt-0.5">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                {{ ($schoolYear ?? $activeSchoolYear)?->name ?? 'No School Year Selected' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Stats Pills -->
                        <div class="hidden md:flex items-center gap-3">
                            <div class="stat-pill">
                                <i class="fas fa-users text-sm"></i>
                                <span>{{ $students->count() }} Total</span>
                            </div>
                            <div class="stat-pill">
                                <i class="fas fa-user-check text-sm"></i>
                                <span>{{ $students->where('status', 'active')->count() ?? $students->count() }} Active</span>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="search-input hidden md:flex">
                            <i class="fas fa-search text-slate-400"></i>
                          <input type="text" id="searchInput" placeholder="Search name, email, or LRN (12 digits)..." maxlength="12" class="bg-transparent border-none outline-none text-sm w-56 placeholder:text-slate-400">
                        </div>

                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="main-content">
                
                @php
                    $selectedYear = $schoolYear ?? $activeSchoolYear;
                    $allGrades = \App\Models\GradeLevel::whereHas('sections.enrollments', function($q) use ($selectedYear) {
                        $q->where('school_year_id', $selectedYear?->id);
                    })->orderBy('name')->pluck('name');

                    $allSections = \App\Models\Section::whereHas('enrollments', function($q) use ($selectedYear) {
                        $q->where('school_year_id', $selectedYear?->id);
                    })->orderBy('name')->pluck('name');
                @endphp
                <!-- Filters & Actions Bar -->
                <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in-up">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-calendar text-slate-400"></i>
                            <select class="bg-transparent border-none outline-none text-sm text-slate-700 cursor-pointer font-medium" onchange="updateFilter('school_year_id', this.value)">
                                @php $allSchoolYears = \App\Models\SchoolYear::orderBy('start_date', 'desc')->get(); @endphp
                                @foreach($allSchoolYears as $sy)
                                    <option value="{{ $sy->id }}" {{ ($schoolYear->id ?? $activeSchoolYear?->id) == $sy->id ? 'selected' : '' }}>{{ $sy->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-filter text-slate-400"></i>
                            <select class="bg-transparent border-none outline-none text-sm text-slate-700 cursor-pointer font-medium" onchange="updateFilter('grade', this.value)">
                                <option value="">All Grades</option>
                                @foreach($allGrades as $grade)
                                    <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
                            <i class="fas fa-layer-group text-slate-400"></i>
                            <select class="bg-transparent border-none outline-none text-sm text-slate-700 cursor-pointer font-medium" onchange="updateFilter('section', this.value)">
                                <option value="">All Sections</option>
                                @foreach($allSections as $section)
                                    <option value="{{ $section }}" {{ request('section') == $section ? 'selected' : '' }}>{{ $section }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(request('grade') || request('section'))
                            <a href="{{ route('admin.students.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium underline">
                                Clear Filters
                            </a>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <button onclick="exportData()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-xl transition-all flex items-center gap-2 bg-white shadow-sm">
                            <i class="fas fa-download"></i>
                            Export
                        </button>
                        <button onclick="printTable()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-xl transition-all flex items-center gap-2 bg-white shadow-sm">
                            <i class="fas fa-print"></i>
                            Print
                        </button>
                    </div>
                </div>

                <!-- Students Table Card -->
                <div class="glass-card overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                                <i class="fas fa-users text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Pupil List</h3>
                                <p class="text-sm text-slate-500">Showing all {{ $students->count() }} enrolled pupils</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Sort by:</span>
                            <select class="bg-white border border-slate-200 rounded-lg px-3 py-1.5 text-sm font-medium text-slate-700 outline-none focus:border-blue-500" onchange="updateFilter('sort', this.value)">
                                <option value="" {{ request('sort') == '' || request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="grade" {{ request('sort') == 'grade' ? 'selected' : '' }}>Grade Level</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="modern-table" id="studentsTable">
                            <thead>
    <tr>
        <th class="w-12">
            <input type="checkbox" class="custom-checkbox" id="selectAll" onclick="toggleSelectAll()">
        </th>
        <th>Pupil Information</th>
        <th>Grade & Section</th>
        <th>Status</th>
        <th>Contact</th>
        <th>Registration Date</th>
        <th class="text-right">Actions</th>
    </tr>
</thead>
                           <tbody>
    @php $currentSection = null; @endphp
    @forelse($students as $student)
    @php
        $enrollment = $student->enrollments->first();
        $sectionName = $enrollment?->section?->name ?? $student->section?->name ?? 'Unassigned';
        $gradeLevelName = $enrollment?->section?->gradeLevel?->name ?? $student->gradeLevel?->name ?? 'N/A';
    @endphp
    @if((!request('sort') || request('sort') === 'newest') && $currentSection !== $sectionName)
        <tr class="bg-slate-100 border-y border-slate-200">
            <td colspan="7" class="px-4 py-2">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-users text-xs"></i>
                    </div>
                    <span class="font-bold text-slate-700 text-sm">{{ $sectionName }}</span>
                    <span class="text-xs text-slate-500">— {{ $gradeLevelName }}</span>
                </div>
            </td>
        </tr>
        @php $currentSection = $sectionName; @endphp
    @endif
    <tr class="student-row"
        data-grade="{{ $gradeLevelName }}"
        data-section="{{ $sectionName }}"
        data-school-year="{{ $schoolYear->name ?? $activeSchoolYear?->name ?? '' }}"
        data-status="{{ $enrollment?->status ?? 'pending' }}"
        data-name="{{ strtolower($student->full_name) }}"
        data-lrn="{{ strtolower($student->lrn ?? '') }}"
        data-last-name="{{ $student->user->last_name ?? '' }}"
        data-first-name="{{ $student->user->first_name ?? '' }}"
        data-middle-name="{{ $student->user->middle_name ?? '' }}"
        data-gender="{{ $student->gender ?? '' }}"
        data-birthdate="{{ $student->birthdate ?? '' }}"
        data-mother-tongue="{{ $student->mother_tongue ?? '' }}"
        data-ethnicity="{{ $student->ethnicity ?? '' }}"
        data-religion="{{ $student->religion ?? '' }}"
        data-street="{{ $student->street_address ?? '' }}"
        data-barangay="{{ $student->barangay ?? '' }}"
        data-city="{{ $student->city ?? '' }}"
        data-province="{{ $student->province ?? '' }}"
        data-zip="{{ $student->zip_code ?? '' }}"
        data-father="{{ $student->father_name ?? '' }}"
        data-mother="{{ $student->mother_name ?? '' }}"
        data-guardian="{{ $student->guardian_name ?? '' }}"
        data-guardian-relationship="{{ $student->guardian_relationship ?? '' }}"
        data-guardian-contact="{{ $student->guardian_contact ?? '' }}"
        data-remarks="{{ $student->remarks ?? '' }}"
    >
        <td>
            <input type="checkbox" class="custom-checkbox student-checkbox" value="{{ $student->id }}" onchange="updateSelection()">
        </td>
        
        <!-- Student Information -->
        <td>
            <div class="flex items-center gap-3">
                <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=random&color=fff&size=128' }}"
                     class="w-12 h-12 rounded-full border-2 border-white shadow-sm object-cover">
                <div>
                    <p class="font-bold text-slate-900 text-sm">{{ $student->full_name }}</p>
                    <p class="text-xs text-slate-500">ID: {{ $student->id }} • LRN: {{ $student->lrn ?? 'N/A' }}</p>
                </div>
            </div>
        </td>
        
        <!-- Grade & Section -->
        <td>
            <div class="flex flex-col gap-1">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-sm font-semibold">
                    <i class="fas fa-graduation-cap text-xs"></i>
                    {{ $gradeLevelName }}
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    <i class="fas fa-door-open mr-1 text-slate-400"></i>
                    {{ $sectionName }}
                </span>
            </div>
        </td>
        
        <!-- Status (from enrollment) -->
        <td>
            @php
                $enrollment = $student->enrollments->first();
                $status = $enrollment?->status ?? 'pending';
                $statusClass = match($status) {
                    'enrolled' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'dropped', 'inactive' => 'bg-red-50 text-red-700 border-red-200',
                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                };
                $statusIcon = match($status) {
                    'enrolled' => 'fa-check-circle',
                    'pending' => 'fa-clock',
                    'dropped', 'inactive' => 'fa-ban',
                    default => 'fa-circle'
                };
            @endphp
            <span class="status-badge {{ $statusClass }} border capitalize">
                <i class="fas {{ $statusIcon }} mr-1.5 text-[10px]"></i>
                {{ $status }}
            </span>
        </td>
        
        <!-- Contact -->
        <td>
            <div class="flex flex-col gap-1">
                <span class="text-sm text-slate-700 font-medium flex items-center gap-2">
                    <i class="fas fa-envelope text-slate-400 text-xs"></i>
                    {{ $student->user->email ?? 'No email' }}
                </span>
                @if($student->guardian_contact)
                <span class="text-xs text-slate-500 flex items-center gap-2">
                    <i class="fas fa-phone text-slate-400"></i>
                    {{ $student->guardian_contact }}
                </span>
                @endif
            </div>
        </td>
        
        <!-- Registration Date -->
        <td>
            <div class="flex flex-col gap-1">
                <span class="text-sm font-medium text-slate-700">
                    {{ $student->created_at->format('M d, Y') }}
                </span>
                <span class="text-xs text-slate-500">
                    {{ $student->created_at->diffForHumans() }}
                </span>
            </div>
        </td>
        
        <!-- Actions -->
        <td class="text-right">
            <div class="flex items-center justify-end gap-1" x-data="{ idCardOpen: false }">
                <a href="{{ route('admin.students.show', $student) }}" class="action-btn text-blue-600 hover:bg-blue-50" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
                <button @click="idCardOpen = true" class="action-btn text-emerald-600 hover:bg-emerald-50" title="ID Card">
                    <i class="fas fa-id-card"></i>
                </button>
                <a href="{{ route('admin.students.edit', $student) }}" class="action-btn text-amber-600 hover:bg-amber-50" title="Edit">
                    <i class="fas fa-edit"></i>
                </a>
               

                <!-- ID Card Modal (teleported to body) -->
                <template x-teleport="body">
                    <div x-show="idCardOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="fixed inset-0 z-[9999]"
                         style="display: none;"
                         @keydown.escape.window="idCardOpen = false">
                        <div class="relative flex min-h-screen items-center justify-center p-4">
                            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="idCardOpen = false"></div>
                            <div x-show="idCardOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                                 class="relative w-full max-w-xl rounded-2xl bg-white shadow-2xl p-5"
                                 style="display: none;"
                                 @click.away="idCardOpen = false">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="text-lg font-bold text-slate-800">Student ID Card</h3>
                                    <div class="flex items-center gap-2">
                                        <button onclick="window.print()" class="inline-flex h-8 px-3 items-center justify-center rounded-full bg-blue-900 text-white hover:bg-blue-800 transition text-xs font-medium">
                                            <i class="fas fa-print mr-1"></i> Print
                                        </button>
                                        <button @click="idCardOpen = false" class="inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">                                        </button>
                                    </div>
                                </div>
                                @include('components.student-id-card', ['student' => $student, 'showPrint' => false])
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="7" class="text-center py-16">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-slash text-3xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-700 mb-2">No pupils found</h3>
            <p class="text-slate-500 mb-6">Get started by adding your first pupil</p>
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-500/30">
                <i class="fas fa-plus"></i>
                Add New Pupil
            </a>
        </td>
    </tr>
    @endforelse
</tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination / Footer -->
                    <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Showing</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $students->count() }}</span>
                            <span class="text-sm text-slate-500">pupils</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-lg transition-all bg-white shadow-sm disabled:opacity-50" disabled>
                                <i class="fas fa-chevron-left mr-1"></i>
                                Previous
                            </button>
                            <div class="flex items-center gap-1">
                                <button class="w-8 h-8 flex items-center justify-center text-sm font-semibold text-white bg-blue-600 rounded-lg">1</button>
                                <button class="w-8 h-8 flex items-center justify-center text-sm font-medium text-slate-600 hover:bg-white rounded-lg transition-all">2</button>
                                <button class="w-8 h-8 flex items-center justify-center text-sm font-medium text-slate-600 hover:bg-white rounded-lg transition-all">3</button>
                            </div>
                            <button class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-white border border-slate-200 rounded-lg transition-all bg-white shadow-sm">
                                Next
                                <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                    @php
                        $gradeDistribution = $students->groupBy('grade_level')->map->count();
                        $genderDistribution = $students->groupBy('gender')->map->count();
                    @endphp
                    
                    <div class="glass-card p-6 flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                            <i class="fas fa-male text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $genderDistribution['Male'] ?? 0 }}</p>
                            <p class="text-sm text-slate-500 font-medium">Male Pupils</p>
                        </div>
                    </div>
                    
                    <div class="glass-card p-6 flex items-center gap-4">
                        <div class="w-12 h-12 bg-pink-50 rounded-2xl flex items-center justify-center text-pink-600">
                            <i class="fas fa-female text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $genderDistribution['Female'] ?? 0 }}</p>
                            <p class="text-sm text-slate-500 font-medium">Female Pupils</p>
                        </div>
                    </div>
                    
                    <div class="glass-card p-6 flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                            <i class="fas fa-user-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $students->where('created_at', '>=', now()->subDays(7))->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">New This Week</p>
                        </div>
                    </div>
                    
                    <div class="glass-card p-6 flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600">
                            <i class="fas fa-graduation-cap text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-900">{{ $students->pluck('section')->unique()->count() }}</p>
                            <p class="text-sm text-slate-500 font-medium">Active Sections</p>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Bulk Actions Toolbar -->
    <div id="bulkActionsBar" class="fixed bottom-6 left-1/2 transform -translate-x-1/2 z-40 hidden">
        <div class="bg-slate-900 text-white px-5 py-3 rounded-2xl shadow-2xl flex items-center gap-4 animate-fade-in-up">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-xs font-bold" id="selectedCount">0</span>
                <span class="text-sm font-medium">selected</span>
            </div>
            <div class="w-px h-5 bg-slate-700"></div>
            <button onclick="exportSelected()" class="text-sm font-medium hover:text-blue-300 transition-colors flex items-center gap-1.5">
                <i class="fas fa-download text-xs"></i>
                Export
            </button>
            <button onclick="printSelected()" class="text-sm font-medium hover:text-blue-300 transition-colors flex items-center gap-1.5">
                <i class="fas fa-print text-xs"></i>
                Print
            </button>
            <button onclick="openBulkDeleteModal()" class="text-sm font-medium hover:text-red-300 transition-colors flex items-center gap-1.5">
                <i class="fas fa-trash-alt text-xs"></i>
                Delete
            </button>
            <button onclick="clearSelection()" class="text-sm text-slate-400 hover:text-white transition-colors ml-1">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div id="bulkDeleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeBulkDeleteModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl p-6 w-96">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Selected Pupils?</h3>
                <p class="text-sm text-slate-500">Are you sure you want to delete <span id="bulkDeleteCount" class="font-bold text-slate-700">0</span> selected pupil(s)? This action cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeBulkDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancel
                </button>
                <form id="bulkDeleteForm" method="POST" action="{{ route('admin.students.bulk-destroy') }}" class="flex-1">
                    @csrf
                    <input type="hidden" name="ids[]" id="bulkDeleteIds">
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-lg shadow-red-500/30">
                        Delete Selected
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl p-6 w-96">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Pupil?</h3>
                <p class="text-sm text-slate-500">Are you sure you want to delete this pupil? This action cannot be undone.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-lg shadow-red-500/30">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Search Functionality - Search by Name, Email, and LRN
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase().trim();
    const rows = document.querySelectorAll('.student-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const lrn = row.getAttribute('data-lrn') || '';
        const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
        
        if (name.includes(searchTerm) || email.includes(searchTerm) || lrn.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Search Functionality - Search by Name, Email, and LRN (12 digits only)
document.getElementById('searchInput')?.addEventListener('input', function(e) {
    let searchTerm = e.target.value.trim();
    const rows = document.querySelectorAll('.student-row');
    
    // If search term is numeric, limit to 12 digits (LRN format)
    if (/^\d+$/.test(searchTerm)) {
        searchTerm = searchTerm.substring(0, 12); // Limit to 12 digits
        e.target.value = searchTerm; // Update input to show limit
    }
    
    searchTerm = searchTerm.toLowerCase();
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name') || '';
        const lrn = row.getAttribute('data-lrn') || '';
        const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
        
        // For LRN search, only match if exactly 12 digits or partial match
        const lrnMatch = /^\d{1,12}$/.test(searchTerm) && lrn.includes(searchTerm);
        
        if (name.includes(searchTerm) || email.includes(searchTerm) || lrnMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

        // Filter by Grade
        function updateFilter(key, value) {
            const url = new URL(window.location.href);
            if (value) {
                url.searchParams.set(key, value);
            } else {
                url.searchParams.delete(key);
            }
            window.location.href = url.toString();
        }

        // Select All Checkbox
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateSelection();
        }

        // Track checkbox selections and show/hide bulk actions toolbar
        function updateSelection() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const toolbar = document.getElementById('bulkActionsBar');
            const countEl = document.getElementById('selectedCount');
            const count = checkboxes.length;
            
            countEl.textContent = count;
            
            if (count > 0) {
                toolbar.classList.remove('hidden');
            } else {
                toolbar.classList.add('hidden');
                document.getElementById('selectAll').checked = false;
            }
        }

        function getSelectedRows() {
            const checked = document.querySelectorAll('.student-checkbox:checked');
            if (checked.length === 0) {
                // If none selected, return all visible rows
                return Array.from(document.querySelectorAll('.student-row')).filter(r => r.style.display !== 'none');
            }
            return Array.from(checked).map(cb => cb.closest('.student-row'));
        }

        function clearSelection() {
            document.querySelectorAll('.student-checkbox').forEach(cb => cb.checked = false);
            document.getElementById('selectAll').checked = false;
            updateSelection();
        }

        // Sort Table
        function sortTable(sortBy) {
            const tbody = document.querySelector('#studentsTable tbody');
            const rows = Array.from(tbody.querySelectorAll('.student-row'));
            
            rows.sort((a, b) => {
                if (sortBy === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortBy === 'grade') {
                    return parseInt(a.getAttribute('data-grade') || 0) - parseInt(b.getAttribute('data-grade') || 0);
                } else {
                    // Newest first (default)
                    return 0; // Keep original order
                }
            });
            
            rows.forEach(row => tbody.appendChild(row));
        }

        // Delete Student
        function deleteStudent(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/admin/students/${id}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Bulk Delete
        function openBulkDeleteModal() {
            const checked = document.querySelectorAll('.student-checkbox:checked');
            if (checked.length === 0) return;
            
            document.getElementById('bulkDeleteCount').textContent = checked.length;
            
            // Create hidden inputs for each selected ID
            const ids = Array.from(checked).map(cb => cb.value);
            const form = document.getElementById('bulkDeleteForm');
            
            // Remove old hidden inputs
            form.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());
            
            // Add new hidden inputs
            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.getElementById('bulkDeleteModal').classList.remove('hidden');
        }

        function closeBulkDeleteModal() {
            document.getElementById('bulkDeleteModal').classList.add('hidden');
        }

        // Export Data (SF1 format via server)
        function exportData() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            if (checkboxes.length === 0) {
                // Export all visible rows if none selected
                const visibleRows = Array.from(document.querySelectorAll('.student-row')).filter(r => r.style.display !== 'none');
                const ids = visibleRows.map(row => row.querySelector('.student-checkbox').value);
                submitExportForm(ids);
            } else {
                exportSelected();
            }
        }

        function exportSelected() {
            const checkboxes = document.querySelectorAll('.student-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => cb.value);
            submitExportForm(ids);
        }

        function submitExportForm(ids) {
            if (ids.length === 0) {
                alert('No pupils to export.');
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.students.export-csv") }}';
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            ids.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        // Print Table
        function printTable() {
            printRows(getSelectedRows());
        }

        function printSelected() {
            printRows(getSelectedRows());
        }

        function printRows(rows) {
            let iframe = document.getElementById('printIframe');
            if (!iframe) {
                iframe = document.createElement('iframe');
                iframe.id = 'printIframe';
                iframe.style.position = 'absolute';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = '0';
                iframe.style.visibility = 'hidden';
                document.body.appendChild(iframe);
            }

            let html = '<' + 'html><' + 'head><' + 'title>Pupil List<' + '/title><' + 'style>' +
                '@page { size: landscape; margin: 10mm; }' +
                'body { font-family: Arial, sans-serif; padding: 10px; font-size: 10px; }' +
                'table { width: 100%; border-collapse: collapse; margin-top: 10px; }' +
                'th, td { border: 1px solid #000; padding: 4px 6px; text-align: left; vertical-align: top; }' +
                'th { background: #e5e7eb; font-weight: bold; font-size: 9px; text-transform: uppercase; }' +
                'h2 { margin: 0 0 5px 0; font-size: 14px; }' +
                '.meta { font-size: 10px; margin-bottom: 10px; }' +
                '<' + '/style><' + '/head><' + 'body>' +
                '<h2>School Form 1 (SF1) - School Register</h2>' +
                '<p class="meta">Generated on ' + new Date().toLocaleDateString() + '</p>' +
                '<table><thead><tr>' +
                '<th style="width:3%">No.</th>' +
                '<th style="width:8%">LRN</th>' +
                '<th style="width:10%">Name</th>' +
                '<th style="width:6%">School Year</th>' +
                '<th style="width:5%">Status</th>' +
                '<th style="width:8%">Grade & Section</th>' +
                '<th style="width:3%">Sex</th>' +
                '<th style="width:5%">Birth Date</th>' +
                '<th style="width:3%">Age</th>' +
                '<th style="width:5%">Mother Tongue</th>' +
                '<th style="width:5%">IP</th>' +
                '<th style="width:5%">Religion</th>' +
                '<th style="width:10%">Address</th>' +
                '<th style="width:8%">Father\'s Name</th>' +
                '<th style="width:8%">Mother\'s Name</th>' +
                '<th style="width:8%">Guardian\'s Name</th>' +
                '<th style="width:4%">Relationship</th>' +
                '<th style="width:5%">Contact</th>' +
                '<th style="width:5%">Remarks</th>' +
                '<' + '/tr><' + '/thead><' + 'tbody>';

            let no = 1;
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const d = row.dataset;
                    const gender = (d.gender || '').toUpperCase();
                    const sex = gender === 'MALE' || gender === 'M' ? 'M' : (gender === 'FEMALE' || gender === 'F' ? 'F' : '');
                    let birthDate = '';
                    if (d.birthdate) {
                        const bd = new Date(d.birthdate);
                        if (!isNaN(bd)) {
                            birthDate = (bd.getMonth() + 1).toString().padStart(2, '0') + '/' + bd.getDate().toString().padStart(2, '0') + '/' + bd.getFullYear();
                        }
                    }
                    let age = '';
                    if (d.birthdate) {
                        const bd = new Date(d.birthdate);
                        const today = new Date();
                        age = today.getFullYear() - bd.getFullYear();
                        const m = today.getMonth() - bd.getMonth();
                        if (m < 0 || (m === 0 && today.getDate() < bd.getDate())) age--;
                    }
                    const name = (d.lastName || '') + ', ' + (d.firstName || '') + ' ' + (d.middleName || '');
                    const addrParts = [d.street, d.barangay, d.city, (d.province || '') + (d.zip ? ' ' + d.zip : '')].filter(Boolean);
                    const address = addrParts.join(', ');

                    const gradeSection = (d.grade || '') + (d.grade && d.section ? ' - ' : '') + (d.section || '');

                    html += '<tr>' +
                        '<td>' + (no++) + '</td>' +
                        '<td>' + (d.lrn || '') + '</td>' +
                        '<td>' + name.trim() + '</td>' +
                        '<td>' + (d.schoolYear || '') + '</td>' +
                        '<td>' + (d.status || '') + '</td>' +
                        '<td>' + gradeSection + '</td>' +
                        '<td>' + sex + '</td>' +
                        '<td>' + birthDate + '</td>' +
                        '<td>' + age + '</td>' +
                        '<td>' + (d.motherTongue || '') + '</td>' +
                        '<td>' + (d.ethnicity || '') + '</td>' +
                        '<td>' + (d.religion || '') + '</td>' +
                        '<td>' + address + '</td>' +
                        '<td>' + (d.father || '') + '</td>' +
                        '<td>' + (d.mother || '') + '</td>' +
                        '<td>' + (d.guardian || '') + '</td>' +
                        '<td>' + (d.guardianRelationship || '') + '</td>' +
                        '<td>' + (d.guardianContact || '') + '</td>' +
                        '<td>' + (d.remarks || '') + '</td>' +
                        '<' + '/tr>';
                }
            });

            html += '<' + '/tbody><' + '/table><' + '/body><' + '/html>';

            const doc = iframe.contentDocument || iframe.contentWindow.document;
            doc.open();
            doc.write(html);
            doc.close();

            iframe.onload = function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            };
            if (doc.readyState === 'complete') {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('searchInput')?.focus();
            }
            if (e.key === 'Escape') {
                closeDeleteModal();
                closeBulkDeleteModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('deleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
        document.getElementById('bulkDeleteModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeBulkDeleteModal();
            }
        });
    </script>

    <!-- Floating Add Student Button -->
<a href="{{ route('admin.students.create') }}" class="fab-student">
    <i class="fas fa-plus text-xl"></i>
</a>


</body>
</html>