<!-- teacher/core-values/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Observed Core Values - <?php echo e($section->name ?? 'Section'); ?> - Teacher Portal</title>
    
    <!-- Tailwind CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js for dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .rating-btn.active {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            transform: scale(1.05);
            border-color: transparent;
        }
        
        .statement-rating-btn.active {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .student-card {
            transition: all 0.2s ease;
        }
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
        
        .behavior-statement-row {
            transition: all 0.2s ease;
        }
        .behavior-statement-row:hover {
            background-color: rgba(255, 255, 255, 0.5);
        }
        
        .remarks-textarea {
            resize: none;
            min-height: 50px;
        }
        
        .unsaved-indicator {
            display: none;
        }
        .unsaved-indicator.show {
            display: flex;
        }
        
        /* Modal animations */
        @keyframes modal-pop {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50" x-data="{ mobileOpen: false }">

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

<!-- Mobile Toggle Button -->
<button @click="mobileOpen = !mobileOpen" 
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    <!-- Include Sidebar -->
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-72 min-h-screen">
        
        <!-- Finalization Warning -->
        <?php if(!$isEditable): ?>
        <div class="bg-amber-50 border-b border-amber-200 p-4">
            <div class="flex items-start gap-3 max-w-7xl mx-auto">
                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-lock text-amber-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-amber-900">Core Values Finalized</h3>
                    <p class="text-sm text-amber-700">Core values for this section have been finalized and are locked. Contact the administrator if you need to make changes.</p>
                    <?php if($finalization?->core_values_finalized_at): ?>
                    <p class="text-xs text-amber-600 mt-1">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Finalized on: <?php echo e($finalization->core_values_finalized_at->format('F d, Y \a\t h:i A')); ?>

                    </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Top Header -->
        <header class="bg-white/80 backdrop-blur-xl border-b border-slate-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-8 py-4">
                <div class="flex items-center gap-4">
                    <a href="<?php echo e(route('teacher.sections.students', $section)); ?>" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-all">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-slate-800">Observed Core Values</h1>
                        <p class="text-sm text-slate-500 mt-1">
                            <?php echo e($section->name); ?> (<?php echo e($section->gradeLevel->name ?? 'N/A'); ?>) | 
                            <span class="text-indigo-600 font-medium"><?php echo e($students->count() ?? 0); ?> Students</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <?php if($isEditable): ?>
                    <button onclick="saveAllRatings()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium transition-all shadow-lg shadow-indigo-500/30 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Save All
                    </button>
                    <?php else: ?>
                    <button disabled class="bg-slate-300 text-slate-500 px-6 py-2.5 rounded-xl font-medium cursor-not-allowed flex items-center gap-2">
                        <i class="fas fa-lock"></i>
                        Locked
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <main class="p-8">
            
            <!-- Filters Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    <!-- Quarter Selector -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Quarter</label>
                        <div class="flex items-center gap-2 bg-slate-100 rounded-xl p-1">
                            <?php for($i = 1; $i <= 4; $i++): ?>
                                <button onclick="changeQuarter(<?php echo e($i); ?>)" 
                                        class="quarter-btn flex-1 px-4 py-2 rounded-lg text-sm font-medium transition-all <?php echo e($currentQuarter == $i ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'); ?>"
                                        data-quarter="<?php echo e($i); ?>">
                                    Q<?php echo e($i); ?>

                                </button>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <!-- Core Value Filter -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Filter by Core Value</label>
                        <select id="coreValueFilter" onchange="filterByCoreValue(this.value)" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-slate-50">
                            <option value="all">All Core Values</option>
                            <option value="Maka-Diyos">1. Maka-Diyos</option>
                            <option value="Makatao">2. Makatao</option>
                            <option value="Maka-Kalikasan">3. Maka-Kalikasan</option>
                            <option value="Maka-bansa">4. Maka-bansa</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Search Student</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                            <input type="text" id="searchStudent" onkeyup="searchStudents()" placeholder="Search by name..." 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition-all bg-slate-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Values Legend -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gradient-to-br from-rose-50 to-rose-100 border border-rose-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all" onclick="filterByCoreValue('Maka-Diyos')">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-rose-500 flex items-center justify-center text-white shadow-lg shadow-rose-500/30">
                            <i class="fas fa-praying-hands"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-rose-600 block">1</span>
                            <h3 class="font-bold text-rose-800">Maka-Diyos</h3>
                        </div>
                    </div>
                    <p class="text-xs text-rose-700">Expresses spiritual beliefs while respecting others'</p>
                </div>
                
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all" onclick="filterByCoreValue('Makatao')">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-blue-600 block">2</span>
                            <h3 class="font-bold text-blue-800">Makatao</h3>
                        </div>
                    </div>
                    <p class="text-xs text-blue-700">Sensitive to differences; contributes to solidarity</p>
                </div>
                
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all" onclick="filterByCoreValue('Maka-Kalikasan')">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-emerald-600 block">3</span>
                            <h3 class="font-bold text-emerald-800">Maka-Kalikasan</h3>
                        </div>
                    </div>
                    <p class="text-xs text-emerald-700">Cares for environment; utilizes resources wisely</p>
                </div>
                
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-4 cursor-pointer hover:shadow-md transition-all" onclick="filterByCoreValue('Maka-bansa')">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                            <i class="fas fa-flag"></i>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-amber-600 block">4</span>
                            <h3 class="font-bold text-amber-800">Maka-bansa</h3>
                        </div>
                    </div>
                    <p class="text-xs text-amber-700">Proud Filipino citizen; appropriate behavior</p>
                </div>
            </div>

            <!-- Students Grid -->
            <div id="studentsContainer" class="grid grid-cols-1 gap-6">
                <?php $__empty_1 = true; $__currentLoopData = $students ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    // Group existing ratings by core_value and statement_key for easy lookup
                    $existingRatings = $student->coreValues->keyBy(function($item) {
                        return $item->core_value . '_' . $item->statement_key;
                    });
                ?>
                <div class="student-card bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden" 
                     data-student-id="<?php echo e($student->id); ?>" 
                     data-student-name="<?php echo e(strtolower(($student->user->last_name ?? '') . ' ' . ($student->user->first_name ?? ''))); ?>"
                     data-has-ratings="<?php echo e($existingRatings->isNotEmpty() ? 'true' : 'false'); ?>">
                    
                    <!-- Student Header -->
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 px-6 py-4 border-b border-slate-100 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                            <?php echo e(strtoupper(substr($student->user->first_name, 0, 1))); ?><?php echo e(strtoupper(substr($student->user->last_name, 0, 1))); ?>

                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-lg truncate"><?php echo e($student->user->last_name ?? ''); ?>, <?php echo e($student->user->first_name ?? ''); ?></h3>
                            <p class="text-xs text-slate-500">LRN: <?php echo e($student->lrn ?? 'N/A'); ?> | <span class="text-indigo-600">#<?php echo e($index + 1); ?></span></p>
                        </div>
                        <div class="saved-indicator hidden flex items-center gap-2 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle"></i>
                            <span>Saved</span>
                        </div>
                        <div class="unsaved-indicator flex items-center gap-2 px-3 py-1.5 bg-amber-100 text-amber-700 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Unsaved</span>
                        </div>
                    </div>

                    <!-- Core Values Form -->
                    <div class="p-6 space-y-6">
                        
                        <!-- 1. Maka-Diyos -->
                        <div class="core-value-group border-l-4 border-rose-400 pl-4 py-3 bg-rose-50/20 rounded-r-xl" data-core-value="Maka-Diyos">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center font-bold">1</span>
                                <span class="text-base font-bold text-slate-800 uppercase tracking-wide">Maka-Diyos</span>
                            </div>
                            
                            <!-- Behavior Statement 1 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-rose-100" data-statement-key="statement1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">1.1 Expresses one's spiritual beliefs while respecting the spiritual beliefs of others</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Maka-Diyos', 'statement1')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Maka-Diyos_statement1']->rating ?? '') == $rating ? 'active bg-rose-500 border-rose-500 text-white' : 'border-slate-200 text-slate-400 hover:border-rose-300 hover:text-rose-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement1"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Behavior Statement 2 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-rose-100" data-statement-key="statement2">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">1.2 Shows adherence to ethical principles by upholding truth</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Maka-Diyos', 'statement2')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Maka-Diyos_statement2']->rating ?? '') == $rating ? 'active bg-rose-500 border-rose-500 text-white' : 'border-slate-200 text-slate-400 hover:border-rose-300 hover:text-rose-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement2"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Remarks for Maka-Diyos -->
                            <div class="mt-3">
                                <textarea class="remarks-textarea w-full px-3 py-2 rounded-lg border border-rose-200 bg-white text-sm focus:border-rose-500 focus:ring-2 focus:ring-rose-100 transition-all <?php echo e(!$isEditable ? 'bg-slate-100 cursor-not-allowed' : ''); ?>" 
                                          data-student-id="<?php echo e($student->id); ?>" 
                                          data-core-value="Maka-Diyos"
                                          placeholder="Remarks for Maka-Diyos (optional)..."
                                          oninput="markAsChanged('<?php echo e($student->id); ?>')" <?php echo e(!$isEditable ? 'disabled' : ''); ?>><?php echo e($existingRatings['Maka-Diyos_statement1']->remarks ?? ($existingRatings['Maka-Diyos_statement2']->remarks ?? '')); ?></textarea>
                            </div>
                        </div>

                        <!-- 2. Makatao -->
                        <div class="core-value-group border-l-4 border-blue-400 pl-4 py-3 bg-blue-50/20 rounded-r-xl" data-core-value="Makatao">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold">2</span>
                                <span class="text-base font-bold text-slate-800 uppercase tracking-wide">Makatao</span>
                            </div>
                            
                            <!-- Behavior Statement 1 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-blue-100" data-statement-key="statement1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">2.1 Is sensitive to individual, social, and cultural differences</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Makatao', 'statement1')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Makatao_statement1']->rating ?? '') == $rating ? 'active bg-blue-500 border-blue-500 text-white' : 'border-slate-200 text-slate-400 hover:border-blue-300 hover:text-blue-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement1"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Behavior Statement 2 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-blue-100" data-statement-key="statement2">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">2.2 Demonstrates contributions toward solidarity</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Makatao', 'statement2')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Makatao_statement2']->rating ?? '') == $rating ? 'active bg-blue-500 border-blue-500 text-white' : 'border-slate-200 text-slate-400 hover:border-blue-300 hover:text-blue-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement2"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Remarks for Makatao -->
                            <div class="mt-3">
                                <textarea class="remarks-textarea w-full px-3 py-2 rounded-lg border border-blue-200 bg-white text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-all <?php echo e(!$isEditable ? 'bg-slate-100 cursor-not-allowed' : ''); ?>" 
                                          data-student-id="<?php echo e($student->id); ?>" 
                                          data-core-value="Makatao"
                                          placeholder="Remarks for Makatao (optional)..."
                                          oninput="markAsChanged('<?php echo e($student->id); ?>')" <?php echo e(!$isEditable ? 'disabled' : ''); ?>><?php echo e($existingRatings['Makatao_statement1']->remarks ?? ($existingRatings['Makatao_statement2']->remarks ?? '')); ?></textarea>
                            </div>
                        </div>

                        <!-- 3. Maka-Kalikasan -->
                        <div class="core-value-group border-l-4 border-emerald-400 pl-4 py-3 bg-emerald-50/20 rounded-r-xl" data-core-value="Maka-Kalikasan">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">3</span>
                                <span class="text-base font-bold text-slate-800 uppercase tracking-wide">Maka-Kalikasan</span>
                            </div>
                            
                            <!-- Behavior Statement 1 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-emerald-100" data-statement-key="statement1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">3.1 Cares for the environment and utilizes resources wisely, judiciously, and economically</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Maka-Kalikasan', 'statement1')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Maka-Kalikasan_statement1']->rating ?? '') == $rating ? 'active bg-emerald-500 border-emerald-500 text-white' : 'border-slate-200 text-slate-400 hover:border-emerald-300 hover:text-emerald-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement1"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Remarks for Maka-Kalikasan -->
                            <div class="mt-3">
                                <textarea class="remarks-textarea w-full px-3 py-2 rounded-lg border border-emerald-200 bg-white text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all" 
                                          data-student-id="<?php echo e($student->id); ?>" 
                                          data-core-value="Maka-Kalikasan"
                                          placeholder="Remarks for Maka-Kalikasan (optional)..."
                                          oninput="markAsChanged('<?php echo e($student->id); ?>')"><?php echo e($existingRatings['Maka-Kalikasan_statement1']->remarks ?? ''); ?></textarea>
                            </div>
                        </div>

                        <!-- 4. Maka-bansa -->
                        <div class="core-value-group border-l-4 border-amber-400 pl-4 py-3 bg-amber-50/20 rounded-r-xl" data-core-value="Maka-bansa">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center font-bold">4</span>
                                <span class="text-base font-bold text-slate-800 uppercase tracking-wide">Maka-bansa</span>
                            </div>
                            
                            <!-- Behavior Statement 1 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-amber-100" data-statement-key="statement1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">4.1 Demonstrates pride in being a Filipino; exercises the rights and responsibilities of a Filipino citizen</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Maka-bansa', 'statement1')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Maka-bansa_statement1']->rating ?? '') == $rating ? 'active bg-amber-500 border-amber-500 text-white' : 'border-slate-200 text-slate-400 hover:border-amber-300 hover:text-amber-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement1"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Behavior Statement 2 -->
                            <div class="behavior-statement-row mb-4 p-3 bg-white rounded-xl border border-amber-100" data-statement-key="statement2">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <p class="text-sm text-slate-700 font-medium mb-2">4.2 Demonstrates appropriate behavior in carrying out activities in the school, community, and country</p>
                                        <div class="flex gap-1.5">
                                            <?php $__currentLoopData = ['AO', 'SO', 'RO', 'NO']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" 
                                                        onclick="setStatementRating(this, '<?php echo e($rating); ?>', '<?php echo e($student->id); ?>', 'Maka-bansa', 'statement2')"
                                                        class="statement-rating-btn w-8 h-8 rounded-md border-2 text-xs font-bold transition-all <?php echo e(($existingRatings['Maka-bansa_statement2']->rating ?? '') == $rating ? 'active bg-amber-500 border-amber-500 text-white' : 'border-slate-200 text-slate-400 hover:border-amber-300 hover:text-amber-500'); ?> <?php echo e(!$isEditable ? 'opacity-50 cursor-not-allowed' : ''); ?>"
                                                        data-rating="<?php echo e($rating); ?>"
                                                        data-statement="statement2"
                                                        <?php echo e(!$isEditable ? 'disabled' : ''); ?>>
                                                    <?php echo e($rating); ?>

                                                </button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Remarks for Maka-bansa -->
                            <div class="mt-3">
                                <textarea class="remarks-textarea w-full px-3 py-2 rounded-lg border border-amber-200 bg-white text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 transition-all <?php echo e(!$isEditable ? 'bg-slate-100 cursor-not-allowed' : ''); ?>" 
                                          data-student-id="<?php echo e($student->id); ?>" 
                                          data-core-value="Maka-bansa"
                                          placeholder="Remarks for Maka-bansa (optional)..."
                                          oninput="markAsChanged('<?php echo e($student->id); ?>')" <?php echo e(!$isEditable ? 'disabled' : ''); ?>><?php echo e($existingRatings['Maka-bansa_statement1']->remarks ?? ($existingRatings['Maka-bansa_statement2']->remarks ?? '')); ?></textarea>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Card Footer -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                        <span class="text-sm text-slate-500 status-text flex items-center gap-2">
                            <i class="fas fa-circle text-xs"></i>
                            <span>No changes</span>
                        </span>
                        <?php if($isEditable): ?>
                        <button onclick="saveStudentRatings('<?php echo e($student->id); ?>')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-medium flex items-center gap-2 transition-all shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50">
                            <i class="fas fa-save"></i>
                            Save Student
                        </button>
                        <?php else: ?>
                        <button disabled class="bg-slate-300 text-slate-500 px-5 py-2 rounded-xl text-sm font-medium cursor-not-allowed flex items-center gap-2">
                            <i class="fas fa-lock"></i>
                            Locked
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-slate-400">
                    <i class="fas fa-users text-5xl mb-4 text-slate-300"></i>
                    <p class="text-lg font-medium">No students enrolled in this section</p>
                    <p class="text-sm">Enroll students to start recording core values</p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Finalize Button -->
            <?php if($isEditable && !$finalization?->core_values_finalized): ?>
            <div class="mt-8 bg-white border border-slate-200 rounded-2xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Finalize Core Values</h3>
                        <p class="text-sm text-slate-500">Once finalized, core values will be locked and cannot be edited.</p>
                    </div>
                    <button type="button" 
                            onclick="showFinalizeCoreValuesModal()"
                            class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                        <i class="fas fa-lock mr-2"></i>Finalize Core Values
                    </button>
                </div>
            </div>

            <!-- Finalize Core Values Modal -->
            <div id="finalizeCoreValuesModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4 transform transition-all" style="animation: modal-pop 0.3s ease-out;">
                    <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-emerald-600 text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-emerald-900">Finalize Core Values?</h3>
                                <p class="text-sm text-emerald-600">This action cannot be undone</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="bg-slate-50 rounded-xl p-4 mb-4 space-y-3">
                            <p class="text-sm text-slate-700">
                                <i class="fas fa-info-circle text-emerald-500 mr-2"></i>
                                You are about to finalize core values for <strong><?php echo e($section->name); ?></strong>.
                            </p>
                            <div class="text-sm text-slate-600 space-y-2 ml-6">
                                <p><i class="fas fa-check text-emerald-500 mr-2"></i>All core value ratings will be locked</p>
                                <p><i class="fas fa-check text-emerald-500 mr-2"></i>Behavioral statements for all 4 quarters will be finalized</p>
                                <p><i class="fas fa-check text-emerald-500 mr-2"></i>Admin assistance required for any changes</p>
                            </div>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                            <p class="text-sm text-amber-800">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                <strong>Important:</strong> Make sure you have rated <strong>ALL 4 core values</strong> for <strong>ALL students</strong> across <strong>ALL quarters (Q1-Q4)</strong> before finalizing.
                            </p>
                        </div>
                        <form id="finalizeCoreValuesForm" action="<?php echo e(route('teacher.sections.core-values.finalize', $section)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <button type="button" 
                                        onclick="document.getElementById('finalizeCoreValuesModal').classList.add('hidden')"
                                        class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl transition-colors">
                                    Cancel
                                </button>
                                <button type="submit" 
                                        class="flex-1 px-4 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                                    <i class="fas fa-lock mr-2"></i>Yes, Finalize Core Values
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
                    <h3 class="text-lg font-semibold text-slate-800">Finalizing Core Values...</h3>
                    <p class="text-sm text-slate-500 mt-1">Please wait while we lock your core values records</p>
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
                            <h3 class="text-xl font-bold text-emerald-900">Finalized Successfully!</h3>
                            <p class="text-sm text-emerald-600 mt-1">Core Values have been locked</p>
                        </div>
                        <div class="p-6 text-center">
                            <p class="text-slate-600 mb-4" id="finalizeSuccessMessage">Core Values have been finalized successfully.</p>
                            <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">
                                Continue
                            </button>
                        </div>
                    </div>
                    
                    
                    <div id="finalizeResultError" class="hidden">
                        <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                            <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-red-900">Finalization Failed!</h3>
                            <p class="text-sm text-red-600 mt-1">Unable to finalize core values</p>
                        </div>
                        <div class="p-6 text-center">
                            <p class="text-slate-600 mb-4" id="finalizeErrorMessage">An error occurred while finalizing core values.</p>
                            <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            function showFinalizeCoreValuesModal() {
                document.getElementById('finalizeCoreValuesModal').classList.remove('hidden');
            }
            
            // Handle finalize form submission via AJAX
            document.getElementById('finalizeCoreValuesForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Hide confirmation modal and show loading
                document.getElementById('finalizeCoreValuesModal').classList.add('hidden');
                document.getElementById('finalizingModal').classList.remove('hidden');
                
                const formData = new FormData(this);
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    // Handle 419 CSRF token expired error
                    if (response.status === 419) {
                        document.getElementById('finalizingModal').classList.add('hidden');
                        showFinalizeResultModal('error', 'Session expired. Please refresh the page and try again.');
                        return;
                    }
                    
                    const data = await response.json().catch(() => ({
                        success: response.ok,
                        message: response.ok ? 'Core Values finalized successfully!' : 'Failed to finalize core values'
                    }));
                    
                    // Hide loading modal
                    document.getElementById('finalizingModal').classList.add('hidden');
                    
                    if (data.success) {
                        showFinalizeResultModal('success', data.message || 'Core Values finalized successfully!');
                        // Reload page after showing success
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        showFinalizeResultModal('error', data.message || 'Failed to finalize core values');
                    }
                } catch (error) {
                    console.error('Finalize error:', error);
                    document.getElementById('finalizingModal').classList.add('hidden');
                    showFinalizeResultModal('error', 'Network error. Please try again.');
                }
            });
            
            // Close modal when clicking outside
            document.getElementById('finalizeCoreValuesModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                }
            });
            </script>
            <?php endif; ?>

            <!-- Rating Guide -->
            <div class="mt-8 bg-white border border-slate-200 rounded-2xl p-6">
                <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-indigo-500"></i>
                    Rating Scale Guide (DepEd Order No. 8, s. 2015)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                        <span class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-lg shadow-emerald-500/30">AO</span>
                        <div>
                            <p class="font-bold text-emerald-900 text-sm">Always Observed</p>
                            <p class="text-xs text-emerald-700 mt-1">Demonstrates behavior consistently across all situations</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-blue-50 border border-blue-100">
                        <span class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-lg shadow-blue-500/30">SO</span>
                        <div>
                            <p class="font-bold text-blue-900 text-sm">Sometimes Observed</p>
                            <p class="text-xs text-blue-700 mt-1">Shows behavior frequently but not consistently</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-amber-50 border border-amber-100">
                        <span class="w-10 h-10 rounded-lg bg-amber-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-lg shadow-amber-500/30">RO</span>
                        <div>
                            <p class="font-bold text-amber-900 text-sm">Rarely Observed</p>
                            <p class="text-xs text-amber-700 mt-1">Demonstrates behavior occasionally, needs guidance</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-red-50 border border-red-100">
                        <span class="w-10 h-10 rounded-lg bg-red-600 text-white flex items-center justify-center font-bold text-sm shrink-0 shadow-lg shadow-red-500/30">NO</span>
                        <div>
                            <p class="font-bold text-red-900 text-sm">Not Observed</p>
                            <p class="text-xs text-red-700 mt-1">Does not demonstrate the behavior at this time</p>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed bottom-4 right-4 bg-slate-800 text-white px-6 py-3 rounded-xl shadow-lg transform translate-y-20 opacity-0 transition-all duration-300 z-50 flex items-center gap-3">
    <i id="toastIcon" class="fas fa-check-circle text-emerald-400"></i>
    <span id="toastMessage">Changes saved successfully!</span>
</div>

<script>
// Store pending changes
let pendingChanges = {};

function markAsChanged(studentId) {
    <?php if(!$isEditable): ?>
    showToast('This section is locked. Contact administrator to make changes.', 'warning');
    return;
    <?php endif; ?>
    
    const card = document.querySelector(`[data-student-id="${studentId}"]`);
    updateStatus(card, 'Unsaved changes');
    showUnsavedIndicator(card);
    
    if (!pendingChanges[studentId]) {
        pendingChanges[studentId] = {};
    }
    pendingChanges[studentId].hasChanges = true;
}

function showUnsavedIndicator(card) {
    const unsavedIndicator = card.querySelector('.unsaved-indicator');
    const savedIndicator = card.querySelector('.saved-indicator');
    if (unsavedIndicator) unsavedIndicator.classList.add('show');
    if (savedIndicator) savedIndicator.classList.add('hidden');
}

function hideUnsavedIndicator(card) {
    const unsavedIndicator = card.querySelector('.unsaved-indicator');
    const savedIndicator = card.querySelector('.saved-indicator');
    if (unsavedIndicator) unsavedIndicator.classList.remove('show');
    if (savedIndicator) savedIndicator.classList.remove('hidden');
}

function setStatementRating(btn, rating, studentId, coreValue, statementKey) {
    <?php if(!$isEditable): ?>
    showToast('This section is locked. Contact administrator to make changes.', 'warning');
    return;
    <?php endif; ?>
    
    const row = btn.closest('.behavior-statement-row');
    const buttons = row.querySelectorAll('.statement-rating-btn');
    
    buttons.forEach(b => {
        b.classList.remove('active', 'bg-rose-500', 'border-rose-500', 'text-white', 'bg-blue-500', 'border-blue-500', 'bg-emerald-500', 'border-emerald-500', 'bg-amber-500', 'border-amber-500');
        // Reset to default styling
        const colorMap = {
            'Maka-Diyos': 'rose',
            'Makatao': 'blue',
            'Maka-Kalikasan': 'emerald',
            'Maka-bansa': 'amber'
        };
        const color = colorMap[coreValue];
        b.classList.add(`hover:border-${color}-300`, `hover:text-${color}-500`);
    });
    
    // Add active classes based on core value color
    const activeColors = {
        'Maka-Diyos': ['bg-rose-500', 'border-rose-500'],
        'Makatao': ['bg-blue-500', 'border-blue-500'],
        'Maka-Kalikasan': ['bg-emerald-500', 'border-emerald-500'],
        'Maka-bansa': ['bg-amber-500', 'border-amber-500']
    };
    
    btn.classList.add('active', ...activeColors[coreValue], 'text-white');
    
    // Store in pending changes
    if (!pendingChanges[studentId]) {
        pendingChanges[studentId] = {};
    }
    if (!pendingChanges[studentId][coreValue]) {
        pendingChanges[studentId][coreValue] = {};
    }
    pendingChanges[studentId][coreValue][statementKey] = rating;
    
    updateStatus(btn.closest('.student-card'), 'Unsaved changes');
    showUnsavedIndicator(btn.closest('.student-card'));
    
    // Visual feedback
    btn.style.transform = 'scale(0.9)';
    setTimeout(() => btn.style.transform = 'scale(1)', 150);
}

function saveStudentRatings(studentId) {
    const card = document.querySelector(`[data-student-id="${studentId}"]`);
    const quarter = <?php echo e($currentQuarter); ?>;
    const ratings = [];
    
    // Collect ALL 7 behavior statements for this student
    // Even if not rated, we collect them to ensure complete data
    card.querySelectorAll('.core-value-group').forEach(group => {
        const coreValue = group.dataset.coreValue;
        
        // Get remarks for this core value (shared across statements in same core value)
        const remarksTextarea = group.querySelector('.remarks-textarea');
        const remarks = remarksTextarea ? remarksTextarea.value : '';
        
        group.querySelectorAll('.behavior-statement-row').forEach(row => {
            const activeBtn = row.querySelector('.statement-rating-btn.active');
            const statementText = row.querySelector('p').textContent.trim();
            const statementKey = row.dataset.statementKey; // Get from data attribute
            
            // Collect ALL statements, including those without ratings
            // This ensures all 7 statements are saved to database
            ratings.push({
                core_value: coreValue,
                behavior_statement: statementText,
                statement_key: statementKey, // e.g., "statement1", "statement2"
                rating: activeBtn ? activeBtn.dataset.rating : null, // null if not rated
                remarks: remarks,
                quarter: quarter
            });
        });
    });
    
    // Validate: Check if at least one rating is selected
    const hasAnyRating = ratings.some(r => r.rating !== null);
    if (!hasAnyRating) {
        showToast('Please select at least one rating', 'warning');
        return;
    }
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Show loading state
    const saveBtn = card.querySelector('button[onclick^="saveStudentRatings"]');
    const originalBtnText = saveBtn.innerHTML;
    saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    saveBtn.disabled = true;
    
    // Send to server
    fetch('<?php echo e(route("teacher.sections.core-values.store", $section)); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            student_id: studentId,
            ratings: ratings,
            quarter: quarter
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(text);
            });
        }
        return response.json();
    })
    .then(data => {
        showToast('Core values saved successfully!');
        updateStatus(card, 'Saved');
        hideUnsavedIndicator(card);
        delete pendingChanges[studentId];
        card.style.borderColor = '#10b981';
        setTimeout(() => card.style.borderColor = '', 1000);
    })
    .catch(error => {
        showToast('Error: ' + error.message, 'error');
        console.error('Error:', error);
    })
    .finally(() => {
        saveBtn.innerHTML = originalBtnText;
        saveBtn.disabled = false;
    });
}

function saveAllRatings() {
    const cards = document.querySelectorAll('.student-card');
    let savedCount = 0;
    let hasChanges = false;
    
    cards.forEach(card => {
        const studentId = card.dataset.studentId;
        const unsavedIndicator = card.querySelector('.unsaved-indicator');
        
        if (unsavedIndicator && unsavedIndicator.classList.contains('show')) {
            saveStudentRatings(studentId);
            savedCount++;
            hasChanges = true;
        }
    });
    
    if (!hasChanges) {
        showToast('No unsaved changes to save', 'info');
    } else {
        showToast(`Saving ${savedCount} student(s)...`, 'info');
    }
}

function changeQuarter(quarter) {
    const url = new URL(window.location.href);
    url.searchParams.set('quarter', quarter);
    window.location.href = url.toString();
}

function searchStudents() {
    const searchTerm = document.getElementById('searchStudent').value.toLowerCase();
    const cards = document.querySelectorAll('.student-card');
    
    cards.forEach(card => {
        const name = card.dataset.studentName;
        card.style.display = name.includes(searchTerm) ? '' : 'none';
    });
}

function filterByCoreValue(coreValue) {
    const cards = document.querySelectorAll('.student-card');
    const select = document.getElementById('coreValueFilter');
    
    if (coreValue) {
        select.value = coreValue;
    } else {
        coreValue = select.value;
    }
    
    cards.forEach(card => {
        if (coreValue === 'all') {
            card.style.display = '';
            // Show all groups
            card.querySelectorAll('.core-value-group').forEach(g => g.style.display = '');
            return;
        }
        
        // Show only the selected core value group
        card.querySelectorAll('.core-value-group').forEach(group => {
            if (group.dataset.coreValue === coreValue) {
                group.style.display = '';
            } else {
                group.style.display = 'none';
            }
        });
        card.style.display = '';
    });
}

function updateStatus(card, text) {
    const statusEl = card.querySelector('.status-text');
    const icon = statusEl.querySelector('i');
    const label = statusEl.querySelector('span');
    
    label.textContent = text;
    
    if (text === 'Saved') {
        statusEl.className = 'text-sm text-emerald-600 status-text flex items-center gap-2 font-medium';
        icon.className = 'fas fa-check-circle text-xs';
    } else {
        statusEl.className = 'text-sm text-amber-600 status-text flex items-center gap-2 font-medium';
        icon.className = 'fas fa-circle text-xs';
    }
}

function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');
    const toastIcon = document.getElementById('toastIcon');
    
    toastMessage.textContent = message;
    
    const icons = {
        success: 'fa-check-circle text-emerald-400',
        error: 'fa-exclamation-circle text-red-400',
        warning: 'fa-exclamation-triangle text-amber-400',
        info: 'fa-info-circle text-blue-400'
    };
    
    toastIcon.className = 'fas ' + (icons[type] || icons.success);
    
    toast.classList.remove('translate-y-20', 'opacity-0');
    
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
    }, 3000);
}

// Warn before leaving if there are unsaved changes
window.addEventListener('beforeunload', function(e) {
    if (Object.keys(pendingChanges).length > 0) {
        e.preventDefault();
        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
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

// Finalize Result Modal Functions
function showFinalizeResultModal(type, message) {
    const modal = document.getElementById('finalizeResultModal');
    const success = document.getElementById('finalizeResultSuccess');
    const error = document.getElementById('finalizeResultError');
    const content = document.getElementById('finalizeResultContent');
    
    modal.classList.remove('hidden');
    success.classList.add('hidden');
    error.classList.add('hidden');
    
    if (type === 'success') {
        success.classList.remove('hidden');
        if (message) document.getElementById('finalizeSuccessMessage').textContent = message;
        playSuccessSound();
    } else if (type === 'error') {
        error.classList.remove('hidden');
        content.classList.add('shake-animation');
        setTimeout(() => content.classList.remove('shake-animation'), 500);
        if (message) document.getElementById('finalizeErrorMessage').textContent = message;
        playErrorSound();
    }
}

function closeFinalizeResultModal() {
    document.getElementById('finalizeResultModal').classList.add('hidden');
}

// Show session success/error messages on page load
<?php if(session('success')): ?>
    showFinalizeResultModal('success', '<?php echo e(session('success')); ?>');
<?php endif; ?>
<?php if(session('error')): ?>
    showFinalizeResultModal('error', '<?php echo e(session('error')); ?>');
<?php endif; ?>
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/teacher/core-values/index.blade.php ENDPATH**/ ?>