<?php $__env->startSection('title', 'Import Students'); ?>

<?php $__env->startSection('header-title', 'Bulk Import Students'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
        <h3 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle"></i>
            Import Instructions
        </h3>
        <ul class="text-sm text-blue-800 space-y-2 list-disc list-inside">
            <li>Download the template CSV file below</li>
            <li>Fill in student data following the format</li>
            <li>LRN must be exactly 12 digits and unique</li>
            <li>Gender must be either "Male" or "Female"</li>
            <li>Birthdate format: YYYY-MM-DD (e.g., 2010-05-15)</li>
            <li>Select the grade level and school year for all imported students</li>
        </ul>
        <div class="mt-4">
            <a href="<?php echo e(route('admin.import.template.students')); ?>" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
        </div>
    </div>

    <!-- Import Form -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <form action="<?php echo e(route('admin.import.students')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Grade Level -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Grade Level *</label>
                    <select name="grade_level_id" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Grade Level</option>
                        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $level): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($level->id); ?>"><?php echo e($level->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- School Year -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">School Year *</label>
                    <select name="school_year_id" required
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select School Year</option>
                        <?php $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year->id); ?>" <?php echo e($year->is_active ? 'selected' : ''); ?>>
                                <?php echo e($year->name); ?> <?php echo e($year->is_active ? '(Active)' : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- File Upload -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">CSV File *</label>
                <div class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center hover:border-indigo-500 transition-colors">
                    <input type="file" name="csv_file" accept=".csv,.txt" required
                           class="w-full" id="csvFile">
                    <p class="text-sm text-slate-500 mt-2">Drag and drop or click to select file</p>
                    <p class="text-xs text-slate-400 mt-1">Maximum file size: 5MB</p>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex gap-3">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-colors">
                    <i class="fas fa-upload mr-2"></i>Import Students
                </button>
                <a href="<?php echo e(route('admin.students.index')); ?>" class="px-6 py-3 border border-slate-200 text-slate-600 rounded-xl font-semibold hover:bg-slate-50 transition-colors">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Import Errors -->
    <?php if(session('import_errors')): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mt-6">
        <h3 class="font-semibold text-amber-900 mb-3 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i>
            Import Errors (<?php echo e(count(session('import_errors'))); ?>)
        </h3>
        <div class="max-h-64 overflow-y-auto">
            <ul class="text-sm text-amber-800 space-y-1">
                <?php $__currentLoopData = session('import_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="font-mono"><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\import\students.blade.php ENDPATH**/ ?>