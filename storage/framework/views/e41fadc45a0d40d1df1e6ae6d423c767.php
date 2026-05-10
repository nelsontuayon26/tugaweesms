<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student | Tugawe Elementary</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #f8fafc;
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
            background: #f8fafc;
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

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .form-section {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 40px;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.23);
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

       /* Floating Back Button - Bottom Right */
.floating-back-btn {
    position: fixed;
    right: 32px;
    bottom: 32px;
    top: auto;
    transform: none;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4);
    transition: all 0.3s ease;
    z-index: 100;
    text-decoration: none;
}

.floating-back-btn:hover {
    transform: scale(1.1) rotate(-10deg);
    box-shadow: 0 6px 30px rgba(59, 130, 246, 0.5);
}

.floating-back-btn:active {
    transform: scale(0.95);
}

/* Tooltip for floating button - now above the button */
.floating-back-btn::before {
   
    position: absolute;
    bottom: 70px;
    right: 50%;
    transform: translateX(50%) translateY(10px);
    background: #1e293b;
    color: white;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    font-weight: 600;
    white-space: nowrap;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.floating-back-btn:hover::before {
    opacity: 1;
    visibility: visible;
    transform: translateX(50%) translateY(0);
}

/* Arrow pointing down for tooltip */
.floating-back-btn::after {
    content: '';
    position: absolute;
    bottom: 64px;
    right: 50%;
    transform: translateX(50%);
    border-width: 6px 6px 0;
    border-style: solid;
    border-color: #1e293b transparent transparent;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.floating-back-btn:hover::after {
    opacity: 1;
    visibility: visible;
}

@media (max-width: 1024px) {
    .floating-back-btn {
        right: 24px;
        bottom: 24px;
        width: 56px;
        height: 56px;
        font-size: 1.125rem;
    }
    
    .floating-back-btn::before {
        bottom: 66px;
    }
    
    .floating-back-btn::after {
        bottom: 60px;
    }
}

        /* Success Alert with Countdown */
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

        .alert-content {
            display: flex;
            align-items: center;
            gap: 12px;
            flex: 1;
        }

        .countdown-timer {
            font-size: 0.75rem;
            font-weight: 700;
            color: #15803d;
            background: rgba(255, 255, 255, 0.6);
            padding: 4px 10px;
            border-radius: 20px;
        }

        .mobile-overlay {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(4px);
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }

        .input-with-icon {
            padding-left: 44px;
        }

        .required::after {
            content: '*';
            color: #ef4444;
            margin-left: 4px;
        }

        .input-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
        }

        .lrn-prefix {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            font-weight: 600;
            font-size: 0.875rem;
            pointer-events: none;
        }

        .input-with-prefix {
            padding-left: 70px;
        }

        /* Username field styling */
        .username-hint {
            font-size: 0.75rem;
            color: #3b82f6;
            margin-top: 4px;
            font-weight: 500;
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
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
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
                        <div class="alert-content">
                            <i class="fas fa-check-circle text-2xl text-green-600"></i>
                            <div>
                                <div class="font-bold text-lg"><?php echo e(session('success')); ?></div>
                                <div class="text-sm text-green-700">Redirecting in <span id="countdown">5</span> seconds...</div>
                            </div>
                        </div>
                        <div class="countdown-timer" id="timerBadge">5s</div>
                    </div>

                    <script>
                        // Countdown timer for redirect
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

                <form action="<?php echo e(route('admin.students.store')); ?>" method="POST" enctype="multipart/form-data" class="glass-card p-8 animate-fade-in-up" id="studentForm">
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


 <!-- Enrollment Information Section -->
<div class="form-section">
    <div class="section-title">
        <div class="section-icon bg-rose-50 text-rose-600">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <span>Enrollment Information</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Grade Level -->
        <div>
            <label class="form-label required">Grade Level</label>
            <select name="grade_level_id" id="gradeLevel" class="form-select" required onchange="updateSections()">
                <option value="">Select Grade Level</option>
                <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($level->id); ?>" <?php echo e(old('grade_level_id') == $level->id ? 'selected' : ''); ?>><?php echo e($level->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <p class="input-hint">Select current grade level for enrollment</p>
            <?php $__errorArgs = ['grade_level_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- LRN -->
                            <div>
                                <label class="form-label">LRN (Learner Reference Number)</label>
                                <div class="input-group">
                                    <span class="lrn-prefix">120231</span>
                                    <input 
                                        type="text" 
                                        name="lrn_suffix" 
                                        id="lrnInput"
                                        class="form-input input-with-prefix" 
                                        placeholder="XXXXXX"
                                        maxlength="6"
                                        pattern="\d{6}"
                                        inputmode="numeric"
                                        title="Please enter exactly 6 numbers"
                                        oninput="validateLRN(this)"
                                        value="<?php echo e(old('lrn_suffix')); ?>"
                                    >
                                </div>
                                <p class="input-hint">Enter last 6 digits only (12 digits total)</p>
                            </div>

        <!-- Section -->
        <div>
            <label class="form-label required">Section</label>
            <select name="section_id" id="sectionId" class="form-select" required>
                <option value="">Select Section</option>
            </select>
            <p class="input-hint">Select section for enrollment</p>
            <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Student Type -->
        <div>
            <label class="form-label required">Student Type</label>
            <select name="type" id="studentType" class="form-select" required onchange="togglePreviousSchool()">
                <option value="">Select Type</option>
                <option value="new" <?php echo e(old('type') == 'new' ? 'selected' : ''); ?>>New Student</option>
                <option value="continuing" <?php echo e(old('type') == 'continuing' ? 'selected' : ''); ?>>Continuing Student</option>
                <option value="transferee" <?php echo e(old('type') == 'transferee' ? 'selected' : ''); ?>>Transferee</option>
            </select>
            <p class="input-hint">Select based on pupil's enrollment status</p>
            <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Previous School (for Transferees) -->
        <div id="previousSchoolContainer" class="hidden">
            <label class="form-label required">Previous School</label>
            <div class="input-group">
                <i class="fas fa-school input-icon"></i>
                <input 
                    type="text" 
                    name="previous_school" 
                    id="previousSchoolInput"
                    class="form-input input-with-icon" 
                    placeholder="Name of previous school"
                    value="<?php echo e(old('previous_school')); ?>"
                >
            </div>
            <p class="input-hint">Required for transferee students</p>
            <?php $__errorArgs = ['previous_school'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
<script>
    // Sections data from server
    const allSections = <?php echo $sectionsJson; ?>;
    const oldSectionId = "<?php echo e(old('section_id', request('section_id'))); ?>";

    function updateSections() {
        const gradeLevelId = document.getElementById('gradeLevel').value;
        const sectionSelect = document.getElementById('sectionId');
        
        // Clear current options
        sectionSelect.innerHTML = '<option value="">Select Section</option>';
        
        if (!gradeLevelId) return;
        
        // Filter sections by grade level
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

    function togglePreviousSchool() {
        const typeSelect = document.getElementById('studentType');
        const previousSchoolContainer = document.getElementById('previousSchoolContainer');
        const previousSchoolInput = document.getElementById('previousSchoolInput');
        const lrnPrefix = document.querySelector('.lrn-prefix');
        const lrnInput = document.getElementById('lrnInput');
        const lrnHint = lrnInput.closest('div').parentElement.querySelector('.input-hint');
        
        // Document elements
        const documentRequirementsText = document.getElementById('documentRequirementsText');
        const birthCertLabel = document.getElementById('birthCertLabel');
        const birthCertStatus = document.getElementById('birthCertStatus');
        const birthCertInput = document.getElementById('birth_certificate');
        const reportCardInput = document.getElementById('report_card');
        const goodMoralInput = document.getElementById('good_moral');
        const transferCredInput = document.getElementById('transfer_credential');
        
        function setDocRequired(input, labelSpan, isRequired) {
            if (isRequired) {
                input.required = true;
                if (labelSpan) labelSpan.textContent = '(Required)';
            } else {
                input.required = false;
                if (labelSpan) labelSpan.textContent = '(Optional)';
            }
        }
        
        if (typeSelect.value === 'transferee') {
            previousSchoolContainer.classList.remove('hidden');
            previousSchoolInput.required = true;
            // Remove prefix for transferees - they have their own LRN format
            if (lrnPrefix) {
                lrnPrefix.style.display = 'none';
                lrnInput.classList.remove('input-with-prefix');
                lrnInput.placeholder = 'Enter full LRN from previous school';
                lrnInput.maxLength = 12;
                lrnInput.removeAttribute('pattern');
                lrnInput.title = 'Enter the full LRN from the previous school';
            }
            if (lrnHint) {
                lrnHint.textContent = 'Enter the full LRN from the previous school';
            }
            // Documents for transferees
            if (documentRequirementsText) {
                documentRequirementsText.innerHTML = '<i class="fas fa-info-circle mr-2"></i>Transferees: Report Card, Good Moral, and Transfer Credentials are required. Birth Certificate is optional.';
            }
            if (birthCertLabel) birthCertLabel.childNodes[0].textContent = 'Birth Certificate (Optional) ';
            setDocRequired(birthCertInput, birthCertStatus, false);
            setDocRequired(reportCardInput, reportCardInput.closest('div').querySelector('.doc-optional'), true);
            setDocRequired(goodMoralInput, goodMoralInput.closest('div').querySelector('.doc-optional'), false);
            setDocRequired(transferCredInput, transferCredInput.closest('div').querySelector('.doc-optional'), true);
        } else if (typeSelect.value === 'new') {
            previousSchoolContainer.classList.add('hidden');
            previousSchoolInput.required = false;
            previousSchoolInput.value = '';
            // Restore prefix for new students
            if (lrnPrefix) {
                lrnPrefix.style.display = '';
                lrnInput.classList.add('input-with-prefix');
                lrnInput.placeholder = 'XXXXXX';
                lrnInput.maxLength = 6;
                lrnInput.setAttribute('pattern', '\\d{6}');
                lrnInput.title = 'Please enter exactly 6 numbers';
            }
            if (lrnHint) {
                lrnHint.textContent = 'Enter last 6 digits only (12 digits total)';
            }
            if (lrnInput.value.length > 6) {
                lrnInput.value = lrnInput.value.slice(0, 6);
            }
            // Documents for new students
            if (documentRequirementsText) {
                documentRequirementsText.innerHTML = '<i class="fas fa-info-circle mr-2"></i>New Pupils: Birth Certificate and Report Card are required. Good Moral is optional.';
            }
            if (birthCertLabel) birthCertLabel.childNodes[0].textContent = 'Birth Certificate ';
            setDocRequired(birthCertInput, birthCertStatus, true);
            setDocRequired(reportCardInput, reportCardInput.closest('div').querySelector('.doc-optional'), true);
            setDocRequired(goodMoralInput, goodMoralInput.closest('div').querySelector('.doc-optional'), false);
            setDocRequired(transferCredInput, transferCredInput.closest('div').querySelector('.doc-optional'), false);
        } else {
            // Continuing students
            previousSchoolContainer.classList.add('hidden');
            previousSchoolInput.required = false;
            previousSchoolInput.value = '';
            // Restore prefix for continuing students
            if (lrnPrefix) {
                lrnPrefix.style.display = '';
                lrnInput.classList.add('input-with-prefix');
                lrnInput.placeholder = 'XXXXXX';
                lrnInput.maxLength = 6;
                lrnInput.setAttribute('pattern', '\\d{6}');
                lrnInput.title = 'Please enter exactly 6 numbers';
            }
            if (lrnHint) {
                lrnHint.textContent = 'Enter last 6 digits only (12 digits total)';
            }
            if (lrnInput.value.length > 6) {
                lrnInput.value = lrnInput.value.slice(0, 6);
            }
            // Documents for continuing students
            if (documentRequirementsText) {
                documentRequirementsText.innerHTML = '<i class="fas fa-info-circle mr-2"></i>Continuing Pupils: All documents are optional.';
            }
            if (birthCertLabel) birthCertLabel.childNodes[0].textContent = 'Birth Certificate ';
            setDocRequired(birthCertInput, birthCertStatus, false);
            setDocRequired(reportCardInput, reportCardInput.closest('div').querySelector('.doc-optional'), false);
            setDocRequired(goodMoralInput, goodMoralInput.closest('div').querySelector('.doc-optional'), false);
            setDocRequired(transferCredInput, transferCredInput.closest('div').querySelector('.doc-optional'), false);
        }
    }
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSections();
        togglePreviousSchool();
    });
</script>


                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-blue-50 text-blue-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>Basic Information</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- First Name -->
                            <div>
                                <label class="form-label required">First Name</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="first_name" class="form-input input-with-icon" placeholder="First name" value="<?php echo e(old('first_name')); ?>" required>
                                </div>
                            </div>

                             <!-- Middle Name -->
                            <div>
                                <label class="form-label">Middle Name</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="middle_name" class="form-input input-with-icon" placeholder="Middle name" value="<?php echo e(old('middle_name')); ?>">
                                </div>
                            </div>

                            <!-- Last Name -->
                            <div>
                                <label class="form-label required">Last Name</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="last_name" class="form-input input-with-icon" placeholder="Last name" value="<?php echo e(old('last_name')); ?>" required>
                                </div>
                            </div>

                            <!-- Suffix -->
                            <div>
                                <label class="form-label">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="" <?php echo e(old('suffix') == '' ? 'selected' : ''); ?>>None</option>
                                    <option value="Jr." <?php echo e(old('suffix') == 'Jr.' ? 'selected' : ''); ?>>Jr.</option>
                                    <option value="Sr." <?php echo e(old('suffix') == 'Sr.' ? 'selected' : ''); ?>>Sr.</option>
                                    <option value="II" <?php echo e(old('suffix') == 'II' ? 'selected' : ''); ?>>II</option>
                                    <option value="III" <?php echo e(old('suffix') == 'III' ? 'selected' : ''); ?>>III</option>
                                    <option value="IV" <?php echo e(old('suffix') == 'IV' ? 'selected' : ''); ?>>IV</option>
                                </select>
                            </div>

                            <!-- Birthdate -->
                            <div>
                                <label class="form-label required">Birthdate</label>
                                <div class="input-group">
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input 
                                        type="date" 
                                        name="birthday" 
                                        class="form-input input-with-icon"
                                        min="1900-01-01"
                                        max="<?php echo e(date('Y-m-d')); ?>"
                                        value="<?php echo e(old('birthday')); ?>"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Birth Place -->
                            <div>
                                <label class="form-label required">Birth Place</label>
                                <div class="input-group">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" name="birth_place" class="form-input input-with-icon" placeholder="City, Province" value="<?php echo e(old('birth_place')); ?>" required>
                                </div>
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="form-label required">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?php echo e(old('gender') == 'Male' ? 'selected' : ''); ?>>Male</option>
                                    <option value="Female" <?php echo e(old('gender') == 'Female' ? 'selected' : ''); ?>>Female</option>
                                    <option value="Other" <?php echo e(old('gender') == 'Other' ? 'selected' : ''); ?>>Other</option>
                                </select>
                            </div>

                            <!-- Nationality -->
                            <div>
                                <label class="form-label required">Nationality</label>
                                <div class="input-group">
                                    <i class="fas fa-globe input-icon"></i>
                                    <input type="text" name="nationality" class="form-input input-with-icon" placeholder="e.g., Filipino" value="<?php echo e(old('nationality')); ?>" required>
                                </div>
                            </div>

                            <!-- Religion -->
                            <div>
                                <label class="form-label required">Religion</label>
                                <div class="input-group">
                                    <i class="fas fa-praying-hands input-icon"></i>
                                    <input type="text" name="religion" class="form-input input-with-icon" placeholder="e.g., Roman Catholic" value="<?php echo e(old('religion')); ?>" required>
                                </div>
                            </div>

                            <!-- Ethnicity -->
                            <div>
                                <label class="form-label required">Ethnicity</label>
                                <div class="input-group">
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="text" name="ethnicity" class="form-input input-with-icon" placeholder="e.g., Cebuano, Tagalog" value="<?php echo e(old('ethnicity')); ?>" required>
                                </div>
                            </div>

                            <!-- Mother Tongue -->
                            <div>
                                <label class="form-label required">Mother Tongue</label>
                                <div class="input-group">
                                    <i class="fas fa-language input-icon"></i>
                                    <input type="text" name="mother_tongue" class="form-input input-with-icon" placeholder="e.g., Cebuano, Filipino" value="<?php echo e(old('mother_tongue')); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>


                   

                    <!-- Family Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-emerald-50 text-emerald-600">
                                <i class="fas fa-users"></i>
                            </div>
                            <span>Family Information</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Father -->
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-male text-blue-500"></i>
                                    Father's Information
                                </h4>
                            </div>
                            
                            <div>
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="father_name" class="form-input" placeholder="Full name" value="<?php echo e(old('father_name')); ?>">
                            </div>

                            <div>
                                <label class="form-label">Father's Occupation</label>
                                <input type="text" name="father_occupation" class="form-input" placeholder="e.g., Farmer, Teacher, OFW" value="<?php echo e(old('father_occupation')); ?>">
                            </div>

                            <!-- Mother -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-female text-pink-500"></i>
                                    Mother's Information
                                </h4>
                            </div>

                            <div>
                                <label class="form-label">Mother's Maiden Name</label>
                                <input type="text" name="mother_name" class="form-input" placeholder="Full name" value="<?php echo e(old('mother_name')); ?>">
                            </div>

                            <div>
                                <label class="form-label">Mother's Occupation</label>
                                <input type="text" name="mother_occupation" class="form-input" placeholder="e.g., Housewife, Teacher, OFW" value="<?php echo e(old('mother_occupation')); ?>">
                            </div>

                            <!-- Guardian -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-amber-500"></i>
                                    Guardian's Information (if applicable)
                                </h4>
                            </div>

                            <div>
                                <label class="form-label required">Guardian's Name</label>
                                <input type="text" name="guardian_name" class="form-input" placeholder="Full name" value="<?php echo e(old('guardian_name')); ?>" required>
                            </div>

                            <div>
                                <label class="form-label required">Relationship to Student</label>
                                <input type="text" name="guardian_relationship" class="form-input" placeholder="e.g., Grandmother, Uncle" value="<?php echo e(old('guardian_relationship')); ?>" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="form-label">Guardian's Contact Number</label>
                                <div class="input-group">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input 
                                        type="tel" 
                                        name="guardian_contact" 
                                        id="contactInput"
                                        class="form-input input-with-icon" 
                                        placeholder="09XX XXX XXXX"
                                        maxlength="11"
                                        pattern="[0-9]{11}"
                                        inputmode="numeric"
                                        value="<?php echo e(old('guardian_contact')); ?>"
                                    >
                                </div>
                                <p class="input-hint">Optional. Must be 11 digits (e.g., 09123456789)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-amber-50 text-amber-600">
                                <i class="fas fa-home"></i>
                            </div>
                            <span>Address</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="form-label">Street Address</label>
                                <div class="input-group">
                                    <i class="fas fa-road input-icon"></i>
                                    <input type="text" name="street_address" class="form-input input-with-icon" placeholder="House number, Street name" value="<?php echo e(old('street_address')); ?>">
                                </div>
                            </div>

                            <div>
                                <label class="form-label">Barangay</label>
                                <input type="text" name="barangay" class="form-input" placeholder="Barangay name" value="<?php echo e(old('barangay')); ?>">
                            </div>

                            <div>
                                <label class="form-label">City / Municipality</label>
                                <input type="text" name="city" class="form-input" placeholder="City name" value="<?php echo e(old('city')); ?>">
                            </div>

                            <div>
                                <label class="form-label">Province</label>
                                <input type="text" name="province" class="form-input" placeholder="Province name" value="<?php echo e(old('province')); ?>">
                            </div>

                            <div>
                                <label class="form-label">Zip Code</label>
                                <div class="input-group">
                                    <i class="fas fa-mail-bulk input-icon"></i>
                                    <input type="text" name="zip_code" class="form-input input-with-icon" placeholder="4-digit code" value="<?php echo e(old('zip_code')); ?>">
                                </div>
                            </div>
                        </div>
                    </div>


                     <!-- Account Information Section -->
<div class="form-section">
    <div class="section-title">
        <div class="section-icon bg-indigo-50 text-indigo-600">
            <i class="fas fa-id-card"></i>
        </div>
        <span>Account Information</span>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Username -->
        <div>
            <label class="form-label required">Username</label>
            <div class="input-group">
                <i class="fas fa-user-circle input-icon"></i>
                <input 
                    type="text" 
                    name="username" 
                    id="usernameInput"
                    class="form-input input-with-icon" 
                    placeholder="Enter unique username"
                    value="<?php echo e(old('username')); ?>"
                    required
                    minlength="4"
                    maxlength="20"
                    pattern="[a-zA-Z0-9_]+"
                    oninput="validateUsername(this)"
                >
            </div>
            <p class="username-hint">4-20 characters, letters, numbers and underscores only</p>
            <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Email -->
        <div>
            <label class="form-label required">Email</label>
            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input 
                    type="email" 
                    name="email" 
                    class="form-input input-with-icon" 
                    placeholder="student@tugaweelem.edu"
                    value="<?php echo e(old('email')); ?>"
                    required
                >
            </div>
            <p class="input-hint">Valid email address required for account activation</p>
            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Password -->
        <div>
            <label class="form-label required">Password</label>
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    name="password" 
                    id="passwordInput"
                    class="form-input input-with-icon" 
                    placeholder="Enter password"
                    required
                    minlength="8"
                    oninput="validatePassword(this)"
                >
                <button type="button" onclick="togglePassword('passwordInput', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <div class="mt-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <i class="fas fa-info-circle text-amber-500 mt-0.5 text-xs"></i>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <p class="font-semibold mb-1">Password must contain:</p>
                        <div class="flex flex-wrap gap-x-3 gap-y-1">
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Uppercase</span>
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Lowercase</span>
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Number</span>
                            <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Special character</span>
                        </div>
                        <p class="mt-1.5 text-amber-700">Example: <code class="bg-white px-1.5 py-0.5 rounded text-amber-600 font-mono font-bold border border-amber-200">@Password123</code></p>
                    </div>
                </div>
            </div>
            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="form-label required">Confirm Password</label>
            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="confirmPasswordInput"
                    class="form-input input-with-icon" 
                    placeholder="Confirm password"
                    required
                    oninput="validateMatch()"
                >
                <button type="button" onclick="togglePassword('confirmPasswordInput', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <p class="input-hint" id="matchHint">Passwords must match</p>
            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</div>
<script>
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

// Password validation (basic strength check)
function validatePassword(input) {
    validateMatch();
}

// Check if passwords match
function validateMatch() {
    const password = document.getElementById('passwordInput').value;
    const confirm = document.getElementById('confirmPasswordInput').value;
    const hint = document.getElementById('matchHint');
    const confirmInput = document.getElementById('confirmPasswordInput');
    
    if (confirm && password !== confirm) {
        hint.textContent = 'Passwords do not match!';
        hint.style.color = '#ef4444';
        confirmInput.style.borderColor = '#ef4444';
    } else if (confirm && password === confirm) {
        hint.textContent = 'Passwords match!';
        hint.style.color = '#22c55e';
        confirmInput.style.borderColor = '#22c55e';
    } else {
        hint.textContent = 'Passwords must match';
        hint.style.color = '#64748b';
        confirmInput.style.borderColor = '#e2e8f0';
    }
}
</script>


                    <!-- Photo Upload Section -->
<div class="form-section">
    <div class="section-title">
        <div class="section-icon bg-purple-50 text-purple-600">
            <i class="fas fa-camera"></i>
        </div>
        <span>Profile Photo</span>
    </div>
    
    <div class="flex items-center gap-6">
        <!-- Preview Container -->
        <div class="relative">
            <div id="photoPreview" class="w-32 h-32 rounded-full bg-slate-100 border-4 border-white shadow-lg flex items-center justify-center overflow-hidden">
                <i class="fas fa-user text-4xl text-slate-300"></i>
            </div>
            <button type="button" id="removePhoto" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full shadow-md hidden hover:bg-red-600 transition-colors">            </button>
        </div>

        <div class="flex-1">
            <label class="form-label">Upload Photo</label>
            <div class="relative">
                <input 
                    type="file" 
                    name="photo" 
                    id="photoInput"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="hidden"
                    onchange="previewPhoto(this)"
                >
                <button 
                    type="button" 
                    onclick="document.getElementById('photoInput').click()"
                    class="btn-secondary w-full md:w-auto"
                >
                    <i class="fas fa-upload"></i>
                    Choose Photo
                </button>
                <p class="input-hint mt-2">JPEG, PNG, GIF up to 2MB</p>
            </div>
            <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
</div>
<script>
    // Photo Preview Function
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

// Remove Photo
document.getElementById('removePhoto').addEventListener('click', function() {
    const input = document.getElementById('photoInput');
    const preview = document.getElementById('photoPreview');
    
    input.value = '';
    preview.innerHTML = '<i class="fas fa-user text-4xl text-slate-300"></i>';
    this.classList.add('hidden');
});
</script>


                    <!-- Remarks Section -->
<div class="form-section">
    <div class="section-title">
        <div class="section-icon bg-gray-50 text-gray-600">
            <i class="fas fa-sticky-note"></i>
        </div>
        <span>Remarks</span>
    </div>
    
    <div>
        <label class="form-label">Student Remark</label>
        <select name="remarks" class="form-select">
            <option value="">Select Remark (Optional)</option>
            <?php $__currentLoopData = \App\Models\Student::$remarksLegend; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $description): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($code); ?>" <?php echo e(old('remarks') == $code ? 'selected' : ''); ?>><?php echo e($code); ?> - <?php echo e($description); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <p class="input-hint">Select a remark code for this pupil's status</p>
    </div>
</div>

                    <!-- Documents -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-indigo-50 text-indigo-600">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <span>Documents</span>
                        </div>
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 mb-4">
                            <p class="text-sm text-blue-800" id="documentRequirementsText">
                                <i class="fas fa-info-circle mr-2"></i>
                                New Students: Birth Certificate, Report Card, and Good Moral Certificate are required.
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label" id="birthCertLabel">Birth Certificate <span class="text-xs text-slate-400" id="birthCertStatus">(Optional)</span></label>
                                <input type="file" name="birth_certificate" id="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <div>
                                <label class="form-label">Report Card / Form 138 <span class="text-xs text-slate-400 doc-optional">(Optional)</span></label>
                                <input type="file" name="report_card" id="report_card" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <div>
                                <label class="form-label">Certificate of Good Moral <span class="text-xs text-slate-400 doc-optional">(Optional)</span></label>
                                <input type="file" name="good_moral" id="good_moral" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                            <div>
                                <label class="form-label">Transfer Credentials <span class="text-xs text-slate-400 doc-optional">(Optional)</span></label>
                                <input type="file" name="transfer_credential" id="transfer_credential" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-4">
                        <div class="text-sm text-slate-500">
                            Fields marked with <span class="text-red-500">*</span> are required
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="<?php echo e(route('admin.students.index')); ?>" class="btn-secondary lg:hidden">
                                Cancel
                            </a>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Save Student
                            </button>
                        </div>
                    </div>

                </form>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('mobileOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('hidden');
            }
        }

        // Username Validation - Only letters, numbers, and underscores
        function validateUsername(input) {
            // Remove any characters that are not letters, numbers, or underscores
            input.value = input.value.replace(/[^a-zA-Z0-9_]/g, '');
            
            // Convert to lowercase
            input.value = input.value.toLowerCase();
        }

   
        // LRN Validation - Only numbers, exactly 6 digits for new/continuing, 12 for transferees
        function validateLRN(input) {
            const studentType = document.getElementById('studentType').value;
            
            // Remove any non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');
            
            if (studentType === 'transferee') {
                // Transferees can have up to 12 digits
                if (input.value.length > 12) {
                    input.value = input.value.slice(0, 12);
                }
                // Visual feedback - red border if not 12 digits
                if (input.value.length > 0 && input.value.length !== 12) {
                    input.style.borderColor = '#ef4444';
                } else {
                    input.style.borderColor = '#e2e8f0';
                }
            } else {
                // Limit to 6 digits for new/continuing
                if (input.value.length > 6) {
                    input.value = input.value.slice(0, 6);
                }
                // Visual feedback - red border if not 6 digits
                if (input.value.length > 0 && input.value.length !== 6) {
                    input.style.borderColor = '#ef4444';
                } else {
                    input.style.borderColor = '#e2e8f0';
                }
            }
        }

        // Contact Number Validation - Only numbers, exactly 11 digits, must start with 09
        function validateContact(input) {
            // Remove any non-numeric characters
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Limit to 11 digits
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11);
            }
            
            // Ensure starts with 09
            if (input.value.length >= 2 && !input.value.startsWith('09')) {
                input.value = '09' + input.value.slice(2);
            }
        }


        
// Form submission validation
document.getElementById('studentForm').addEventListener('submit', function(e) {
    const lrnInput = document.getElementById('lrnInput');
    const contactInput = document.getElementById('contactInput');
    const usernameInput = document.getElementById('usernameInput');
    const passwordInput = document.getElementById('passwordInput');
    const confirmInput = document.getElementById('confirmPasswordInput');
    
    // Validate Username
    if (usernameInput.value.length < 4) {
        e.preventDefault();
        alert('Username must be at least 4 characters long');
        usernameInput.focus();
        return false;
    }
    
    // Validate Password length
    if (passwordInput.value.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long');
        passwordInput.focus();
        return false;
    }
    
    // Validate Password match
    if (passwordInput.value !== confirmInput.value) {
        e.preventDefault();
        alert('Passwords do not match!');
        confirmInput.focus();
        return false;
    }
    
    // Validate LRN based on student type
    const studentType = document.getElementById('studentType').value;
    if (lrnInput.value) {
        if (studentType === 'transferee' && lrnInput.value.length !== 12) {
            e.preventDefault();
            alert('LRN must be exactly 12 digits for transferees');
            lrnInput.focus();
            return false;
        } else if (studentType !== 'transferee' && lrnInput.value.length !== 6) {
            e.preventDefault();
            alert('LRN must be exactly 6 digits');
            lrnInput.focus();
            return false;
        }
    }
    
    // Validate contact
    if (contactInput.value && contactInput.value.length !== 11) {
        e.preventDefault();
        alert('Contact number must be exactly 11 digits');
        contactInput.focus();
        return false;
    }
    
});
        // Auto-hide success message after 5 seconds (backup)
        setTimeout(function() {
            const alert = document.getElementById('successAlert');
            if (alert) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    if (alert && alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }, 5000);
    </script>
</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\students\create.blade.php ENDPATH**/ ?>