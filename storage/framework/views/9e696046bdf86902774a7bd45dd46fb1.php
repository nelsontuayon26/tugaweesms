<?php $__env->startSection('title', 'Create Event'); ?>
<?php $__env->startSection('header-title', 'New Event'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="<?php echo e(route('admin.events.index')); ?>" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create New Event</h1>
            <p class="text-sm text-slate-500">Add a new school event or activity</p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <form action="<?php echo e(route('admin.events.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            
            <div class="mb-5">
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">Event Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" value="<?php echo e(old('title')); ?>" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                       placeholder="e.g., Foundation Day, Sports Fest, Graduation">
                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-5">
                <label for="date" class="block text-sm font-medium text-slate-700 mb-2">Event Date <span class="text-rose-500">*</span></label>
                <input type="date" name="date" id="date" value="<?php echo e(old('date')); ?>" required
                       class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="5"
                          class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                          placeholder="Enter event details, schedule, location, etc..."><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-1 text-sm text-rose-600"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
                <a href="<?php echo e(route('admin.events.index')); ?>" class="px-5 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-lg shadow-blue-500/30">
                    <i class="fas fa-save mr-2"></i>Create Event
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\events\create.blade.php ENDPATH**/ ?>