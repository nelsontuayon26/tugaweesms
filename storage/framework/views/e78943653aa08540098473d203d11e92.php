

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - Admin / Principal - Tugawe Elem</title>
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
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

        .admin-badge {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }

        .form-input {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .form-input:focus {
            background: white;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-group {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: color 0.2s ease;
        }
        .form-input:focus + .input-icon,
        .input-group:focus-within .input-icon {
            color: #3b82f6;
        }
        .form-input.has-icon {
            padding-left: 2.75rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .alert-success {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border-left: 4px solid #f59e0b;
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

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        /* Toggle switch */
        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            background: #e2e8f0;
            border-radius: 9999px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .toggle-switch.active {
            background: #3b82f6;
        }
        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .toggle-switch.active::after {
            transform: translateX(20px);
        }

        /* Permission checkbox */
        .permission-checkbox {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .permission-checkbox:checked {
            background: #3b82f6;
            border-color: #3b82f6;
        }
        .permission-checkbox:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
</head>
<body class="overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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

<div class="dashboard-container">
    <!-- Include Sidebar -->
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('admin.users.index')); ?>" class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-all">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Create New User</h1>
                            <span class="admin-badge">
                                <i class="fa-solid fa-shield-halved"></i>
                                Admin / Principal
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mt-0.5">Create a new system user with administrative or oversight privileges</p>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Admin Warning -->
            <div class="mb-6 p-4 alert-warning rounded-xl flex items-center gap-3 animate-fade-in-up shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                </div>
                <div>
                    <p class="text-amber-800 font-semibold text-sm">Admin / Principal Account Creation</p>
                    <p class="text-amber-700 text-sm">You are creating a user with elevated system access. Please verify all information before proceeding.</p>
                </div>
            </div>

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

            <form action="<?php echo e(route('admin.users.store')); ?>" method="POST" class="space-y-6">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Account Preview & Quick Settings -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Account Preview Card -->
                        <div class="glass-card p-6 animate-fade-in-up">
                            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-eye text-blue-600"></i>
                                Account Preview
                            </h3>
                            
                            <div class="text-center mb-4">
                                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-3">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <p class="text-sm text-slate-500">Default Avatar</p>
                            </div>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                    <span class="text-slate-500">Role</span>
                                    <span class="font-semibold text-blue-600" id="previewRole">Select Role</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                    <span class="text-slate-500">Status</span>
                                    <span class="font-semibold text-emerald-600" id="previewStatus">Active</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-lg">
                                    <span class="text-slate-500">Email Verified</span>
                                    <span class="font-semibold text-slate-600">No</span>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Permissions -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-1">
                            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
                                <i class="fa-solid fa-user-shield text-purple-600"></i>
                                Account Permissions
                            </h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="manage_users" class="permission-checkbox">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-700">Manage Users</p>
                                        <p class="text-xs text-slate-500">Create, edit, delete users</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="manage_teachers" class="permission-checkbox">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-700">Manage Teachers</p>
                                        <p class="text-xs text-slate-500">Full teacher management</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="manage_students" class="permission-checkbox">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-700">Manage Students</p>
                                        <p class="text-xs text-slate-500">Full student management</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="view_reports" class="permission-checkbox">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-700">View Reports</p>
                                        <p class="text-xs text-slate-500">Access all reports</p>
                                    </div>
                                </label>

                                <label class="flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors">
                                    <input type="checkbox" name="permissions[]" value="system_settings" class="permission-checkbox">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-slate-700">System Settings</p>
                                        <p class="text-xs text-slate-500">Modify system configuration</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-2">
                            <h3 class="text-sm font-bold text-slate-900 mb-4">Quick Actions</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Send Welcome Email</p>
                                        <p class="text-xs text-slate-500">Notify user via email</p>
                                    </div>
                                    <div class="toggle-switch active" onclick="this.classList.toggle('active')">
                                        <input type="hidden" name="send_welcome_email" value="1">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Require Password Change</p>
                                        <p class="text-xs text-slate-500">On first login</p>
                                    </div>
                                    <div class="toggle-switch" onclick="this.classList.toggle('active')">
                                        <input type="hidden" name="require_password_change" value="0">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">Two-Factor Auth</p>
                                        <p class="text-xs text-slate-500">Enable 2FA</p>
                                    </div>
                                    <div class="toggle-switch" onclick="this.classList.toggle('active')">
                                        <input type="hidden" name="two_factor_enabled" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                       <!-- Basic Information -->
<div class="glass-card p-6 animate-fade-in-up">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
            <i class="fa-solid fa-user-plus text-blue-600"></i>
        </div>
        <div>
            <h2 class="font-bold text-slate-900">Basic Information</h2>
            <p class="text-xs text-slate-500">Enter user's personal details</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Hidden Full Name Field (combines first + last) -->
        <input type="hidden" name="name" id="fullNameField" value="<?php echo e(old('name')); ?>">

        <!-- First Name -->
        <div>
            <label class="form-label">First Name <span class="text-red-500">*</span></label>
            <div class="input-group">
                <input type="text" 
                       name="first_name" 
                       id="firstName"
                       value="<?php echo e(old('first_name')); ?>"
                       class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Enter first name" required oninput="updateFullName()">
                <i class="fa-regular fa-user input-icon"></i>
            </div>
            <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Last Name -->
        <div>
            <label class="form-label">Last Name <span class="text-red-500">*</span></label>
            <div class="input-group">
                <input type="text" 
                       name="last_name" 
                       id="lastName"
                       value="<?php echo e(old('last_name')); ?>"
                       class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Enter last name" required oninput="updateFullName()">
                <i class="fa-regular fa-user input-icon"></i>
            </div>
            <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Middle Name -->
        <div>
            <label class="form-label">Middle Name <span class="text-slate-400 font-normal">(Optional)</span></label>
            <div class="input-group">
                <input type="text" 
                       name="middle_name" 
                       value="<?php echo e(old('middle_name')); ?>"
                       class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                       placeholder="Enter middle name">
                <i class="fa-regular fa-user input-icon"></i>
            </div>
        </div>

        <!-- Username -->
        <div>
            <label class="form-label">Username <span class="text-red-500">*</span></label>
            <div class="input-group">
                <input type="text" 
                       name="username" 
                       value="<?php echo e(old('username')); ?>"
                       class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       placeholder="Enter username" required>
                <i class="fa-solid fa-at input-icon"></i>
            </div>
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                    <?php echo e($message); ?>

                </p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</div>
                        <!-- Contact Information -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-1">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fa-solid fa-address-card text-purple-600"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-slate-900">Contact Information</h2>
                                    <p class="text-xs text-slate-500">Contact details for the user</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                                    <div class="input-group">
                                        <input type="email" 
                                               name="email" 
                                               value="<?php echo e(old('email')); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter email address" required>
                                        <i class="fa-regular fa-envelope input-icon"></i>
                                    </div>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="form-label">Phone Number <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <div class="input-group">
                                        <input type="tel" 
                                               name="phone" 
                                               value="<?php echo e(old('phone')); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                                               placeholder="Enter phone number">
                                        <i class="fa-solid fa-phone input-icon"></i>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="form-label">Address <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <textarea name="address" 
                                              rows="3"
                                              class="form-input w-full px-4 py-2.5 rounded-xl text-sm"
                                              placeholder="Enter full address"><?php echo e(old('address')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Account Configuration -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-2">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                    <i class="fa-solid fa-gear text-emerald-600"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-slate-900">Account Configuration</h2>
                                    <p class="text-xs text-slate-500">Set role, status, and security</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Role -->
                                <div>
                                    <label class="form-label">User Role <span class="text-red-500">*</span></label>
                                    <div class="input-group">
                                        <select name="role_id" 
                                                id="roleSelect"
                                                class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                onchange="updatePreview()" required>
                                            <option value="">Select a role</option>
                                            <?php $__currentLoopData = $roles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($role->id); ?>" <?php echo e(old('role_id') == $role->id ? 'selected' : ''); ?>>
                                                    <?php echo e($role->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="fa-solid fa-user-shield input-icon"></i>
                                    </div>
                                    <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                               <!-- Status -->
<div>
    <label class="form-label">Status <span class="text-red-500">*</span></label>
    <div class="input-group">
        <select name="status" 
                class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon appearance-none cursor-pointer <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                required>
            
            <?php
                $currentStatus = old('status') ?? $user->status ?? 'active';
            ?>
            
            <option value="active" <?php echo e($currentStatus == 'active' ? 'selected' : ''); ?>>Active</option>
            <option value="inactive" <?php echo e($currentStatus == 'inactive' ? 'selected' : ''); ?>>Inactive</option>
        </select>
        <i class="fa-solid fa-toggle-on input-icon"></i>
        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
    </div>
    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
            <?php echo e($message); ?>

        </p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div>
                                <!-- Password -->
                                <div>
                                    <label class="form-label">Password <span class="text-red-500">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password" 
                                               id="password"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter secure password" required>
                                        <i class="fa-solid fa-lock input-icon"></i>
                                        <button type="button" 
                                                onclick="togglePassword('password', this)"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 flex items-center gap-2">
                                        <button type="button" onclick="generatePassword()" class="text-xs text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                                            Generate Strong Password
                                        </button>
                                    </div>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                            <i class="fa-solid fa-circle-exclamation text-[10px]"></i>
                                            <?php echo e($message); ?>

                                        </p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                                               placeholder="Confirm password" required>
                                        <i class="fa-solid fa-lock input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Notes -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-2">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <i class="fa-solid fa-note-sticky text-amber-600"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-slate-900">Internal Notes</h2>
                                    <p class="text-xs text-slate-500">Internal notes (only visible to admins & principals)</p>
                                </div>
                            </div>
                            
                            <textarea name="admin_notes" 
                                      rows="4"
                                      class="form-input w-full px-4 py-2.5 rounded-xl text-sm"
                                      placeholder="Add any internal notes about this user..."><?php echo e(old('admin_notes')); ?></textarea>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 animate-fade-in-up">
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
                               class="btn-secondary px-6 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                                <i class="fa-solid fa-xmark"></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="btn-primary px-8 py-2.5 rounded-xl font-semibold text-sm text-white flex items-center gap-2">
                                <i class="fa-solid fa-user-plus"></i>
                                Create User
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div>


<script>
    // Combine first and last name into full name for validation
    function updateFullName() {
        const first = document.getElementById('firstName').value || '';
        const last = document.getElementById('lastName').value || '';
        document.getElementById('fullNameField').value = (first + ' ' + last).trim();
    }
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', updateFullName);
</script>

<script>
    // Update preview card based on selections
    function updatePreview() {
        const roleSelect = document.getElementById('roleSelect');
        const statusSelect = document.getElementById('statusSelect');
        
        const roleText = roleSelect.options[roleSelect.selectedIndex].text;
        const statusText = statusSelect.options[statusSelect.selectedIndex].text;
        
        document.getElementById('previewRole').textContent = roleSelect.value ? roleText : 'Select Role';
        document.getElementById('previewStatus').textContent = statusText;
        
        // Update status color
        const statusPreview = document.getElementById('previewStatus');
        statusPreview.className = 'font-semibold ';
        if (statusText === 'Active') {
            statusPreview.classList.add('text-emerald-600');
        } else if (statusText === 'Inactive') {
            statusPreview.classList.add('text-slate-600');
        } else if (statusText === 'Pending') {
            statusPreview.classList.add('text-amber-600');
        }
    }

    // Toggle password visibility
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        
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

    // Generate random password
    function generatePassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < 12; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        
        const passwordInput = document.getElementById('password');
        passwordInput.value = password;
        passwordInput.type = 'text';
        
        // Copy to clipboard
        navigator.clipboard.writeText(password).then(() => {
            // Show temporary notification
            const btn = document.querySelector('button[onclick="generatePassword()"]');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check"></i> Copied to clipboard!';
            btn.classList.add('text-emerald-600');
            
            setTimeout(() => {
                btn.innerHTML = originalHTML;
                btn.classList.remove('text-emerald-600');
            }, 2000);
        });
    }

    // Toggle switch functionality
    document.querySelectorAll('.toggle-switch').forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.querySelector('input');
            input.value = this.classList.contains('active') ? '1' : '0';
        });
    });

    // Auto-dismiss alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert-success, .alert-error, .alert-warning');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                alert.style.transition = 'all 0.3s ease';
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        });
        
        // Initialize preview
        updatePreview();
    });
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\users\create.blade.php ENDPATH**/ ?>