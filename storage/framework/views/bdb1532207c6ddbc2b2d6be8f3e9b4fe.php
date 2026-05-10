

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Tugawe Elem</title>
    <!-- Fixed Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { overflow-x: hidden; }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .main-header {
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 32px;
            flex-shrink: 0;
            z-index: 40;
        }

        .main-content {
            flex: 1;
            overflow-x: hidden;
            background: #f8fafc;
            padding: 1.5rem;
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
        }

        .fab {
            box-shadow: 0 10px 40px -10px rgba(37, 99, 235, 0.5);
            transition: all 0.3s ease;
        }
        .fab:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 50px -10px rgba(37, 99, 235, 0.6);
        }

        .search-input {
            background: #f1f5f9;
            border: 2px solid transparent;
            transition: all 0.2s ease;
        }
        .search-input:focus {
            background: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .table-row {
            transition: all 0.2s ease;
        }
        .table-row:hover {
            background: #f8fafc;
            transform: scale(1.002);
        }

        .action-btn {
            transition: all 0.2s ease;
            position: relative;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }

        @media (hover: none) and (pointer: coarse) {
            .action-btn {
                opacity: 1 !important;
            }
        }

        /* Tooltip styles */
        .tooltip {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
            padding: 6px 10px;
            background: #1e293b;
            color: white;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
            pointer-events: none;
            z-index: 50;
        }
        .tooltip::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #1e293b;
        }
        .action-btn:hover .tooltip {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-4px);
        }

        .status-badge {
            transition: all 0.2s ease;
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        .animate-delay-1 { animation-delay: 0.1s; }
        .animate-delay-2 { animation-delay: 0.2s; }
        .animate-delay-3 { animation-delay: 0.3s; }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        /* Modal styles */
        .modal-backdrop {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }
        
        .alert-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
        }

        .highlight {
    background: #fef08a;
    padding: 0 2px;
    border-radius: 2px;
}
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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
         style="display: none;"></div>

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2.5 hover:bg-slate-100 rounded-xl transition-colors">
                        <i class="fas fa-bars text-slate-600"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manage Users</h1>
                        <p class="text-sm text-slate-500 mt-0.5">View and manage system users</p>
                    </div>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex items-center gap-4">
                    <div class="relative">
                      <input type="text" 
       id="searchInput"
       placeholder="Search users..." 
       class="search-input pl-10 pr-4 py-2.5 rounded-xl text-sm w-64 focus:outline-none">
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 alert-success rounded-xl flex items-center gap-3 animate-fade-in-up shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-emerald-800 font-semibold text-sm">Success</p>
                        <p class="text-emerald-700 text-sm"><?php echo e(session('success')); ?></p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-emerald-600 hover:text-emerald-800 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Error Message -->
            <?php if(session('error')): ?>
                <div class="mb-6 p-4 alert-error rounded-xl flex items-center gap-3 animate-fade-in-up shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-red-800 font-semibold text-sm">Error</p>
                        <p class="text-red-700 text-sm"><?php echo e(session('error')); ?></p>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 alert-error rounded-xl flex items-start gap-3 animate-fade-in-up shadow-sm">
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fa-solid fa-circle-exclamation text-red-600"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-red-800 font-semibold text-sm mb-1">Please fix the following errors:</p>
                        <ul class="text-red-700 text-sm list-disc list-inside space-y-0.5">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <button onclick="this.parentElement.remove()" class="ml-auto text-red-600 hover:text-red-800 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            <?php endif; ?>

          <!-- Enhanced Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="glass-card p-6 animate-fade-in-up relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/10 to-transparent rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e($totalUsers = $users->total() ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
            <?php
                $lastMonthUsers = \App\Models\User::where('created_at', '<', now()->startOfMonth())
                    ->where('created_at', '>=', now()->subMonth()->startOfMonth())
                    ->count();
                $thisMonthUsers = \App\Models\User::where('created_at', '>=', now()->startOfMonth())->count();
                $growthPercent = $lastMonthUsers > 0 ? round((($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : ($thisMonthUsers > 0 ? 100 : 0);
                $growthIcon = $growthPercent >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down';
                $growthColor = $growthPercent >= 0 ? 'text-emerald-600' : 'text-red-600';
            ?>
            <div class="flex items-center gap-2 text-sm">
                <span class="<?php echo e($growthColor); ?> font-semibold flex items-center gap-1 bg-emerald-50 px-2 py-1 rounded-lg">
                    <i class="fa-solid <?php echo e($growthIcon); ?> text-xs"></i>
                    <?php echo e(abs($growthPercent)); ?>%
                </span>
                <span class="text-slate-400">vs last month</span>
            </div>
        </div>
    </div>

    <!-- Active Users Card -->
    <div class="glass-card p-6 animate-fade-in-up animate-delay-1 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Active Users</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e($activeUsers = $users->where('status', 'active')->count() ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-user-check text-xl"></i>
                </div>
            </div>
            <?php
                $activePercent = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
            ?>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500"><?php echo e($activePercent); ?>% of total</span>
                    <span class="text-emerald-600 font-semibold"><?php echo e($activeUsers); ?> active</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-400 h-full rounded-full transition-all duration-500" style="width: <?php echo e($activePercent); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inactive Users Card -->
    <div class="glass-card p-6 animate-fade-in-up animate-delay-2 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-500/10 to-transparent rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Inactive Users</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e($inactiveUsers = $users->where('status', 'inactive')->count() ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center text-white shadow-lg shadow-slate-500/30">
                    <i class="fa-solid fa-user-xmark text-xl"></i>
                </div>
            </div>
            <?php
                $inactivePercent = $totalUsers > 0 ? round(($inactiveUsers / $totalUsers) * 100, 1) : 0;
            ?>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500"><?php echo e($inactivePercent); ?>% of total</span>
                    <span class="text-slate-600 font-semibold"><?php echo e($inactiveUsers); ?> inactive</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-500 to-slate-400 h-full rounded-full transition-all duration-500" style="width: <?php echo e($inactivePercent); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- New This Week Card -->
    <div class="glass-card p-6 animate-fade-in-up animate-delay-3 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-500/10 to-transparent rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">New This Week</p>
                    <p class="text-3xl font-bold text-slate-900"><?php echo e($newThisWeek = $users->where('created_at', '>=', now()->subWeek())->count() ?? 0); ?></p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                    <i class="fa-solid fa-user-plus text-xl"></i>
                </div>
            </div>
            <?php
                $lastWeekUsers = \App\Models\User::where('created_at', '>=', now()->subWeeks(2))
                    ->where('created_at', '<', now()->subWeek())
                    ->count();
                $weekGrowth = $lastWeekUsers > 0 ? round((($newThisWeek - $lastWeekUsers) / $lastWeekUsers) * 100, 1) : ($newThisWeek > 0 ? 100 : 0);
                $weekIcon = $weekGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                $weekColor = $weekGrowth >= 0 ? 'text-purple-600' : 'text-red-600';
            ?>
            <div class="flex items-center gap-2 text-sm">
                <span class="<?php echo e($weekColor); ?> font-semibold flex items-center gap-1 bg-purple-50 px-2 py-1 rounded-lg">
                    <i class="fa-solid <?php echo e($weekIcon); ?> text-xs"></i>
                    <?php echo e(abs($weekGrowth)); ?>%
                </span>
                <span class="text-slate-400">vs last week</span>
            </div>
        </div>
    </div>
</div>

<!-- Visual Stats Summary Bar -->
<div class="glass-card p-4 mb-8 animate-fade-in-up animate-delay-3">
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
            <span class="text-sm text-slate-600">Active: <strong class="text-slate-900"><?php echo e($activeUsers); ?></strong></span>
        </div>
        <div class="flex items-center gap-3">
            <div class="w-3 h-3 rounded-full bg-slate-500"></div>
            <span class="text-sm text-slate-600">Inactive: <strong class="text-slate-900"><?php echo e($inactiveUsers); ?></strong></span>
        </div>
        <div class="flex-1 h-3 bg-slate-100 rounded-full overflow-hidden flex">
            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: <?php echo e($activePercent); ?>%" title="Active <?php echo e($activePercent); ?>%"></div>
            <div class="bg-slate-500 h-full transition-all duration-500" style="width: <?php echo e($inactivePercent); ?>%" title="Inactive <?php echo e($inactivePercent); ?>%"></div>
        </div>
        <span class="text-sm font-semibold text-slate-700"><?php echo e($totalUsers); ?> Total</span>
    </div>
</div>

            <!-- Users Table Card -->
            <div class="glass-card overflow-hidden animate-fade-in-up animate-delay-3">
                <!-- Table Header -->
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-list text-blue-600"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-900">User List</h2>
                            <p class="text-xs text-slate-500">Manage all system users</p>
                        </div>
                    </div>
                    
                    <!-- Add User Button -->
                    <a href="<?php echo e(route('admin.users.create')); ?>" 
                       class="fab flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm hover:from-blue-700 hover:to-indigo-700 transition-all">
                        <i class="fa-solid fa-plus"></i>
                        <span>Add Admin / Principal</span>
                    </a>
                </div>

                <!-- Role Filter -->
                <div class="px-6 py-3 border-b border-slate-100 bg-white">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mr-1">Filter by Role:</span>
                        <a href="<?php echo e(route('admin.users.index')); ?>" 
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?php echo e(!$selectedRole ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                            All
                        </a>
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('admin.users.index', ['role' => $role->name])); ?>" 
                               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-all <?php echo e($selectedRole === $role->name ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">
                                <?php echo e(ucfirst($role->name)); ?>

                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <tr class="table-row group" data-name="<?php echo e(strtolower($user->first_name . ' ' . $user->last_name . ' ' . $user->email)); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                                <?php echo e(strtoupper(substr($user->first_name ?? $user->name, 0, 1))); ?>

                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900">
                                                    <?php echo e($user->first_name); ?> <?php echo e($user->middle_name ?? ''); ?> <?php echo e($user->last_name); ?>

                                                </p>
                                                <p class="text-xs text-slate-500"><?php echo e($user->username ?? '@user'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <i class="fa-regular fa-envelope text-slate-400 text-xs"></i>
                                            <span class="truncate max-w-[200px]"><?php echo e($user->email); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                            <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                            <?php echo e(ucfirst($user->role->name ?? 'User')); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-sm text-slate-600">
                                            <i class="fa-regular fa-calendar text-slate-400 text-xs"></i>
                                            <span><?php echo e($user->created_at->format('M d, Y')); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
    <?php
        $isActive = ($user->status ?? 'active') === 'active';
        $statusClass = $isActive 
            ? 'bg-emerald-50 text-emerald-700 border-emerald-200' 
            : 'bg-slate-50 text-slate-600 border-slate-200';
        $statusIcon = $isActive ? 'fa-circle-check' : 'fa-circle-xmark';
        $statusText = $isActive ? 'Active' : 'Inactive';
    ?>
    <span class="status-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border <?php echo e($statusClass); ?>">
        <i class="fa-solid <?php echo e($statusIcon); ?> text-[10px]"></i>
        <?php echo e($statusText); ?>

    </span>
</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Edit Button (Icon Only) -->
                                            <a href="<?php echo e(route('admin.users.edit', $user)); ?>" 
                                               class="action-btn w-9 h-9 flex items-center justify-center bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 border border-amber-200 hover:border-amber-300">
                                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                                                <span class="tooltip">Edit User</span>
                                            </a>
                                            
                                            <!-- Reset Password Button (Icon Only) -->
                                            <button type="button"
                                                    onclick="openResetPasswordModal('<?php echo e(route('admin.users.reset-password', $user)); ?>', '<?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>')"
                                                    class="action-btn w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 border border-blue-200 hover:border-blue-300">
                                                <i class="fa-solid fa-key text-sm"></i>
                                                <span class="tooltip">Reset Password</span>
                                            </button>

                                            <!-- Delete Button (Icon Only) -->
                                            <button type="button" 
                                                    onclick="openDeleteModal('<?php echo e(route('admin.users.destroy', $user)); ?>', '<?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>')"
                                                    class="action-btn w-9 h-9 flex items-center justify-center bg-red-50 text-red-600 rounded-lg hover:bg-red-100 border border-red-200 hover:border-red-300">
                                                <i class="fa-regular fa-trash-can text-sm"></i>
                                                <span class="tooltip">Delete User</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                                <i class="fa-solid fa-users text-2xl text-slate-300"></i>
                                            </div>
                                            <p class="text-sm font-medium">No users found</p>
                                            <p class="text-xs mt-1">Get started by adding a new user</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($users->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        <?php echo e($users->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Mobile FAB (visible only on small screens) -->
            <div class="md:hidden fixed bottom-6 right-6 z-50">
                <a href="<?php echo e(route('admin.users.create')); ?>" 
                   class="fab w-14 h-14 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-plus text-xl"></i>
                </a>
            </div>
        </main>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 modal-backdrop transition-opacity opacity-0" id="resetPasswordBackdrop" onclick="closeResetPasswordModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="resetPasswordContent">
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-key text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Reset Password</h3>
                        <p class="text-sm text-slate-500">Set a new password for <span id="resetPasswordUserName" class="font-semibold text-slate-900"></span></p>
                    </div>
                </div>
            </div>
            <form id="resetPasswordForm" method="POST" class="p-6 space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                    <input type="password" name="password" required minlength="8"
                           class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                           placeholder="Enter new password">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required minlength="8"
                           class="w-full px-4 py-2.5 rounded-xl border-2 border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all"
                           placeholder="Confirm new password">
                </div>
                <?php if($errors->has('password')): ?>
                    <p class="text-sm text-red-600"><?php echo e($errors->first('password')); ?></p>
                <?php endif; ?>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeResetPasswordModal()"
                            class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold text-sm hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg shadow-blue-500/30">
                        <i class="fa-solid fa-key mr-2"></i>
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 modal-backdrop transition-opacity opacity-0" id="modalBackdrop" onclick="closeDeleteModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
            <!-- Modal Header -->
            <div class="p-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Delete User</h3>
                        <p class="text-sm text-slate-500">This action cannot be undone</p>
                    </div>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <p class="text-slate-600 text-sm leading-relaxed">
                    Are you sure you want to delete <span id="deleteUserName" class="font-semibold text-slate-900"></span>? 
                    All associated data will be permanently removed from the system.
                </p>
            </div>
            
            <!-- Modal Footer -->
            <div class="p-6 border-t border-slate-100 flex gap-3">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-semibold text-sm hover:bg-slate-200 transition-all">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" 
                            class="w-full px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold text-sm hover:from-red-700 hover:to-red-800 transition-all shadow-lg shadow-red-500/30">
                        <i class="fa-regular fa-trash-can mr-2"></i>
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    // Real-time search functionality
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.table-row[data-name]');
        
        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            if (name.includes(searchTerm)) {
                row.style.display = '';
                row.style.opacity = '1';
            } else {
                row.style.display = 'none';
                row.style.opacity = '0';
            }
        });
        
        // Show "no results" message if all rows are hidden
        const visibleRows = document.querySelectorAll('.table-row[data-name]:not([style*="display: none"])');
        const noResultsRow = document.getElementById('noResultsRow');
        
        if (visibleRows.length === 0 && !noResultsRow) {
            const tbody = document.querySelector('tbody');
            const tr = document.createElement('tr');
            tr.id = 'noResultsRow';
            tr.innerHTML = `
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-400">
                        <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                            <i class="fa-solid fa-magnifying-glass text-2xl text-slate-300"></i>
                        </div>
                        <p class="text-sm font-medium">No users found</p>
                        <p class="text-xs mt-1">Try adjusting your search</p>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        } else if (visibleRows.length > 0 && noResultsRow) {
            noResultsRow.remove();
        }
    });
</script>

<script>

    
    // Delete Modal Functions
    function openDeleteModal(url, userName) {
        const modal = document.getElementById('deleteModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('deleteUserName');
        
        form.action = url;
        nameSpan.textContent = userName;
        
        modal.classList.remove('hidden');
        
        // Animate in
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        const backdrop = document.getElementById('modalBackdrop');
        const content = document.getElementById('modalContent');
        
        // Animate out
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    // Reset Password Modal Functions
    function openResetPasswordModal(url, userName) {
        const modal = document.getElementById('resetPasswordModal');
        const backdrop = document.getElementById('resetPasswordBackdrop');
        const content = document.getElementById('resetPasswordContent');
        const form = document.getElementById('resetPasswordForm');
        const nameSpan = document.getElementById('resetPasswordUserName');

        form.action = url;
        nameSpan.textContent = userName;

        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeResetPasswordModal() {
        const modal = document.getElementById('resetPasswordModal');
        const backdrop = document.getElementById('resetPasswordBackdrop');
        const content = document.getElementById('resetPasswordContent');

        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeResetPasswordModal();
            closeDeleteModal();
        }
    });
    
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-success, .alert-error');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
    });
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\users\index.blade.php ENDPATH**/ ?>