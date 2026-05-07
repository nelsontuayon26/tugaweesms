@php
$user = auth()->user();
$student = $user->student;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }} - Announcement</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="{ mobileOpen: false, sidebarCollapsed: false, lightboxOpen: false, lightboxImage: '' }" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
     style="display: none;">
</div>

<div class="flex h-screen">
    @include('student.includes.sidebar')

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center gap-3 shrink-0">
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all mr-1">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <a href="{{ route('student.announcements') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900">Announcement</h1>
                <p class="text-xs text-slate-500">From {{ $announcement->author?->full_name ?? 'School Admin' }}</p>
            </div>
        </div>

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto">
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
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white font-bold text-sm">
                                {{ strtoupper(substr($announcement->author?->first_name ?? 'A', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-900">{{ $announcement->author?->full_name ?? 'School Admin' }}</p>
                                <p class="text-xs text-slate-400">{{ $announcement->created_at->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-slate-900 mb-4">{{ $announcement->title }}</h2>
                        
                        <div class="prose prose-slate max-w-none text-slate-700 whitespace-pre-wrap">{{ $announcement->message }}</div>

                        {{-- Attachments --}}
                        @if($announcement->attachments->count() > 0)
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <h4 class="text-sm font-semibold text-slate-700 mb-3">Attachments</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($announcement->attachments as $att)
                                        @if($att->isImage())
                                            <div @click="lightboxImage = '{{ $att->url() }}'; lightboxOpen = true" class="relative w-24 h-24 rounded-xl overflow-hidden cursor-pointer group border border-slate-200 shadow-sm hover:shadow-md transition-all">
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

                {{-- Back button --}}
                <div class="mt-6 text-center">
                    <a href="{{ route('student.announcements') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                        <i class="fas fa-arrow-left"></i> Back to Announcements
                    </a>
                </div>
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

</body>
</html>
