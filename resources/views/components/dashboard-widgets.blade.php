<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Pending Tasks Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-tasks text-indigo-500"></i>
            Pending Tasks
        </h3>
        <div class="space-y-3">
            @foreach($pendingTasks as $task)
                <a href="{{ $task['route'] }}" class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-{{ $task['color'] }}-50 transition-colors group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-{{ $task['color'] }}-100 flex items-center justify-center">
                            <i class="fas {{ $task['icon'] }} text-{{ $task['color'] }}-600"></i>
                        </div>
                        <span class="font-medium text-slate-700 group-hover:text-{{ $task['color'] }}-700">{{ $task['title'] }}</span>
                    </div>
                    @if($task['count'] > 0)
                        <span class="px-3 py-1 bg-{{ $task['color'] }}-100 text-{{ $task['color'] }}-700 rounded-full text-sm font-bold">
                            {{ $task['count'] }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-history text-blue-500"></i>
            Recent Activity
        </h3>
        <div class="space-y-3 max-h-64 overflow-y-auto">
            @forelse($recentActivity as $activity)
                <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50">
                    <div class="w-8 h-8 rounded-full bg-{{ $activity->action_color }}-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $activity->action_icon }} text-{{ $activity->action_color }}-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-slate-700 truncate">{{ $activity->description }}</p>
                        <p class="text-xs text-slate-400">{{ $activity->user?->name ?? 'System' }} • {{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <p class="text-slate-400 text-sm text-center py-4">No recent activity</p>
            @endforelse
        </div>
        <a href="{{ route('admin.activity-logs.index') }}" class="block text-center text-sm text-indigo-600 hover:text-indigo-700 mt-3 pt-3 border-t border-slate-100">
            View All Activity
        </a>
    </div>

    <!-- Today's Birthdays Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5">
        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fas fa-birthday-cake text-rose-500"></i>
            Today's Birthdays
            <span class="text-xs font-normal text-slate-400">{{ now()->format('M d') }}</span>
        </h3>
        <div class="space-y-3">
            @forelse($todaysBirthdays as $student)
                <div class="flex items-center gap-3 p-2 rounded-lg hover:bg-rose-50">
                    <img src="{{ $student->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($student->full_name) . '&background=random' }}" 
                         alt="" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-700 truncate">{{ $student->full_name }}</p>
                        <p class="text-xs text-slate-400">{{ $student->gradeLevel?->name ?? 'N/A' }}</p>
                    </div>
                </div>
            @empty
                <div class="text-center py-6">
                    <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-calendar text-slate-400"></i>
                    </div>
                    <p class="text-slate-400 text-sm">No birthdays today</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
