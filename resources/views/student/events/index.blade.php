@php
$user = auth()->user();
$student = $user->student;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>School Events - Pupil Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          init() {
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = false;
              } else {
                  this.mobileOpen = false;
              }
          }
      }"
      x-init="init()"
      @resize.window="
          if (window.innerWidth < 1024) {
              sidebarCollapsed = false;
          }
      ">

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

    <div class="flex-1 h-screen flex flex-col bg-slate-50 overflow-hidden transition-all duration-300 lg:ml-72">
        {{-- Header --}}
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">School Events</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Stay updated with school activities</p>
                </div>
            </div>
        </div>

        {{-- Events Content --}}
        <div class="flex-1 overflow-y-auto p-6">
            {{-- Events List --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                @if($events->isEmpty())
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-calendar-alt text-3xl text-slate-300"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-700">No events yet</h3>
                        <p class="text-sm text-slate-400 mt-1">Check back later for school events and activities.</p>
                    </div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($events as $event)
                            <div class="p-5 hover:bg-slate-50 transition-colors {{ $event->date->isToday() ? 'bg-amber-50/30' : '' }}">
                                <div class="flex items-start gap-4">
                                    {{-- Date Box --}}
                                    <div class="w-16 h-16 rounded-xl flex flex-col items-center justify-center shrink-0 {{ $event->date->isPast() ? 'bg-slate-100 text-slate-500' : ($event->date->isToday() ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600') }}">
                                        <span class="text-xs font-bold uppercase">{{ $event->date->format('M') }}</span>
                                        <span class="text-xl font-bold">{{ $event->date->format('d') }}</span>
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            @if($event->date->isToday())
                                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                    <i class="fas fa-star mr-1"></i>Today
                                                </span>
                                            @elseif($event->date->isPast())
                                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                    Completed
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                                    Upcoming
                                                </span>
                                            @endif
                                            <span class="text-xs text-slate-400">
                                                {{ $event->date->format('l, Y') }}
                                            </span>
                                        </div>

                                        <h3 class="font-semibold text-slate-900">{{ $event->title }}</h3>
                                        <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ Str::limit($event->description, 150) }}</p>

                                        <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>{{ $event->date->diffForHumans() }}</span>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="{{ route('student.events.show', $event) }}" class="px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</body>
</html>
