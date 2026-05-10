<div class="max-w-5xl mx-auto">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center">
                    <i class="fas fa-bell"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Total</p>
                    <p class="text-xl font-bold text-slate-800"><?php echo e($total); ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center">
                    <i class="fas fa-envelope"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Unread</p>
                    <p class="text-xl font-bold text-slate-800"><?php echo e($unreadCount); ?></p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-medium">Read</p>
                    <p class="text-xl font-bold text-slate-800"><?php echo e($total - $unreadCount); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-slate-800">All Notifications</h3>
        <?php if($unreadCount > 0): ?>
            <form action="<?php echo e(route('notifications.mark-all-read')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors">
                    Mark all as read
                </button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-start gap-4 px-5 py-4 border-b border-slate-100 hover:bg-slate-50 transition-colors <?php echo e($notification->read_at ? 'opacity-70' : 'bg-indigo-50/20'); ?>">
                <div class="w-2.5 h-2.5 mt-2 rounded-full flex-shrink-0 <?php echo e($notification->read_at ? 'bg-slate-300' : 'bg-indigo-500'); ?>"></div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-semibold text-slate-800"><?php echo e($notification->title); ?></p>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-full uppercase tracking-wide
                            <?php echo e(match($notification->type ?? 'message') {
                                'event' => 'bg-purple-100 text-purple-700',
                                'announcement' => 'bg-amber-100 text-amber-700',
                                'grade' => 'bg-emerald-100 text-emerald-700',
                                'attendance' => 'bg-rose-100 text-rose-700',
                                default => 'bg-indigo-100 text-indigo-700',
                            }); ?>">
                            <?php echo e($notification->type); ?>

                        </span>
                    </div>
                    <p class="text-sm text-slate-600 mt-0.5"><?php echo e($notification->body); ?></p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="text-xs text-slate-400"><?php echo e($notification->created_at->diffForHumans()); ?></span>
                        <?php if($notification->data['url'] ?? false): ?>
                            <a href="<?php echo e($notification->data['url']); ?>" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">View details &rarr;</a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <?php if(!$notification->read_at): ?>
                        <form action="<?php echo e(route('notifications.mark-read', $notification->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                Mark read
                            </button>
                        </form>
                    <?php endif; ?>
                    <?php if($notification->is_real ?? false): ?>
                        <form action="<?php echo e(route('notifications.destroy', $notification->id)); ?>" method="POST" onsubmit="return confirm('Delete this notification?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors">
                                Delete
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="p-10 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-2xl">
                    <i class="fas fa-bell-slash"></i>
                </div>
                <h3 class="text-lg font-semibold text-slate-800 mb-1">No notifications</h3>
                <p class="text-slate-500 text-sm">You don't have any notifications at the moment.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($notifications->hasPages()): ?>
        <div class="mt-4">
            <?php echo e($notifications->links('pagination::tailwind')); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\notifications\partials\content.blade.php ENDPATH**/ ?>