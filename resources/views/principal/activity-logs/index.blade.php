@extends('layouts.principal')

@section('title', 'Activity Logs')

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
                    <h1 class="text-lg font-bold text-stone-900 tracking-tight">Activity Logs</h1>
                    <p class="text-xs text-stone-500 mt-0.5">Audit trail of all system activity</p>
                </div>
            </div>
            <span class="px-3 py-1.5 bg-stone-100 text-stone-600 rounded-lg text-xs font-bold border border-stone-200">
                <i class="fas fa-eye mr-1"></i> Read-Only
            </span>
        </div>
    </header>

    <main class="principal-content">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 flex items-center gap-3">
                <i class="fas fa-exclamation-circle text-rose-600"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas fa-exclamation-triangle text-amber-600"></i>
                    <span class="font-semibold">Please fix the following:</span>
                </div>
                <ul class="list-disc list-inside text-sm ml-6">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
            <div class="p-card p-4">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-wide">Total (24h)</p>
                <p class="text-2xl font-bold text-stone-900">{{ $stats['total'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-emerald-400">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Created</p>
                <p class="text-2xl font-bold text-emerald-700">{{ $stats['created'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-blue-400">
                <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wide">Updated</p>
                <p class="text-2xl font-bold text-blue-700">{{ $stats['updated'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-rose-400">
                <p class="text-[10px] font-bold text-rose-600 uppercase tracking-wide">Deleted</p>
                <p class="text-2xl font-bold text-rose-700">{{ $stats['deleted'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-emerald-400">
                <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wide">Approved</p>
                <p class="text-2xl font-bold text-emerald-700">{{ $stats['approved'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-red-400">
                <p class="text-[10px] font-bold text-red-600 uppercase tracking-wide">Rejected</p>
                <p class="text-2xl font-bold text-red-700">{{ $stats['rejected'] }}</p>
            </div>
            <div class="p-card p-4 border-l-4 border-indigo-400">
                <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-wide">Logins</p>
                <p class="text-2xl font-bold text-indigo-700">{{ $stats['logins'] }}</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="p-card p-5 mb-6">
            <form method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Search logs..."
                               class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-400 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Action</label>
                        <select name="action" class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm bg-white">
                            <option value="all">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">Entity</label>
                        <select name="entity_type" class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm bg-white">
                            <option value="all">All Entities</option>
                            @foreach($entityTypes as $type)
                                <option value="{{ $type }}" {{ request('entity_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                               class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wide mb-1">To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                               class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('principal.activity-logs.index') }}" class="px-4 py-2 border border-stone-200 text-stone-600 rounded-lg hover:bg-stone-50 transition-colors text-sm font-semibold">
                        <i class="fas fa-undo mr-2"></i>Reset
                    </a>
                    <a href="{{ route('principal.activity-logs.export', request()->query()) }}" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-semibold">
                        <i class="fas fa-download mr-2"></i>Export
                    </a>
                    <button type="button" onclick="document.getElementById('clearLogsModal').classList.remove('hidden')" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors text-sm font-semibold ml-auto">
                        <i class="fas fa-trash-alt mr-2"></i>Clear Old Logs
                    </button>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="p-card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>Description</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <span class="text-sm text-stone-600">{{ $log->created_at->format('M d, Y') }}</span>
                                    <span class="text-stone-400 text-xs">{{ $log->created_at->format('h:i A') }}</span>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-xs">
                                            {{ substr($log->user?->first_name ?? 'S', 0, 1) }}{{ substr($log->user?->last_name ?? 'Y', 0, 1) }}
                                        </div>
                                        <span class="text-sm text-stone-700">{{ $log->user?->name ?? 'System' }}</span>
                                    </div>
                                </td>
                                <td>
                                    @php $color = $log->action_color; @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-700">
                                        <i class="fas {{ $log->action_icon }}"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="text-sm text-stone-600">
                                    {{ $log->entity_type }}
                                    @if($log->entity_id)
                                        <span class="text-stone-400">#{{ $log->entity_id }}</span>
                                    @endif
                                </td>
                                <td class="text-sm text-stone-700 max-w-md truncate" title="{{ $log->description }}">
                                    {{ $log->description }}
                                </td>
                                <td class="text-sm text-stone-500 font-mono">
                                    {{ $log->ip_address ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No activity logs found</p>
                                    <p class="text-stone-400 text-xs mt-1">Try adjusting your filters</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Clear Logs Modal -->
    <div id="clearLogsModal" class="fixed inset-0 z-50 hidden">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('clearLogsModal').classList.add('hidden')"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Clear Old Logs?</h3>
                    <p class="text-slate-600 mb-4">This will permanently delete activity logs older than the specified number of days.</p>

                    <form action="{{ route('principal.activity-logs.clear') }}" method="POST">
                        @csrf
                        <div class="mb-4 text-left">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Delete logs older than (days)</label>
                            <input type="number" name="days" value="30" min="1" max="365"
                                   class="w-full px-3 py-2 border border-stone-200 rounded-lg focus:ring-2 focus:ring-rose-500">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('clearLogsModal').classList.add('hidden')"
                                    class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2 bg-rose-600 text-white rounded-xl font-semibold hover:bg-rose-700 transition-colors">
                                Clear Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
