@extends('layouts.app')

@section('title', 'Parent Communications')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{ activeTab: 'inbox', showCompose: false }">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Parent Communication Center</h1>
            <p class="text-slate-500">Send messages and announcements to parents</p>
        </div>
        <button @click="showCompose = true" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
            <i class="fas fa-plus mr-2"></i>New Message
        </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-inbox text-blue-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $unreadCount ?? 0 }}</p>
                    <p class="text-xs text-slate-500">Unread Messages</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-paper-plane text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $sentCount ?? 0 }}</p>
                    <p class="text-xs text-slate-500">Sent Today</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-amber-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $parentCount ?? 0 }}</p>
                    <p class="text-xs text-slate-500">Parents</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-purple-600"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-slate-800">{{ $announcementCount ?? 0 }}</p>
                    <p class="text-xs text-slate-500">Announcements</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Modal -->
    <div x-show="showCompose" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showCompose = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-slate-900">New Message</h3>
                    <button @click="showCompose = false" class="text-slate-400 hover:text-slate-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form action="{{ route('teacher.communications.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="recipient_type" value="multiple">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Recipients</label>
                            <select name="recipient_ids[]" multiple required
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                                @forelse($sections ?? [] as $section)
                                    @php
                                        $validEnrollments = $section->enrollments->filter(function($e) {
                                            return $e->student && $e->student->user_id && $e->student->user;
                                        });
                                    @endphp
                                    @if($validEnrollments->isNotEmpty())
                                        <optgroup label="{{ $section->name }}">
                                            @foreach($validEnrollments as $enrollment)
                                                <option value="{{ $enrollment->student->user_id }}">
                                                    {{ $enrollment->student->full_name }} (Parent)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @empty
                                    <option value="" disabled>No sections assigned</option>
                                @endforelse
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Hold Ctrl/Cmd to select multiple</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Subject</label>
                            <input type="text" name="subject" required
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Message</label>
                            <textarea name="body" rows="5" required
                                      class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Attachments (optional)</label>
                            <input type="file" name="attachments[]" multiple
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg">
                            <p class="text-xs text-slate-500 mt-1">Max 10MB per file</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Template (optional)</label>
                            <select x-on:change="document.querySelector('textarea[name=body]').value = $el.value"
                                    class="w-full px-3 py-2 border border-slate-200 rounded-lg">
                                <option value="">Select a template...</option>
                                <option value="Dear Parent, Your child has been performing well in class. Keep up the good work!">Performance Praise</option>
                                <option value="Dear Parent, This is to inform you that your child was absent today. Please provide an excuse letter.">Absence Notice</option>
                                <option value="Dear Parent, We would like to schedule a meeting to discuss your child's progress.">Meeting Request</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showCompose = false"
                                class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-paper-plane mr-2"></i>Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="border-b border-slate-200">
            <div class="flex">
                <button @click="activeTab = 'inbox'" 
                        :class="{ 'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'inbox' }"
                        class="px-6 py-3 text-sm font-medium text-slate-500 hover:text-slate-700">
                    <i class="fas fa-inbox mr-2"></i>Inbox
                </button>
                <button @click="activeTab = 'sent'"
                        :class="{ 'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'sent' }"
                        class="px-6 py-3 text-sm font-medium text-slate-500 hover:text-slate-700">
                    <i class="fas fa-paper-plane mr-2"></i>Sent
                </button>
            </div>
        </div>
        
        <div class="divide-y divide-slate-100">
            @forelse($messages ?? [] as $message)
                <a href="{{ route('teacher.communications.show', $message) }}" 
                   class="flex items-center gap-4 p-4 hover:bg-slate-50 transition-colors {{ $message->read_at ? '' : 'bg-indigo-50/50' }}">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                        {{ substr($message->sender->first_name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-slate-800">{{ $message->sender->name ?? 'Unknown' }}</span>
                            @if(!$message->read_at)
                                <span class="px-2 py-0.5 bg-indigo-500 text-white text-xs rounded-full">New</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-600 truncate">{{ $message->subject }}</p>
                        <p class="text-xs text-slate-400">{{ $message->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @empty
                <div class="p-8 text-center">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-inbox text-2xl text-slate-400"></i>
                    </div>
                    <p class="text-slate-500">No messages yet</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
