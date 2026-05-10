<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
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
        
        /* Enhanced Glassmorphism */
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
        
        /* Liquid Glass Effect for Tabs */
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
        
        /* Info Cards with Hover Lift */
        .info-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .info-card:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 20px 40px -5px rgba(0, 0, 0, 0.1), 0 10px 20px -5px rgba(0, 0, 0, 0.04);
            border-color: rgba(99, 102, 241, 0.2);
        }
        
        /* Icon-only Floating Action Button */
        .fab-icon {
            box-shadow: 0 10px 35px -5px rgba(79, 70, 229, 0.5), 0 4px 15px -2px rgba(79, 70, 229, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fab-icon:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 45px -5px rgba(79, 70, 229, 0.6), 0 6px 20px -2px rgba(79, 70, 229, 0.4);
        }
        
        .fab-icon:active {
            transform: scale(0.95);
        }
        
        /* Section Header with Glass Effect */
        .section-header {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(226, 232, 240, 0.8) 100%);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        
        /* Success Message with Glass Effect */
        .success-message {
            animation: slideDown 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Sidebar transition */
        .sidebar-transition {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Mobile overlay with blur */
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        /* Custom scrollbar */
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
        
        /* Tab container with glass effect */
        .tab-container {
            background: rgba(226, 232, 240, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        /* Avatar glow effect */
        .avatar-glow {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        
        /* Status indicator pulse */
        .status-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Smooth content transition */
        .tab-content {
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Tooltip for FAB */
        .fab-tooltip {
            position: absolute;
            right: 70px;
            top: 50%;
            transform: translateY(-50%) scale(0.8);
            background: rgba(15, 23, 42, 0.9);
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        
        .fab-container:hover .fab-tooltip {
            opacity: 1;
            transform: translateY(-50%) scale(1);
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }">

    
    <?php if(session('success')): ?>
    <div id="success-message" class="fixed top-4 right-4 z-50 success-message max-w-sm">
        <div class="glass-panel bg-green-50/90 border border-green-200/50 rounded-2xl p-4 shadow-2xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-green-900">Success!</p>
                <p class="text-xs text-green-700 mt-0.5 leading-relaxed"><?php echo e(session('success')); ?></p>
            </div>
            <button onclick="closeSuccessMessage()" class="text-green-400 hover:text-green-700 transition-colors p-1 hover:bg-green-100 rounded-lg">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>

    
    <div class="lg:hidden fixed top-0 left-0 right-0 z-40 glass-panel border-b border-white/50 px-4 py-3 flex items-center justify-between">
        <button @click="mobileOpen = !mobileOpen" class="p-2.5 rounded-xl hover:bg-white/60 transition-all active:scale-95">
            <i class="fas fa-bars text-slate-700 text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <h1 class="text-lg font-bold gradient-text">Teacher Profile</h1>
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
            
            
            <header class="hidden lg:block sticky top-0 z-30 glass-panel border-b border-white/60 px-6 xl:px-8 py-4 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold gradient-text tracking-tight">Teacher Profile</h1>
                        <p class="text-sm text-slate-500 mt-1 font-medium">View and manage your professional information</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-800"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></p>
                            <p class="text-xs text-slate-500 font-medium"><?php echo e($teacher->position ?? 'Teacher'); ?></p>
                        </div>
                        <?php if($user->photo): ?>
                            <img src="<?php echo e(profile_photo_url($user->photo)); ?>" alt="Profile" class="w-11 h-11 rounded-full object-cover avatar-glow ring-4 ring-white/50">
                        <?php else: ?>
                            <div class="w-11 h-11 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-lg avatar-glow ring-4 ring-white/50">
                                <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            
            <main class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar p-4 lg:p-6 xl:p-8 bg-gradient-to-br from-slate-50 via-white to-slate-100">
                
                
                <div class="flex flex-wrap gap-2 mb-8 p-2 tab-container rounded-2xl w-fit max-w-full overflow-x-auto shadow-sm">
                    <button onclick="switchTab('account')" id="tab-account" class="tab-liquid tab-active px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-user-circle text-lg"></i>
                        <span>Account</span>
                    </button>
                    <button onclick="switchTab('personal')" id="tab-personal" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-id-card text-lg"></i>
                        <span>Personal</span>
                    </button>
                    <button onclick="switchTab('contact')" id="tab-contact" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-address-book text-lg"></i>
                        <span>Contact</span>
                    </button>
                    <button onclick="switchTab('employment')" id="tab-employment" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-briefcase text-lg"></i>
                        <span>Employment</span>
                    </button>
                    <button onclick="switchTab('education')" id="tab-education" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-lg"></i>
                        <span>Education</span>
                    </button>
                    <button onclick="switchTab('emergency')" id="tab-emergency" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-ambulance text-lg"></i>
                        <span>Emergency</span>
                    </button>
                    <button onclick="switchTab('salary')" id="tab-salary" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-money-check-alt text-lg"></i>
                        <span>Salary</span>
                    </button>
                    <button onclick="switchTab('credentials')" id="tab-credentials" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-certificate text-lg"></i>
                        <span>Credentials</span>
                    </button>
                    <button onclick="switchTab('government')" id="tab-government" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-id-card-alt text-lg"></i>
                        <span>Gov't IDs</span>
                    </button>
                    <button onclick="switchTab('family')" id="tab-family" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-users text-lg"></i>
                        <span>Family</span>
                    </button>
                    <button onclick="switchTab('others')" id="tab-others" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                        <i class="fas fa-ellipsis-h text-lg"></i>
                        <span>Others</span>
                    </button>
                </div>

                
                <div id="content-account" class="tab-content">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                Account Information
                            </h2>
                        </div>

                        
                        <div class="mb-8 p-6 bg-gradient-to-br from-indigo-50 to-white rounded-2xl border border-indigo-100">
                            <div class="flex flex-col sm:flex-row items-center gap-6">
                                
                                <div class="relative">
                                    <?php if($user->photo): ?>
                                        <img src="<?php echo e(profile_photo_url($user->photo)); ?>" alt="Profile Photo" 
                                             class="w-28 h-28 rounded-2xl object-cover shadow-lg border-4 border-white">
                                    <?php else: ?>
                                        <div class="w-28 h-28 rounded-2xl gradient-bg flex items-center justify-center text-white font-bold text-2xl shadow-lg border-4 border-white">
                                            <?php echo e(substr($user->first_name, 0, 1)); ?><?php echo e(substr($user->last_name, 0, 1)); ?>

                                        </div>
                                    <?php endif; ?>
                                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center border-2 border-white shadow-md" title="Active">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>

                                
                                <div class="flex-1 text-center sm:text-left">
                                    <h3 class="text-lg font-bold text-slate-800"><?php echo e($user->first_name); ?> <?php echo e($user->last_name); ?></h3>
                                    <p class="text-sm text-slate-500 mb-3"><?php echo e($teacher->position ?? 'Teacher'); ?></p>
                                    <a href="<?php echo e(route('teacher.profile.edit')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-md shadow-indigo-500/20">
                                        <i class="fas fa-camera"></i>
                                        <span>Change Photo</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($user->first_name); ?> <?php echo e($user->middle_name); ?> <?php echo e($user->last_name); ?> <?php echo e($user->suffix); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Email Address</p>
                                <p class="text-base font-bold text-slate-800 flex items-center gap-2 break-all">
                                    <i class="fas fa-envelope text-indigo-400 flex-shrink-0"></i>
                                    <span class="truncate"><?php echo e($user->email); ?></span>
                                </p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Username</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($user->username); ?></p>
                            </div>
<?php
use Carbon\Carbon;

$dob = $teacher->date_of_birth ?? null;
if ($dob) {
    $birthDate = Carbon::parse($dob)->format('d/m/Y'); // format as 10/10/1998
    $age = Carbon::parse($dob)->age; // calculate age
    $display = "$birthDate ({$age} years old)";
} else {
    $display = 'Not set';
}
?>

<div class="info-card bg-white rounded-2xl p-5 shadow-sm">
    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Birthday</p>
    <p class="text-base font-bold text-slate-800 flex items-center gap-2">
        <i class="fas fa-birthday-cake text-indigo-400"></i>
        <?php echo e($display); ?>

    </p>
</div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Role</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-indigo-100 text-indigo-700 border border-indigo-200">
                                    <?php echo e($user->role->name ?? 'Teacher'); ?>

                                </span>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Status</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold <?php echo e($user->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-red-100 text-red-700 border border-red-200'); ?>">
                                    <span class="w-2 h-2 rounded-full <?php echo e($user->is_active ? 'bg-emerald-500 status-pulse' : 'bg-red-500'); ?> mr-2"></span>
                                    <?php echo e($user->is_active ? 'Active' : 'Inactive'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-personal" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                Personal Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">DepEd ID</p>
                                <p class="text-base font-bold text-slate-800 font-mono"><?php echo e($teacher->deped_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Full Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->first_name ?? ''); ?> <?php echo e($teacher->middle_name ?? ''); ?> <?php echo e($teacher->last_name ?? ''); ?> <?php echo e($teacher->suffix ?? ''); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date of Birth</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->date_of_birth ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Place of Birth</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->place_of_birth ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Gender</p>
                                <p class="text-base font-bold text-slate-800 capitalize"><?php echo e($teacher->gender ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Civil Status</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->civil_status ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nationality</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->nationality ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Religion</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->religion ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Blood Type</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                    <i class="fas fa-tint mr-1.5 text-red-500"></i>
                                    <?php echo e($teacher->blood_type ?? 'N/A'); ?>

                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-contact" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                Contact Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mobile Number</p>
                                <p class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-mobile-alt text-indigo-400"></i>
                                    <?php echo e($teacher->mobile_number ?? 'N/A'); ?>

                                </p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Telephone</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->telephone_number ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Street Address</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->street_address ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Barangay</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->barangay ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">City/Municipality</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->city_municipality ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Province</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->province ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">ZIP Code</p>
                                <p class="text-base font-bold text-slate-800 font-mono"><?php echo e($teacher->zip_code ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Region</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->region ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-employment" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-building"></i>
                                </div>
                                Employment Details
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Employment Status</p>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                    <?php echo e($teacher->employment_status ?? 'N/A'); ?>

                                </span>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date Hired</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->date_hired ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Date Regularized</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->date_regularized ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Current Status</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->current_status ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Teaching Level</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->teaching_level ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Position</p>
                                <p class="text-base font-bold text-indigo-700"><?php echo e($teacher->position ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Designation</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->designation ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Department</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->department ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-education" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-university"></i>
                                </div>
                                Educational Background
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Highest Education</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->highest_education ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Degree Program</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->degree_program ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Major</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->major ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Minor</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->minor ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">School Graduated</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->school_graduated ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Year Graduated</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->year_graduated ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">PRC License Number</p>
                                <p class="text-base font-bold text-slate-800 font-mono bg-slate-100 px-3 py-1.5 rounded-lg inline-block"><?php echo e($teacher->prc_license_number ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">PRC Validity</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->prc_license_validity ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Years of Experience</p>
                                <p class="text-base font-bold text-slate-800 flex items-center gap-2">
                                    <i class="fas fa-star text-amber-400"></i>
                                    <span><?php echo e($teacher->years_of_experience ?? 'N/A'); ?> years</span>
                                </p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Honors Received</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->honors_received ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-emergency" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-ambulance"></i></div>
                                Emergency Contact
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contact Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->emergency_contact_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Relationship</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->emergency_contact_relationship ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contact Number</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->emergency_contact_number ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm sm:col-span-2 lg:col-span-3">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Address</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->emergency_contact_address ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-salary" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-money-check-alt"></i></div>
                                Salary & Bank Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Salary Grade</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->salary_grade ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Step Increment</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->step_increment ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Basic Salary</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->basic_salary ? '₱'.number_format($teacher->basic_salary, 2) : 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Bank Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->bank_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Bank Account Number</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->bank_account_number ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-credentials" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-certificate"></i></div>
                                Professional Credentials
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">LET Passer</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->let_passer ? 'Yes' : 'No'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Board Rating</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->board_rating ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">TESDA NC</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->tesda_nc ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">TESDA Sector</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->tesda_sector ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Previous School</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->previous_school ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Previous Position</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->previous_position ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-government" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-id-card-alt"></i></div>
                                Government IDs
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">GSIS ID</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->gsis_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pag-IBIG ID</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->pagibig_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">PhilHealth ID</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->philhealth_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">SSS ID</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->sss_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">TIN ID</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->tin_id ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Pag-IBIG RTN</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->pagibig_rtn ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-family" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-pink-500 to-pink-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-users"></i></div>
                                Family Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Spouse Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->spouse_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Spouse Occupation</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->spouse_occupation ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Spouse Contact</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->spouse_contact ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Number of Children</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->number_of_children ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Father's Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->father_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Father's Occupation</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->father_occupation ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mother's Name</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->mother_name ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Mother's Occupation</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->mother_occupation ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="content-others" class="tab-content hidden">
                    <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                        <div class="section-header rounded-2xl p-5 mb-8 border border-white/60 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center text-white shadow-lg"><i class="fas fa-ellipsis-h"></i></div>
                                Other Information
                            </h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Class Adviser</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->is_class_adviser ? 'Yes' : 'No'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Advisory Class</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->advisory_class ?? 'N/A'); ?></p>
                            </div>
                            <div class="info-card bg-white rounded-2xl p-5 shadow-sm sm:col-span-2 lg:col-span-3">
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Remarks</p>
                                <p class="text-base font-bold text-slate-800"><?php echo e($teacher->remarks ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="h-24 lg:h-28"></div>

            </main>
        </div>
    </div>

    
    <div class="fab-container fixed bottom-6 right-6 z-50">
        <a href="<?php echo e(route('teacher.profile.edit')); ?>" class="fab-icon w-14 h-14 rounded-full gradient-bg flex items-center justify-center text-white text-xl z-50 relative">
            <i class="fas fa-pencil-alt"></i>
        </a>
        <span class="fab-tooltip">Edit Profile</span>
    </div>

<script>
    // Tab switching with smooth transition
    function switchTab(tabName) {
        // Hide all tab contents with fade out
        document.querySelectorAll('.tab-content').forEach(content => {
            content.style.opacity = '0';
            content.style.transform = 'translateY(10px)';
            setTimeout(() => {
                content.classList.add('hidden');
                content.style.opacity = '';
                content.style.transform = '';
            }, 150);
        });
        
        // Show selected tab content with delay for smooth transition
        setTimeout(() => {
            const selectedContent = document.getElementById('content-' + tabName);
            selectedContent.classList.remove('hidden');
            // Trigger reflow
            void selectedContent.offsetWidth;
            selectedContent.style.animation = 'fadeIn 0.3s ease-out';
        }, 160);
        
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



    // Success message functions with smooth animation
    function closeSuccessMessage() {
        const message = document.getElementById('success-message');
        if (message) {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-20px) scale(0.95)';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }
    }

    // Auto-hide success message after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            setTimeout(() => {
                closeSuccessMessage();
            }, 5000);
        }
        
    });


</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\profile\index.blade.php ENDPATH**/ ?>