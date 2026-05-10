<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile | Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            background: #f8fafc;
            overflow-x: hidden;
        }

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

        .main-content {
            flex: 1;
            overflow-x: hidden;
            background: #f8fafc;
        }

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

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
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
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
            color: white;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 12px 24px;
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

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
        }

        .info-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
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

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-active {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-inactive {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

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
        .stagger-4 { animation-delay: 0.4s; }

        .document-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            background: #f8fafc;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .document-card:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
        }

        .document-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .audit-item {
            position: relative;
            padding-left: 32px;
            padding-bottom: 24px;
            border-left: 2px solid #e2e8f0;
            margin-left: 16px;
        }

        .audit-item::before {
            content: '';
            position: absolute;
            left: -9px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #10b981;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #10b981;
        }

        .audit-item:last-child {
            border-left: 2px solid transparent;
            padding-bottom: 0;
        }

        .change-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-top: 4px;
        }

        .change-field {
            font-weight: 600;
            color: #64748b;
        }

        .change-old {
            text-decoration: line-through;
            color: #ef4444;
            background: #fee2e2;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .change-new {
            color: #10b981;
            font-weight: 600;
            background: #d1fae5;
            padding: 2px 8px;
            border-radius: 6px;
        }

        .photo-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #94a3b8;
            border: 4px solid white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .photo-uploaded {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .fixed { position: fixed; }

        .tab-btn {
            padding: 12px 20px;
            font-weight: 600;
            font-size: 14px;
            color: #64748b;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            background: none;
            border-top: none;
            border-left: none;
            border-right: none;
        }

        .tab-btn:hover {
            color: #10b981;
        }

        .tab-btn.active {
            color: #10b981;
            border-bottom-color: #10b981;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
    </style>
</head>
<body class="antialiased text-slate-800 overflow-x-hidden" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

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
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="main-wrapper">
        <div class="main-content">
            <div class="max-w-6xl mx-auto pb-8">
                
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
                    <div class="flex items-center gap-4">
                        <div class="photo-placeholder overflow-hidden">
                            <?php if($teacher->photo_path): ?>
                                <img src="<?php echo e(profile_photo_url($teacher->photo_path)); ?>" alt="Profile" class="w-full h-full object-cover">
                            <?php else: ?>
                                <i class="fas fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                <h1 class="text-3xl font-bold text-slate-900">
                                    <?php echo e($teacher->first_name); ?> <?php echo e($teacher->middle_name ? $teacher->middle_name . ' ' : ''); ?><?php echo e($teacher->last_name); ?> <?php echo e($teacher->suffix ?? ''); ?>

                                </h1>
                                <span class="section-badge">
                                    <i class="fas fa-chalkboard-teacher text-xs"></i>
                                    <?php echo e($teacher->current_status ?? 'Active'); ?>

                                </span>
                            </div>
                            <p class="text-slate-500 flex items-center gap-2 flex-wrap">
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-lg text-sm font-semibold">
                                    <?php echo e($teacher->position ?? 'Teacher'); ?>

                                </span>
                                <span class="text-slate-400">•</span>
                                <span><i class="fas fa-id-card mr-1"></i> <?php echo e($teacher->deped_id ?? 'No DepEd ID'); ?></span>
                                <span class="text-slate-400">•</span>
                                <span><i class="fas fa-envelope mr-1"></i> <?php echo e($teacher->email ?? 'No email'); ?></span>
                            </p>
                        </div>
                    </div>
                    
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 animate-fade-in stagger-1">
                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-emerald-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">
                                <?php echo e($teacher->date_hired ? $teacher->date_hired->format('M Y') : 'N/A'); ?>

                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Date Hired</p>
                        <p class="text-xs text-slate-400 mt-1">
                            <?php echo e($teacher->date_hired ? $teacher->date_hired->diffForHumans() : 'Not set'); ?>

                        </p>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-blue-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">
                                <?php echo e($teacher->years_of_experience ?? 0); ?>

                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Years Experience</p>
                        <p class="text-xs text-slate-400 mt-1">Professional experience</p>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <span class="text-2xl font-bold text-slate-900">
                                <?php echo e($teacher->is_class_adviser ? 'Yes' : 'No'); ?>

                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">Class Adviser</p>
                        <p class="text-xs text-slate-400 mt-1">
                            <?php echo e($teacher->advisory_class ?? 'Not assigned'); ?>

                        </p>
                    </div>

                    <div class="stat-card">
                        <div class="flex items-center justify-between mb-3">
                            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-certificate text-amber-600"></i>
                            </div>
                            <span class="text-lg font-bold text-slate-900 truncate max-w-[120px]">
                                <?php echo e($teacher->prc_license_number ?? 'N/A'); ?>

                            </span>
                        </div>
                        <p class="text-sm font-semibold text-slate-600">PRC License</p>
                        <p class="text-xs text-slate-400 mt-1">
                            <?php echo e($teacher->prc_license_validity ? 'Valid until ' . $teacher->prc_license_validity->format('M Y') : 'No license'); ?>

                        </p>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="glass-card mb-6 animate-fade-in stagger-2">
                    <div class="flex border-b border-slate-100 overflow-x-auto">
                        <button class="tab-btn active" onclick="switchTab('personal')">
                            <i class="fas fa-user mr-2"></i>Personal
                        </button>
                        <button class="tab-btn" onclick="switchTab('employment')">
                            <i class="fas fa-briefcase mr-2"></i>Employment
                        </button>
                        <button class="tab-btn" onclick="switchTab('education')">
                            <i class="fas fa-graduation-cap mr-2"></i>Education
                        </button>
                        <button class="tab-btn" onclick="switchTab('credentials')">
                            <i class="fas fa-certificate mr-2"></i>Credentials
                        </button>
                        <button class="tab-btn" onclick="switchTab('government')">
                            <i class="fas fa-id-card mr-2"></i>Gov't IDs
                        </button>
                        <button class="tab-btn" onclick="switchTab('family')">
                            <i class="fas fa-users mr-2"></i>Family
                        </button>
                        <button class="tab-btn" onclick="switchTab('documents')">
                            <i class="fas fa-folder-open mr-2"></i>Documents
                        </button>
                        <button class="tab-btn" onclick="switchTab('audit')">
                            <i class="fas fa-history mr-2"></i>History
                        </button>
                    </div>
                </div>

                <!-- Personal Information Tab -->
                <div id="personal" class="tab-content active">
                    <div class="glass-card p-6 mb-6 animate-fade-in stagger-3">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-user text-emerald-500"></i>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Full Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->middle_name ? $teacher->middle_name . ' ' : ''); ?><?php echo e($teacher->last_name); ?> <?php echo e($teacher->suffix ?? ''); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Date of Birth</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->date_of_birth ? $teacher->date_of_birth->format('F d, Y') : 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Place of Birth</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->place_of_birth ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-venus-mars"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Gender</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->gender ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Civil Status</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->civil_status ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-cyan-100 text-cyan-600">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Nationality</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->nationality ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-praying-hands"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Religion</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->religion ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-red-100 text-red-600">
                                    <i class="fas fa-tint"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Blood Type</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->blood_type ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="glass-card p-6 mb-6 animate-fade-in stagger-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-address-book text-emerald-500"></i>
                            Contact Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Email</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->email ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Mobile</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->mobile_number ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Telephone</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->telephone_number ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item md:col-span-2 lg:col-span-3">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Complete Address</p>
                                    <p class="font-bold text-slate-900">
                                        <?php echo e($teacher->street_address ?? ''); ?> <?php echo e($teacher->barangay ? ', ' . $teacher->barangay : ''); ?>

                                        <?php echo e($teacher->city_municipality ? ', ' . $teacher->city_municipality : ''); ?>

                                        <?php echo e($teacher->province ? ', ' . $teacher->province : ''); ?>

                                        <?php echo e($teacher->zip_code ? ' ' . $teacher->zip_code : ''); ?>

                                        <?php echo e($teacher->region ? ' (' . $teacher->region . ')' : ''); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="glass-card p-6 animate-fade-in stagger-4">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-ambulance text-red-500"></i>
                            Emergency Contact
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-red-100 text-red-600">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Contact Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->emergency_contact_name ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-orange-100 text-orange-600">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Relationship</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->emergency_contact_relationship ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-green-100 text-green-600">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Contact Number</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->emergency_contact_number ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item md:col-span-2 lg:col-span-3">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Address</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->emergency_contact_address ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Tab -->
                <div id="employment" class="tab-content">
                    <div class="glass-card p-6 mb-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-briefcase text-emerald-500"></i>
                            Employment Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-id-badge"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">DepEd ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->deped_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Employment Status</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->employment_status ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Date Hired</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->date_hired ? $teacher->date_hired->format('F d, Y') : 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Date Regularized</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->date_regularized ? $teacher->date_regularized->format('F d, Y') : 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-toggle-on"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Current Status</p>
                                    <p class="font-bold text-slate-900">
                                        <span class="status-badge <?php echo e(($teacher->current_status ?? 'active') == 'Active' ? 'status-active' : 'status-inactive'); ?>">
                                            <i class="fas fa-circle text-[8px]"></i>
                                            <?php echo e($teacher->current_status ?? 'Active'); ?>

                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-cyan-100 text-cyan-600">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Teaching Level</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->teaching_level ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Position</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->position ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-teal-100 text-teal-600">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Designation</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->designation ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-pink-100 text-pink-600">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Department</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->department ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-yellow-100 text-yellow-600">
                                    <i class="fas fa-chalkboard"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Class Adviser</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->is_class_adviser ? 'Yes' : 'No'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-lime-100 text-lime-600">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Advisory Class</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->advisory_class ?? 'Not assigned'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary Information -->
                    <div class="glass-card p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-emerald-500"></i>
                            Salary & Bank Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Salary Grade</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->salary_grade ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-arrow-up"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Step Increment</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->step_increment ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-peso-sign"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Basic Salary</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->basic_salary ? '₱' . number_format($teacher->basic_salary, 2) : 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Bank Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->bank_name ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item md:col-span-2">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Bank Account Number</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->bank_account_number ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education Tab -->
                <div id="education" class="tab-content">
                    <div class="glass-card p-6 mb-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-emerald-500"></i>
                            Educational Background
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-award"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Highest Education</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->highest_education ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-scroll"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Degree Program</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->degree_program ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Major</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->major ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Minor</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->minor ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">School Graduated</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->school_graduated ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-cyan-100 text-cyan-600">
                                    <i class="fas fa-calendar-graduation"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Year Graduated</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->year_graduated ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item md:col-span-2 lg:col-span-3">
                                <div class="info-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-medal"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Honors Received</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->honors_received ?? 'None'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credentials Tab -->
                <div id="credentials" class="tab-content">
                    <div class="glass-card p-6 mb-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-certificate text-emerald-500"></i>
                            Professional Credentials
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">PRC License Number</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->prc_license_number ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">License Validity</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->prc_license_validity ? $teacher->prc_license_validity->format('F d, Y') : 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">LET Passer</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->let_passer ? 'Yes' : 'No'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Board Rating</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->board_rating ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">TESDA NC</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->tesda_nc ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-cyan-100 text-cyan-600">
                                    <i class="fas fa-industry"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">TESDA Sector</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->tesda_sector ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-indigo-100 text-indigo-600">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Years of Experience</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->years_of_experience ?? 'Not provided'); ?> years</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-teal-100 text-teal-600">
                                    <i class="fas fa-school"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Previous School</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->previous_school ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-pink-100 text-pink-600">
                                    <i class="fas fa-user-tag"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Previous Position</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->previous_position ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Government IDs Tab -->
                <div id="government" class="tab-content">
                    <div class="glass-card p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-id-card-alt text-emerald-500"></i>
                            Government IDs
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-emerald-100 text-emerald-600">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">GSIS ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->gsis_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Pag-IBIG ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->pagibig_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-heartbeat"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">PhilHealth ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->philhealth_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-amber-100 text-amber-600">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">SSS ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->sss_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-file-invoice"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">TIN ID</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->tin_id ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-cyan-100 text-cyan-600">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Pag-IBIG RTN</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->pagibig_rtn ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Tab -->
                <div id="family" class="tab-content">
                    <div class="glass-card p-6 mb-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-heart text-rose-500"></i>
                            Spouse Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-rose-100 text-rose-600">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Spouse Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->spouse_name ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-pink-100 text-pink-600">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Occupation</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->spouse_occupation ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-purple-100 text-purple-600">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Contact</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->spouse_contact ?? 'Not provided'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-child"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Number of Children</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->number_of_children ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="glass-card p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-users text-blue-500"></i>
                            Parents Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="info-item">
                                <div class="info-icon bg-blue-100 text-blue-600">
                                    <i class="fas fa-male"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Father's Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->father_name ?? 'Not provided'); ?></p>
                                    <p class="text-xs text-slate-500 mt-1">Occupation: <?php echo e($teacher->father_occupation ?? 'N/A'); ?></p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon bg-pink-100 text-pink-600">
                                    <i class="fas fa-female"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500 font-semibold uppercase">Mother's Name</p>
                                    <p class="font-bold text-slate-900"><?php echo e($teacher->mother_name ?? 'Not provided'); ?></p>
                                    <p class="text-xs text-slate-500 mt-1">Occupation: <?php echo e($teacher->mother_occupation ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div id="documents" class="tab-content">
                    <div class="glass-card p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-folder-open text-emerald-500"></i>
                            Documents & Attachments
                        </h3>
                        
                        <?php
                            $documents = [
                                ['path' => $teacher->resume_path, 'name' => 'Resume/CV', 'icon' => 'fa-file-pdf', 'color' => 'red'],
                                ['path' => $teacher->prc_id_path, 'name' => 'PRC ID', 'icon' => 'fa-id-card', 'color' => 'blue'],
                                ['path' => $teacher->transcript_path, 'name' => 'Transcript of Records', 'icon' => 'fa-file-alt', 'color' => 'amber'],
                                ['path' => $teacher->clearance_path, 'name' => 'Clearance', 'icon' => 'fa-file-signature', 'color' => 'emerald'],
                                ['path' => $teacher->medical_cert_path, 'name' => 'Medical Certificate', 'icon' => 'fa-file-medical', 'color' => 'rose'],
                                ['path' => $teacher->nbi_clearance_path, 'name' => 'NBI Clearance', 'icon' => 'fa-shield-alt', 'color' => 'purple'],
                                ['path' => $teacher->service_record_path, 'name' => 'Service Record', 'icon' => 'fa-history', 'color' => 'cyan']
                            ];
                        ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="document-card">
                                    <div class="flex items-center gap-4">
                                        <div class="document-icon bg-<?php echo e($doc['color']); ?>-100 text-<?php echo e($doc['color']); ?>-600">
                                            <i class="fas <?php echo e($doc['icon']); ?>"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900"><?php echo e($doc['name']); ?></p>
                                            <p class="text-xs text-slate-500">
                                                <?php echo e($doc['path'] ? 'Uploaded' : 'Not uploaded'); ?>

                                            </p>
                                        </div>
                                    </div>
                                    <?php if($doc['path']): ?>
                                        <a href="<?php echo e(asset('storage/' . $doc['path'])); ?>" target="_blank" class="btn-primary text-sm py-2 px-4">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-sm font-semibold">Missing</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Audit Trail Tab -->
                <div id="audit" class="tab-content">
                    <div class="glass-card p-6 animate-fade-in">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-history text-emerald-500"></i>
                            Edit History & Audit Trail
                        </h3>
                        
                        <?php if(isset($auditLogs) && count($auditLogs) > 0): ?>
                            <div class="space-y-0">
                                <?php $__currentLoopData = $auditLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="audit-item">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <p class="font-bold text-slate-900"><?php echo e($log->action); ?> by <?php echo e($log->user->name ?? 'System'); ?></p>
                                                <p class="text-sm text-slate-500"><?php echo e($log->created_at->format('F d, Y h:i A')); ?></p>
                                            </div>
                                            <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-semibold">
                                                <?php echo e($log->ip_address ?? 'N/A'); ?>

                                            </span>
                                        </div>
                                        <?php if($log->changes): ?>
                                            <div class="bg-slate-50 rounded-lg p-3 space-y-1">
                                                <?php $__currentLoopData = $log->changes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $change): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="change-item">
                                                        <span class="change-field"><?php echo e(ucwords(str_replace('_', ' ', $field))); ?>:</span>
                                                        <span class="change-old"><?php echo e($change['old'] ?? 'N/A'); ?></span>
                                                        <i class="fas fa-arrow-right text-slate-400"></i>
                                                        <span class="change-new"><?php echo e($change['new'] ?? 'N/A'); ?></span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-check text-2xl text-slate-400"></i>
                                </div>
                                <h4 class="font-bold text-slate-900 mb-2">No Edit History</h4>
                                <p class="text-slate-500 text-sm max-w-md mx-auto">
                                    No changes have been recorded yet. When the teacher edits their profile, those changes will appear here.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- Floating Action Buttons -->
            <div class="fixed bottom-8 right-8 flex flex-col gap-3 z-50">
                <a href="<?php echo e(route('admin.teachers.edit', $teacher->id)); ?>" 
                   class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/40 transition-all hover:scale-110 hover:rotate-3"
                   title="Edit Teacher">
                    <i class="fas fa-edit text-lg"></i>
                </a>
                <a href="<?php echo e(route('admin.teachers.index')); ?>" 
                   class="w-12 h-12 bg-white text-slate-600 hover:text-slate-900 rounded-full flex items-center justify-center shadow-lg border border-slate-200 transition-all hover:scale-110"
                   title="Back to List">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Show selected tab content
        document.getElementById(tabId).classList.add('active');
        
        // Add active class to clicked button
        event.target.classList.add('active');
    }

    // Initialize first tab
    document.addEventListener('DOMContentLoaded', function() {
        // Any initialization code here
    });
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\teachers\show.blade.php ENDPATH**/ ?>