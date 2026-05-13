<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Pupil | Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        :root {
            --primary: #0d9488;
            --primary-dark: #0f766e;
            --primary-light: #14b8a6;
            --accent: #f97316;
            --accent-light: #fb923c;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #f1f5f9;
        }

        .dashboard-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .sidebar-container {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
            flex-shrink: 0;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            background: #f1f5f9;
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
            overflow-y: auto;
            overflow-x: hidden;
            padding: 32px;
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

        @media (max-width: 1024px) {
            .sidebar-container {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar-container.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }

        /* DepEd Form Section Header Style - matching register-page */
        .form-section-header {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border: 2px solid #cbd5e1;
            border-bottom: 3px solid var(--primary);
            padding: 0.75rem 1rem;
            font-weight: 700;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #1e293b;
            text-align: center;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .form-section-header.accent {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border-color: var(--primary-dark);
        }

        .form-section-body {
            border: 2px solid #cbd5e1;
            border-top: none;
            padding: 1.5rem;
            background: white;
            border-radius: 0 0 0.5rem 0.5rem;
            margin-bottom: 1.5rem;
        }

        /* Custom Checkbox & Radio - matching register-page */
        .custom-checkbox, .custom-radio {
            appearance: none;
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #cbd5e1;
            cursor: pointer;
            position: relative;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        .custom-checkbox {
            border-radius: 0.25rem;
        }
        .custom-radio {
            border-radius: 50%;
        }
        .custom-checkbox:checked, .custom-radio:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .custom-checkbox:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        .custom-radio:checked::after {
            content: '';
            position: absolute;
            left: 3px;
            top: 3px;
            width: 8px;
            height: 8px;
            background: white;
            border-radius: 50%;
        }

        /* Label style matching DepEd form */
        .form-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.375rem;
            display: block;
        }
        .form-label .required {
            color: #dc2626;
        }

        /* Input styling matching register-page */
        .form-input {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }
        .form-input:disabled, .form-input.bg-slate-100 {
            background: #f1f5f9;
            color: #64748b;
        }

        .form-select {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 2px solid #cbd5e1;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            cursor: pointer;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 40px;
        }
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        /* Sub-section divider */
        .sub-section {
            border-top: 1px dashed #cbd5e1;
            padding-top: 1rem;
            margin-top: 1rem;
        }
        .sub-section:first-child {
            border-top: none;
            padding-top: 0;
            margin-top: 0;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px 0 rgba(13, 148, 136, 0.39);
            text-decoration: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 148, 136, 0.23);
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Floating Back Button */
        .floating-back-btn {
            position: fixed;
            right: 32px;
            bottom: 32px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            box-shadow: 0 4px 20px rgba(13, 148, 136, 0.4);
            transition: all 0.3s ease;
            z-index: 100;
            text-decoration: none;
        }
        .floating-back-btn:hover {
            transform: scale(1.1) rotate(-10deg);
            box-shadow: 0 6px 30px rgba(13, 148, 136, 0.5);
        }

        /* Success Alert */
        .alert-success {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            border: 1px solid #86efac;
            color: #166534;
            padding: 20px 24px;
            border-radius: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(34, 197, 94, 0.15);
        }
        .alert-success::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: #22c55e;
            width: 100%;
            transform-origin: left;
            animation: countdown 5s linear forwards;
        }
        @keyframes countdown {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

        /* Error Alert */
        .alert-error {
            background: #fee2e2;
            border: 2px solid #ef4444;
            color: #991b1b;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            position: relative;
        }

        /* File upload area matching register-page */
        .file-upload-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 5rem;
            border: 2px dashed #cbd5e1;
            border-radius: 0.5rem;
            cursor: pointer;
            background: white;
            transition: all 0.3s;
        }
        .file-upload-area:hover {
            background: #f8fafc;
            border-color: var(--primary);
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        .input-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
        }

        .mobile-overlay {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body class="text-slate-800">

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

    <!-- Floating Back Button -->
    <a href="<?php echo e(route('admin.students.index')); ?>" class="floating-back-btn" title="Back to List">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="dashboard-layout">
        <!-- Fixed Sidebar -->
        <div class="sidebar-container">
            <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Fixed Header -->
            <header class="main-header">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button type="button" @click="mobileOpen = !mobileOpen" class="lg:hidden p-2.5 hover:bg-slate-100 rounded-xl transition-colors">
                            <i class="fas fa-bars text-slate-600"></i>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Create Pupil</h2>
                            <p class="text-sm text-slate-500 font-medium flex items-center gap-2 mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                Add new pupil enrollment
                            </p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="main-content">

                <?php if(session('success')): ?>
                    <div class="alert-success animate-fade-in-up" id="successAlert">
                        <div class="flex items-center gap-3 flex-1">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            <div>
                                <div class="font-bold text-lg"><?php echo e(session('success')); ?></div>
                                <div class="text-sm text-green-700">Redirecting in <span id="countdown">5</span> seconds...</div>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-green-700 bg-white/60 px-3 py-1 rounded-full" id="timerBadge">5s</div>
                    </div>
                    <script>
                        let seconds = 5;
                        const countdownEl = document.getElementById('countdown');
                        const timerBadgeEl = document.getElementById('timerBadge');
                        const timer = setInterval(function() {
                            seconds--;
                            countdownEl.textContent = seconds;
                            timerBadgeEl.textContent = seconds + 's';
                            if (seconds <= 0) {
                                clearInterval(timer);
                                window.location.href = "<?php echo e(route('admin.students.index')); ?>";
                            }
                        }, 1000);
                    </script>
                <?php endif; ?>

                <form action="<?php echo e(route('admin.students.store')); ?>" method="POST" enctype="multipart/form-data" class="animate-fade-in-up" id="studentForm">
                    <?php echo csrf_field(); ?>

                    <?php if($errors->any() || session('error')): ?>
                        <div class="alert-error animate-fade-in-up" id="errorAlert">
                            <strong><?php echo e($errors->any() ? 'VALIDATION ERRORS:' : 'ERROR:'); ?></strong>
                            <?php if($errors->any()): ?>
                                <ul class="mt-2 ml-5 text-sm">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php elseif(session('error')): ?>
                                <span><?php echo e(session('error')); ?></span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- ========== SECTION 1: ACADEMIC INFORMATION ========== -->
                    <div>
                        <div class="form-section-header">Academic Information</div>
                        <div class="form-section-body space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="form-label">School Year <span class="required">*</span></label>
                                    <input type="text" value="<?php echo e($activeSchoolYear?->name ?? date('Y') . ' - ' . (date('Y') + 1)); ?>" readonly
                                           class="form-input bg-slate-100 text-slate-600 font-semibold">
                                </div>
                                <div>
                                    <label class="form-label">Grade Level to Enroll <span class="required">*</span></label>
                                    <select name="grade_level_id" id="gradeLevel" class="form-select" required onchange="updateSections()">
                                        <option value="">Select Grade Level</option>
                                        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($level->id); ?>" <?php echo e(old('grade_level_id') == $level->id ? 'selected' : ''); ?>><?php echo e($level->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label">Pupil Type <span class="required">*</span></label>
                                    <select name="type" id="studentType" class="form-select" required onchange="toggleStudentTypeFields()">
                                        <option value="new" <?php echo e(old('type') == 'new' ? 'selected' : ''); ?>>New Pupil</option>
                                        <option value="transferee" <?php echo e(old('type') == 'transferee' ? 'selected' : ''); ?>>Transferee</option>
                                        <option value="continuing" <?php echo e(old('type') == 'continuing' ? 'selected' : ''); ?>>Continuing</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Section -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="form-label">Section <span class="required">*</span></label>
                                    <select name="section_id" id="sectionId" class="form-select" required>
                                        <option value="">Select Section</option>
                                    </select>
                                    <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Checkboxes row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 border-t border-dashed border-slate-300">
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-semibold text-slate-700">1. With LRN?</span>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_lrn" value="1" id="hasLrnYes" class="custom-radio" onchange="toggleLrnField()" <?php echo e(old('has_lrn') == '1' ? 'checked' : ''); ?>>
                                        <span class="text-sm text-slate-600">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_lrn" value="0" id="hasLrnNo" class="custom-radio" checked onchange="toggleLrnField()">
                                        <span class="text-sm text-slate-600">No</span>
                                    </label>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-semibold text-slate-700">2. Returning (Balik-Aral)?</span>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_returning_balik_aral" value="1" id="isBalikAralYes" class="custom-radio" onchange="toggleReturningSection()" <?php echo e(old('is_returning_balik_aral') == '1' ? 'checked' : ''); ?>>
                                        <span class="text-sm text-slate-600">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="is_returning_balik_aral" value="0" id="isBalikAralNo" class="custom-radio" checked onchange="toggleReturningSection()">
                                        <span class="text-sm text-slate-600">No</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                    $sectionsJson = $sections->map(function($s) {
                        return [
                            'id' => $s->id,
                            'name' => $s->name,
                            'grade_level_id' => $s->grade_level_id,
                            'capacity' => $s->capacity,
                            'student_count' => $s->students_count
                        ];
                    })->toJson();
                    ?>

                    <!-- ========== SECTION 2: LEARNER INFORMATION ========== -->
                    <div>
                        <div class="form-section-header">Learner Information</div>
                        <div class="form-section-body space-y-4">
                            
                              <!-- PSA Birth Cert & LRN row -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label">PSA Birth Certificate No. (if available)</label>
                                    <input type="text" name="psa_birth_cert_no" placeholder="XXX-XXXX-XXXXXX"
                                           value="<?php echo e(old('psa_birth_cert_no')); ?>" class="form-input">
                                </div>
                                <div id="lrnFieldContainer">
                                    <label class="form-label">Learner Reference No. (LRN) <span id="lrnRequired" class="required hidden">*</span></label>
                                    <input type="text" name="lrn_suffix" id="lrnInput" maxlength="12" placeholder="12-digit LRN"
                                           value="<?php echo e(old('lrn_suffix')); ?>"
                                           class="form-input font-mono tracking-wider"
                                           disabled>
                                    <p class="input-hint" id="lrnHelper">Select "Yes" for "With LRN?" to enable this field</p>
                                </div>
                            </div>

                            <!-- Name row -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="form-label">Last Name <span class="required">*</span></label>
                                    <input type="text" name="last_name" placeholder="DELA CRUZ" required
                                           value="<?php echo e(old('last_name')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">First Name <span class="required">*</span></label>
                                    <input type="text" name="first_name" placeholder="JUAN" required
                                           value="<?php echo e(old('first_name')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" placeholder="SANTOS"
                                           value="<?php echo e(old('middle_name')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Extension (Jr., III, etc.)</label>
                                    <select name="suffix" class="form-select">
                                        <option value="" <?php echo e(old('suffix') == '' ? 'selected' : ''); ?>>None</option>
                                        <option value="Jr." <?php echo e(old('suffix') == 'Jr.' ? 'selected' : ''); ?>>Jr.</option>
                                        <option value="Sr." <?php echo e(old('suffix') == 'Sr.' ? 'selected' : ''); ?>>Sr.</option>
                                        <option value="II" <?php echo e(old('suffix') == 'II' ? 'selected' : ''); ?>>II</option>
                                        <option value="III" <?php echo e(old('suffix') == 'III' ? 'selected' : ''); ?>>III</option>
                                        <option value="IV" <?php echo e(old('suffix') == 'IV' ? 'selected' : ''); ?>>IV</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Birth info row -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="form-label">Birthdate (mm/dd/yyyy) <span class="required">*</span></label>
                                    <input type="date" name="birthday" required
                                           value="<?php echo e(old('birthday')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Place of Birth (Municipality/City) <span class="required">*</span></label>
                                    <input type="text" name="birth_place" placeholder="DUMAGUETE CITY" required
                                           value="<?php echo e(old('birth_place')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Age</label>
                                    <input type="number" id="ageField" placeholder="Auto-calculated" readonly
                                           class="form-input bg-slate-100 text-slate-500">
                                </div>
                            </div>

                            <!-- Sex & Mother Tongue -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="form-label">Sex <span class="required">*</span></label>
                                    <div class="flex gap-4 mt-2">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="gender" value="Male" class="custom-radio" <?php echo e(old('gender') == 'Male' ? 'checked' : ''); ?> required>
                                            <span class="text-sm text-slate-600">Male</span>
                                        </label>
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="radio" name="gender" value="Female" class="custom-radio" <?php echo e(old('gender') == 'Female' ? 'checked' : ''); ?> required>
                                            <span class="text-sm text-slate-600">Female</span>
                                        </label>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label">Mother Tongue <span class="required">*</span></label>
                                    <input type="text" name="mother_tongue" placeholder="CEBUANO" required
                                           value="<?php echo e(old('mother_tongue')); ?>" class="form-input">
                                </div>
                            </div>

                            <!-- IP Community -->
                            <div class="sub-section">
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <label class="form-label">Belonging to any Indigenous Peoples (IP) Community/Indigenous Cultural Community?</label>
                                        <div class="flex gap-4 mt-1">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="is_ip" value="1" class="custom-radio" onchange="toggleIpField()" <?php echo e(old('is_ip') == '1' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-slate-600">Yes</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="is_ip" value="0" class="custom-radio" onchange="toggleIpField()" <?php echo e(old('is_ip') != '1' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-slate-600">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex-1" id="ipSpecificationField">
                                        <label class="form-label">If Yes, please specify:</label>
                                        <input type="text" name="ip_specification" placeholder="e.g., Subanon, Manobo"
                                               value="<?php echo e(old('ip_specification')); ?>" class="form-input" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- 4Ps -->
                            <div class="sub-section">
                                <div class="flex items-start gap-4">
                                    <div class="flex-1">
                                        <label class="form-label">Is your family a beneficiary of 4Ps?</label>
                                        <div class="flex gap-4 mt-1">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="is_4ps_beneficiary" value="1" class="custom-radio" onchange="toggle4psField()" <?php echo e(old('is_4ps_beneficiary') == '1' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-slate-600">Yes</span>
                                            </label>
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="is_4ps_beneficiary" value="0" class="custom-radio" onchange="toggle4psField()" <?php echo e(old('is_4ps_beneficiary') != '1' ? 'checked' : ''); ?>>
                                                <span class="text-sm text-slate-600">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex-1" id="householdIdField">
                                        <label class="form-label">If Yes, write the 4Ps Household ID Number:</label>
                                        <input type="text" name="household_id_4ps" placeholder="XXXXXXXXXXXX"
                                               value="<?php echo e(old('household_id_4ps')); ?>" class="form-input font-mono tracking-wider" disabled>
                                    </div>
                                </div>
                            </div>

                            <!-- Nationality, Religion, Ethnicity -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 sub-section">
                                <div>
                                    <label class="form-label">Nationality <span class="required">*</span></label>
                                    <input type="text" name="nationality" placeholder="FILIPINO" required
                                           value="<?php echo e(old('nationality', 'Filipino')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Religion <span class="required">*</span></label>
                                    <input type="text" name="religion" placeholder="ROMAN CATHOLIC" required
                                           value="<?php echo e(old('religion', 'Roman Catholic')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Ethnicity <span class="required">*</span></label>
                                    <input type="text" name="ethnicity" placeholder="e.g., CEBUANO, TAGALOG" required
                                           value="<?php echo e(old('ethnicity')); ?>" class="form-input">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 3: CURRENT ADDRESS ========== -->
                    <div>
                        <div class="form-section-header">Current Address</div>
                        <div class="form-section-body space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="form-label">House No./Street <span class="required">*</span></label>
                                    <input type="text" name="street_address" placeholder="123 Purok 1" required
                                           value="<?php echo e(old('street_address')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Street Name</label>
                                    <input type="text" name="street_name" placeholder="MABINI STREET"
                                           value="<?php echo e(old('street_name')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Barangay <span class="required">*</span></label>
                                    <input type="text" name="barangay" placeholder="TUGAWE" required
                                           value="<?php echo e(old('barangay')); ?>" class="form-input">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="form-label">Municipality/City <span class="required">*</span></label>
                                    <input type="text" name="city" placeholder="DAUIN" required
                                           value="<?php echo e(old('city')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Province <span class="required">*</span></label>
                                    <input type="text" name="province" placeholder="NEGROS ORIENTAL" required
                                           value="<?php echo e(old('province')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Country</label>
                                    <input type="text" value="PHILIPPINES" readonly
                                           class="form-input bg-slate-100 text-slate-600 font-semibold">
                                </div>
                                <div>
                                    <label class="form-label">Zip Code <span class="required">*</span></label>
                                    <input type="text" name="zip_code" placeholder="6217" maxlength="4" required
                                           value="<?php echo e(old('zip_code')); ?>" class="form-input font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 4: PERMANENT ADDRESS ========== -->
                    <div>
                        <div class="form-section-header flex justify-between items-center">
                            <span>Permanent Address</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-normal normal-case tracking-normal">Same with your Current Address?</span>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="same_as_current_address" value="1" class="custom-radio" checked onchange="togglePermanentAddress()">
                                    <span class="text-xs font-normal normal-case tracking-normal">Yes</span>
                                </label>
                                <label class="flex items-center gap-1 cursor-pointer">
                                    <input type="radio" name="same_as_current_address" value="0" class="custom-radio" onchange="togglePermanentAddress()">
                                    <span class="text-xs font-normal normal-case tracking-normal">No</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-section-body space-y-3 hidden" id="permanentAddressSection">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="form-label">House No./Street</label>
                                    <input type="text" name="permanent_street_address" placeholder="123 Purok 1"
                                           value="<?php echo e(old('permanent_street_address')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Street Name</label>
                                    <input type="text" name="permanent_street_name" placeholder="MABINI STREET"
                                           value="<?php echo e(old('permanent_street_name')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Barangay</label>
                                    <input type="text" name="permanent_barangay" placeholder="TUGAWE"
                                           value="<?php echo e(old('permanent_barangay')); ?>" class="form-input">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                <div>
                                    <label class="form-label">Municipality/City</label>
                                    <input type="text" name="permanent_city" placeholder="DAUIN"
                                           value="<?php echo e(old('permanent_city')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Province</label>
                                    <input type="text" name="permanent_province" placeholder="NEGROS ORIENTAL"
                                           value="<?php echo e(old('permanent_province')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Country</label>
                                    <input type="text" value="PHILIPPINES" readonly
                                           class="form-input bg-slate-100 text-slate-600 font-semibold">
                                </div>
                                <div>
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" name="permanent_zip_code" placeholder="6217" maxlength="4"
                                           value="<?php echo e(old('permanent_zip_code')); ?>" class="form-input font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 5: PARENT'S/GUARDIAN'S INFORMATION ========== -->
                    <div>
                        <div class="form-section-header">Parent's/Guardian's Information</div>
                        <div class="form-section-body space-y-4">
                            
                            <!-- Father -->
                            <div class="sub-section">
                                <p class="text-sm font-bold text-slate-700 mb-2">Father's Name</p>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="father_last_name" placeholder="DELA CRUZ"
                                               value="<?php echo e(old('father_last_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="father_first_name" placeholder="JUAN"
                                               value="<?php echo e(old('father_first_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="father_middle_name" placeholder="SANTOS"
                                               value="<?php echo e(old('father_middle_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Contact Number</label>
                                        <input type="tel" name="father_contact" maxlength="11" placeholder="09XXXXXXXXX"
                                               value="<?php echo e(old('father_contact')); ?>" class="form-input font-mono">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="father_occupation" placeholder="e.g., FARMER, TEACHER"
                                           value="<?php echo e(old('father_occupation')); ?>" class="form-input md:w-1/2">
                                </div>
                            </div>

                            <!-- Mother -->
                            <div class="sub-section">
                                <p class="text-sm font-bold text-slate-700 mb-2">Mother's Maiden Name</p>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="mother_last_name" placeholder="GARCIA"
                                               value="<?php echo e(old('mother_last_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="mother_first_name" placeholder="MARIA"
                                               value="<?php echo e(old('mother_first_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="mother_middle_name" placeholder="REYES"
                                               value="<?php echo e(old('mother_middle_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Contact Number</label>
                                        <input type="tel" name="mother_contact" maxlength="11" placeholder="09XXXXXXXXX"
                                               value="<?php echo e(old('mother_contact')); ?>" class="form-input font-mono">
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label class="form-label">Occupation</label>
                                    <input type="text" name="mother_occupation" placeholder="e.g., HOUSEWIFE, NURSE"
                                           value="<?php echo e(old('mother_occupation')); ?>" class="form-input md:w-1/2">
                                </div>
                            </div>

                            <!-- Guardian -->
                            <div class="sub-section">
                                <p class="text-sm font-bold text-slate-700 mb-2">Guardian's Name <span class="text-red-500">*</span></p>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="guardian_last_name" placeholder="DELA CRUZ"
                                               value="<?php echo e(old('guardian_last_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="guardian_first_name" placeholder="JUAN"
                                               value="<?php echo e(old('guardian_first_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Middle Name</label>
                                        <input type="text" name="guardian_middle_name" placeholder="SANTOS"
                                               value="<?php echo e(old('guardian_middle_name')); ?>" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Contact Number <span class="required">*</span></label>
                                        <input type="tel" name="guardian_contact" id="guardianContact" maxlength="11" placeholder="09XXXXXXXXX" required
                                               value="<?php echo e(old('guardian_contact')); ?>" class="form-input font-mono">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2">
                                    <div>
                                        <label class="form-label">Relationship <span class="required">*</span></label>
                                        <select name="guardian_relationship" required class="form-select">
                                            <option value="">Select</option>
                                            <option value="Parent" <?php echo e(old('guardian_relationship') == 'Parent' ? 'selected' : ''); ?>>Parent</option>
                                            <option value="Grandparent" <?php echo e(old('guardian_relationship') == 'Grandparent' ? 'selected' : ''); ?>>Grandparent</option>
                                            <option value="Sibling" <?php echo e(old('guardian_relationship') == 'Sibling' ? 'selected' : ''); ?>>Sibling</option>
                                            <option value="Relative" <?php echo e(old('guardian_relationship') == 'Relative' ? 'selected' : ''); ?>>Relative</option>
                                            <option value="Other" <?php echo e(old('guardian_relationship') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="form-label">Guardian's Full Name (if different from above)</label>
                                        <input type="text" name="guardian_name" placeholder="Full Name for records"
                                               value="<?php echo e(old('guardian_name')); ?>" class="form-input">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 6: FOR RETURNING/TRANSFEREE ========== -->
                    <div id="returningTransfereeSection" class="hidden">
                        <div class="form-section-header">
                            For Returning Learner (Balik-Aral) and Those Who will Transfer/Move In
                            <span id="returningSectionBadge" class="hidden ml-2 px-2 py-0.5 bg-orange-500 text-white text-xs rounded-full">Required</span>
                        </div>
                        <div class="form-section-body space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label">Last Grade Level Completed</label>
                                    <input type="text" name="last_grade_level_completed" placeholder="Grade 3"
                                           value="<?php echo e(old('last_grade_level_completed')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Last School Year Completed</label>
                                    <input type="text" name="last_school_year_completed" placeholder="2024-2025"
                                           value="<?php echo e(old('last_school_year_completed')); ?>" class="form-input">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label">Last School Attended</label>
                                    <input type="text" name="previous_school" id="previousSchoolInput" placeholder="Name of previous school"
                                           value="<?php echo e(old('previous_school')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">School ID</label>
                                    <input type="text" name="previous_school_id" placeholder="6-digit School ID"
                                           value="<?php echo e(old('previous_school_id')); ?>" class="form-input font-mono">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 7: ACCOUNT SETUP ========== -->
                    <div>
                        <div class="form-section-header accent">
                            Account Setup <span class="text-xs font-normal normal-case tracking-normal opacity-80">(For portal login)</span>
                        </div>
                        <div class="form-section-body space-y-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label">Username <span class="required">*</span></label>
                                    <input type="text" name="username" placeholder="juan.dela.cruz" required
                                           value="<?php echo e(old('username')); ?>" class="form-input">
                                </div>
                                <div>
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="email" placeholder="juan@example.com" required
                                           value="<?php echo e(old('email')); ?>" class="form-input">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label">Password <span class="required">*</span></label>
                                    <input type="password" name="password" placeholder="••••••••" required class="form-input">
                                    <div class="mt-1.5 bg-amber-50 border border-amber-200 rounded p-2">
                                        <p class="text-xs text-amber-800">
                                            <span class="font-bold">Requirements:</span> Uppercase, lowercase, number, special character. 
                                            Example: <code class="font-mono font-bold bg-white px-1 rounded">@Password123</code>
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Confirm Password <span class="required">*</span></label>
                                    <input type="password" name="password_confirmation" placeholder="••••••••" required class="form-input">
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Pupil Photo</label>
                                <div class="flex items-center gap-6">
                                    <div class="relative">
                                        <div id="photoPreview" class="w-32 h-32 rounded-full bg-slate-100 border-4 border-white shadow-lg flex items-center justify-center overflow-hidden">
                                            <i class="fas fa-user text-4xl text-slate-300"></i>
                                        </div>
                                        <button type="button" id="removePhoto" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full shadow-md hidden hover:bg-red-600 transition-colors">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <div class="flex-1">
                                        <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/jpg,image/gif" class="hidden" onchange="previewPhoto(this)">
                                        <button type="button" onclick="document.getElementById('photoInput').click()" class="btn-secondary">
                                            <i class="fas fa-upload"></i> Choose Photo
                                        </button>
                                        <p class="input-hint">JPEG, PNG, GIF up to 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 8: REQUIRED DOCUMENTS ========== -->
                    <div>
                        <div class="form-section-header">Required Documents</div>
                        <div class="form-section-body space-y-3">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-2">
                                <p class="text-sm text-blue-800 font-medium" id="documentRequirementsText">
                                    New Pupils: Birth Certificate is required.
                                </p>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3" id="documentsGrid">
                                <!-- Birth Certificate -->
                                <div class="border border-slate-200 rounded-lg p-3" id="birthCertWrapper">
                                    <label class="form-label flex items-center gap-2">
                                        <span id="birthCertLabel">Birth Certificate</span>
                                        <span class="text-xs font-normal" id="birthCertRequired">(Required)</span>
                                    </label>
                                    <div class="mt-1">
                                        <label for="birth_certificate" class="file-upload-area">
                                            <div class="flex flex-col items-center justify-center py-2">
                                                <i class="fas fa-file-upload text-slate-400 text-lg mb-1"></i>
                                                <p class="text-xs text-slate-500"><span class="font-semibold">Click to upload</span></p>
                                            </div>
                                            <input id="birth_certificate" type="file" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'birthCertPreview')" />
                                        </label>
                                    </div>
                                    <div id="birthCertPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>

                                <!-- Report Card -->
                                <div class="border border-slate-200 rounded-lg p-3 hidden" id="reportCardWrapper">
                                    <label class="form-label flex items-center gap-2">
                                        <span id="reportCardLabel">Report Card / Form 138</span>
                                        <span class="text-xs font-normal" id="reportCardRequired">(Optional)</span>
                                    </label>
                                    <div class="mt-1">
                                        <label for="report_card" class="file-upload-area">
                                            <div class="flex flex-col items-center justify-center py-2">
                                                <i class="fas fa-file-upload text-slate-400 text-lg mb-1"></i>
                                                <p class="text-xs text-slate-500"><span class="font-semibold">Click to upload</span></p>
                                            </div>
                                            <input id="report_card" type="file" name="report_card" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'reportCardPreview')" />
                                        </label>
                                    </div>
                                    <div id="reportCardPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>

                                <!-- Good Moral -->
                                <div class="border border-slate-200 rounded-lg p-3 hidden" id="goodMoralWrapper">
                                    <label class="form-label flex items-center gap-2">
                                        <span id="goodMoralLabel">Certificate of Good Moral</span>
                                        <span class="text-xs font-normal" id="goodMoralRequired">(Optional)</span>
                                    </label>
                                    <div class="mt-1">
                                        <label for="good_moral" class="file-upload-area">
                                            <div class="flex flex-col items-center justify-center py-2">
                                                <i class="fas fa-file-upload text-slate-400 text-lg mb-1"></i>
                                                <p class="text-xs text-slate-500"><span class="font-semibold">Click to upload</span></p>
                                            </div>
                                            <input id="good_moral" type="file" name="good_moral" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'goodMoralPreview')" />
                                        </label>
                                    </div>
                                    <div id="goodMoralPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>

                                <!-- Transfer Credentials -->
                                <div class="border border-slate-200 rounded-lg p-3 hidden" id="transferCredWrapper">
                                    <label class="form-label flex items-center gap-2">
                                        <span id="transferCredLabel">Transfer Credentials / Honorable Dismissal</span>
                                        <span class="text-xs font-normal" id="transferCredRequired">(Optional)</span>
                                    </label>
                                    <div class="mt-1">
                                        <label for="transfer_credential" class="file-upload-area">
                                            <div class="flex flex-col items-center justify-center py-2">
                                                <i class="fas fa-file-upload text-slate-400 text-lg mb-1"></i>
                                                <p class="text-xs text-slate-500"><span class="font-semibold">Click to upload</span></p>
                                            </div>
                                            <input id="transfer_credential" type="file" name="transfer_credential" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="previewDocument(this, 'transferCredPreview')" />
                                        </label>
                                    </div>
                                    <div id="transferCredPreview" class="mt-1 hidden flex items-center gap-2 text-xs text-teal-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SECTION 9: REMARKS ========== -->
                    <div>
                        <div class="form-section-header">Remarks <span class="text-xs font-normal normal-case tracking-normal opacity-70">(Optional)</span></div>
                        <div class="form-section-body">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="form-label">Remark Code</label>
                                    <select name="remarks" class="form-select">
                                        <option value="" <?php echo e(old('remarks') == '' ? 'selected' : ''); ?>>-- Select Remark --</option>
                                        <option value="TI" <?php echo e(old('remarks') == 'TI' ? 'selected' : ''); ?>>TI - Transferred In</option>
                                        <option value="TO" <?php echo e(old('remarks') == 'TO' ? 'selected' : ''); ?>>TO - Transferred Out</option>
                                        <option value="DO" <?php echo e(old('remarks') == 'DO' ? 'selected' : ''); ?>>DO - Dropped Out</option>
                                        <option value="LE" <?php echo e(old('remarks') == 'LE' ? 'selected' : ''); ?>>LE - Late Enrollee</option>
                                        <option value="CCT" <?php echo e(old('remarks') == 'CCT' ? 'selected' : ''); ?>>CCT - CCT Recipient</option>
                                        <option value="BA" <?php echo e(old('remarks') == 'BA' ? 'selected' : ''); ?>>BA - Balik Aral</option>
                                        <option value="LWD" <?php echo e(old('remarks') == 'LWD' ? 'selected' : ''); ?>>LWD - Learner With Disability</option>
                                    </select>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 w-full">
                                        <p class="text-xs text-amber-800">Select only if applicable. Multiple selections require admin assistance.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ========== SUBMIT ========== -->
                    <div class="pt-4 pb-8 flex items-center justify-between">
                        <div class="text-sm text-slate-500">
                            Fields marked with <span class="text-red-500">*</span> are required
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="<?php echo e(route('admin.students.index')); ?>" class="btn-secondary lg:hidden">Cancel</a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Save Pupil
                            </button>
                        </div>
                    </div>

                </form>
            </main>
        </div>
    </div>

    <script>
        // Sections data from server
        const allSections = <?php echo $sectionsJson; ?>;
        const oldSectionId = "<?php echo e(old('section_id', request('section_id'))); ?>";

        function updateSections() {
            const gradeLevelId = document.getElementById('gradeLevel').value;
            const sectionSelect = document.getElementById('sectionId');
            sectionSelect.innerHTML = '<option value="">Select Section</option>';
            if (!gradeLevelId) return;
            
            const filteredSections = allSections.filter(s => String(s.grade_level_id) === String(gradeLevelId));
            let autoSelected = false;
            
            filteredSections.forEach(section => {
                const option = document.createElement('option');
                option.value = section.id;
                const isFull = section.capacity && section.student_count >= section.capacity;
                option.textContent = section.name + (isFull ? ' (Full)' : '');
                if (isFull) {
                    option.disabled = true;
                    option.style.color = '#94a3b8';
                }
                if (String(section.id) === String(oldSectionId)) {
                    option.selected = true;
                    autoSelected = true;
                } else if (!autoSelected && !isFull) {
                    option.selected = true;
                    autoSelected = true;
                }
                sectionSelect.appendChild(option);
            });
        }

        // Toggle LRN field based on "With LRN?" radio
        function toggleLrnField() {
            const hasLrn = document.getElementById('hasLrnYes').checked;
            const lrnInput = document.getElementById('lrnInput');
            const lrnRequired = document.getElementById('lrnRequired');
            const lrnHelper = document.getElementById('lrnHelper');
            
            lrnInput.disabled = !hasLrn;
            if (hasLrn) {
                lrnInput.classList.remove('bg-slate-100', 'text-slate-400');
                lrnInput.classList.add('bg-white');
                lrnInput.required = true;
                lrnRequired.classList.remove('hidden');
                lrnHelper.textContent = 'Enter your 12-digit LRN';
                lrnHelper.classList.add('text-teal-600');
            } else {
                lrnInput.classList.add('bg-slate-100', 'text-slate-400');
                lrnInput.classList.remove('bg-white');
                lrnInput.required = false;
                lrnInput.value = '';
                lrnRequired.classList.add('hidden');
                lrnHelper.textContent = 'Select "Yes" for "With LRN?" to enable this field';
                lrnHelper.classList.remove('text-teal-600');
            }
        }

        // Toggle Returning/Transferee section
        function toggleReturningSection() {
            const isBalikAral = document.getElementById('isBalikAralYes').checked;
            const studentType = document.getElementById('studentType').value;
            const returningSection = document.getElementById('returningTransfereeSection');
            const badge = document.getElementById('returningSectionBadge');
            
            const shouldShow = isBalikAral || studentType === 'transferee';
            
            if (shouldShow) {
                returningSection.classList.remove('hidden');
                if (studentType === 'transferee') {
                    badge.classList.remove('hidden');
                    badge.textContent = 'Required';
                } else if (isBalikAral) {
                    badge.classList.remove('hidden');
                    badge.textContent = 'Balik-Aral';
                }
            } else {
                returningSection.classList.add('hidden');
                badge.classList.add('hidden');
                returningSection.querySelectorAll('input:not([type="radio"])').forEach(input => {
                    if (!input.readOnly) input.value = '';
                });
            }
        }

        // Toggle IP specification field
        function toggleIpField() {
            const isIp = document.querySelector('input[name="is_ip"]:checked')?.value === '1';
            const ipField = document.querySelector('input[name="ip_specification"]');
            ipField.disabled = !isIp;
            if (!isIp) ipField.value = '';
        }

        // Toggle 4Ps household ID field
        function toggle4psField() {
            const is4ps = document.querySelector('input[name="is_4ps_beneficiary"]:checked')?.value === '1';
            const hhField = document.querySelector('input[name="household_id_4ps"]');
            hhField.disabled = !is4ps;
            if (!is4ps) hhField.value = '';
        }

        // Toggle permanent address section
        function togglePermanentAddress() {
            const sameAsCurrent = document.querySelector('input[name="same_as_current_address"]:checked')?.value === '1';
            const permSection = document.getElementById('permanentAddressSection');
            if (sameAsCurrent) {
                permSection.classList.add('hidden');
                permSection.querySelectorAll('input:not([readonly])').forEach(input => input.value = '');
            } else {
                permSection.classList.remove('hidden');
            }
        }

        // Main pupil type toggle
        function toggleStudentTypeFields() {
            const typeSelect = document.getElementById('studentType');
            const documentRequirementsText = document.getElementById('documentRequirementsText');
            
            // Document elements
            const birthCertWrapper = document.getElementById('birthCertWrapper');
            const reportCardWrapper = document.getElementById('reportCardWrapper');
            const goodMoralWrapper = document.getElementById('goodMoralWrapper');
            const transferCredWrapper = document.getElementById('transferCredWrapper');
            
            const birthCertRequired = document.getElementById('birthCertRequired');
            const reportCardRequired = document.getElementById('reportCardRequired');
            const goodMoralRequired = document.getElementById('goodMoralRequired');
            const transferCredRequired = document.getElementById('transferCredRequired');
            
            const birthCertInput = document.getElementById('birth_certificate');
            const reportCardInput = document.getElementById('report_card');
            const goodMoralInput = document.getElementById('good_moral');
            const transferCredInput = document.getElementById('transfer_credential');

            function setDocumentState(wrapperId, input, requiredSpan, isRequired, isVisible) {
                const wrapper = document.getElementById(wrapperId);
                if (isVisible) {
                    wrapper.classList.remove('hidden');
                } else {
                    wrapper.classList.add('hidden');
                    input.value = '';
                    const previewId = input.getAttribute('onchange').match(/'([^']+)'/)[1];
                    document.getElementById(previewId)?.classList.add('hidden');
                }
                
                if (isRequired) {
                    input.required = true;
                    requiredSpan.textContent = '(Required)';
                    requiredSpan.className = 'text-xs font-normal text-red-500';
                } else {
                    input.required = false;
                    requiredSpan.textContent = '(Optional)';
                    requiredSpan.className = 'text-xs font-normal text-slate-400';
                }
            }

            if (typeSelect.value === 'new') {
                documentRequirementsText.textContent = 'New Pupils: Birth Certificate is required. Other documents are not needed.';
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, true, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, false, false);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, false, false);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, false, false);
                
                // New pupils typically don't have LRN yet
                document.getElementById('hasLrnNo').checked = true;
                toggleLrnField();
                
            } else if (typeSelect.value === 'transferee') {
                documentRequirementsText.textContent = 'Transferees: Birth Certificate, Report Card, Good Moral Character, and Transfer Credentials are ALL required.';
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, true, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, true, true);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, true, true);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, true, true);
                
                // Transferees usually have LRN from previous school
                document.getElementById('hasLrnYes').checked = true;
                toggleLrnField();
                
            } else {
                // CONTINUING
                documentRequirementsText.textContent = 'Continuing Pupils: All documents are optional. Submit only if available.';
                setDocumentState('birthCertWrapper', birthCertInput, birthCertRequired, false, true);
                setDocumentState('reportCardWrapper', reportCardInput, reportCardRequired, false, true);
                setDocumentState('goodMoralWrapper', goodMoralInput, goodMoralRequired, false, true);
                setDocumentState('transferCredWrapper', transferCredInput, transferCredRequired, false, true);
                
                // Continuing pupils should have LRN
                document.getElementById('hasLrnYes').checked = true;
                toggleLrnField();
            }
            
            toggleReturningSection();
        }

        // Calculate age from birthdate
        function calculateAge() {
            const birthdateInput = document.querySelector('input[name="birthday"]');
            const ageField = document.getElementById('ageField');
            if (!birthdateInput || !ageField) return;
            
            const computeAge = function() {
                if (this.value) {
                    const birth = new Date(this.value);
                    const today = new Date();
                    let age = today.getFullYear() - birth.getFullYear();
                    const monthDiff = today.getMonth() - birth.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) age--;
                    ageField.value = age >= 0 ? age : '';
                } else {
                    ageField.value = '';
                }
            };
            
            birthdateInput.addEventListener('change', computeAge);
            birthdateInput.addEventListener('input', computeAge);
            if (birthdateInput.value) computeAge.call(birthdateInput);
        }

        // LRN input - numeric only, max 12 digits
        document.getElementById('lrnInput')?.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '').slice(0, 12);
        });

        // Photo Preview
        function previewPhoto(input) {
            const preview = document.getElementById('photoPreview');
            const removeBtn = document.getElementById('removePhoto');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    removeBtn.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('removePhoto').addEventListener('click', function() {
            const input = document.getElementById('photoInput');
            const preview = document.getElementById('photoPreview');
            input.value = '';
            preview.innerHTML = '<i class="fas fa-user text-4xl text-slate-300"></i>';
            this.classList.add('hidden');
        });

        // Document Preview
        function previewDocument(input, previewId) {
            const preview = document.getElementById(previewId);
            const fileName = preview.querySelector('.file-name');
            if (input.files && input.files[0]) {
                fileName.textContent = input.files[0].name;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        // Form submission validation
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            const lrnInput = document.getElementById('lrnInput');
            
            // If LRN field is enabled and has value, must be 12 digits
            if (!lrnInput.disabled && lrnInput.value && lrnInput.value.length !== 12) {
                e.preventDefault();
                alert('LRN must be exactly 12 digits');
                lrnInput.focus();
                return false;
            }
        });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            updateSections();
            toggleStudentTypeFields();
            toggleLrnField();
            toggleIpField();
            toggle4psField();
            togglePermanentAddress();
            toggleReturningSection();
            setTimeout(calculateAge, 0);

            // Auto-fill guardian_name from split fields if blank before submission
            document.querySelector('form').addEventListener('submit', function() {
                const guardianNameField = document.querySelector('input[name="guardian_name"]');
                if (!guardianNameField.value.trim()) {
                    const first = document.querySelector('input[name="guardian_first_name"]')?.value || '';
                    const middle = document.querySelector('input[name="guardian_middle_name"]')?.value || '';
                    const last = document.querySelector('input[name="guardian_last_name"]')?.value || '';
                    guardianNameField.value = [first, middle, last].filter(Boolean).join(' ').trim();
                }
            });

            // Numeric restrictions
            document.querySelectorAll('input[name="father_contact"], input[name="mother_contact"], input[name="guardian_contact"]').forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 11);
                });
            });
        });
    </script>
</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/admin/students/create.blade.php ENDPATH**/ ?>