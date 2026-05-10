<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Teacher - Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        .main-content {
            flex: 1;
            overflow-x: hidden;
            background: #f8fafc;
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .input-icon {
            color: #3b82f6;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .form-label {
            color: #3b82f6;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(59, 130, 246, 0.4);
            position: relative;
            overflow: hidden;
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
            box-shadow: 0 10px 30px -5px rgba(59, 130, 246, 0.5);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 15px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            border-color: #cbd5e1;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.3);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .section-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            height: 4px;
            background: #e2e8f0;
            z-index: 100;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            width: 0%;
            transition: width 0.3s ease;
        }

        .floating-label {
            position: absolute;
            left: 44px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
            padding: 0 4px;
        }

        .form-input:not(:placeholder-shown) + .floating-label,
        .form-input:focus + .floating-label {
            top: 0;
            font-size: 12px;
            color: #3b82f6;
            font-weight: 600;
        }

        .password-strength {
            height: 4px;
            border-radius: 2px;
            margin-top: 8px;
            transition: all 0.3s ease;
            background: #e2e8f0;
        }

        .password-strength.weak { background: #ef4444; width: 33%; }
        .password-strength.medium { background: #f59e0b; width: 66%; }
        .password-strength.strong { background: #10b981; width: 100%; }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        .error-shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .success-toast {
            position: fixed;
            top: 24px;
            right: 24px;
            background: white;
            border-left: 4px solid #10b981;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1000;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .success-toast.show {
            transform: translateX(0);
        }

        .tooltip {
            position: relative;
        }

        .tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(-8px);
            padding: 8px 12px;
            background: #1e293b;
            color: white;
            font-size: 12px;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .tooltip:hover::after {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(-4px);
        }

        .input-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .input-hint.valid {
            color: #10b981;
        }

        .char-counter {
            position: absolute;
            right: 12px;
            bottom: 12px;
            font-size: 11px;
            color: #94a3b8;
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .glass-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 24px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .glass-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(60px);
        }

        .glass-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            filter: blur(40px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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

    <!-- Progress Bar -->
    <div class="progress-bar">
        <div class="progress-fill" id="progressBar"></div>
    </div>

    <div class="dashboard-container">
        <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-wrapper">
            <div class="main-content">
               <div class="max-w-6xl mx-auto pb-32">
                    
                    <!-- Enhanced Header -->
                    <div class="glass-header animate-fade-in">
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-user-plus text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold">Create Teacher Account</h1>
                                    <p class="text-blue-100 mt-1">Set up credentials for new faculty member</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-blue-100 mt-6">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-shield-alt"></i>
                                    Secure account creation
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-clock"></i>
                                    Takes less than 2 minutes
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if(session('error')): ?>
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-xl mb-6 animate-fade-in error-shake flex items-start gap-3">
                            <i class="fas fa-exclamation-circle mt-1"></i>
                            <div>
                                <p class="font-semibold">Error</p>
                                <p class="text-sm"><?php echo e(session('error')); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    

                    <form action="<?php echo e(route('admin.teachers.store')); ?>" method="POST" class="space-y-6" id="teacherForm">
                        <?php echo csrf_field(); ?>


<?php if($errors->any() || session('error')): ?>
    <div id="errorAlert" class="transition-all duration-500"
         style="position: relative; background: #fee2e2; border: 2px solid #ef4444; color: #991b1b; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <strong><?php echo e($errors->any() ? 'VALIDATION ERRORS:' : 'ERROR:'); ?></strong>
        <?php if($errors->any()): ?>
            <ul style="margin-top: 10px; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php elseif(session('error')): ?>
            <span><?php echo e(session('error')); ?></span>
        <?php endif; ?>
        <span id="countdown" style="position:absolute; top:8px; right:12px; font-weight:600;">3</span>
    </div>

    <script>
        (function() {
            const alertBox = document.getElementById('errorAlert');
            const countdownEl = document.getElementById('countdown');
            let timeLeft = 3;

            const countdownInterval = setInterval(() => {
                timeLeft--;
                countdownEl.textContent = timeLeft;
                if(timeLeft <= 0) {
                    clearInterval(countdownInterval);
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 500); // fade out smoothly
                }
            }, 1000);
        })();
    </script>
<?php endif; ?>
                        
                        <!-- Personal Info Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-1">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Personal Information</h3>
                                    <p class="section-subtitle">Basic details about the teacher</p>
                                </div>
                            </div>

                           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="input-group">
                                    <label class="form-label">First Name <span class="text-red-500">*</span></label>
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="first_name" required 
                                           value="<?php echo e(old('first_name')); ?>"
                                           class="form-input" 
                                           placeholder="e.g. Maria"
                                           autocomplete="off">
                                    <?php $__errorArgs = ['first_name'];
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

                                <div class="input-group">
                                    <label class="form-label">Last Name <span class="text-red-500">*</span></label>
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="last_name" required 
                                           value="<?php echo e(old('last_name')); ?>"
                                           class="form-input" 
                                           placeholder="e.g. Santos"
                                           autocomplete="off">
                                    <?php $__errorArgs = ['last_name'];
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

                                <div class="input-group md:col-span-2">
                                    <label class="form-label">Email Address <span class="text-red-500">*</span></label>
                                    <i class="fas fa-envelope input-icon"></i>
                                    <input type="email" name="email" required 
                                           value="<?php echo e(old('email')); ?>"
                                           class="form-input" 
                                           placeholder="teacher@tugaweelem.edu"
                                           id="emailInput">
                                    <p class="input-hint">
                                        <i class="fas fa-info-circle"></i>
                                        This will be used for password recovery
                                    </p>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-2"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="input-group md:col-span-2">
                                    <label class="form-label">Mobile Number <span class="text-red-500">*</span></label>
                                    <i class="fas fa-mobile-alt input-icon"></i>
                                    <input type="tel" name="mobile_number" required 
                                           value="<?php echo e(old('mobile_number')); ?>"
                                           class="form-input" 
                                           placeholder="09XX XXX XXXX"
                                           id="mobileInput"
                                           maxlength="13">
                                    <p class="input-hint" id="mobileHint">
                                        <i class="fas fa-phone"></i>
                                        Format: 09XXXXXXXXX (11 digits)
                                    </p>
                                    <?php $__errorArgs = ['mobile_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-2"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Account Credentials Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-2 bg-gradient-to-br from-blue-50/50 to-indigo-50/50 border-blue-200/50">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Account Credentials</h3>
                                    <p class="section-subtitle">Login information for the teacher</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="input-group">
                                    <label class="form-label">Username <span class="text-red-500">*</span></label>
                                    <i class="fas fa-id-badge input-icon"></i>
                                    <input type="text" name="username" required 
                                           value="<?php echo e(old('username')); ?>"
                                           class="form-input" 
                                           placeholder="e.g. msantos"
                                           id="usernameInput"
                                           autocomplete="off">
                                    <p class="input-hint">
                                        <i class="fas fa-lightbulb"></i>
                                        Suggested: <span class="text-blue-600 cursor-pointer hover:underline" onclick="generateUsername()">Generate from name</span>
                                    </p>
                                    <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-2"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="input-group">
                                        <label class="form-label">Password <span class="text-red-500">*</span></label>
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" name="password" required 
                                               minlength="8"
                                               class="form-input" 
                                               placeholder="••••••••"
                                               id="passwordInput">
                                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" onclick="togglePassword('passwordInput')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="password-strength" id="passwordStrength"></div>
                                        <p class="input-hint" id="passwordHint">
                                            <i class="fas fa-shield-alt"></i>
                                            Minimum 8 characters with letters and numbers
                                        </p>
                                    </div>

                                    <div class="input-group">
                                        <label class="form-label">Confirm Password <span class="text-red-500">*</span></label>
                                        <i class="fas fa-lock input-icon"></i>
                                        <input type="password" name="password_confirmation" required 
                                               minlength="8"
                                               class="form-input" 
                                               placeholder="••••••••"
                                               id="confirmPasswordInput">
                                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" onclick="togglePassword('confirmPasswordInput')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <p class="input-hint" id="matchHint">
                                            <i class="fas fa-check-circle"></i>
                                            Passwords must match
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Tips -->
                        <div class="glass-card p-6 rounded-2xl animate-fade-in stagger-3 bg-amber-50/50 border-amber-200/50">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-lightbulb text-amber-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-1">Next Steps</h4>
                                    <p class="text-sm text-amber-700 leading-relaxed">
                                        After creating this account, the teacher will receive their credentials and be prompted to complete their full profile (address, education, government IDs, etc.) upon first login.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="fixed bottom-0 left-0 right-0 lg:left-72 bg-white/80 backdrop-blur-xl border-t border-slate-200/80 p-4 shadow-2xl z-30">
                           <div class="max-w-6xl mx-auto flex items-center justify-between">
                                <div class="hidden md:flex items-center gap-3 text-sm text-slate-500">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    All fields are encrypted and secure
                                </div>
                                <div class="flex gap-3 ml-auto">
                                    <a href="<?php echo e(route('admin.teachers.index')); ?>" class="btn-secondary">
                                        <i class="fas fa-times mr-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn-primary" id="submitBtn">
                                        <i class="fas fa-user-plus mr-2"></i>
                                        Create Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast (hidden by default) -->
    <div class="success-toast" id="successToast">
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-emerald-600"></i>
        </div>
        <div>
            <p class="font-semibold text-slate-900">Account Created!</p>
            <p class="text-sm text-slate-500">Credentials ready to share</p>
        </div>
    </div>

    <script>
        // Progress bar
        const form = document.getElementById('teacherForm');
        const progressBar = document.getElementById('progressBar');
        const inputs = form.querySelectorAll('input[required]');
        
        function updateProgress() {
            const filled = Array.from(inputs).filter(input => input.value.trim() !== '').length;
            const percent = (filled / inputs.length) * 100;
            progressBar.style.width = percent + '%';
        }
        
        inputs.forEach(input => {
            input.addEventListener('input', updateProgress);
        });

        // Generate username from name
        function generateUsername() {
            const firstName = document.querySelector('input[name="first_name"]').value;
            const lastName = document.querySelector('input[name="last_name"]').value;
            
            if (firstName && lastName) {
                const username = (firstName[0] + lastName).toLowerCase().replace(/\s/g, '');
                document.getElementById('usernameInput').value = username;
                updateProgress();
            }
        }

        // Password strength indicator
        const passwordInput = document.getElementById('passwordInput');
        const strengthBar = document.getElementById('passwordStrength');
        
        passwordInput.addEventListener('input', function() {
            const val = this.value;
            let strength = 0;
            
            if (val.length >= 8) strength++;
            if (val.match(/[a-z]/) && val.match(/[A-Z]/)) strength++;
            if (val.match(/[0-9]/)) strength++;
            if (val.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            if (strength <= 1) strengthBar.classList.add('weak');
            else if (strength === 2) strengthBar.classList.add('medium');
            else strengthBar.classList.add('strong');
        });

        // Password match indicator
        const confirmInput = document.getElementById('confirmPasswordInput');
        const matchHint = document.getElementById('matchHint');
        
        confirmInput.addEventListener('input', function() {
            if (this.value === passwordInput.value && this.value !== '') {
                matchHint.classList.add('valid');
                matchHint.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
            } else {
                matchHint.classList.remove('valid');
                matchHint.innerHTML = '<i class="fas fa-check-circle"></i> Passwords must match';
            }
        });

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            
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
        const mobileInput = document.getElementById('mobileInput');
        mobileInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);
            
            // Format as user types
            if (value.length >= 4) {
                value = value.slice(0, 4) + ' ' + value.slice(4);
            }
            if (value.length >= 8) {
                value = value.slice(0, 8) + ' ' + value.slice(8);
            }
            
            e.target.value = value;
            
            // Validate
            const hint = document.getElementById('mobileHint');
            const clean = e.target.value.replace(/\s/g, '');
            if (clean.length === 11 && clean.startsWith('09')) {
                hint.classList.add('valid');
                hint.innerHTML = '<i class="fas fa-check-circle"></i> Valid mobile number';
            } else {
                hint.classList.remove('valid');
                hint.innerHTML = '<i class="fas fa-phone"></i> Format: 09XXXXXXXXX (11 digits)';
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            btn.disabled = true;
        });

        // Initialize progress
        updateProgress();
    </script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\teachers\create.blade.php ENDPATH**/ ?>