<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5" x-data="{ showAll: false }">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-bold text-slate-900 flex items-center gap-2">
            <i class="fas fa-calendar-check text-emerald-500"></i>
            Today's Attendance
            <span class="text-sm font-normal text-slate-400">{{ now()->format('M d, Y') }}</span>
        </h3>
        <a href="{{ route('teacher.attendance.create', $section) }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
            Take Attendance →
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-4 gap-3 mb-4">
        <div class="bg-emerald-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-emerald-700">{{ $stats['present'] }}</p>
            <p class="text-xs text-emerald-600">Present</p>
        </div>
        <div class="bg-rose-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-rose-700">{{ $stats['absent'] }}</p>
            <p class="text-xs text-rose-600">Absent</p>
        </div>
        <div class="bg-amber-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-amber-700">{{ $stats['late'] }}</p>
            <p class="text-xs text-amber-600">Late</p>
        </div>
        <div class="bg-slate-50 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-slate-700">{{ $stats['unmarked'] }}</p>
            <p class="text-xs text-slate-600">Unmarked</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="mb-4">
        <div class="flex items-center justify-between text-sm mb-1">
            <span class="text-slate-600">Attendance Rate</span>
            <span class="font-semibold text-slate-800">{{ $stats['present_pct'] }}%</span>
        </div>
        <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
            <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $stats['present_pct'] }}%"></div>
        </div>
    </div>

    <!-- Quick Actions -->
    @if($stats['unmarked'] > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-exclamation-circle text-amber-600"></i>
                    <span class="text-sm text-amber-800">
                        {{ $stats['unmarked'] }} student(s) not marked
                    </span>
                </div>
                <form action="{{ route('teacher.attendance.mark-all', $section) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="present">
                    <button type="submit" class="text-xs px-3 py-1 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Mark All Present
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Student List -->
    <div class="space-y-2 max-h-64 overflow-y-auto">
        @foreach($attendanceData->take(5) as $data)
            <div class="flex items-center justify-between p-2 rounded-lg {{ $data['status'] ? 'bg-slate-50' : 'bg-amber-50/50' }}">
                <div class="flex items-center gap-2">
                    <img src="{{ $data['student']->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($data['student']->full_name) . '&background=random' }}" 
                         alt="" class="w-8 h-8 rounded-full">
                    <span class="text-sm font-medium text-slate-700">{{ $data['student']->full_name }}</span>
                </div>
                @if($data['status'])
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $data['status'] === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                        {{ $data['status'] === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}
                        {{ $data['status'] === 'late' ? 'bg-amber-100 text-amber-700' : '' }}">
                        {{ ucfirst($data['status']) }}
                    </span>
                @else
                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                        -
                    </span>
                @endif
            </div>
        @endforeach
        
        @if($attendanceData->count() > 5)
            <button @click="showAll = !showAll" class="w-full text-center text-sm text-indigo-600 hover:text-indigo-700 py-2">
                <span x-show="!showAll">Show all {{ $attendanceData->count() }} students</span>
                <span x-show="showAll">Show less</span>
            </button>
            
            <div x-show="showAll" class="space-y-2">
                @foreach($attendanceData->skip(5) as $data)
                    <div class="flex items-center justify-between p-2 rounded-lg {{ $data['status'] ? 'bg-slate-50' : 'bg-amber-50/50' }}">
                        <div class="flex items-center gap-2">
                            <img src="{{ $data['student']->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($data['student']->full_name) . '&background=random' }}" 
                                 alt="" class="w-8 h-8 rounded-full">
                            <span class="text-sm font-medium text-slate-700">{{ $data['student']->full_name }}</span>
                        </div>
                        @if($data['status'])
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $data['status'] === 'present' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                {{ $data['status'] === 'absent' ? 'bg-rose-100 text-rose-700' : '' }}
                                {{ $data['status'] === 'late' ? 'bg-amber-100 text-amber-700' : '' }}">
                                {{ ucfirst($data['status']) }}
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-slate-200 text-slate-600">
                                -
                            </span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
