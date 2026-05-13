

<?php $__env->startSection('title', 'School Year Management'); ?>

<?php $__env->startSection('header-title', 'School Year Management'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .glass-card {
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    @keyframes fadeInUp { 
        from { opacity: 0; transform: translateY(20px); } 
        to { opacity: 1; transform: translateY(0); } 
    }
    .animate-fade-in-up { 
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
    }
</style>

<div class="max-w-7xl mx-auto" id="schoolYearPage">
            <!-- Page Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-slate-900">School Year Management</h2>
                        <p class="text-slate-500 mt-1">Manage school years, generate enrollment QR codes, and control enrollment periods.</p>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <?php if(session('success')): ?>
            <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex items-start gap-4 animate-fade-in-up">
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-emerald-900 text-lg">Success</h3>
                    <p class="text-emerald-700"><?php echo e(session('success')); ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
            <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-5 flex items-start gap-4 animate-fade-in-up">
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-rose-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-rose-900 text-lg">Error</h3>
                    <p class="text-rose-700"><?php echo e(session('error')); ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>

            <?php if(session('warning')): ?>
            <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4 animate-fade-in-up">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-amber-900 text-lg">Warning</h3>
                    <p class="text-amber-700"><?php echo e(session('warning')); ?></p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-400 hover:text-amber-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <?php endif; ?>

            <!-- Active School Year & QR Code Section -->
            <?php if(session('qr_code') && isset($activeSchoolYear)): ?>
            <div class="glass-card rounded-3xl p-8 mb-8 animate-fade-in-up">
                <div class="flex flex-col lg:flex-row items-start justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                            <i class="fas fa-qrcode text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900"><?php echo e($activeSchoolYear->name); ?></h3>
                            <p class="text-sm text-slate-500">Enrollment QR Code Generated</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold shadow-sm">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        ACTIVE
                    </span>
                </div>
                
                <div class="flex flex-col xl:flex-row items-start gap-8">
                    <!-- QR Code Image -->
                    <div class="flex-shrink-0 bg-slate-50 p-6 rounded-2xl border-2 border-dashed border-slate-200">
                        <img src="<?php echo e(Storage::url(session('qr_code')->qr_code_image_path)); ?>" 
                             alt="Enrollment QR Code" 
                             class="w-56 h-56 object-contain">
                    </div>
                    
                    <!-- QR Code Info & Actions -->
                    <div class="flex-1 space-y-5 w-full min-w-0">
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-link text-blue-500"></i>
                                Enrollment URL
                            </p>
                            <div class="flex gap-3">
                                <code class="flex-1 bg-slate-800 text-emerald-400 px-4 py-3.5 rounded-xl text-sm break-all font-mono min-w-0">
                                    <?php echo e(route('admin.enrollment.form.qr', ['token' => session('qr_code')->qr_code_token])); ?>

                                </code>
                                <button onclick="copyToClipboard('<?php echo e(route('admin.enrollment.form.qr', ['token' => session('qr_code')->qr_code_token])); ?>', this)" 
                                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2 font-semibold whitespace-nowrap"
                                        title="Copy URL">
                                    <i class="fas fa-copy"></i>
                                    <span class="hidden sm:inline">Copy</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="<?php echo e(route('admin.school-year.download-qr', session('qr_code'))); ?>" 
                               class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-download mr-2"></i>Download QR
                            </a>
                            
                            <form action="<?php echo e(route('admin.school-year.regenerate-qr')); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-xl hover:-translate-y-0.5"
                                        onclick="return confirm('Regenerate QR code? Old QR codes will be invalidated.')">
                                    <i class="fas fa-sync mr-2"></i>Regenerate
                                </button>
                            </form>

                            <button type="button"
                                    onclick="showEndSchoolYearModal()"
                                    class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-rose-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-stop-circle mr-2"></i>End School Year
                            </button>

                            <button type="button"
                                    onclick="showCarryForwardModal()"
                                    class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-teal-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-clone mr-2"></i>Carry Forward Sections
                            </button>
                        </div>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fas fa-info-circle text-blue-600"></i>
                                </div>
                                <p class="text-sm text-blue-800 leading-relaxed">
                                    Students can scan this QR code or visit the URL to submit their enrollment application. Download and print this QR code for physical distribution.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif(isset($activeSchoolYear) && $activeSchoolYear->qrCode): ?>
            <!-- Show existing QR code if school year is active but page was refreshed -->
            <div class="glass-card rounded-3xl p-8 mb-8 animate-fade-in-up">
                <div class="flex flex-col lg:flex-row items-start justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                            <i class="fas fa-qrcode text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-slate-900"><?php echo e($activeSchoolYear->name); ?></h3>
                            <p class="text-sm text-slate-500">Existing QR Code</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold shadow-sm">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        ACTIVE
                    </span>
                </div>
                
                <div class="flex flex-col xl:flex-row items-start gap-8">
                    <div class="flex-shrink-0 bg-slate-50 p-6 rounded-2xl border-2 border-dashed border-slate-200">
                        <img src="<?php echo e(Storage::url($activeSchoolYear->qrCode->qr_code_image_path)); ?>" 
                             alt="Enrollment QR Code" 
                             class="w-56 h-56 object-contain">
                    </div>
                    
                    <div class="flex-1 space-y-5 w-full min-w-0">
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-link text-blue-500"></i>
                                Enrollment URL
                            </p>
                            <div class="flex gap-3">
                                <code class="flex-1 bg-slate-800 text-emerald-400 px-4 py-3.5 rounded-xl text-sm break-all font-mono min-w-0">
                                    <?php echo e(route('admin.enrollment.form.qr', ['token' => $activeSchoolYear->qrCode->qr_code_token])); ?>

                                </code>
                                <button onclick="copyToClipboard('<?php echo e(route('admin.enrollment.form.qr', ['token' => $activeSchoolYear->qrCode->qr_code_token])); ?>', this)" 
                                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-all shadow-lg shadow-blue-500/30 flex items-center gap-2 font-semibold whitespace-nowrap"
                                        title="Copy URL">
                                    <i class="fas fa-copy"></i>
                                    <span class="hidden sm:inline">Copy</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap gap-3">
                            <a href="<?php echo e(route('admin.school-year.download-qr', $activeSchoolYear->qrCode)); ?>" 
                               class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-download mr-2"></i>Download QR
                            </a>
                            
                            <form action="<?php echo e(route('admin.school-year.regenerate-qr')); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
                                <button type="submit" 
                                        class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-xl hover:-translate-y-0.5"
                                        onclick="return confirm('Regenerate QR code? Old QR codes will be invalidated.')">
                                    <i class="fas fa-sync mr-2"></i>Regenerate
                                </button>
                            </form>

                            <button type="button"
                                    onclick="showEndSchoolYearModal()"
                                    class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-rose-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-stop-circle mr-2"></i>End School Year
                            </button>

                            <button type="button"
                                    onclick="showCarryForwardModal()"
                                    class="inline-flex items-center px-6 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-teal-500/30 hover:shadow-xl hover:-translate-y-0.5">
                                <i class="fas fa-clone mr-2"></i>Carry Forward Sections
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif(isset($activeSchoolYear)): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 mb-8 animate-fade-in-up">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-amber-600 text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-amber-900 text-lg">Active School Year: <?php echo e($activeSchoolYear->name); ?></h3>
                        <p class="text-amber-700">No QR code generated yet. Generate one to enable student enrollment.</p>
                    </div>
                    <form action="<?php echo e(route('admin.school-year.regenerate-qr')); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
                        <button type="submit" 
                                class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/30 hover:shadow-xl hover:-translate-y-0.5 whitespace-nowrap"
                                onclick="return confirm('Generate QR code for enrollment?')">
                            <i class="fas fa-magic mr-2"></i>Generate QR
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- School Years List -->
            <div class="glass-card rounded-3xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-list text-indigo-600"></i>
                        </div>
                        <h3 class="font-bold text-xl text-slate-900">All School Years</h3>
                    </div>
                   <div class="flex gap-3">
                <?php if($activeSchoolYear): ?>
                <a href="<?php echo e(route('admin.school-year.closure')); ?>" 
                   class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-rose-500/30 hover:shadow-xl hover:-translate-y-0.5">
                    <i class="fas fa-calendar-check mr-2"></i>Closure Dashboard
                </a>
                <?php endif; ?>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50/80">
                            <tr>
                                <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">School Year</th>
                                <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">End Date</th>
                                <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-8 py-5 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            <?php $__empty_1 = true; $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $yearData = json_encode([
                                    'id' => $year->id,
                                    'name' => $year->name,
                                    'start_date' => $year->start_date ? $year->start_date->format('Y-m-d') : '',
                                    'end_date' => $year->end_date ? $year->end_date->format('Y-m-d') : '',
                                    'description' => $year->description,
                                ]);
                                $quartersData = [];
                                foreach ($year->quarters as $q) {
                                    $quartersData[] = [
                                        'quarter_number' => $q->quarter_number,
                                        'name' => $q->name,
                                        'start_date' => $q->start_date?->format('Y-m-d'),
                                        'end_date' => $q->end_date?->format('Y-m-d'),
                                        'notes' => $q->notes,
                                    ];
                                }
                                $quartersJson = json_encode($quartersData);
                            ?>
                            <tr data-year='<?php echo e($yearData); ?>'
                                onclick="selectYear(this)"
                                class="transition-all group cursor-pointer hover:bg-slate-50/80 <?php echo e($year->closure && $year->closure->status === 'closed' ? 'bg-gray-50/50' : ''); ?>"
                                id="year-row-<?php echo e($year->id); ?>">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-sm">
                                            <?php echo e(substr($year->name, 0, 2)); ?>

                                        </div>
                                        <span class="font-semibold text-slate-900"><?php echo e($year->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-slate-600"><?php echo e($year->start_date->format('M d, Y')); ?></td>
                                <td class="px-8 py-5 text-slate-600"><?php echo e(optional($year->end_date)->format('M d, Y') ?? 'â€”'); ?></td>
                                <td class="px-8 py-5">
                                    <?php if($year->is_active): ?>
                                        <span class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold shadow-sm">
                                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                            ACTIVE
                                        </span>
                                    <?php elseif($year->closure && $year->closure->status === 'closed'): ?>
                                        <span class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-600 rounded-full text-sm font-bold shadow-sm">
                                            <i class="fas fa-lock mr-2 text-gray-500"></i>
                                            CLOSED
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-full text-sm font-medium">
                                            Inactive
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-2">
                                        <?php if(!$year->is_active && !($year->closure && $year->closure->status === 'closed')): ?>
                                        <form action="<?php echo e(route('admin.school-year.start')); ?>" method="POST" class="inline" @click.stop>
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="school_year_id" value="<?php echo e($year->id); ?>">
                                            <button type="submit" 
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5"
                                                    onclick="return confirm('Start this school year? This will generate a QR code for enrollment.')">
                                                <i class="fas fa-play mr-2"></i>Start
                                            </button>
                                        </form>
                                        <?php elseif($year->closure && $year->closure->status === 'closed'): ?>
                                        <span class="text-gray-400 text-sm italic flex items-center gap-2">
                                            <i class="fas fa-lock text-gray-500"></i>
                                            Closed — view in Reports
                                        </span>
                                        <?php else: ?>
                                        <span class="text-slate-400 text-sm italic flex items-center gap-2">
                                            <i class="fas fa-check-circle text-emerald-500"></i>
                                            Active
                                        </span>
                                        <?php endif; ?>
                                        
                                        
                                        <button type="button"
                                                data-year-id="<?php echo e($year->id); ?>"
                                                data-year-name="<?php echo e($year->name); ?>"
                                                data-quarters="<?php echo e($quartersJson); ?>"
                                                onclick="event.stopPropagation(); handleQuartersClick(this);"
                                                style="color:#2563eb;border-color:#2563eb;"
                                                class="inline-flex items-center px-3 py-2 bg-white border-2 text-sm font-bold rounded-xl transition-all hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5 active:scale-95"
                                                title="Manage Quarter Dates"
                                                onmouseover="this.style.backgroundColor='#2563eb';this.style.color='#fff';"
                                                onmouseout="this.style.backgroundColor='#fff';this.style.color='#2563eb';">
                                            <span style="background:#dbeafe;color:#2563eb;" class="w-6 h-6 rounded-lg flex items-center justify-center mr-2 text-xs font-bold">Q</span>
                                            Quarters
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center">
                                            <i class="fas fa-inbox text-4xl text-slate-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-slate-500 font-medium text-lg">No school years found</p>
                                            <p class="text-slate-400 text-sm mt-1">Create your first school year to get started</p>
                                        </div>
                                        <a href="<?php echo e(route('admin.school-years.create')); ?>" 
                                           class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/30 mt-2 hover:shadow-xl hover:-translate-y-0.5">
                                            <i class="fas fa-plus mr-2"></i>Create School Year
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($schoolYears->hasPages()): ?>
                <div class="px-8 py-5 border-t border-slate-200 bg-slate-50/50">
                    <?php echo e($schoolYears->links()); ?>

                </div>
                <?php endif; ?>
            </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3" id="fabGroup">
        <!-- Add Button -->
        <button onclick="openCreateModal()"
                class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white shadow-lg shadow-blue-500/40 hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center"
                title="Create New School Year">
            <i class="fas fa-plus text-lg"></i>
        </button>

        <!-- Edit Button -->
        <button onclick="openEditModal()"
                id="editFabBtn"
                class="w-14 h-14 rounded-full bg-slate-300 cursor-not-allowed shadow-none text-white transition-all flex items-center justify-center"
                title="Edit Selected School Year">
            <i class="fas fa-pen text-lg"></i>
        </button>

      
        </form>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative" onclick="event.stopPropagation()">
            <h2 class="text-2xl font-bold mb-6 text-center">Create School Year</h2>
            <form action="<?php echo e(route('admin.school-years.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="name" class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" id="name" placeholder="e.g., 2026-2027"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label for="start_date" class="block font-medium mb-1">Start Date</label>
                    <input type="date" name="start_date" id="start_date"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label for="end_date" class="block font-medium mb-1">End Date</label>
                    <input type="date" name="end_date" id="end_date"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label for="description" class="block font-medium mb-1">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="border border-gray-300 rounded px-3 py-2 w-full" placeholder="Optional description"></textarea>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="mr-2">
                    <label for="is_active" class="font-medium">Set as active</label>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeCreateModal()"
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 font-medium">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 rounded bg-blue-600 hover:bg-blue-700 text-white font-semibold">Save</button>
                </div>
            </form>
            <button onclick="closeCreateModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative" onclick="event.stopPropagation()">
            <h2 class="text-2xl font-bold mb-6 text-center">Edit School Year</h2>
            <form id="editForm" action="#" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div>
                    <label class="block font-medium mb-1">Name</label>
                    <input type="text" name="name" id="editName"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Start Date</label>
                    <input type="date" name="start_date" id="editStartDate"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">End Date</label>
                    <input type="date" name="end_date" id="editEndDate"
                           class="border border-gray-300 rounded px-3 py-2 w-full" required>
                </div>
                <div>
                    <label class="block font-medium mb-1">Description</label>
                    <textarea name="description" id="editDescription" rows="3"
                              class="border border-gray-300 rounded px-3 py-2 w-full" placeholder="Optional description"></textarea>
                </div>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300 font-medium">Cancel</button>
                    <button type="submit"
                            class="px-4 py-2 rounded bg-amber-600 hover:bg-amber-700 text-white font-semibold">Update</button>
                </div>
            </form>
            <button onclick="closeEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Carry Forward Sections Modal -->
    <div id="carryForwardModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 p-6 relative">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clone text-teal-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Carry Forward Sections</h3>
                <p class="text-slate-600 mt-1">Copy all sections from a previous school year into the active year.</p>
            </div>

            <form action="<?php echo e(route('admin.school-year.carry-forward-sections')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="target_school_year_id" value="<?php echo e($activeSchoolYear?->id); ?>">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Copy sections from which school year?</label>
                    <select name="source_school_year_id" required
                            class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 bg-white">
                        <option value="">Select a school year...</option>
                        <?php $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($year->id !== ($activeSchoolYear?->id)): ?>
                                <option value="<?php echo e($year->id); ?>"><?php echo e($year->name); ?></option>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
                    <div class="flex items-start gap-2">
                        <i class="fas fa-info-circle mt-0.5"></i>
                        <div>
                            <p class="font-semibold">What gets copied:</p>
                            <ul class="list-disc list-inside mt-1 text-amber-700">
                                <li>Section name & grade level</li>
                                <li>Room number & capacity</li>
                                <li>Assigned teacher (optional)</li>
                            </ul>
                            <p class="mt-2 text-amber-700">Sections that already exist in the target year (same name + grade) will be skipped.</p>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCarryForwardModal()"
                            class="flex-1 px-4 py-3 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-teal-500 to-emerald-600 text-white rounded-xl font-semibold hover:from-teal-600 hover:to-emerald-700 transition-colors shadow-lg shadow-teal-500/30">
                        Copy Sections
                    </button>
                </div>
            </form>

            <button onclick="closeCarryForwardModal()" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed top-6 right-6 z-50 flex flex-col gap-3"></div>

    <!-- End School Year Modal -->
    <div id="endSchoolYearModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div id="endSchoolYearModalContent" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all">
            
            <!-- Initial State - Confirmation -->
            <div id="endSyInitialState">
                <div class="bg-rose-50 rounded-t-2xl p-6 border-b border-rose-100">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-calendar-times text-rose-600 text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-rose-900">End School Year</h3>
                            <p class="text-sm text-rose-600"><?php echo e($activeSchoolYear?->name ?? 'Current School Year'); ?></p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-amber-800 font-medium mb-1">Important Notice</p>
                                <p class="text-sm text-amber-700">
                                    Ending the school year will promote all students to the next grade level. 
                                    This action requires all teachers to finalize their SF9 content first.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-graduation-cap text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-700">SF9 Requirement</p>
                                <p class="text-xs text-slate-500">All grades, attendance, and core values must be finalized</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-user-graduate text-emerald-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-700">Student Promotion</p>
                                <p class="text-xs text-slate-500">Students will advance to the next grade level</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="closeEndSchoolYearModal()" 
                                class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                            Cancel
                        </button>
                        <button onclick="submitEndSchoolYear()" 
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-semibold rounded-xl transition-all shadow-lg shadow-rose-500/30">
                            <i class="fas fa-check mr-2"></i>Confirm End School Year
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Loading State -->
            <div id="endSyLoadingState" class="hidden p-8 text-center">
                <div class="w-16 h-16 rounded-full border-4 border-rose-200 border-t-rose-600 animate-spin mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-slate-800">Processing...</h3>
                <p class="text-sm text-slate-500 mt-1">Validating finalization status and ending school year</p>
            </div>
            
            <!-- Success State -->
            <div id="endSySuccessState" class="hidden">
                <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                    <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900">School Year Ended!</h3>
                    <p class="text-sm text-emerald-600 mt-1">Student promotion processing complete</p>
                </div>
                <div class="p-6 text-center">
                    <p class="text-slate-600 mb-4" id="endSySuccessMessage">The school year has been successfully ended.</p>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="bg-emerald-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-emerald-700" id="endSyPromotedCount">0</p>
                            <p class="text-xs text-emerald-600">Promoted</p>
                        </div>
                        <div class="bg-amber-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-amber-700" id="endSyRetainedCount">0</p>
                            <p class="text-xs text-amber-600">Retained</p>
                        </div>
                        <div class="bg-blue-50 rounded-xl p-3 text-center">
                            <p class="text-2xl font-bold text-blue-700" id="endSyGraduatedCount">0</p>
                            <p class="text-xs text-blue-600">Graduated</p>
                        </div>
                    </div>
                    <button onclick="closeEndSchoolYearModal(); window.location.reload();" 
                            class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                        Continue
                    </button>
                </div>
            </div>
            
            <!-- Error State -->
            <div id="endSyErrorState" class="hidden">
                <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                    <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-900">Cannot End School Year</h3>
                    <p class="text-sm text-red-600 mt-1">Some teachers have not finalized their content</p>
                </div>
                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-amber-600 mt-0.5 flex-shrink-0"></i>
                            <div>
                                <p class="text-sm text-amber-800 font-medium mb-1">SF9 Finalization Required</p>
                                <p class="text-sm text-amber-700">
                                    All teachers must finalize grades, attendance, and core values before the school year can end.
                                    Please visit the <a href="<?php echo e(route('admin.school-year.closure')); ?>" class="font-semibold underline">Closure Dashboard</a> for details.
                                </p>
                            </div>
                        </div>
                    </div>
                    <p class="text-slate-600 mb-4 text-sm" id="endSyErrorMessage"></p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="closeEndSchoolYearModal()" 
                                class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                            Close
                        </button>
                        <a href="<?php echo e(route('admin.school-year.closure')); ?>" 
                           class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-semibold rounded-xl transition-all text-center">
                            <i class="fas fa-tasks mr-2"></i>View Closure Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Quarters Management Modal -->
    <div id="quartersModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-3">
        <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full mx-auto transform transition-all max-h-[90vh] overflow-hidden flex flex-col">
            
            <div class="p-3 border-b border-slate-100 bg-slate-50 flex-shrink-0">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center text-white shadow-md">
                            <i class="fas fa-calendar-alt text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">Quarter Dates</h3>
                            <p class="text-xs text-slate-500" id="quartersModalSyName">School Year</p>
                        </div>
                    </div>
                    <button onclick="closeQuartersModal()" class="w-8 h-8 rounded-lg bg-white border border-slate-200 hover:border-slate-300 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
            
            
            <form id="quartersForm" method="POST" class="flex-1 flex flex-col min-h-0">
                <?php echo csrf_field(); ?>
                <div class="flex-1 overflow-y-auto p-3 space-y-2" id="quartersFormBody">
                    
                </div>
                
                <div class="p-3 border-t border-slate-100 bg-slate-50 flex justify-end gap-2 flex-shrink-0">
                    <button type="button" onclick="closeQuartersModal()" 
                            class="px-5 py-2.5 bg-white border-2 border-slate-200 text-slate-600 font-bold rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="quartersSaveBtn"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 active:scale-95">
                        <i class="fas fa-save mr-1.5"></i>Save Dates
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            document.getElementById('sidebar')?.classList.toggle('open');
        });

        // Copy to clipboard with toast notification and button feedback
        function copyToClipboard(text, btn) {
            navigator.clipboard.writeText(text).then(function() {
                showToast('Enrollment URL copied to clipboard!', 'success');
                // Button feedback: change to checkmark
                if (btn) {
                    const originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i><span class="hidden sm:inline">Copied!</span>';
                    btn.classList.remove('bg-blue-600', 'hover:bg-blue-700', 'shadow-blue-500/30');
                    btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700', 'shadow-emerald-500/30');
                    setTimeout(() => {
                        btn.innerHTML = originalHtml;
                        btn.classList.remove('bg-emerald-600', 'hover:bg-emerald-700', 'shadow-emerald-500/30');
                        btn.classList.add('bg-blue-600', 'hover:bg-blue-700', 'shadow-blue-500/30');
                    }, 2000);
                }
            }, function(err) {
                console.error('Could not copy text: ', err);
                showToast('Failed to copy URL. Please copy manually.', 'error');
            });
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const bgColor = type === 'success' ? 'bg-slate-800' : 'bg-rose-600';
            const icon = type === 'success' ? 'fa-check-circle text-emerald-400' : 'fa-exclamation-circle text-white';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 transform translate-y-10 opacity-0 transition-all duration-300 min-w-[300px]`;
            toast.innerHTML = `
                <i class="fas ${icon} text-lg"></i>
                <span class="font-medium">${message}</span>
            `;
            
            container.appendChild(toast);
            
            // Animate in
            requestAnimationFrame(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            });
            
            // Remove after 3 seconds
            setTimeout(() => {
                toast.style.transform = 'translateY(10px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        /* Auto-hide alerts after 5 seconds
        document.querySelectorAll('[class*="animate-fade-in-up"]').forEach(alert => {
            setTimeout(() => {
                if (alert && alert.parentElement) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    alert.style.transition = 'all 0.3s ease';
                    setTimeout(() => alert.remove(), 300);
                }
            }, 5000);
        }); */

        // End School Year Modal Functions
        function showEndSchoolYearModal() {
            document.getElementById('endSchoolYearModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            // Reset to initial state
            document.getElementById('endSyInitialState').classList.remove('hidden');
            document.getElementById('endSyLoadingState').classList.add('hidden');
            document.getElementById('endSySuccessState').classList.add('hidden');
            document.getElementById('endSyErrorState').classList.add('hidden');
        }

        function closeEndSchoolYearModal() {
            document.getElementById('endSchoolYearModal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        function submitEndSchoolYear() {
            // Show loading state
            document.getElementById('endSyInitialState').classList.add('hidden');
            document.getElementById('endSyLoadingState').classList.remove('hidden');
            
            // Create form data
            const formData = new FormData();
            formData.append('_token', '<?php echo e(csrf_token()); ?>');
            <?php if($activeSchoolYear): ?>
            formData.append('school_year_id', '<?php echo e($activeSchoolYear->id); ?>');
            <?php endif; ?>
            
            // Submit via fetch
            fetch('<?php echo e(route('admin.school-year.end')); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.redirected) {
                    // If redirected, it likely succeeded
                    return { success: true, message: 'School year ended successfully!' };
                }
                return response.json().catch(() => {
                    return { success: true, message: 'School year ended successfully!' };
                });
            })
            .then(data => {
                document.getElementById('endSyLoadingState').classList.add('hidden');
                
                if (data.success === true || data.success === undefined) {
                    // Show success
                    document.getElementById('endSySuccessState').classList.remove('hidden');
                    if (data.message) {
                        document.getElementById('endSySuccessMessage').textContent = data.message;
                    }
                    // Update counts
                    if (data.promoted_count !== undefined) {
                        document.getElementById('endSyPromotedCount').textContent = data.promoted_count;
                    }
                    if (data.retained_count !== undefined) {
                        document.getElementById('endSyRetainedCount').textContent = data.retained_count;
                    }
                    if (data.graduated_count !== undefined) {
                        document.getElementById('endSyGraduatedCount').textContent = data.graduated_count;
                    }
                } else {
                    // Show error
                    document.getElementById('endSyErrorState').classList.remove('hidden');
                    if (data.message) {
                        document.getElementById('endSyErrorMessage').textContent = data.message;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('endSyLoadingState').classList.add('hidden');
                document.getElementById('endSyErrorState').classList.remove('hidden');
                document.getElementById('endSyErrorMessage').textContent = 'An error occurred. Please try again or visit the Closure Dashboard for more details.';
            });
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEndSchoolYearModal();
            }
        });

        // Close modal when clicking outside
        document.getElementById('endSchoolYearModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEndSchoolYearModal();
            }
        });

        // --- Quarters Modal ---
        function handleQuartersClick(btn) {
            const schoolYearId = btn.dataset.yearId;
            const schoolYearName = btn.dataset.yearName;
            const existingQuarters = JSON.parse(btn.dataset.quarters || '[]');
            openQuartersModal(schoolYearId, schoolYearName, existingQuarters);
        }

        function openQuartersModal(schoolYearId, schoolYearName, existingQuarters) {
            document.getElementById('quartersModalSyName').textContent = schoolYearName;
            document.getElementById('quartersForm').action = '<?php echo e(url('admin/school-year')); ?>/' + schoolYearId + '/quarters';
            
            const container = document.getElementById('quartersFormBody');
            container.innerHTML = '';
            
            const defaultNames = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
            const configs = [
                { color: '#2563eb', bg: '#eff6ff', border: '#bfdbfe' },
                { color: '#059669', bg: '#ecfdf5', border: '#a7f3d0' },
                { color: '#d97706', bg: '#fffbeb', border: '#fde68a' },
                { color: '#e11d48', bg: '#fff1f2', border: '#fecdd3' },
            ];
            
            for (let i = 1; i <= 4; i++) {
                const existing = existingQuarters?.find(q => q.quarter_number === i);
                const qName = existing?.name || defaultNames[i - 1];
                const qStart = existing?.start_date || '';
                const qEnd = existing?.end_date || '';
                const qNotes = existing?.notes || '';
                const c = configs[i - 1];
                
                container.innerHTML += `
                    <div style="background:${c.bg};border:1px solid ${c.border};" class="rounded-lg p-2.5">
                        <div class="flex items-center gap-2 mb-1.5">
                            <div style="background:${c.color};" class="w-6 h-6 rounded flex items-center justify-center text-white font-bold text-[10px]">
                                Q${i}
                            </div>
                            <input type="text" name="quarters[${i}][name]" value="${qName.replace(/"/g, '&quot;')}" 
                                   style="color:${c.color};"
                                   class="flex-1 bg-white border border-slate-200 rounded px-2 py-1 text-sm font-bold outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                   placeholder="Quarter name">
                        </div>
                        <div class="grid grid-cols-2 gap-2 mb-1.5">
                            <div>
                                <label style="color:${c.color};" class="block text-[10px] font-bold uppercase tracking-wider mb-0.5">Start</label>
                                <input type="date" name="quarters[${i}][start_date]" value="${qStart}" required
                                       class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                            <div>
                                <label style="color:${c.color};" class="block text-[10px] font-bold uppercase tracking-wider mb-0.5">End</label>
                                <input type="date" name="quarters[${i}][end_date]" value="${qEnd}" required
                                       class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-sm text-slate-700 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <input type="text" name="quarters[${i}][notes]" value="${(qNotes || '').replace(/"/g, '&quot;')}" 
                                   class="w-full bg-white border border-slate-200 rounded px-2 py-1 text-sm text-slate-600 outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                                   placeholder="Notes (optional)">
                        </div>
                        <input type="hidden" name="quarters[${i}][quarter_number]" value="${i}">
                    </div>
                `;
            }
            
            document.getElementById('quartersModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        
        function closeQuartersModal() {
            document.getElementById('quartersModal').classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close quarters modal on outside click
        document.getElementById('quartersModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeQuartersModal();
        });
        
        // Handle quarters form submission via AJAX for smoother UX
        document.getElementById('quartersForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            btn.disabled = true;
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('Quarter dates saved successfully!', 'success');
                    closeQuartersModal();
                    // Reload to show updated dates
                    setTimeout(() => window.location.reload(), 500);
                } else {
                    showToast(data.message || 'Failed to save quarter dates.', 'error');
                }
            } catch (err) {
                showToast('An error occurred while saving.', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // --- School Year Row Selection & FABs ---
        let selectedYear = null;

        function selectYear(row) {
            const yearData = JSON.parse(row.dataset.year);
            const yearId = yearData.id;

            if (selectedYear && selectedYear.id === yearId) {
                // Deselect
                selectedYear = null;
                row.classList.remove('bg-blue-50/80', 'ring-1', 'ring-blue-200');
                row.classList.add('hover:bg-slate-50/80');
            } else {
                // Deselect previous
                if (selectedYear) {
                    const prevRow = document.getElementById('year-row-' + selectedYear.id);
                    if (prevRow) {
                        prevRow.classList.remove('bg-blue-50/80', 'ring-1', 'ring-blue-200');
                        prevRow.classList.add('hover:bg-slate-50/80');
                    }
                }
                // Select new
                selectedYear = yearData;
                row.classList.remove('hover:bg-slate-50/80');
                row.classList.add('bg-blue-50/80', 'ring-1', 'ring-blue-200');
            }

            updateFabButtons();
        }

        function updateFabButtons() {
            const editBtn = document.getElementById('editFabBtn');
            const deleteBtn = document.getElementById('deleteFabBtn');

            if (selectedYear) {
                editBtn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                editBtn.classList.add('bg-gradient-to-r', 'from-amber-500', 'to-orange-500', 'hover:from-amber-600', 'hover:to-orange-600', 'shadow-amber-500/40', 'hover:-translate-y-0.5', 'shadow-lg');
                editBtn.disabled = false;

                deleteBtn.classList.remove('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                deleteBtn.classList.add('bg-gradient-to-r', 'from-rose-500', 'to-red-600', 'hover:from-rose-600', 'hover:to-red-700', 'shadow-rose-500/40', 'hover:-translate-y-0.5', 'shadow-lg');
                deleteBtn.disabled = false;
            } else {
                editBtn.classList.add('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                editBtn.classList.remove('bg-gradient-to-r', 'from-amber-500', 'to-orange-500', 'hover:from-amber-600', 'hover:to-orange-600', 'shadow-amber-500/40', 'hover:-translate-y-0.5', 'shadow-lg');
                editBtn.disabled = true;

                deleteBtn.classList.add('bg-slate-300', 'cursor-not-allowed', 'shadow-none');
                deleteBtn.classList.remove('bg-gradient-to-r', 'from-rose-500', 'to-red-600', 'hover:from-rose-600', 'hover:to-red-700', 'shadow-rose-500/40', 'hover:-translate-y-0.5', 'shadow-lg');
                deleteBtn.disabled = true;
            }
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openEditModal() {
            if (!selectedYear) return;
            document.getElementById('editName').value = selectedYear.name;
            document.getElementById('editStartDate').value = selectedYear.start_date;
            document.getElementById('editEndDate').value = selectedYear.end_date;
            document.getElementById('editDescription').value = selectedYear.description || '';
            document.getElementById('editForm').action = '<?php echo e(url('admin/school-years')); ?>/' + selectedYear.id;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete() {
            if (!selectedYear) return;
            if (confirm('Are you sure you want to delete "' + selectedYear.name + '"?')) {
                document.getElementById('deleteForm').action = '<?php echo e(url('admin/school-years')); ?>/' + selectedYear.id;
                document.getElementById('deleteForm').submit();
            }
        }

        function showCarryForwardModal() {
            document.getElementById('carryForwardModal').classList.remove('hidden');
        }

        function closeCarryForwardModal() {
            document.getElementById('carryForwardModal').classList.add('hidden');
        }

        // Close modals on backdrop click
        document.getElementById('createModal').addEventListener('click', function(e) {
            if (e.target === this) closeCreateModal();
        });
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
        document.getElementById('carryForwardModal').addEventListener('click', function(e) {
            if (e.target === this) closeCarryForwardModal();
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/admin/school-years/index.blade.php ENDPATH**/ ?>