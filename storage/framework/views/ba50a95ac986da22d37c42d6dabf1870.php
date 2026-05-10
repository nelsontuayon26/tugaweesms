<?php $__env->startSection('title', 'School Days Configuration'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                <i class="fas fa-calendar-alt text-2xl"></i>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-slate-900">School Days Configuration</h2>
                <p class="text-slate-500 mt-1"><?php echo e($section->name); ?> • <?php echo e($section->gradeLevel->name ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    <?php if(session('success')): ?>
    <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-emerald-900 text-lg">Success</h3>
            <p class="text-emerald-700"><?php echo e(session('success')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-exclamation-circle text-rose-600 text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-rose-900 text-lg">Error</h3>
            <p class="text-rose-700"><?php echo e(session('error')); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Summary Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 bg-blue-50 rounded-xl">
                <p class="text-sm text-slate-600 mb-1">Month</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo e($schoolDays->getMonthName()); ?></p>
                <p class="text-sm text-slate-500"><?php echo e($year); ?></p>
            </div>
            <div class="text-center p-4 bg-emerald-50 rounded-xl">
                <p class="text-sm text-slate-600 mb-1">School Days</p>
                <p class="text-2xl font-bold text-emerald-600"><?php echo e($schoolDays->total_school_days); ?></p>
                <p class="text-sm text-slate-500">Total</p>
            </div>
            <div class="text-center p-4 bg-rose-50 rounded-xl">
                <p class="text-sm text-slate-600 mb-1">Non-School Days</p>
                <p class="text-2xl font-bold text-rose-600"><?php echo e($schoolDays->getNonSchoolDaysCount()); ?></p>
                <p class="text-sm text-slate-500">Holidays/Suspensions</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-xl">
                <p class="text-sm text-slate-600 mb-1">Status</p>
                <p class="text-lg font-bold text-amber-600"><i class="fas fa-clock mr-1"></i> Configurable</p>
            </div>
        </div>
    </div>

    <!-- Month Selector -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700">Month:</label>
                <select name="month" class="px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <?php $__currentLoopData = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $monthName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($index + 1); ?>" <?php echo e($month == $index + 1 ? 'selected' : ''); ?>><?php echo e($monthName); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-slate-700">Year:</label>
                <select name="year" class="px-4 py-2 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                    <?php for($y = now()->year - 1; $y <= now()->year + 1; $y++): ?>
                    <option value="<?php echo e($y); ?>" <?php echo e($year == $y ? 'selected' : ''); ?>><?php echo e($y); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                <i class="fas fa-filter mr-2"></i>View
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Calendar View -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-lg text-slate-900">Calendar View</h3>
                <div class="flex items-center gap-4 text-sm">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> School Day</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-slate-300"></span> Weekend</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-rose-500"></span> Non-School</span>
                </div>
            </div>

            <div class="grid grid-cols-7 gap-2 mb-2">
                <?php $__currentLoopData = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center text-sm font-medium text-slate-500 py-2"><?php echo e($day); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="grid grid-cols-7 gap-2">
                <?php
                $firstDay = \Carbon\Carbon::create($year, $month, 1);
                $startOffset = $firstDay->dayOfWeek;
                ?>

                <?php for($i = 0; $i < $startOffset; $i++): ?>
                <div class="aspect-square"></div>
                <?php endfor; ?>

                <?php $__currentLoopData = $calendar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="aspect-square rounded-xl border-2 <?php echo e($day['is_school_day'] ? 'border-emerald-200 bg-emerald-50' : ($day['is_weekend'] ? 'border-slate-200 bg-slate-100' : 'border-rose-200 bg-rose-50')); ?> p-2 relative group">
                    <span class="text-sm font-semibold <?php echo e($day['is_school_day'] ? 'text-emerald-700' : ($day['is_weekend'] ? 'text-slate-500' : 'text-rose-700')); ?>">
                        <?php echo e($day['day']); ?>

                    </span>
                    
                    <?php if($day['is_non_school_day'] && $day['reason']): ?>
                    <div class="mt-1 text-[10px] text-rose-600 leading-tight line-clamp-2" title="<?php echo e($day['reason']); ?>">
                        <?php echo e($day['reason']); ?>

                    </div>
                    <?php endif; ?>

                    <?php if(!$day['is_weekend']): ?>
                    <button type="button" 
                            onclick="toggleSchoolDay('<?php echo e($day['date']); ?>', <?php echo e($day['is_non_school_day'] ? 'true' : 'false'); ?>)"
                            class="absolute inset-0 w-full h-full opacity-0 group-hover:opacity-100 bg-black/5 transition-opacity rounded-xl flex items-center justify-center">
                        <i class="fas <?php echo e($day['is_non_school_day'] ? 'fa-plus text-emerald-600' : 'fa-minus text-rose-600'); ?> text-lg"></i>
                    </button>
                    <?php endif; ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Non-School Days List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-bold text-lg text-slate-900 mb-4">Non-School Days</h3>
            
            <?php if(count($schoolDays->non_school_days ?? []) > 0): ?>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                <?php $__currentLoopData = $schoolDays->non_school_days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nonSchoolDay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 p-3 bg-rose-50 rounded-xl border border-rose-100">
                    <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-ban text-rose-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-900">
                            <?php echo e(\Carbon\Carbon::parse($nonSchoolDay['date'])->format('M d, Y')); ?>

                        </p>
                        <p class="text-xs text-slate-500 truncate"><?php echo e($nonSchoolDay['reason']); ?></p>
                    </div>
                    <button type="button" 
                            onclick="removeNonSchoolDay('<?php echo e($nonSchoolDay['date']); ?>')"
                            class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-100 rounded-lg transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php else: ?>
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-calendar-check text-slate-300 text-2xl"></i>
                </div>
                <p class="text-sm text-slate-500">No non-school days configured</p>
            </div>
            <?php endif; ?>

            <button type="button" 
                    onclick="document.getElementById('addNonSchoolDayModal').classList.remove('hidden')"
                    class="w-full mt-4 px-4 py-3 bg-rose-100 hover:bg-rose-200 text-rose-700 rounded-xl font-medium transition-colors flex items-center justify-center gap-2">
                <i class="fas fa-plus"></i>
                Add Non-School Day
            </button>
        </div>
    </div>

    <!-- Teacher Notes -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
        <h3 class="font-bold text-lg text-slate-900 mb-4">Teacher Notes</h3>
        <form action="<?php echo e(route('teacher.sections.attendance.school-days.update', $section)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="month" value="<?php echo e($month); ?>">
            <input type="hidden" name="year" value="<?php echo e($year); ?>">
            
            <textarea name="teacher_notes" rows="4" 
                      class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20"
                      placeholder="Add notes about holidays, suspensions, or other important information..."><?php echo e($schoolDays->teacher_notes); ?></textarea>
            
            <div class="mt-4 flex justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Notes
                </button>
            </div>
        </form>
    </div>

</div>

<!-- Add Non-School Day Modal -->
<div id="addNonSchoolDayModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 relative">
        <button onclick="document.getElementById('addNonSchoolDayModal').classList.add('hidden')" 
                class="absolute top-4 right-4 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>
        
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-ban text-rose-600 text-xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Add Non-School Day</h3>
        </div>
        
        <form id="addNonSchoolDayForm" onsubmit="return addNonSchoolDay(event)">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date</label>
                    <input type="date" id="nonSchoolDate" required
                           min="<?php echo e($year); ?>-<?php echo e(str_pad($month, 2, '0', STR_PAD_LEFT)); ?>-01"
                           max="<?php echo e($year); ?>-<?php echo e(str_pad($month, 2, '0', STR_PAD_LEFT)); ?>-<?php echo e(date('t', mktime(0, 0, 0, $month, 1, $year))); ?>"
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Reason</label>
                    <select id="nonSchoolReason" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 mb-2">
                        <option value="">Select reason...</option>
                        <option value="National Holiday">National Holiday</option>
                        <option value="Local Holiday">Local Holiday</option>
                        <option value="Class Suspension">Class Suspension</option>
                        <option value="Teacher Training">Teacher Training</option>
                        <option value="School Event">School Event</option>
                        <option value="Weather Advisory">Weather Advisory</option>
                        <option value="other">Other (specify)</option>
                    </select>
                    <input type="text" id="nonSchoolReasonOther" 
                           placeholder="Specify other reason..."
                           class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 hidden">
                </div>
            </div>
            
            <div class="flex gap-3 mt-6">
                <button type="button" 
                        onclick="document.getElementById('addNonSchoolDayModal').classList.add('hidden')"
                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-3 bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white font-medium rounded-xl transition-all">
                    Add Day
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle reason input visibility
document.getElementById('nonSchoolReason').addEventListener('change', function() {
    const otherInput = document.getElementById('nonSchoolReasonOther');
    if (this.value === 'other') {
        otherInput.classList.remove('hidden');
        otherInput.required = true;
    } else {
        otherInput.classList.add('hidden');
        otherInput.required = false;
    }
});

// Toggle school day (add/remove non-school day)
function toggleSchoolDay(date, isNonSchoolDay) {
    if (isNonSchoolDay) {
        removeNonSchoolDay(date);
    } else {
        document.getElementById('nonSchoolDate').value = date;
        document.getElementById('addNonSchoolDayModal').classList.remove('hidden');
    }
}

// Add non-school day
function addNonSchoolDay(event) {
    event.preventDefault();
    
    const date = document.getElementById('nonSchoolDate').value;
    const reasonSelect = document.getElementById('nonSchoolReason');
    let reason = reasonSelect.value;
    
    if (reason === 'other') {
        reason = document.getElementById('nonSchoolReasonOther').value;
    }
    
    if (!date || !reason) return;
    
    fetch('<?php echo e(route("teacher.sections.attendance.non-school-day.add", $section)); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ date, reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to add non-school day');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
    
    return false;
}

// Remove non-school day
function removeNonSchoolDay(date) {
    if (!confirm('Remove this non-school day?')) return;
    
    fetch('<?php echo e(route("teacher.sections.attendance.non-school-day.remove", $section)); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ date })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.error || 'Failed to remove non-school day');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.teacher', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\attendance\school-days.blade.php ENDPATH**/ ?>