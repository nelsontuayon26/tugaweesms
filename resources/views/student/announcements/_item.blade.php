@php
$isUnread = !$announcement->isReadBy(auth()->id());
@endphp
<a href="{{ route('student.announcements.show', $announcement) }}" 
   class="block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all {{ $isUnread ? 'announcement-unread border-l-4 border-indigo-500 bg-indigo-50/30' : '' }}">
    <div class="p-5">
        <div class="flex items-start gap-4">
            {{-- Priority Icon --}}
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                {{ $announcement->priority === 'urgent' ? 'bg-rose-100 text-rose-600' : ($announcement->priority === 'important' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500') }}">
                <i class="fas {{ $announcement->priorityIcon() }}"></i>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    @if($announcement->pinned)
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                            <i class="fas fa-thumbtack mr-1"></i>Pinned
                        </span>
                    @endif
                    @if($isUnread)
                        <span class="unread-badge px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-wide">New</span>
                    @endif
                    <span class="px-2 py-0.5 bg-{{ $announcement->priorityColor() }}-100 text-{{ $announcement->priorityColor() }}-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                        {{ $announcement->priority }}
                    </span>
                </div>

                <h3 class="font-semibold text-slate-900 {{ $isUnread ? 'text-slate-900' : '' }}">{{ $announcement->title }}</h3>
                <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ Str::limit($announcement->message, 150) }}</p>

                <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                    <span class="flex items-center gap-1">
                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-[9px] font-bold">
                            {{ strtoupper(substr($announcement->author?->first_name ?? 'A', 0, 1)) }}
                        </div>
                        {{ $announcement->author?->full_name ?? 'Admin' }}
                    </span>
                    <span><i class="far fa-clock mr-1"></i>{{ $announcement->created_at->diffForHumans() }}</span>
                    @if($announcement->attachments->count() > 0)
                        <span><i class="fas fa-paperclip mr-1"></i>{{ $announcement->attachments->count() }}</span>
                    @endif
                </div>
            </div>

            <div class="shrink-0">
                <i class="fas fa-chevron-right text-slate-300"></i>
            </div>
        </div>
    </div>
</a>
