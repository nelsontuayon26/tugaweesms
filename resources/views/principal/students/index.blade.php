@extends('layouts.principal')

@section('title', 'Pupils Directory')

@push('styles')
<style>
    .p-card { background: white; border: 1px solid #e7e5e4; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.02); }
    .p-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #78716c; padding: 14px 20px; background: #fafaf9; border-bottom: 1px solid #e7e5e4; }
    .p-table td { padding: 14px 20px; border-bottom: 1px solid #f5f5f4; font-size: 0.875rem; color: #44403c; }
    .p-table tbody tr:hover td { background: #fafaf9; }
    .page-link { padding: 8px 14px; border-radius: 10px; font-size: 0.875rem; font-weight: 500; color: #57534e; background: white; border: 1px solid #e7e5e4; transition: all 0.2s; }
    .page-link:hover { background: #fffbeb; border-color: #fbbf24; color: #92400e; }
    .page-link.active { background: linear-gradient(135deg, #f59e0b, #ea580c); color: white; border-color: transparent; }
</style>
@endpush

@section('content')
    <!-- Header -->
    <header class="principal-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-stone-900 tracking-tight">Pupils Directory</h1>
                    <p class="text-xs text-stone-500 mt-0.5">View all enrolled pupils</p>
                </div>
            </div>
            <span class="px-3 py-1.5 bg-stone-100 text-stone-600 rounded-lg text-xs font-bold border border-stone-200">
                <i class="fas fa-eye mr-1"></i> Read-Only
            </span>
        </div>
    </header>

    <main class="principal-content">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Total Pupils</p>
                        <p class="text-3xl font-bold text-stone-900">{{ $students->total() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">School Year</p>
                        <p class="text-xl font-bold text-stone-900">{{ $schoolYear?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Showing</p>
                        <p class="text-xl font-bold text-stone-900">{{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg">
                        <i class="fas fa-list-ol"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-card overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-gradient-to-r from-stone-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-900 text-sm">Pupil List</h2>
                        <p class="text-[10px] text-stone-400 uppercase tracking-wide font-semibold">Overview of all enrolled pupils</p>
                    </div>
                </div>
                <form method="GET" action="{{ route('principal.students.index') }}" class="flex items-center gap-2">
                    @if(request('school_year_id'))
                        <input type="hidden" name="school_year_id" value="{{ request('school_year_id') }}">
                    @endif
                    <select name="grade" onchange="this.form.submit()"
                            class="text-xs font-medium text-stone-600 bg-white border border-stone-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 cursor-pointer">
                        <option value="">All Grades</option>
                        @foreach($gradeLevels as $gl)
                            <option value="{{ $gl->name }}" {{ request('grade') == $gl->name ? 'selected' : '' }}>{{ $gl->name }}</option>
                        @endforeach
                    </select>
                    @if(request('grade'))
                        <a href="{{ route('principal.students.index', request()->except('grade')) }}"
                           class="text-xs text-stone-400 hover:text-red-500 transition-colors px-2 py-1">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Pupil</th>
                            <th>LRN</th>
                            <th>Grade & Section</th>
                            <th>Gender</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="{{ profile_photo_url($student->user->photo) ?? 'https://ui-avatars.com/api/?name=' . urlencode(($student->user->first_name ?? $student->first_name) . '+' . ($student->user->last_name ?? $student->last_name)) . '&background=f5f5f4&color=57534e' }}"
                                             alt="" class="w-9 h-9 rounded-full border border-stone-200 object-cover">
                                        <div>
                                            <a href="{{ route('principal.students.show', $student) }}" class="font-bold text-stone-900 text-sm hover:text-amber-600 transition-colors">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </a>
                                            <p class="text-[10px] text-stone-400">{{ $student->user->email ?? 'No email' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-mono text-xs text-stone-500 bg-stone-50 px-2 py-0.5 rounded border border-stone-200">{{ $student->lrn ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    @php
                                        $enrollment = $student->enrollments->first();
                                        $section = $enrollment?->section;
                                        $grade = $section?->gradeLevel?->name ?? $student->gradeLevel?->name ?? 'N/A';
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100">
                                        {{ $grade }} - {{ $section?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600">{{ $student->gender }}</span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $student->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        {{ ucfirst($student->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No pupils found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-stone-500">Showing <strong class="text-stone-900">{{ $students->firstItem() ?? 0 }}-{{ $students->lastItem() ?? 0 }}</strong> of <strong class="text-stone-900">{{ $students->total() }}</strong></p>
                        <div class="flex items-center gap-2">
                            @if($students->onFirstPage())
                                <span class="page-link opacity-50 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $students->previousPageUrl() }}" class="page-link">Previous</a>
                            @endif
                            @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                                @if($page == $students->currentPage())
                                    <span class="page-link active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if($students->hasMorePages())
                                <a href="{{ $students->nextPageUrl() }}" class="page-link">Next</a>
                            @else
                                <span class="page-link opacity-50 cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
