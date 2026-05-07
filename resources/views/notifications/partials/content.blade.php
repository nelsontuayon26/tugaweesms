<div class="max-w-5xl mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Total</p>
                    <p class="text-xl font-bold text-slate-800">{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Unread</p>
                    <p class="text-xl font-bold text-slate-800">{{ $unreadCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Read</p>
                    <p class="text-xl font-bold text-slate-800">{{ $total - $unreadCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800">All Notifications</h3>
        @if($unreadCount > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                    Mark all as read
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        @forelse($notifications as $notification)
            <div class="flex items-start gap-4 px-5 py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors {{ $notification->read_at ? 'opacity-70' : 'bg-indigo-50/20' }}">
                <div class="w-2.5 h-2.5 mt-2 rounded-full flex-shrink-0 {{ $notification->read_at ? 'bg-slate-300' : 'bg-indigo-500' }}"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-slate-800">{{ $notification->title }}</p>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-wide
                            {{ match($notification->type ?? 'message') {
                                'event' => 'bg-purple-100 text-purple-700',
                                'announcement' => 'bg-amber-100 text-amber-700',
                                'grade' => 'bg-emerald-100 text-emerald-700',
                                'attendance' => 'bg-rose-100 text-rose-700',
                                default => 'bg-indigo-100 text-indigo-700',
                            } }}">
                            {{ $notification->type }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mt-0.5">{{ $notification->body }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                        @if($notification->data['url'] ?? false)
                            <a href="{{ $notification->data['url'] }}" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View details &rarr;</a>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    @if(!$notification->read_at)
                        <form action="{{ route('notifications.mark-read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                Mark read
                            </button>
                        </form>
                    @endif
                    @if($notification->is_real ?? false)
                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" onsubmit="return confirm('Delete this notification?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors">
                                Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-10 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-1">No notifications</h3>
                <p class="text-slate-500 text-sm">You don't have any notifications at the moment.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links('pagination::tailwind') }}
        </div>
    @endif
</div>
