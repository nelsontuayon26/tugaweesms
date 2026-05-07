@extends('layouts.admin')

@section('title', 'Post Announcement')
@section('header-title', 'Post New Announcement')

@section('content')
<div class="max-w-3xl mx-auto" x-data="announcementForm()">
    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" @submit="return validateForm()">
        @csrf

        {{-- Priority & Target Row --}}
        <div class="p-6 border-b border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Target Audience --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Target Audience <span class="text-rose-500">*</span></label>
                    <select name="target" x-model="target" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                        <option value="students">Pupils</option>
                        <option value="teachers">Teachers</option>
                        <option value="all">Teachers & Pupils</option>
                    </select>
                </div>

                {{-- Priority --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Priority</label>
                    <select name="priority" x-model="priority" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm bg-white">
                        <option value="normal">Normal</option>
                        <option value="important">Important</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>

            {{-- Priority Preview --}}
            <div class="mt-4 p-3 rounded-xl text-sm flex items-center gap-3" :class="priorityColorClass">
                <i class="fas" :class="priorityIconClass"></i>
                <span x-text="priorityLabel"></span>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-6 border-b border-slate-100">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" x-model="title" maxlength="255" placeholder="Enter announcement title..." 
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Message <span class="text-rose-500">*</span></label>
                <textarea name="message" x-model="message" rows="8" maxlength="10000" placeholder="Write your announcement here..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none" required></textarea>
                <p class="text-xs text-slate-400 mt-1 text-right" x-text="message.length + '/10000'"></p>
            </div>

            {{-- Attachments --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Attachments (Optional)</label>
                <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-blue-300 transition-colors cursor-pointer" @click="$refs.fileInput.click()">
                    <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2"></i>
                    <p class="text-sm text-slate-500">Click to upload files</p>
                    <p class="text-xs text-slate-400 mt-1">Images, documents, PDFs (Max 10MB each)</p>
                    <input type="file" x-ref="fileInput" name="attachments[]" multiple @change="handleFiles($event)" class="hidden" accept="image/*,.pdf,.doc,.docx,.txt">
                </div>
                <div x-show="files.length > 0" class="mt-3 flex flex-wrap gap-2">
                    <template x-for="(file, i) in files" :key="i">
                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-xs border border-slate-200">
                            <i class="fas fa-file text-slate-500"></i>
                            <span class="truncate max-w-[150px]" x-text="file.name"></span>
                            <span class="text-slate-400" x-text="formatSize(file.size)"></span>
                            <button type="button" @click="removeFile(i)" class="text-rose-500 hover:text-rose-600 ml-1"><i class="fas fa-times"></i></button>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- Options --}}
        <div class="p-6 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="pinned" value="1" x-model="pinned" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="text-sm text-slate-700">Pin this announcement</span>
                    <i class="fas fa-thumbtack text-amber-500 text-xs"></i>
                </label>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-700">Expires:</span>
                    <input type="datetime-local" name="expires_at" x-model="expiresAt" class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="p-6 bg-slate-50 flex items-center justify-end gap-3">
            <a href="{{ route('admin.announcements.index') }}" class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Post Announcement
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function announcementForm() {
    return {
        target: 'students',
        priority: 'normal',
        title: '',
        message: '',
        pinned: false,
        expiresAt: '',
        files: [],

        get priorityColorClass() {
            const colors = { normal: 'bg-slate-100 text-slate-700', important: 'bg-amber-50 text-amber-700 border border-amber-200', urgent: 'bg-rose-50 text-rose-700 border border-rose-200' };
            return colors[this.priority];
        },
        get priorityIconClass() {
            const icons = { normal: 'fa-bullhorn', important: 'fa-star', urgent: 'fa-exclamation-circle' };
            return icons[this.priority];
        },
        get priorityLabel() {
            const labels = { normal: 'Normal announcement. Appears in the announcement feed.', important: 'Important — will be highlighted to recipients.', urgent: 'Urgent — recipients will see a prominent alert.' };
            return labels[this.priority];
        },

        handleFiles(e) {
            const newFiles = Array.from(e.target.files);
            const maxSize = 10 * 1024 * 1024;
            newFiles.forEach(file => {
                if (file.size > maxSize) { alert(`"${file.name}" is too large. Max size is 10MB.`); return; }
                this.files.push(file);
            });
            this.updateFileInput();
        },
        removeFile(index) { this.files.splice(index, 1); this.updateFileInput(); },
        updateFileInput() {
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            this.$refs.fileInput.files = dt.files;
        },
        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        },
        validateForm() {
            if (!this.title.trim() || !this.message.trim()) { alert('Please fill in both title and message.'); return false; }
            return true;
        }
    }
}
</script>
@endpush
@endsection
