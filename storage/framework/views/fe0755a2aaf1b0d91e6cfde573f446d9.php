<?php $__env->startSection('title', 'Enrollment Applications'); ?>

<?php $__env->startSection('content'); ?>
<style>
    /* Hide Alpine elements before Alpine loads */
    [x-cloak] { display: none !important; }
    /* Ensure modal is hidden by default */
    .modal-hidden { display: none !important; }
</style>
<div class="max-w-7xl mx-auto" x-data="enrollmentIndexApp()">
    <!-- Toast Notification -->
    <div x-show="toast.show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex flex-col rounded-xl shadow-lg overflow-hidden min-w-[300px]"
         :class="toast.type === 'success' ? 'bg-emerald-500' : toast.type === 'error' ? 'bg-rose-500' : 'bg-amber-500'">
        <div class="flex items-center gap-2 px-4 py-3 text-white">
            <i class="fas" :class="toast.type === 'success' ? 'fa-check-circle' : toast.type === 'error' ? 'fa-exclamation-circle' : 'fa-exclamation-triangle'"></i>
            <span class="font-medium text-sm" x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-auto text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="h-1 bg-white/20">
            <div class="h-full bg-white/60 transition-all ease-linear"
                 :style="`width: ${toast.progress}%; transition-duration: ${toast.duration}ms`">
            </div>
        </div>
    </div>

    <?php
        $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
        $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
    ?>

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Continuing Student Enrollments</h1>
            <p class="text-slate-500">
                Manage online enrollment for <?php echo e($activeSchoolYear->name ?? 'current school year'); ?>

                <?php if($activeSchoolYear && $activeSchoolYear->is_active): ?>
                    <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full">Active</span>
                <?php endif; ?>
            </p>
        </div>
        
        <!-- Enrollment Toggle -->
        <form action="<?php echo e(route('admin.settings.toggle-enrollment')); ?>" method="POST" class="flex items-center gap-3 bg-white px-4 py-3 rounded-xl shadow-sm border border-slate-200" id="enrollmentToggleWrapper">
            <?php echo csrf_field(); ?>
            <div class="text-right">
                <p class="text-sm font-medium text-slate-700">Student Enrollment</p>
                <p class="text-xs text-slate-500">Allow students to submit requests</p>
            </div>
            <input type="hidden" name="enrollment_enabled" value="<?php echo e($enrollmentEnabled ? '0' : '1'); ?>">
            <button type="submit" 
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 <?php echo e($enrollmentEnabled ? 'bg-emerald-500' : 'bg-slate-300'); ?>">
                <span class="sr-only">Toggle enrollment</span>
                <span class="inline-block h-5 w-5 transform rounded-full bg-white transition-transform <?php echo e($enrollmentEnabled ? 'translate-x-6' : 'translate-x-1'); ?>"></span>
            </button>
            <span class="text-xs font-medium <?php echo e($enrollmentEnabled ? 'text-emerald-600' : 'text-slate-500'); ?>">
                <?php echo e($enrollmentEnabled ? 'OPEN' : 'CLOSED'); ?>

            </span>
        </form>
    </div>

    <?php if(!$enrollmentEnabled): ?>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center">
                <i class="fas fa-lock text-amber-600"></i>
            </div>
            <div>
                <p class="font-medium text-amber-800">Enrollment is currently closed</p>
                <p class="text-sm text-amber-700">Students cannot submit enrollment requests. Toggle the switch above to open enrollment.</p>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-slate-500">Total Enrolled</p>
            <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-emerald-600 font-medium">Promoted</p>
            <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['promoted']); ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-amber-600 font-medium">Retained</p>
            <p class="text-2xl font-bold text-amber-600"><?php echo e($stats['retained']); ?></p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-sm text-indigo-600 font-medium">Sections</p>
            <p class="text-2xl font-bold text-indigo-600"><?php echo e($stats['sections']); ?></p>
        </div>
    </div>

    <!-- Grouped by Section -->
    <?php $__empty_1 = true; $__currentLoopData = $groupedEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
        <!-- Section Header -->
        <div class="bg-gradient-to-r from-indigo-50 to-white px-6 py-4 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900"><?php echo e($group['section']->name); ?></h3>
                        <p class="text-sm text-slate-500">
                            <?php echo e($group['section']->gradeLevel?->name ?? 'N/A'); ?>

                            &middot; Adviser: <?php echo e($group['section']->teacher?->user?->full_name ?? 'N/A'); ?>

                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full">
                        <i class="fas fa-mars mr-1"></i> <?php echo e($group['male_count']); ?> Male
                    </span>
                    <span class="px-3 py-1 bg-pink-50 text-pink-700 rounded-full">
                        <i class="fas fa-venus mr-1"></i> <?php echo e($group['female_count']); ?> Female
                    </span>
                    <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-full font-semibold">
                        <?php echo e($group['students']->count()); ?> Total
                    </span>
                </div>
            </div>
        </div>

        <!-- Student List -->
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Student</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">LRN</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Gender</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-slate-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Enrolled</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $__currentLoopData = $group['students']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-3 text-sm text-slate-500"><?php echo e($index + 1); ?></td>
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full <?php echo e(strtolower($enrollment->student->gender) === 'male' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600'); ?> flex items-center justify-center text-xs font-bold">
                                <?php echo e(substr($enrollment->student->first_name ?? 'S', 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-medium text-slate-800"><?php echo e($enrollment->student->full_name ?? ($enrollment->student->first_name . ' ' . $enrollment->student->last_name)); ?></p>
                                <p class="text-xs text-slate-500"><?php echo e($enrollment->student->user?->email ?? 'N/A'); ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-slate-600 font-mono text-sm"><?php echo e($enrollment->student->lrn ?? 'N/A'); ?></td>
                    <td class="px-6 py-3 text-center">
                        <?php if(strtolower($enrollment->student->gender) === 'male'): ?>
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs font-medium">Male</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-pink-50 text-pink-700 rounded text-xs font-medium">Female</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <?php if($enrollment->remarks === 'Promoted'): ?>
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded text-xs font-medium">Promoted</span>
                        <?php elseif($enrollment->remarks === 'Retained'): ?>
                            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded text-xs font-medium">Retained</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-slate-50 text-slate-600 rounded text-xs font-medium">Enrolled</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-3 text-sm text-slate-500"><?php echo e($enrollment->enrollment_date?->format('M d, Y') ?? $enrollment->created_at->format('M d, Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-inbox text-2xl text-slate-300"></i>
        </div>
        <p class="text-sm font-medium text-slate-600">No enrolled students yet</p>
        <p class="text-xs text-slate-400 mt-1">Continuing students will appear here after they submit their enrollment request.</p>
    </div>
    <?php endif; ?>

    <script>
        function enrollmentIndexApp() {
            return {
                toast: { 
                    show: false, 
                    message: '', 
                    type: 'success',
                    progress: 100,
                    duration: 3000
                },

                init() {
                    // Handle session flash messages
                    <?php if(session('success')): ?>
                        this.showToast('<?php echo e(session('success')); ?>', 'success');
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        this.showToast('<?php echo e(session('error')); ?>', 'error');
                    <?php endif; ?>
                    <?php if(session('warning')): ?>
                        this.showToast('<?php echo e(session('warning')); ?>', 'warning');
                    <?php endif; ?>
                },

                showToast(message, type = 'success', duration = 3000) {
                    if (this.toast.timeout) {
                        clearTimeout(this.toast.timeout);
                    }
                    
                    this.toast = { 
                        show: true, 
                        message, 
                        type, 
                        progress: 100, 
                        duration: duration 
                    };
                    
                    setTimeout(() => {
                        this.toast.progress = 0;
                    }, 50);
                    
                    this.toast.timeout = setTimeout(() => {
                        this.toast.show = false;
                    }, duration);
                }
            }
        }
    </script>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\enrollment\admin\index.blade.php ENDPATH**/ ?>