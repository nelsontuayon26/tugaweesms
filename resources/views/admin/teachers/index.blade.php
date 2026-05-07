{{-- resources/views/admin/teachers/index.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Teachers | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        body {
            overflow-x: hidden;
            background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 50%, #faf5ff 100%);
        }

        .dashboard-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: transparent;
        }

        .main-header {
            height: 84px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
            display: flex;
            align-items: center;
            padding: 0 32px;
            flex-shrink: 0;
            z-index: 40;
        }

        .main-content {
            flex: 1;
            overflow-x: hidden;
            padding: 24px 32px;
        }

        @media (max-width: 1024px) {
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Enhanced Table Design */
        .modern-table {
            border-collapse: separate;
            border-spacing: 0 4px;
            width: 100%;
        }
        
        .modern-table thead th {
            background: transparent;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-size: 0.7rem;
            color: #64748b;
            padding: 16px 24px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .modern-table tbody tr {
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
        }
        
        .modern-table tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -5px rgba(0, 0, 0, 0.08), 0 4px 12px -6px rgba(0, 0, 0, 0.05);
            z-index: 10;
            position: relative;
        }
        
        .modern-table td {
            padding: 20px 24px;
            border: none;
            vertical-align: middle;
        }
        
        .modern-table tbody tr td:first-child {
            border-radius: 12px 0 0 12px;
        }
        
        .modern-table tbody tr td:last-child {
            border-radius: 0 12px 12px 0;
        }

        /* Enhanced Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            gap: 6px;
            transition: all 0.2s;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .status-active {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }
        
        .status-inactive {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .status-on-leave {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        /* Enhanced Action Buttons */
        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateX(10px);
        }
        
        .modern-table tbody tr:hover .action-btn {
            opacity: 1;
            transform: translateX(0);
        }

        @media (hover: none) and (pointer: coarse) {
            .action-btn {
                opacity: 1 !important;
            }
        }
        
        .action-btn:hover {
            transform: scale(1.15) rotate(5deg);
        }

        .custom-checkbox {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        
        .custom-checkbox:checked {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-color: #7c3aed;
        }
        
        .custom-checkbox:checked::after {
            content: '✓';
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: 700;
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(30px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-5px); }
        }
        
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }
        
        .animate-slide-in {
            animation: slideInRight 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Enhanced Glass Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.6);
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
            backdrop-filter: blur(10px);
        }

        .search-input {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        
        .search-input:focus-within {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.1), 0 4px 12px rgba(139, 92, 246, 0.08);
            transform: translateY(-1px);
        }

        .mobile-overlay {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(8px);
        }

        /* Enhanced Stat Pills */
        .stat-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #e2e8f0;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #475569;
            transition: all 0.3s;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        
        .stat-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-pill i {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subject-tag {
            display: inline-flex;
            align-items: center;
            padding: 5px 12px;
            background: linear-gradient(135deg, #f3e8ff 0%, #ede9fe 100%);
            color: #7c3aed;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-right: 6px;
            margin-bottom: 6px;
            border: 1px solid #ddd6fe;
            transition: all 0.2s;
        }
        
        .subject-tag:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(124, 58, 237, 0.15);
        }

        /* Enhanced FAB */
        .fab-icon-only {
            position: fixed;
            bottom: 32px;
            right: 32px;
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #9333ea 0%, #7c3aed 50%, #4f46e5 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 10px 40px -10px rgba(124, 58, 237, 0.5), 0 0 0 0 rgba(147, 51, 234, 0.4);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 45;
            text-decoration: none;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { box-shadow: 0 10px 40px -10px rgba(124, 58, 237, 0.5), 0 0 0 0 rgba(147, 51, 234, 0.4); }
            50% { box-shadow: 0 10px 40px -10px rgba(124, 58, 237, 0.6), 0 0 0 10px rgba(147, 51, 234, 0); }
        }

        .fab-icon-only:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 20px 50px -10px rgba(124, 58, 237, 0.6);
            animation: none;
        }

        /* Enhanced Section Tags */
        .section-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 6px;
            margin-right: 6px;
            transition: all 0.2s;
        }
        
        .section-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
        }

        /* Enhanced Filter Selects */
        .filter-select {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.3s;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }
        
        .filter-select:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .filter-select:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
            outline: none;
        }

        /* Avatar Enhancement */
        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            object-fit: cover;
            transition: all 0.3s;
        }
        
        .modern-table tbody tr:hover .avatar-circle {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        /* Empty State Enhancement */
        .empty-state {
            padding: 60px 40px;
            text-align: center;
        }
        
        .empty-state-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            animation: float 3s ease-in-out infinite;
        }

        /* Quick Stats Cards */
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(226, 232, 240, 0.6);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #8b5cf6 0%, #7c3aed 100%);
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px -12px rgba(0, 0, 0, 0.12);
        }
        
        .stat-card:hover::before {
            opacity: 1;
        }

        .stat-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: all 0.3s;
        }
        
        .stat-card:hover .stat-icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        /* Mobile */
        @media (max-width: 1024px) {
            .fab-icon-only {
                bottom: 24px;
                right: 24px;
                width: 56px;
                height: 56px;
            }
            
            .main-header {
                padding: 0 20px;
                height: 72px;
            }
            
            .main-content {
                padding: 20px;
            }
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #1e293b 0%, #475569 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enhanced Modal */
        .modal-backdrop {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 100px;
            right: 32px;
            background: white;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 100;
            border-left: 4px solid #10b981;
        }
        
        .toast.show {
            transform: translateX(0);
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
                            <h2 class="text-2xl font-bold gradient-text tracking-tight">Teachers Management</h2>
                            <p class="text-sm text-slate-500 font-medium flex items-center gap-2 mt-1">
                                <span class="w-2 h-2 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-full animate-pulse"></span>
                                Manage faculty and teaching staff
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="hidden md:flex items-center gap-3">
                            <div class="stat-pill">
                                <i class="fas fa-chalkboard-teacher text-sm"></i>
                                <span>{{ $teacherStats['total'] }} Total</span>
                            </div>
                            <div class="stat-pill">
                                <i class="fas fa-user-check text-sm"></i>
                                <span>{{ $teacherStats['active'] }} Active</span>
                            </div>
                        </div>

                        <div class="search-input hidden md:flex">
                            <i class="fas fa-search text-slate-400"></i>
                            <input type="text" id="searchInput" placeholder="Search teachers..." class="bg-transparent border-none outline-none text-sm w-56 placeholder:text-slate-400">
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="main-content">
                
                <!-- Teacher data is now passed from controller with pagination -->

                <!-- Enhanced Filters -->
                <div class="mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 animate-fade-in-up">
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-filter text-slate-400"></i>
                            <select id="statusFilter" class="filter-select" onchange="filterByStatus(this.value)">
                                <option value="">All Status</option>
                                <option value="active">🟢 Active</option>
                                <option value="on_leave">🟡 On Leave</option>
                                <option value="inactive">⚪ Inactive</option>
                            </select>
                        </div>
                        

                    </div>

                    <div class="flex items-center gap-3">
                        <button onclick="exportData()" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-purple-600 hover:bg-purple-50 border border-slate-200 hover:border-purple-200 rounded-xl transition-all flex items-center gap-2 bg-white shadow-sm">
                            <i class="fas fa-download"></i>
                            Export CSV
                        </button>
                    </div>
                </div>

                <!-- Enhanced Teachers Table -->
                <div class="glass-card overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50/80 to-white">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                                <i class="fas fa-chalkboard-teacher text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Faculty Directory</h3>
                                <p class="text-sm text-slate-500">Showing <span class="font-semibold text-purple-600">{{ $teachers->count() }}</span> of {{ $teacherStats['total'] }} teaching staff members</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-500 font-medium">Sort by:</span>
                            <select id="sortSelect" class="filter-select py-2" onchange="sortTable(this.value)">
                                <option value="newest">📅 Newest First</option>
                                <option value="name">🔤 Name (A-Z)</option>
                               
                            </select>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto p-2">
                        <table class="modern-table" id="teachersTable">
                            <thead>
                                <tr>
                                    <th class="w-12 pl-6">
                                        <input type="checkbox" class="custom-checkbox" id="selectAll" onclick="toggleSelectAll()">
                                    </th>
                                    <th>Teacher Information</th>
                                    <th>Sections & Subjects</th>
                                    <th>Contact Details</th>
                                    <th>Status</th>
                                    <th>Joined Date</th>
                                    <th class="text-right pr-6">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($teachers as $teacher)
                                <tr class="teacher-row" data-status="{{ $teacher->status }}" data-name="{{ strtolower($teacher->full_name) }}" data-subjects="{{ $teacher->subjects->pluck('name')->join(', ') }}">
                                    <td class="pl-6">
                                        <input type="checkbox" class="custom-checkbox teacher-checkbox" value="{{ $teacher->id }}">
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-4">
                                            <img src="{{ $teacher->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($teacher->full_name) . '&background=random&color=fff&size=128' }}"
                                                 alt="{{ $teacher->full_name }}" 
                                                 class="avatar-circle">
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm mb-0.5">{{ $teacher->full_name }}</p>
                                                <p class="text-xs text-slate-500 font-medium">ID: {{ $teacher->employee_id ?? 'EMP-' . str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            @php
                                                $sections = $teacher->sections;
                                                $subjects = $teacher->subjects;
                                            @endphp
                                            
                                            @if($sections->isEmpty() && $subjects->isEmpty())
                                                <span class="text-xs text-slate-400 italic bg-slate-50 px-3 py-1.5 rounded-lg inline-block w-fit">No assignments</span>
                                            @else
                                                <div class="flex flex-wrap">
                                                    @foreach($sections as $section)
                                                        <div class="section-item">
                                                            <i class="fas fa-door-open text-xs"></i>
                                                            <span>{{ $section->name }}</span>
                                                            @if($section->gradeLevel)
                                                                <span class="text-blue-400 text-xs">• {{ $section->gradeLevel->name }}</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="flex flex-wrap mt-1">
                                                    @foreach($subjects as $subject)
                                                        <span class="subject-tag">
                                                            <i class="fas fa-book-open text-xs mr-1"></i>
                                                            {{ $subject->name }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-2">
                                            <span class="text-sm text-slate-700 font-medium flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg w-fit">
                                                <i class="fas fa-envelope text-purple-400 text-xs"></i>
                                                {{ $teacher->email ?? 'No email' }}
                                            </span>
                                            @if($teacher->phone)
                                            <span class="text-xs text-slate-500 flex items-center gap-2 bg-slate-50 px-3 py-1.5 rounded-lg w-fit">
                                                <i class="fas fa-phone text-emerald-400"></i>
                                                {{ $teacher->phone }}
                                            </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $status = $teacher->status ?? 'active';
                                            $statusClass = match($status) {
                                                'active' => 'status-active',
                                                'on_leave' => 'status-on-leave',
                                                default => 'status-inactive'
                                            };
                                            $statusIcon = match($status) {
                                                'active' => 'fa-check-circle',
                                                'on_leave' => 'fa-clock',
                                                default => 'fa-ban'
                                            };
                                            $statusLabel = match($status) {
                                                'on_leave' => 'On Leave',
                                                default => ucfirst($status)
                                            };
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">
                                            <i class="fas {{ $statusIcon }}"></i>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-semibold text-slate-700">
                                                {{ $teacher->created_at->format('M d, Y') }}
                                            </span>
                                            <span class="text-xs text-slate-400">
                                                {{ $teacher->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-right pr-6">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="action-btn text-blue-600 hover:bg-blue-50" title="View Details" style="transition-delay: 0ms;">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="action-btn text-amber-600 hover:bg-amber-50" title="Edit Teacher" style="transition-delay: 50ms;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteTeacher({{ $teacher->id }})" class="action-btn text-red-600 hover:bg-red-50" title="Delete" style="transition-delay: 100ms;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-user-slash text-4xl text-slate-400"></i>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-700 mb-2">No teachers found</h3>
                                            <p class="text-slate-500 mb-6 max-w-sm mx-auto">Get started by adding your first teacher to the faculty directory</p>
                                            <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-8 py-3.5 rounded-xl font-semibold hover:from-purple-700 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/30 hover:shadow-purple-500/40 hover:-translate-y-0.5">
                                                <i class="fas fa-plus"></i>
                                                Add New Teacher
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Enhanced Pagination -->
                    <div class="px-6 py-5 border-t border-slate-200 bg-gradient-to-r from-slate-50/50 to-white flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Showing {{ $teachers->firstItem() ?? 0 }}–{{ $teachers->lastItem() ?? 0 }}</span>
                            <span class="text-sm text-slate-500">of {{ $teachers->total() }} teachers</span>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            {{ $teachers->links('vendor.pagination.tailwind') }}
                        </div>
                    </div>
                </div>

                <!-- Enhanced Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-8 animate-fade-in-up" style="animation-delay: 0.2s;">
                    <!-- Stats pre-computed in controller for performance -->
                    
                    <div class="stat-card">
                        <div class="stat-icon-box bg-gradient-to-br from-emerald-50 to-emerald-100 text-emerald-600">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-slate-900">{{ $teacherStats['active'] }}</p>
                            <p class="text-sm text-slate-500 font-medium">Active Teachers</p>
                            <div class="mt-2 flex items-center gap-1 text-xs text-emerald-600 font-semibold">
                                <i class="fas fa-arrow-up"></i>
                                <span>{{ $teacherStats['total'] > 0 ? round(($teacherStats['active'] / $teacherStats['total']) * 100) : 0 }}% of total</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon-box bg-gradient-to-br from-amber-50 to-amber-100 text-amber-600">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-slate-900">{{ $teacherStats['on_leave'] }}</p>
                            <p class="text-sm text-slate-500 font-medium">On Leave</p>
                            <div class="mt-2 flex items-center gap-1 text-xs text-amber-600 font-semibold">
                                <i class="fas fa-minus-circle"></i>
                                <span>Temporary absence</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon-box bg-gradient-to-br from-blue-50 to-blue-100 text-blue-600">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-slate-900">{{ $teacherStats['subjects'] }}</p>
                            <p class="text-sm text-slate-500 font-medium">Subjects Covered</p>
                            <div class="mt-2 flex items-center gap-1 text-xs text-blue-600 font-semibold">
                                <i class="fas fa-layer-group"></i>
                                <span>Across all grades</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon-box bg-gradient-to-br from-purple-50 to-purple-100 text-purple-600">
                            <i class="fas fa-chalkboard"></i>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-slate-900">{{ $teacherStats['sections'] }}</p>
                            <p class="text-sm text-slate-500 font-medium">Sections Handled</p>
                            <div class="mt-2 flex items-center gap-1 text-xs text-purple-600 font-semibold">
                                <i class="fas fa-users"></i>
                                <span>Active assignments</span>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Enhanced Delete Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden">
        <div class="modal-backdrop absolute inset-0" onclick="closeDeleteModal()"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md animate-fade-in-up">
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-gradient-to-br from-red-50 to-red-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">Delete Teacher?</h3>
                <p class="text-sm text-slate-500 leading-relaxed">Are you sure you want to remove this teacher from the faculty directory? This action cannot be undone and all associated data will be permanently deleted.</p>
            </div>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-5 py-3 text-sm font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-5 py-3 text-sm font-bold text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 rounded-xl transition-all shadow-lg shadow-red-500/30 hover:shadow-red-500/40">
                        Delete Teacher
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toast" class="toast">
        <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
        <div>
            <p class="font-semibold text-slate-900 text-sm">Success</p>
            <p class="text-slate-500 text-xs">Operation completed successfully</p>
        </div>
    </div>

    <!-- Enhanced Floating Action Button -->
    <a href="{{ route('admin.teachers.create') }}" class="fab-icon-only" title="Add New Teacher">
        <i class="fas fa-plus text-xl"></i>
    </a>

    <script>
        // Enhanced Search with debounce
        let searchTimeout;
        document.getElementById('searchInput')?.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('.teacher-row');
                
                rows.forEach(row => {
                    const name = row.getAttribute('data-name');
                    const email = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
                    const subjects = row.getAttribute('data-subjects')?.toLowerCase() || '';
                    
                    if (name.includes(searchTerm) || email.includes(searchTerm) || subjects.includes(searchTerm)) {
                        row.style.display = '';
                        row.style.animation = 'fadeInUp 0.4s ease forwards';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }, 300);
        });

        function filterByStatus(status) {
            const rows = document.querySelectorAll('.teacher-row');
            
            rows.forEach(row => {
                if (!status || row.getAttribute('data-status') === status) {
                    row.style.display = '';
                    row.style.animation = 'fadeInUp 0.4s ease forwards';
                } else {
                    row.style.display = 'none';
                }
            });
            
            showToast(`Filtered by status: ${status || 'All'}`);
        }

        function filterBySubject(subject) {
            const rows = document.querySelectorAll('.teacher-row');
            
            rows.forEach(row => {
                const subjects = row.getAttribute('data-subjects') || '';
                if (!subject || subjects.includes(subject)) {
                    row.style.display = '';
                    row.style.animation = 'fadeInUp 0.4s ease forwards';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.teacher-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            if (selectAll.checked) {
                showToast(`Selected ${checkboxes.length} teachers`);
            }
        }

        function sortTable(sortBy) {
            const tbody = document.querySelector('#teachersTable tbody');
            const rows = Array.from(tbody.querySelectorAll('.teacher-row'));
            
            rows.sort((a, b) => {
                if (sortBy === 'name') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortBy === 'subject') {
                    const aSubjects = a.getAttribute('data-subjects') || '';
                    const bSubjects = b.getAttribute('data-subjects') || '';
                    return aSubjects.localeCompare(bSubjects);
                }
                return 0;
            });
            
            rows.forEach(row => {
                tbody.appendChild(row);
                row.style.animation = 'fadeInUp 0.4s ease forwards';
            });
        }

        function deleteTeacher(id) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/admin/teachers/${id}`;
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        function exportData() {
            const rows = document.querySelectorAll('.teacher-row');
            let csv = 'ID,Name,Email,Status,Hired Date\\n';
            
            rows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cells = row.querySelectorAll('td');
                    const id = cells[1].querySelector('p.text-xs').textContent.replace('ID: ', '').trim();
                    const name = cells[1].querySelector('p.font-bold').textContent;
                    const email = cells[3].querySelector('span').textContent.trim();
                    const status = cells[4].textContent.trim();
                    const date = cells[5].querySelector('span.text-sm').textContent;
                    
                    csv += `"${id}","${name}","${email}","${status}","${date}"\\n`;
                }
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `teachers_export_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            
            showToast('CSV exported successfully');
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const icon = toast.querySelector('i');
            const title = toast.querySelector('p.font-semibold');
            
            toast.querySelector('p.text-xs').textContent = message;
            
            if (type === 'error') {
                toast.style.borderLeftColor = '#ef4444';
                icon.className = 'fas fa-times-circle text-red-500 text-xl';
                title.textContent = 'Error';
                playSound('error');
            } else {
                toast.style.borderLeftColor = '#10b981';
                icon.className = 'fas fa-check-circle text-emerald-500 text-xl';
                title.textContent = 'Success';
                playSound('success');
            }
            
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function playSound(type) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                
                if (type === 'success') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(523.25, ctx.currentTime); // C5
                    osc.frequency.setValueAtTime(659.25, ctx.currentTime + 0.1); // E5
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.4);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(150, ctx.currentTime);
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.3);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.3);
                }
            } catch (e) {
                // ignore audio errors
            }
        }

        // Handle Laravel flash messages
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                showToast(@json(session('success')), 'success');
            });
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => {
                showToast(@json(session('error')), 'error');
            });
        @endif

        // Persist filter and sort using localStorage
        const STATUS_KEY = 'teachers_status_filter';
        const SORT_KEY = 'teachers_sort_by';

        const savedStatus = localStorage.getItem(STATUS_KEY);
        const savedSort = localStorage.getItem(SORT_KEY);

        if (savedStatus) {
            const statusSelect = document.getElementById('statusFilter');
            if (statusSelect) {
                statusSelect.value = savedStatus;
                filterByStatus(savedStatus);
            }
        }

        if (savedSort) {
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.value = savedSort;
                sortTable(savedSort);
            }
        }

        // Override original functions to save state
        const originalFilterByStatus = filterByStatus;
        filterByStatus = function(status) {
            localStorage.setItem(STATUS_KEY, status);
            originalFilterByStatus(status);
        };

        const originalSortTable = sortTable;
        sortTable = function(sortBy) {
            localStorage.setItem(SORT_KEY, sortBy);
            originalSortTable(sortBy);
        };

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                document.getElementById('searchInput')?.focus();
            }
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
            if ((e.metaKey || e.ctrlKey) && e.key === 'n') {
                e.preventDefault();
                window.location.href = "{{ route('admin.teachers.create') }}";
            }
        });

        // Add loading states
        document.querySelectorAll('a[href*="create"]').forEach(link => {
            link.addEventListener('click', function() {
                this.style.opacity = '0.7';
            });
        });
    </script>
</body>
</html>