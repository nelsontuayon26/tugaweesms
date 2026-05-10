<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher - Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        :root {
            --primary: #4f46e5;
            --primary-light: #6366f1;
            --primary-dark: #4338ca;
            --secondary: #0ea5e9;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --bg-soft: #f0f9ff;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        
        body { 
            background: linear-gradient(135deg, #f0f9ff 0%, #f8fafc 50%, #f0f4f8 100%);
            min-height: 100vh;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Main Content Adjustment */
        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            min-height: 100vh;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* Main Content */
        .main-content {
            padding: 32px;
            max-width: 1400px;
            margin: 0 auto;
            padding-bottom: 120px;
            overflow-x: hidden;
        }

        /* Glass Cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 24px;
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: rgba(99, 102, 241, 0.2);
        }

        /* Input Groups */
        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .input-group:focus-within .input-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid var(--border);
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            outline: none;
            color: var(--text-main);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1), inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .form-input[readonly] {
            background: #f8fafc;
            cursor: not-allowed;
            border-color: #e2e8f0;
            color: var(--text-muted);
        }

        /* Floating Labels */
        .floating-label {
            position: absolute;
            left: 48px;
            top: 0;
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 700;
            background: white;
            padding: 0 4px;
            pointer-events: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 5;
        }

        .form-input:focus ~ .floating-label {
            color: var(--primary);
        }

        .form-input:placeholder-shown ~ .floating-label {
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            font-weight: 400;
            background: transparent;
        }

        .form-input:focus ~ .floating-label,
        .form-input:not(:placeholder-shown) ~ .floating-label {
            top: 0;
            transform: translateY(-50%);
            font-size: 11px;
            font-weight: 700;
            background: white;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 4px 15px -3px rgba(79, 70, 229, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 35px -5px rgba(79, 70, 229, 0.5);
        }

        .btn-secondary {
            background: rgba(241, 245, 249, 0.8);
            color: var(--text-muted);
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 15px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: rgba(226, 232, 240, 0.9);
            color: var(--text-main);
            border-color: var(--border);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 15px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: #fca5a5;
        }

        /* Header */
        .glass-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            padding: 48px;
            border-radius: 28px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(30, 41, 59, 0.4);
        }

        .glass-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--secondary) 100%);
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(30px, -30px) scale(1.1); }
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-icon {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.35);
        }

        .section-title {
            font-size: 19px;
            font-weight: 800;
            color: var(--text-main);
        }

        .section-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        /* Avatar */
        .avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: 700;
            border: 4px solid white;
            box-shadow: 0 10px 30px -5px rgba(79, 70, 229, 0.4);
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #059669;
        }

        .status-badge.inactive {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        /* Sticky Footer */
        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 280px;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding: 20px 32px;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.05);
            z-index: 30;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sticky-footer.expanded {
            left: 80px;
        }

        @media (max-width: 1024px) {
            .sticky-footer {
                left: 0 !important;
            }
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
            background: #e2e8f0;
            border-radius: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .toggle-switch.active {
            background: var(--primary);
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .toggle-switch.active::after {
            left: 27px;
        }

        /* Tooltip */
        .tooltip-container {
            position: relative;
        }

        .tooltip-message {
            position: absolute;
            bottom: calc(100% + 10px);
            left: 50%;
            transform: translateX(-50%) scale(0.9);
            background: #1e293b;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .tooltip-container:hover .tooltip-message {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) scale(1);
        }

        .lock-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: 14px;
        }

        /* Change Password Section */
        .change-password-section {
            background: linear-gradient(135deg, rgba(254, 243, 199, 0.3) 0%, rgba(253, 230, 138, 0.1) 100%);
            border: 1px solid rgba(251, 191, 36, 0.3);
            border-radius: 16px;
            padding: 20px;
            margin-top: 20px;
        }

        /* Loading Spinner */
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Success Toast */
        .success-toast {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            border-left: 4px solid var(--success);
            padding: 20px 28px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
            display: flex;
            align-items: center;
            gap: 16px;
            z-index: 1000;
            transform: translateX(500px);
            transition: all 0.5s ease;
        }

        .success-toast.show {
            transform: translateX(0);
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.7s ease forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        /* View All Link */
        .view-all-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--primary);
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-left: auto;
        }

        .view-all-link:hover {
            color: var(--primary-dark);
            gap: 10px;
        }

        /* Toggle Password Button */
        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .toggle-password:hover {
            color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
        }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar Include -->
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="main-wrapper" id="mainWrapper">
        <div class="main-content">
            <!-- Header -->
            <div class="glass-header animate-fade-in">
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            <div class="avatar-large">
                                <?php echo e(strtoupper(substr($teacher->first_name, 0, 1) . substr($teacher->last_name, 0, 1))); ?>

                            </div>
                            <div>
                                <h1 class="text-3xl lg:text-4xl font-bold tracking-tight"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></h1>
                                <p class="text-blue-200 mt-2 text-base lg:text-lg">Teacher Account • ID: <?php echo e($teacher->employee_id); ?></p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-4">
                            <span class="status-badge <?php echo e($teacher->status === 'active' ? 'active' : 'inactive'); ?>">
                                <i class="fas fa-circle text-[8px]"></i>
                                <?php echo e(ucfirst($teacher->status ?? 'Active')); ?>

                            </span>
                            <div class="text-sm text-blue-200">
                                <i class="fas fa-clock mr-1"></i>
                                Last updated: <?php echo e($teacher->updated_at ? $teacher->updated_at->format('M d, Y h:i A') : 'Never'); ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-blue-200">Employee ID</div>
                                    <div class="font-semibold"><?php echo e($teacher->employee_id); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-blue-200">Email</div>
                                    <div class="font-semibold truncate"><?php echo e($teacher->email); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-4 border border-white/20">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <div class="text-xs text-blue-200">Username</div>
                                    <div class="font-semibold"><?php echo e($teacher->user->username ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <?php if(session('success')): ?>
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-6 py-4 rounded-r-xl mb-6 animate-fade-in">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Success!</p>
                            <p class="text-sm"><?php echo e(session('success')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($errors->any() || session('error')): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-xl mb-6 animate-fade-in">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-xl mt-1"></i>
                        <div class="flex-1">
                            <p class="font-bold">Please fix the following errors:</p>
                            <?php if($errors->any()): ?>
                                <ul class="mt-2 space-y-1 text-sm">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>• <?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php else: ?>
                                <p class="mt-1 text-sm"><?php echo e(session('error')); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="<?php echo e(route('admin.teachers.update', $teacher->id)); ?>" method="POST" class="space-y-6" id="teacherForm">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                  <!-- Left Column: Personal Information -->
<div class="glass-card p-6 lg:p-8 animate-fade-in stagger-1">
    <div class="section-header">
        <div class="section-icon">
            <i class="fas fa-user"></i>
        </div>
        <div>
            <h3 class="section-title">Personal Information</h3>
            <p class="section-subtitle">Basic details about the teacher</p>
        </div>
    </div>

    <div class="space-y-5">
        <!-- Name Row -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="first_name" required 
                       value="<?php echo e(old('first_name', $teacher->first_name)); ?>"
                       class="form-input" placeholder=" ">
                <label class="floating-label">First Name <span class="text-red-500">*</span></label>
            </div>
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="last_name" required 
                       value="<?php echo e(old('last_name', $teacher->last_name)); ?>"
                       class="form-input" placeholder=" ">
                <label class="floating-label">Last Name <span class="text-red-500">*</span></label>
            </div>
            <div class="input-group">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="middle_name" 
                       value="<?php echo e(old('middle_name', $teacher->middle_name)); ?>"
                       class="form-input" placeholder=" ">
                <label class="floating-label">Middle Name</label>
            </div>
        </div>

        <!-- Email & Mobile Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="input-group tooltip-container">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" value="<?php echo e($teacher->email); ?>" class="form-input" readonly placeholder=" ">
                <label class="floating-label">Email Address</label>
                <i class="fas fa-lock lock-icon"></i>
                <div class="tooltip-message">
                    <i class="fas fa-info-circle mr-1"></i>
                    Email cannot be changed
                </div>
                <p class="input-hint text-xs mt-1">
                    <i class="fas fa-shield-alt"></i>
                    Contact admin to change email
                </p>
            </div>
            <input type="hidden" name="email" value="<?php echo e($teacher->email); ?>">

            <div class="input-group">
                <i class="fas fa-mobile-alt input-icon"></i>
                <input type="tel" name="mobile_number" required 
                       value="<?php echo e(old('mobile_number', $teacher->mobile_number)); ?>"
                       class="form-input" placeholder=" " maxlength="13" id="mobileInput">
                <label class="floating-label">Mobile Number <span class="text-red-500">*</span></label>
            </div>
        </div>

        <!-- Employee ID & Status Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Employee ID (Read-only) -->
            <div class="input-group tooltip-container">
                <i class="fas fa-id-card input-icon"></i>
                <input type="text" value="<?php echo e($teacher->employee_id); ?>" class="form-input" readonly placeholder=" ">
                <label class="floating-label">Employee ID</label>
                <i class="fas fa-lock lock-icon"></i>
                <div class="tooltip-message">
                    <i class="fas fa-info-circle mr-1"></i>
                    Employee ID cannot be changed
                </div>
            </div>

            <!-- Status Selection -->
            <div class="input-group">
                <i class="fas fa-toggle-on input-icon"></i>
                <select name="status" required 
                        class="form-input appearance-none cursor-pointer"
                        style="padding-left: 44px;">
                    <option value="active" <?php echo e(old('status', $teacher->status) == 'active' ? 'selected' : ''); ?>>
                        🟢 Active
                    </option>
                    <option value="on_leave" <?php echo e(old('status', $teacher->status) == 'on_leave' ? 'selected' : ''); ?>>
                        🟡 On Leave
                    </option>
                    <option value="inactive" <?php echo e(old('status', $teacher->status) == 'inactive' ? 'selected' : ''); ?>>
                        🔴 Inactive
                    </option>
                </select>
                <label class="floating-label" style="top: 0; font-size: 12px; color: #3b82f6; font-weight: 600;">Status <span class="text-red-500">*</span></label>
                <i class="fas fa-chevron-down absolute right-4 top-[42px] text-slate-400 pointer-events-none"></i>
                
                <!-- Status indicator badge -->
                <div class="mt-2 flex items-center gap-2 text-xs">
                    <span id="statusBadge" class="px-2 py-1 rounded-full font-medium transition-colors duration-300">
                        <?php echo e(ucfirst(str_replace('_', ' ', $teacher->status ?? 'Active'))); ?>

                    </span>
                </div>

                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-xs mt-2 flex items-center gap-1">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?php echo e($message); ?>

                    </p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>
    </div>
</div>

<!-- Add this JavaScript for status badge color updates -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.querySelector('select[name="status"]');
        const statusBadge = document.getElementById('statusBadge');
        
        const statusColors = {
            'active': 'bg-emerald-100 text-emerald-700 border border-emerald-200',
            'on_leave': 'bg-amber-100 text-amber-700 border border-amber-200',
            'inactive': 'bg-rose-100 text-rose-700 border border-rose-200'
        };
        
        function updateStatusBadge() {
            const value = statusSelect.value;
            const text = statusSelect.options[statusSelect.selectedIndex].text.replace(/[\u{1F7E2}\u{1F7E1}\u{1F534}]\s*/u, '');
            statusBadge.textContent = text;
            statusBadge.className = `px-2 py-1 rounded-full font-medium transition-colors duration-300 ${statusColors[value] || statusColors['active']}`;
        }
        
        statusSelect.addEventListener('change', updateStatusBadge);
        updateStatusBadge(); // Initialize on load
    });
</script>

                    <!-- Right Column: Account Credentials & Activity -->
                    <div class="space-y-6">
                        <!-- Account Credentials -->
                        <div class="glass-card p-6 lg:p-8 animate-fade-in stagger-2">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Account Credentials</h3>
                                    <p class="section-subtitle">Manage login information</p>
                                </div>
                            </div>

                            <!-- Current Username -->
                            <div class="mb-6">
                                <label class="text-sm font-semibold text-slate-700 mb-2 block">Current Username</label>
                                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-xl p-4 flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white">
                                        <i class="fas fa-id-badge text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="font-mono font-bold text-indigo-700 text-lg"><?php echo e($teacher->user->username ?? 'N/A'); ?></div>
                                        <div class="text-xs text-indigo-500">Linked to user account #<?php echo e($teacher->user_id ?? $teacher->user->id ?? 'N/A'); ?></div>
                                    </div>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Active</span>
                                </div>
                            </div>

                            <!-- New Username Input -->
                            <div class="input-group mb-6">
                                <i class="fas fa-edit input-icon"></i>
                                <input type="text" name="username" required 
                                       value="<?php echo e(old('username', $teacher->user->username ?? '')); ?>"
                                       class="form-input" placeholder=" " autocomplete="off">
                                <label class="floating-label">New Username <span class="text-red-500">*</span></label>
                                <p class="input-hint text-xs">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Changing username will affect login credentials
                                </p>
                            </div>

                            <!-- Change Password Toggle -->
                            <div class="flex items-center justify-between mb-4 p-4 bg-slate-50 rounded-xl">
                                <div>
                                    <div class="text-sm font-semibold text-slate-700">Change Password</div>
                                    <div class="text-xs text-slate-500">Leave blank to keep current password</div>
                                </div>
                                <div class="toggle-switch" id="passwordToggle" onclick="togglePasswordSection()"></div>
                            </div>

                            <!-- Password Fields -->
                            <div id="passwordSection" style="display: none;">
                                <div class="change-password-section">
                                    <div class="space-y-4">
                                        <div class="input-group">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" name="password" minlength="8" class="form-input" placeholder=" " id="passwordInput">
                                            <label class="floating-label">New Password <span class="text-slate-400 font-normal">(Min 8 chars)</span></label>
                                            <span class="toggle-password" onclick="togglePassword('passwordInput')">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </div>
                                        <div class="input-group">
                                            <i class="fas fa-lock input-icon"></i>
                                            <input type="password" name="password_confirmation" minlength="8" class="form-input" placeholder=" " id="confirmPasswordInput">
                                            <label class="floating-label">Confirm New Password</label>
                                            <span class="toggle-password" onclick="togglePassword('confirmPasswordInput')">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                            <p class="input-hint text-xs" id="matchHint">
                                                <i class="fas fa-info-circle"></i>
                                                Re-enter the new password
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Activity -->
                        <div class="glass-card p-6 lg:p-8 animate-fade-in stagger-3">
                            <div class="section-header" style="border-bottom: none; margin-bottom: 20px; padding-bottom: 0;">
                                <div class="section-icon" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                                    <i class="fas fa-history"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="section-title">Account Activity</h3>
                                    <p class="section-subtitle">Recent account information</p>
                                </div>
                                <a href="#" class="view-all-link" onclick="alert('View All Activity feature coming soon!'); return false;">
                                    View All <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Last Login</div>
                                            <div class="font-semibold text-sm"><?php echo e($teacher->last_login_at ? $teacher->last_login_at->format('M d, Y h:i A') : 'Never'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Account Created</div>
                                            <div class="font-semibold text-sm"><?php echo e($teacher->created_at ? $teacher->created_at->format('M d, Y') : 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center">
                                            <i class="fas fa-edit"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Last Updated By</div>
                                            <div class="font-semibold text-sm"><?php echo e($teacher->updated_by ?? 'System'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                                            <i class="fas fa-key"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs text-slate-500">Password Changed</div>
                                            <div class="font-semibold text-sm"><?php echo e($teacher->password_changed_at ? $teacher->password_changed_at->format('M d, Y') : 'Never'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra space before footer -->
                <div class="h-20"></div>
            </form>

            <!-- Hidden Delete Form -->
            <form action="<?php echo e(route('admin.teachers.destroy', $teacher->id)); ?>" method="POST" id="deleteForm" style="display: none;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
            </form>
        </div>
    </div>

    <!-- Sticky Footer (Outside form so Account Activity is visible above) -->
    <div class="sticky-footer" id="stickyFooter">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <span class="flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-500"></i>
                    Editing teacher #<?php echo e($teacher->id); ?>

                </span>
                <span class="hidden sm:inline h-4 w-px bg-slate-300"></span>
                <span class="hidden sm:inline">
                    <span id="changesCount" class="font-semibold text-blue-600">0</span> changes made
                </span>
            </div>
            <div class="flex gap-3">
                <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn-secondary">                    Cancel
                </a>
                <button type="button" class="btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash-alt mr-2"></i>
                    Delete
                </button>
                <button type="submit" class="btn-primary" id="submitBtn" form="teacherForm">
                    <i class="fas fa-save mr-2"></i>
                    <span id="submitText">Save Changes</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Success Toast -->
    <div class="success-toast" id="successToast">
        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-emerald-600 text-xl"></i>
        </div>
        <div>
            <p class="font-bold text-slate-900 text-lg">Changes Saved!</p>
            <p class="text-sm text-slate-500">Teacher information has been updated</p>
        </div>
    </div>

    <script>
        // Track changes
        let originalValues = {};
        const form = document.getElementById('teacherForm');
        const inputs = form.querySelectorAll('input:not([readonly]), select');
        
        inputs.forEach(input => {
            originalValues[input.name] = input.value;
            input.addEventListener('change', trackChanges);
            input.addEventListener('input', trackChanges);
        });

        function trackChanges() {
            let changes = 0;
            inputs.forEach(input => {
                if (input.value !== originalValues[input.name]) {
                    changes++;
                }
            });
            document.getElementById('changesCount').textContent = changes;
        }

        // Toggle password section
        function togglePasswordSection() {
            const toggle = document.getElementById('passwordToggle');
            const section = document.getElementById('passwordSection');
            
            toggle.classList.toggle('active');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
            
            if (section.style.display === 'none') {
                document.getElementById('passwordInput').value = '';
                document.getElementById('confirmPasswordInput').value = '';
            }
        }

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.parentElement.querySelector('.toggle-password i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Mobile number formatter
        document.getElementById('mobileInput').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            if (value.length >= 4) value = value.slice(0, 4) + ' ' + value.slice(4);
            if (value.length >= 8) value = value.slice(0, 8) + ' ' + value.slice(8);
            e.target.value = value;
        });

        // Password match checker
        document.getElementById('confirmPasswordInput').addEventListener('input', function() {
            const matchHint = document.getElementById('matchHint');
            if (this.value === document.getElementById('passwordInput').value && this.value !== '') {
                matchHint.className = 'input-hint valid';
                matchHint.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            } else if (this.value !== '') {
                matchHint.className = 'input-hint invalid';
                matchHint.innerHTML = '<i class="fas fa-exclamation-circle"></i> Passwords do not match';
            } else {
                matchHint.className = 'input-hint';
                matchHint.innerHTML = '<i class="fas fa-info-circle"></i> Re-enter the new password';
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('submitText');
            btn.disabled = true;
            btnText.innerHTML = '<div class="spinner inline-block mr-2"></div> Saving...';
        });

        // Confirm delete
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this teacher account? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Success toast
        <?php if(session('success')): ?>
            setTimeout(() => {
                document.getElementById('successToast').classList.add('show');
                setTimeout(() => document.getElementById('successToast').classList.remove('show'), 4000);
            }, 500);
        <?php endif; ?>
    </script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\teachers\edit.blade.php ENDPATH**/ ?>