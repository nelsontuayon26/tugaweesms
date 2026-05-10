<?php
$isUnread = !$announcement->isReadBy(auth()->id());
?>
<a href="<?php echo e(route('student.announcements.show', $announcement)); ?>" 
   class="block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all <?php echo e($isUnread ? 'announcement-unread border-l-4 border-indigo-500 bg-indigo-50/30' : ''); ?>">
    <div class="p-5">
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
                    <?php if($isUnread): ?>
                        <span class="unread-badge px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded-full uppercase tracking-wide">New</span>
                    <?php endif; ?>
                    <span class="px-2 py-0.5 bg-<?php echo e($announcement->priorityColor()); ?>-100 text-<?php echo e($announcement->priorityColor()); ?>-700 text-[10px] font-bold rounded-full uppercase tracking-wide">
                        <?php echo e($announcement->priority); ?>

                    </span>
                </div>

                <h3 class="font-semibold text-slate-900 <?php echo e($isUnread ? 'text-slate-900' : ''); ?>"><?php echo e($announcement->title); ?></h3>
                <p class="text-sm text-slate-500 mt-1 line-clamp-2"><?php echo e(Str::limit($announcement->message, 150)); ?></p>

                <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                    <span class="flex items-center gap-1">
                        <div class="w-5 h-5 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 flex items-center justify-center text-white text-[9px] font-bold">
                            <?php echo e(strtoupper(substr($announcement->author?->first_name ?? 'A', 0, 1))); ?>

                        </div>
                        <?php echo e($announcement->author?->full_name ?? 'Admin'); ?>

                    </span>
                    <span><i class="far fa-clock mr-1"></i><?php echo e($announcement->created_at->diffForHumans()); ?></span>
                    <?php if($announcement->attachments->count() > 0): ?>
                        <span><i class="fas fa-paperclip mr-1"></i><?php echo e($announcement->attachments->count()); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="shrink-0">
                <i class="fas fa-chevron-right text-slate-300"></i>
            </div>
        </div>
    </div>
</a>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\announcements\_item.blade.php ENDPATH**/ ?>