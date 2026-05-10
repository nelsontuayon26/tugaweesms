<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Settings | Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        
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
            overflow: hidden;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 32px;
            background: #f8fafc;
        }

        .main-content::-webkit-scrollbar { width: 8px; }
        .main-content::-webkit-scrollbar-track { background: transparent; }
        .main-content::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
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

        /* Settings Header */
        .settings-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 40px;
            border-radius: 24px;
            margin-bottom: 32px;
            position: relative;
            overflow: hidden;
        }

        .settings-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(99, 102, 241, 0.2);
            border-radius: 50%;
            filter: blur(60px);
        }

        /* Tab Navigation */
        .settings-nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 16px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
            background: transparent;
            text-align: left;
            width: 100%;
        }

        .nav-item:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4);
        }

        .nav-item i {
            width: 24px;
            text-align: center;
            font-size: 16px;
        }

        /* Settings Content */
        .settings-content {
            display: none;
        }

        .settings-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Form Elements */
        .form-group { margin-bottom: 24px; }

        .form-label {
            display: block;
            font-weight: 600;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-label span { color: #ef4444; }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            transition: all 0.2s ease;
            background: white;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder { color: #94a3b8; }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .form-select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #1e293b;
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        /* Toggle Switch */
        .toggle-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            margin-bottom: 12px;
            transition: all 0.2s ease;
        }

        .toggle-wrapper:hover { background: #f1f5f9; }

        .toggle-info h4 {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .toggle-info p {
            font-size: 13px;
            color: #64748b;
        }

        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            background: #cbd5e1;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .toggle-switch.active {
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .toggle-switch.active::after { transform: translateX(24px); }

        /* File Upload */
        .file-upload {
            border: 2px dashed #cbd5e1;
            border-radius: 16px;
            padding: 32px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #f8fafc;
        }

        .file-upload:hover {
            border-color: #6366f1;
            background: #eef2ff;
        }

        .file-upload i {
            font-size: 32px;
            color: #6366f1;
            margin-bottom: 12px;
        }

        .file-upload p {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }

        .file-upload span {
            font-size: 13px;
            color: #64748b;
        }

        /* Color Picker */
        .color-picker-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .color-picker {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            overflow: hidden;
        }

        .color-picker input {
            width: 150%;
            height: 150%;
            transform: translate(-25%, -25%);
            cursor: pointer;
            border: none;
            padding: 0;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(99, 102, 241, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(99, 102, 241, 0.5);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            border-color: #cbd5e1;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 12px 28px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(239, 68, 68, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(239, 68, 68, 0.5);
        }

        /* Section Cards */
        .section-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i { color: #6366f1; }

        /* Danger Zone */
        .danger-zone {
            border: 1px solid #fecaca;
            background: #fef2f2;
            border-radius: 20px;
            padding: 24px;
        }

        .danger-zone-title {
            color: #991b1b;
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .danger-zone p {
            color: #7f1d1d;
            font-size: 14px;
            margin-bottom: 16px;
        }

        /* Toast Notification */
        .toast {
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
            z-index: 100;
            transform: translateX(400px);
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .toast.show { transform: translateX(0); }

        .toast.error { border-left-color: #ef4444; }

        /* Animations */
        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }

        /* Activity Log */
        .log-item {
            padding: 12px 16px;
            border-radius: 12px;
            transition: background 0.2s ease;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .log-item:hover { background: #f8fafc; }

        .log-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Health Metrics */
        .health-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
        }

        .health-value {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .health-label {
            font-size: 13px;
            color: #64748b;
            margin-top: 4px;
        }

        .progress-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }

        .progress-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .settings-layout { flex-direction: column; }
            .settings-sidebar { width: 100%; margin-bottom: 24px; }
            .settings-nav {
                flex-direction: row;
                overflow-x: auto;
                padding: 12px;
            }
            .nav-item { white-space: nowrap; }
        }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-slate-800" x-data="settingsApp()" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Toast Notification -->
    <?php if(session('success')): ?>
    <div id="successToast" class="toast show">
        <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-emerald-600"></i>
        </div>
        <div>
            <p class="font-semibold text-slate-900">Success!</p>
            <p class="text-sm text-slate-500"><?php echo e(session('success')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div id="errorToast" class="toast error show">
        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation text-red-600"></i>
        </div>
        <div>
            <p class="font-semibold text-slate-900">Error!</p>
            <p class="text-sm text-slate-500"><?php echo e(session('error')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <div class="dashboard-container">
        <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="main-wrapper">
            <div class="main-content">
                <div class="max-w-7xl mx-auto pb-24">

                    <!-- Header -->
                    <div class="settings-header animate-fade-in">
                        <div class="relative z-10">
                            <h1 class="text-3xl font-bold mb-2">System Settings</h1>
                            <p class="text-slate-300">Manage your school management system preferences and configurations</p>
                        </div>
                    </div>

                    <!-- Settings Layout -->
                    <div class="flex flex-col lg:flex-row gap-6 animate-fade-in stagger-1">
                        
                        <!-- Settings Navigation Sidebar -->
                        <div class="lg:w-72 flex-shrink-0">
                            <div class="glass-card settings-sidebar">
                                <div class="settings-nav">
                                    <button class="nav-item active" data-tab="general" onclick="switchTab('general', this)">
                                        <i class="fas fa-cog"></i>
                                        General Settings
                                    </button>
                                    <button class="nav-item" data-tab="school" onclick="switchTab('school', this)">
                                        <i class="fas fa-school"></i>
                                        School Info
                                    </button>
                                    <button class="nav-item" data-tab="academic" onclick="switchTab('academic', this)">
                                        <i class="fas fa-graduation-cap"></i>
                                        Academic Year
                                    </button>
                                    <button class="nav-item" data-tab="notifications" onclick="switchTab('notifications', this)">
                                        <i class="fas fa-bell"></i>
                                        Notifications
                                    </button>
                                    <button class="nav-item" data-tab="security" onclick="switchTab('security', this)">
                                        <i class="fas fa-shield-alt"></i>
                                        Security
                                    </button>
                                    <button class="nav-item" data-tab="appearance" onclick="switchTab('appearance', this)">
                                        <i class="fas fa-paint-brush"></i>
                                        Appearance
                                    </button>
                                    <button class="nav-item" data-tab="logs" onclick="switchTab('logs', this)">
                                        <i class="fas fa-clipboard-list"></i>
                                        Activity Logs
                                    </button>
                                    <button class="nav-item" data-tab="backup" onclick="switchTab('backup', this)">
                                        <i class="fas fa-database"></i>
                                        Backup & Data
                                    </button>
                                    <button class="nav-item" data-tab="advanced" onclick="switchTab('advanced', this)">
                                        <i class="fas fa-sliders-h"></i>
                                        Advanced
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Settings Content Area -->
                        <div class="flex-1 min-w-0">
                            
                            <!-- GENERAL SETTINGS -->
                            <div id="general" class="settings-content active">
                                <form class="settings-ajax-form" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-info-circle"></i>
                                            General Information
                                        </h3>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">System Name <span>*</span></label>
                                                <input type="text" name="system_name" class="form-input" 
                                                    value="<?php echo e($settings['system_name'] ?? 'Tugawe Elementary School'); ?>" 
                                                    placeholder="Enter system name">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Timezone</label>
                                                <select name="timezone" class="form-select">
                                                    <option value="Asia/Manila" <?php echo e(($settings['timezone'] ?? '') == 'Asia/Manila' ? 'selected' : ''); ?>>Asia/Manila (GMT+8)</option>
                                                    <option value="Asia/Tokyo" <?php echo e(($settings['timezone'] ?? '') == 'Asia/Tokyo' ? 'selected' : ''); ?>>Asia/Tokyo (GMT+9)</option>
                                                    <option value="Asia/Singapore" <?php echo e(($settings['timezone'] ?? '') == 'Asia/Singapore' ? 'selected' : ''); ?>>Asia/Singapore (GMT+8)</option>
                                                    <option value="UTC" <?php echo e(($settings['timezone'] ?? '') == 'UTC' ? 'selected' : ''); ?>>UTC</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Date Format</label>
                                                <select name="date_format" class="form-select">
                                                    <option value="F d, Y" <?php echo e(($settings['date_format'] ?? '') == 'F d, Y' ? 'selected' : ''); ?>>January 01, 2024</option>
                                                    <option value="Y-m-d" <?php echo e(($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : ''); ?>>2024-01-01</option>
                                                    <option value="d/m/Y" <?php echo e(($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : ''); ?>>01/01/2024</option>
                                                    <option value="m/d/Y" <?php echo e(($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : ''); ?>>01/01/2024</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Default Language</label>
                                                <select name="default_language" class="form-select">
                                                    <option value="en" <?php echo e(($settings['default_language'] ?? '') == 'en' ? 'selected' : ''); ?>>English</option>
                                                    <option value="fil" <?php echo e(($settings['default_language'] ?? '') == 'fil' ? 'selected' : ''); ?>>Filipino</option>
                                                    <option value="ceb" <?php echo e(($settings['default_language'] ?? '') == 'ceb' ? 'selected' : ''); ?>>Cebuano</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-toggle-on"></i>
                                            System Features
                                        </h3>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Maintenance Mode</h4>
                                                <p>Temporarily disable access to the system for maintenance</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['maintenance_mode'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="maintenance_mode"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>User Registration</h4>
                                                <p>Allow new users to register accounts</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['user_registration'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="user_registration"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Email Verification</h4>
                                                <p>Require email verification for new accounts</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['email_verification'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="email_verification"></div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" class="btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- SCHOOL INFO -->
                            <div id="school" class="settings-content">
                                <form class="settings-ajax-form" id="schoolInfoForm" action="<?php echo e(route('admin.settings.update')); ?>" method="POST" enctype="multipart/form-data">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-school"></i>
                                            School Details
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group md:col-span-2">
                                                <label class="form-label">School Name <span>*</span></label>
                                                <input type="text" name="school_name" class="form-input" 
                                                    value="<?php echo e($settings['school_name'] ?? 'Tugawe Elementary School'); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">School Code</label>
                                                <input type="text" name="school_code" class="form-input" 
                                                    value="<?php echo e($settings['school_code'] ?? ''); ?>"
                                                    placeholder="e.g., TES-2024">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">DepEd School ID</label>
                                                <input type="text" name="deped_school_id" class="form-input" 
                                                    value="<?php echo e($settings['deped_school_id'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group md:col-span-2">
                                                <label class="form-label">School Address</label>
                                                <textarea name="school_address" class="form-textarea" rows="3"><?php echo e($settings['school_address'] ?? ''); ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Contact Email</label>
                                                <input type="email" name="school_email" class="form-input" 
                                                    value="<?php echo e($settings['school_email'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Contact Phone</label>
                                                <input type="tel" name="school_phone" class="form-input" 
                                                    value="<?php echo e($settings['school_phone'] ?? ''); ?>" maxlength="11" placeholder="09xxxxxxxxx">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">School Division</label>
                                                <input type="text" name="school_division" class="form-input" 
                                                    value="<?php echo e($settings['school_division'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">School Region</label>
                                                <input type="text" name="school_region" class="form-input" 
                                                    value="<?php echo e($settings['school_region'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">School District</label>
                                                <input type="text" name="school_district" class="form-input" 
                                                    value="<?php echo e($settings['school_district'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group md:col-span-2">
                                                <label class="form-label">School Head / Principal</label>
                                                <input type="text" name="school_head" class="form-input" 
                                                    value="<?php echo e($settings['school_head'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-image"></i>
                                            School Logo
                                        </h3>

                                        <div class="file-upload" onclick="document.getElementById('school_logo').click()">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Click to upload school logo</p>
                                            <span>Recommended: 400x400px, PNG or JPG</span>
                                            <input type="file" id="school_logo" name="school_logo" accept="image/*" style="display: none;" onchange="updateFileName(this)">
                                        </div>
                                        <p id="fileNameDisplay" class="text-sm text-slate-500 mt-2 hidden"></p>

                                        <?php if($settings['school_logo'] ?? false): ?>
                                        <div class="mt-4 flex items-center gap-4">
                                            <img src="<?php echo e(asset('storage/' . $settings['school_logo'])); ?>" alt="School Logo" class="w-24 h-24 object-contain border rounded-lg">
                                            <button type="button" class="text-red-600 hover:text-red-700 font-semibold text-sm" onclick="removeLogo()">
                                                <i class="fas fa-trash"></i> Remove Logo
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="submit" class="btn-primary" id="schoolInfoSaveBtn">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- ACADEMIC YEAR -->
                            <div id="academic" class="settings-content">
                                <form class="settings-ajax-form" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-calendar-alt"></i>
                                            Current Academic Year
                                            <?php if($activeSchoolYear): ?>
                                                <span class="ml-2 text-xs font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">
                                                    <i class="fas fa-sync-alt"></i> Auto-synced from active school year
                                                </span>
                                            <?php endif; ?>
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Active School Year <span>*</span></label>
                                                <select name="active_school_year_id" id="activeSchoolYearSelect" class="form-select">
                                                    <option value="">-- Select School Year --</option>
                                                    <?php $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($year->id); ?>"
                                                            data-name="<?php echo e($year->name); ?>"
                                                            data-start="<?php echo e($year->start_date ? $year->start_date->format('Y-m-d') : ''); ?>"
                                                            data-end="<?php echo e($year->end_date ? $year->end_date->format('Y-m-d') : ''); ?>"
                                                            <?php echo e(($settings['active_school_year_id'] ?? '') == $year->id ? 'selected' : ''); ?>>
                                                            <?php echo e($year->name); ?> <?php echo e($year->is_active ? '✓ Active' : ''); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <input type="hidden" name="current_school_year" id="currentSchoolYearName" value="<?php echo e($settings['current_school_year'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" name="school_year_start" id="schoolYearStart" class="form-input" 
                                                    value="<?php echo e($settings['school_year_start'] ?? ''); ?>" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">End Date</label>
                                                <input type="date" name="school_year_end" id="schoolYearEnd" class="form-input" 
                                                    value="<?php echo e($settings['school_year_end'] ?? ''); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-clock"></i>
                                            Grading Periods
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Grading System</label>
                                                <select name="grading_system" class="form-select">
                                                    <option value="quarterly" <?php echo e(($settings['grading_system'] ?? '') == 'quarterly' ? 'selected' : ''); ?>>Quarterly (4 Quarters)</option>
                                                    <option value="semestral" <?php echo e(($settings['grading_system'] ?? '') == 'semestral' ? 'selected' : ''); ?>>Semestral (2 Semesters)</option>
                                                    <option value="trimestral" <?php echo e(($settings['grading_system'] ?? '') == 'trimestral' ? 'selected' : ''); ?>>Trimestral (3 Terms)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Passing Grade (%)</label>
                                                <input type="number" name="passing_grade" class="form-input" 
                                                    value="<?php echo e($settings['passing_grade'] ?? '75'); ?>"
                                                    min="0" max="100">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-door-open"></i>
                                            Enrollment Configuration
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Enrollment Start Date</label>
                                                <input type="date" name="enrollment_start_date" class="form-input" 
                                                    value="<?php echo e($settings['enrollment_start_date'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Enrollment End Date</label>
                                                <input type="date" name="enrollment_end_date" class="form-input" 
                                                    value="<?php echo e($settings['enrollment_end_date'] ?? ''); ?>">
                                            </div>
                                        </div>

                                        <div class="toggle-wrapper mt-4">
                                            <div class="toggle-info">
                                                <h4>Allow Late Enrollment</h4>
                                                <p>Accept enrollment applications after the deadline</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['allow_late_enrollment'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="allow_late_enrollment"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Enrollment Enabled</h4>
                                                <p>Allow new enrollment applications</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['enrollment_enabled'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="enrollment_enabled"></div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- NOTIFICATIONS -->
                            <div id="notifications" class="settings-content">
                                <form class="settings-ajax-form" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-envelope"></i>
                                            Email Notifications
                                        </h3>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>New Student Enrollment</h4>
                                                <p>Send email when new student is enrolled</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['notify_new_student'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="notify_new_student"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Attendance Alerts</h4>
                                                <p>Notify parents when student is absent</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['notify_attendance'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="notify_attendance"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Grade Updates</h4>
                                                <p>Send notification when grades are published</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['notify_grades'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="notify_grades"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>System Announcements</h4>
                                                <p>Send system-wide announcements to all users</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['notify_announcements'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="notify_announcements"></div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-mobile-alt"></i>
                                            SMS Notifications
                                        </h3>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Enable SMS Notifications</h4>
                                                <p>Send SMS alerts to parents and teachers</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['sms_enabled'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="sms_enabled"></div>
                                        </div>

                                        <div class="form-group mt-4">
                                            <label class="form-label">SMS Provider</label>
                                            <select name="sms_provider" class="form-select">
                                                <option value="twilio" <?php echo e(($settings['sms_provider'] ?? '') == 'twilio' ? 'selected' : ''); ?>>Twilio</option>
                                                <option value="vonage" <?php echo e(($settings['sms_provider'] ?? '') == 'vonage' ? 'selected' : ''); ?>>Vonage (Nexmo)</option>
                                                <option value="plivo" <?php echo e(($settings['sms_provider'] ?? '') == 'plivo' ? 'selected' : ''); ?>>Plivo</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>

                                <!-- Email/SMTP Configuration -->
                                <form action="<?php echo e(route('admin.settings.email')); ?>" method="POST" class="mt-6">
                                    <?php echo csrf_field(); ?>
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-server"></i>
                                            SMTP / Email Configuration
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Mail Driver</label>
                                                <select name="mail_driver" class="form-select">
                                                    <option value="smtp" <?php echo e(($settings['mail_driver'] ?? '') == 'smtp' ? 'selected' : ''); ?>>SMTP</option>
                                                    <option value="sendmail" <?php echo e(($settings['mail_driver'] ?? '') == 'sendmail' ? 'selected' : ''); ?>>Sendmail</option>
                                                    <option value="mailgun" <?php echo e(($settings['mail_driver'] ?? '') == 'mailgun' ? 'selected' : ''); ?>>Mailgun</option>
                                                    <option value="ses" <?php echo e(($settings['mail_driver'] ?? '') == 'ses' ? 'selected' : ''); ?>>Amazon SES</option>
                                                    <option value="log" <?php echo e(($settings['mail_driver'] ?? '') == 'log' ? 'selected' : ''); ?>>Log (Testing)</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Mail Host</label>
                                                <input type="text" name="mail_host" class="form-input" 
                                                    value="<?php echo e($settings['mail_host'] ?? ''); ?>"
                                                    placeholder="e.g., smtp.gmail.com">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Mail Port</label>
                                                <input type="number" name="mail_port" class="form-input" 
                                                    value="<?php echo e($settings['mail_port'] ?? '587'); ?>"
                                                    placeholder="e.g., 587">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Encryption</label>
                                                <select name="mail_encryption" class="form-select">
                                                    <option value="tls" <?php echo e(($settings['mail_encryption'] ?? '') == 'tls' ? 'selected' : ''); ?>>TLS</option>
                                                    <option value="ssl" <?php echo e(($settings['mail_encryption'] ?? '') == 'ssl' ? 'selected' : ''); ?>>SSL</option>
                                                    <option value="" <?php echo e(($settings['mail_encryption'] ?? '') == '' ? 'selected' : ''); ?>>None</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Username</label>
                                                <input type="text" name="mail_username" class="form-input" 
                                                    value="<?php echo e($settings['mail_username'] ?? ''); ?>">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Password</label>
                                                <input type="password" name="mail_password" class="form-input" 
                                                    value="<?php echo e($settings['mail_password'] ?? ''); ?>"
                                                    placeholder="Leave blank to keep current">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">From Address</label>
                                                <input type="email" name="mail_from_address" class="form-input" 
                                                    value="<?php echo e($settings['mail_from_address'] ?? ''); ?>"
                                                    placeholder="noreply@school.edu.ph">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">From Name</label>
                                                <input type="text" name="mail_from_name" class="form-input" 
                                                    value="<?php echo e($settings['mail_from_name'] ?? 'Tugawe Elementary School'); ?>">
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3 mt-4">
                                            <button type="button" class="btn-secondary" onclick="testEmailConnection()">
                                                <i class="fas fa-paper-plane"></i> Test Connection
                                            </button>
                                            <button type="submit" class="btn-primary">
                                                <i class="fas fa-save"></i> Save Email Settings
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- SECURITY -->
                            <div id="security" class="settings-content">
                                <form class="settings-ajax-form" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-lock"></i>
                                            Password Policy
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Minimum Password Length</label>
                                                <input type="number" name="min_password_length" class="form-input" 
                                                    value="<?php echo e($settings['min_password_length'] ?? '8'); ?>"
                                                    min="6" max="32">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Password Expiry (days)</label>
                                                <input type="number" name="password_expiry" class="form-input" 
                                                    value="<?php echo e($settings['password_expiry'] ?? '90'); ?>"
                                                    min="0" placeholder="0 = never">
                                            </div>
                                        </div>

                                        <div class="toggle-wrapper mt-4">
                                            <div class="toggle-info">
                                                <h4>Require Strong Passwords</h4>
                                                <p>Require uppercase, lowercase, numbers, and symbols</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['strong_passwords'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="strong_passwords"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Two-Factor Authentication</h4>
                                                <p>Require 2FA for admin accounts</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['require_2fa'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="require_2fa"></div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-user-shield"></i>
                                            Session & Login
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Session Timeout (minutes)</label>
                                                <input type="number" name="session_timeout" class="form-input" 
                                                    value="<?php echo e($settings['session_timeout'] ?? '30'); ?>"
                                                    min="5" max="480">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Max Login Attempts</label>
                                                <input type="number" name="max_login_attempts" class="form-input" 
                                                    value="<?php echo e($settings['max_login_attempts'] ?? '5'); ?>"
                                                    min="3" max="10">
                                            </div>
                                        </div>

                                        <div class="toggle-wrapper mt-4">
                                            <div class="toggle-info">
                                                <h4>Login Notifications</h4>
                                                <p>Send email alert on new device login</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['login_notifications'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="login_notifications"></div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- APPEARANCE -->
                            <div id="appearance" class="settings-content">
                                <form class="settings-ajax-form" action="<?php echo e(route('admin.settings.update')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    
                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-palette"></i>
                                            Theme Colors
                                        </h3>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div class="form-group">
                                                <label class="form-label">Primary Color</label>
                                                <div class="color-picker-wrapper">
                                                    <div class="color-picker">
                                                        <input type="color" name="primary_color" 
                                                            value="<?php echo e($settings['primary_color'] ?? '#6366f1'); ?>">
                                                    </div>
                                                    <input type="text" class="form-input" style="width: 120px;"
                                                        value="<?php echo e($settings['primary_color'] ?? '#6366f1'); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Secondary Color</label>
                                                <div class="color-picker-wrapper">
                                                    <div class="color-picker">
                                                        <input type="color" name="secondary_color" 
                                                            value="<?php echo e($settings['secondary_color'] ?? '#10b981'); ?>">
                                                    </div>
                                                    <input type="text" class="form-input" style="width: 120px;"
                                                        value="<?php echo e($settings['secondary_color'] ?? '#10b981'); ?>" readonly>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">Accent Color</label>
                                                <div class="color-picker-wrapper">
                                                    <div class="color-picker">
                                                        <input type="color" name="accent_color" 
                                                            value="<?php echo e($settings['accent_color'] ?? '#f59e0b'); ?>">
                                                    </div>
                                                    <input type="text" class="form-input" style="width: 120px;"
                                                        value="<?php echo e($settings['accent_color'] ?? '#f59e0b'); ?>" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="section-card">
                                        <h3 class="section-title">
                                            <i class="fas fa-desktop"></i>
                                            Layout Options
                                        </h3>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Compact Mode</h4>
                                                <p>Use compact spacing for dense information display</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['compact_mode'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="compact_mode"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Dark Mode</h4>
                                                <p>Enable dark theme for the admin panel</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['dark_mode'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="dark_mode"></div>
                                        </div>

                                        <div class="toggle-wrapper">
                                            <div class="toggle-info">
                                                <h4>Animations</h4>
                                                <p>Enable page transitions and hover effects</p>
                                            </div>
                                            <div class="toggle-switch <?php echo e(($settings['animations'] ?? true) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="animations"></div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="submit" class="btn-primary">
                                            <i class="fas fa-save"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- ACTIVITY LOGS -->
                            <div id="logs" class="settings-content">
                                <!-- Stats Cards -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                                    <div class="health-card">
                                        <div class="health-value text-indigo-600"><?php echo e($activityStats['total'] ?? 0); ?></div>
                                        <div class="health-label">Total (24h)</div>
                                    </div>
                                    <div class="health-card">
                                        <div class="health-value text-emerald-600"><?php echo e($activityStats['created'] ?? 0); ?></div>
                                        <div class="health-label">Created</div>
                                    </div>
                                    <div class="health-card">
                                        <div class="health-value text-blue-600"><?php echo e($activityStats['updated'] ?? 0); ?></div>
                                        <div class="health-label">Updated</div>
                                    </div>
                                    <div class="health-card">
                                        <div class="health-value text-rose-600"><?php echo e($activityStats['deleted'] ?? 0); ?></div>
                                        <div class="health-label">Deleted</div>
                                    </div>
                                </div>

                                <!-- Filters -->
                                <div class="section-card">
                                    <div class="flex flex-col md:flex-row gap-3">
                                        <input type="text" 
                                               x-model="logSearch" 
                                               @input.debounce.300ms="fetchLogs()"
                                               class="form-input flex-1" 
                                               placeholder="Search logs...">
                                        <select x-model="logAction" @change="fetchLogs()" class="form-select" style="width: auto; min-width: 150px;">
                                            <option value="all">All Actions</option>
                                            <option value="created">Created</option>
                                            <option value="updated">Updated</option>
                                            <option value="deleted">Deleted</option>
                                            <option value="login">Login</option>
                                            <option value="logout">Logout</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                        <button type="button" class="btn-primary" @click="fetchLogs()">
                                            <i class="fas fa-sync"></i> Refresh
                                        </button>
                                    </div>
                                </div>

                                <!-- Logs List -->
                                <div class="section-card">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="section-title mb-0">
                                            <i class="fas fa-history"></i>
                                            Recent Activity
                                        </h3>
                                        <div class="flex gap-2">
                                            <button type="button" class="btn-secondary text-sm py-2 px-4" @click="clearOldLogs()">
                                                <i class="fas fa-broom"></i> Clear Old
                                            </button>
                                            <a href="<?php echo e(route('admin.settings.logs.download')); ?>" class="btn-secondary text-sm py-2 px-4">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>

                                    <div x-show="logsLoading" class="py-6 space-y-4">
                                        <?php if (isset($component)) { $__componentOriginal9e393e2811beadc8ba24897767594071 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9e393e2811beadc8ba24897767594071 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton-loader','data' => ['type' => 'list-item','count' => '5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton-loader'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'list-item','count' => '5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9e393e2811beadc8ba24897767594071)): ?>
<?php $attributes = $__attributesOriginal9e393e2811beadc8ba24897767594071; ?>
<?php unset($__attributesOriginal9e393e2811beadc8ba24897767594071); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9e393e2811beadc8ba24897767594071)): ?>
<?php $component = $__componentOriginal9e393e2811beadc8ba24897767594071; ?>
<?php unset($__componentOriginal9e393e2811beadc8ba24897767594071); ?>
<?php endif; ?>
                                    </div>

                                    <div x-show="!logsLoading && logs.length === 0" class="text-center py-8 text-slate-500">
                                        <i class="fas fa-inbox text-4xl mb-3"></i>
                                        <p>No activity logs found</p>
                                    </div>

                                    <template x-for="log in logs" :key="log.id">
                                        <div class="log-item border-b border-slate-100 last:border-0">
                                            <div class="log-icon" 
                                                 :class="{
                                                     'bg-emerald-100 text-emerald-600': log.action === 'created' || log.action === 'approved',
                                                     'bg-blue-100 text-blue-600': log.action === 'updated',
                                                     'bg-rose-100 text-rose-600': log.action === 'deleted' || log.action === 'rejected',
                                                     'bg-indigo-100 text-indigo-600': log.action === 'login',
                                                     'bg-slate-100 text-slate-600': log.action === 'logout'
                                                 }">
                                                <i class="fas" :class="log.action_icon"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-900" x-text="log.description"></p>
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs text-slate-500">
                                                        <i class="fas fa-user mr-1"></i>
                                                        <span x-text="log.user_name"></span>
                                                    </span>
                                                    <span class="text-xs text-slate-400" x-text="log.created_at"></span>
                                                    <span class="text-xs text-slate-400" x-show="log.ip_address" x-text="log.ip_address"></span>
                                                </div>
                                            </div>
                                            <span class="px-2 py-1 rounded-full text-xs font-medium"
                                                  :class="{
                                                      'bg-emerald-100 text-emerald-700': log.action === 'created' || log.action === 'approved',
                                                      'bg-blue-100 text-blue-700': log.action === 'updated',
                                                      'bg-rose-100 text-rose-700': log.action === 'deleted' || log.action === 'rejected',
                                                      'bg-indigo-100 text-indigo-700': log.action === 'login',
                                                      'bg-slate-100 text-slate-600': log.action === 'logout'
                                                  }"
                                                  x-text="log.action"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- BACKUP & DATA -->
                            <div id="backup" class="settings-content">
                                <div class="section-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-cloud-download-alt"></i>
                                        Database Backup
                                    </h3>

                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                            <div>
                                                <p class="font-semibold text-blue-900">Last Backup</p>
                                                <p class="text-sm text-blue-700"><?php echo e($settings['last_backup'] ?? 'Never'); ?></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                        <button type="button" class="btn-primary" @click="createBackup()" :disabled="backupLoading">
                                            <i class="fas fa-download" x-show="!backupLoading"></i>
                                            <i class="fas fa-spinner fa-spin" x-show="backupLoading"></i>
                                            <span x-text="backupLoading ? 'Creating...' : 'Download Backup Now'"></span>
                                        </button>
                                        <button type="button" class="btn-secondary" onclick="document.getElementById('scheduleBackupModal').classList.remove('hidden')">
                                            <i class="fas fa-calendar"></i> Schedule Auto-Backup
                                        </button>
                                    </div>

                                    <div class="toggle-wrapper">
                                        <div class="toggle-info">
                                            <h4>Auto-Backup</h4>
                                            <p>Automatically backup database daily</p>
                                        </div>
                                        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" style="display:inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="toggle-switch <?php echo e(($settings['auto_backup'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="auto_backup"></div>
                                        </form>
                                    </div>
                                </div>

                                <div class="section-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-file-export"></i>
                                        Data Export
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <a href="<?php echo e(route('admin.settings.export', 'students')); ?>" class="btn-secondary justify-center">
                                            <i class="fas fa-users"></i> Export Students
                                        </a>
                                        <a href="<?php echo e(route('admin.settings.export', 'teachers')); ?>" class="btn-secondary justify-center">
                                            <i class="fas fa-chalkboard-teacher"></i> Export Teachers
                                        </a>
                                        <a href="<?php echo e(route('admin.settings.export', 'grades')); ?>" class="btn-secondary justify-center">
                                            <i class="fas fa-graduation-cap"></i> Export Grades
                                        </a>
                                        <a href="<?php echo e(route('admin.settings.export', 'attendance')); ?>" class="btn-secondary justify-center">
                                            <i class="fas fa-calendar-check"></i> Export Attendance
                                        </a>
                                    </div>
                                </div>

                                <div class="danger-zone">
                                    <div class="danger-zone-title">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Danger Zone
                                    </div>
                                    <p>These actions are irreversible. Please proceed with caution.</p>
                                    <div class="flex flex-wrap gap-3">
                                        <button type="button" class="btn-danger" @click="clearCache()" :disabled="cacheLoading">
                                            <i class="fas fa-broom" x-show="!cacheLoading"></i>
                                            <i class="fas fa-spinner fa-spin" x-show="cacheLoading"></i>
                                            <span x-text="cacheLoading ? 'Clearing...' : 'Clear Cache'"></span>
                                        </button>
                                        <button type="button" class="btn-danger" @click="resetSettings()" :disabled="resetLoading">
                                            <i class="fas fa-undo" x-show="!resetLoading"></i>
                                            <i class="fas fa-spinner fa-spin" x-show="resetLoading"></i>
                                            <span x-text="resetLoading ? 'Resetting...' : 'Reset to Default'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- ADVANCED -->
                            <div id="advanced" class="settings-content">
                                
                                <!-- System Health -->
                                <div class="section-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-heartbeat"></i>
                                        System Health
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                        <!-- Disk Usage -->
                                        <div class="health-card">
                                            <div class="flex items-center justify-between mb-2">
                                                <i class="fas fa-hdd text-2xl text-indigo-600"></i>
                                                <span class="text-xs font-medium px-2 py-1 rounded-full"
                                                      :class="{
                                                          'bg-emerald-100 text-emerald-700': health.disk?.status === 'good',
                                                          'bg-amber-100 text-amber-700': health.disk?.status === 'warning',
                                                          'bg-rose-100 text-rose-700': health.disk?.status === 'critical'
                                                      }"
                                                      x-text="health.disk?.status?.toUpperCase() || 'Loading...'"></span>
                                            </div>
                                            <div class="health-value" x-text="health.disk?.percent + '%' || '--'">--</div>
                                            <div class="health-label">Disk Usage</div>
                                            <div class="progress-bar">
                                                <div class="progress-bar-fill"
                                                     :class="{
                                                         'bg-emerald-500': health.disk?.status === 'good',
                                                         'bg-amber-500': health.disk?.status === 'warning',
                                                         'bg-rose-500': health.disk?.status === 'critical'
                                                     }"
                                                     :style="'width:' + (health.disk?.percent || 0) + '%'"></div>
                                            </div>
                                            <p class="text-xs text-slate-400 mt-1" x-text="health.disk?.used + ' / ' + health.disk?.total || ''"></p>
                                        </div>

                                        <!-- Database -->
                                        <div class="health-card">
                                            <div class="flex items-center justify-between mb-2">
                                                <i class="fas fa-database text-2xl" 
                                                   :class="health.database?.connected ? 'text-emerald-600' : 'text-rose-600'"></i>
                                                <span class="text-xs font-medium px-2 py-1 rounded-full"
                                                      :class="health.database?.connected ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                                                      x-text="health.database?.connected ? 'CONNECTED' : 'DISCONNECTED'"></span>
                                            </div>
                                            <div class="health-value text-sm" x-text="health.database?.name || '--'"></div>
                                            <div class="health-label">Database</div>
                                            <p class="text-xs text-slate-400 mt-1" x-text="health.database?.version ? 'v' + health.database.version : ''"></p>
                                        </div>

                                        <!-- Queue -->
                                        <div class="health-card">
                                            <div class="flex items-center justify-between mb-2">
                                                <i class="fas fa-tasks text-2xl text-blue-600"></i>
                                            </div>
                                            <div class="health-value" x-text="(health.queue?.pending || 0)"></div>
                                            <div class="health-label">Pending Jobs</div>
                                            <p class="text-xs text-slate-400 mt-1">
                                                <span x-text="health.queue?.failed || 0"></span> failed
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                                            <i class="fab fa-php text-2xl text-indigo-600 mb-2"></i>
                                            <div class="text-sm font-semibold" x-text="health.php?.version || '<?php echo e(phpversion()); ?>'"></div>
                                            <div class="text-xs text-slate-500">PHP</div>
                                        </div>
                                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                                            <i class="fab fa-laravel text-2xl text-rose-600 mb-2"></i>
                                            <div class="text-sm font-semibold" x-text="health.laravel?.version || '<?php echo e(app()->version()); ?>'"></div>
                                            <div class="text-xs text-slate-500">Laravel</div>
                                        </div>
                                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                                            <i class="fas fa-memory text-2xl text-emerald-600 mb-2"></i>
                                            <div class="text-sm font-semibold" x-text="health.php?.max_upload || '<?php echo e(ini_get('upload_max_filesize')); ?>'"></div>
                                            <div class="text-xs text-slate-500">Max Upload</div>
                                        </div>
                                        <div class="bg-slate-50 rounded-xl p-4 text-center">
                                            <i class="fas fa-clock text-2xl text-amber-600 mb-2"></i>
                                            <div class="text-sm font-semibold" x-text="health.php?.max_execution || '<?php echo e(ini_get('max_execution_time')); ?>s'"></div>
                                            <div class="text-xs text-slate-500">Max Execution</div>
                                        </div>
                                    </div>

                                    <div class="flex justify-center mt-4">
                                        <button type="button" class="btn-secondary" @click="refreshHealth()" :disabled="healthLoading">
                                            <i class="fas fa-sync" :class="{'fa-spin': healthLoading}"></i>
                                            Refresh Health
                                        </button>
                                    </div>
                                </div>

                                <!-- API Settings -->
                                <div class="section-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-code"></i>
                                        API Settings
                                    </h3>

                                    <div class="form-group">
                                        <label class="form-label">API Key</label>
                                        <div class="flex gap-2">
                                            <input type="text" id="apiKeyDisplay" class="form-input font-mono" 
                                                value="<?php echo e($settings['api_key'] ?? '************************'); ?>" readonly>
                                            <button type="button" class="btn-secondary" @click="regenerateApiKey()" :disabled="apiKeyLoading">
                                                <i class="fas fa-sync" :class="{'fa-spin': apiKeyLoading}"></i>
                                                <span x-text="apiKeyLoading ? '...' : 'Regenerate'"></span>
                                            </button>
                                            <button type="button" class="btn-secondary" onclick="copyApiKey()">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>

                                    <div class="toggle-wrapper mt-4">
                                        <div class="toggle-info">
                                            <h4>API Access</h4>
                                            <p>Enable external API access</p>
                                        </div>
                                        <form action="<?php echo e(route('admin.settings.update')); ?>" method="POST" style="display:inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="toggle-switch <?php echo e(($settings['api_enabled'] ?? false) ? 'active' : ''); ?>" 
                                                 onclick="toggleSwitch(this)" data-name="api_enabled"></div>
                                        </form>
                                    </div>
                                </div>

                                <!-- System Information -->
                                <div class="section-card">
                                    <h3 class="section-title">
                                        <i class="fas fa-info-circle"></i>
                                        System Information
                                    </h3>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Laravel Version</span>
                                            <span class="font-semibold"><?php echo e(app()->version()); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">PHP Version</span>
                                            <span class="font-semibold"><?php echo e(phpversion()); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Database</span>
                                            <span class="font-semibold"><?php echo e(config('database.default')); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Environment</span>
                                            <span class="font-semibold"><?php echo e(config('app.env')); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Debug Mode</span>
                                            <span class="font-semibold"><?php echo e(config('app.debug') ? 'Enabled' : 'Disabled'); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Timezone</span>
                                            <span class="font-semibold"><?php echo e(config('app.timezone')); ?></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Log File Size</span>
                                            <span class="font-semibold" x-text="health.logs?.size || 'Checking...'"></span>
                                        </div>
                                        <div class="flex justify-between py-2 border-b border-slate-100">
                                            <span class="text-slate-500">Memory Limit</span>
                                            <span class="font-semibold"><?php echo e(ini_get('memory_limit')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Backup Modal -->
    <div id="scheduleBackupModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('scheduleBackupModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden p-6">
            <h3 class="text-lg font-bold mb-4">Schedule Auto-Backup</h3>
            <p class="text-slate-500 text-sm mb-4">Auto-backup feature will be available in a future update. You can manually create backups anytime from the Backup & Data tab.</p>
            <div class="flex justify-end gap-3">
                <button type="button" class="btn-secondary" onclick="document.getElementById('scheduleBackupModal').classList.add('hidden')">Close</button>
            </div>
        </div>
    </div>

    <!-- Reset Confirmation Modal -->
    <div id="resetModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('resetModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 bg-rose-50 border-b border-rose-100">
                <h3 class="text-lg font-bold text-rose-800"><i class="fas fa-exclamation-triangle mr-2"></i>Reset All Settings?</h3>
            </div>
            <div class="p-6">
                <p class="text-slate-600 mb-4">This will reset ALL settings to their default values. This action cannot be undone.</p>
                <form id="resetForm" action="<?php echo e(route('admin.settings.reset')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="confirm_reset" value="1">
                    <div class="flex justify-end gap-3">
                        <button type="button" class="btn-secondary" onclick="document.getElementById('resetModal').classList.add('hidden')">Cancel</button>
                        <button type="submit" class="btn-danger">Yes, Reset Everything</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // CSRF token for AJAX
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Alpine.js Data
        function settingsApp() {
            return {
                // Mobile sidebar
                mobileOpen: false,
                
                // Logs
                logs: [],
                logsLoading: false,
                logSearch: '',
                logAction: 'all',
                
                // Loading states
                backupLoading: false,
                cacheLoading: false,
                resetLoading: false,
                apiKeyLoading: false,
                healthLoading: false,
                
                // Health data
                health: <?php echo json_encode($health ?? [], 15, 512) ?>,
                
                init() {
                    // Load health on init
                    this.refreshHealth();
                    // Load logs if on logs tab
                    if (document.getElementById('logs')?.classList.contains('active')) {
                        this.fetchLogs();
                    }
                },
                
                // Fetch activity logs
                async fetchLogs() {
                    this.logsLoading = true;
                    try {
                        const params = new URLSearchParams();
                        if (this.logAction !== 'all') params.append('action', this.logAction);
                        if (this.logSearch) params.append('search', this.logSearch);
                        
                        const response = await fetch(`<?php echo e(route('admin.settings.logs')); ?>?${params.toString()}`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.logs = data.logs;
                        }
                    } catch (error) {
                        console.error('Failed to fetch logs:', error);
                    } finally {
                        this.logsLoading = false;
                    }
                },
                
                // Clear old logs
                async clearOldLogs() {
                    if (!confirm('Clear logs older than 30 days?')) return;
                    
                    try {
                        const response = await fetch('<?php echo e(route('admin.settings.logs.clear')); ?>', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ days: 30 })
                        });
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showToast(data.message, 'success');
                            this.fetchLogs();
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to clear logs', 'error');
                    }
                },
                
                // Create backup
                async createBackup() {
                    this.backupLoading = true;
                    try {
                        const response = await fetch('<?php echo e(route('admin.settings.backup')); ?>', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        
                        if (response.ok) {
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'backup_' + new Date().toISOString().slice(0,10) + '.sql';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                            this.showToast('Backup downloaded successfully!', 'success');
                        } else {
                            this.showToast('Backup failed. Check server logs.', 'error');
                        }
                    } catch (error) {
                        this.showToast('Backup failed: ' + error.message, 'error');
                    } finally {
                        this.backupLoading = false;
                    }
                },
                
                // Clear cache
                async clearCache() {
                    if (!confirm('Clear all application caches?')) return;
                    
                    this.cacheLoading = true;
                    try {
                        const response = await fetch('<?php echo e(route('admin.settings.clear-cache')); ?>', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        const data = await response.json();
                        
                        if (data.success) {
                            this.showToast(data.message, 'success');
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to clear cache', 'error');
                    } finally {
                        this.cacheLoading = false;
                    }
                },
                
                // Reset settings
                resetSettings() {
                    document.getElementById('resetModal').classList.remove('hidden');
                },
                
                // Regenerate API key
                async regenerateApiKey() {
                    if (!confirm('Regenerate API key? The old key will stop working immediately.')) return;
                    
                    this.apiKeyLoading = true;
                    try {
                        const response = await fetch('<?php echo e(route('admin.settings.regenerate-api-key')); ?>', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken }
                        });
                        const data = await response.json();
                        
                        if (data.success) {
                            document.getElementById('apiKeyDisplay').value = data.api_key;
                            this.showToast('API key regenerated successfully!', 'success');
                        } else {
                            this.showToast(data.message, 'error');
                        }
                    } catch (error) {
                        this.showToast('Failed to regenerate API key', 'error');
                    } finally {
                        this.apiKeyLoading = false;
                    }
                },
                
                // Refresh health metrics
                async refreshHealth() {
                    this.healthLoading = true;
                    try {
                        const response = await fetch('<?php echo e(route('admin.settings.health')); ?>');
                        const data = await response.json();
                        
                        if (data.success) {
                            this.health = data.health;
                        }
                    } catch (error) {
                        console.error('Failed to fetch health:', error);
                    } finally {
                        this.healthLoading = false;
                    }
                },
                
                // Show toast notification
                showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    toast.className = `toast ${type === 'error' ? 'error' : ''} show`;
                    toast.innerHTML = `
                        <div class="w-10 h-10 ${type === 'error' ? 'bg-red-100' : 'bg-emerald-100'} rounded-full flex items-center justify-center">
                            <i class="fas ${type === 'error' ? 'fa-exclamation text-red-600' : 'fa-check text-emerald-600'}"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-900">${type === 'error' ? 'Error!' : 'Success!'}</p>
                            <p class="text-sm text-slate-500">${message}</p>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 400);
                    }, 4000);
                }
            };
        }

        // Tab switching
        function switchTab(tabId, clickedNavItem = null) {
            document.querySelectorAll('.settings-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            const contentEl = document.getElementById(tabId);
            if (contentEl) contentEl.classList.add('active');
            
            const navItem = clickedNavItem || document.querySelector(`.nav-item[data-tab="${tabId}"]`);
            if (navItem) navItem.classList.add('active');
            
            localStorage.setItem('settings_active_tab', tabId);
            
            // If switching to logs tab, fetch logs
            if (tabId === 'logs' && typeof Alpine !== 'undefined') {
                const el = document.querySelector('[x-data="settingsApp()"]');
                if (el && el._x_dataStack) {
                    el._x_dataStack[0].fetchLogs();
                }
            }
        }

        // Toggle switch
        function toggleSwitch(element) {
            element.classList.toggle('active');
            updateToggleInput(element);

            // Real-time appearance updates
            applyAppearanceRealtime(element.dataset.name, element.classList.contains('active'));
        }

        function updateToggleInput(element) {
            // Remove existing hidden input if any
            const existing = element.querySelector('input[type="hidden"]');
            if (existing) existing.remove();
            // Add new hidden input
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = element.dataset.name;
            input.value = element.classList.contains('active') ? '1' : '0';
            element.appendChild(input);
        }

        function applyAppearanceRealtime(name, isActive) {
            const body = document.body;
            if (name === 'dark_mode') {
                // Use unified theme engine
                if (window.tessmsTheme) {
                    window.tessmsTheme.set(isActive ? 'dark' : 'light');
                } else {
                    body.classList.toggle('dark-mode', isActive);
                    document.documentElement.classList.toggle('dark', isActive);
                }
            }
            if (name === 'compact_mode') {
                body.classList.toggle('compact-mode', isActive);
                localStorage.setItem('app_compact_mode', isActive ? '1' : '0');
            }
            if (name === 'animations') {
                body.classList.toggle('animations-disabled', !isActive);
                localStorage.setItem('app_animations', isActive ? '1' : '0');
            }
        }

        // Real-time school year sync
        function syncSchoolYearDropdown() {
            const select = document.getElementById('activeSchoolYearSelect');
            if (!select) return;

            const selected = select.options[select.selectedIndex];
            const name = selected.getAttribute('data-name') || '';
            const start = selected.getAttribute('data-start') || '';
            const end = selected.getAttribute('data-end') || '';

            document.getElementById('currentSchoolYearName').value = name;
            document.getElementById('schoolYearStart').value = start;
            document.getElementById('schoolYearEnd').value = end;
        }

        // Initialize appearance real-time listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Color pickers real-time
            document.querySelectorAll('input[type="color"]').forEach(picker => {
                picker.addEventListener('input', function() {
                    this.parentElement.nextElementSibling.value = this.value;
                    const name = this.name;
                    const root = document.documentElement;
                    if (name === 'primary_color') {
                        root.style.setProperty('--primary-color', this.value);
                        localStorage.setItem('app_primary_color', this.value);
                    }
                    if (name === 'secondary_color') {
                        root.style.setProperty('--secondary-color', this.value);
                        localStorage.setItem('app_secondary_color', this.value);
                    }
                    if (name === 'accent_color') {
                        root.style.setProperty('--accent-color', this.value);
                        localStorage.setItem('app_accent_color', this.value);
                    }
                });
            });

            // Initialize all toggle switches with hidden inputs so FormData captures them
            document.querySelectorAll('.toggle-switch').forEach(toggle => {
                updateToggleInput(toggle);
            });

            // Initialize appearance toggles on load
            const darkToggle = document.querySelector('.toggle-switch[data-name="dark_mode"]');
            const compactToggle = document.querySelector('.toggle-switch[data-name="compact_mode"]');
            const animToggle = document.querySelector('.toggle-switch[data-name="animations"]');
            if (darkToggle) applyAppearanceRealtime('dark_mode', darkToggle.classList.contains('active'));
            if (compactToggle) applyAppearanceRealtime('compact_mode', compactToggle.classList.contains('active'));
            if (animToggle) applyAppearanceRealtime('animations', animToggle.classList.contains('active'));

            // School year dropdown real-time sync
            const sySelect = document.getElementById('activeSchoolYearSelect');
            if (sySelect) {
                sySelect.addEventListener('change', syncSchoolYearDropdown);
                syncSchoolYearDropdown(); // initial sync
            }

            // AJAX save for all settings forms
            document.querySelectorAll('.settings-ajax-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const btn = form.querySelector('button[type="submit"]');
                    if (!btn) return;
                    const originalHtml = btn.innerHTML;
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

                    try {
                        const formData = new FormData(form);
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });

                        if (response.redirected) {
                            window.location.href = response.url;
                            return;
                        }

                        const text = await response.text();
                        const isSuccess = text.includes('Settings updated successfully') || response.ok;
                        const tabName = form.closest('.settings-content')?.id || 'settings';

                        if (isSuccess) {
                            showGlobalToast(ucfirst(tabName) + ' saved successfully!', 'success');
                        } else {
                            showGlobalToast('Failed to save ' + tabName + '.', 'error');
                        }
                    } catch (err) {
                        showGlobalToast('Failed to save settings.', 'error');
                    } finally {
                        btn.disabled = false;
                        btn.innerHTML = originalHtml;
                    }
                });
            });

            // Restore active tab from localStorage
            const savedTab = localStorage.getItem('settings_active_tab');
            if (savedTab && savedTab !== 'general') {
                const navItem = document.querySelector(`.nav-item[data-tab="${savedTab}"]`);
                if (navItem) {
                    // Small delay to let Alpine/DOM settle
                    setTimeout(() => switchTab(savedTab, navItem), 50);
                }
            }
        });

        function ucfirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function showGlobalToast(message, type = 'success') {
            const el = document.querySelector('[x-data="settingsApp()"]');
            if (el && el._x_dataStack && el._x_dataStack[0] && typeof el._x_dataStack[0].showToast === 'function') {
                el._x_dataStack[0].showToast(message, type);
            } else {
                // Fallback standalone toast
                const toast = document.createElement('div');
                toast.className = `toast ${type === 'error' ? 'error' : ''} show`;
                toast.innerHTML = `
                    <div class="w-10 h-10 ${type === 'error' ? 'bg-red-100' : 'bg-emerald-100'} rounded-full flex items-center justify-center">
                        <i class="fas ${type === 'error' ? 'fa-exclamation text-red-600' : 'fa-check text-emerald-600'}"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">${type === 'error' ? 'Error!' : 'Success!'}</p>
                        <p class="text-sm text-slate-500">${message}</p>
                    </div>
                `;
                document.body.appendChild(toast);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 400);
                }, 4000);
            }
        }

        // Toast auto-hide
        setTimeout(() => {
            document.querySelectorAll('.toast').forEach(toast => {
                toast.classList.remove('show');
            });
        }, 4000);

        // Form functions
        function resetForm() {
            if(confirm('Reset all changes?')) {
                location.reload();
            }
        }

        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            if (input.files && input.files[0]) {
                display.textContent = 'Selected: ' + input.files[0].name;
                display.classList.remove('hidden');
            }
        }

        function removeLogo() {
            if(confirm('Remove school logo?')) {
                // This would need a dedicated route - for now just alert
                alert('Logo removal requires a dedicated endpoint. Please contact your developer.');
            }
        }

        function testEmailConnection() {
            alert('Email connection test will be implemented with your SMTP provider.');
        }

        function copyApiKey() {
            const input = document.getElementById('apiKeyDisplay');
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value);
            alert('API key copied to clipboard!');
        }

        // Color picker sync
        document.querySelectorAll('input[type="color"]').forEach(picker => {
            picker.addEventListener('input', function() {
                this.parentElement.nextElementSibling.value = this.value;
            });
        });
    </script>

</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\settings\index.blade.php ENDPATH**/ ?>