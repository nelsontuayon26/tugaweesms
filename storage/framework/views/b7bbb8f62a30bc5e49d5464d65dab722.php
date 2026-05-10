
<div x-data="pwaStatus()" x-init="init()" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">
            <i class="fas fa-mobile-alt text-blue-500 mr-2"></i>
            Mobile App Status
        </h3>
        <span x-show="isInstalled" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
            <i class="fas fa-check mr-1"></i> Installed
        </span>
        <span x-show="!isInstalled && isInstallable" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-medium">
            Ready to Install
        </span>
    </div>

    
    <div x-show="!isInstalled && isInstallable" class="mb-4">
        <p class="text-sm text-slate-600 mb-3">
            Install TESSMS on your device for quick access and offline functionality.
        </p>
        <button @click="installApp()" 
                :disabled="installing"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-4 rounded-lg transition flex items-center justify-center space-x-2">
            <svg x-show="installing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <i x-show="!installing" class="fas fa-download"></i>
            <span x-text="installing ? 'Installing...' : 'Install App'"></span>
        </button>
    </div>

    
    <div class="space-y-3">
        
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div :class="isOnline ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'" 
                     class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <i :class="isOnline ? 'fas fa-wifi' : 'fas fa-wifi-slash'" class="text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-slate-800" x-text="isOnline ? 'Online' : 'Offline Mode'"></p>
                    <p class="text-xs text-slate-500" x-text="isOnline ? 'Connected to internet' : 'Working offline'"></p>
                </div>
            </div>
            <span :class="isOnline ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
                <span x-show="isOnline">Connected</span>
                <span x-show="!isOnline">Offline</span>
            </span>
        </div>

        
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div :class="swRegistered ? 'bg-green-100 text-green-600' : 'bg-yellow-100 text-yellow-600'" 
                     class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cog text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-slate-800">Background Sync</p>
                    <p class="text-xs text-slate-500">Auto-sync when online</p>
                </div>
            </div>
            <span :class="swRegistered ? 'text-green-600' : 'text-yellow-600'" class="text-sm font-medium">
                <span x-show="swRegistered">Active</span>
                <span x-show="!swRegistered">Pending</span>
            </span>
        </div>

        
        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
            <div class="flex items-center space-x-3">
                <div :class="pushEnabled ? 'bg-green-100 text-green-600' : 'bg-slate-200 text-slate-500'" 
                     class="w-10 h-10 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bell text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-slate-800">Push Notifications</p>
                    <p class="text-xs text-slate-500">Grade & announcement alerts</p>
                </div>
            </div>
            <button @click="togglePushNotifications()" 
                    :class="pushEnabled ? 'bg-green-600' : 'bg-slate-400'"
                    class="px-3 py-1.5 rounded text-white text-sm font-medium transition">
                <span x-text="pushEnabled ? 'On' : 'Off'"></span>
            </button>
        </div>

        
        <div x-show="pendingSync > 0" 
             class="flex items-center justify-between p-3 bg-amber-50 border border-amber-200 rounded-lg">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-sync-alt text-lg"></i>
                </div>
                <div>
                    <p class="font-medium text-amber-800">Pending Sync</p>
                    <p class="text-xs text-amber-600">Items waiting to upload</p>
                </div>
            </div>
            <span class="bg-amber-200 text-amber-800 px-3 py-1 rounded-full text-sm font-bold" 
                  x-text="pendingSync"></span>
        </div>
    </div>

    
    <div class="mt-4 pt-4 border-t border-slate-200">
        <div class="grid grid-cols-2 gap-3">
            <button @click="testNotification()" 
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition text-sm">
                <i class="fas fa-bell"></i>
                <span>Test Alert</span>
            </button>
            <button @click="syncNow()" 
                    :disabled="syncing || pendingSync === 0"
                    class="flex items-center justify-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition text-sm disabled:opacity-50">
                <svg x-show="syncing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i x-show="!syncing" class="fas fa-sync"></i>
                <span x-text="syncing ? 'Syncing...' : 'Sync Now'"></span>
            </button>
        </div>
    </div>

    
    <div class="mt-4">
        <button @click="showDebug = !showDebug" class="text-xs text-slate-400 hover:text-slate-600 flex items-center">
            <i :class="showDebug ? 'fas fa-chevron-down' : 'fas fa-chevron-right'" class="mr-1"></i>
            Technical Details
        </button>
        <div x-show="showDebug" x-transition class="mt-2 text-xs text-slate-500 space-y-1 font-mono bg-slate-50 p-3 rounded">
            <p>SW Registered: <span x-text="swRegistered ? 'Yes' : 'No'"></span></p>
            <p>Standalone: <span x-text="isStandalone ? 'Yes' : 'No'"></span></p>
            <p>Push Supported: <span x-text="pushSupported ? 'Yes' : 'No'"></span></p>
            <p>Sync Supported: <span x-text="syncSupported ? 'Yes' : 'No'"></span></p>
        </div>
    </div>
</div>

<script>
function pwaStatus() {
    return {
        isInstalled: false,
        isInstallable: false,
        isOnline: navigator.onLine,
        swRegistered: false,
        pushEnabled: false,
        pushSupported: false,
        syncSupported: false,
        isStandalone: false,
        pendingSync: 0,
        installing: false,
        syncing: false,
        showDebug: false,
        deferredPrompt: null,

        async init() {
            // Check online status
            window.addEventListener('online', () => this.isOnline = true);
            window.addEventListener('offline', () => this.isOnline = false);

            // Check if running as standalone PWA
            this.isStandalone = window.matchMedia('(display-mode: standalone)').matches 
                || window.navigator.standalone === true;
            this.isInstalled = this.isStandalone || localStorage.getItem('pwa-installed') === 'true';

            // Check service worker
            if ('serviceWorker' in navigator) {
                const registration = await navigator.serviceWorker.ready;
                this.swRegistered = !!registration.active;
                
                // Check push support
                this.pushSupported = 'PushManager' in window;
                if (this.pushSupported) {
                    const subscription = await registration.pushManager.getSubscription();
                    this.pushEnabled = !!subscription;
                }

                // Check sync support
                this.syncSupported = 'sync' in registration;
            }

            // Check installability
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.isInstallable = true;
            });

            window.addEventListener('appinstalled', () => {
                this.isInstalled = true;
                this.isInstallable = false;
                this.deferredPrompt = null;
                localStorage.setItem('pwa-installed', 'true');
            });

            // Get pending sync count
            this.updatePendingSync();
            setInterval(() => this.updatePendingSync(), 5000);
        },

        async installApp() {
            if (!this.deferredPrompt) return;
            
            this.installing = true;
            this.deferredPrompt.prompt();
            
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                this.isInstalled = true;
            }
            
            this.deferredPrompt = null;
            this.installing = false;
        },

        async togglePushNotifications() {
            if (!this.pushSupported) {
                alert('Push notifications are not supported on this device.');
                return;
            }

            if (this.pushEnabled) {
                // Unsubscribe
                const registration = await navigator.serviceWorker.ready;
                const subscription = await registration.pushManager.getSubscription();
                if (subscription) {
                    await subscription.unsubscribe();
                    // Notify server
                    await fetch('/api/notifications/unsubscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        },
                        body: JSON.stringify({ endpoint: subscription.endpoint })
                    });
                    this.pushEnabled = false;
                }
            } else {
                // Request permission and subscribe
                const permission = await Notification.requestPermission();
                if (permission === 'granted') {
                    // The registration script will handle the subscription
                    window.location.reload();
                }
            }
        },

        async testNotification() {
            try {
                const response = await fetch('/api/notifications/test', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
                
                if (response.ok) {
                    this.showToast('Test notification sent!', 'success');
                } else {
                    this.showToast('Failed to send test notification', 'error');
                }
            } catch (error) {
                this.showToast('Error: ' + error.message, 'error');
            }
        },

        async syncNow() {
            if (!this.syncSupported || this.pendingSync === 0) return;
            
            this.syncing = true;
            
            try {
                const registration = await navigator.serviceWorker.ready;
                await registration.sync.register('sync-attendance');
                await registration.sync.register('sync-grades');
                
                // Wait a moment then update count
                setTimeout(async () => {
                    await this.updatePendingSync();
                    this.syncing = false;
                    
                    if (this.pendingSync === 0) {
                        this.showToast('All data synced!', 'success');
                    }
                }, 2000);
            } catch (error) {
                this.syncing = false;
                this.showToast('Sync failed: ' + error.message, 'error');
            }
        },

        async updatePendingSync() {
            if (window.getPendingSyncCount) {
                this.pendingSync = await window.getPendingSyncCount();
            }
        },

        showToast(message, type = 'info') {
            if (window.showToast) {
                window.showToast(message, type);
            } else {
                // Simple fallback
                const div = document.createElement('div');
                div.className = `fixed bottom-4 right-4 px-4 py-2 rounded text-white z-50 ${
                    type === 'success' ? 'bg-green-500' : 
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                div.textContent = message;
                document.body.appendChild(div);
                setTimeout(() => div.remove(), 3000);
            }
        }
    };
}
</script>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\components\pwa-status.blade.php ENDPATH**/ ?>