<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messages - Pupil Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        
        .message-item { transition: all 0.15s ease; }
        .message-item:hover { background: #f1f5f9; }
        .message-item.unread { background: #eff6ff; }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased"
      x-data="{ sidebarCollapsed: false, mobileOpen: false }"
      x-init="if (window.innerWidth >= 1024) { sidebarCollapsed = false; }">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" x-transition.opacity.duration.200ms @click="mobileOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden" style="display: none;"></div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <!-- Sidebar -->
    @include('student.includes.sidebar')

    <!-- Main Content -->
    <main class="min-h-screen transition-all duration-300 lg:ml-72">
        
        <!-- Simple Header -->
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200">
            <div class="flex items-center justify-between h-14 px-4 lg:px-6">
                <h1 class="text-lg font-semibold text-slate-800 lg:ml-0 ml-14">Messages</h1>
                @if($unreadCount > 0)
                    <span class="bg-indigo-600 text-white text-xs font-medium px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                @endif
            </div>
        </header>

        <!-- Messenger Container -->
        <div class="flex h-[calc(100vh-56px)]">
            
            <!-- Conversation List -->
            <div class="w-full max-w-md bg-white border-r border-slate-200 flex flex-col">
                
                <!-- Search & New Message -->
                <div class="p-3 border-b border-slate-200 space-y-2">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text" placeholder="Search messages..." 
                               class="w-full pl-9 pr-4 py-2 bg-slate-100 border-0 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all">
                    </div>
                    <a href="{{ route('student.messages.create') }}" 
                       class="flex items-center justify-center gap-2 w-full py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-all">
                        <i class="fas fa-pen text-xs"></i>
                        Message {{ $section && $section->teacher ? $section->teacher->user->first_name : 'Teacher' }}
                    </a>
                </div>

                <!-- Tabs -->
                <div class="flex border-b border-slate-200">
                    <a href="{{ route('student.messages.index', ['tab' => 'inbox']) }}" 
                       class="flex-1 py-3 text-center text-sm font-medium {{ $tab === 'inbox' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-700' }}">
                        Inbox
                        @if($unreadCount > 0)
                            <span class="ml-1 bg-rose-500 text-white text-[10px] px-1.5 rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('student.messages.index', ['tab' => 'sent']) }}" 
                       class="flex-1 py-3 text-center text-sm font-medium {{ $tab === 'sent' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500 hover:text-slate-700' }}">
                        Sent
                    </a>
                </div>

                <!-- Messages List -->
                <div class="flex-1 overflow-y-auto scrollbar-thin">
                    @if($messages && $messages->count() > 0)
                        @foreach($messages as $message)
                            @php
                                $isSender = $message->sender_id === auth()->id();
                                $isUnread = !$isSender && !$message->is_read;
                                $otherPerson = $isSender ? $message->recipient : $message->sender;
                            @endphp
                            <a href="{{ route('student.messages.show', $message) }}" 
                               class="message-item flex items-center gap-3 p-4 border-b border-slate-100 {{ $isUnread ? 'unread' : '' }}">
                                
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full {{ $isSender ? 'bg-emerald-500' : 'bg-indigo-500' }} flex items-center justify-center text-white font-semibold text-sm">
                                        {{ $otherPerson ? strtoupper(substr($otherPerson->first_name ?? 'U', 0, 1)) : '?' }}
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-baseline mb-0.5">
                                        <h3 class="font-semibold text-sm {{ $isUnread ? 'text-slate-900' : 'text-slate-700' }} truncate">
                                            {{ $otherPerson ? $otherPerson->full_name : 'Unknown' }}
                                            @if($isSender)
                                                <span class="text-xs text-emerald-600 font-normal ml-1">(You sent)</span>
                                            @endif
                                        </h3>
                                        <span class="text-xs text-slate-400">{{ $message->created_at->diffForHumans(null, true) }}</span>
                                    </div>
                                    <p class="text-sm {{ $isUnread ? 'font-semibold text-slate-800' : 'text-slate-600' }} truncate">
                                        {{ $message->subject }}
                                    </p>
                                    <p class="text-xs text-slate-500 truncate">{{ $message->preview }}</p>
                                </div>
                                
                                <!-- Unread Dot -->
                                @if($isUnread)
                                    <div class="w-2.5 h-2.5 bg-indigo-600 rounded-full flex-shrink-0"></div>
                                @endif
                            </a>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center h-full p-8 text-center text-slate-400">
                            <i class="fas fa-comment-slash text-4xl mb-3"></i>
                            <p class="text-sm">No messages yet</p>
                            <p class="text-xs mt-1">Start a conversation with your teacher</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Empty State (Desktop) -->
            <div class="hidden lg:flex flex-1 flex-col items-center justify-center bg-slate-50 text-slate-400">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                    <i class="fas fa-comments text-3xl"></i>
                </div>
                <p class="text-sm">Select a conversation to view messages</p>
            </div>
        </div>
    </main>

</body>
</html>
