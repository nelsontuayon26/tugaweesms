@php
$user = auth()->user();
$roleName = $user->role ? strtolower($user->role->name) : '';
$isStudent = in_array($roleName, ['pupil']);
$isTeacher = in_array($roleName, ['teacher']);
$student = $isStudent ? $user->student : null;
$section = $student ? $student->section : null;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messenger - {{ $isStudent ? 'Pupil' : 'Teacher' }} Portal</title>
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
    </style>
</head>
<body class="bg-gray-50"
      x-data="{ sidebarCollapsed: false, mobileOpen: false }"
      x-init="if (window.innerWidth < 1024) { sidebarCollapsed = true; }">
    @if($isStudent)
        @include('student.includes.sidebar')
    @else
        @include('teacher.includes.sidebar')
    @endif
    <main class="min-h-screen transition-all duration-300" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-72'">

<div class="h-[calc(100vh-4rem)] flex flex-col md:flex-row bg-gray-50" x-data="messenger()" x-init="init()">
    <!-- Conversations Sidebar -->
    <div class="w-full md:w-80 bg-white border-r border-gray-200 flex flex-col" 
         :class="{ 'hidden': activeConversation, 'md:flex': true }">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-white">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-900">Messages</h2>
                <button @click="showNewMessage = true" 
                        class="p-2 bg-indigo-600 text-white rounded-full hover:bg-indigo-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
            <div class="relative">
                <input type="text" 
                       x-model="searchQuery"
                       placeholder="Search conversations..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <!-- Conversations List -->
        <div class="flex-1 overflow-y-auto scrollbar-thin">
            <template x-for="conv in filteredConversations" :key="conv.id">
                <div @click="selectConversation(conv)"
                     class="flex items-center gap-3 p-4 border-b border-gray-100 cursor-pointer hover:bg-gray-50 transition-colors"
                     :class="{ 'bg-indigo-50': activeConversation?.id === conv.id }">
                    <div class="relative">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold"
                             :class="conv.avatarColor">
                            <span x-text="conv.initials"></span>
                        </div>
                        <div x-show="conv.is_online" 
                             class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <h4 class="font-semibold text-gray-900 truncate" x-text="conv.name"></h4>
                            <span class="text-xs text-gray-500" x-text="conv.lastMessageTime"></span>
                        </div>
                        <p class="text-sm text-gray-600 truncate" x-text="conv.lastMessage"></p>
                    </div>
                    <div x-show="conv.unreadCount > 0" 
                         class="px-2 py-1 bg-indigo-600 text-white text-xs font-bold rounded-full"
                         x-text="conv.unreadCount"></div>
                </div>
            </template>
            <div x-show="conversations.length === 0" class="p-8 text-center text-gray-500">
                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p>No conversations yet</p>
            </div>
        </div>
    </div>

    <!-- Chat Area -->
    <div class="flex-1 flex flex-col bg-gray-100" :class="{ 'hidden': !activeConversation, 'md:flex': true }">
        <!-- Chat Header -->
        <div x-show="activeConversation" class="flex items-center justify-between p-4 bg-white border-b border-gray-200">
            <div class="flex items-center gap-3">
                <button @click="activeConversation = null" class="md:hidden p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <div class="relative">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-semibold"
                         :class="activeConversation?.avatarColor">
                        <span x-text="activeConversation?.initials"></span>
                    </div>
                    <div x-show="activeConversation?.is_online" 
                         class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900" x-text="activeConversation?.name"></h3>
                    <p class="text-xs text-gray-500" x-text="typing ? 'typing...' : (activeConversation?.is_online ? 'Online' : 'Offline')"></p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div x-show="activeConversation" 
             x-ref="messagesContainer"
             class="flex-1 overflow-y-auto p-4 space-y-4 scrollbar-thin"
             @scroll="handleScroll">
            
            <!-- Load More -->
            <div x-show="hasMoreMessages" class="text-center py-2">
                <button @click="loadMoreMessages()" class="text-sm text-indigo-600 hover:text-indigo-800">
                    Load older messages
                </button>
            </div>

            <template x-for="msg in messages" :key="msg.id">
                <div class="flex" :class="msg.isMine ? 'justify-end' : 'justify-start'">
                    <div class="max-w-[75%] md:max-w-[60%]">
                        <div class="flex items-end gap-2" :class="msg.isMine ? 'flex-row-reverse' : ''">
                            <div x-show="!msg.isMine" 
                                 class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-white text-sm font-semibold"
                                 :class="activeConversation?.avatarColor">
                                <span x-text="activeConversation?.initials"></span>
                            </div>
                            <div class="px-4 py-2.5 rounded-2xl text-sm"
                                 :class="msg.isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-white text-gray-900 rounded-bl-none shadow-sm'">
                                <p x-text="msg.body" class="leading-relaxed"></p>
                                
                                <!-- Attachments -->
                                <div x-show="msg.attachments?.length > 0" class="mt-2 space-y-1">
                                    <template x-for="att in msg.attachments" :key="att.id">
                                        <a :href="att.url" 
                                           target="_blank"
                                           class="flex items-center gap-2 p-2 rounded-lg text-xs"
                                           :class="msg.isMine ? 'bg-indigo-700 text-indigo-100' : 'bg-gray-100 text-gray-700'">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                            </svg>
                                            <span x-text="att.file_name" class="truncate max-w-[150px]"></span>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mt-1 text-xs text-gray-400"
                             :class="msg.isMine ? 'justify-end' : ''">
                            <span x-text="msg.time"></span>
                            <span x-show="msg.isMine" class="flex items-center">
                                <svg x-show="msg.is_read" class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 3a1 1 0 00-1.447-.894L8.763 6H5a3 3 0 000 6h.28l1.771 5.316A1 1 0 008 18h1a1 1 0 001-1v-4.382l6.553 3.276A1 1 0 0018 15V3z"/>
                                </svg>
                                <svg x-show="!msg.is_read" class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </template>
            
            <!-- Typing Indicator -->
            <div x-show="typing" class="flex justify-start">
                <div class="flex items-center gap-2 bg-white px-4 py-2.5 rounded-2xl rounded-bl-none shadow-sm">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold"
                         :class="activeConversation?.avatarColor">
                        <span x-text="activeConversation?.initials"></span>
                    </div>
                    <div class="flex gap-1">
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></span>
                        <span class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div x-show="!activeConversation" class="flex-1 flex items-center justify-center">
            <div class="text-center text-gray-500 px-4">
                <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-lg font-medium">Select a conversation</p>
                <p class="text-sm">Choose someone to start messaging</p>
            </div>
        </div>

        <!-- Message Input -->
        <div x-show="activeConversation" class="p-4 bg-white border-t border-gray-200">
            <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
                <div class="flex-1">
                    <textarea x-model="newMessage"
                              @keydown.enter.prevent="if (!event.shiftKey) sendMessage()"
                              @input="handleTyping()"
                              placeholder="Type a message..."
                              rows="1"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none text-sm"
                              style="min-height: 44px; max-height: 120px;"></textarea>
                </div>
                <input type="file" 
                       x-ref="fileInput" 
                       @change="handleFileSelect()"
                       multiple 
                       class="hidden"
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.mp4">
                <button type="button" 
                        @click="$refs.fileInput.click()"
                        class="p-3 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                </button>
                <button type="submit" 
                        :disabled="(!newMessage.trim() && selectedFiles.length === 0) || sending"
                        class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        <svg x-show="!sending" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <svg x-show="sending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                </button>
            </form>
            <!-- Selected Files -->
            <div x-show="selectedFiles.length > 0" class="mt-2 flex flex-wrap gap-2">
                <template x-for="(file, index) in selectedFiles" :key="index">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-full text-xs">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span x-text="file.name" class="truncate max-w-[120px]"></span>
                        <button @click="removeFile(index)" class="text-gray-400 hover:text-red-500 ml-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- New Message Modal -->
    <div x-show="showNewMessage" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="showNewMessage = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">New Message</h3>
                    <button @click="showNewMessage = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="startNewConversation()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                            <select x-model="newConversation.recipientId" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                <option value="">Select recipient...</option>
                                @if($isStudent)
                                    @if($section && $section->teacher)
                                        <option value="{{ $section->teacher->user_id }}">
                                            {{ $section->teacher->user->full_name }} (Adviser)
                                        </option>
                                    @endif
                                @else
                                    @foreach(auth()->user()->teacher?->sections ?? [] as $section)
                                        <optgroup label="{{ $section->name }}">
                                            @foreach($section->students as $student)
                                                <option value="{{ $student->user_id }}">
                                                    {{ $student->user->full_name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" x-model="newConversation.subject" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea x-model="newConversation.body" rows="4" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showNewMessage = false"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="sending"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50">
                            <span x-text="sending ? 'Sending...' : 'Send Message'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function messenger() {
    return {
        conversations: @json($conversations ?? []),
        messages: [],
        activeConversation: null,
        newMessage: '',
        selectedFiles: [],
        searchQuery: '',
        showNewMessage: false,
        newConversation: { recipientId: '', subject: '', body: '' },
        typing: false,
        typingTimeout: null,
        sending: false,
        hasMoreMessages: false,
        page: 1,
        currentUserId: {{ auth()->id() }},

        init() {
            this.processConversations();
            this.setupEchoListeners();
        },

        processConversations() {
            this.conversations = this.conversations.map(conv => ({
                ...conv,
                avatarColor: this.getAvatarColor(conv.user_id || conv.id),
                initials: this.getInitials(conv.name)
            }));
        },

        setupEchoListeners() {
            Echo.private('user.' + this.currentUserId)
                .listen('.message.sent', (e) => {
                    this.handleNewMessage(e);
                })
                .listen('.message.read', (e) => {
                    this.handleMessageRead(e);
                })
                .listen('.user.typing', (e) => {
                    if (this.activeConversation && e.sender_id === this.activeConversation.user_id) {
                        this.typing = true;
                        setTimeout(() => this.typing = false, 3000);
                    }
                });
        },

        async loadConversations() {
            try {
                const response = await fetch('{{ route('api.conversations.index') }}');
                const data = await response.json();
                this.conversations = data.conversations.map(conv => ({
                    ...conv,
                    avatarColor: this.getAvatarColor(conv.user_id),
                    initials: this.getInitials(conv.name)
                }));
            } catch (error) {
                console.error('Failed to load conversations:', error);
            }
        },

        async selectConversation(conv) {
            this.activeConversation = conv;
            this.page = 1;
            this.messages = [];
            await this.loadMessages();
            this.markAsRead(conv.user_id);
            this.scrollToBottom();
        },

        async loadMessages() {
            if (!this.activeConversation) return;
            
            try {
                const response = await fetch(`/api/conversations/${this.activeConversation.user_id}?page=${this.page}`);
                const data = await response.json();
                
                const newMessages = data.messages.map(msg => ({
                    ...msg,
                    isMine: msg.sender_id === this.currentUserId,
                    time: new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                }));
                
                if (this.page === 1) {
                    this.messages = newMessages;
                } else {
                    this.messages = [...newMessages, ...this.messages];
                }
                
                this.hasMoreMessages = data.has_more;
            } catch (error) {
                console.error('Failed to load messages:', error);
            }
        },

        async loadMoreMessages() {
            this.page++;
            await this.loadMessages();
        },

        async sendMessage() {
            if ((!this.newMessage.trim() && this.selectedFiles.length === 0) || this.sending) return;

            this.sending = true;
            const formData = new FormData();
            formData.append('recipient_id', this.activeConversation.user_id);
            formData.append('body', this.newMessage);
            if (this.activeConversation.parent_id) {
                formData.append('parent_id', this.activeConversation.parent_id);
            }

            this.selectedFiles.forEach((file, index) => {
                formData.append(`attachments[${index}]`, file);
            });

            try {
                const response = await fetch('{{ route('api.messages.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                if (response.ok) {
                    const data = await response.json();
                    this.newMessage = '';
                    this.selectedFiles = [];
                    
                    // Optimistically add message
                    this.messages.push({
                        id: data.message.id,
                        body: data.message.body,
                        sender_id: this.currentUserId,
                        isMine: true,
                        time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        attachments: data.message.attachments || [],
                        is_read: false
                    });
                    
                    this.scrollToBottom();
                    this.loadConversations();
                }
            } catch (error) {
                console.error('Failed to send message:', error);
            } finally {
                this.sending = false;
            }
        },

        async startNewConversation() {
            this.sending = true;
            
            try {
                const response = await fetch('{{ route('api.messages.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        recipient_id: this.newConversation.recipientId,
                        subject: this.newConversation.subject,
                        body: this.newConversation.body
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    this.showNewMessage = false;
                    this.newConversation = { recipientId: '', subject: '', body: '' };
                    await this.loadConversations();
                    
                    const conv = this.conversations.find(c => c.user_id == data.message.recipient_id);
                    if (conv) this.selectConversation(conv);
                }
            } catch (error) {
                console.error('Failed to start conversation:', error);
            } finally {
                this.sending = false;
            }
        },

        handleNewMessage(e) {
            // Update conversation list
            const conv = this.conversations.find(c => c.user_id === e.sender.id);
            if (conv) {
                conv.lastMessage = e.preview;
                conv.lastMessageTime = 'Just now';
                if (!this.activeConversation || this.activeConversation.user_id !== e.sender.id) {
                    conv.unreadCount = (conv.unreadCount || 0) + 1;
                }
            } else {
                this.loadConversations();
            }

            // Add to active conversation if viewing
            if (this.activeConversation && e.sender.id === this.activeConversation.user_id) {
                this.messages.push({
                    id: e.id,
                    body: e.body,
                    sender_id: e.sender.id,
                    isMine: false,
                    time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                    attachments: e.attachments || [],
                    is_read: false
                });
                this.scrollToBottom();
                this.markAsRead(this.activeConversation.user_id);
            }
        },

        handleMessageRead(e) {
            const msg = this.messages.find(m => m.id === e.message_id);
            if (msg) msg.is_read = true;
        },

        async markAsRead(userId) {
            try {
                await fetch(`/api/conversations/${userId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const conv = this.conversations.find(c => c.user_id === userId);
                if (conv) conv.unreadCount = 0;
            } catch (error) {
                console.error('Failed to mark as read:', error);
            }
        },

        handleTyping() {
            if (this.typingTimeout) clearTimeout(this.typingTimeout);
            
            this.typingTimeout = setTimeout(() => {
                if (this.activeConversation) {
                    fetch(`/api/typing/${this.activeConversation.user_id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    }).catch(() => {});
                }
            }, 300);
        },

        handleFileSelect() {
            const files = this.$refs.fileInput.files;
            for (let file of files) {
                if (file.size <= 10 * 1024 * 1024) {
                    this.selectedFiles.push(file);
                } else {
                    alert('File too large: ' + file.name);
                }
            }
            this.$refs.fileInput.value = '';
        },

        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) container.scrollTop = container.scrollHeight;
            });
        },

        handleScroll() {
            const container = this.$refs.messagesContainer;
            if (container.scrollTop === 0 && this.hasMoreMessages) {
                this.loadMoreMessages();
            }
        },

        getAvatarColor(id) {
            const colors = [
                'bg-red-500', 'bg-orange-500', 'bg-amber-500', 'bg-yellow-500',
                'bg-lime-500', 'bg-green-500', 'bg-emerald-500', 'bg-teal-500',
                'bg-cyan-500', 'bg-sky-500', 'bg-blue-500', 'bg-indigo-500',
                'bg-violet-500', 'bg-purple-500', 'bg-fuchsia-500', 'bg-pink-500'
            ];
            return colors[(id || 0) % colors.length];
        },

        getInitials(name) {
            if (!name) return '?';
            const parts = name.split(' ').filter(p => p.length > 0);
            let initials = '';
            for (let part of parts) {
                initials += part[0].toUpperCase();
                if (initials.length >= 2) break;
            }
            return initials || '?';
        },

        get filteredConversations() {
            if (!this.searchQuery) return this.conversations;
            const query = this.searchQuery.toLowerCase();
            return this.conversations.filter(c => 
                (c.name || '').toLowerCase().includes(query) ||
                (c.lastMessage || '').toLowerCase().includes(query)
            );
        }
    }
}
</script>

    </main>
</body>
</html>
