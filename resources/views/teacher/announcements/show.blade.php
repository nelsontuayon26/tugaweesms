@php
$teacher = auth()->user()->teacher;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Announcement Details - Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="announcementShow()" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<div class="flex h-screen">
    @include('teacher.includes.sidebar')

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.announcements.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Announcement Details</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Posted {{ $announcement->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('teacher.announcements.edit', $announcement) }}" class="px-4 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('teacher.announcements.pin', $announcement) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium {{ $announcement->pinned ? 'text-amber-600 bg-amber-50' : 'text-slate-600 bg-slate-100' }} rounded-xl hover:bg-opacity-80 transition-colors">
                        <i class="fas fa-thumbtack mr-1"></i> {{ $announcement->pinned ? 'Unpin' : 'Pin' }}
                    </button>
                </form>
                <button @click="confirmDelete()" class="px-4 py-2 text-sm font-medium text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition-colors">
                    <i class="fas fa-trash mr-1"></i> Delete
                </button>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto space-y-6">
                {{-- Main Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    {{-- Priority Banner --}}
                    <div class="px-6 py-3 {{ $announcement->priority === 'urgent' ? 'bg-rose-50 border-b border-rose-100' : ($announcement->priority === 'important' ? 'bg-amber-50 border-b border-amber-100' : 'bg-slate-50 border-b border-slate-100') }}">
                        <div class="flex items-center gap-2">
                            <i class="fas {{ $announcement->priorityIcon() }} {{ $announcement->priority === 'urgent' ? 'text-rose-500' : ($announcement->priority === 'important' ? 'text-amber-500' : 'text-slate-500') }}"></i>
                            <span class="text-sm font-semibold {{ $announcement->priority === 'urgent' ? 'text-rose-700' : ($announcement->priority === 'important' ? 'text-amber-700' : 'text-slate-700') }}">
                                {{ ucfirst($announcement->priority) }} Priority
                            </span>
                            @if($announcement->pinned)
                                <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full">
                                    <i class="fas fa-thumbtack mr-1"></i>Pinned
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ $announcement->title }}</h2>
                        
                        <div class="prose prose-slate max-w-none text-slate-700 whitespace-pre-wrap">{{ $announcement->message }}</div>

                        {{-- Attachments --}}
                        @if($announcement->attachments->count() > 0)
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <h4 class="text-sm font-semibold text-slate-700 mb-3">Attachments ({{ $announcement->attachments->count() }})</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($announcement->attachments as $att)
                                        @if($att->isImage())
                                            <div @click="openLightbox('{{ $att->url() }}')" class="relative w-24 h-24 rounded-xl overflow-hidden cursor-pointer group border border-slate-200 shadow-sm hover:shadow-md transition-all">
                                                <img src="{{ $att->url() }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors flex items-center justify-center">
                                                    <i class="fas fa-expand text-white opacity-0 group-hover:opacity-100 transition-opacity drop-shadow-lg"></i>
                                                </div>
                                                <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/60 to-transparent p-1.5">
                                                    <p class="text-[10px] text-white truncate">{{ $att->file_name }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ $att->url() }}" download 
                                               class="flex items-center gap-2 px-4 py-3 bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 rounded-xl text-sm text-slate-700 hover:text-indigo-700 transition-all">
                                                <i class="fas fa-file text-slate-400"></i>
                                                <span class="truncate max-w-[150px]">{{ $att->file_name }}</span>
                                                <i class="fas fa-download text-xs opacity-50"></i>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-semibold text-slate-900">Read Statistics</h3>
                    </div>
                    <div class="p-6">
                        @if(!empty($readStats))
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center p-4 bg-slate-50 rounded-xl">
                                    <p class="text-2xl font-bold text-slate-900">{{ $readStats['total'] }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Total Recipients</p>
                                </div>
                                <div class="text-center p-4 bg-emerald-50 rounded-xl">
                                    <p class="text-2xl font-bold text-emerald-700">{{ $readStats['read'] }}</p>
                                    <p class="text-xs text-emerald-600 mt-1">Read</p>
                                </div>
                                <div class="text-center p-4 bg-rose-50 rounded-xl">
                                    <p class="text-2xl font-bold text-rose-700">{{ $readStats['unread'] }}</p>
                                    <p class="text-xs text-rose-600 mt-1">Unread</p>
                                </div>
                            </div>

                            @php $readPercent = $readStats['total'] > 0 ? round(($readStats['read'] / $readStats['total']) * 100) : 0; @endphp
                            <div class="mt-4">
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-slate-600">Read Rate</span>
                                    <span class="font-semibold text-slate-900">{{ $readPercent }}%</span>
                                </div>
                                <div class="h-3 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full transition-all duration-500" style="width: {{ $readPercent }}%"></div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-400">
                                <i class="fas fa-chart-bar text-3xl mb-2"></i>
                                <p class="text-sm">Read statistics will appear here.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Meta Info --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100">
                        <h3 class="font-semibold text-slate-900">Details</h3>
                    </div>
                    <div class="p-6 space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Target</span>
                            <span class="font-medium text-slate-900 capitalize">Pupils</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-slate-500">Posted On</span>
                            <span class="font-medium text-slate-900">{{ $announcement->created_at->format('M d, Y g:i A') }}</span>
                        </div>
                        @if($announcement->expires_at)
                            <div class="flex justify-between">
                                <span class="text-slate-500">Expires</span>
                                <span class="font-medium {{ $announcement->expires_at->isPast() ? 'text-rose-600' : 'text-slate-900' }}">{{ $announcement->expires_at->format('M d, Y g:i A') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-slate-500">School Year</span>
                            <span class="font-medium text-slate-900">{{ $announcement->schoolYear->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
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
<form x-ref="deleteForm" action="{{ route('teacher.announcements.destroy', $announcement) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function announcementShow() {
    return {
        mobileOpen: false,
        deleteModalOpen: false,
        deleteCountdown: 3,
        deleteCountdownInterval: null,
        lightboxOpen: false,
        lightboxImage: '',

        confirmDelete() {
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
            if (this.deleteCountdownInterval) {
                clearInterval(this.deleteCountdownInterval);
                this.deleteCountdownInterval = null;
            }
        },

        executeDelete() {
            if (this.deleteCountdown > 0) return;
            const form = this.$refs.deleteForm;
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
