@extends('layouts.principal')

@section('title', 'Principal Dashboard')

@push('styles')
<style>
    .p-card {
        background: white;
        border: 1px solid #e7e5e4;
        border-radius: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }
    .p-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06), 0 16px 40px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    .p-stat {
        position: relative;
        overflow: hidden;
    }
    .p-stat::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        opacity: 0.04;
        transform: translate(30%, -30%);
    }

    .p-table th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #78716c;
        padding: 14px 20px;
        background: #fafaf9;
        border-bottom: 1px solid #e7e5e4;
    }
    .p-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #f5f5f4;
        font-size: 0.875rem;
        color: #44403c;
    }
    .p-table tbody tr:hover td {
        background: #fafaf9;
    }

    .p-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .p-btn-amber {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: white;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: 0 4px 14px rgba(245,158,11,0.25);
    }
    .p-btn-amber:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(245,158,11,0.35);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    @php
        $activeSchoolYearId = $activeSchoolYear ? $activeSchoolYear->id : null;
        $activeSchoolYearName = $activeSchoolYear ? $activeSchoolYear->name : 'No Active School Year';
    @endphp

    <!-- Header -->
    <header class="principal-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <div>
                    <h2 class="text-lg font-bold text-stone-900 tracking-tight">Principal Overview</h2>
                    <p class="text-xs text-stone-500 mt-0.5">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($activeSchoolYear)
                    <span class="px-3 py-1.5 bg-amber-50 text-amber-700 rounded-lg text-xs font-bold border border-amber-200">
                        <i class="fas fa-graduation-cap mr-1"></i> {{ $activeSchoolYearName }}
                    </span>
                @endif
                <a href="{{ route('principal.reports.index') }}" class="hidden sm:flex items-center gap-2 p-btn-amber">
                    <i class="fas fa-file-alt text-sm"></i>
                    <span>Reports</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="principal-content">
        @if(!$activeSchoolYear)
            <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                <p class="text-sm text-amber-800 font-medium">No active school year configured. Contact the System Admin.</p>
            </div>
        @endif

        <!-- Welcome -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-stone-900">Welcome back, {{ auth()->user()->first_name ?? 'Principal' }}!</h1>
            <p class="text-stone-500 text-sm mt-1">Here's your school-wide performance snapshot.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            <div class="p-card p-stat p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                        {{ $maleStudents }}M / {{ $femaleStudents }}F
                    </span>
                </div>
                <p class="text-2xl font-bold text-stone-900">{{ number_format($totalStudents) }}</p>
                <p class="text-xs text-stone-500 mt-0.5 font-medium">Total Pupils</p>
            </div>

            <div class="p-card p-stat p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                        {{ $totalTeachers > 0 ? round(($activeTeachers / $totalTeachers) * 100) : 0 }}% active
                    </span>
                </div>
                <p class="text-2xl font-bold text-stone-900">{{ number_format($totalTeachers) }}</p>
                <p class="text-xs text-stone-500 mt-0.5 font-medium">Teachers</p>
            </div>

            <div class="p-card p-stat p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    @php
                        $attendanceRate = $totalStudents > 0 ? round(($presentToday / max($totalStudents, 1)) * 100, 1) : 0;
                    @endphp
                    <span class="text-[10px] font-bold {{ $attendanceRate >= 90 ? 'text-emerald-600 bg-emerald-50 border-emerald-100' : 'text-amber-600 bg-amber-50 border-amber-100' }} px-2 py-0.5 rounded-md border">
                        {{ $attendanceRate }}%
                    </span>
                </div>
                <p class="text-2xl font-bold text-stone-900">{{ $attendanceRate }}%</p>
                <p class="text-xs text-stone-500 mt-0.5 font-medium">Attendance Today</p>
            </div>

            <div class="p-card p-stat p-5">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">
                        {{ number_format($avgGrade, 1) }}
                    </span>
                </div>
                <p class="text-2xl font-bold text-stone-900">{{ number_format($avgGrade, 1) }}</p>
                <p class="text-xs text-stone-500 mt-0.5 font-medium">Average Grade</p>
            </div>
        </div>

        <!-- Quick Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
                <a href="{{ route('principal.pending-registrations.index') }}" class="p-card p-5 hover:shadow-lg transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-xl group-hover:bg-amber-200 transition-colors">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-900">{{ $pendingRegistrations }}</p>
                            <p class="text-xs text-stone-500 font-medium">Pending Registrations</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('principal.enrollment.index') }}" class="p-card p-5 hover:shadow-lg transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl group-hover:bg-indigo-200 transition-colors">
                            <i class="fas fa-file-signature"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-900">{{ $pendingEnrollments }}</p>
                            <p class="text-xs text-stone-500 font-medium">Online Enrollments</p>
                        </div>
                    </div>
                </a>
                <a href="{{ route('principal.sections.index') }}" class="p-card p-5 hover:shadow-lg transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-200 transition-colors">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-900">{{ $totalSections }}</p>
                            <p class="text-xs text-stone-500 font-medium">Active Sections</p>
                        </div>
                    </div>
                </a>
                <div class="p-card p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl">
                            <i class="fas fa-user-slash"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-stone-900">{{ $absentToday + $lateToday }}</p>
                            <p class="text-xs text-stone-500 font-medium">Absent / Late Today</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-card p-5">
                <h3 class="text-sm font-bold text-stone-900 mb-1">Grade Distribution</h3>
                <p class="text-[10px] text-stone-400 mb-4 uppercase tracking-wide font-semibold">{{ $activeSchoolYearName }}</p>
                <div class="space-y-3">
                    @php $maxCount = !empty($gradeDistribution) ? max($gradeDistribution) : 1; @endphp
                    @forelse($gradeDistribution as $grade => $count)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 text-white flex items-center justify-center font-bold text-xs shadow-sm flex-shrink-0">
                                {{ $grade }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-semibold text-stone-700 text-xs">{{ $grade }}</span>
                                    <span class="font-bold text-stone-900 text-sm">{{ $count }}</span>
                                </div>
                                <div class="h-1.5 bg-stone-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-amber-400 to-orange-500 rounded-full" style="width: {{ ($count / $maxCount) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-stone-400 text-sm text-center py-4">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Pupils -->
        <div class="p-card overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-gradient-to-r from-stone-50 to-white">
                <div>
                    <h3 class="text-sm font-bold text-stone-900">Recent Enrollments</h3>
                    <p class="text-[10px] text-stone-400 uppercase tracking-wide font-semibold mt-0.5">{{ $activeSchoolYearName }}</p>
                </div>
                <a href="{{ route('principal.students.index') }}" class="text-xs font-bold text-amber-600 hover:text-amber-700 bg-amber-50 hover:bg-amber-100 px-3 py-1.5 rounded-lg transition-colors">
                    View All <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Pupil</th>
                            <th>Grade & Section</th>
                            <th>LRN</th>
                            <th>Status</th>
                            <th>Enrolled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentStudents as $student)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=f5f5f4&color=57534e' }}" alt="" class="w-8 h-8 rounded-full border border-stone-200">
                                        <div>
                                            <p class="font-bold text-stone-900 text-sm">{{ $student->full_name }}</p>
                                            <p class="text-[10px] text-stone-400">{{ $student->gender }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-stone-100 text-stone-700 font-semibold text-xs border border-stone-200">
                                        {{ $student->grade_level }}-{{ $student->section->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-mono text-xs text-stone-500 bg-stone-50 px-2 py-0.5 rounded border border-stone-200">{{ $student->lrn ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="p-badge {{ $student->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                                <td class="text-xs text-stone-500">{{ $student->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No recent enrollments</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
@endsection
