<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    
    <?php echo $__env->make('partials.pwa-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Alpine.js x-cloak: hide elements until Alpine initializes */
        [x-cloak] { display: none !important; }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Dark mode scrollbar */
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #475569;
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Sidebar Transition */
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile Overlay */
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.5);
            backdrop-filter: blur(4px);
        }
        
        /* Responsive Tables */
        .responsive-table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Card Grid Responsive */
        .responsive-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .responsive-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .responsive-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (min-width: 1280px) {
            .responsive-grid { grid-template-columns: repeat(4, 1fr); }
        }
        
        /* Stats Grid Responsive */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        @media (min-width: 640px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); gap: 1rem; }
        }
        @media (min-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(5, 1fr); }
        }
        
        /* Form Grid Responsive */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        @media (min-width: 768px) {
            .form-grid { grid-template-columns: repeat(2, 1fr); }
        }
        
        /* Action Buttons Responsive */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        @media (min-width: 640px) {
            .action-buttons {
                flex-direction: row;
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 antialiased" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">
    <?php echo $__env->make('partials.page-loader', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    
    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;">
    </div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        
        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-72">
            
            <!-- Mobile Header -->
            <header class="lg:hidden sticky top-0 z-30 bg-white/95 backdrop-blur-xl border-b border-slate-200 h-16 flex-shrink-0">
                <div class="flex items-center justify-between h-full px-4 pl-16">
                    <div class="flex items-center gap-3">
                        <!-- Hamburger moved to fixed button above -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0">
                                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Tugawe Elementary School Logo" class="w-full h-full object-contain">
                            </div>
                            <span class="font-bold text-slate-800">Tugawe Elementary School</span>
                        </div>
                    </div>
                    
                    <!-- Mobile User Avatar -->
                    <div class="flex items-center gap-2">
                        <?php if(auth()->user()->photo): ?>
                            <img src="<?php echo e(profile_photo_url(auth()->user()->photo)); ?>" 
                                 alt="Admin" 
                                 class="w-9 h-9 rounded-full border-2 border-slate-200 object-cover">
                        <?php else: ?>
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 border-2 border-slate-200 flex items-center justify-center text-white font-bold text-sm">
                                <?php echo e(strtoupper(substr(auth()->user()->first_name ?? 'A', 0, 1))); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Desktop Header -->
            <header class="hidden lg:flex sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-16 flex-shrink-0 items-center justify-between px-8">
                <div>
                    <h2 class="text-lg font-bold text-slate-800"><?php echo $__env->yieldContent('header-title', 'Dashboard'); ?></h2>
                    <p class="text-xs text-slate-500"><?php echo e(now()->format('l, F d, Y')); ?></p>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notifications -->
                    <div class="relative" x-data="notificationDropdown()" x-init="initNotifications()">
                        <button @click="open = !open" class="relative p-2.5 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
                            <i class="fas fa-bell text-lg"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-rose-500 rounded-full border-2 border-white flex items-center justify-center"></span>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 z-50 overflow-hidden">
                            <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50">
                                <h3 class="font-semibold text-sm text-slate-800">Notifications</h3>
                                <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">Mark all read</button>
                            </div>
                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                <template x-if="loading">
                                    <div class="py-2">
                                        <?php if (isset($component)) { $__componentOriginal9e393e2811beadc8ba24897767594071 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e393e2811beadc8ba24897767594071 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton-loader','data' => ['type' => 'notification','count' => '3']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'notification','count' => '3']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e393e2811beadc8ba24897767594071)): ?>
<?php $attributes = $__attributesOriginal9e393e2811beadc8ba24897767594071; ?>
<?php unset($__attributesOriginal9e393e2811beadc8ba24897767594071); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e393e2811beadc8ba24897767594071)): ?>
<?php $component = $__componentOriginal9e393e2811beadc8ba24897767594071; ?>
<?php unset($__componentOriginal9e393e2811beadc8ba24897767594071); ?>
<?php endif; ?>
                                    </div>
                                </template>
                                <template x-if="!loading && notifications.length === 0">
                                    <div class="p-6 text-center text-sm text-slate-500">
                                        <i class="fas fa-bell-slash text-slate-300 text-2xl mb-2"></i>
                                        <p>No notifications yet</p>
                                    </div>
                                </template>
                                <template x-for="n in notifications" :key="n.id">
                                    <div @click="handleClick(n)" class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 cursor-pointer transition-colors" :class="n.read_at ? 'opacity-70' : 'bg-indigo-50/30'">
                                        <div class="flex items-start gap-3">
                                            <div class="w-2 h-2 mt-2 rounded-full flex-shrink-0" :class="n.read_at ? 'bg-slate-300' : 'bg-indigo-500'"></div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-slate-800 leading-tight" x-text="n.title"></p>
                                                <p class="text-xs text-slate-600 mt-0.5 line-clamp-2" x-text="n.body"></p>
                                                <p class="text-[10px] text-slate-400 mt-1" x-text="timeAgo(n.created_at)"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <a href="<?php echo e(route('notifications.index')); ?>" class="block text-center px-4 py-2 text-xs font-medium text-indigo-600 bg-slate-50 hover:bg-slate-100 border-t border-slate-100">View all notifications</a>
                        </div>
                    </div>
                </div>
                
                <script>
                function notificationDropdown() {
                    return {
                        open: false,
                        loading: false,
                        notifications: [],
                        unreadCount: 0,
                        initNotifications() {
                            this.fetchUnreadCount();
                            this.$watch('open', value => { if (value) this.fetchRecent(); });
                            setInterval(() => this.fetchUnreadCount(), 30000);
                        },
                        fetchUnreadCount() {
                            fetch('<?php echo e(route('notifications.unread-count')); ?>', { credentials: 'include' })
                                .then(r => r.json())
                                .then(data => this.unreadCount = data.count || 0)
                                .catch(() => {});
                        },
                        fetchRecent() {
                            this.loading = true;
                            fetch('<?php echo e(route('notifications.recent')); ?>', { credentials: 'include' })
                                .then(r => r.json())
                                .then(data => {
                                    this.notifications = data.notifications || [];
                                    this.unreadCount = data.unread_count || 0;
                                    this.loading = false;
                                })
                                .catch(() => { this.loading = false; });
                        },
                        markAllRead() {
                            fetch('<?php echo e(route('notifications.mark-all-read')); ?>', {
                                method: 'POST',
                                credentials: 'include',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
                            })
                            .then(r => r.json())
                            .then(() => {
                                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                                this.unreadCount = 0;
                            })
                            .catch(() => {});
                        },
                        handleClick(n) {
                            if (!n.read_at) {
                                fetch(`<?php echo e(url('notifications')); ?>/${n.id}/read`, {
                                    method: 'POST',
                                    credentials: 'include',
                                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' }
                                })
                                .then(() => {
                                    n.read_at = new Date().toISOString();
                                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                                })
                                .catch(() => {});
                            }
                            if (n.data && n.data.url) {
                                window.location.href = n.data.url;
                            }
                        },
                        timeAgo(dateString) {
                            const date = new Date(dateString);
                            const now = new Date();
                            const seconds = Math.floor((now - date) / 1000);
                            if (seconds < 60) return 'Just now';
                            const minutes = Math.floor(seconds / 60);
                            if (minutes < 60) return minutes + 'm ago';
                            const hours = Math.floor(minutes / 60);
                            if (hours < 24) return hours + 'h ago';
                            const days = Math.floor(hours / 24);
                            if (days < 7) return days + 'd ago';
                            return date.toLocaleDateString();
                        }
                    }
                }
                </script>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\layouts\admin.blade.php ENDPATH**/ ?>