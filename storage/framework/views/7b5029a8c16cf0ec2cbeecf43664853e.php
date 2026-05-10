<?php
$user = auth()->user();
$student = $user->student;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Announcements - Pupil Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>* { font-family: 'Inter', sans-serif; } body { background: #f1f5f9; } [x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden" x-data="announcementFeed()" x-init="init(); if (window.innerWidth < 1024) mobileOpen = false">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
     style="display: none;">
</div>

<div class="flex h-screen">
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="flex-1 lg:ml-72 h-screen flex flex-col bg-slate-50 overflow-hidden">
        
        <div class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-4">
                <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Announcements</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Important updates from your teachers and school</p>
                </div>
            </div>
            <?php if($unreadCount > 0): ?>
                <button @click="markAllAsRead()" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors" :disabled="markingAll">
                    <svg x-show="markingAll" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <i x-show="!markingAll" class="fas fa-check-double"></i>
                    <span x-text="markingAll ? 'Marking...' : 'Mark All as Read'"></span>
                </button>
            <?php endif; ?>
        </div>

        
        <div class="flex-1 overflow-y-auto p-6" id="announcements-feed">
            <?php if($announcements->isEmpty()): ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-bullhorn text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-700">No announcements yet</h3>
                    <p class="text-sm text-slate-400 mt-1">Check back later for updates from your teachers.</p>
                </div>
            <?php else: ?>
                <div class="max-w-3xl mx-auto space-y-4">
                    
                    <?php $pinned = $announcements->where('pinned', true); ?>
                    <?php if($pinned->isNotEmpty()): ?>
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-3 px-1">
                                <i class="fas fa-thumbtack text-amber-500 text-sm"></i>
                                <span class="text-xs font-bold text-amber-600 uppercase tracking-wider">Pinned</span>
                            </div>
                            <?php $__currentLoopData = $pinned; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo $__env->make('student.announcements._item', ['announcement' => $announcement], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php $regular = $announcements->where('pinned', false); ?>
                    <?php if($regular->isNotEmpty()): ?>
                        <?php if($pinned->isNotEmpty()): ?>
                            <div class="flex items-center gap-2 mb-3 px-1 pt-2">
                                <i class="fas fa-stream text-slate-400 text-sm"></i>
                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Recent</span>
                            </div>
                        <?php endif; ?>
                        <?php $__currentLoopData = $regular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('student.announcements._item', ['announcement' => $announcement], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<div x-show="toast.show" x-transition class="fixed top-4 right-4 z-50">
    <div class="bg-indigo-600 text-white px-4 py-3 rounded-xl shadow-lg flex items-center gap-3">
        <i class="fas fa-bullhorn"></i>
        <div>
            <p class="text-sm font-semibold" x-text="toast.title"></p>
            <p class="text-xs opacity-80">New announcement posted</p>
        </div>
        <button @click="toast.show = false" class="ml-2 text-white/70 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>

<script>
function announcementFeed() {
    return {
        mobileOpen: false,
        sidebarCollapsed: false,
        markingAll: false,
        toast: { show: false, title: '' },

        init() {
            // Listen for real-time announcements
            if (typeof Echo !== 'undefined') {
                Echo.channel('announcements')
                    .listen('.announcement.posted', (e) => {
                        this.showToast(e.title);
                        // Reload after a short delay to show the new announcement
                        setTimeout(() => window.location.reload(), 3000);
                    });
            }
        },

        async markAllAsRead() {
            this.markingAll = true;
            try {
                const res = await fetch('/api/announcements/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"').content,
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    // Remove unread styling
                    document.querySelectorAll('.announcement-unread').forEach(el => {
                        el.classList.remove('announcement-unread', 'border-l-4', 'border-indigo-500', 'bg-indigo-50/30');
                    });
                    document.querySelectorAll('.unread-badge').forEach(el => el.remove());
                }
            } catch (err) {
                console.error('Failed to mark all as read:', err);
            } finally {
                this.markingAll = false;
            }
        },

        showToast(title) {
            this.toast.title = title;
            this.toast.show = true;
            setTimeout(() => this.toast.show = false, 5000);
        }
    }
}
</script>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\announcements\index.blade.php ENDPATH**/ ?>