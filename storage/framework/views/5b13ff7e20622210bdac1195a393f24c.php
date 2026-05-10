<?php
$roleName = auth()->user()->role?->name ?? '';
$roleLower = strtolower($roleName);
$isAdmin = in_array($roleLower, ['system admin', 'registrar', 'admin']);
$isTeacher = $roleLower === 'teacher' || auth()->user()->teacher !== null;
$isStudent = $roleLower === 'pupil' || auth()->user()->student !== null;
?>

<?php if($isAdmin): ?>
    
    <?php $__env->startSection('title', 'Notification Settings'); ?>
    <?php $__env->startSection('header-title', 'Notification Settings'); ?>
    <?php $__env->startSection('content'); ?>
        <?php echo $__env->make('notifications.settings-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__env->stopSection(); ?>
<?php elseif($isTeacher): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title>Notification Settings | Teacher Portal</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <style>
            * { font-family: 'Plus Jakarta Sans', sans-serif; }
            body { background: #f1f5f9; color: #1e293b; }
            .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            .toast { animation: slideIn 0.3s ease-out; backdrop-filter: blur(12px); }
        </style>
    </head>
    <body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden">
        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

        <!-- Mobile Overlay -->
        <div id="mobileOverlay" class="fixed inset-0 z-40 hidden lg:hidden bg-slate-900/30 backdrop-blur-sm transition-opacity duration-300" onclick="toggleSidebar()"></div>

        <!-- Main Layout -->
        <div class="flex min-h-screen">
            <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <div class="flex-1 lg:ml-[280px] min-h-screen flex flex-col">
                <!-- Header -->
                <header class="sticky top-0 z-30 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 h-16 flex-shrink-0">
                    <div class="flex items-center justify-between h-full px-4 lg:px-8">
                        <div class="flex items-center gap-4">
                            <button class="lg:hidden p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all" onclick="toggleMobileMenu()">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            <div class="hidden sm:block">
                                <h2 class="text-lg font-bold text-slate-800">Notification Settings</h2>
                                <p class="text-xs text-slate-500">Manage your notification preferences</p>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Content -->
                <main class="flex-1 p-4 lg:p-8 overflow-x-hidden">
                    <?php echo $__env->make('notifications.settings-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </main>
            </div>
        </div>

        <script>
            function toggleMobileMenu() {
                const sidebar = document.querySelector('.flex-col.w-72.h-screen.fixed');
                const overlay = document.getElementById('mobileOverlay');
                sidebar?.classList.toggle('-translate-x-full');
                overlay?.classList.toggle('hidden');
            }
            function toggleSidebar() {
                const sidebar = document.querySelector('.flex-col.w-72.h-screen.fixed');
                const overlay = document.getElementById('mobileOverlay');
                sidebar?.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
            }
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                if (!container) return;
                const colors = { success: 'bg-emerald-500', error: 'bg-rose-500', info: 'bg-indigo-500', warning: 'bg-amber-500' };
                const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle', warning: 'fa-exclamation-triangle' };
                const toast = document.createElement('div');
                toast.className = `toast flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white ${colors[type]} min-w-[300px] cursor-pointer`;
                toast.innerHTML = `<i class="fas ${icons[type]}"></i><span class="font-medium text-sm">${message}</span><button onclick="this.parentElement.remove()" class="ml-auto hover:opacity-75"><i class="fas fa-times"></i></button>`;
                toast.onclick = () => toast.remove();
                container.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(100%)'; setTimeout(() => toast.remove(), 300); }, 4000);
            }
            document.addEventListener('keydown', e => { if (e.key === 'Escape') toggleSidebar(); });
        </script>
    </body>
    </html>
<?php elseif($isStudent): ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title>Notification Settings | Pupil Portal</title>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
            body { font-family: 'Inter', sans-serif; }
            [x-cloak] { display: none !important; }
            .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
            .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
            @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
            .toast { animation: slideIn 0.3s ease-out; backdrop-filter: blur(12px); }
        </style>
    </head>
    <body class="min-h-screen bg-slate-50 font-sans antialiased"
          x-data="{ sidebarCollapsed: false, mobileOpen: false }"
          x-init="if (window.innerWidth >= 1024) { sidebarCollapsed = false; } else { mobileOpen = false; }"
          @resize.window="if (window.innerWidth < 1024) { sidebarCollapsed = false; }">

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

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

        <!-- Mobile Toggle Button -->
        <button @click="mobileOpen = !mobileOpen"
                class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-105 hover:shadow-xl transition-all duration-200 border border-slate-100">
            <i class="fas fa-bars text-lg"></i>        </button>

        <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <main class="min-h-screen transition-all duration-300 ease-out"
              :class="{ 'lg:ml-0': sidebarCollapsed, 'lg:ml-72': !sidebarCollapsed }">

            <!-- Top Header -->
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-sm text-slate-500">
                        <a href="<?php echo e(url()->previous()); ?>" class="hover:text-slate-700 cursor-pointer transition-colors">Back</a>
                        <i class="fas fa-chevron-right text-xs"></i>
                        <span class="text-slate-800 font-medium">Notification Settings</span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-6 max-w-7xl mx-auto">
                <?php echo $__env->make('notifications.settings-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </main>

        <script>
            function showToast(message, type = 'info') {
                const container = document.getElementById('toastContainer');
                if (!container) return;
                const colors = { success: 'bg-emerald-500', error: 'bg-rose-500', info: 'bg-indigo-500', warning: 'bg-amber-500' };
                const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', info: 'fa-info-circle', warning: 'fa-exclamation-triangle' };
                const toast = document.createElement('div');
                toast.className = `toast flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white ${colors[type]} min-w-[300px] cursor-pointer`;
                toast.innerHTML = `<i class="fas ${icons[type]}"></i><span class="font-medium text-sm">${message}</span><button onclick="this.parentElement.remove()" class="ml-auto hover:opacity-75"><i class="fas fa-times"></i></button>`;
                toast.onclick = () => toast.remove();
                container.appendChild(toast);
                setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateX(100%)'; setTimeout(() => toast.remove(), 300); }, 4000);
            }
        </script>
    </body>
    </html>
<?php else: ?>
    <div class="min-h-screen">
        <?php echo $__env->make('notifications.settings-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>
<?php endif; ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\notifications\settings.blade.php ENDPATH**/ ?>