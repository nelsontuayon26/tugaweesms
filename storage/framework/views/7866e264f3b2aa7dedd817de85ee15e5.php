

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - <?php echo e($isAdminLevel ? 'Admin / Principal' : ''); ?> - Tugawe Elem</title>
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

        /* Avatar upload styles */
        .avatar-upload {
            position: relative;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .avatar-upload:hover .avatar-overlay {
            opacity: 1;
        }
        .avatar-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
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
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit User</h1>
                        <p class="text-sm text-slate-500 mt-0.5">Update <?php echo e($isAdminLevel ? 'admin / principal' : 'user'); ?> information</p>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Avatar & Quick Info -->
                <div class="lg:col-span-1">
                    <div class="glass-card p-6 animate-fade-in-up">
                        <div class="text-center">
                            <!-- Avatar Upload -->
                            <div class="avatar-upload w-32 h-32 mx-auto mb-4 relative">
                                <?php if($user->photo): ?>
                                    <img src="<?php echo e(profile_photo_url($user->photo)); ?>" 
                                         alt="User Avatar" 
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->first_name . '+' . $user->last_name)); ?>&background=3b82f6&color=fff&size=128" 
                                         alt="User Avatar" 
                                         class="w-full h-full rounded-full object-cover border-4 border-white shadow-lg">
                                <?php endif; ?>
                                <div class="avatar-overlay">
                                    <i class="fa-solid fa-camera text-white text-2xl"></i>
                                </div>
                                <input type="file" name="avatar" class="hidden" id="avatarInput" accept="image/*">
                            </div>
                            
                            <h3 class="text-lg font-bold text-slate-900"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></h3>
                            <p class="text-sm text-slate-500 mb-4"><?php echo e($user->email); ?></p>
                            
                            <div class="flex items-center justify-center gap-2 mb-4">
                                <?php
                                    $statusClass = match($user->status ?? 'active') {
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'inactive' => 'bg-slate-50 text-slate-600 border-slate-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        default => 'bg-emerald-50 text-emerald-700 border-emerald-200'
                                    };
                                ?>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border <?php echo e($statusClass); ?>">
                                    <i class="fa-solid fa-circle text-[8px]"></i>
                                    <?php echo e(ucfirst($user->status ?? 'Active')); ?>

                                </span>
                            </div>

                            <div class="pt-4 border-t border-slate-100 text-left space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Member Since</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($user->created_at->format('M d, Y')); ?></span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">Last Updated</span>
                                    <span class="font-semibold text-slate-900"><?php echo e($user->updated_at->format('M d, Y')); ?></span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-slate-500">User ID</span>
                                    <span class="font-semibold text-slate-900">#<?php echo e($user->id); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="glass-card p-6 mt-6 animate-fade-in-up animate-delay-1 border-red-100">
                        <h4 class="text-sm font-bold text-red-600 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Danger Zone
                        </h4>
                        <p class="text-xs text-slate-500 mb-4">Deleting this user will remove all associated data permanently.</p>
                        <button type="button" 
                                onclick="openDeleteModal('<?php echo e(route('admin.users.destroy', $user)); ?>', '<?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?>')"
                                class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg font-semibold text-sm hover:bg-red-100 border border-red-200 transition-all flex items-center justify-center gap-2">
                            <i class="fa-regular fa-trash-can"></i>
                            Delete User
                        </button>
                    </div>
                </div>

                <!-- Right Column: Edit Form -->
                <div class="lg:col-span-2">
                    <form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <!-- Personal Information -->
                        <div class="glass-card p-6 animate-fade-in-up">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                    <i class="fa-solid fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-slate-900">Personal Information</h2>
                                    <p class="text-xs text-slate-500">Update user's basic details</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label class="form-label">First Name</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="first_name" 
                                               value="<?php echo e(old('first_name', $user->first_name)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter first name">
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
                                </div>

                                <!-- Last Name -->
                                <div>
                                    <label class="form-label">Last Name</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="last_name" 
                                               value="<?php echo e(old('last_name', $user->last_name)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter last name">
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
                                               value="<?php echo e(old('middle_name', $user->middle_name)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                                               placeholder="Enter middle name">
                                        <i class="fa-regular fa-user input-icon"></i>
                                    </div>
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="form-label">Username</label>
                                    <div class="input-group">
                                        <input type="text" 
                                               name="username" 
                                               value="<?php echo e(old('username', $user->username)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter username">
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
                                    <p class="text-xs text-slate-500">Update contact details</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Email -->
                                <div class="md:col-span-2">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <input type="email" 
                                               name="email" 
                                               value="<?php echo e(old('email', $user->email)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter email address">
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
                                               value="<?php echo e(old('phone', $user->phone)); ?>"
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                                               placeholder="Enter phone number">
                                        <i class="fa-solid fa-phone input-icon"></i>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="md:col-span-2">
                                    <label class="form-label">Address <span class="text-slate-400 font-normal">(Optional)</span></label>
                                    <div class="input-group">
                                        <textarea name="address" 
                                                  rows="3"
                                                  class="form-input w-full px-4 py-2.5 rounded-xl text-sm <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                  placeholder="Enter address"><?php echo e(old('address', $user->address)); ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Settings -->
                        <div class="glass-card p-6 animate-fade-in-up animate-delay-2">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                    <i class="fa-solid fa-shield-halved text-emerald-600"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-slate-900">Account Settings</h2>
                                    <p class="text-xs text-slate-500">Manage <?php echo e($isAdminLevel ? 'admin / principal role' : 'role'); ?> and status</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                           <!-- Role -->
<div>
    <label class="form-label">Role <span class="text-red-500">*</span></label>
    <div class="input-group">
        <select name="role_id" 
                class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                required>
            <option value="">-- Select Role --</option>
            <?php $__empty_1 = true; $__currentLoopData = $roles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <option value="<?php echo e($role->id); ?>" <?php echo e(old('role_id', $user->role_id) == $role->id ? 'selected' : ''); ?>>
                    <?php echo e($role->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <option value="" disabled>No roles available</option>
            <?php endif; ?>
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
                                <div class="md:col-span-2">
                                    <label class="form-label">New Password <span class="text-slate-400 font-normal">(Leave blank to keep current)</span></label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password" 
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="Enter new password">
                                        <i class="fa-solid fa-lock input-icon"></i>
                                        <button type="button" 
                                                onclick="togglePassword(this)"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors">
                                            <i class="fa-regular fa-eye"></i>
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
                                <div class="md:col-span-2">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               class="form-input w-full px-4 py-2.5 rounded-xl text-sm has-icon"
                                               placeholder="Confirm new password">
                                        <i class="fa-solid fa-lock input-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 animate-fade-in-up">
                            <a href="<?php echo e(route('admin.users.index')); ?>" 
                               class="btn-secondary px-6 py-2.5 rounded-xl font-semibold text-sm flex items-center gap-2">
                                <i class="fa-solid fa-xmark"></i>
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="btn-primary px-6 py-2.5 rounded-xl font-semibold text-sm text-white flex items-center gap-2">
                                <i class="fa-solid fa-check"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 modal-backdrop transition-opacity opacity-0" id="modalBackdrop" onclick="closeDeleteModal()"></div>
    
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
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
            
            <div class="p-6">
                <p class="text-slate-600 text-sm leading-relaxed">
                    Are you sure you want to delete <span id="deleteUserName" class="font-semibold text-slate-900"></span>? 
                    All associated data will be permanently removed from the system.
                </p>
            </div>
            
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
        
        backdrop.classList.add('opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
    
    // Auto-dismiss alerts
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
    
    // Password toggle
    function togglePassword(btn) {
        const input = btn.parentElement.querySelector('input');
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
    
    // Avatar upload click
    document.querySelector('.avatar-upload')?.addEventListener('click', function() {
        document.getElementById('avatarInput').click();
    });
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\users\edit.blade.php ENDPATH**/ ?>