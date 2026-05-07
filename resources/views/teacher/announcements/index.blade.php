@php
$teacher = auth()->user()->teacher;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Announcements - Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="announcementList()" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<div class="flex h-screen">
    @include('teacher.includes.sidebar')

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-900">My Announcements</h1>
                <p class="text-sm text-slate-500 mt-0.5">Manage announcements you've posted</p>
            </div>
            <a href="{{ route('teacher.announcements.create') }}" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg shadow-indigo-500/30">
                <i class="fas fa-plus"></i> New Announcement
            </a>
        </div>

        {{-- Stats Bar --}}


        {{-- Announcements List --}}
        <div class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($announcements->isEmpty())
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bullhorn text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700">No announcements yet</h3>
                    <p class="text-sm text-slate-400 mt-1 max-w-sm">Create your first announcement to reach your pupils instantly.</p>
                    <a href="{{ route('teacher.announcements.create') }}" class="mt-4 px-5 py-2.5 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-plus mr-1"></i> Create Announcement
                    </a>
                </div>
            @else
                <div class="space-y-4 max-w-4xl mx-auto">
                    @foreach($announcements as $announcement)
                        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-shadow {{ $announcement->pinned ? 'ring-1 ring-amber-200' : '' }}">
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
                                            <span class="px-2 py-0.5 bg-{{ $announcement->priorityColor() }}-100 text-{{ $announcement->priorityColor() }}-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                {{ $announcement->priority }}
                                            </span>
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                Pupils
                                            </span>
                                            @if($announcement->sections->count() > 0)
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] rounded-full" title="{{ $announcement->sections->pluck('name')->join(', ') }}">
                                                    {{ $announcement->sections->count() }} section{{ $announcement->sections->count() > 1 ? 's' : '' }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="font-semibold text-slate-900 truncate">{{ $announcement->title }}</h3>
                                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ Str::limit($announcement->message, 150) }}</p>

                                        {{-- Attachment Previews --}}
                                        @if($announcement->attachments->count() > 0)
                                            <div class="flex flex-wrap gap-2 mt-3">
                                                @foreach($announcement->attachments->take(3) as $att)
                                                    @if($att->isImage())
                                                        <div @click="openLightbox('{{ $att->url() }}')" class="relative w-16 h-16 rounded-lg overflow-hidden cursor-pointer group border border-slate-200">
                                                            <img src="{{ $att->url() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                                                <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 text-xs"></i>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <a href="{{ $att->url() }}" download class="flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-600 hover:bg-slate-100 transition-colors">
                                                            <i class="fas fa-file text-slate-400"></i>
                                                            <span class="truncate max-w-[80px]">{{ $att->file_name }}</span>
                                                        </a>
                                                    @endif
                                                @endforeach
                                                @if($announcement->attachments->count() > 3)
                                                    <span class="flex items-center px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-500">
                                                        +{{ $announcement->attachments->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>{{ $announcement->created_at->diffForHumans() }}</span>
                                            <span><i class="far fa-eye mr-1"></i>{{ $announcement->reads_count }} read</span>
                                            @if($announcement->expires_at && $announcement->expires_at->isPast())
                                                <span class="text-rose-500"><i class="fas fa-history mr-1"></i>Expired</span>
                                            @elseif($announcement->expires_at)
                                                <span><i class="fas fa-hourglass-half mr-1"></i>Expires {{ $announcement->expires_at->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="{{ route('teacher.announcements.show', $announcement) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher.announcements.edit', $announcement) }}" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher.announcements.pin', $announcement) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 {{ $announcement->pinned ? 'text-amber-500 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-amber-500 hover:bg-amber-50' }} rounded-lg transition-colors" title="{{ $announcement->pinned ? 'Unpin' : 'Pin' }}">
                                                <i class="fas fa-thumbtack"></i>
                                            </button>
                                        </form>
                                        <button @click="confirmDelete({{ $announcement->id }})" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 max-w-4xl mx-auto">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal with Countdown --}}
<div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all" x-transition.scale>
        <div class="text-center">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Announcement?</h3>
            <p class="text-slate-500 text-sm mb-6">This action cannot be undone. The announcement and all its attachments will be permanently removed.</p>
            
            <div class="flex gap-3">
                <button @click="cancelDelete()" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button @click="executeDelete()" :disabled="deleteCountdown > 0" class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                    <template x-if="deleteCountdown > 0">
                        <span class="flex items-center gap-1.5">
                            <i class="fas fa-clock text-sm"></i>
                            <span x-text="deleteCountdown + 's'"></span>
                        </span>
                    </template>
                    <span x-show="deleteCountdown === 0">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox Modal for Images --}}
<div x-show="lightboxOpen" x-cloak @keydown.escape.window="lightboxOpen = false" class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90" x-transition.opacity>
    <button @click="lightboxOpen = false" class="absolute top-4 right-4 p-3 text-white/70 hover:text-white transition-colors z-10">
        <i class="fas fa-times text-2xl"></i>
    </button>
    <a :href="lightboxImage" download class="absolute top-4 left-4 p-3 text-white/70 hover:text-white transition-colors z-10" title="Download image">
        <i class="fas fa-download text-xl"></i>
    </a>
    <img :src="lightboxImage" class="max-w-[95vw] max-h-[90vh] object-contain rounded-lg shadow-2xl" @click.stop>
</div>

{{-- Hidden delete form --}}
<form x-ref="deleteForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function announcementList() {
    return {
        mobileOpen: false,
        deleteModalOpen: false,
        deleteAnnouncementId: null,
        deleteCountdown: 3,
        deleteCountdownInterval: null,
        lightboxOpen: false,
        lightboxImage: '',

        confirmDelete(id) {
            this.deleteAnnouncementId = id;
            this.deleteModalOpen = true;
            this.deleteCountdown = 3;
            
            this.deleteCountdownInterval = setInterval(() => {
                this.deleteCountdown--;
                if (this.deleteCountdown <= 0) {
                    clearInterval(this.deleteCountdownInterval);
                    this.deleteCountdownInterval = null;
                }
            }, 1000);
        },

        cancelDelete() {
            this.deleteModalOpen = false;
            this.deleteAnnouncementId = null;
            if (this.deleteCountdownInterval) {
                clearInterval(this.deleteCountdownInterval);
                this.deleteCountdownInterval = null;
            }
        },

        executeDelete() {
            if (!this.deleteAnnouncementId || this.deleteCountdown > 0) return;
            
            const form = this.$refs.deleteForm;
            form.setAttribute('action', '{{ url('/teacher/announcements') }}/' + this.deleteAnnouncementId);
            form.submit();
        },

        openLightbox(url) {
            this.lightboxImage = url;
            this.lightboxOpen = true;
        }
    }
}
</script>
</body>
</html>
