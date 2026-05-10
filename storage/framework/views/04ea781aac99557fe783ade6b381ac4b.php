<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Teacher Profile</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.06), 0 2px 8px -1px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .tab-liquid {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .tab-liquid::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .tab-liquid:hover::before {
            opacity: 1;
        }
        
        .tab-active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4), 0 4px 10px -2px rgba(79, 70, 229, 0.2);
            transform: translateY(-1px);
        }
        
        .input-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }
        
        .section-header {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(226, 232, 240, 0.8) 100%);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        
        .sidebar-transition {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #94a3b8 0%, #64748b 100%);
        }
        
        .tab-container {
            background: rgba(226, 232, 240, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .avatar-glow {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        
        /* Form Actions */
        .form-actions {
            position: sticky;
            bottom: 0;
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding: 16px 24px;
            margin: 0 -24px -24px -24px;
            z-index: 20;
        }
        
        .form-section {
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .error-message {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }">

    
    <?php if($errors->any()): ?>
    <div class="fixed top-4 right-4 z-50 max-w-sm error-message">
        <div class="glass-panel bg-red-50/90 border border-red-200/50 rounded-2xl p-4 shadow-2xl">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-red-900">Please check your input</p>
                    <ul class="text-xs text-red-700 mt-1 space-y-1">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button onclick="this.closest('.fixed').style.display='none'" class="text-red-400 hover:text-red-700 transition-colors p-1 hover:bg-red-100 rounded-lg">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="lg:hidden fixed top-0 left-0 right-0 z-40 glass-panel border-b border-white/50 px-4 py-3 flex items-center justify-between">
        <button @click="mobileOpen = !mobileOpen" class="p-2.5 rounded-xl hover:bg-white/60 transition-all active:scale-95">
            <i class="fas fa-bars text-slate-700 text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <h1 class="text-lg font-bold gradient-text">Edit Profile</h1>
        </div>
        <?php if($user->photo): ?>
            <img src="<?php echo e(profile_photo_url($user->photo)); ?>" alt="Profile" class="w-9 h-9 rounded-full object-cover avatar-glow">
        <?php else: ?>
            <div class="w-9 h-9 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm avatar-glow">
                <?php echo e(substr($user->first_name ?? 'T', 0, 1)); ?><?php echo e(substr($user->last_name ?? 'P', 0, 1)); ?>

            </div>
        <?php endif; ?>
    </div>

    
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
         style="display: none;"></div>

    <div class="flex h-screen overflow-hidden pt-14 lg:pt-0">
        
        
        <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden lg:ml-72 relative">
            
            
            <header class="hidden lg:flex sticky top-0 z-30 glass-panel border-b border-white/60 px-6 xl:px-8 py-4 flex-shrink-0 items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold gradient-text tracking-tight">Edit Profile</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Update your personal and professional information</p>
                </div>
                <div class="flex items-center gap-3">
                    <?php if($user->photo): ?>
                        <img src="<?php echo e(profile_photo_url($user->photo)); ?>" alt="Profile" class="w-11 h-11 rounded-full object-cover avatar-glow ring-4 ring-white/50">
                    <?php else: ?>
                        <div class="w-11 h-11 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-lg avatar-glow ring-4 ring-white/50">
                            <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </header>

            
            <main class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar p-4 lg:p-6 xl:p-8 bg-gradient-to-br from-slate-50 via-white to-slate-100">
                
                <form id="profile-form" action="<?php echo e(route('teacher.profile.update')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div class="flex flex-wrap gap-2 mb-6 p-2 tab-container rounded-2xl w-fit max-w-full overflow-x-auto shadow-sm">
                        <button type="button" onclick="switchTab('account')" id="tab-account" class="tab-liquid tab-active px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-user-circle text-lg"></i>
                            <span>Account</span>
                        </button>
                        <button type="button" onclick="switchTab('personal')" id="tab-personal" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-id-card text-lg"></i>
                            <span>Personal</span>
                        </button>
                        <button type="button" onclick="switchTab('contact')" id="tab-contact" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-address-book text-lg"></i>
                            <span>Contact</span>
                        </button>
                        <button type="button" onclick="switchTab('employment')" id="tab-employment" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-briefcase text-lg"></i>
                            <span>Employment</span>
                        </button>
                        <button type="button" onclick="switchTab('education')" id="tab-education" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-lg"></i>
                            <span>Education</span>
                        </button>
                        <button type="button" onclick="switchTab('emergency')" id="tab-emergency" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-ambulance text-lg"></i>
                            <span>Emergency</span>
                        </button>
                        <button type="button" onclick="switchTab('salary')" id="tab-salary" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-money-check-alt text-lg"></i>
                            <span>Salary</span>
                        </button>
                        <button type="button" onclick="switchTab('credentials')" id="tab-credentials" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-certificate text-lg"></i>
                            <span>Credentials</span>
                        </button>
                        <button type="button" onclick="switchTab('government')" id="tab-government" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-id-card-alt text-lg"></i>
                            <span>Gov't IDs</span>
                        </button>
                        <button type="button" onclick="switchTab('family')" id="tab-family" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-users text-lg"></i>
                            <span>Family</span>
                        </button>
                        <button type="button" onclick="switchTab('others')" id="tab-others" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-ellipsis-h text-lg"></i>
                            <span>Others</span>
                        </button>
                    </div>

                    
                    <div id="content-account" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                Account Information
                            </h2>
                        </div>

                        
                        <div class="mb-8 p-6 bg-gradient-to-br from-indigo-50 to-white rounded-2xl border border-indigo-100">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                
                                <div class="relative group">
                                    <div id="photoPreviewContainer" class="w-28 h-28 rounded-2xl overflow-hidden shadow-lg border-4 border-white <?php echo e($user->photo ? '' : 'hidden'); ?>">
                                        <img id="photoPreview" src="<?php echo e(profile_photo_url($user->photo)); ?>" 
                                             alt="Profile Photo" class="w-full h-full object-cover">
                                    </div>
                                    <div id="photoPlaceholder" class="w-28 h-28 rounded-2xl gradient-bg flex items-center justify-center text-white font-bold text-2xl shadow-lg border-4 border-white <?php echo e($user->photo ? 'hidden' : ''); ?>">
                                        <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                                    </div>
                                    
                                    <div class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                         onclick="document.getElementById('photoInput').click()">
                                        <i class="fas fa-camera text-white text-xl"></i>
                                    </div>
                                </div>

                                
                                <div class="flex-1 text-center sm:text-left">
                                    <h4 class="text-sm font-bold text-slate-800 mb-1">Profile Photo</h4>
                                    <p class="text-xs text-slate-500 mb-3">Upload a clear photo of yourself. Max 2MB. JPG, PNG only.</p>
                                    <div class="flex flex-wrap items-center gap-3 justify-center sm:justify-start">
                                        <label for="photoInput" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-md shadow-indigo-500/20">
                                            <i class="fas fa-upload"></i>
                                            <span>Choose Photo</span>
                                        </label>
                                        <?php if($user->photo): ?>
                                            <button type="button" onclick="removePhoto()" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl transition-colors border border-rose-200">
                                                <i class="fas fa-trash-alt"></i>
                                                <span>Remove</span>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <input type="file" id="photoInput" name="photo" accept="image/jpeg,image/png,image/jpg" class="hidden" onchange="previewPhoto(this)">
                                    <input type="hidden" name="remove_photo" id="removePhotoFlag" value="0">
                                    <p id="photoFileName" class="text-xs text-indigo-600 font-medium mt-2"></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">First Name</label>
                                <input type="text" name="first_name" value="<?php echo e(old('first_name', $user->first_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="First Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Middle Name</label>
                                <input type="text" name="middle_name" value="<?php echo e(old('middle_name', $user->middle_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Middle Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Last Name</label>
                                <input type="text" name="last_name" value="<?php echo e(old('last_name', $user->last_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Last Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Suffix</label>
                                <input type="text" name="suffix" value="<?php echo e(old('suffix', $user->suffix)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Suffix (Jr., Sr., etc.)">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Address</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Email">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</label>
                                <div class="relative">
                                    <i class="fas fa-at absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="username" value="<?php echo e(old('username', $user->username)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Username">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Date of Birth</label>
                                <div class="relative">
                                    <i class="fas fa-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $teacher->date_of_birth?->format('Y-m-d'))); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-personal" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                Personal Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">DepEd ID</label>
                                <input type="text" name="deped_id" value="<?php echo e(old('deped_id', $teacher->deped_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none font-mono" placeholder="DepEd ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Gender</label>
                                <select name="gender" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Gender</option>
                                    <option value="male" <?php echo e(old('gender', $teacher->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="female" <?php echo e(old('gender', $teacher->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="other" <?php echo e(old('gender', $teacher->gender) == 'other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Civil Status</label>
                                <select name="civil_status" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Status</option>
                                    <option value="single" <?php echo e(old('civil_status', $teacher->civil_status) == 'single' ? 'selected' : ''); ?>>Single</option>
                                    <option value="married" <?php echo e(old('civil_status', $teacher->civil_status) == 'married' ? 'selected' : ''); ?>>Married</option>
                                    <option value="widowed" <?php echo e(old('civil_status', $teacher->civil_status) == 'widowed' ? 'selected' : ''); ?>>Widowed</option>
                                    <option value="separated" <?php echo e(old('civil_status', $teacher->civil_status) == 'separated' ? 'selected' : ''); ?>>Separated</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Nationality</label>
                                <input type="text" name="nationality" value="<?php echo e(old('nationality', $teacher->nationality)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Nationality">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Religion</label>
                                <input type="text" name="religion" value="<?php echo e(old('religion', $teacher->religion)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Religion">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Blood Type</label>
                                <select name="blood_type" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Blood Type</option>
                                    <option value="A+" <?php echo e(old('blood_type', $teacher->blood_type) == 'A+' ? 'selected' : ''); ?>>A+</option>
                                    <option value="A-" <?php echo e(old('blood_type', $teacher->blood_type) == 'A-' ? 'selected' : ''); ?>>A-</option>
                                    <option value="B+" <?php echo e(old('blood_type', $teacher->blood_type) == 'B+' ? 'selected' : ''); ?>>B+</option>
                                    <option value="B-" <?php echo e(old('blood_type', $teacher->blood_type) == 'B-' ? 'selected' : ''); ?>>B-</option>
                                    <option value="AB+" <?php echo e(old('blood_type', $teacher->blood_type) == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                                    <option value="AB-" <?php echo e(old('blood_type', $teacher->blood_type) == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                                    <option value="O+" <?php echo e(old('blood_type', $teacher->blood_type) == 'O+' ? 'selected' : ''); ?>>O+</option>
                                    <option value="O-" <?php echo e(old('blood_type', $teacher->blood_type) == 'O-' ? 'selected' : ''); ?>>O-</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Place of Birth</label>
                                <input type="text" name="place_of_birth" value="<?php echo e(old('place_of_birth', $teacher->place_of_birth)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Place of Birth">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-contact" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                Contact Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mobile Number</label>
                                <div class="relative">
                                    <i class="fas fa-mobile-alt absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="mobile_number" value="<?php echo e(old('mobile_number', $teacher->mobile_number)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Mobile Number">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Telephone Number</label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="telephone_number" value="<?php echo e(old('telephone_number', $teacher->telephone_number)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Telephone Number">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Street Address</label>
                                <input type="text" name="street_address" value="<?php echo e(old('street_address', $teacher->street_address)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Street Address">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Barangay</label>
                                <input type="text" name="barangay" value="<?php echo e(old('barangay', $teacher->barangay)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Barangay">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">City/Municipality</label>
                                <input type="text" name="city_municipality" value="<?php echo e(old('city_municipality', $teacher->city_municipality)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="City/Municipality">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Province</label>
                                <input type="text" name="province" value="<?php echo e(old('province', $teacher->province)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Province">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">ZIP Code</label>
                                <input type="text" name="zip_code" value="<?php echo e(old('zip_code', $teacher->zip_code)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none font-mono" placeholder="ZIP Code">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Region</label>
                                <input type="text" name="region" value="<?php echo e(old('region', $teacher->region)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Region">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-employment" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-building"></i>
                                </div>
                                Employment Details
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Employment Status</label>
                                <select name="employment_status" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Status</option>
                                    <option value="permanent" <?php echo e(old('employment_status', $teacher->employment_status) == 'permanent' ? 'selected' : ''); ?>>Permanent</option>
                                    <option value="contractual" <?php echo e(old('employment_status', $teacher->employment_status) == 'contractual' ? 'selected' : ''); ?>>Contractual</option>
                                    <option value="substitute" <?php echo e(old('employment_status', $teacher->employment_status) == 'substitute' ? 'selected' : ''); ?>>Substitute</option>
                                    <option value="part_time" <?php echo e(old('employment_status', $teacher->employment_status) == 'part_time' ? 'selected' : ''); ?>>Part-time</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Hired</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="date_hired" value="<?php echo e(old('date_hired', $teacher->date_hired?->format('Y-m-d'))); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Regularized</label>
                                <div class="relative">
                                    <i class="fas fa-calendar-check absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="date_regularized" value="<?php echo e(old('date_regularized', $teacher->date_regularized?->format('Y-m-d'))); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Current Status</label>
                                <select name="current_status" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Status</option>
                                    <option value="active" <?php echo e(old('current_status', $teacher->current_status) == 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="on_leave" <?php echo e(old('current_status', $teacher->current_status) == 'on_leave' ? 'selected' : ''); ?>>On Leave</option>
                                    <option value="suspended" <?php echo e(old('current_status', $teacher->current_status) == 'suspended' ? 'selected' : ''); ?>>Suspended</option>
                                    <option value="retired" <?php echo e(old('current_status', $teacher->current_status) == 'retired' ? 'selected' : ''); ?>>Retired</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Teaching Level</label>
                                <input type="text" name="teaching_level" value="<?php echo e(old('teaching_level', $teacher->teaching_level)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Teaching Level">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Position</label>
                                <input type="text" name="position" value="<?php echo e(old('position', $teacher->position)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Position">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Designation</label>
                                <input type="text" name="designation" value="<?php echo e(old('designation', $teacher->designation)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Designation">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Department</label>
                                <input type="text" name="department" value="<?php echo e(old('department', $teacher->department)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Department">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-education" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-university"></i>
                                </div>
                                Educational Background
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Highest Education</label>
                                <select name="highest_education" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="">Select Education</option>
                                    <option value="bachelor" <?php echo e(old('highest_education', $teacher->highest_education) == 'bachelor' ? 'selected' : ''); ?>>Bachelor's Degree</option>
                                    <option value="master" <?php echo e(old('highest_education', $teacher->highest_education) == 'master' ? 'selected' : ''); ?>>Master's Degree</option>
                                    <option value="doctorate" <?php echo e(old('highest_education', $teacher->highest_education) == 'doctorate' ? 'selected' : ''); ?>>Doctorate</option>
                                    <option value="post_doctorate" <?php echo e(old('highest_education', $teacher->highest_education) == 'post_doctorate' ? 'selected' : ''); ?>>Post-Doctorate</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Degree Program</label>
                                <input type="text" name="degree_program" value="<?php echo e(old('degree_program', $teacher->degree_program)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Degree Program">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Major</label>
                                <input type="text" name="major" value="<?php echo e(old('major', $teacher->major)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Major">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Minor</label>
                                <input type="text" name="minor" value="<?php echo e(old('minor', $teacher->minor)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Minor">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">School Graduated</label>
                                <input type="text" name="school_graduated" value="<?php echo e(old('school_graduated', $teacher->school_graduated)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="School Graduated">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Year Graduated</label>
                                <div class="relative">
                                    <i class="fas fa-graduation-cap absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="number" name="year_graduated" value="<?php echo e(old('year_graduated', $teacher->year_graduated)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Year Graduated">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PRC License Number</label>
                                <input type="text" name="prc_license_number" value="<?php echo e(old('prc_license_number', $teacher->prc_license_number)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none font-mono" placeholder="PRC License Number">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PRC Validity</label>
                                <div class="relative">
                                    <i class="fas fa-certificate absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="date" name="prc_license_validity" value="<?php echo e(old('prc_license_validity', $teacher->prc_license_validity?->format('Y-m-d'))); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Years of Experience</label>
                                <div class="relative">
                                    <i class="fas fa-star absolute left-4 top-1/2 -translate-y-1/2 text-amber-400"></i>
                                    <input type="number" name="years_of_experience" value="<?php echo e(old('years_of_experience', $teacher->years_of_experience)); ?>" class="input-field w-full pl-11 pr-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Years of Experience">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-emergency" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-ambulance"></i></div>
                                Emergency Contact
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Name</label>
                                <input type="text" name="emergency_contact_name" value="<?php echo e(old('emergency_contact_name', $teacher->emergency_contact_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Emergency Contact Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Relationship</label>
                                <input type="text" name="emergency_contact_relationship" value="<?php echo e(old('emergency_contact_relationship', $teacher->emergency_contact_relationship)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Relationship">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Number</label>
                                <input type="text" name="emergency_contact_number" value="<?php echo e(old('emergency_contact_number', $teacher->emergency_contact_number)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Contact Number">
                            </div>
                            <div class="space-y-2 sm:col-span-2 lg:col-span-3">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Emergency Contact Address</label>
                                <input type="text" name="emergency_contact_address" value="<?php echo e(old('emergency_contact_address', $teacher->emergency_contact_address)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Emergency Contact Address">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-salary" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-money-check-alt"></i></div>
                                Salary & Bank Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Salary Grade</label>
                                <input type="text" name="salary_grade" value="<?php echo e(old('salary_grade', $teacher->salary_grade)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Salary Grade">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Step Increment</label>
                                <input type="text" name="step_increment" value="<?php echo e(old('step_increment', $teacher->step_increment)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Step Increment">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Basic Salary</label>
                                <input type="number" step="0.01" name="basic_salary" value="<?php echo e(old('basic_salary', $teacher->basic_salary)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Basic Salary">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bank Name</label>
                                <input type="text" name="bank_name" value="<?php echo e(old('bank_name', $teacher->bank_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Bank Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bank Account Number</label>
                                <input type="text" name="bank_account_number" value="<?php echo e(old('bank_account_number', $teacher->bank_account_number)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Bank Account Number">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-credentials" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-certificate"></i></div>
                                Professional Credentials
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">LET Passer</label>
                                <select name="let_passer" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="0" <?php echo e(old('let_passer', $teacher->let_passer) ? '' : 'selected'); ?>>No</option>
                                    <option value="1" <?php echo e(old('let_passer', $teacher->let_passer) ? 'selected' : ''); ?>>Yes</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Board Rating</label>
                                <input type="text" name="board_rating" value="<?php echo e(old('board_rating', $teacher->board_rating)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Board Rating">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">TESDA NC</label>
                                <input type="text" name="tesda_nc" value="<?php echo e(old('tesda_nc', $teacher->tesda_nc)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="TESDA NC">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">TESDA Sector</label>
                                <input type="text" name="tesda_sector" value="<?php echo e(old('tesda_sector', $teacher->tesda_sector)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="TESDA Sector">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Previous School</label>
                                <input type="text" name="previous_school" value="<?php echo e(old('previous_school', $teacher->previous_school)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Previous School">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Previous Position</label>
                                <input type="text" name="previous_position" value="<?php echo e(old('previous_position', $teacher->previous_position)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Previous Position">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Honors Received</label>
                                <input type="text" name="honors_received" value="<?php echo e(old('honors_received', $teacher->honors_received)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Honors Received">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-government" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-id-card-alt"></i></div>
                                Government IDs
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">GSIS ID</label>
                                <input type="text" name="gsis_id" value="<?php echo e(old('gsis_id', $teacher->gsis_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="GSIS ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pag-IBIG ID</label>
                                <input type="text" name="pagibig_id" value="<?php echo e(old('pagibig_id', $teacher->pagibig_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Pag-IBIG ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">PhilHealth ID</label>
                                <input type="text" name="philhealth_id" value="<?php echo e(old('philhealth_id', $teacher->philhealth_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="PhilHealth ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">SSS ID</label>
                                <input type="text" name="sss_id" value="<?php echo e(old('sss_id', $teacher->sss_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="SSS ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">TIN ID</label>
                                <input type="text" name="tin_id" value="<?php echo e(old('tin_id', $teacher->tin_id)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="TIN ID">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pag-IBIG RTN</label>
                                <input type="text" name="pagibig_rtn" value="<?php echo e(old('pagibig_rtn', $teacher->pagibig_rtn)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Pag-IBIG RTN">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-family" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-users"></i></div>
                                Family Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Spouse Name</label>
                                <input type="text" name="spouse_name" value="<?php echo e(old('spouse_name', $teacher->spouse_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Spouse Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Spouse Occupation</label>
                                <input type="text" name="spouse_occupation" value="<?php echo e(old('spouse_occupation', $teacher->spouse_occupation)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Spouse Occupation">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Spouse Contact</label>
                                <input type="text" name="spouse_contact" value="<?php echo e(old('spouse_contact', $teacher->spouse_contact)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Spouse Contact">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Number of Children</label>
                                <input type="number" name="number_of_children" value="<?php echo e(old('number_of_children', $teacher->number_of_children)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Number of Children">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Father's Name</label>
                                <input type="text" name="father_name" value="<?php echo e(old('father_name', $teacher->father_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Father's Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Father's Occupation</label>
                                <input type="text" name="father_occupation" value="<?php echo e(old('father_occupation', $teacher->father_occupation)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Father's Occupation">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mother's Name</label>
                                <input type="text" name="mother_name" value="<?php echo e(old('mother_name', $teacher->mother_name)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Mother's Name">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mother's Occupation</label>
                                <input type="text" name="mother_occupation" value="<?php echo e(old('mother_occupation', $teacher->mother_occupation)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Mother's Occupation">
                            </div>
                        </div>
                    </div>

                    
                    <div id="content-others" class="form-section glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60 hidden">
                        <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-ellipsis-h"></i></div>
                                Other Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Class Adviser</label>
                                <select name="is_class_adviser" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                    <option value="0" <?php echo e(old('is_class_adviser', $teacher->is_class_adviser) ? '' : 'selected'); ?>>No</option>
                                    <option value="1" <?php echo e(old('is_class_adviser', $teacher->is_class_adviser) ? 'selected' : ''); ?>>Yes</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Advisory Class</label>
                                <input type="text" name="advisory_class" value="<?php echo e(old('advisory_class', $teacher->advisory_class)); ?>" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Advisory Class">
                            </div>
                            <div class="space-y-2 sm:col-span-2 lg:col-span-3">
                                <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Remarks</label>
                                <textarea name="remarks" rows="3" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Remarks"><?php echo e(old('remarks', $teacher->remarks)); ?></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div class="form-actions flex items-center justify-end gap-3">
                        <a href="<?php echo e(route('teacher.profile')); ?>" 
                           class="px-6 py-2.5 bg-white border-2 border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <button type="submit" form="profile-form"
                                class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i>Save Changes
                        </button>
                    </div>

                </form>

            </main>
        </div>
    </div>

<script>
    // Tab switching
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.form-section').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Update tab buttons
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.remove('tab-active');
            tab.classList.add('text-slate-600');
        });
        
        // Activate selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('tab-active');
        activeTab.classList.remove('text-slate-600');
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close any open modals here if needed
        }
    });

    // Auto-hide error messages after 8 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const errorDiv = document.querySelector('.fixed.top-4.right-4');
        if (errorDiv) {
            setTimeout(() => {
                errorDiv.style.opacity = '0';
                errorDiv.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 300);
            }, 8000);
        }
    });

    // Photo preview
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (file.size > maxSize) {
                alert('Photo must be less than 2MB');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                document.getElementById('photoPreviewContainer').classList.remove('hidden');
                document.getElementById('photoPlaceholder').classList.add('hidden');
                document.getElementById('photoFileName').textContent = file.name;
                document.getElementById('removePhotoFlag').value = '0';
            };
            reader.readAsDataURL(file);
        }
    }

    // Remove photo
    function removePhoto() {
        document.getElementById('photoInput').value = '';
        document.getElementById('photoPreviewContainer').classList.add('hidden');
        document.getElementById('photoPlaceholder').classList.remove('hidden');
        document.getElementById('photoFileName').textContent = '';
        document.getElementById('removePhotoFlag').value = '1';
    }
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\profile\edit.blade.php ENDPATH**/ ?>