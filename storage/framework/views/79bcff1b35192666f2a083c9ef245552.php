<?php $__env->startSection('title', 'School Year Closure'); ?>

<?php $__env->startSection('header-title', 'School Year Closure'); ?>

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
    .progress-bar {
        transition: width 0.5s ease;
    }
</style>

<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-rose-600 to-pink-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                <i class="fas fa-calendar-check text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-slate-900">School Year Closure</h2>
                <p class="text-slate-500 mt-1">Manage section finalizations and end the school year</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Modal with Sound -->
    <div id="actionModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div id="actionModalContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 modal-pop">
            
            <div id="actionModalSuccess" class="hidden">
                <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center relative overflow-hidden">
                    <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-emerald-900">Success!</h3>
                    <p class="text-sm text-emerald-600 mt-1">Action completed successfully</p>
                    
                    <div id="countdownProgress" class="absolute bottom-0 left-0 h-1 bg-emerald-500 transition-all duration-1000" style="width: 100%;"></div>
                </div>
                <div class="p-6 text-center">
                    <p class="text-slate-600 mb-4" id="actionSuccessMessage">Operation completed.</p>
                    <button onclick="closeActionModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                        Continue
                    </button>
                </div>
            </div>
            
            
            <div id="actionModalError" class="hidden">
                <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                    <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-900">Error!</h3>
                    <p class="text-sm text-red-600 mt-1">Something went wrong</p>
                </div>
                <div class="p-6 text-center">
                    <p class="text-slate-600 mb-4" id="actionErrorMessage">An error occurred.</p>
                    <button onclick="closeActionModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showActionModal('success', '<?php echo e(session('success')); ?>');
        });
    </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showActionModal('error', '<?php echo e(session('error')); ?>');
        });
    </script>
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
    </div>
    <?php endif; ?>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card rounded-2xl p-6 animate-fade-in-up">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chalkboard text-blue-600"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Total Sections</span>
            </div>
            <p class="text-3xl font-bold text-slate-900"><?php echo e($closure->total_sections); ?></p>
        </div>

        <div class="glass-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-double text-emerald-600"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Finalized</span>
            </div>
            <p class="text-3xl font-bold text-emerald-600"><?php echo e($closure->finalized_sections); ?></p>
        </div>

        <div class="glass-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Pending</span>
            </div>
            <p class="text-3xl font-bold text-amber-600"><?php echo e($closure->total_sections - $closure->finalized_sections); ?></p>
        </div>

        <div class="glass-card rounded-2xl p-6 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600"></i>
                </div>
                <span class="text-sm font-medium text-slate-600">Progress</span>
            </div>
            <p class="text-3xl font-bold text-purple-600"><?php echo e($closure->getProgressPercentage()); ?>%</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="glass-card rounded-2xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-900">Finalization Progress</h3>
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 flex items-center gap-1">
                    <i class="fas fa-sync-alt fa-spin" id="autoRefreshIcon" style="display:none;"></i>
                    <span id="lastUpdated">Last updated: <?php echo e(now()->format('h:i A')); ?></span>
                </span>
                <span class="text-sm font-medium <?php echo e($closure->all_sections_finalized ? 'text-emerald-600' : 'text-amber-600'); ?>">
                    <?php echo e($closure->finalized_sections); ?> / <?php echo e($closure->total_sections); ?> Sections
                </span>
            </div>
        </div>
        <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden">
            <div class="progress-bar h-full rounded-full <?php echo e($closure->all_sections_finalized ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500'); ?>" 
                 style="width: <?php echo e($closure->getProgressPercentage()); ?>%"></div>
        </div>
        <?php if($closure->finalization_deadline): ?>
        <div class="mt-3 flex items-center gap-2 text-sm">
            <i class="fas fa-calendar-alt text-slate-400"></i>
            <span class="text-slate-600">Finalization Deadline:</span>
            <span class="font-semibold <?php echo e(now()->greaterThan($closure->finalization_deadline) ? 'text-rose-600' : 'text-slate-900'); ?>">
                <?php echo e($closure->finalization_deadline->format('F d, Y')); ?>

            </span>
            <?php if($closure->auto_close_enabled): ?>
            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-medium">
                Auto-close enabled
            </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Action Buttons -->
    <div class="glass-card rounded-2xl p-6 mb-8 animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-900 mb-1">End School Year</h3>
                <p class="text-sm text-slate-500">
                    <?php if($canEnd['all_finalized']): ?>
                        All sections are finalized. You can now end the school year.
                    <?php elseif($canEnd['can_end']): ?>
                        Deadline has passed. You may force end the school year with a reason.
                    <?php else: ?>
                        <?php echo e($canEnd['pending_count']); ?> section(s) still pending finalization.
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex gap-3">
                <?php if($canEnd['all_finalized']): ?>
                <button type="button" 
                        onclick="showEndSchoolYearModal()"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                    <i class="fas fa-check-circle mr-2"></i>
                    End School Year
                </button>
                <?php elseif($canEnd['can_end']): ?>
                <button type="button" 
                        onclick="document.getElementById('forceEndModal').classList.remove('hidden')"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-amber-500/30">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Force End School Year
                </button>
                <?php else: ?>
                <button type="button" 
                        onclick="showCannotEndModal()"
                        class="inline-flex items-center px-6 py-3 bg-slate-300 text-slate-600 font-semibold rounded-xl cursor-pointer hover:bg-slate-400 transition-colors">
                    <i class="fas fa-lock mr-2"></i>
                    End School Year
                </button>
                <?php endif; ?>

                <button type="button" 
                        onclick="document.getElementById('setDeadlineModal').classList.remove('hidden')"
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-blue-500/30">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Set Deadline
                </button>
            </div>
        </div>
    </div>

    <!-- Sections List -->
    <div class="glass-card rounded-2xl overflow-hidden animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-indigo-600"></i>
                </div>
                <h3 class="font-bold text-xl text-slate-900">Section Finalization Status</h3>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50/80">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Adviser</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Grades</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Core Values</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Kindergarten</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $sectionsStatus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition-all">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-sm">
                                    <?php echo e(substr($item['section']->name, 0, 2)); ?>

                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900"><?php echo e($item['section']->name); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo e($item['section']->gradeLevel->name ?? 'N/A'); ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-slate-700"><?php echo e($item['section']->teacher->user->full_name ?? 'N/A'); ?></p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($item['finalization'] && $item['finalization']->grades_finalized): ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                <i class="fas fa-check mr-1"></i> Done
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-medium">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php if($item['finalization'] && $item['finalization']->core_values_finalized): ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                <i class="fas fa-check mr-1"></i> Done
                            </span>
                            <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-medium">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <?php
                                $isKindergartenSection = stripos($item['section']->gradeLevel->name ?? '', 'kinder') !== false;
                            ?>
                            <?php if($isKindergartenSection): ?>
                                <?php if($item['finalization'] && $item['finalization']->grades_finalized): ?>
                                <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">
                                    <i class="fas fa-check mr-1"></i> Done
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-medium">
                                    <i class="fas fa-clock mr-1"></i> Pending
                                </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-1 bg-gray-100 text-gray-400 rounded-full text-xs font-medium">
                                    <i class="fas fa-minus mr-1"></i> N/A
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium <?php echo e($item['status']['class']); ?>">
                                <?php echo e($item['status']['text']); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1.5">
                                <?php if($item['finalization']): ?>
                                    <?php
                                        $sectionId = $item['section']->id;
                                        $sectionName = $item['section']->name;
                                        $grades = $item['finalization']->grades_finalized;
                                        $coreValues = $item['finalization']->core_values_finalized;
                                        $isKinder = stripos($item['section']->gradeLevel->name ?? '', 'kinder') !== false;
                                        $finalizedCount = ($grades ? 1 : 0) + ($coreValues ? 1 : 0) + ($isKinder && $grades ? 1 : 0);
                                    ?>
                                    
                                    
                                    <?php if($grades): ?>
                                        <button type="button"
                                            onclick="unlockComponent(<?php echo e($sectionId); ?>, '<?php echo e(addslashes($sectionName)); ?>', 'grades')"
                                            class="inline-flex items-center px-2 py-1 bg-emerald-100 hover:bg-emerald-200 text-emerald-700 rounded-lg text-xs font-medium transition-colors"
                                            title="Unlock Grades">
                                            <i class="fas fa-graduation-cap"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if($coreValues): ?>
                                        <button type="button"
                                            onclick="unlockComponent(<?php echo e($sectionId); ?>, '<?php echo e(addslashes($sectionName)); ?>', 'core_values')"
                                            class="inline-flex items-center px-2 py-1 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-lg text-xs font-medium transition-colors"
                                            title="Unlock Core Values">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php if($isKinder && $grades): ?>
                                        <button type="button"
                                            onclick="unlockComponent(<?php echo e($sectionId); ?>, '<?php echo e(addslashes($sectionName)); ?>', 'kindergarten')"
                                            class="inline-flex items-center px-2 py-1 bg-pink-100 hover:bg-pink-200 text-pink-700 rounded-lg text-xs font-medium transition-colors"
                                            title="Unlock Kindergarten Assessments">
                                            <i class="fas fa-child"></i>
                                        </button>
                                    <?php endif; ?>
                                    
                                    
                                    <?php if($finalizedCount > 1): ?>
                                        <button type="button"
                                            onclick="unlockAll(<?php echo e($sectionId); ?>, '<?php echo e(addslashes($sectionName)); ?>', <?php echo e(json_encode(['grades' => $grades, 'core_values' => $coreValues, 'kindergarten' => $isKinder && $grades])); ?>)"
                                            class="inline-flex items-center px-2 py-1 bg-amber-100 hover:bg-amber-200 text-amber-700 rounded-lg text-xs font-medium transition-colors"
                                            title="Unlock All Components">
                                            <i class="fas fa-unlock-alt mr-1"></i> All
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-xs text-slate-400">-</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-inbox text-3xl text-slate-300"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No sections found</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Set Deadline Modal -->
<div id="setDeadlineModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('setDeadlineModal').classList.add('hidden')" 
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Set Finalization Deadline</h3>
        </div>
        
        <form action="<?php echo e(route('admin.school-year.set-deadline')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Deadline Date</label>
                    <input type="date" name="deadline" required
                           min="<?php echo e(now()->addDay()->format('Y-m-d')); ?>"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                </div>
                
                <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                    <input type="checkbox" name="auto_finalize" id="auto_finalize" value="1" class="w-5 h-5 text-blue-600 rounded">
                    <label for="auto_finalize" class="text-sm text-slate-700">
                        <span class="font-medium">Auto-finalize on deadline</span>
                        <p class="text-xs text-slate-500 mt-1">Automatically finalize all pending sections when deadline is reached</p>
                    </label>
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('setDeadlineModal').classList.add('hidden')"
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-medium rounded-xl transition-all">
                    Set Deadline
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Force End Modal -->
<div id="forceEndModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('forceEndModal').classList.add('hidden')" 
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-rose-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Force End School Year</h3>
        </div>
        
        <div class="mb-6">
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <p class="text-sm text-amber-800">
                    <i class="fas fa-info-circle mr-1"></i>
                    <?php echo e($canEnd['pending_count'] ?? 0); ?> section(s) are not yet finalized. Please provide a reason for force ending the school year.
                </p>
            </div>
        </div>
        
        <form action="<?php echo e(route('admin.school-year.force-end')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Reason <span class="text-rose-500">*</span></label>
                <textarea name="reason" required rows="3"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20"
                          placeholder="Enter reason for force ending..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" 
                        onclick="document.getElementById('forceEndModal').classList.add('hidden')"
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white font-medium rounded-xl transition-all"
                        onclick="return confirm('Are you sure? This action cannot be undone.')">
                    Force End
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Unlock Modal System -->
<div id="unlockModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative m-4">
        <button type="button" id="unlockModalClose" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
            <div id="unlockModalIcon" class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-unlock text-indigo-600 text-xl"></i>
            </div>
            <div>
                <h3 id="unlockModalTitle" class="text-xl font-bold text-slate-900">Unlock Component</h3>
                <p id="unlockModalSubtitle" class="text-sm text-slate-500">Unlock for section</p>
            </div>
        </div>
        
        <div id="unlockModalInfo" class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 mb-4">
            <p class="text-sm text-indigo-800">
                <i class="fas fa-info-circle mr-2"></i>
                <span id="unlockModalDescription">You are about to unlock this component.</span>
            </p>
            <ul id="unlockModalList" class="hidden text-sm text-indigo-700 mt-2 ml-6 space-y-1">
                <li data-component="grades"><i class="fas fa-graduation-cap text-emerald-500 mr-1"></i> Grades</li>
                <li data-component="core_values"><i class="fas fa-heart text-purple-500 mr-1"></i> Core Values</li>
                <li data-component="kindergarten"><i class="fas fa-child text-pink-500 mr-1"></i> Kindergarten</li>
            </ul>
        </div>
        
        <form id="unlockForm" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="section_id" id="unlockSectionId">
            <input type="hidden" name="school_year_id" value="<?php echo e($activeSchoolYear->id); ?>">
            <input type="hidden" name="component" id="unlockComponentType">
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Reason <span class="text-rose-500">*</span>
                </label>
                <textarea name="reason" id="unlockReason" required rows="3"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"
                    placeholder="Enter reason for unlocking..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" id="unlockModalCancel"
                    class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" id="unlockModalSubmit"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-indigo-500 to-purple-500 hover:from-indigo-600 hover:to-purple-600 text-white font-medium rounded-xl transition-all">
                    <i class="fas fa-unlock mr-2"></i><span id="unlockSubmitText">Unlock</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- End School Year Modal -->
<div id="endSchoolYearModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div id="endSchoolYearModalContent" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all">
        
        <!-- Initial State - Confirmation -->
        <div id="endSyInitialState">
            <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-emerald-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-emerald-900">End School Year</h3>
                        <p class="text-sm text-emerald-600"><?php echo e($activeSchoolYear?->name ?? 'Current School Year'); ?></p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-sm text-amber-800 font-medium mb-1">Final Confirmation</p>
                            <p class="text-sm text-amber-700">
                                All sections have been finalized. You are about to end the school year and promote all students.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check-double text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-700">All SF9 Components Finalized</p>
                            <p class="text-xs text-slate-500">Grades and core values are complete</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-user-graduate text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-700">Student Promotion</p>
                            <p class="text-xs text-slate-500">All students will advance to the next grade level</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    <button onclick="closeEndSchoolYearModal()" 
                            class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button onclick="submitEndSchoolYear()" 
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-check mr-2"></i>Confirm End School Year
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Loading State -->
        <div id="endSyLoadingState" class="hidden p-8 text-center">
            <div class="w-16 h-16 rounded-full border-4 border-emerald-200 border-t-emerald-600 animate-spin mx-auto mb-4"></div>
            <h3 class="text-lg font-semibold text-slate-800">Processing...</h3>
            <p class="text-sm text-slate-500 mt-1">Ending school year and promoting students</p>
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
                <button onclick="window.location.href='<?php echo e(route('admin.school-years.index')); ?>'" 
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
                <p class="text-sm text-red-600 mt-1">An error occurred while processing</p>
            </div>
            <div class="p-6">
                <p class="text-slate-600 mb-4" id="endSyErrorMessage"></p>
                <button onclick="closeEndSchoolYearModal()" 
                        class="w-full px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    Close
                </button>
            </div>
        </div>
        
    </div>
</div>

<!-- Cannot End Modal -->
<div id="cannotEndModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4">
        <div class="bg-amber-50 rounded-t-2xl p-6 border-b border-amber-100">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock text-amber-600 text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-amber-900">Cannot End School Year</h3>
                    <p class="text-sm text-amber-600">SF9 Finalization Required</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="bg-slate-50 rounded-xl p-4 mb-4">
                <p class="text-sm text-slate-700 mb-2">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    The school year cannot be ended until all teachers have finalized their SF9 content:
                </p>
                <ul class="text-sm text-slate-600 space-y-1 ml-6">
                    <li><i class="fas fa-graduation-cap text-slate-400 mr-1"></i> Grades</li>
                    <li><i class="fas fa-calendar-check text-slate-400 mr-1"></i> Attendance</li>
                    <li><i class="fas fa-heart text-slate-400 mr-1"></i> Core Values</li>
                </ul>
            </div>
            <p class="text-sm text-slate-600 mb-4">
                <strong><?php echo e($canEnd['pending_count'] ?? 0); ?></strong> section(s) still pending finalization.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
                <button onclick="document.getElementById('cannotEndModal').classList.add('hidden')" 
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                    Close
                </button>
                <button onclick="document.getElementById('cannotEndModal').classList.add('hidden'); document.getElementById('setDeadlineModal').classList.remove('hidden');" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 hover:from-blue-600 hover:to-indigo-600 text-white font-semibold rounded-xl transition-all">
                    <i class="fas fa-calendar-alt mr-2"></i>Set Deadline
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// ============================================
// UNLOCK MODAL SYSTEM - Simple & Working
// ============================================

const componentConfig = {
    grades: { name: 'Grades', bg: 'bg-emerald-100', text: 'text-emerald-600', btn: 'from-emerald-500 to-teal-500' },
    core_values: { name: 'Core Values', bg: 'bg-purple-100', text: 'text-purple-600', btn: 'from-purple-500 to-pink-500' },
    kindergarten: { name: 'Kindergarten', bg: 'bg-pink-100', text: 'text-pink-600', btn: 'from-pink-500 to-rose-500', icon: 'fa-child' }
};

// Show unlock modal for individual component
function unlockComponent(sectionId, sectionName, component) {
    console.log('unlockComponent called:', sectionId, sectionName, component);
    
    const config = componentConfig[component];
    if (!config) {
        console.error('Unknown component:', component);
        return;
    }
    
    // Set form values
    const form = document.getElementById('unlockForm');
    form.action = '<?php echo e(route("admin.school-year.unlock-component")); ?>';
    document.getElementById('unlockSectionId').value = sectionId;
    document.getElementById('unlockComponentType').value = component;
    document.getElementById('unlockReason').value = '';
    
    // Update modal content
    document.getElementById('unlockModalTitle').textContent = 'Unlock ' + config.name;
    document.getElementById('unlockModalSubtitle').textContent = 'Unlock for ' + sectionName;
    document.getElementById('unlockModalDescription').innerHTML = 
        'You are about to unlock <strong>' + config.name + '</strong> for this section. The teacher will be able to edit this component again.';
    document.getElementById('unlockSubmitText').textContent = 'Unlock ' + config.name;
    
    // Style modal
    const iconDiv = document.getElementById('unlockModalIcon');
    iconDiv.className = 'w-12 h-12 rounded-xl flex items-center justify-center ' + config.bg;
    iconDiv.innerHTML = '<i class="fas ' + config.name.toLowerCase().replace(' ', '-') + ' ' + config.text + ' text-xl"></i>';
    if (component === 'grades') iconDiv.innerHTML = '<i class="fas fa-graduation-cap ' + config.text + ' text-xl"></i>';
    if (component === 'core_values') iconDiv.innerHTML = '<i class="fas fa-heart ' + config.text + ' text-xl"></i>';
    if (component === 'kindergarten') iconDiv.innerHTML = '<i class="fas fa-child ' + config.text + ' text-xl"></i>';
    
    document.getElementById('unlockModalInfo').className = config.bg.replace('100', '50') + ' border rounded-xl p-4 mb-4';
    document.getElementById('unlockModalSubmit').className = 'flex-1 px-4 py-3 bg-gradient-to-r ' + config.btn + ' hover:opacity-90 text-white font-medium rounded-xl transition-all';
    
    // Hide the list
    document.getElementById('unlockModalList').classList.add('hidden');
    
    // Show modal
    document.getElementById('unlockModal').classList.remove('hidden');
    stopAutoRefresh();
}

// Show unlock modal for all components
function unlockAll(sectionId, sectionName, components) {
    console.log('unlockAll called:', sectionId, sectionName, components);
    
    // Set form values
    const form = document.getElementById('unlockForm');
    form.action = '<?php echo e(route("admin.school-year.unlock-all-components")); ?>';
    document.getElementById('unlockSectionId').value = sectionId;
    document.getElementById('unlockComponentType').value = '';
    document.getElementById('unlockReason').value = '';
    
    // Update modal content
    document.getElementById('unlockModalTitle').textContent = 'Unlock All Components';
    document.getElementById('unlockModalSubtitle').textContent = 'Unlock for ' + sectionName;
    document.getElementById('unlockModalDescription').innerHTML = 'The following finalized components will be unlocked:';
    document.getElementById('unlockSubmitText').textContent = 'Unlock All';
    
    // Style modal for amber
    document.getElementById('unlockModalIcon').className = 'w-12 h-12 rounded-xl flex items-center justify-center bg-amber-100';
    document.getElementById('unlockModalIcon').innerHTML = '<i class="fas fa-unlock-alt text-amber-600 text-xl"></i>';
    document.getElementById('unlockModalInfo').className = 'bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4';
    document.getElementById('unlockModalSubmit').className = 'flex-1 px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:opacity-90 text-white font-medium rounded-xl transition-all';
    
    // Show list with finalized components
    const list = document.getElementById('unlockModalList');
    list.classList.remove('hidden');
    list.querySelector('li[data-component="grades"]').classList.toggle('hidden', !components.grades);
    list.querySelector('li[data-component="core_values"]').classList.toggle('hidden', !components.core_values);
    list.querySelector('li[data-component="kindergarten"]').classList.toggle('hidden', !components.kindergarten);
    
    // Show modal
    document.getElementById('unlockModal').classList.remove('hidden');
    stopAutoRefresh();
}

// Close unlock modal
function closeUnlockModal() {
    document.getElementById('unlockModal').classList.add('hidden');
    startAutoRefresh();
}

// Close modal handlers
document.getElementById('unlockModalClose').addEventListener('click', closeUnlockModal);
document.getElementById('unlockModalCancel').addEventListener('click', closeUnlockModal);
document.getElementById('unlockModal').addEventListener('click', function(e) {
    if (e.target === this) closeUnlockModal();
});

// Form submission
const unlockForm = document.getElementById('unlockForm');
if (unlockForm) {
    unlockForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const component = formData.get('component');
        const config = component ? componentConfig[component] : null;
        
        closeUnlockModal();
        
        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json().catch(() => ({ 
                success: true, 
                message: config ? config.name + ' unlocked successfully!' : 'All components unlocked successfully!' 
            }));
            
            if (data.success) {
                showActionModal('success', data.message || (config ? config.name + ' unlocked!' : 'All unlocked!'), true);
            } else {
                showActionModal('error', data.message || 'Failed to unlock');
            }
        } catch (error) {
            console.error('Unlock error:', error);
            showActionModal('error', 'Network error. Please try again.');
        }
    });
}

// Auto-refresh page every 30 seconds to show real-time updates
let autoRefreshInterval;
let countdownInterval;

function startAutoRefresh() {
    // Show refresh indicator
    document.getElementById('autoRefreshIcon').style.display = 'inline-block';
    
    autoRefreshInterval = setInterval(() => {
        // Only refresh if no modal is open
        const openModals = document.querySelectorAll('.fixed:not(.hidden)');
        if (openModals.length === 0) {
            window.location.reload();
        }
    }, 30000); // 30 seconds
    
    // Update countdown
    let seconds = 30;
    countdownInterval = setInterval(() => {
        seconds--;
        if (seconds <= 0) seconds = 30;
    }, 1000);
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    // Hide refresh indicator
    document.getElementById('autoRefreshIcon').style.display = 'none';
}

// Start auto-refresh on page load
document.addEventListener('DOMContentLoaded', startAutoRefresh);

// Stop auto-refresh when any modal is opened
document.querySelectorAll('button[onclick*="show"]').forEach(btn => {
    btn.addEventListener('click', stopAutoRefresh);
});



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

function showCannotEndModal() {
    document.getElementById('cannotEndModal').classList.remove('hidden');
}

function submitEndSchoolYear() {
    // Show loading state
    document.getElementById('endSyInitialState').classList.add('hidden');
    document.getElementById('endSyLoadingState').classList.remove('hidden');
    
    // Create form data
    const formData = new FormData();
    formData.append('_token', '<?php echo e(csrf_token()); ?>');
    formData.append('school_year_id', '<?php echo e($activeSchoolYear->id); ?>');
    
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
        document.getElementById('endSyErrorMessage').textContent = 'An error occurred. Please try again.';
    });
}

// Close modals on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEndSchoolYearModal();
        document.getElementById('cannotEndModal').classList.add('hidden');
        document.getElementById('setDeadlineModal').classList.add('hidden');
        document.getElementById('forceEndModal').classList.add('hidden');
        closeUnlockModal(); // Use the correct function to close unlock modal
    }
});

// Close modals when clicking outside
document.getElementById('endSchoolYearModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEndSchoolYearModal();
    }
});
document.getElementById('cannotEndModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
// Click-outside-to-close for unlock modal (unlockModal is the actual modal ID)
document.getElementById('unlockModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUnlockModal();
    }
});

// Sound effects using Web Audio API
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

function playErrorSound() {
    const ctx = initAudioContext();
    const now = ctx.currentTime;
    const duration = 2.0;
    const interval = 0.4;
    
    // First beep
    const osc1 = ctx.createOscillator();
    const gain1 = ctx.createGain();
    osc1.connect(gain1);
    gain1.connect(ctx.destination);
    
    osc1.type = 'sine';
    osc1.frequency.setValueAtTime(400, now);
    gain1.gain.setValueAtTime(0, now);
    gain1.gain.linearRampToValueAtTime(0.25, now + 0.02);
    gain1.gain.setValueAtTime(0.25, now + 0.1);
    gain1.gain.exponentialRampToValueAtTime(0.001, now + interval);
    osc1.start(now);
    osc1.stop(now + interval);
    
    // Second beep
    const osc2 = ctx.createOscillator();
    const gain2 = ctx.createGain();
    osc2.connect(gain2);
    gain2.connect(ctx.destination);
    
    osc2.type = 'sine';
    osc2.frequency.setValueAtTime(300, now + interval);
    gain2.gain.setValueAtTime(0, now + interval);
    gain2.gain.linearRampToValueAtTime(0.25, now + interval + 0.02);
    gain2.gain.setValueAtTime(0.25, now + interval + 0.1);
    gain2.gain.exponentialRampToValueAtTime(0.001, now + interval * 2);
    osc2.start(now + interval);
    osc2.stop(now + interval * 2);
    
    // Third beep
    const osc3 = ctx.createOscillator();
    const gain3 = ctx.createGain();
    osc3.connect(gain3);
    gain3.connect(ctx.destination);
    
    osc3.type = 'sine';
    osc3.frequency.setValueAtTime(200, now + interval * 2);
    gain3.gain.setValueAtTime(0, now + interval * 2);
    gain3.gain.linearRampToValueAtTime(0.25, now + interval * 2 + 0.02);
    gain3.gain.setValueAtTime(0.25, now + interval * 2 + 0.3);
    gain3.gain.exponentialRampToValueAtTime(0.001, now + duration);
    osc3.start(now + interval * 2);
    osc3.stop(now + duration);
}

// Initialize audio on first user interaction
document.addEventListener('click', function initAudio() {
    initAudioContext();
    document.removeEventListener('click', initAudio);
}, { once: true });

// Modal functions with countdown (countdownInterval is already declared above)

function showActionModal(type, message, autoReload = false) {
    const modal = document.getElementById('actionModal');
    const success = document.getElementById('actionModalSuccess');
    const error = document.getElementById('actionModalError');
    const content = document.getElementById('actionModalContent');
    
    modal.classList.remove('hidden');
    success.classList.add('hidden');
    error.classList.add('hidden');
    
    // Clear any existing countdown
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
    
    if (type === 'success') {
        success.classList.remove('hidden');
        const msgEl = document.getElementById('actionSuccessMessage');
        if (message) {
            if (autoReload) {
                msgEl.innerHTML = message + '<br><span class="text-sm text-slate-400 mt-2 block">Reloading in <span id="countdown">3</span>s...</span>';
                startCountdown();
            } else {
                msgEl.textContent = message;
            }
        }
        playSuccessSound();
    } else if (type === 'error') {
        error.classList.remove('hidden');
        content.classList.add('shake-animation');
        setTimeout(() => content.classList.remove('shake-animation'), 500);
        if (message) document.getElementById('actionErrorMessage').textContent = message;
        playErrorSound();
    }
}

function startCountdown() {
    let seconds = 3;
    const countdownEl = document.getElementById('countdown');
    const progressEl = document.getElementById('countdownProgress');
    
    // Animate progress bar
    if (progressEl) {
        progressEl.style.width = '100%';
        setTimeout(() => {
            progressEl.style.width = '0%';
        }, 50);
    }
    
    countdownInterval = setInterval(() => {
        seconds--;
        if (countdownEl) countdownEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(countdownInterval);
            window.location.reload();
        }
    }, 1000);
}

function closeActionModal() {
    document.getElementById('actionModal').classList.add('hidden');
    if (countdownInterval) {
        clearInterval(countdownInterval);
    }
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const actionModal = document.getElementById('actionModal');
    if (actionModal) {
        actionModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeActionModal();
            }
        });
    }
});
</script>

<style>
    @keyframes modal-pop {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
    .modal-pop {
        animation: modal-pop 0.3s ease-out;
    }
    @keyframes shake-animation {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    .shake-animation {
        animation: shake-animation 0.5s ease-in-out;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\school-years\closure.blade.php ENDPATH**/ ?>