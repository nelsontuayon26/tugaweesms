<?php $__env->startSection('title', 'Announcements'); ?>
<?php $__env->startSection('header-title', 'School Announcements'); ?>

<?php $__env->startSection('content'); ?>
<div x-data="announcementList()" class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">All Announcements</h1>
            <p class="text-sm text-slate-500 mt-1">Manage school-wide announcements</p>
        </div>
        <a href="<?php echo e(route('admin.announcements.create')); ?>" class="flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30">
            <i class="fas fa-plus"></i> New Announcement
        </a>
    </div>

    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider">Total</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($announcements->total()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Pinned</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($announcements->where('pinned', true)->count()); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Total Reads</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($announcements->sum('reads_count')); ?></p>
        </div>
        <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm">
            <p class="text-xs font-semibold text-rose-600 uppercase tracking-wider">Urgent</p>
            <p class="text-2xl font-bold text-slate-900 mt-1"><?php echo e($announcements->where('priority', 'urgent')->count()); ?></p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <?php if(session('success')): ?>
            <div class="p-4 bg-emerald-50 border-b border-emerald-100 text-emerald-700 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <?php if($announcements->isEmpty()): ?>
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-bullhorn text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-700">No announcements yet</h3>
                <p class="text-sm text-slate-400 mt-1">Create an announcement to reach pupils and teachers.</p>
                <a href="<?php echo e(route('admin.announcements.create')); ?>" class="mt-4 px-5 py-2.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                    <i class="fas fa-plus mr-1"></i> Create Announcement
                </a>
            </div>
        <?php else: ?>
            <div class="divide-y divide-slate-100">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="p-5 hover:bg-slate-50 transition-colors <?php echo e($announcement->pinned ? 'bg-amber-50/30' : ''); ?>">
                        <div class="flex items-start gap-4">
                            
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                                <?php echo e($announcement->priority === 'urgent' ? 'bg-rose-100 text-rose-600' : ($announcement->priority === 'important' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500')); ?>">
                                <i class="fas <?php echo e($announcement->priorityIcon()); ?>"></i>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    <?php if($announcement->pinned): ?>
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                            <i class="fas fa-thumbtack mr-1"></i>Pinned
                                        </span>
                                    <?php endif; ?>
                                    <span class="px-2 py-0.5 bg-<?php echo e($announcement->priorityColor()); ?>-100 text-<?php echo e($announcement->priorityColor()); ?>-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                        <?php echo e($announcement->priority); ?>

                                    </span>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-bold rounded-full uppercase tracking-wide">
                                        <?php echo e($announcement->target === 'all' ? 'Teachers & Pupils' : ($announcement->target === 'students' ? 'Pupils' : ucfirst($announcement->target))); ?>

                                    </span>
                                    <?php if($announcement->author): ?>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-medium rounded-full">
                                            by <?php echo e($announcement->author->full_name ?? $announcement->author->name); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>

                                <h3 class="font-semibold text-slate-900"><?php echo e($announcement->title); ?></h3>
                                <p class="text-sm text-slate-500 mt-1 line-clamp-2"><?php echo e(Str::limit($announcement->message, 150)); ?></p>

                                <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                    <span><i class="far fa-clock mr-1"></i><?php echo e($announcement->created_at->diffForHumans()); ?></span>
                                    <span><i class="far fa-eye mr-1"></i><?php echo e($announcement->reads_count); ?> read</span>
                                    <?php if($announcement->attachments->count() > 0): ?>
                                        <span><i class="fas fa-paperclip mr-1"></i><?php echo e($announcement->attachments->count()); ?></span>
                                    <?php endif; ?>
                                    <?php if($announcement->expires_at && $announcement->expires_at->isPast()): ?>
                                        <span class="text-rose-500"><i class="fas fa-history mr-1"></i>Expired</span>
                                    <?php elseif($announcement->expires_at): ?>
                                        <span><i class="fas fa-hourglass-half mr-1"></i>Expires <?php echo e($announcement->expires_at->diffForHumans()); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="<?php echo e(route('admin.announcements.show', $announcement)); ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo e(route('admin.announcements.edit', $announcement)); ?>" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo e(route('admin.announcements.pin', $announcement)); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="p-2 <?php echo e($announcement->pinned ? 'text-amber-500 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-amber-500 hover:bg-amber-50'); ?> rounded-lg transition-colors" title="<?php echo e($announcement->pinned ? 'Unpin' : 'Pin'); ?>">
                                        <i class="fas fa-thumbtack"></i>
                                    </button>
                                </form>
                                <button @click="confirmDelete(<?php echo e($announcement->id); ?>)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="p-4 border-t border-slate-100">
                <?php echo e($announcements->links()); ?>

            </div>
        <?php endif; ?>
    </div>


<div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 backdrop-blur-sm" x-transition.opacity>
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all" x-transition.scale>
        <div class="text-center">
            <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-2xl text-rose-500"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Announcement?</h3>
            <p class="text-slate-500 text-sm mb-6">This action cannot be undone. The announcement and all its attachments will be permanently removed.</p>
            <div class="flex gap-3">
                <button @click="cancelDelete()" class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                <button @click="executeDelete()" :disabled="deleteCountdown > 0" class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                    <template x-if="deleteCountdown > 0">
                        <span class="flex items-center gap-1.5"><i class="fas fa-clock text-sm"></i><span x-text="deleteCountdown + 's'"></span></span>
                    </template>
                    <span x-show="deleteCountdown === 0">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function announcementList() {
    return {
        deleteModalOpen: false,
        deleteAnnouncementId: null,
        deleteCountdown: 3,
        deleteCountdownInterval: null,

        confirmDelete(id) {
            this.deleteAnnouncementId = id;
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
            this.deleteAnnouncementId = null;
            if (this.deleteCountdownInterval) {
                clearInterval(this.deleteCountdownInterval);
                this.deleteCountdownInterval = null;
            }
        },
        async executeDelete() {
            if (!this.deleteAnnouncementId || this.deleteCountdown > 0) return;
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const res = await fetch('/admin/announcements/' + this.deleteAnnouncementId, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams({ '_method': 'DELETE' })
                });
                if (res.ok || res.redirected) {
                    window.location.reload();
                } else {
                    alert('Failed to delete announcement. Please try again.');
                }
            } catch (err) {
                console.error('Delete failed:', err);
                alert('Failed to delete announcement. Please try again.');
            }
        }
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\announcements\index.blade.php ENDPATH**/ ?>