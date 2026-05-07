{{-- Principal Executive Sidebar --}}

<style>
    .principal-sidebar {
        width: 260px;
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        z-index: 40;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: hidden;
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .principal-sidebar::-webkit-scrollbar { width: 4px; }
    .principal-sidebar::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    .principal-sidebar::-webkit-scrollbar-track { background: transparent; }

    .principal-nav-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        margin: 2px 12px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #94a3b8;
        transition: all 0.2s ease;
        position: relative;
    }
    .principal-nav-item:hover {
        background: rgba(255,255,255,0.06);
        color: #e2e8f0;
    }
    .principal-nav-item.active {
        background: linear-gradient(135deg, rgba(245,158,11,0.15), rgba(234,88,12,0.1));
        color: #fbbf24;
        box-shadow: 0 0 0 1px rgba(245,158,11,0.2);
    }
    .principal-nav-item.active::before {
        content: '';
        position: absolute;
        left: -12px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 20px;
        background: linear-gradient(180deg, #fbbf24, #f59e0b);
        border-radius: 0 3px 3px 0;
    }
    .principal-nav-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        background: rgba(255,255,255,0.05);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        flex-shrink: 0;
    }
    .principal-nav-item:hover .principal-nav-icon {
        background: rgba(255,255,255,0.1);
    }
    .principal-nav-item.active .principal-nav-icon {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        box-shadow: 0 4px 12px rgba(245,158,11,0.3);
    }

    .principal-user-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        padding: 14px;
        margin: 12px;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .principal-user-card:hover {
        background: rgba(255,255,255,0.07);
        border-color: rgba(255,255,255,0.1);
    }

    @media (max-width: 1023px) {
        .principal-sidebar { transform: translateX(-100%) !important; }
        .principal-sidebar.translate-x-0 { transform: translateX(0) !important; }
    }
    /* Ensure sidebar is hidden before Alpine initializes */
    .principal-sidebar {
        visibility: hidden;
    }
    .principal-sidebar.translate-x-0,
    .lg\:translate-x-0 {
        visibility: visible;
    }
</style>

@php
$activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
$sidebarStudentCount = $activeSchoolYear
    ? \App\Models\Enrollment::where('school_year_id', $activeSchoolYear->id)->whereIn('status', ['approved', 'enrolled', 'completed'])->count()
    : \App\Models\Student::where('status', 'active')->count();
$sidebarTeacherCount = \App\Models\Teacher::count();
$sidebarSectionCount = \App\Models\Section::count();
$sidebarPendingCount = $activeSchoolYear
    ? \App\Models\Enrollment::where('school_year_id', $activeSchoolYear->id)->where('status', 'pending')->count()
    : \App\Models\Enrollment::where('status', 'pending')->count();
@endphp

<!-- Sidebar -->
<aside id="sidebar"
       class="principal-sidebar lg:translate-x-0"
       :class="mobileOpen ? 'translate-x-0' : ''">

    <!-- Logo -->
    <div class="px-5 pt-6 pb-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center flex-shrink-0 overflow-hidden">
                <img src="{{ asset('images/logo.png') }}" alt="Tugawe Elementary School Logo" class="w-full h-full object-contain p-1">
            </div>
            <div>
                <h1 class="font-bold text-sm text-white tracking-tight">Tugawe Elementary School</h1>
                <p class="text-[10px] text-amber-400 font-medium tracking-wide uppercase">Principal Portal</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-0 py-2 space-y-0.5 overflow-y-auto"
         @click="if ($event.target.closest('a')) mobileOpen = false">
        <p class="px-5 pt-4 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Overview</p>

        <a href="{{ route('principal.dashboard') }}"
           class="principal-nav-item {{ request()->routeIs('principal.dashboard') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <span>Dashboard</span>
        </a>

        <p class="px-5 pt-5 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Directory</p>

        <a href="{{ route('principal.students.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.students.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-users"></i>
            </div>
            <span>Pupils</span>
            <span class="ml-auto text-[10px] font-bold text-slate-500 bg-slate-800 px-2 py-0.5 rounded-md">{{ $sidebarStudentCount }}</span>
        </a>

        <a href="{{ route('principal.teachers.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.teachers.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <span>Teachers</span>
            <span class="ml-auto text-[10px] font-bold text-slate-500 bg-slate-800 px-2 py-0.5 rounded-md">{{ $sidebarTeacherCount }}</span>
        </a>

        <a href="{{ route('principal.sections.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.sections.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-th-large"></i>
            </div>
            <span>Sections</span>
            <span class="ml-auto text-[10px] font-bold text-slate-500 bg-slate-800 px-2 py-0.5 rounded-md">{{ $sidebarSectionCount }}</span>
        </a>

        <p class="px-5 pt-5 pb-2 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operations</p>

        <a href="{{ route('principal.pending-registrations.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.pending-registrations.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-user-clock"></i>
            </div>
            <span>Pending</span>
            @if($sidebarPendingCount > 0)
                <span class="ml-auto text-[10px] font-bold text-amber-400 bg-amber-900/30 px-2 py-0.5 rounded-md">{{ $sidebarPendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('principal.reports.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.reports.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <span>Reports</span>
        </a>

        <a href="{{ route('principal.school-years.index') }}"
           class="principal-nav-item {{ request()->routeIs('principal.school-years.*') ? 'active' : '' }}">
            <div class="principal-nav-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <span>School Year</span>
        </a>
    </nav>

    <!-- User Card -->
    <div class="p-3">
        <div class="principal-user-card" onclick="document.getElementById('principalUserMenu').classList.toggle('hidden')">
            <div class="flex items-center gap-3">
                @if(auth()->user()->photo)
                    <img src="{{ profile_photo_url(auth()->user()->photo) }}" alt="" class="w-9 h-9 rounded-full border-2 border-amber-500/30 object-cover">
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->first_name ?? 'P', 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Principal' }}</p>
                    <p class="text-[10px] text-amber-400 truncate">Principal</p>
                </div>
                <i class="fas fa-chevron-up text-slate-500 text-xs"></i>
            </div>

            <div id="principalUserMenu" class="hidden mt-3 pt-3 border-t border-white/5 space-y-1">
                <a href="{{ route('principal.profile') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-user text-xs w-4"></i> Profile
                </a>
                 <a href="{{ route('principal.activity-logs.index') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-history text-xs w-4"></i> Activity Logs
                </a>
                <a href="{{ route('pwa.settings') }}" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                    <i class="fas fa-mobile-alt text-xs w-4"></i> PWA & Biometric
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 rounded-lg text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition-colors text-left">
                        <i class="fas fa-sign-out-alt text-xs w-4"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>


