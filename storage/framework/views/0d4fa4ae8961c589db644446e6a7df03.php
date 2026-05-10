<?php
$teacher = auth()->user()->teacher;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Edit Announcement - Teacher Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="announcementForm()" x-init="if (window.innerWidth < 1024) mobileOpen = false">

<div class="flex h-screen">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Edit Announcement</h1>
                <p class="text-sm text-slate-500 mt-0.5">Update your announcement</p>
            </div>
            <a href="<?php echo e(route('teacher.announcements.index')); ?>" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                <i class="fas fa-arrow-left"></i> Back to Announcements
            </a>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6">
            <div class="max-w-3xl mx-auto">
                <form action="<?php echo e(route('teacher.announcements.update', $announcement)); ?>" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" @submit="return validateForm()">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="p-6 border-b border-slate-100">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Target Audience <span class="text-rose-500">*</span></label>
                                <input type="hidden" name="target" value="students">
                                <div class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-sm text-slate-600">
                                    Pupils
                                </div>
                            </div>

                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Priority</label>
                                <select name="priority" x-model="priority" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm bg-white">
                                    <option value="normal">Normal</option>
                                    <option value="important">Important</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="mt-4 p-3 rounded-xl text-sm flex items-center gap-3"
                             :class="priorityColorClass">
                            <i class="fas" :class="priorityIconClass"></i>
                            <span x-text="priorityLabel"></span>
                        </div>
                    </div>

                    
                    <div class="p-6 border-b border-slate-100">
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Title <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" x-model="title" maxlength="255" placeholder="Enter announcement title..." 
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Message <span class="text-rose-500">*</span></label>
                            <textarea name="message" x-model="message" rows="8" maxlength="10000" placeholder="Write your announcement here..."
                                      class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm resize-none"
                                      required></textarea>
                            <p class="text-xs text-slate-400 mt-1 text-right" x-text="message.length + '/10000'"></p>
                        </div>

                        
                        <?php if($announcement->attachments->count() > 0): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Current Attachments</label>
                                <div class="flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $announcement->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-100 rounded-lg text-xs border border-slate-200 group">
                                            <?php if($att->isImage()): ?>
                                                <img src="<?php echo e($att->url()); ?>" class="w-8 h-8 rounded object-cover">
                                            <?php else: ?>
                                                <i class="fas fa-file text-slate-500"></i>
                                            <?php endif; ?>
                                            <span class="truncate max-w-[120px]"><?php echo e($att->file_name); ?></span>
                                            <label class="flex items-center gap-1 cursor-pointer text-rose-500 hover:text-rose-600 ml-1">
                                                <input type="checkbox" name="remove_attachments[]" value="<?php echo e($att->id); ?>" class="rounded border-slate-300 text-rose-500 focus:ring-rose-500">
                                                <i class="fas fa-trash"></i>
                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <p class="text-xs text-slate-400 mt-1">Check the box to remove an attachment</p>
                            </div>
                        <?php endif; ?>

                        
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Add New Attachments (Optional)</label>
                            <div class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center hover:border-indigo-300 transition-colors cursor-pointer"
                                 @click="$refs.fileInput.click()">
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
                                        <button type="button" @click="removeFile(i)" class="text-rose-500 hover:text-rose-600 ml-1">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-6 border-b border-slate-100">
                        <div class="flex flex-wrap items-center gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="pinned" value="1" x-model="pinned" class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                <span class="text-sm text-slate-700">Pin this announcement</span>
                                <i class="fas fa-thumbtack text-amber-500 text-xs"></i>
                            </label>

                            <div class="flex items-center gap-2">
                                <span class="text-sm text-slate-700">Expires:</span>
                                <input type="datetime-local" name="expires_at" x-model="expiresAt" 
                                       class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    
                    <div class="p-6 bg-slate-50 flex items-center justify-end gap-3">
                        <a href="<?php echo e(route('teacher.announcements.index')); ?>" class="px-6 py-2.5 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-violet-600 rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function announcementForm() {
    return {
        mobileOpen: false,
        target: '<?php echo e($announcement->target); ?>',
        priority: '<?php echo e($announcement->priority); ?>',
        title: <?php echo json_encode($announcement->title, 15, 512) ?>,
        message: <?php echo json_encode($announcement->message, 15, 512) ?>,
        pinned: <?php echo e($announcement->pinned ? 'true' : 'false'); ?>,
        expiresAt: '<?php echo e($announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : ''); ?>',
        files: [],

        get priorityColorClass() {
            const colors = {
                normal: 'bg-slate-100 text-slate-700',
                important: 'bg-amber-50 text-amber-700 border border-amber-200',
                urgent: 'bg-rose-50 text-rose-700 border border-rose-200',
            };
            return colors[this.priority];
        },

        get priorityIconClass() {
            const icons = {
                normal: 'fa-bullhorn',
                important: 'fa-star',
                urgent: 'fa-exclamation-circle',
            };
            return icons[this.priority];
        },

        get priorityLabel() {
            const labels = {
                normal: 'This is a normal announcement. It will appear in the announcement feed.',
                important: 'This is marked as important. It will be highlighted to pupils.',
                urgent: 'This is marked as urgent. Pupils will see a prominent alert.',
            };
            return labels[this.priority];
        },

        handleFiles(e) {
            const newFiles = Array.from(e.target.files);
            const maxSize = 10 * 1024 * 1024;
            newFiles.forEach(file => {
                if (file.size > maxSize) {
                    alert(`"${file.name}" is too large. Max size is 10MB.`);
                    return;
                }
                this.files.push(file);
            });
            this.updateFileInput();
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.updateFileInput();
        },

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
            if (!this.title.trim() || !this.message.trim()) {
                alert('Please fill in both title and message.');
                return false;
            }
            return true;
        }
    }
}
</script>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\announcements\edit.blade.php ENDPATH**/ ?>