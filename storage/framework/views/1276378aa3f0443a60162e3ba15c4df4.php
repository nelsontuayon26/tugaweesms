<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sections | Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            margin: 0; 
            padding: 0; 
            background: #f8fafc;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 32px;
            background: #f8fafc;
        }

        .main-content::-webkit-scrollbar { 
            width: 8px; 
        }
        .main-content::-webkit-scrollbar-track { 
            background: transparent; 
        }
        .main-content::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 4px; 
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .modern-table th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 20px 24px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .modern-table td {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.03) 0%, transparent 100%);
            transform: scale(1.002);
        }

        .action-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            transform: translateX(-10px);
        }

        .modern-table tbody tr:hover .action-btn {
            opacity: 1;
            transform: translateX(0);
        }

        @media (hover: none) and (pointer: coarse) {
            .action-btn {
                opacity: 1 !important;
            }
        }

        .action-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .action-btn:nth-child(1) { transition-delay: 0ms; }
        .action-btn:nth-child(2) { transition-delay: 50ms; }
        .action-btn:nth-child(3) { transition-delay: 100ms; }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow-x: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.4);
            position: relative;
            overflow-x: hidden;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            font-weight: 700;
            color: #065f46;
            font-size: 0.875rem;
        }

        .grade-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px solid #93c5fd;
            border-radius: 10px;
            font-weight: 700;
            color: #1e40af;
            font-size: 0.8rem;
        }

        .adviser-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #f5f3ff 0%, #e9d5ff 100%);
            border: 1px solid #c4b5fd;
            border-radius: 10px;
            font-weight: 600;
            color: #5b21b6;
            font-size: 0.75rem;
        }

        .no-adviser-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1px solid #fcd34d;
            border-radius: 10px;
            font-weight: 600;
            color: #92400e;
            font-size: 0.75rem;
        }

        .capacity-bar {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow-x: hidden;
            margin-top: 8px;
        }

        .capacity-fill {
            height: 100%;
            border-radius: 4px;
            transition: all 0.5s ease;
        }

        .capacity-fill.low { background: linear-gradient(90deg, #10b981, #34d399); }
        .capacity-fill.medium { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .capacity-fill.high { background: linear-gradient(90deg, #ef4444, #f87171); }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            color: #94a3b8;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.025em;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-top: 4px;
        }

        .search-input {
            width: 100%;
            max-width: 320px;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .filter-pill {
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }

        .filter-pill.active {
            background: #10b981;
            color: white;
        }

        .filter-pill:not(.active) {
            background: white;
            color: #64748b;
            border-color: #e2e8f0;
        }

        .filter-pill:not(.active):hover {
            border-color: #10b981;
            color: #10b981;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        .delete-btn:hover {
            background: #fef2f2;
            color: #dc2626;
        }

        .view-btn:hover {
            background: #eff6ff;
            color: #2563eb;
        }

        .edit-btn:hover {
            background: #fffbeb;
            color: #d97706;
        }

        .school-year-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 1px solid #f59e0b;
            border-radius: 8px;
            font-weight: 600;
            color: #92400e;
            font-size: 0.7rem;
        }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">   <!-- Mobile Overlay -->   <div x-show="mobileOpen"        x-transition:enter="transition ease-out duration-300"       x-transition:enter-start="opacity-0"        x-transition:enter-end="opacity-100"        x-transition:leave="transition ease-in duration-200"         x-transition:leave-start="opacity-100"         x-transition:leave-end="opacity-0"        @click="mobileOpen = false"         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"        style="display: none;"></div>   <!-- Mobile Hamburger -->   <button @click="mobileOpen = !mobileOpen"           class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">        <i class="fas fa-bars"></i>    </button>

<div class="dashboard-container">
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-wrapper">
        <div class="main-content">
            
            <!-- Stats Row -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in">
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-th-large text-emerald-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($sections->count()); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Total Sections</p>
                    <p class="text-xs text-slate-400 mt-1">All grade levels</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($totalStudents); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Active Students</p>
                    <p class="text-xs text-slate-400 mt-1">Currently enrolled</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-purple-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($sections->whereNotNull('teacher_id')->count()); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">With Advisers</p>
                    <p class="text-xs text-slate-400 mt-1">Assigned teachers</p>
                </div>

                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-door-open text-amber-600 text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($sections->whereNull('teacher_id')->count()); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Need Adviser</p>
                    <p class="text-xs text-slate-400 mt-1">Unassigned sections</p>
                </div>
            </div>

            <!-- Header & Filters -->
            <div class="glass-card p-6 mb-6 animate-fade-in stagger-1">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
                    <div>
                        <h2 class="page-title">Sections Management</h2>
                        <p class="page-subtitle">Manage class sections, assign advisers, and monitor capacity</p>
                        <?php if($selectedSchoolYear): ?>
                            <span class="school-year-badge mt-2">
                                <i class="fas fa-calendar-check"></i>
                                Showing: <?php echo e($selectedSchoolYear->name); ?>

                                <?php if($activeSchoolYear && $selectedSchoolYear->id == $activeSchoolYear->id): ?>
                                    <span class="ml-1 text-emerald-700 font-bold">(Active)</span>
                                <?php endif; ?>
                            </span>
                        <?php else: ?>
                            <span class="school-year-badge mt-2">
                                <i class="fas fa-calendar"></i>
                                Showing: All School Years
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
                    <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                        
                        <form action="<?php echo e(route('admin.sections.index')); ?>" method="GET" class="relative w-full sm:w-auto">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search sections..." class="search-input">
                            <?php if(request('search')): ?>
                                <a href="<?php echo e(route('admin.sections.index')); ?>" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">                                </a>
                            <?php endif; ?>
                        </form>
                        
                        
                        <form action="<?php echo e(route('admin.sections.index')); ?>" method="GET" id="schoolYearFilterForm" class="relative w-full sm:w-auto">
                            <?php if(request('search')): ?>
                                <input type="hidden" name="search" value="<?php echo e(request('search')); ?>">
                            <?php endif; ?>
                            <select name="school_year_id" onchange="document.getElementById('schoolYearFilterForm').submit()"
                                    class="w-full sm:w-56 px-4 py-3 border-2 border-slate-200 rounded-2xl text-sm font-semibold text-slate-700 bg-white focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all cursor-pointer">
                                <option value="">All School Years</option>
                                <?php $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($year->id); ?>" <?php echo e($selectedSchoolYear && $selectedSchoolYear->id == $year->id ? 'selected' : ''); ?>>
                                        <?php echo e($year->name); ?> <?php echo e($year->is_active ? '★ Active' : ''); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </form>
                    </div>
                    
                    <div class="flex gap-2 flex-wrap">
                        <button class="filter-pill active">All Grades</button>
                        <?php $__currentLoopData = $gradeLevels ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button class="filter-pill">Grade <?php echo e($grade->level); ?></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="glass-card overflow-hidden animate-fade-in stagger-2">
                <div class="overflow-x-auto">
                    <table class="modern-table w-full">
                    <thead>
                        <tr>
                            <th>Section Details</th>
                            <th>Grade Level & Adviser</th>
                            <th>Capacity Status</th>
                            <th>Active Students</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                // Use active_students loaded from controller
                                $studentCount = $section->active_students->count();
                                $capacity = $section->capacity ?? 40;
                                $percentage = $capacity > 0 ? min(100, ($studentCount / $capacity) * 100) : 0;
                                $statusClass = $percentage < 50 ? 'low' : ($percentage < 80 ? 'medium' : 'high');
                            ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-emerald-500/30">
                                            <?php echo e(strtoupper(substr($section->name, 0, 1))); ?>

                                        </div>
                                        <div>
                                            <div class="section-badge mb-2">
                                                <i class="fas fa-th-large text-xs"></i>
                                                <?php echo e($section->name); ?>

                                            </div>
                                            <p class="text-xs text-slate-500 flex items-center gap-1">
                                                <i class="fas fa-door-open"></i>
                                                Room <?php echo e($section->room_number ?? 'TBA'); ?>

                                                <?php if($section->school_year): ?>
                                                    <span class="mx-1">•</span>
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <?php echo e($section->school_year); ?>

                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-2">
                                        <?php if($section->gradeLevel): ?>
                                            <span class="grade-badge">
                                                <i class="fas fa-graduation-cap"></i>
                                                <?php echo e($section->gradeLevel->name ?? 'Grade ' . $section->gradeLevel->level); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 text-xs text-slate-400">
                                                <i class="fas fa-graduation-cap"></i>
                                                No grade assigned
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if($section->teacher): ?>
                                            <span class="adviser-badge">
                                                <i class="fas fa-user-tie"></i>
                                                <?php echo e($section->teacher->full_name ?? $section->teacher->first_name . ' ' . $section->teacher->last_name); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="no-adviser-badge">
                                                <i class="fas fa-exclamation-circle"></i>
                                                Adviser needed
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="w-36">
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="font-semibold text-slate-700"><?php echo e($studentCount); ?>/<?php echo e($capacity); ?></span>
                                            <span class="text-slate-400"><?php echo e(round($percentage)); ?>%</span>
                                        </div>
                                        <div class="capacity-bar">
                                            <div class="capacity-fill <?php echo e($statusClass); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="flex -space-x-2">
                                            <?php for($i = 0; $i < min(3, $studentCount); $i++): ?>
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 border-2 border-white flex items-center justify-center text-white text-xs font-bold">
                                                    <?php echo e(chr(65 + $i)); ?>

                                                </div>
                                            <?php endfor; ?>
                                            <?php if($studentCount > 3): ?>
                                                <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-slate-600 text-xs font-bold">
                                                    +<?php echo e($studentCount - 3); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-700"><?php echo e($studentCount); ?> active</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex gap-2">
                                        <a href="<?php echo e(route('admin.sections.show', $section)); ?>" class="action-btn view-btn text-slate-400 hover:text-blue-600" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.sections.id-cards', $section)); ?>" class="action-btn text-slate-400 hover:text-emerald-600" title="Generate ID Cards">
                                            <i class="fas fa-id-card"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.sections.edit', $section)); ?>" class="action-btn edit-btn text-slate-400 hover:text-amber-600" title="Edit Section">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-th-large"></i>
                                        </div>
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">
                                            <?php if($selectedSchoolYear): ?>
                                                No Sections for <?php echo e($selectedSchoolYear->name); ?>

                                            <?php else: ?>
                                                No Sections Found
                                            <?php endif; ?>
                                        </h3>
                                        <p class="text-slate-500 mb-6 max-w-md mx-auto">
                                            <?php if($selectedSchoolYear): ?>
                                                There are no sections created for <?php echo e($selectedSchoolYear->name); ?> yet. 
                                                <?php if($activeSchoolYear && $selectedSchoolYear->id != $activeSchoolYear->id): ?>
                                                    <a href="<?php echo e(route('admin.sections.index', ['school_year_id' => $activeSchoolYear->id])); ?>" class="text-emerald-600 hover:underline font-semibold">Switch to active year <?php echo e($activeSchoolYear->name); ?></a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                Get started by creating your first class section. Sections help organize students by grade level and assign advisers.
                                            <?php endif; ?>
                                        </p>
                                        <a href="<?php echo e(route('admin.sections.create')); ?>" class="btn-primary">
                                            <i class="fas fa-plus mr-2"></i>
                                            <?php echo e($selectedSchoolYear ? 'Create Section for ' . $selectedSchoolYear->name : 'Create First Section'); ?>

                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>

                <?php if($sections instanceof \Illuminate\Pagination\LengthAwarePaginator && $sections->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100">
                        <?php echo e($sections->links()); ?>

                    </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Floating Add Button -->
<a href="<?php echo e(route('admin.sections.create')); ?>" 
   class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40 transition-all hover:scale-110 hover:rotate-3 z-50"
   title="Add New Section">
    <i class="fas fa-plus text-lg"></i>
</a>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/admin/sections/index.blade.php ENDPATH**/ ?>