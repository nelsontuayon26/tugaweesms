<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Subject Assignments | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { margin: 0; padding: 0; background: #f8fafc; overflow-x: hidden; }
        .dashboard-container { display: flex; height: 100vh; width: 100vw; }
        .main-wrapper { margin-left: 280px; flex: 1; display: flex; flex-direction: column; height: 100vh; overflow-x: hidden; }
        .main-content { flex: 1; overflow-y: auto; overflow-x: hidden; padding: 32px; background: #f8fafc; }
        .main-content::-webkit-scrollbar { width: 8px; }
        .main-content::-webkit-scrollbar-track { background: transparent; }
        .main-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .glass-card { background: white; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1), 0 1px 2px 0 rgba(0,0,0,0.06); border-radius: 24px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .glass-card:hover { box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
        .modern-table { border-collapse: separate; border-spacing: 0; width: 100%; }
        .modern-table th { background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%); font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; color: #64748b; padding: 16px 20px; text-align: left; border-bottom: 2px solid #e2e8f0; }
        .modern-table td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; transition: all 0.2s ease; }
        .modern-table tbody tr { transition: all 0.2s ease; }
        .modern-table tbody tr:hover { background: linear-gradient(90deg, rgba(99,102,241,0.03) 0%, transparent 100%); transform: scale(1.002); }
        .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 24px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow-x: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #6366f1, #8b5cf6); transform: scaleX(0); transition: transform 0.3s ease; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -12px rgba(0,0,0,0.15); }
        .stat-card:hover::before { transform: scaleX(1); }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .page-title { font-size: 28px; font-weight: 800; color: #0f172a; letter-spacing: -0.025em; }
        .page-subtitle { color: #64748b; font-size: 14px; margin-top: 4px; }
        .search-input { width: 100%; max-width: 320px; padding: 10px 14px 10px 40px; border: 2px solid #e2e8f0; border-radius: 14px; font-size: 14px; transition: all 0.3s ease; background: white; }
        .search-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .btn-primary { background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); color: white; padding: 12px 24px; border-radius: 14px; font-weight: 700; font-size: 14px; border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px -3px rgba(99,102,241,0.4); display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 30px -5px rgba(99,102,241,0.5); }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 13px; border: none; cursor: pointer; transition: all 0.3s ease; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(239,68,68,0.4); }
        .btn-secondary { background: #f1f5f9; color: #475569; padding: 10px 18px; border-radius: 12px; font-weight: 600; font-size: 13px; border: none; cursor: pointer; transition: all 0.2s ease; }
        .btn-secondary:hover { background: #e2e8f0; }
        .form-select, .form-input { width: 100%; padding: 10px 14px; border: 2px solid #e2e8f0; border-radius: 12px; font-size: 14px; transition: all 0.3s ease; background: white; }
        .form-select:focus, .form-input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        .form-group { margin-bottom: 16px; }
        .badge { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 10px; font-weight: 600; font-size: 0.75rem; }
        .badge-indigo { background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%); border: 1px solid #c7d2fe; color: #3730a3; }
        .badge-emerald { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border: 1px solid #a7f3d0; color: #065f46; }
        .badge-amber { background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1px solid #fcd34d; color: #92400e; }
        .badge-slate { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border: 1px solid #e2e8f0; color: #475569; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { width: 80px; height: 80px; background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-radius: 24px; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px; font-size: 32px; color: #94a3b8; }
        .alert { padding: 14px 20px; border-radius: 14px; margin-bottom: 20px; font-size: 14px; font-weight: 500; }
        .alert-success { background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .alert-error { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .collapse-section { max-height: 0; overflow: hidden; transition: max-height 0.4s ease, opacity 0.3s ease; opacity: 0; }
        .collapse-section.open { max-height: 800px; opacity: 1; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeInUp 0.6s cubic-bezier(0.16,1,0.3,1) forwards; }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        @media (max-width: 1024px) { .main-wrapper { margin-left: 0; } }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileOpen: false, formOpen: false }" @keydown.escape.window="mobileOpen = false">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" x-transition class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm" style="display: none;" @click="mobileOpen = false"></div>
<!-- Mobile Hamburger -->
<button @click="mobileOpen = !mobileOpen" class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
    <i class="fas fa-bars"></i>
</button>

<div class="dashboard-container">
    @include('admin.includes.sidebar')

    <div class="main-wrapper">
        <div class="main-content">

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error animate-fade-in">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-fade-in">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book-open text-indigo-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Total Assignments</p>
                    <p class="text-xs text-slate-400 mt-1">Subject-teacher pairs</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-purple-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900">{{ $stats['teachers'] }}</span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Teachers Assigned</p>
                    <p class="text-xs text-slate-400 mt-1">With subject loads</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-th-large text-emerald-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900">{{ $stats['sections'] }}</span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Sections Covered</p>
                    <p class="text-xs text-slate-400 mt-1">With assigned subjects</p>
                </div>
            </div>

            <!-- Header & Filters -->
            <div class="glass-card p-6 mb-6 animate-fade-in stagger-1">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="page-title">Subject Assignments</h2>
                        <p class="page-subtitle">Assign teachers to subjects and sections for grade encoding</p>
                        @if($selectedSchoolYear)
                            <span class="badge badge-emerald mt-2">
                                <i class="fas fa-calendar-check"></i>
                                {{ $selectedSchoolYear->name }}
                                @if($activeSchoolYear && $selectedSchoolYear->id == $activeSchoolYear->id)
                                    <span class="ml-1 font-bold">(Active)</span>
                                @endif
                            </span>
                        @endif
                    </div>
                    <button @click="formOpen = !formOpen" class="btn-primary">
                        <i class="fas fa-plus" x-show="!formOpen"></i>
                        <i class="fas fa-minus" x-show="formOpen" x-cloak></i>
                        <span x-text="formOpen ? 'Close Form' : 'New Assignment'"></span>
                    </button>
                </div>

                <!-- Create Form (collapsible) -->
                <div :class="formOpen ? 'open' : ''" class="collapse-section">
                    <div class="border-t border-slate-100 pt-6 mt-4">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Create New Assignment</h3>
                        <form action="{{ route('admin.teacher-subject-assignments.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div class="form-group">
                                    <label class="form-label">Teacher <span class="text-red-500">*</span></label>
                                    <select name="teacher_id" class="form-select @error('teacher_id') border-red-400 @enderror" required>
                                        <option value="">Select Teacher</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                                {{ $t->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('teacher_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Section <span class="text-red-500">*</span></label>
                                    <select name="section_id" class="form-select @error('section_id') border-red-400 @enderror" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $s)
                                            <option value="{{ $s->id }}" {{ old('section_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->name }} ({{ $s->gradeLevel?->name ?? 'N/A' }}) — {{ $s->schoolYear?->name ?? '' }}
                                                @if($s->teacher) [Adviser: {{ $s->teacher->full_name }}] @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Subject <span class="text-red-500">*</span></label>
                                    <select name="subject_id" class="form-select @error('subject_id') border-red-400 @enderror" required>
                                        <option value="">Select Subject</option>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->id }}" {{ old('subject_id') == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }} ({{ $sub->gradeLevel?->name ?? 'All Grades' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('subject_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">School Year</label>
                                    <select name="school_year" class="form-select">
                                        <option value="">Active School Year</option>
                                        @foreach($schoolYears as $sy)
                                            <option value="{{ $sy->name }}" {{ old('school_year', $selectedSchoolYear?->name) == $sy->name ? 'selected' : '' }}>
                                                {{ $sy->name }} {{ $sy->is_active ? '★' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Schedule (e.g., MWF)</label>
                                    <input type="text" name="schedule" class="form-input" value="{{ old('schedule') }}" placeholder="MWF, TTh, etc.">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Room</label>
                                    <input type="text" name="room" class="form-input" value="{{ old('room') }}" placeholder="Room number">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Time Start</label>
                                    <input type="time" name="time_start" class="form-input" value="{{ old('time_start') }}">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Time End</label>
                                    <input type="time" name="time_end" class="form-input" value="{{ old('time_end') }}">
                                </div>
                            </div>

                            <div class="flex gap-3 mt-4">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save"></i> Save Assignment
                                </button>
                                <button type="button" @click="formOpen = false" class="btn-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Filters Bar -->
                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between mt-6 pt-6 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        <form action="{{ route('admin.teacher-subject-assignments.index') }}" method="GET" class="relative w-full sm:w-auto">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search assignments..." class="search-input">
                        </form>

                        <form action="{{ route('admin.teacher-subject-assignments.index') }}" method="GET" id="syFilterForm" class="relative w-full sm:w-auto">
                            @foreach(['search','teacher_id','section_id','subject_id'] as $param)
                                @if(request($param))<input type="hidden" name="{{ $param }}" value="{{ request($param) }}">@endif
                            @endforeach
                            <select name="school_year_id" onchange="document.getElementById('syFilterForm').submit()" class="form-select w-full sm:w-52">
                                <option value="">All School Years</option>
                                @foreach($schoolYears as $sy)
                                    <option value="{{ $sy->id }}" {{ $selectedSchoolYear && $selectedSchoolYear->id == $sy->id ? 'selected' : '' }}>
                                        {{ $sy->name }} {{ $sy->is_active ? '★ Active' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <form action="{{ route('admin.teacher-subject-assignments.index') }}" method="GET" class="flex flex-wrap gap-2">
                            @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                            @if(request('school_year_id'))<input type="hidden" name="school_year_id" value="{{ request('school_year_id') }}">@endif

                            <select name="teacher_id" onchange="this.form.submit()" class="form-select w-auto min-w-[140px]">
                                <option value="">All Teachers</option>
                                @foreach($teachers as $t)
                                    <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->full_name }}</option>
                                @endforeach
                            </select>

                            <select name="section_id" onchange="this.form.submit()" class="form-select w-auto min-w-[140px]">
                                <option value="">All Sections</option>
                                @foreach($sections as $s)
                                    <option value="{{ $s->id }}" {{ request('section_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>

                            <select name="subject_id" onchange="this.form.submit()" class="form-select w-auto min-w-[140px]">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                @endforeach
                            </select>

                            @if(request('teacher_id') || request('section_id') || request('subject_id') || request('search') || request('school_year_id'))
                                <a href="{{ route('admin.teacher-subject-assignments.index') }}" class="btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="glass-card overflow-hidden animate-fade-in stagger-2">
                <div class="overflow-x-auto">
                    <table class="modern-table w-full">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Subject</th>
                                <th>Section</th>
                                <th>Grade Level</th>
                                <th>School Year</th>
                                <th>Schedule</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-user-tie text-indigo-600 text-xs"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-800 text-sm">{{ $assignment->teacher_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-indigo">{{ $assignment->subject_name }}</span>
                                        @if($assignment->subject_code)
                                            <span class="text-xs text-slate-400 ml-1">({{ $assignment->subject_code }})</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-emerald">{{ $assignment->section_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="text-sm text-slate-600">{{ $assignment->grade_level_name ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-slate">{{ $assignment->school_year ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="text-sm text-slate-600">
                                            @if($assignment->schedule)
                                                <div class="font-medium">{{ $assignment->schedule }}</div>
                                            @endif
                                            @if($assignment->time_start && $assignment->time_end)
                                                <div class="text-xs text-slate-400">{{ date('g:i A', strtotime($assignment->time_start)) }} - {{ date('g:i A', strtotime($assignment->time_end)) }}</div>
                                            @endif
                                            @if($assignment->room)
                                                <div class="text-xs text-slate-400"><i class="fas fa-door-open mr-1"></i>{{ $assignment->room }}</div>
                                            @endif
                                            @if(!$assignment->schedule && !$assignment->time_start && !$assignment->room)
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <form action="{{ route('admin.teacher-subject-assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Remove this assignment? The teacher will no longer be able to encode grades for this subject.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">
                                                <i class="fas fa-trash-alt mr-1"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-book-open"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-700 mb-2">No Assignments Found</h3>
                                            <p class="text-slate-400 text-sm mb-4">No subject assignments match your filters.</p>
                                            <button @click="formOpen = true" class="btn-primary">
                                                <i class="fas fa-plus mr-2"></i> Create First Assignment
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($assignments->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100">
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

</body>
</html>
