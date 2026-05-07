@extends('layouts.principal')

@section('title', 'Sections Overview')

@push('styles')
<style>
    .p-card { background: white; border: 1px solid #e7e5e4; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.02); }
    .p-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #78716c; padding: 14px 20px; background: #fafaf9; border-bottom: 1px solid #e7e5e4; }
    .p-table td { padding: 14px 20px; border-bottom: 1px solid #f5f5f4; font-size: 0.875rem; color: #44403c; }
    .p-table tbody tr:hover td { background: #fafaf9; }
    .page-link { padding: 8px 14px; border-radius: 10px; font-size: 0.875rem; font-weight: 500; color: #57534e; background: white; border: 1px solid #e7e5e4; transition: all 0.2s; }
    .page-link:hover { background: #ecfdf5; border-color: #10b981; color: #065f46; }
    .page-link.active { background: linear-gradient(135deg, #10b981, #059669); color: white; border-color: transparent; }
</style>
@endpush

@section('content')
    <!-- Header -->
    <header class="principal-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-stone-900 tracking-tight">Sections Overview</h1>
                    <p class="text-xs text-stone-500 mt-0.5">View all class sections</p>
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
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Total Sections</p>
                        <p class="text-3xl font-bold text-stone-900">{{ $sections->total() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg">
                        <i class="fas fa-th-large"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">School Year</p>
                        <p class="text-xl font-bold text-stone-900">{{ $selectedSchoolYear?->name ?? $activeSchoolYear?->name ?? 'N/A' }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Total Enrolled</p>
                        <p class="text-3xl font-bold text-stone-900">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-card overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-gradient-to-r from-stone-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-th-large text-emerald-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-900 text-sm">Section List</h2>
                        <p class="text-[10px] text-stone-400 uppercase tracking-wide font-semibold">Overview of all class sections</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Grade Level</th>
                            <th>Teacher</th>
                            <th>Room</th>
                            <th>School Year</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ strtoupper(substr($section->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-stone-900 text-sm">{{ $section->name }}</p>
                                            <p class="text-[10px] text-stone-400">{{ $section->students_count ?? 0 }} pupils</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold text-xs border border-emerald-100">
                                        {{ $section->gradeLevel?->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600">
                                        {{ $section->teacher?->first_name }} {{ $section->teacher?->last_name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="font-mono text-xs text-stone-500 bg-stone-50 px-2 py-0.5 rounded border border-stone-200">{{ $section->room_number ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600">{{ $section->schoolYear?->name ?? 'N/A' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No sections found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($sections->hasPages())
                <div class="px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-stone-500">Showing <strong class="text-stone-900">{{ $sections->firstItem() ?? 0 }}-{{ $sections->lastItem() ?? 0 }}</strong> of <strong class="text-stone-900">{{ $sections->total() }}</strong></p>
                        <div class="flex items-center gap-2">
                            @if($sections->onFirstPage())
                                <span class="page-link opacity-50 cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $sections->previousPageUrl() }}" class="page-link">Previous</a>
                            @endif
                            @foreach($sections->getUrlRange(1, $sections->lastPage()) as $page => $url)
                                @if($page == $sections->currentPage())
                                    <span class="page-link active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="page-link">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if($sections->hasMorePages())
                                <a href="{{ $sections->nextPageUrl() }}" class="page-link">Next</a>
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
