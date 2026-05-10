<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kindergarten Assessment - Developmental Domains</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        .domain-card { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 1.5rem; overflow: hidden; }
        .domain-header { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); color: white; padding: 1rem 1.5rem; font-weight: 600; }
        .subdomain-section { background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
        .subdomain-title { background: #e0e7ff; color: #1e40af; padding: 0.5rem 1.5rem; font-weight: 600; font-size: 0.875rem; }
        .indicator-row { display: flex; align-items: center; padding: 0.75rem 1.5rem; border-bottom: 1px solid #f1f5f9; }
        .indicator-row:last-child { border-bottom: none; }
        .indicator-row:hover { background: #f8fafc; }
        .indicator-text { flex: 1; font-size: 0.875rem; color: #334155; }
        .rating-options { display: flex; gap: 0.5rem; }
        .rating-option { position: relative; }
        .rating-option input[type="radio"] { position: absolute; opacity: 0; cursor: pointer; }
        .rating-option label { display: flex; align-items: center; justify-content: center; width: 40px; height: 36px; border: 2px solid #cbd5e1; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.875rem; color: #64748b; transition: all 0.2s; }
        .rating-option input[type="radio"]:checked + label { border-color: #1e40af; background: #1e40af; color: white; }
        .rating-option label:hover { border-color: #3b82f6; color: #3b82f6; }
        .rating-legend { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.25rem 0.75rem; background: #f1f5f9; border-radius: 9999px; font-size: 0.75rem; color: #64748b; }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Overlay -->
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

    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="lg:ml-72 p-6">
        
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-start">
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-900 flex items-center justify-center text-white">
                    <i class="fas fa-child text-lg"></i>
                </div>
                <div>
                    Kindergarten Assessment
                    <p class="text-sm font-normal text-gray-500 mt-0"><?php echo e($lang == 'cebuano' ? 'Pagtimbang-timbang sa Kindergarten' : 'Kindergarten Developmental Assessment'); ?></p>
                </div>
            </h1>
            <a href="?<?php echo e(http_build_query(array_merge(request()->except('lang'), ['lang' => $lang == 'cebuano' ? 'english' : 'cebuano']))); ?>" 
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                <?php echo e($lang == 'cebuano' ? 'Switch to English' : 'Switch to Cebuano'); ?>

            </a>
        </div>

        <!-- Success/Error Modal with Sound -->
        <div id="successErrorModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div id="successErrorContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
                
                <div id="successState" class="hidden">
                    <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900"><?php echo e($lang == 'cebuano' ? 'Malampuson!' : 'Success!'); ?></h3>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="successMessageText"><?php echo e(session('success')); ?></p>
                        <button onclick="closeSuccessErrorModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                            <?php echo e($lang == 'cebuano' ? 'Padayon' : 'Continue'); ?>

                        </button>
                    </div>
                </div>
                
                
                <div id="errorState" class="hidden">
                    <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-900"><?php echo e($lang == 'cebuano' ? 'Sayop!' : 'Error!'); ?></h3>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="errorMessageText"><?php echo e(session('error')); ?></p>
                        <button onclick="closeSuccessErrorModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                            <?php echo e($lang == 'cebuano' ? 'Close' : 'Close'); ?>

                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php if(session('validation_errors')): ?>
        <div class="mb-4 bg-amber-50 border border-amber-200 rounded-lg p-4">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                <span class="font-medium text-amber-800"><?php echo e($lang == 'cebuano' ? 'Mga Kulang nga Marka:' : 'Missing Ratings:'); ?></span>
            </div>
            <ul class="list-disc list-inside text-amber-700 text-sm max-h-32 overflow-y-auto">
                <?php $__currentLoopData = session('validation_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        
        <?php if($selectedSection && $finalization): ?>
            <?php if($finalization->grades_finalized || $finalization->is_locked): ?>
            <div class="mb-4 bg-emerald-50 border border-emerald-200 rounded-lg p-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fas fa-lock text-emerald-600 text-xl"></i>
                    <div>
                        <p class="font-bold text-emerald-800">
                            <?php echo e($lang == 'cebuano' ? 'Na-finalize na ang mga marka' : 'Assessments Finalized'); ?>

                        </p>
                        <p class="text-emerald-600 text-sm">
                            <?php echo e($lang == 'cebuano' 
                                ? 'Ang mga marka nalock na. Kontaka ang admin kung kinahanglan usbon.' 
                                : 'Assessments are locked. Contact admin if changes are needed.'); ?>

                        </p>
                    </div>
                </div>
                <?php if($finalization->grades_finalized_at): ?>
                <div class="text-right text-sm text-emerald-600">
                    <p><?php echo e($lang == 'cebuano' ? 'Gi-finalize ni:' : 'Finalized by:'); ?></p>
                    <p class="font-medium"><?php echo e($finalization->finalizedByUser?->name ?? 'N/A'); ?></p>
                    <p><?php echo e($finalization->grades_finalized_at->format('M d, Y h:i A')); ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($kinderSections->isEmpty()): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-8 text-center">
            <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
            <p class="text-amber-800 font-medium">You don't have any Kindergarten sections assigned.</p>
            <p class="text-amber-600 text-sm mt-1">Kindergarten assessment is only available for Kindergarten sections.</p>
        </div>
        <?php else: ?>

        <!-- Filters -->
        <div class="bg-white rounded-lg p-6 shadow-sm mb-6">
            <form method="GET" action="<?php echo e(route('teacher.kindergarten.assessment')); ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="hidden" name="lang" value="<?php echo e($lang); ?>">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Section</label>
                    <select name="section_id" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        <?php $__currentLoopData = $kinderSections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>" <?php echo e($selectedSection && $selectedSection->id == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->name); ?> - <?php echo e($section->gradeLevel->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Student</label>
                    <select name="student_id" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select Student --</option>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($student->id); ?>" <?php echo e($selectedStudent && $selectedStudent->id == $student->id ? 'selected' : ''); ?>>
                                <?php echo e($student->user->last_name ?? ''); ?>, <?php echo e($student->user->first_name ?? ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Quarter</label>
                    <select name="quarter" onchange="this.form.submit()" class="w-full px-4 py-2 rounded-lg border border-slate-300 focus:ring-2 focus:ring-blue-500">
                        <option value="1" <?php echo e($selectedQuarter == 1 ? 'selected' : ''); ?>>1st Quarter</option>
                        <option value="2" <?php echo e($selectedQuarter == 2 ? 'selected' : ''); ?>>2nd Quarter</option>
                        <option value="3" <?php echo e($selectedQuarter == 3 ? 'selected' : ''); ?>>3rd Quarter</option>
                        <option value="4" <?php echo e($selectedQuarter == 4 ? 'selected' : ''); ?>>4th Quarter</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-filter mr-2"></i>Load Assessment
                    </button>
                </div>
            </form>
            
            
            <?php if($selectedSection && $students->isNotEmpty()): ?>
            <div class="mt-4 pt-4 border-t border-slate-200 flex items-center justify-between">
                <div class="text-sm text-slate-600">
                    <?php if($isEditable): ?>
                        <i class="fas fa-info-circle mr-1 text-blue-500"></i>
                        <?php echo e($lang == 'cebuano' 
                            ? 'I-finalize ang mga marka kung kompleto na ang tanan.' 
                            : 'Finalize assessments once all ratings are complete.'); ?>

                    <?php else: ?>
                        <i class="fas fa-check-circle mr-1 text-emerald-500"></i>
                        <?php echo e($lang == 'cebuano' 
                            ? 'Ang mga marka para niining seksyon gi-finalize na.' 
                            : 'Assessments for this section have been finalized.'); ?>

                    <?php endif; ?>
                </div>
                <?php if($isEditable): ?>
                <button type="button" onclick="showFinalizeKindergartenModal()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    <?php echo e($lang == 'cebuano' ? 'I-finalize ang mga Marka' : 'Finalize Assessments'); ?>

                </button>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if($selectedStudent): ?>
        <!-- Student Info Card -->
        <div class="bg-white rounded-lg p-4 shadow-sm mb-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xl font-bold">
                    <?php echo e(substr($selectedStudent->user->first_name ?? 'S', 0, 1)); ?>

                </div>
                <div>
                    <h3 class="font-bold text-lg text-slate-800">
                        <?php echo e($selectedStudent->user->last_name ?? ''); ?>, <?php echo e($selectedStudent->user->first_name ?? ''); ?> <?php echo e($selectedStudent->user->middle_name ?? ''); ?>

                    </h3>
                    <p class="text-sm text-slate-500">
                        LRN: <?php echo e($selectedStudent->lrn ?? 'N/A'); ?> | 
                        Quarter: <span class="font-medium text-blue-600"><?php echo e($selectedQuarter); ?></span>
                    </p>
                </div>
            </div>
            <div class="flex gap-4 text-sm">
                <?php $ratingScale = config('kindergarten.rating_scale'); ?>
                <div class="rating-legend"><span class="font-bold text-blue-700">B</span> = <?php echo e($ratingScale['B']['label'][$lang]); ?></div>
                <div class="rating-legend"><span class="font-bold text-blue-700">D</span> = <?php echo e($ratingScale['D']['label'][$lang]); ?></div>
                <div class="rating-legend"><span class="font-bold text-blue-700">C</span> = <?php echo e($ratingScale['C']['label'][$lang]); ?></div>
            </div>
        </div>

        <!-- Assessment Form -->
        <form method="POST" action="<?php echo e(route('teacher.kindergarten.store')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="student_id" value="<?php echo e($selectedStudent->id); ?>">
            <input type="hidden" name="quarter" value="<?php echo e($selectedQuarter); ?>">
            <input type="hidden" name="section_id" value="<?php echo e($selectedSection->id ?? ''); ?>">
            <input type="hidden" name="lang" value="<?php echo e($lang); ?>">
            
            <?php if(!$isEditable): ?>
            <fieldset disabled class="opacity-75">
            <?php endif; ?>

            <?php $__currentLoopData = $kinderConfig; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $domainKey => $domainData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="domain-card">
                <div class="domain-header">
                    <i class="fas fa-book-open mr-2"></i>
                    <?php echo e($domainData['name'][$lang] ?? $domainData['name']['cebuano']); ?>

                </div>
                
                <?php if(isset($domainData['indicators'])): ?>
                    <div class="subdomain-section">
                        <?php $__currentLoopData = $domainData['indicators']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indicatorKey => $indicatorData): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $ratingKey = $domainKey . '.' . $indicatorKey;
                            $existingRating = $existingRatings->get($ratingKey)?->rating ?? '';
                            $indicatorText = $indicatorData[$lang] ?? $indicatorData['cebuano'];
                        ?>
                        <div class="indicator-row">
                            <span class="indicator-text"><?php echo e($indicatorText); ?></span>
                            <div class="rating-options">
                                <div class="rating-option">
                                    <input type="radio" name="ratings[<?php echo e($domainKey); ?>][<?php echo e($indicatorKey); ?>]" id="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_B" value="B" <?php echo e($existingRating == 'B' ? 'checked' : ''); ?> <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                    <label for="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_B">B</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" name="ratings[<?php echo e($domainKey); ?>][<?php echo e($indicatorKey); ?>]" id="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_D" value="D" <?php echo e($existingRating == 'D' ? 'checked' : ''); ?> <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                    <label for="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_D">D</label>
                                </div>
                                <div class="rating-option">
                                    <input type="radio" name="ratings[<?php echo e($domainKey); ?>][<?php echo e($indicatorKey); ?>]" id="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_C" value="C" <?php echo e($existingRating == 'C' ? 'checked' : ''); ?> <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                    <label for="<?php echo e($domainKey); ?>_<?php echo e($indicatorKey); ?>_C">C</label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(!$isEditable): ?>
            </fieldset>
            <?php endif; ?>

            <!-- Submit Button -->
            <div class="flex justify-between items-center mt-6 mb-12">
                <div class="text-sm text-slate-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    <?php if($isEditable): ?>
                        <?php echo e($lang == 'cebuano' ? 'All ratings are automatically saved when you click Save Assessment' : 'All ratings are automatically saved when you click Save'); ?>

                    <?php else: ?>
                        <?php echo e($lang == 'cebuano' ? 'Ang mga marka nalock na. Dili na makausab.' : 'Assessments are locked. No further changes allowed.'); ?>

                    <?php endif; ?>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('teacher.sf9')); ?>?student_id=<?php echo e($selectedStudent->id); ?>&lang=<?php echo e($lang); ?>" target="_blank" class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-medium flex items-center gap-2">
                        <i class="fas fa-eye"></i><?php echo e($lang == 'cebuano' ? 'View SF9' : 'View Report Card'); ?>

                    </a>
                    <?php if($isEditable): ?>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                            <i class="fas fa-save"></i><?php echo e($lang == 'cebuano' ? 'Save Assessment' : 'Save Ratings'); ?>

                        </button>
                    <?php else: ?>
                        <button type="button" disabled class="px-8 py-3 bg-gray-400 text-white rounded-lg font-medium flex items-center gap-2 cursor-not-allowed">
                            <i class="fas fa-lock"></i><?php echo e($lang == 'cebuano' ? 'Nalock Na' : 'Locked'); ?>

                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>

        <?php else: ?>
        <div class="bg-white rounded-lg p-8 text-center shadow-sm">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-800 mb-2">Select a Student</h3>
            <p class="text-slate-500">Please select a Kindergarten student from the dropdown above to start the assessment.</p>
        </div>
        <?php endif; ?>

        <?php endif; ?>
    </div>

    <?php if($isEditable && $selectedSection): ?>
    <!-- Finalize Kindergarten Modal -->
    <div id="finalizeKindergartenModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all" style="animation: modal-pop 0.3s ease-out;">
            <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-emerald-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-emerald-900"><?php echo e($lang == 'cebuano' ? 'I-finalize ang mga Marka?' : 'Finalize Assessments?'); ?></h3>
                        <p class="text-sm text-emerald-600"><?php echo e($lang == 'cebuano' ? 'Dili na makausab human niini' : 'This action cannot be undone'); ?></p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-slate-50 rounded-xl p-4 mb-4 space-y-3">
                    <p class="text-sm text-slate-700">
                        <i class="fas fa-info-circle text-emerald-500 mr-2"></i>
                        <?php echo e($lang == 'cebuano' ? 'I-finalize nimo ang mga marka para sa' : 'You are about to finalize assessments for'); ?> <strong><?php echo e($selectedSection->name); ?></strong>.
                    </p>
                    <div class="text-sm text-slate-600 space-y-2 ml-6">
                        <p><i class="fas fa-check text-emerald-500 mr-2"></i><?php echo e($lang == 'cebuano' ? 'Ang tanang marka nalock na' : 'All ratings will be locked'); ?></p>
                        <p><i class="fas fa-check text-emerald-500 mr-2"></i><?php echo e($lang == 'cebuano' ? 'Ang 8 ka developmental domains para sa Q1-Q4 gi-finalize na' : 'All 8 developmental domains for Q1-Q4 will be finalized'); ?></p>
                        <p><i class="fas fa-check text-emerald-500 mr-2"></i><?php echo e($lang == 'cebuano' ? 'Kinahanglan ang tabang sa admin kung usbon' : 'Admin assistance required for any changes'); ?></p>
                    </div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <p class="text-sm text-amber-800">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong><?php echo e($lang == 'cebuano' ? 'Importanteng butang:' : 'Important:'); ?></strong> <?php echo e($lang == 'cebuano' ? 'Siguradoha nga na-complete na ang tanang 8 ka domains para sa tanang estudyante sa Q1-Q4.' : 'Make sure you have rated ALL 8 developmental domains for ALL students across ALL quarters (Q1-Q4) before finalizing.'); ?>

                    </p>
                </div>
                <form id="finalizeKindergartenForm" action="<?php echo e(route('teacher.kindergarten.finalize', $selectedSection)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button type="button" 
                                onclick="document.getElementById('finalizeKindergartenModal').classList.add('hidden')"
                                class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                            <?php echo e($lang == 'cebuano' ? 'Kanselahon' : 'Cancel'); ?>

                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-lock mr-2"></i><?php echo e($lang == 'cebuano' ? 'Oo, I-finalize' : 'Yes, Finalize'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Finalizing Loading Modal -->
    <div id="finalizingModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-8 text-center">
            <div class="w-16 h-16 rounded-full border-4 border-emerald-200 border-t-emerald-500 animate-spin mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-slate-800"><?php echo e($lang == 'cebuano' ? 'Nag-finalize...' : 'Finalizing...'); ?></h3>
            <p class="text-sm text-slate-500 mt-1"><?php echo e($lang == 'cebuano' ? 'Palihog hulat samtang gi-lock ang mga marka' : 'Please wait while we lock your assessment records'); ?></p>
        </div>
    </div>

    <!-- Finalization Result Modal with Sound -->
    <div id="finalizeResultModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div id="finalizeResultContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
            
            <div id="finalizeResultSuccess" class="hidden">
                <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                    <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900"><?php echo e($lang == 'cebuano' ? 'Malampuson nga Finalize!' : 'Finalized Successfully!'); ?></h3>
                    <p class="text-sm text-emerald-600 mt-1"><?php echo e($lang == 'cebuano' ? 'Ang mga marka nalock na' : 'Assessments have been locked'); ?></p>
                </div>
                <div class="p-6 text-center">
                    <p class="text-slate-600 mb-4" id="finalizeSuccessMessage"><?php echo e($lang == 'cebuano' ? 'Ang mga marka malampuson nga na-finalize.' : 'Kindergarten assessments have been finalized successfully.'); ?></p>
                    <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                        <?php echo e($lang == 'cebuano' ? 'Padayon' : 'Continue'); ?>

                    </button>
                </div>
            </div>
            
            
            <div id="finalizeResultError" class="hidden">
                <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                    <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-900"><?php echo e($lang == 'cebuano' ? 'Napakyas ang Finalize!' : 'Finalization Failed!'); ?></h3>
                    <p class="text-sm text-red-600 mt-1"><?php echo e($lang == 'cebuano' ? 'Dili ma-finalize ang mga marka' : 'Unable to finalize assessments'); ?></p>
                </div>
                <div class="p-6 text-center">
                    <p class="text-slate-600 mb-4" id="finalizeErrorMessage"><?php echo e($lang == 'cebuano' ? 'Naay sayup samtang gi-finalize ang mga marka.' : 'An error occurred while finalizing assessments.'); ?></p>
                    <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                        <?php echo e($lang == 'cebuano' ? 'Usa Pa' : 'Try Again'); ?>

                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes modal-pop {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .modal-pop {
            animation: modal-pop 0.3s ease-out;
        }
    </style>

    <script>
    // Web Audio API for sound effects
    let audioContext = null;
    
    function initAudioContext() {
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }
        return audioContext;
    }
    
    function playSuccessSound() {
        const ctx = initAudioContext();
        const now = ctx.currentTime;
        const duration = 2.0;
        
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.connect(gain);
        gain.connect(ctx.destination);
        
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, now);
        osc.frequency.exponentialRampToValueAtTime(1760, now + 0.1);
        
        gain.gain.setValueAtTime(0, now);
        gain.gain.linearRampToValueAtTime(0.25, now + 0.05);
        gain.gain.setValueAtTime(0.25, now + 0.1);
        gain.gain.exponentialRampToValueAtTime(0.001, now + duration);
        
        osc.start(now);
        osc.stop(now + duration);
    }
    
    function showFinalizeKindergartenModal() {
        document.getElementById('finalizeKindergartenModal').classList.remove('hidden');
    }
    
    function showFinalizeResultModal(type, message) {
        const modal = document.getElementById('finalizeResultModal');
        const successDiv = document.getElementById('finalizeResultSuccess');
        const errorDiv = document.getElementById('finalizeResultError');
        
        modal.classList.remove('hidden');
        
        if (type === 'success') {
            successDiv.classList.remove('hidden');
            errorDiv.classList.add('hidden');
            document.getElementById('finalizeSuccessMessage').textContent = message;
            // Play success sound
            playSuccessSound();
        } else {
            successDiv.classList.add('hidden');
            errorDiv.classList.remove('hidden');
            document.getElementById('finalizeErrorMessage').textContent = message;
        }
    }
    
    function closeFinalizeResultModal() {
        document.getElementById('finalizeResultModal').classList.add('hidden');
    }
    
    // Handle finalize form submission via AJAX
    document.getElementById('finalizeKindergartenForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Hide confirmation modal and show loading
        document.getElementById('finalizeKindergartenModal').classList.add('hidden');
        document.getElementById('finalizingModal').classList.remove('hidden');
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || ''
                }
            });
            
            // Handle 419 CSRF token expired error
            if (response.status === 419) {
                document.getElementById('finalizingModal').classList.add('hidden');
                showFinalizeResultModal('error', '<?php echo e($lang == 'cebuano' ? 'Na-expire ang sesyon. Palihog refresh sa page ug sulayi pag-usab.' : 'Session expired. Please refresh the page and try again.'); ?>');
                return;
            }
            
            const data = await response.json().catch(() => ({
                success: response.ok,
                message: response.ok ? '<?php echo e($lang == 'cebuano' ? 'Malampuson nga na-finalize!' : 'Finalized successfully!'); ?>' : '<?php echo e($lang == 'cebuano' ? 'Napakyas ang pag-finalize' : 'Failed to finalize'); ?>'
            }));
            
            // Hide loading modal
            document.getElementById('finalizingModal').classList.add('hidden');
            
            if (data.success) {
                showFinalizeResultModal('success', data.message || '<?php echo e($lang == 'cebuano' ? 'Malampuson nga na-finalize ang mga marka!' : 'Kindergarten assessments finalized successfully!'); ?>');
                // Reload page after showing success
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showFinalizeResultModal('error', data.message || '<?php echo e($lang == 'cebuano' ? 'Napakyas ang pag-finalize sa mga marka' : 'Failed to finalize assessments'); ?>');
            }
        } catch (error) {
            console.error('Finalize error:', error);
            document.getElementById('finalizingModal').classList.add('hidden');
            showFinalizeResultModal('error', '<?php echo e($lang == 'cebuano' ? 'Network error. Palihog sulayi pag-usab.' : 'Network error. Please try again.'); ?>');
        }
    });
    
    // Close modal when clicking outside
    document.getElementById('finalizeKindergartenModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    // Initialize audio on first user interaction
    document.addEventListener('click', function initAudio() {
        initAudioContext();
        document.removeEventListener('click', initAudio);
    }, { once: true });
    
    // Success/Error Modal Functions for session messages
    function showSuccessErrorModal(type, message) {
        const modal = document.getElementById('successErrorModal');
        const successState = document.getElementById('successState');
        const errorState = document.getElementById('errorState');
        
        modal.classList.remove('hidden');
        
        if (type === 'success') {
            successState.classList.remove('hidden');
            errorState.classList.add('hidden');
            document.getElementById('successMessageText').textContent = message;
            playSuccessSound();
        } else {
            successState.classList.add('hidden');
            errorState.classList.remove('hidden');
            document.getElementById('errorMessageText').textContent = message;
        }
    }
    
    function closeSuccessErrorModal() {
        document.getElementById('successErrorModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside
    document.getElementById('successErrorModal').addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });
    
    // Show success/error modal on page load if there's a session message
    <?php if(session('success')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessErrorModal('success', '<?php echo e(session('success')); ?>');
        });
    <?php endif; ?>
    <?php if(session('error')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showSuccessErrorModal('error', '<?php echo e(session('error')); ?>');
        });
    <?php endif; ?>
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\school-forms\kindergarten-assessment.blade.php ENDPATH**/ ?>