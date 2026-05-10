<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>PWA & Biometric Settings - Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        body { background: #f1f5f9; }
    </style>
</head>
<?php
$isPrincipal = $roleName === 'principal';
$useAlpineSidebar = in_array($roleName, ['admin', 'system admin', 'teacher', 'student', 'pupil', 'principal']);
?>

<?php
$isStudent = in_array($roleName, ['student', 'pupil']);
?>

<body class="<?php echo e($bodyBg ?? 'bg-slate-50'); ?> text-slate-800 antialiased"
      <?php if($isStudent): ?>
          x-data="{ sidebarCollapsed: false, mobileOpen: false }"
          x-init="if (window.innerWidth >= 1024) { sidebarCollapsed = false } else { mobileOpen = false }"
          @keydown.escape.window="mobileOpen = false"
      <?php elseif($useAlpineSidebar): ?>
          x-data="{ mobileOpen: false }"
          @keydown.escape.window="mobileOpen = false"
      <?php endif; ?>>


<?php if($sidebarView): ?>
    <?php echo $__env->make($sidebarView, array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>


<?php if($useAlpineSidebar): ?>
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
<?php endif; ?>


<?php if($useAlpineSidebar): ?>
<button @click="mobileOpen = !mobileOpen"
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>
<?php elseif($isPrincipal): ?>
<button onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>
<?php endif; ?>


<div class="min-h-screen <?php echo e($mainClass ?? 'lg:ml-72'); ?> transition-all duration-300">

    
    <div class="lg:hidden bg-white border-b border-slate-200 h-14 flex items-center px-4 sticky top-0 z-20">
        <div class="w-10"></div>
        <h1 class="font-semibold text-slate-800 flex-1 text-center">PWA & Biometric Settings</h1>
        <div class="w-10"></div>
    </div>

    <div class="p-4 lg:p-8 max-w-3xl mx-auto">
        
        
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-800">PWA & Biometric Settings</h1>
            <p class="text-slate-600">Manage your mobile app experience and biometric login</p>
        </div>

        
        <?php echo $__env->make('components.pwa-status', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <?php echo $__env->make('components.biometric-setup', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-bell text-blue-500 mr-2"></i>
                Notification Preferences
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-800">Grade Alerts</p>
                        <p class="text-sm text-slate-500">Get notified when new grades are posted</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer" onchange="saveNotificationPref('grades', this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-800">Announcements</p>
                        <p class="text-sm text-slate-500">School and class announcements</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer" onchange="saveNotificationPref('announcements', this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-800">Assignment Reminders</p>
                        <p class="text-sm text-slate-500">Due date reminders for assignments</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" class="sr-only peer" onchange="saveNotificationPref('assignments', this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-800">Attendance Alerts</p>
                        <p class="text-sm text-slate-500">Notify when marked absent or late</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer" onchange="saveNotificationPref('attendance', this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-medium text-slate-800">Messages</p>
                        <p class="text-sm text-slate-500">New message notifications</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" checked class="sr-only peer" onchange="saveNotificationPref('messages', this.checked)">
                        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    </label>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-database text-green-500 mr-2"></i>
                Offline Data
            </h2>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Cached Grades</p>
                            <p class="text-sm text-slate-500" id="cached-grades">Loading...</p>
                        </div>
                    </div>
                    <button onclick="clearCachedData('grades')" class="text-red-500 hover:text-red-700 text-sm font-medium">
                        Clear
                    </button>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Cached Attendance</p>
                            <p class="text-sm text-slate-500" id="cached-attendance">Loading...</p>
                        </div>
                    </div>
                    <button onclick="clearCachedData('attendance')" class="text-red-500 hover:text-red-700 text-sm font-medium">
                        Clear
                    </button>
                </div>

                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-800">Cached Announcements</p>
                            <p class="text-sm text-slate-500" id="cached-announcements">Loading...</p>
                        </div>
                    </div>
                    <button onclick="clearCachedData('announcements')" class="text-red-500 hover:text-red-700 text-sm font-medium">
                        Clear
                    </button>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-200">
                <button onclick="clearAllCachedData()" class="w-full py-2.5 border-2 border-red-200 text-red-600 rounded-lg font-medium hover:bg-red-50 transition">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Clear All Cached Data
                </button>
            </div>
        </div>

        
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-info-circle text-slate-500 mr-2"></i>
                App Information
            </h2>
            
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">App Version</span>
                    <span class="font-medium text-slate-800">1.0.0</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">PWA Status</span>
                    <span id="pwa-status" class="font-medium text-slate-800">Checking...</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">Service Worker</span>
                    <span id="sw-status" class="font-medium text-slate-800">Checking...</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">Push Notifications</span>
                    <span id="push-status" class="font-medium text-slate-800">Checking...</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">Cache Storage</span>
                    <span id="cache-size" class="font-medium text-slate-800">Calculating...</span>
                </div>
            </div>
        </div>

        
        <?php if(auth()->user()->role?->name === 'System Admin' || auth()->user()->role?->name === 'Admin'): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-tools text-purple-500 mr-2"></i>
                Developer Tools
            </h2>
            
            <div class="grid grid-cols-2 gap-3">
                <button onclick="location.reload()" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 font-medium transition">
                    <i class="fas fa-sync mr-2"></i>
                    Reload App
                </button>
                <button onclick="updateServiceWorker()" class="p-3 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 font-medium transition">
                    <i class="fas fa-download mr-2"></i>
                    Update SW
                </button>
                <button onclick="unregisterServiceWorker()" class="p-3 bg-red-100 hover:bg-red-200 rounded-lg text-red-700 font-medium transition">
                    <i class="fas fa-power-off mr-2"></i>
                    Unregister SW
                </button>
                <button onclick="testPushNotification()" class="p-3 bg-blue-100 hover:bg-blue-200 rounded-lg text-blue-700 font-medium transition">
                    <i class="fas fa-bell mr-2"></i>
                    Test Push
                </button>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>


<script src="<?php echo e(asset('js/pwa/biometric-auth.js')); ?>"></script>

<script>
// Notification preference saving
function saveNotificationPref(type, enabled) {
    try {
        const prefs = JSON.parse(localStorage.getItem('tessms-notifications') || '{}');
        prefs[type] = enabled;
        localStorage.setItem('tessms-notifications', JSON.stringify(prefs));
        showToast(type.charAt(0).toUpperCase() + type.slice(1) + ' notifications ' + (enabled ? 'enabled' : 'disabled'), 'success');
    } catch (e) {
        console.error('Failed to save notification preference:', e);
    }
}

// Load saved preferences on page load
document.addEventListener('DOMContentLoaded', () => {
    try {
        const prefs = JSON.parse(localStorage.getItem('tessms-notifications') || '{}');
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            const type = checkbox.getAttribute('onchange')?.match(/saveNotificationPref\('(\w+)'/)?.[1];
            if (type && prefs[type] !== undefined) {
                checkbox.checked = prefs[type];
            }
        });
    } catch (e) {
        console.error('Failed to load notification preferences:', e);
    }
});

// Check PWA status
document.addEventListener('DOMContentLoaded', async () => {
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                        window.navigator.standalone === true;
    document.getElementById('pwa-status').textContent = isStandalone ? 'Installed' : 'Browser';
    document.getElementById('pwa-status').className = isStandalone ? 'font-medium text-green-600' : 'font-medium text-amber-600';

    if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.ready;
        document.getElementById('sw-status').textContent = registration.active ? 'Active' : 'Inactive';
        document.getElementById('sw-status').className = registration.active ? 'font-medium text-green-600' : 'font-medium text-red-600';
    } else {
        document.getElementById('sw-status').textContent = 'Not Supported';
        document.getElementById('sw-status').className = 'font-medium text-red-600';
    }

    if ('PushManager' in window) {
        const permission = Notification.permission;
        document.getElementById('push-status').textContent = permission === 'granted' ? 'Enabled' : 
                                                             permission === 'denied' ? 'Blocked' : 'Not Set';
        document.getElementById('push-status').className = permission === 'granted' ? 'font-medium text-green-600' : 
                                                           permission === 'denied' ? 'font-medium text-red-600' : 'font-medium text-amber-600';
    } else {
        document.getElementById('push-status').textContent = 'Not Supported';
        document.getElementById('push-status').className = 'font-medium text-red-600';
    }

    if ('caches' in window) {
        const cacheNames = await caches.keys();
        let totalSize = 0;
        for (const name of cacheNames) {
            const cache = await caches.open(name);
            const requests = await cache.keys();
            totalSize += requests.length * 1024;
        }
        const sizeMB = (totalSize / 1024 / 1024).toFixed(2);
        document.getElementById('cache-size').textContent = sizeMB + ' MB (approx)';
    }

    if (window.getPendingSyncCount) {
        const count = await window.getPendingSyncCount();
        if (count > 0) {
            document.getElementById('cached-grades').textContent = count + ' pending uploads';
        } else {
            document.getElementById('cached-grades').textContent = 'Up to date';
        }
    }
});

async function clearCachedData(type) {
    if (!confirm('Clear cached ' + type + '?')) return;
    
    if ('caches' in window) {
        const cacheNames = await caches.keys();
        for (const name of cacheNames) {
            if (name.includes(type) || name.includes('dynamic')) {
                const cache = await caches.open(name);
                const requests = await cache.keys();
                for (const request of requests) {
                    if (request.url.includes(type)) {
                        await cache.delete(request);
                    }
                }
            }
        }
    }
    
    if (window.tessmsOffline && window.tessmsOffline.db) {
        // Implementation depends on your offline support
    }
    
    showToast('Cached ' + type + ' cleared!', 'success');
    location.reload();
}

async function clearAllCachedData() {
    if (!confirm('Clear ALL cached data? This will require re-downloading.')) return;
    
    if ('caches' in window) {
        const cacheNames = await caches.keys();
        await Promise.all(cacheNames.map(name => caches.delete(name)));
    }
    
    const dbs = await indexedDB.databases();
    dbs.forEach(db => {
        if (db.name) indexedDB.deleteDatabase(db.name);
    });
    
    showToast('All cached data cleared!', 'success');
    location.reload();
}

async function updateServiceWorker() {
    if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.ready;
        await registration.update();
        showToast('Service Worker updated!', 'success');
    }
}

async function unregisterServiceWorker() {
    if (!confirm('Unregister Service Worker? The app will not work offline.')) return;
    
    if ('serviceWorker' in navigator) {
        const registration = await navigator.serviceWorker.ready;
        await registration.unregister();
        showToast('Service Worker unregistered. Reloading...', 'success');
        setTimeout(() => location.reload(), 1500);
    }
}

async function testPushNotification() {
    try {
        const response = await fetch('/api/notifications/test', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (response.ok) {
            showToast('Test notification sent!', 'success');
        } else {
            showToast('Failed to send test notification', 'error');
        }
    } catch (error) {
        showToast('Error: ' + error.message, 'error');
    }
}

// Global toast function
function showToast(message, type = 'info') {
    const div = document.createElement('div');
    div.className = `fixed bottom-4 right-4 px-4 py-3 rounded-xl text-white z-50 shadow-lg transform transition-all duration-300 translate-y-0 ${
        type === 'success' ? 'bg-emerald-500' :
        type === 'error' ? 'bg-rose-500' : 'bg-blue-500'
    }`;
    div.innerHTML = `<div class="flex items-center gap-2"><i class="fas ${
        type === 'success' ? 'fa-check-circle' :
        type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'
    }"></i><span>${message}</span></div>`;
    document.body.appendChild(div);
    
    // Animate in
    requestAnimationFrame(() => {
        div.classList.add('opacity-100');
    });
    
    setTimeout(() => {
        div.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => div.remove(), 300);
    }, 3000);
}
</script>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\pwa-settings.blade.php ENDPATH**/ ?>