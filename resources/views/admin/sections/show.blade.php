<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Details | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            margin: 0; 
            padding: 0; 
            background: #f8fafc;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 32px;
            background: #f8fafc;
        }

        .main-content::-webkit-scrollbar { 
            width: 8px; 
        }
        .main-content::-webkit-scrollbar-track { 
            background: transparent; 
        }
        .main-content::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 4px; 
        }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .modern-table th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 20px 24px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .modern-table td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.03) 0%, transparent 100%);
            transform: scale(1.002);
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow-x: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            border-color: #cbd5e1;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            font-weight: 700;
            color: #065f46;
            font-size: 0.875rem;
        }

        .grade-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #93c5fd;
            border-radius: 10px;
            font-weight: 700;
            color: #1e40af;
            font-size: 0.8rem;
        }

        .adviser-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #f5f3ff 0%, #e9d5ff 100%);
            border: 1px solid #c4b5fd;
            border-radius: 10px;
            font-weight: 600;
            color: #5b21b6;
            font-size: 0.75rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .capacity-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow-x: hidden;
        }

        .capacity-fill {
            height: 100%;
            border-radius: 4px;
            transition: all 0.5s ease;
        }

        .capacity-fill.low { background: linear-gradient(90deg, #10b981, #34d399); }
        .capacity-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .capacity-fill.high { background: linear-gradient(90deg, #ef4444, #f87171); }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-enrolled {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-completed {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .status-dropped {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            color: #94a3b8;
        }

        .action-bar {
            display: flex;
            gap: 12px;
        }

        .action-btn-sm {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #94a3b8;
        }

        .action-btn-sm:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .school-year-banner {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            color: #92400e;
            font-size: 0.875rem;
        }

        .fixed {
            position: fixed;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

<div class="dashboard-container">
    @include('admin.includes.sidebar')

    <div class="main-wrapper">
        <div class="main-content">
            <div class="max-w-5xl mx-auto pb-8">
                
                <!-- Active School Year Banner -->
                @if(isset($activeSchoolYear) && $activeSchoolYear)
                    <div class="school-year-banner animate-fade-in">
                        <i class="fas fa-calendar-check text-amber-600"></i>
                        <span>Showing students for Active School Year: {{ $activeSchoolYear->name }}</span>
                    </div>
                @endif

                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-lg shadow-emerald-500/30">
                            {{ strtoupper(substr($section->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1 class="text-3xl font-bold text-slate-900">Section {{ $section->name }}</h1>
                                <span class="section-badge">
                                    <i class="fas fa-th-large text-xs"></i>
                                    Active
                                </span>
                            </div>
                            <p class="text-slate-500 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-sm"></i>
                                {{ $section->schoolYear->name ?? 'No School Year' }}
                            </p>
                        </div>
                    </div>
                </div>

                @php
                    // Use active_students from controller (filtered by active school year and enrolled status)
                    $studentCount = $section->active_students->count();
                    $capacity = $section->capacity ?? 40;
                    $percentage = $capacity > 0 ? min(100, ($studentCount / $capacity) * 100) : 0;
                    $statusClass = $percentage < 50 ? 'low' : ($percentage < 80 ? 'medium' : 'high');
                @endphp

                <!-- Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in stagger-1">
                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-emerald-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">{{ $studentCount }}</span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Active Students</p>
                        <div class="mt-3">
                            <div class="capacity-bar">
                                <div class="capacity-fill {{ $statusClass }}" style="width: {{ $percentage }}%"></div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">{{ round($percentage) }}% of {{ $capacity }} capacity</p>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-blue-600"></i>
                            </div>
                            <span class="text-lg font-bold text-slate-900 text-right">
                                {{ $section->gradeLevel->name ?? 'N/A' }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Grade Level</p>
                        <p class="text-xs text-slate-400 mt-1">Level {{ $section->gradeLevel->level ?? '-' }}</p>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                            </div>
                            <span class="text-lg font-bold text-slate-900 text-right truncate max-w-[120px]">
                                {{ $section->teacher->last_name ?? 'None' }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Adviser</p>
                        <p class="text-xs text-slate-400 mt-1">
                            {{ $section->teacher ? 'Assigned' : 'Not assigned' }}
                        </p>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-door-open text-amber-600"></i>
                            </div>
                            <span class="text-lg font-bold text-slate-900 text-right">
                                {{ $section->room_number ?? 'TBA' }}
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Room</p>
                        <p class="text-xs text-slate-400 mt-1">Classroom location</p>
                    </div>
                </div>

                <!-- Section Info Cards -->
                <div class="glass-card p-6 mb-8 animate-fade-in stagger-2">
                    <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-emerald-500"></i>
                        Section Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="info-item">
                            <div class="info-icon bg-emerald-100 text-emerald-600">
                                <i class="fas fa-th-large"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Section Name</p>
                                <p class="font-bold text-slate-900">{{ $section->name }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon bg-blue-100 text-blue-600">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Grade Level</p>
                                <p class="font-bold text-slate-900">{{ $section->gradeLevel->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon bg-purple-100 text-purple-600">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Adviser</p>
                                <p class="font-bold text-slate-900">{{ $section->teacher->full_name ?? 'Not assigned' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon bg-amber-100 text-amber-600">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Room Number</p>
                                <p class="font-bold text-slate-900">{{ $section->room_number ?? 'Not assigned' }}</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon bg-rose-100 text-rose-600">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Capacity</p>
                                <p class="font-bold text-slate-900">{{ $capacity }} students</p>
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-icon bg-cyan-100 text-cyan-600">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">School Year</p>
                                <p class="font-bold text-slate-900">{{ $section->schoolYear->name ?? 'Not set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Students Table - Only Active Enrollments -->
                <div class="glass-card overflow-hidden animate-fade-in stagger-3">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-user-graduate text-emerald-500"></i>
                            Currently Enrolled Students
                            <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-bold">{{ $studentCount }}</span>
                        </h3>
                        <a href="{{ route('admin.students.create') }}?section_id={{ $section->id }}" class="btn-primary text-sm py-2 px-4">
                            <i class="fas fa-plus"></i>
                            Add Student
                        </a>
                    </div>

                    <table class="modern-table w-full">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Contact</th>
                                <th>Gender</th>
                                <th>Enrollment Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($section->active_students as $index => $student)
                                @php
                                    $enrollment = $student->enrollments->first();
                                    $gender = strtoupper($student->gender ?? '');
                                    $sex = ($gender == 'MALE' || $gender == 'M') ? 'M' : 'F';
                                @endphp
                                <tr>
                                    <td>
                                        <span class="text-sm font-semibold text-slate-400">#{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="student-avatar">
                                                {{ strtoupper(substr($student->user->first_name ?? $student->first_name, 0, 1) . substr($student->user->last_name ?? $student->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900">
                                                    {{ ($student->user->last_name ?? $student->last_name) . ', ' . ($student->user->first_name ?? $student->first_name) }}
                                                </p>
                                                <p class="text-xs text-slate-500">ID: {{ $student->student_id ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm text-slate-700 flex items-center gap-1">
                                                <i class="fas fa-envelope text-xs text-slate-400"></i>
                                                {{ $student->user->email ?? 'No email' }}
                                            </span>
                                            <span class="text-xs text-slate-500 flex items-center gap-1">
                                                <i class="fas fa-phone text-xs text-slate-400"></i>
                                                {{ $student->contact_number ?? 'No contact' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-sm font-semibold text-slate-700">{{ $sex }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-enrolled">
                                            <i class="fas fa-circle text-[8px]"></i>
                                            Enrolled
                                        </span>
                                        @if($enrollment && $enrollment->enrollment_date)
                                            <p class="text-xs text-slate-400 mt-1">{{ $enrollment->enrollment_date->format('M d, Y') }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <a href="{{ route('admin.students.show', $student) }}" class="action-btn-sm hover:text-blue-600" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student) }}" class="action-btn-sm hover:text-amber-600" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-user-graduate"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">No Active Students</h3>
                                            <p class="text-slate-500 mb-2 max-w-md mx-auto">This section has no currently enrolled students in the active school year.</p>
                                            @if(isset($activeSchoolYear))
                                                <p class="text-xs text-slate-400 mb-6">School Year: {{ $activeSchoolYear->name }}</p>
                                            @endif
                                            <a href="{{ route('admin.students.create') }}?section_id={{ $section->id }}" class="btn-primary">
                                                <i class="fas fa-plus mr-2"></i>
                                                Enroll First Student
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- Floating Action Buttons -->
            <div class="fixed bottom-8 right-8 flex flex-col gap-3 z-50">
                <a href="{{ route('admin.sections.id-cards', $section) }}"
                   class="w-14 h-14 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-full flex items-center justify-center shadow-lg shadow-blue-500/40 transition-all hover:scale-110"
                   title="Generate Section ID Cards">
                    <i class="fas fa-id-card text-lg"></i>
                </a>
                <a href="{{ route('admin.sections.edit', $section) }}" 
                   class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40 transition-all hover:scale-110 hover:rotate-3"
                   title="Edit Section">
                    <i class="fas fa-edit text-lg"></i>
                </a>
                <a href="{{ route('admin.sections.index') }}" 
                   class="w-12 h-12 bg-white text-slate-600 hover:text-slate-900 rounded-full flex items-center justify-center shadow-lg border border-slate-200 transition-all hover:scale-110"
                   title="Back to Sections">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>