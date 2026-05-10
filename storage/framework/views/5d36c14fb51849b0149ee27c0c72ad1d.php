<?php $__env->startSection('title', 'Activity Logs'); ?>

<?php $__env->startSection('header-title', 'Activity Audit Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto" x-data="{ showClearModal: false }" id="activity-logs-page">
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 flex items-center gap-3">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span class="font-medium"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="mb-4 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-rose-600"></i>
            <span class="font-medium"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700">
            <div class="flex items-center gap-2 mb-1">
                <i class="fas fa-exclamation-triangle text-amber-600"></i>
                <span class="font-semibold">Please fix the following:</span>
            </div>
            <ul class="list-disc list-inside text-sm ml-6">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200">
            <p class="text-xs text-slate-500 uppercase">Total (24h)</p>
            <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['total']); ?></p>
        </div>
        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
            <p class="text-xs text-emerald-600 uppercase">Created</p>
            <p class="text-2xl font-bold text-emerald-700"><?php echo e($stats['created']); ?></p>
        </div>
        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
            <p class="text-xs text-blue-600 uppercase">Updated</p>
            <p class="text-2xl font-bold text-blue-700"><?php echo e($stats['updated']); ?></p>
        </div>
        <div class="bg-rose-50 p-4 rounded-xl border border-rose-200">
            <p class="text-xs text-rose-600 uppercase">Deleted</p>
            <p class="text-2xl font-bold text-rose-700"><?php echo e($stats['deleted']); ?></p>
        </div>
        <div class="bg-emerald-50 p-4 rounded-xl border border-emerald-200">
            <p class="text-xs text-emerald-600 uppercase">Approved</p>
            <p class="text-2xl font-bold text-emerald-700"><?php echo e($stats['approved']); ?></p>
        </div>
        <div class="bg-red-50 p-4 rounded-xl border border-red-200">
            <p class="text-xs text-red-600 uppercase">Rejected</p>
            <p class="text-2xl font-bold text-red-700"><?php echo e($stats['rejected']); ?></p>
        </div>
        <div class="bg-indigo-50 p-4 rounded-xl border border-indigo-200">
            <p class="text-xs text-indigo-600 uppercase">Logins</p>
            <p class="text-2xl font-bold text-indigo-700"><?php echo e($stats['logins']); ?></p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border border-slate-200 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Search</label>
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                           placeholder="Search logs..."
                           class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <!-- Action Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Action</label>
                    <select name="action" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="all">All Actions</option>
                        <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($action); ?>" <?php echo e(request('action') == $action ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($action)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Entity Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Entity</label>
                    <select name="entity_type" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="all">All Entities</option>
                        <?php $__currentLoopData = $entityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e(request('entity_type') == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">From</label>
                    <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                           class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">To</label>
                    <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                           class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="<?php echo e(route('admin.activity-logs.index')); ?>" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                    <i class="fas fa-undo mr-2"></i>Reset
                </a>
                <a href="<?php echo e(route('admin.activity-logs.export', request()->query())); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    <i class="fas fa-download mr-2"></i>Export
                </a>
                <button type="button" @click="showClearModal = true" onclick="document.getElementById('clearLogsModal').style.display='block'" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors ml-auto">
                    <i class="fas fa-trash-alt mr-2"></i>Clear Old Logs
                </button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Time</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Action</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Entity</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-slate-600 whitespace-nowrap">
                                <?php echo e($log->created_at->format('M d, Y')); ?>

                                <span class="text-slate-400"><?php echo e($log->created_at->format('h:i A')); ?></span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                        <?php echo e(substr($log->user?->first_name ?? 'S', 0, 1)); ?><?php echo e(substr($log->user?->last_name ?? 'Y', 0, 1)); ?>

                                    </div>
                                    <span class="text-sm text-slate-700">
                                        <?php echo e($log->user?->name ?? 'System'); ?>

                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-<?php echo e($log->action_color); ?>-100 text-<?php echo e($log->action_color); ?>-700">
                                    <i class="fas <?php echo e($log->action_icon); ?>"></i>
                                    <?php echo e(ucfirst($log->action)); ?>

                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600">
                                <?php echo e($log->entity_type); ?>

                                <?php if($log->entity_id): ?>
                                    <span class="text-slate-400">#<?php echo e($log->entity_id); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-700 max-w-md truncate" title="<?php echo e($log->description); ?>">
                                <?php echo e($log->description); ?>

                            </td>
                            <td class="px-4 py-3 text-sm text-slate-500 font-mono">
                                <?php echo e($log->ip_address ?? 'N/A'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-inbox text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-slate-500 font-medium">No activity logs found</p>
                                <p class="text-slate-400 text-sm mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($logs->hasPages()): ?>
            <div class="px-4 py-3 border-t border-slate-200 bg-slate-50">
                <?php echo e($logs->links()); ?>

            </div>
        <?php endif; ?>
    </div>

    <!-- Clear Logs Modal -->
    <div x-show="showClearModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" id="clearLogsModal">
        <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="showClearModal = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-rose-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Clear Old Logs?</h3>
                    <p class="text-slate-600 mb-4">This will permanently delete activity logs older than the specified number of days.</p>
                    
                    <form action="<?php echo e(route('admin.activity-logs.clear')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Delete logs older than (days)</label>
                            <input type="number" name="days" value="30" min="1" max="365"
                                   class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-rose-500">
                        </div>
                        <div class="flex gap-3">
                            <button type="button" @click="showClearModal = false" onclick="document.getElementById('clearLogsModal').style.display='none'"
                                    class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 rounded-xl font-semibold hover:bg-slate-200 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="flex-1 px-4 py-2 bg-rose-600 text-white rounded-xl font-semibold hover:bg-rose-700 transition-colors">
                                Clear Logs
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\activity-logs\index.blade.php ENDPATH**/ ?>