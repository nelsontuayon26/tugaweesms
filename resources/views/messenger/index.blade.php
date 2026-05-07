@php
$user = auth()->user();
$roleName = strtolower($user->role?->name ?? '');
$isTeacher = $roleName === 'teacher';
$isStudent = $roleName === 'pupil';

// Prepare contacts data in PHP
$contactsData = $contacts->map(function($c) use ($user) {
    $unread = \App\Models\Message::where('sender_id', $c->id)
        ->where('recipient_id', $user->id)
        ->where('is_read', false)->count();
    $lastMsg = \App\Models\Message::betweenUsers($user->id, $c->id)->latest()->first();
    $isOnline = \Illuminate\Support\Facades\Cache::has('user-online-' . $c->id);
    return [
        'id' => $c->id,
        'type' => 'user',
        'name' => $c->full_name,
        'initials' => strtoupper(substr($c->first_name, 0, 1)) . strtoupper(substr($c->last_name, 0, 1)),
        'unread' => $unread,
        'is_online' => $isOnline,
        'lastMessage' => $lastMsg 
            ? \Illuminate\Support\Str::limit($lastMsg->body, 25)
            : 'Click to start chat',
        'lastMessageFull' => $lastMsg ? ($lastMsg->sender_id == $user->id ? 'You: ' : '') . $lastMsg->body : 'Click to start chat',
    ];
});

// Debug info for teachers
$debugInfo = null;
if ($isTeacher) {
    $teacher = \App\Models\Teacher::where('user_id', $user->id)->first();
    $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
    $sectionIds = collect();
    if ($teacher) {
        $sectionIds = $sectionIds->merge(\App\Models\Section::where('teacher_id', $teacher->id)->pluck('id'));
        $pivotIds = \DB::table('teacher_sections')->where('teacher_id', $teacher->id)->pluck('section_id');
        $sectionIds = $sectionIds->merge($pivotIds)->unique()->values();
    }
    // Check is_active values on student users to diagnose filtering issues
    $sampleIsActive = null;
    if ($contacts->isNotEmpty()) {
        $sampleIsActive = $contacts->first()->is_active;
    }
    $debugInfo = [
        'teacher_id' => $teacher?->id,
        'teacher_name' => $teacher?->full_name,
        'section_ids' => $sectionIds->toArray(),
        'section_count' => $sectionIds->count(),
        'active_school_year' => $activeSchoolYear?->name,
        'contact_count' => $contacts->count(),
        'user_role' => $user->role?->name,
        'sample_is_active' => $sampleIsActive,
    ];
}

// Sidebar-specific variables for student
if ($isStudent) {
    $student = $user->student;
    $section = $student ? $student->section : null;
    $gradeLevel = $student ? $student->gradeLevel : null;
}

// Build group contacts
$groupContactsData = collect();
$groupSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();

try {
    if ($isTeacher) {
        $teacherModel = \App\Models\Teacher::where('user_id', $user->id)->first();
        $sectionIds = collect();
        if ($teacherModel) {
            $sectionIds = $sectionIds->merge(\App\Models\Section::where('teacher_id', $teacherModel->id)->pluck('id'));
            try {
                $pivotIds = \DB::table('teacher_sections')->where('teacher_id', $teacherModel->id)->pluck('section_id');
                $sectionIds = $sectionIds->merge($pivotIds)->unique()->values();
            } catch (\Exception $e) {
                \Log::warning('Messenger view: teacher_sections query failed: ' . $e->getMessage());
            }
        }
        $groupSections = \App\Models\Section::whereIn('id', $sectionIds)
            ->when($groupSchoolYear, fn($q) => $q->where('school_year_id', $groupSchoolYear->id))
            ->get();

        foreach ($groupSections as $grpSection) {
            try {
                $lastRead = \Illuminate\Support\Facades\Cache::get("messenger:group:read:{$user->id}:{$grpSection->id}");
                $unreadQuery = \App\Models\Message::where('section_id', $grpSection->id)
                    ->where('is_group_chat', true)
                    ->where('sender_id', '!=', $user->id);
                if ($lastRead) {
                    $unreadQuery->where('created_at', '>', date('Y-m-d H:i:s', $lastRead));
                }
                $unread = $unreadQuery->count();
                $lastMsg = \App\Models\Message::where('section_id', $grpSection->id)
                    ->where('is_group_chat', true)
                    ->latest()
                    ->first();
                $memberCount = $grpSection->students()->whereNotIn('status', ['completed', 'inactive'])->count() + 1;

                $groupContactsData->push([
                    'id' => 'group_' . $grpSection->id,
                    'type' => 'group',
                    'name' => $grpSection->name,
                    'initials' => null,
                    'unread' => $unread,
                    'is_online' => false,
                    'member_count' => $memberCount,
                    'lastMessage' => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->body, 25) : 'No messages yet',
                    'lastMessageFull' => $lastMsg ? ($lastMsg->sender_id == $user->id ? 'You: ' : '') . $lastMsg->body : 'No messages yet',
                ]);
            } catch (\Exception $e) {
                \Log::warning('Messenger view: group chat query failed for section ' . $grpSection->id . ': ' . $e->getMessage());
            }
        }
    } elseif ($isStudent && $section) {
        try {
            $lastRead = \Illuminate\Support\Facades\Cache::get("messenger:group:read:{$user->id}:{$section->id}");
            $unreadQuery = \App\Models\Message::where('section_id', $section->id)
                ->where('is_group_chat', true)
                ->where('sender_id', '!=', $user->id);
            if ($lastRead) {
                $unreadQuery->where('created_at', '>', date('Y-m-d H:i:s', $lastRead));
            }
            $unread = $unreadQuery->count();
            $lastMsg = \App\Models\Message::where('section_id', $section->id)
                ->where('is_group_chat', true)
                ->latest()
                ->first();
            $memberCount = $section->students()->whereNotIn('status', ['completed', 'inactive'])->count() + 1;

            $groupContactsData->push([
                'id' => 'group_' . $section->id,
                'type' => 'group',
                'name' => $section->name,
                'initials' => null,
                'unread' => $unread,
                'is_online' => false,
                'member_count' => $memberCount,
                'lastMessage' => $lastMsg ? \Illuminate\Support\Str::limit($lastMsg->body, 25) : 'No messages yet',
                'lastMessageFull' => $lastMsg ? ($lastMsg->sender_id == $user->id ? 'You: ' : '') . $lastMsg->body : 'No messages yet',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Messenger view: student group chat query failed: ' . $e->getMessage());
        }
    }
} catch (\Exception $e) {
    \Log::error('Messenger view: group contacts build failed: ' . $e->getMessage());
}

$contactsData = collect($contactsData)->merge($groupContactsData);
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messenger - {{ $isTeacher ? 'Teacher Portal' : 'Pupil Portal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; overflow: hidden; }
        html, body { height: 100%; }
        [x-cloak] { display: none !important; }
        
        .scrollbar-thin::-webkit-scrollbar { width: 5px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-in { animation: fadeIn 0.3s ease-out; }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(2); opacity: 0; }
        }
        .typing-dot { animation: typingBounce 1.4s infinite ease-in-out both; }
        .typing-dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dot:nth-child(2) { animation-delay: -0.16s; }
        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0); }
            40% { transform: scale(1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" 
      x-data="messenger()" 
      x-init="init()">

<div class="flex h-screen">
    {{-- Role-specific Sidebar --}}
    @if($isTeacher)
        @include('teacher.includes.sidebar')
    @elseif($isStudent)
        @include('student.includes.sidebar')
    @endif

    {{-- Main Messenger Area --}}
    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        
        {{-- Mobile Header with back to contacts --}}
        <div class="lg:hidden bg-white border-b border-slate-200 h-14 flex items-center px-4 shrink-0">
            <button @click="activeContact = null; sidebarOpen = true" 
                    x-show="activeContact"
                    class="mr-3 p-2 text-slate-500 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all">
                <i class="fas fa-arrow-left"></i>
            </button>
            <h1 class="font-semibold text-slate-800">Messages</h1>
        </div>

        <div class="flex flex-1 h-[calc(100vh-56px)] lg:h-full overflow-hidden">
            
            {{-- Contacts Sidebar (Messenger-specific) --}}
            <div class="absolute lg:relative z-30 w-full lg:w-80 h-full bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-300 lg:translate-x-0 overflow-hidden"
                 :class="(sidebarOpen || !activeContact) ? 'translate-x-0' : '-translate-x-full'">
                
                {{-- Search Header --}}
                <div class="p-4 bg-gradient-to-r from-indigo-600 to-violet-600 flex-shrink-0">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-bold text-white">
                            <i class="fas fa-comments mr-2"></i>Messages
                        </h2>
                        @php
                            $totalUnread = \App\Models\Message::receivedBy(auth()->id())->unread()->count();
                        @endphp
                        @if($totalUnread > 0)
                            <span class="bg-rose-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                {{ $totalUnread }}
                            </span>
                        @endif
                    </div>
                    <div class="relative">
                        <input type="text" 
                               x-model="searchQuery" 
                               placeholder="Search contacts..." 
                               class="w-full px-4 py-2 pl-10 rounded-xl bg-white/15 text-white placeholder-white/70 border border-white/20 focus:outline-none focus:ring-2 focus:ring-white/40 text-sm">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-white/60 text-sm"></i>
                    </div>
                </div>
                
                {{-- Contact List --}}
                <div class="flex-1 overflow-y-auto scrollbar-thin">
                    <template x-if="filteredContacts.length === 0">
                        <div class="p-4 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-search text-2xl text-slate-300"></i>
                            </div>
                            <p class="text-sm text-slate-400 mb-3">No contacts found</p>
                            
                            @if($isTeacher && empty($contacts))
                                <p class="text-xs text-slate-400 mt-2">No pupils available for messaging.</p>
                            @endif
                        </div>
                    </template>
                    
                    <template x-for="contact in filteredContacts" :key="contact.id">
                        <div @click="openChat(contact)" 
                             class="flex items-center gap-3 p-4 cursor-pointer hover:bg-slate-50 transition-all border-b border-slate-100"
                             :class="activeContact?.id === contact.id ? 'bg-indigo-50 border-l-4 border-l-indigo-500' : 'border-l-4 border-l-transparent'">
                            <div class="relative flex-shrink-0">
                                <template x-if="contact.type === 'group'">
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </template>
                                <template x-if="contact.type !== 'group'">
                                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-md"
                                         x-text="contact.initials">
                                    </div>
                                </template>
                                <span x-show="contact.unread > 0" 
                                      class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center shadow-sm border-2 border-white"
                                      x-text="contact.unread > 9 ? '9+' : contact.unread">
                                </span>
                                <span x-show="contact.is_online && contact.type !== 'group'"
                                      class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-emerald-500 rounded-full border-2 border-white shadow-sm">
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <h4 class="font-semibold text-sm truncate" 
                                            :class="contact.unread > 0 ? 'text-slate-900' : 'text-slate-700'"
                                            x-text="contact.name">
                                        </h4>
                                        <span x-show="contact.type === 'group'" class="px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded uppercase tracking-wide">Group</span>
                                    </div>
                                </div>
                                <p class="text-xs truncate mt-0.5"
                                   :class="contact.unread > 0 ? 'text-slate-700 font-medium' : 'text-slate-400'"
                                   x-text="contact.lastMessage">
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Chat Area --}}
            <div class="flex-1 flex flex-col bg-slate-50 w-full relative overflow-hidden min-h-0">
                
                {{-- Empty State --}}
                <template x-if="!activeContact">
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                        <div class="w-28 h-28 bg-gradient-to-br from-indigo-100 to-violet-100 rounded-full flex items-center justify-center mb-6 shadow-lg">
                            <i class="fas fa-comments text-5xl text-indigo-400"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-800 mb-2">Welcome to Messenger</h3>
                        <p class="text-slate-400 max-w-sm text-sm">Select a contact to start chatting. Send text messages, photos, videos, and documents.</p>
                        <div class="mt-6 flex gap-4 text-slate-400 text-2xl">
                            <i class="fas fa-image hover:text-indigo-500 transition-colors" title="Photos"></i>
                            <i class="fas fa-video hover:text-indigo-500 transition-colors" title="Videos"></i>
                            <i class="fas fa-file hover:text-indigo-500 transition-colors" title="Documents"></i>
                        </div>
                    </div>
                </template>

                {{-- Active Chat --}}
                <template x-if="activeContact">
                    <div class="flex flex-col h-full min-h-0">
                        {{-- Chat Header --}}
                        <div class="p-4 bg-white border-b border-slate-200 flex items-center gap-3 shadow-sm flex-shrink-0">
                            <button @click="activeContact = null; sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <div class="relative flex-shrink-0">
                                <template x-if="activeContact.type === 'group'">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </template>
                                <template x-if="activeContact.type !== 'group'">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-white font-bold text-sm shadow-md"
                                         x-text="activeContact.initials">
                                    </div>
                                </template>
                                <span x-show="activeContactOnline && activeContact.type !== 'group'"
                                      class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white shadow-sm">
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-900 text-sm truncate" x-text="activeContact.name"></h3>
                                <p class="text-xs text-slate-400" x-text="activeContact.type === 'group' ? (activeContact.member_count ? activeContact.member_count + ' members' : 'Group chat') : (activeContactOnline ? 'Active now' : 'Offline')"></p>
                            </div>
                        </div>

                        {{-- Messages Scroll Area --}}
                        <div x-ref="messagesContainer" 
                             class="flex-1 overflow-y-auto scrollbar-thin p-4 lg:p-6 space-y-3 min-h-0"
                             @scroll="onScroll">
                            
                            {{-- Loading --}}
                            <template x-if="loadingMessages">
                                <div class="flex items-center justify-center py-8">
                                    <x-inline-spinner size="lg" />
                                </div>
                            </template>

                            {{-- Messages --}}
                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex group" 
                                     :class="msg.isMine ? 'justify-end' : 'justify-start'"
                                     :id="'msg-' + msg.id">
                                    
                                    <div class="max-w-[85%] lg:max-w-[65%] relative">
                                        <template x-if="!msg.isMine && msg.sender && activeContact.type === 'group'">
                                            <p class="text-[11px] text-slate-500 font-medium mb-0.5 px-1" x-text="msg.sender.name"></p>
                                        </template>
                                        
                                        {{-- Edit/Delete Actions (hover) --}}
                                        <div x-show="msg.isMine && editingMessageId !== msg.id && !msg.deleted" 
                                             x-cloak
                                             class="absolute -top-9 right-0 hidden group-hover:flex items-center gap-1 bg-white rounded-lg shadow-lg p-1 border border-slate-200 z-10">
                                            <button @click="startEdit(msg)" 
                                                    class="p-1.5 text-slate-500 hover:text-indigo-600 rounded transition-colors"
                                                    title="Edit message">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                            <button @click="confirmDelete(msg.id)" 
                                                    class="p-1.5 text-slate-500 hover:text-rose-600 rounded transition-colors"
                                                    title="Delete message">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                        
                                        {{-- Message Bubble --}}
                                        <div class="px-4 py-2.5 rounded-2xl text-sm relative" 
                                             :class="[
                                                msg.isMine 
                                                    ? 'bg-indigo-600 text-white rounded-br-md' 
                                                    : msg.is_read 
                                                        ? 'bg-white text-slate-800 rounded-bl-md shadow-sm border border-slate-100' 
                                                        : 'bg-white text-slate-900 rounded-bl-md shadow-sm font-semibold border-l-4 border-indigo-500',
                                                msg.deleted ? 'opacity-60 !bg-slate-200 !text-slate-400 italic' : ''
                                             ]">
                                            
                                            {{-- Deleted Message --}}
                                            <template x-if="msg.deleted">
                                                <p class="italic text-xs flex items-center gap-1.5">
                                                    <i class="fas fa-ban"></i> This message was deleted
                                                </p>
                                            </template>

                                            {{-- Edit Mode --}}
                                            <template x-if="editingMessageId === msg.id && !msg.deleted">
                                                <div>
                                                    <textarea x-model="editMessageBody" 
                                                              rows="2" 
                                                              @keydown.enter.prevent="saveEdit()"
                                                              @keydown.escape="cancelEdit()"
                                                              class="w-full px-3 py-2 rounded-lg text-slate-800 border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:outline-none resize-none text-sm"
                                                              x-ref="editTextarea"></textarea>
                                                    <div class="flex gap-2 mt-2">
                                                        <button @click="saveEdit()" 
                                                                class="px-3 py-1 bg-emerald-500 text-white rounded-lg text-xs font-medium hover:bg-emerald-600 transition-colors">
                                                            <i class="fas fa-check mr-1"></i>Save
                                                        </button>
                                                        <button @click="cancelEdit()" 
                                                                class="px-3 py-1 bg-slate-400 text-white rounded-lg text-xs font-medium hover:bg-slate-500 transition-colors">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            {{-- Display Mode --}}
                                            <template x-if="editingMessageId !== msg.id && !msg.deleted">
                                                <div>
                                                    {{-- Text Content --}}
                                                    <p x-show="msg.body" 
                                                       x-text="msg.body"
                                                       class="whitespace-pre-wrap break-words leading-relaxed"></p>
                                                    
                                                    {{-- Attachments --}}
                                                    <div x-show="msg.attachments?.length" 
                                                         class="mt-2 space-y-2"
                                                         :class="msg.body ? 'pt-2 border-t border-white/20' : ''">
                                                        <template x-for="att in msg.attachments" :key="att.id">
                                                            <div>
                                                                {{-- Image: Viewable inline + clickable lightbox --}}
                                                                <template x-if="isImage(att.file_name)">
                                                                    <div class="relative group/img cursor-pointer"
                                                                         @click="openLightbox(att.url)">
                                                                        <img :src="att.url" 
                                                                             class="max-w-full max-h-48 rounded-lg hover:opacity-95 transition-opacity shadow-sm object-cover"
                                                                             loading="lazy"
                                                                             :alt="att.file_name">
                                                                        <div class="absolute inset-0 bg-black/0 group-hover/img:bg-black/10 transition-colors rounded-lg flex items-center justify-center">
                                                                            <i class="fas fa-expand text-white opacity-0 group-hover/img:opacity-100 transition-opacity drop-shadow-lg text-lg"></i>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                                
                                                                {{-- Video: Playable inline --}}
                                                                <template x-if="isVideo(att.file_name)">
                                                                    <div class="relative">
                                                                        <video controls 
                                                                               class="max-w-full max-h-48 rounded-lg shadow-sm"
                                                                               preload="metadata">
                                                                            <source :src="att.url" :type="att.file_type || 'video/mp4'">
                                                                            Your browser does not support the video tag.
                                                                        </video>
                                                                    </div>
                                                                </template>
                                                                
                                                                {{-- Documents: Downloadable --}}
                                                                <template x-if="!isImage(att.file_name) && !isVideo(att.file_name)">
                                                                    <a :href="att.url" 
                                                                       download
                                                                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs"
                                                                       :class="msg.isMine ? 'bg-white/15 hover:bg-white/25 text-white' : 'bg-slate-100 hover:bg-slate-200 text-slate-700'">
                                                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                                                             :class="msg.isMine ? 'bg-white/20' : 'bg-white'">
                                                                            <i class="fas fa-file text-sm"
                                                                               :class="msg.isMine ? 'text-white' : 'text-slate-500'"></i>
                                                                        </div>
                                                                        <span class="truncate flex-1 font-medium" x-text="att.file_name"></span>
                                                                        <i class="fas fa-download opacity-60"></i>
                                                                    </a>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        {{-- Timestamp & Status --}}
                                        <div class="flex items-center gap-1.5 mt-1 px-1"
                                             :class="msg.isMine ? 'justify-end' : ''">
                                            <span class="text-[11px] text-slate-400" x-text="msg.time"></span>
                                            <template x-if="msg.isMine && !msg.deleted">
                                                <i class="fas fa-check-double text-[10px]"
                                                   :class="msg.is_read ? 'text-indigo-500' : 'text-slate-300'"></i>
                                            </template>
                                            <span x-show="msg.is_edited && !msg.deleted" 
                                                  class="text-[10px] text-slate-400 italic">(edited)</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        {{-- Typing Indicator --}}
                        <div x-show="typingContactId && activeContact && typingContactId === activeContact.id"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="px-4 py-2 flex items-center gap-2 shrink-0">
                            <div class="flex items-center gap-1.5 bg-white border border-slate-100 shadow-sm rounded-2xl rounded-bl-md px-3 py-2">
                                <div class="flex gap-0.5">
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full typing-dot"></div>
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full typing-dot"></div>
                                    <div class="w-1.5 h-1.5 bg-indigo-400 rounded-full typing-dot"></div>
                                </div>
                                <span class="text-[11px] text-slate-400" x-text="activeContact.name + ' is typing...'"></span>
                            </div>
                        </div>
                        
                        {{-- Input Area --}}
                        <div class="p-4 bg-white border-t border-slate-200 flex-shrink-0">
                            {{-- Selected Files Preview --}}
                            <div x-show="selectedFiles.length" class="mb-3 flex flex-wrap gap-2">
                                <template x-for="(file, i) in selectedFiles" :key="i">
                                    <div class="relative group/file">
                                        {{-- Image Preview --}}
                                        <template x-if="isImage(file.name)">
                                            <div class="relative">
                                                <img :src="URL.createObjectURL(file)" 
                                                     class="w-14 h-14 object-cover rounded-lg border border-slate-200 shadow-sm">
                                                <button @click="removeFile(i)" 
                                                        class="absolute -top-2 -right-2 w-5 h-5 bg-rose-500 text-white rounded-full text-[10px] flex items-center justify-center shadow-md hover:bg-rose-600 transition-colors">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                        {{-- Other File --}}
                                        <template x-if="!isImage(file.name)">
                                            <div class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-xs max-w-[160px] border border-slate-200">
                                                <i class="fas fa-file text-slate-500"></i>
                                                <span class="truncate flex-1 text-slate-700" x-text="file.name"></span>
                                                <button @click="removeFile(i)" class="text-rose-500 hover:text-rose-600 transition-colors">
                                                    <i class="fas fa-times text-xs"></i>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <form @submit.prevent="sendMessage()" class="flex items-end gap-2">
                                <input type="file" x-ref="fileInput" @change="handleFileSelect($event)" multiple class="hidden">
                                <button type="button" 
                                        @click="$refs.fileInput.click()" 
                                        class="p-3 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all shrink-0"
                                        title="Attach files (images, videos, documents)">
                                    <i class="fas fa-paperclip text-lg"></i>
                                </button>
                                <textarea x-model="newMessage" 
                                          @keydown.enter.prevent="sendMessage()"
                                          rows="1" 
                                          x-ref="messageInput"
                                          @input="autoResize($event); broadcastTyping()"
                                          class="flex-1 px-4 py-3 border border-slate-200 rounded-xl resize-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent max-h-32 bg-slate-50 focus:bg-white transition-all text-sm"
                                          placeholder="Type a message..."></textarea>
                                <button type="submit" 
                                        :disabled="(!newMessage.trim() && selectedFiles.length === 0) || sending" 
                                        class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all shadow-md shrink-0"
                                        title="Send message">
                                    <i x-show="!sending" class="fas fa-paper-plane text-lg"></i>
                                    <svg x-show="sending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </form>
                            <p class="text-[11px] text-slate-400 mt-2 text-center">Press Enter to send, Shift+Enter for new line • Max file size: 10MB</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal with Countdown --}}
<div x-show="deleteModalOpen" 
     x-cloak
     class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm"
     x-transition.opacity>
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all"
         x-transition.scale>
        <div class="text-center">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Message?</h3>
            <p class="text-slate-500 text-sm mb-6">This action cannot be undone. The message will be permanently removed from this conversation.</p>
            
            <div class="flex gap-3">
                <button @click="cancelDelete()" 
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-colors">
                    Cancel
                </button>
                <button @click="executeDelete()" 
                        :disabled="deleteCountdown > 0"
                        class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
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
<div x-show="lightboxOpen" 
     x-cloak
     @keydown.escape.window="lightboxOpen = false"
     class="fixed inset-0 z-[60] flex items-center justify-center bg-black/90"
     x-transition.opacity>
    <button @click="lightboxOpen = false" 
            class="absolute top-4 right-4 p-3 text-white/70 hover:text-white transition-colors z-10">
        <i class="fas fa-times text-2xl"></i>
    </button>
    <a :href="lightboxImage" download 
       class="absolute top-4 left-4 p-3 text-white/70 hover:text-white transition-colors z-10"
       title="Download image">
        <i class="fas fa-download text-xl"></i>
    </a>
    <img :src="lightboxImage" 
         class="max-w-[95vw] max-h-[90vh] object-contain rounded-lg shadow-2xl"
         @click.stop>
</div>

{{-- Toast Notifications --}}
<div class="fixed bottom-4 right-4 z-[70] space-y-2" x-show="toasts.length > 0" x-cloak>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             class="px-4 py-3 rounded-xl shadow-lg text-sm font-medium flex items-center gap-2"
             :class="toast.type === 'success' ? 'bg-emerald-500 text-white' : toast.type === 'error' ? 'bg-rose-500 text-white' : 'bg-slate-800 text-white'">
            <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : toast.type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'"></i>
            <span x-text="toast.message"></span>
        </div>
    </template>
</div>

<script>
function messenger() {
    return {
        sidebarOpen: window.innerWidth < 1024,
        mobileOpen: false,
        sidebarCollapsed: false,
        activeContact: null,
        messages: [],
        newMessage: '',
        selectedFiles: [],
        editingMessageId: null,
        editMessageBody: '',
        searchQuery: '',
        currentUserId: {{ auth()->id() }},
        contacts: @json($contactsData),
        sending: false,
        loadingMessages: false,
        lightboxOpen: false,
        lightboxImage: '',
        activeContactOnline: false,
        typingContactId: null,
        typingTimer: null,
        typingBroadcastTimer: null,
        toasts: [],
        
        // Delete modal state
        deleteModalOpen: false,
        deleteMessageId: null,
        deleteCountdown: 3,
        deleteCountdownInterval: null,
        
        init() {
            // Setup real-time listeners
            this.setupEchoListener();
            
            // Start heartbeat for online status (tells server we're online)
            this.startHeartbeat();
            
            // Start polling contact online status
            this.startOnlineStatusPolling();
            
            // Check for URL parameter to open specific contact
            const urlParams = new URLSearchParams(window.location.search);
            const contactId = urlParams.get('contact');
            if (contactId) {
                const contact = this.contacts.find(c => c.id == contactId);
                if (contact) {
                    this.openChat(contact);
                }
            }
            
            // Handle mobile sidebar
            if (window.innerWidth < 1024) {
                this.sidebarOpen = true;
            }
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    this.mobileOpen = false;
                }
            });
        },

        setupEchoListener() {
            if (typeof Echo !== 'undefined') {
                Echo.private('user.' + this.currentUserId)
                    // New message received
                    .listen('.message.sent', (e) => {
                        if (e.is_group_chat) {
                            const groupId = 'group_' + e.section_id;
                            // If currently chatting with this group, add message
                            if (this.activeContact && this.activeContact.id === groupId) {
                                this.messages.push({
                                    id: e.id,
                                    body: e.body,
                                    sender_id: e.sender.id,
                                    sender: e.sender,
                                    recipient_id: this.currentUserId,
                                    isMine: false,
                                    is_read: false,
                                    is_edited: false,
                                    time: new Date(e.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}),
                                    attachments: (e.attachments_count > 0) ? [] : [], // Will be loaded on refresh
                                    deleted: false
                                });
                                this.scrollToBottom();
                                this.markAsRead(groupId);
                                
                                // Mark messages as read via API
                                fetch(`/api/conversations/${groupId}/read`, {
                                    method: 'POST',
                                    headers: { 
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                        'Accept': 'application/json'
                                    }
                                }).catch(() => {});
                            }
                            // Update contact list
                            this.updateContactPreview(groupId, e.sender.name + ': ' + (e.body || 'Sent a message'));
                            
                            // Show toast notification
                            const groupContact = this.contacts.find(c => c.id === groupId);
                            const groupName = groupContact ? groupContact.name : 'group';
                            if (!this.activeContact || this.activeContact.id !== groupId) {
                                this.showToast(e.sender.name + ' in ' + groupName, 'info');
                            }
                            return;
                        }
                        
                        // Sender is online since they just sent a message
                        if (e.sender) {
                            const contact = this.contacts.find(c => c.id === e.sender.id);
                            if (contact) contact.is_online = true;
                            if (this.activeContact && this.activeContact.id === e.sender.id) {
                                this.activeContactOnline = true;
                            }
                        }
                        // If currently chatting with sender, add message
                        if (this.activeContact && e.sender && e.sender.id === this.activeContact.id) {
                            this.messages.push({
                                id: e.id,
                                body: e.body,
                                sender_id: e.sender.id,
                                recipient_id: this.currentUserId,
                                isMine: false,
                                is_read: false,
                                is_edited: false,
                                time: new Date(e.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}),
                                attachments: (e.attachments_count > 0) ? [] : [], // Will be loaded on refresh
                                deleted: false
                            });
                            this.scrollToBottom();
                            this.markAsRead(e.sender.id);
                            
                            // Mark messages as read via API
                            fetch(`/api/conversations/${e.sender.id}/read`, {
                                method: 'POST',
                                headers: { 
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            }).catch(() => {});
                        }
                        // Update contact list
                        this.updateContactPreview(e.sender.id, e.body || 'Sent a message');
                        
                        // Show toast notification
                        if (!this.activeContact || this.activeContact.id !== e.sender.id) {
                            this.showToast('New message from ' + e.sender.name, 'info');
                        }
                    })
                    // Message was edited
                    .listen('.message.edited', (e) => {
                        const msg = this.messages.find(m => m.id === e.id);
                        if (msg) {
                            msg.body = e.body;
                            msg.is_edited = true;
                        }
                        // Also update contact preview if this was the last message
                        if (this.activeContact) {
                            this.updateContactPreview(this.activeContact.id, e.body);
                        }
                    })
                    // Message was deleted
                    .listen('.message.deleted', (e) => {
                        const msg = this.messages.find(m => m.id === e.message_id);
                        if (msg) {
                            msg.deleted = true;
                            msg.body = '';
                            msg.attachments = [];
                        }
                    })
                    // Message read receipt
                    .listen('.message.read', (e) => {
                        // Update read status for our sent messages
                        this.messages.forEach(msg => {
                            if (msg.id === e.message_id) {
                                msg.is_read = true;
                            }
                        });
                    })
                    // Typing indicator
                    .listen('.user.typing', (e) => {
                        if (this.activeContact && e.user_id === this.activeContact.id) {
                            this.typingContactId = e.user_id;
                            this.scrollToBottom();
                            // Clear typing indicator after 3 seconds
                            clearTimeout(this.typingTimer);
                            this.typingTimer = setTimeout(() => {
                                this.typingContactId = null;
                            }, 3000);
                        }
                    });
            }
        },

        startHeartbeat() {
            // Send heartbeat every 30 seconds to tell server we're online
            setInterval(() => {
                fetch('/api/heartbeat', {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).catch(() => {});
            }, 30000);
        },

        startOnlineStatusPolling() {
            // Poll contact online status every 15 seconds
            setInterval(() => {
                if (!this.activeContact) return;
                fetch(`/api/conversations/${this.activeContact.id}`)
                    .then(r => r.ok ? r.json() : null)
                    .then(data => {
                        if (data && data.contact) {
                            this.activeContactOnline = data.contact.is_online || false;
                            // Also update in contacts list
                            const c = this.contacts.find(c => c.id === this.activeContact.id);
                            if (c) c.is_online = data.contact.is_online || false;
                        }
                    })
                    .catch(() => {});
            }, 15000);
        },

        broadcastTyping() {
            if (!this.activeContact) return;
            // Debounce: only send if we haven't typed in the last 300ms
            clearTimeout(this.typingBroadcastTimer);
            this.typingBroadcastTimer = setTimeout(() => {
                fetch(`/api/typing/${this.activeContact.id}`, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                }).catch(() => {});
            }, 300);
        },

        updateContactPreview(contactId, message) {
            const contact = this.contacts.find(c => c.id === contactId);
            if (contact) {
                const preview = message.substring(0, 25) + (message.length > 25 ? '...' : '');
                contact.lastMessage = preview;
                if (!this.activeContact || this.activeContact.id !== contactId) {
                    contact.unread++;
                }
            }
        },
        
        showToast(message, type = 'info') {
            const id = Date.now();
            this.toasts.push({ id, message, type, show: true });
            setTimeout(() => {
                const toast = this.toasts.find(t => t.id === id);
                if (toast) toast.show = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 200);
            }, 3000);
        },
        
        get filteredContacts() {
            if (!this.searchQuery) return this.contacts;
            const query = this.searchQuery.toLowerCase();
            return this.contacts.filter(c => c.name.toLowerCase().includes(query));
        },
        
        async openChat(contact) {
            this.activeContact = contact;
            this.activeContactOnline = contact.is_online || false;
            this.sidebarOpen = false;
            this.loadingMessages = true;
            this.messages = [];
            this.editingMessageId = null;
            
            try {
                const res = await fetch(`/api/conversations/${contact.id}`);
                const data = await res.json();
                
                if (!res.ok) {
                    throw new Error(data.error || `HTTP ${res.status}`);
                }
                
                this.messages = (data.messages || []).map(m => ({
                    ...m,
                    isMine: m.sender_id === this.currentUserId,
                    time: m.created_at 
                        ? new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})
                        : '',
                    deleted: false
                }));
                
                // Mark unread contact messages as read
                if (contact.unread > 0) {
                    this.markAsRead(contact.id);
                    contact.unread = 0;
                }
                
                this.$nextTick(() => {
                    this.scrollToBottom();
                    this.$refs.messageInput?.focus();
                });
            } catch (err) {
                console.error('Failed to load messages:', err);
                this.showToast('Failed to load messages: ' + (err.message || 'Unknown error'), 'error');
            } finally {
                this.loadingMessages = false;
            }
        },
        
        async sendMessage() {
            const body = this.newMessage.trim();
            if ((!body && !this.selectedFiles.length) || this.sending) return;
            
            this.sending = true;
            const filesToSend = [...this.selectedFiles];
            
            // Optimistic update
            const tempId = 'temp-' + Date.now();
            const tempMessage = {
                id: tempId,
                body: body,
                sender_id: this.currentUserId,
                recipient_id: this.activeContact.id,
                isMine: true,
                time: 'Just now',
                attachments: filesToSend.map(f => ({ 
                    file_name: f.name, 
                    url: URL.createObjectURL(f),
                    temp: true 
                })),
                is_read: false,
                is_edited: false,
                deleted: false
            };
            
            this.messages.push(tempMessage);
            this.newMessage = '';
            this.selectedFiles = [];
            this.scrollToBottom();
            this.autoResize({ target: this.$refs.messageInput });
            
            try {
                const formData = new FormData();
                const isGroup = this.activeContact.type === 'group';
                if (isGroup) {
                    formData.append('section_id', String(this.activeContact.id).replace('group_', ''));
                } else {
                    formData.append('recipient_id', this.activeContact.id);
                }
                formData.append('body', body);
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                filesToSend.forEach((f, i) => formData.append(`attachments[${i}]`, f));
                
                const endpoint = isGroup ? '/api/messages/group' : '/api/messages';
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    body: formData
                });
                
                if (res.ok) {
                    const data = await res.json();
                    const idx = this.messages.findIndex(m => m.id === tempId);
                    if (idx !== -1 && data.message) {
                        this.messages[idx] = {
                            ...this.messages[idx],
                            id: data.message.id,
                            time: new Date(data.message.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}),
                            attachments: data.message.attachments || []
                        };
                    }
                    // Update contact preview
                    this.activeContact.lastMessage = 'You: ' + (body || 'Sent an attachment');
                } else {
                    // Try to get error details from response
                    let errorMsg = 'Failed to send message';
                    try {
                        const errorData = await res.json();
                        if (errorData.message) errorMsg = errorData.message;
                        else if (errorData.error) errorMsg = errorData.error;
                        else if (res.status === 419) errorMsg = 'Session expired. Please refresh the page.';
                        else if (res.status === 422) {
                            const errors = errorData.errors ? Object.values(errorData.errors).flat().join(', ') : 'Validation failed';
                            errorMsg = errors;
                        }
                    } catch (e) {
                        errorMsg = 'Server error (' + res.status + '). Please try again.';
                    }
                    throw new Error(errorMsg);
                }
            } catch (err) {
                console.error('Send failed:', err);
                // Remove optimistic message on failure
                this.messages = this.messages.filter(m => m.id !== tempId);
                this.showToast(err.message || 'Failed to send message. Please try again.', 'error');
            } finally {
                this.sending = false;
            }
        },
        
        startEdit(msg) {
            this.editingMessageId = msg.id;
            this.editMessageBody = msg.body;
            this.$nextTick(() => {
                this.$refs.editTextarea?.focus();
            });
        },
        
        cancelEdit() {
            this.editingMessageId = null;
            this.editMessageBody = '';
        },
        
        async saveEdit() {
            if (!this.editMessageBody.trim()) return;
            
            try {
                const res = await fetch(`/api/messages/${this.editingMessageId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ body: this.editMessageBody.trim() })
                });
                
                if (res.ok) {
                    const msg = this.messages.find(m => m.id === this.editingMessageId);
                    if (msg) {
                        msg.body = this.editMessageBody.trim();
                        msg.is_edited = true;
                    }
                    this.cancelEdit();
                    this.showToast('Message edited', 'success');
                } else {
                    throw new Error('Failed to edit');
                }
            } catch (err) {
                console.error('Edit failed:', err);
                this.showToast('Failed to edit message', 'error');
            }
        },
        
        confirmDelete(messageId) {
            this.deleteMessageId = messageId;
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
            this.deleteMessageId = null;
            if (this.deleteCountdownInterval) {
                clearInterval(this.deleteCountdownInterval);
                this.deleteCountdownInterval = null;
            }
        },
        
        async executeDelete() {
            if (!this.deleteMessageId || this.deleteCountdown > 0) return;
            
            try {
                const res = await fetch(`/api/messages/${this.deleteMessageId}`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                if (res.ok) {
                    const msg = this.messages.find(m => m.id === this.deleteMessageId);
                    if (msg) {
                        msg.deleted = true;
                        msg.body = '';
                        msg.attachments = [];
                    }
                    this.cancelDelete();
                    this.showToast('Message deleted', 'success');
                } else {
                    throw new Error('Failed to delete');
                }
            } catch (err) {
                console.error('Delete failed:', err);
                this.showToast('Failed to delete message', 'error');
            }
        },
        
        handleFileSelect(e) {
            const files = Array.from(e.target.files);
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'video/mp4', 'video/webm', 'video/quicktime', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain'];
            
            files.forEach(file => {
                if (file.size > maxSize) {
                    this.showToast(`"${file.name}" is too large. Max size is 10MB.`, 'error');
                    return;
                }
                if (file.type && !allowedTypes.includes(file.type)) {
                    this.showToast(`"${file.name}" file type not supported.`, 'error');
                    return;
                }
                this.selectedFiles.push(file);
            });
            
            e.target.value = ''; // Reset input
        },
        
        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },
        
        isImage(fileName) {
            if (!fileName) return false;
            const ext = fileName.split('.').pop().toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'].includes(ext);
        },
        
        isVideo(fileName) {
            if (!fileName) return false;
            const ext = fileName.split('.').pop().toLowerCase();
            return ['mp4', 'webm', 'mov', 'avi', 'mkv', 'flv', 'wmv'].includes(ext);
        },

        openLightbox(url) {
            this.lightboxImage = url;
            this.lightboxOpen = true;
        },
        
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        autoResize(e) {
            const textarea = e.target;
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 128) + 'px';
        },
        
        markAsRead(userId) {
            fetch(`/api/conversations/${userId}/read`, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).catch(() => {});
        },

        onScroll() {
            // Could implement pagination here
        }
    }
}
</script>

</body>
</html>
