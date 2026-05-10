<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Book - <?php echo e($section->name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="lg:ml-72 w-full min-h-screen p-8">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                <a href="<?php echo e(route('teacher.dashboard')); ?>" class="hover:text-indigo-600">Dashboard</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="<?php echo e(route('teacher.sf3', ['section_id' => $section->id])); ?>" class="hover:text-indigo-600">SF3 - Books</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-indigo-600 font-medium">Issue Book</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-book-medical text-white text-xl"></i>
                        </div>
                        <div>
                            Issue Book
                            <p class="text-sm font-normal text-slate-500 mt-1">
                                <?php echo e($section->name); ?> • <?php echo e($section->gradeLevel->name ?? 'N/A'); ?> • <?php echo e($activeSchoolYear->name); ?>

                            </p>
                        </div>
                    </h1>
                </div>
                <a href="<?php echo e(route('teacher.sf3', ['section_id' => $section->id])); ?>" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to SF3
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-emerald-900">Success!</p>
                    <p class="text-sm text-emerald-700"><?php echo e(session('success')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-red-900">Error!</p>
                    <p class="text-sm text-red-700"><?php echo e(session('error')); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Validation Errors -->
        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                    <p class="font-semibold text-red-900">Please fix the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Issue Form -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl shadow-slate-200/50 p-8" 
             x-data="issueForm()"
             x-init="init()">
            <form method="POST" action="<?php echo e(route('teacher.books.storeIssue')); ?>" @submit.prevent="validateAndSubmit" id="issueForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="section_id" value="<?php echo e($section->id); ?>">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Left Column - Student Selection -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                            <h3 class="font-semibold text-slate-900 flex items-center gap-2 mb-4">
                                <i class="fas fa-user-graduate text-indigo-600"></i>
                                Select Students
                                <span class="text-xs text-slate-500 font-normal" x-show="selectedStudents.length > 0" x-cloak>
                                    (<a href="#" @click.prevent="clearSelection()" class="text-red-500 hover:underline">Clear all</a>)
                                </span>
                                <span class="ml-auto text-xs font-medium px-2 py-1 bg-indigo-100 text-indigo-700 rounded-lg" x-show="selectedStudents.length > 0" x-cloak x-text="selectedStudents.length + ' selected'"></span>
                            </h3>

                            <div class="space-y-4">
                                <!-- Real-time Search -->
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        x-model="searchQuery"
                                        @input="filterStudents()"
                                        placeholder="Search student by name or LRN..."
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                                    >
                                    <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <button 
                                        x-show="searchQuery.length > 0"
                                        @click="searchQuery = ''; filterStudents()"
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        type="button">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>

                                <!-- Student List -->
                                <div class="max-h-96 overflow-y-auto space-y-2 pr-2 custom-scrollbar">
                                    <template x-for="student in displayedStudents" :key="student.id">
                                        <div>
                                            <!-- Male Header -->
                                            <div x-show="student.is_first_male && searchQuery === ''" 
                                                 class="bg-blue-100 text-blue-800 px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2">
                                                <i class="fas fa-mars"></i> Male Students
                                            </div>
                                            <!-- Female Header -->
                                            <div x-show="student.is_first_female && searchQuery === ''" 
                                                 class="bg-pink-100 text-pink-800 px-3 py-2 rounded-lg text-xs font-bold uppercase tracking-wider mb-2 flex items-center gap-2">
                                                <i class="fas fa-venus"></i> Female Students
                                            </div>
                                            
                                            <label 
                                                class="flex items-center gap-3 p-3 rounded-xl border-2 cursor-pointer transition-all"
                                                :class="isSelected(student.id) ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300 hover:bg-slate-50'"
                                            >
                                                <input 
                                                    type="checkbox" 
                                                    name="student_ids[]" 
                                                    :value="student.id"
                                                    :checked="isSelected(student.id)"
                                                    @change="toggleSelection(student.id)"
                                                    class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 rounded"
                                                >
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                                     :class="student.gender === 'M' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600'">
                                                    <span x-text="getInitials(student)"></span>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-slate-900" x-text="student.full_name"></p>
                                                    <p class="text-xs text-slate-500">
                                                        <span :class="student.gender === 'M' ? 'text-blue-600 font-semibold' : 'text-pink-600 font-semibold'" x-text="student.gender === 'M' ? 'Male' : 'Female'"></span> | 
                                                        LRN: <span x-text="student.lrn ?? 'N/A'"></span>
                                                    </p>
                                                </div>
                                                <div x-show="isSelected(student.id)" class="text-indigo-600">
                                                    <i class="fas fa-check-circle text-xl"></i>
                                                </div>
                                            </label>
                                        </div>
                                    </template>
                                    
                                    <div x-show="displayedStudents.length === 0" class="text-center py-8 text-slate-500">
                                        <i class="fas fa-search text-4xl mb-2 text-slate-300"></i>
                                        <p>No students found matching "<span x-text="searchQuery"></span>"</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Students Info -->
                        <div x-show="selectedStudents.length > 0" x-transition class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                            <h4 class="font-semibold text-emerald-900 mb-2">Selected Students (<span x-text="selectedStudents.length"></span>):</h4>
                            <div class="space-y-1 max-h-32 overflow-y-auto">
                                <template x-for="student in getSelectedStudents()" :key="student.id">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-emerald-700" x-text="student.full_name"></span>
                                        <button @click="toggleSelection(student.id)" type="button" class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <!-- Copy Number Preview -->
                            <div x-show="selectedBookId && getCopyNumberPreview().length > 0" class="mt-3 pt-3 border-t border-emerald-200">
                                <p class="text-xs font-semibold text-emerald-800 mb-1">
                                    <i class="fas fa-barcode mr-1"></i>Copy numbers to be assigned:
                                </p>
                                <div class="flex flex-wrap gap-1">
                                    <template x-for="num in getCopyNumberPreview()" :key="num">
                                        <span class="inline-flex items-center px-2 py-0.5 bg-emerald-200 text-emerald-800 text-xs font-bold rounded-md" x-text="'Copy #' + num"></span>
                                    </template>
                                </div>
                            </div>
                            <button @click="clearSelection()" type="button" class="mt-3 text-sm text-red-600 hover:text-red-800 underline">
                                Remove all selections
                            </button>
                        </div>
                    </div>

                    <!-- Right Column - Book Selection -->
                    <div class="space-y-6">
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-2xl p-6 border border-amber-100">
                            <h3 class="font-semibold text-slate-900 flex items-center gap-2 mb-4">
                                <i class="fas fa-book text-amber-600"></i>
                                Select Book <span class="text-red-500">*</span>
                            </h3>

                            <?php if($bookInventories->isEmpty()): ?>
                                <div class="text-center py-8 text-amber-700 bg-amber-100/50 rounded-xl">
                                    <i class="fas fa-exclamation-circle text-3xl mb-2"></i>
                                    <p>No books available in inventory for this grade level.</p>
                                    <a href="<?php echo e(route('teacher.books.createInventory')); ?>" class="inline-block mt-3 text-sm font-medium text-amber-800 hover:underline">
                                        Add books to inventory →
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="space-y-3 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                                    <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all hover:border-amber-300 hover:bg-amber-50/50 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                            <input 
                                                type="radio" 
                                                name="book_inventory_id" 
                                                value="<?php echo e($inventory->id); ?>"
                                                class="w-4 h-4 text-amber-600 focus:ring-amber-500"
                                                required
                                            >
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <p class="font-semibold text-slate-900"><?php echo e($inventory->title); ?></p>
                                                    <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs rounded-lg font-medium">
                                                        <?php echo e($inventory->available_copies); ?> available
                                                    </span>
                                                </div>
                                                <p class="text-sm text-slate-500">
                                                    <?php echo e($inventory->subject_area); ?> • Code: <?php echo e($inventory->book_code); ?>

                                                </p>
                                                <?php if($inventory->isbn): ?>
                                                    <p class="text-xs text-slate-400">ISBN: <?php echo e($inventory->isbn); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Already Issued Warning -->
                        <div x-show="selectedBookId && alreadyIssuedCount > 0" x-transition class="bg-amber-50 rounded-2xl p-4 border border-amber-100">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-amber-600 mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-medium text-amber-800">
                                        <span x-text="alreadyIssuedCount"></span> student(s) already have this book and are hidden from the list.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Issue Details -->
                        <div class="bg-white rounded-2xl p-6 border border-slate-200">
                            <h3 class="font-semibold text-slate-900 flex items-center gap-2 mb-4">
                                <i class="fas fa-calendar-alt text-slate-600"></i>
                                Issue Details
                            </h3>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Date Issued <span class="text-red-500">*</span></label>
                                    <input 
                                        type="date" 
                                        name="date_issued" 
                                        value="<?php echo e(date('Y-m-d')); ?>"
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                                        required
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-2">Condition <span class="text-red-500">*</span></label>
                                    <select 
                                        name="condition" 
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                                        required
                                    >
                                        <option value="new">New</option>
                                        <option value="good">Good</option>
                                        <option value="used">Used</option>
                                        <option value="damaged">Damaged</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Remarks (Optional)</label>
                                <textarea 
                                    name="remarks" 
                                    rows="2"
                                    placeholder="Any additional notes..."
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 resize-none"
                                ></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-end gap-4">
                    <a href="<?php echo e(route('teacher.sf3', ['section_id' => $section->id])); ?>" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-colors">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium rounded-xl shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 transform hover:-translate-y-0.5 transition-all flex items-center gap-2"
                        :disabled="selectedStudents.length === 0"
                        :class="{ 'opacity-50 cursor-not-allowed': selectedStudents.length === 0 }"
                    >
                        <i class="fas fa-check"></i>
                        <span x-show="selectedStudents.length <= 1">Issue Book</span>
                        <span x-show="selectedStudents.length > 1" x-text="'Issue to ' + selectedStudents.length + ' Students'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-4 gap-4">
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-4 border border-white/50 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-mars text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Male Students</p>
                        <p class="text-xl font-bold text-slate-900" id="maleCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-4 border border-white/50 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-100 flex items-center justify-center">
                        <i class="fas fa-venus text-pink-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Female Students</p>
                        <p class="text-xl font-bold text-slate-900" id="femaleCount">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-4 border border-white/50 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                        <i class="fas fa-book text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Book Titles</p>
                        <p class="text-xl font-bold text-slate-900"><?php echo e($bookInventories->count()); ?></p>
                    </div>
                </div>
            </div>
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl p-4 border border-white/50 shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Available</p>
                        <p class="text-xl font-bold text-slate-900"><?php echo e($bookInventories->sum('available_copies')); ?></p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
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
    [x-cloak] { display: none !important; }
</style>

<script>
    function issueForm() {
        return {
            searchQuery: '',
            selectedStudents: [],
            selectedBookId: null,
            allStudents: <?php echo json_encode($studentsFormatted, 15, 512) ?>,
            studentBookCodes: <?php echo json_encode($studentBookCodes, 15, 512) ?>,
            bookCodeMap: <?php echo json_encode($bookInventories->pluck('book_code', 'id'), 512) ?>,
            maxCopyNumbers: <?php echo json_encode($maxCopyNumbers, 15, 512) ?>,
            displayedStudents: [],
            alreadyIssuedCount: 0,

            init() {
                this.filterDisplayedStudents();
                this.updateGenderCounts();

                // Listen for book selection changes
                document.querySelectorAll('input[name="book_inventory_id"]').forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        if (e.target.checked) {
                            this.selectedBookId = parseInt(e.target.value);
                            this.filterDisplayedStudents();
                        }
                    });
                });
            },

            filterDisplayedStudents() {
                let students = this.allStudents;

                // Filter out students who already have the selected book
                if (this.selectedBookId) {
                    const selectedBookCode = this.bookCodeMap[this.selectedBookId];
                    const beforeCount = students.length;
                    students = students.filter(student => {
                        const codes = this.studentBookCodes[student.id] || [];
                        return !codes.includes(selectedBookCode);
                    });
                    this.alreadyIssuedCount = beforeCount - students.length;
                } else {
                    this.alreadyIssuedCount = 0;
                }

                // Apply search filter
                if (this.searchQuery && this.searchQuery.trim() !== '') {
                    const query = this.searchQuery.toLowerCase().trim();
                    students = students.filter(student =>
                        student.full_name.toLowerCase().includes(query) ||
                        (student.lrn && student.lrn.toLowerCase().includes(query))
                    );
                }

                this.displayedStudents = students;
            },

            filterStudents() {
                this.filterDisplayedStudents();
            },

            isSelected(studentId) {
                return this.selectedStudents.includes(studentId);
            },

            toggleSelection(studentId) {
                const index = this.selectedStudents.indexOf(studentId);
                if (index > -1) {
                    this.selectedStudents.splice(index, 1);
                } else {
                    this.selectedStudents.push(studentId);
                }
            },

            clearSelection() {
                this.selectedStudents = [];
                document.querySelectorAll('input[name="student_ids[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            },

            getInitials(student) {
                const names = student.full_name.split(',');
                const lastName = names[0]?.trim() ?? '';
                const firstName = names[1]?.trim() ?? '';
                return (lastName.charAt(0) + firstName.charAt(0)).toUpperCase();
            },

            getSelectedStudents() {
                return this.allStudents.filter(s => this.selectedStudents.includes(s.id));
            },

            getCopyNumberPreview() {
                if (!this.selectedBookId || this.selectedStudents.length === 0) return [];
                const maxCopy = this.maxCopyNumbers[this.selectedBookId] || 0;
                const preview = [];
                for (let i = 1; i <= this.selectedStudents.length; i++) {
                    preview.push(maxCopy + i);
                }
                return preview;
            },

            updateGenderCounts() {
                const males = this.allStudents.filter(s => s.gender === 'M').length;
                const females = this.allStudents.filter(s => s.gender === 'F').length;
                document.getElementById('maleCount').textContent = males;
                document.getElementById('femaleCount').textContent = females;
            },

            validateAndSubmit(e) {
                if (this.selectedStudents.length === 0) {
                    alert('Please select at least one student.');
                    return;
                }

                // Check if book is selected
                const bookSelected = document.querySelector('input[name="book_inventory_id"]:checked');
                if (!bookSelected) {
                    alert('Please select a book from the inventory.');
                    return;
                }

                e.target.submit();
            }
        }
    }
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\books\issue.blade.php ENDPATH**/ ?>