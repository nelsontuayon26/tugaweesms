<?php $__env->startSection('title', 'Teachers Directory'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .p-card { background: white; border: 1px solid #e7e5e4; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.02); }
    .p-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #78716c; padding: 14px 20px; background: #fafaf9; border-bottom: 1px solid #e7e5e4; }
    .p-table td { padding: 14px 20px; border-bottom: 1px solid #f5f5f4; font-size: 0.875rem; color: #44403c; }
    .p-table tbody tr:hover td { background: #fafaf9; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <header class="principal-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-stone-900 tracking-tight">Teachers Directory</h1>
                    <p class="text-xs text-stone-500 mt-0.5">View all faculty members</p>
                </div>
            </div>
            <span class="px-3 py-1.5 bg-stone-100 text-stone-600 rounded-lg text-xs font-bold border border-stone-200">
                <i class="fas fa-eye mr-1"></i> Read-Only
            </span>
        </div>
    </header>

    <main class="principal-content">
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Total Teachers</p>
                        <p class="text-3xl font-bold text-stone-900"><?php echo e($teachers->count()); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600 text-lg">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Active</p>
                        <p class="text-3xl font-bold text-stone-900"><?php echo e($teachers->where('status', 'active')->count()); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg">
                        <i class="fas fa-user-check"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">On Leave</p>
                        <p class="text-3xl font-bold text-stone-900"><?php echo e($teachers->where('status', 'on_leave')->count()); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-card overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-gradient-to-r from-stone-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-purple-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-900 text-sm">Teacher List</h2>
                        <p class="text-[10px] text-stone-400 uppercase tracking-wide font-semibold">Overview of all faculty members</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Teacher</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Sections</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="<?php echo e(profile_photo_url($teacher->user->photo) ?? 'https://ui-avatars.com/api/?name=' . urlencode(($teacher->user->first_name ?? $teacher->first_name) . '+' . ($teacher->user->last_name ?? $teacher->last_name)) . '&background=f5f5f4&color=57534e'); ?>"
                                             alt="" class="w-9 h-9 rounded-full border border-stone-200 object-cover">
                                        <div>
                                            <p class="font-bold text-stone-900 text-sm"><?php echo e($teacher->first_name); ?> <?php echo e($teacher->last_name); ?></p>
                                            <p class="text-[10px] text-stone-400"><?php echo e($teacher->user->username ?? '@teacher'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600"><?php echo e($teacher->user->email ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600"><?php echo e($teacher->mobile_number ?? $teacher->telephone_number ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-purple-50 text-purple-700 font-semibold text-xs border border-purple-100">
                                        <?php echo e($teacher->sections->count() ?? 0); ?> section(s)
                                    </span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold <?php echo e(($teacher->status ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200'); ?>">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <?php echo e(ucfirst($teacher->status ?? 'Active')); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No teachers found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.principal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\principal\teachers\index.blade.php ENDPATH**/ ?>