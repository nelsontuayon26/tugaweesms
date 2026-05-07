@php
$teacher = auth()->user()->teacher;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Received Announcements - Teacher Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="{ mobileOpen: false }" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<div class="flex h-screen">
    @include('teacher.includes.sidebar')

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Received Announcements</h1>
                <p class="text-sm text-slate-500 mt-0.5">Announcements addressed to you</p>
            </div>
        </div>

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
                    <p class="text-sm text-slate-400 mt-1 max-w-sm">When admin or other teachers send you an announcement, it will appear here.</p>
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
                                                {{ $announcement->target === 'all' ? 'Teachers & Pupils' : ($announcement->target === 'students' ? 'Pupils' : 'Teachers') }}
                                            </span>
                                        </div>

                                        <h3 class="font-semibold text-slate-900 mb-1">{{ $announcement->title }}</h3>
                                        <p class="text-sm text-slate-500 line-clamp-2">{{ strip_tags($announcement->message) }}</p>

                                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-user"></i>
                                                {{ $announcement->author?->name ?? 'Unknown' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-clock"></i>
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </span>
                                            @if($announcement->attachments->count() > 0)
                                                <span class="flex items-center gap-1">
                                                    <i class="fas fa-paperclip"></i>
                                                    {{ $announcement->attachments->count() }} attachment{{ $announcement->attachments->count() > 1 ? 's' : '' }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Message Preview --}}
                            <div class="px-5 pb-5">
                                <div class="prose prose-sm max-w-none text-slate-600 bg-slate-50 rounded-lg p-4">
                                    {{ $announcement->message }}
                                </div>
                                @if($announcement->attachments->count() > 0)
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        @foreach($announcement->attachments as $att)
                                            <a href="{{ $att->url() }}" target="_blank" class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-xs text-slate-600 hover:bg-slate-200 transition-colors border border-slate-200">
                                                <i class="fas fa-file"></i>
                                                <span class="truncate max-w-[150px]">{{ $att->file_name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

</body>
</html>
