<!-- Notification Bell Component -->
<div x-data="notifications()" x-init="init()" class="relative">
    <!-- Bell Icon with Badge -->
    <button @click="toggle()" class="relative p-2 text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl transition-all">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        <!-- Unread Badge -->
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white animate-pulse"
                  x-text="unreadCount > 9 ? '9+' : unreadCount">
            </span>
        </template>
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-200 z-50 overflow-hidden"
         style="display: none;">
        
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Notifications</h3>
            <div class="flex items-center gap-2">
                <button @click="markAllRead()" 
                        x-show="unreadCount > 0"
                        class="text-xs text-indigo-600 hover:text-indigo-700 font-medium">
                    Mark all read
                </button>
                <button @click="open = false; settingsModalOpen = true" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="py-2">
            <?php if (isset($component)) { $__componentOriginal9e393e2811beadc8ba24897767594071 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e393e2811beadc8ba24897767594071 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton-loader','data' => ['type' => 'notification','count' => '4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'notification','count' => '4']); ?>
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

        <!-- Empty State -->
        <div x-show="!loading && notifications.length === 0" class="p-8 text-center text-slate-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            <p class="text-sm">No notifications yet</p>
        </div>

        <!-- Notifications List -->
        <div x-show="!loading && notifications.length > 0" class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <div @click="handleClick(notification)"
                     class="p-4 border-b border-slate-100 hover:bg-slate-50 cursor-pointer transition-colors"
                     :class="{ 'bg-indigo-50/50': !notification.read_at }">
                    <div class="flex items-start gap-3">
                        <!-- Icon based on type -->
                        <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                             :class="{
                                 'bg-indigo-100 text-indigo-600': notification.type === 'message',
                                 'bg-amber-100 text-amber-600': notification.type === 'announcement',
                                 'bg-emerald-100 text-emerald-600': notification.type === 'grade',
                                 'bg-rose-100 text-rose-600': notification.type === 'attendance',
                                 'bg-blue-100 text-blue-600': notification.type === 'assignment',
                                 'bg-purple-100 text-purple-600': notification.type === 'event'
                             }">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path x-show="notification.type === 'message'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                <path x-show="notification.type === 'announcement'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                <path x-show="notification.type === 'grade'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                <path x-show="notification.type === 'attendance'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                <path x-show="notification.type === 'assignment'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                <path x-show="notification.type === 'event'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-800" x-text="notification.title"></p>
                            <p class="text-xs text-slate-500 mt-0.5 line-clamp-2" x-text="notification.body"></p>
                            <p class="text-[10px] text-slate-400 mt-1" x-text="formatTime(notification.created_at)"></p>
                        </div>
                        
                        <!-- Unread Dot -->
                        <div x-show="!notification.read_at" class="w-2 h-2 bg-indigo-500 rounded-full flex-shrink-0 mt-1.5"></div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div x-show="notifications.length > 0" class="p-3 border-t border-slate-100 text-center">
            <button @click="openAllNotifications()" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                View all notifications
            </button>
        </div>
    </div>

    <!-- Settings Modal (teleported to body to escape backdrop-filter stacking) -->
    <template x-teleport="body">
        <div x-show="settingsModalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-[9999]"
             style="display: none;"
             @keydown.escape.window="settingsModalOpen = false"
             @close-settings-modal.window="settingsModalOpen = false">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="settingsModalOpen = false"></div>

            <!-- Centering wrapper -->
            <div class="relative flex min-h-screen items-center justify-center p-4">
                <!-- Modal Panel -->
                <div x-show="settingsModalOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="relative w-full max-w-xl rounded-2xl bg-white shadow-2xl"
                     style="display: none;"
                     @click.away="settingsModalOpen = false">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Notification Settings</h3>
                            <p class="text-xs text-slate-500">Choose how you want to be notified</p>
                        </div>
                        <button @click="settingsModalOpen = false" class="ml-4 inline-flex h-8 w-8 items-center justify-center rounded-full text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                            <i class="fas fa-times text-base"></i>
                        </button>
                    </div>
                    <!-- Modal Body -->
                    <div class="max-h-[70vh] overflow-y-auto px-6 py-5">
                        <?php echo $__env->make('notifications.settings-content', ['modal' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function notifications() {
    return {
        open: false,
        settingsModalOpen: false,
        loading: false,
        notifications: [],
        unreadCount: 0,
        audioInitialized: false,
        
        init() {
            this.fetchUnreadCount();
            // Poll for new notifications every 30 seconds
            setInterval(() => this.fetchUnreadCount(), 30000);
            
            // Initialize audio context on first user interaction (browser policy)
            const initAudio = () => {
                this.audioInitialized = true;
                document.removeEventListener('click', initAudio);
                document.removeEventListener('keydown', initAudio);
            };
            document.addEventListener('click', initAudio, { once: true });
            document.addEventListener('keydown', initAudio, { once: true });
        },
        
        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.fetchNotifications();
            }
        },
        
        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('<?php echo e(route('notifications.recent')); ?>', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                this.notifications = data.notifications;
                this.unreadCount = data.unread_count;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            }
            this.loading = false;
        },
        
        async fetchUnreadCount() {
            try {
                const response = await fetch('<?php echo e(route('notifications.unread-count')); ?>', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                const previousCount = this.unreadCount;
                this.unreadCount = data.count;
                
                // Play sound if new notifications arrived (not on initial load)
                if (previousCount > 0 && this.unreadCount > previousCount && this.audioInitialized) {
                    this.playNotificationSound();
                }
                // Also allow sound on first poll if count > 0 and user has interacted
                if (previousCount === 0 && this.unreadCount > 0 && this.audioInitialized) {
                    this.playNotificationSound();
                }
            } catch (error) {
                console.error('Failed to fetch unread count:', error);
            }
        },
        
        async markAllRead() {
            try {
                await fetch('<?php echo e(route('notifications.mark-all-read')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        },
        
        async handleClick(notification) {
            // Mark as read
            if (!notification.read_at) {
                try {
                    await fetch(`/notifications/${notification.id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    notification.read_at = new Date().toISOString();
                    this.unreadCount = Math.max(0, this.unreadCount - 1);
                } catch (error) {
                    console.error('Failed to mark as read:', error);
                }
            }
            
            // Navigate to URL if available
            if (notification.data?.url) {
                window.location.href = notification.data.url;
            }
        },
        
        playNotificationSound() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                
                const ctx = new AudioContext();
                const now = ctx.currentTime;
                
                // Create a pleasant notification bell sound
                const oscillator = ctx.createOscillator();
                const gainNode = ctx.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(ctx.destination);
                
                // Bell-like tone (sine wave)
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(523.25, now); // C5
                oscillator.frequency.exponentialRampToValueAtTime(659.25, now + 0.1); // E5
                
                // Envelope for bell sound
                gainNode.gain.setValueAtTime(0, now);
                gainNode.gain.linearRampToValueAtTime(0.3, now + 0.05);
                gainNode.gain.exponentialRampToValueAtTime(0.001, now + 1.5);
                
                oscillator.start(now);
                oscillator.stop(now + 1.5);
                
                // Second tone for a nicer chord
                const osc2 = ctx.createOscillator();
                const gain2 = ctx.createGain();
                osc2.connect(gain2);
                gain2.connect(ctx.destination);
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(783.99, now + 0.1); // G5
                gain2.gain.setValueAtTime(0, now + 0.1);
                gain2.gain.linearRampToValueAtTime(0.2, now + 0.15);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 1.2);
                osc2.start(now + 0.1);
                osc2.stop(now + 1.2);
            } catch (e) {
                console.error('Failed to play notification sound:', e);
            }
        },
        
        formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds
            
            if (diff < 60) return 'Just now';
            if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
            if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
            if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
            return date.toLocaleDateString();
        },
        
        openAllNotifications() {
            window.location.href = '<?php echo e(route('notifications.index')); ?>';
        }
    }
}
</script>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\components\notification-bell.blade.php ENDPATH**/ ?>