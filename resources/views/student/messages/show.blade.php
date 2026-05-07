<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Message - Pupil Portal</title>
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
        
        /* Chat Bubbles */
        .chat-bubble {
            max-width: 85%;
            padding: 12px 16px;
            border-radius: 16px;
            position: relative;
            display: inline-block;
            text-align: left;
        }
        .chat-bubble.me {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }
        .chat-bubble.them {
            background: white;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-bottom-left-radius: 4px;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    </style>
</head>
<body class="min-h-[100dvh] bg-slate-50 font-sans antialiased"
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
    <main class="min-h-[100dvh] transition-all duration-300 flex flex-col lg:ml-72">
        
        @php
            $otherPerson = $message->sender_id === auth()->id() ? $message->recipient : $message->sender;
        @endphp

        {{-- Chat Header --}}
        <header class="sticky top-0 z-20 bg-white border-b border-slate-200">
            <div class="flex items-center h-14 px-4 lg:px-6">
                <a href="{{ route('student.messages.index') }}" class="p-2 -ml-2 text-slate-600 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all mr-2 lg:ml-0 ml-12">
                    <i class="fas fa-arrow-left"></i>
                </a>
                
                {{-- Avatar --}}
                <div class="w-9 h-9 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm mr-3">
                    {{ strtoupper(substr($otherPerson->first_name ?? 'U', 0, 1)) }}
                </div>
                
                <div class="flex-1 min-w-0">
                    <h1 class="font-semibold text-slate-800 truncate">{{ $otherPerson->full_name ?? 'Unknown' }}</h1>
                    <p class="text-xs text-slate-500">{{ $message->is_read ? 'Active now' : 'Offline' }}</p>
                </div>
                
                {{-- Actions --}}
                <form action="{{ route('student.messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this conversation?')" class="ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </header>

        {{-- Chat Messages --}}
        <div class="flex-1 overflow-y-auto scrollbar-thin bg-slate-50 p-4 space-y-4">
            @if(session('success'))
                <div class="text-center animate-fade-in">
                    <span class="inline-block bg-emerald-100 text-emerald-700 text-xs px-3 py-1 rounded-full">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Original Message --}}
            <div class="animate-fade-in {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                <div class="chat-bubble {{ $message->sender_id === auth()->id() ? 'me shadow-md' : 'them shadow-sm' }}">
                    @if($message->subject)
                        <p class="font-medium text-sm mb-1 {{ $message->sender_id === auth()->id() ? 'text-white' : 'text-slate-800' }}">{{ $message->subject }}</p>
                    @endif
                    <p class="text-sm whitespace-pre-wrap">{{ $message->body }}</p>
                    <p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-indigo-200' : 'text-slate-400' }} mt-2">{{ $message->created_at->format('M d, g:i A') }}</p>
                </div>
                
                {{-- Attachments --}}
                @if($message->attachments->count() > 0)
                    <div class="mt-2 space-y-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                        @foreach($message->attachments as $attachment)
                            <a href="{{ route('student.messages.attachment', $attachment) }}" 
                               class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs hover:bg-indigo-50 hover:border-indigo-300 transition-all">
                                <i class="fas fa-file text-slate-400"></i>
                                <span class="text-slate-700">{{ $attachment->file_name }}</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Replies --}}
            @foreach($message->replies as $reply)
                <div class="animate-fade-in {{ $reply->sender_id === auth()->id() ? 'text-right' : 'text-left' }}" style="animation-delay: {{ $loop->index * 0.05 }}s">
                    <div class="chat-bubble {{ $reply->sender_id === auth()->id() ? 'me shadow-md' : 'them shadow-sm' }}">
                        <p class="text-sm whitespace-pre-wrap">{{ $reply->body }}</p>
                        <p class="text-xs {{ $reply->sender_id === auth()->id() ? 'text-indigo-200' : 'text-slate-400' }} mt-2">
                            {{ $reply->created_at->format('M d, g:i A') }}
                        </p>
                    </div>
                    
                    @if($reply->attachments->count() > 0)
                        <div class="mt-1 {{ $reply->sender_id === auth()->id() ? 'text-right' : '' }}">
                            @foreach($reply->attachments as $attachment)
                                <a href="{{ route('student.messages.attachment', $attachment) }}" 
                                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-xs hover:bg-indigo-50 transition-all">
                                    <i class="fas fa-file text-slate-400"></i>
                                    <span class="text-slate-700">{{ $attachment->file_name }}</span>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Reply Input - Available to both sender and recipient --}}
        <div class="bg-white border-t border-slate-200 p-4">
            <form action="{{ route('student.messages.reply', $message) }}" method="POST" enctype="multipart/form-data" class="flex items-end gap-2">
                @csrf
                <div class="flex-1 relative">
                    <textarea name="body" rows="1" required placeholder="Type a message..."
                              class="w-full px-4 py-3 pr-10 bg-slate-100 border-0 rounded-xl resize-none focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all"
                              oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                              style="min-height: 44px; max-height: 120px;"></textarea>
                    <label class="absolute right-3 bottom-3 text-slate-400 hover:text-indigo-600 cursor-pointer transition-all">
                        <i class="fas fa-paperclip"></i>
                        <input type="file" name="attachments[]" multiple class="hidden" onchange="showFileNames(this)">
                    </label>
                </div>
                <button type="submit" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all flex-shrink-0">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
            <div id="replyFileNames" class="mt-2 text-xs text-slate-500"></div>
        </div>
    </main>

<script>
function showFileNames(input) {
    const names = Array.from(input.files).map(f => f.name).join(', ');
    document.getElementById('replyFileNames').textContent = names ? 'Attached: ' + names : '';
}
</script>

</body>
</html>
