<?php $__env->startSection('title', 'Quick Grade Entry'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto" x-data="{ 
    saving: false,
    hasChanges: false,
    showSubmitAll: false,
    grades: <?php echo e(json_encode($grades->mapWithKeys(function($g) {
        return [$g->student_id . '_' . $g->subject_id => $g->final_grade];
    }))); ?>

}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Quick Grade Entry</h1>
            <p class="text-slate-500"><?php echo e($section->name); ?> • Quarter <?php echo e($currentQuarter); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" 
                    @click="if(hasChanges && confirm('You have unsaved changes. Save before leaving?')) { document.getElementById('gradeForm').submit(); }"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </button>
            <button type="submit" 
                    form="gradeForm"
                    @click="saving = true"
                    :disabled="saving"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50">
                <svg x-show="saving" class="w-4 h-4 animate-spin mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i class="fas fa-save mr-2" x-show="!saving"></i>
                <span x-text="saving ? 'Saving...' : 'Save All'"></span>
            </button>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-lightbulb text-blue-600 mt-1"></i>
            <div class="text-sm text-blue-800">
                <p class="font-semibold mb-1">Quick Entry Tips:</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Press <kbd class="px-1.5 py-0.5 bg-white rounded border">Tab</kbd> to move to next cell</li>
                    <li>Press <kbd class="px-1.5 py-0.5 bg-white rounded border">Enter</kbd> to move to next row</li>
                    <li>Grades auto-save when you click "Save All"</li>
                    <li>Valid range: 60-100 (DepEd transmuted grades)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Grade Form -->
    <form id="gradeForm" action="<?php echo e(route('teacher.grades.quick-save', $section)); ?>" method="POST" 
          @change="hasChanges = true">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="quarter" value="<?php echo e($currentQuarter); ?>">

        <!-- Spreadsheet Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-16">#</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider min-w-[200px]">Student Name</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider w-24">LRN</th>
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="px-3 py-3 text-center text-xs font-bold text-slate-500 uppercase tracking-wider w-24" title="<?php echo e($subject->name); ?>">
                                    <div class="truncate max-w-[80px]"><?php echo e(Str::limit($subject->code ?? $subject->name, 8)); ?></div>
                                </th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-2 text-sm text-slate-400"><?php echo e($index + 1); ?></td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xs">
                                            <?php echo e(substr($student->first_name, 0, 1)); ?><?php echo e(substr($student->last_name, 0, 1)); ?>

                                        </div>
                                        <span class="font-medium text-slate-700 text-sm truncate">
                                            <?php echo e($student->last_name); ?>, <?php echo e($student->first_name); ?>

                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-2 text-sm text-slate-500 font-mono"><?php echo e($student->lrn ? substr($student->lrn, -4) : 'N/A'); ?></td>
                                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $gradeKey = $student->id . '_' . $subject->id;
                                        $existingGrade = $grades[$gradeKey] ?? null;
                                    ?>
                                    <td class="px-2 py-2">
                                        <input type="number" 
                                               name="grades[<?php echo e($student->id); ?>][<?php echo e($subject->id); ?>]"
                                               value="<?php echo e($existingGrade?->final_grade); ?>"
                                               min="60" max="100"
                                               step="1"
                                               placeholder="-"
                                               class="w-full px-2 py-1.5 text-center text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 grade-input"
                                               :class="{ 'bg-yellow-50 border-yellow-300': grades['<?php echo e($gradeKey); ?>'] !== $el.value && $el.value !== '' }"
                                               x-on:input="grades['<?php echo e($gradeKey); ?>'] = $el.value"
                                               tabindex="<?php echo e(($index * count($subjects)) + $loop->parent->iteration); ?>">
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 flex justify-end">
            <button type="submit" 
                    @click="saving = true"
                    :disabled="saving"
                    class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-semibold hover:bg-indigo-700 transition-colors disabled:opacity-50 flex items-center gap-2">
                <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i class="fas fa-save" x-show="!saving"></i>
                <span x-text="saving ? 'Saving Grades...' : 'Save All Grades'"></span>
            </button>
        </div>
    </form>
</div>

<style>
    .grade-input::-webkit-inner-spin-button,
    .grade-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .grade-input[type=number] {
        -moz-appearance: textfield;
    }
    kbd {
        font-family: monospace;
        font-size: 0.75rem;
    }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\grades\quick-entry.blade.php ENDPATH**/ ?>