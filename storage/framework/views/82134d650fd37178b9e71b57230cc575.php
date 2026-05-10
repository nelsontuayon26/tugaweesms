<?php $__env->startSection('title', 'Pupils Directory'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .p-card { background: white; border: 1px solid #e7e5e4; border-radius: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.02); }
    .p-table th { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #78716c; padding: 14px 20px; background: #fafaf9; border-bottom: 1px solid #e7e5e4; }
    .p-table td { padding: 14px 20px; border-bottom: 1px solid #f5f5f4; font-size: 0.875rem; color: #44403c; }
    .p-table tbody tr:hover td { background: #fafaf9; }
    .page-link { padding: 8px 14px; border-radius: 10px; font-size: 0.875rem; font-weight: 500; color: #57534e; background: white; border: 1px solid #e7e5e4; transition: all 0.2s; }
    .page-link:hover { background: #fffbeb; border-color: #fbbf24; color: #92400e; }
    .page-link.active { background: linear-gradient(135deg, #f59e0b, #ea580c); color: white; border-color: transparent; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <header class="principal-header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-4">
                <div>
                    <h1 class="text-lg font-bold text-stone-900 tracking-tight">Pupils Directory</h1>
                    <p class="text-xs text-stone-500 mt-0.5">View all enrolled pupils</p>
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
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Total Pupils</p>
                        <p class="text-3xl font-bold text-stone-900"><?php echo e($students->total()); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 text-lg">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">School Year</p>
                        <p class="text-xl font-bold text-stone-900"><?php echo e($schoolYear?->name ?? 'N/A'); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 text-lg">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="p-card p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-stone-400 uppercase tracking-wide mb-1">Showing</p>
                        <p class="text-xl font-bold text-stone-900"><?php echo e($students->firstItem() ?? 0); ?> - <?php echo e($students->lastItem() ?? 0); ?></p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 text-lg">
                        <i class="fas fa-list-ol"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-card overflow-hidden">
            <div class="px-5 py-4 border-b border-stone-100 flex items-center justify-between bg-gradient-to-r from-stone-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-blue-600 text-sm"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-stone-900 text-sm">Pupil List</h2>
                        <p class="text-[10px] text-stone-400 uppercase tracking-wide font-semibold">Overview of all enrolled pupils</p>
                    </div>
                </div>
                <form method="GET" action="<?php echo e(route('principal.students.index')); ?>" class="flex items-center gap-2">
                    <?php if(request('school_year_id')): ?>
                        <input type="hidden" name="school_year_id" value="<?php echo e(request('school_year_id')); ?>">
                    <?php endif; ?>
                    <select name="grade" onchange="this.form.submit()"
                            class="text-xs font-medium text-stone-600 bg-white border border-stone-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 cursor-pointer">
                        <option value="">All Grades</option>
                        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($gl->name); ?>" <?php echo e(request('grade') == $gl->name ? 'selected' : ''); ?>><?php echo e($gl->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php if(request('grade')): ?>
                        <a href="<?php echo e(route('principal.students.index', request()->except('grade'))); ?>"
                           class="text-xs text-stone-400 hover:text-red-500 transition-colors px-2 py-1">
                            <i class="fas fa-times"></i>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="p-table w-full text-left">
                    <thead>
                        <tr>
                            <th>Pupil</th>
                            <th>LRN</th>
                            <th>Grade & Section</th>
                            <th>Gender</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <img src="<?php echo e(profile_photo_url($student->user->photo) ?? 'https://ui-avatars.com/api/?name=' . urlencode(($student->user->first_name ?? $student->first_name) . '+' . ($student->user->last_name ?? $student->last_name)) . '&background=f5f5f4&color=57534e'); ?>"
                                             alt="" class="w-9 h-9 rounded-full border border-stone-200 object-cover">
                                        <div>
                                            <a href="<?php echo e(route('principal.students.show', $student)); ?>" class="font-bold text-stone-900 text-sm hover:text-amber-600 transition-colors">
                                                <?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?>

                                            </a>
                                            <p class="text-[10px] text-stone-400"><?php echo e($student->user->email ?? 'No email'); ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-mono text-xs text-stone-500 bg-stone-50 px-2 py-0.5 rounded border border-stone-200"><?php echo e($student->lrn ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <?php
                                        $enrollment = $student->enrollments->first();
                                        $section = $enrollment?->section;
                                        $grade = $section?->gradeLevel?->name ?? $student->gradeLevel?->name ?? 'N/A';
                                    ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg bg-blue-50 text-blue-700 font-semibold text-xs border border-blue-100">
                                        <?php echo e($grade); ?> - <?php echo e($section?->name ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td>
                                    <span class="text-sm text-stone-600"><?php echo e($student->gender); ?></span>
                                </td>
                                <td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold <?php echo e($student->status === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-stone-100 text-stone-600 border border-stone-200'); ?>">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                        <?php echo e(ucfirst($student->status)); ?>

                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center">
                                    <i class="fas fa-inbox text-2xl text-stone-300 mb-2"></i>
                                    <p class="text-stone-400 text-sm">No pupils found</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($students->hasPages()): ?>
                <div class="px-5 py-4 border-t border-stone-100 bg-stone-50/50">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-stone-500">Showing <strong class="text-stone-900"><?php echo e($students->firstItem() ?? 0); ?>-<?php echo e($students->lastItem() ?? 0); ?></strong> of <strong class="text-stone-900"><?php echo e($students->total()); ?></strong></p>
                        <div class="flex items-center gap-2">
                            <?php if($students->onFirstPage()): ?>
                                <span class="page-link opacity-50 cursor-not-allowed">Previous</span>
                            <?php else: ?>
                                <a href="<?php echo e($students->previousPageUrl()); ?>" class="page-link">Previous</a>
                            <?php endif; ?>
                            <?php $__currentLoopData = $students->getUrlRange(1, $students->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($page == $students->currentPage()): ?>
                                    <span class="page-link active"><?php echo e($page); ?></span>
                                <?php else: ?>
                                    <a href="<?php echo e($url); ?>" class="page-link"><?php echo e($page); ?></a>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if($students->hasMorePages()): ?>
                                <a href="<?php echo e($students->nextPageUrl()); ?>" class="page-link">Next</a>
                            <?php else: ?>
                                <span class="page-link opacity-50 cursor-not-allowed">Next</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.principal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\principal\students\index.blade.php ENDPATH**/ ?>