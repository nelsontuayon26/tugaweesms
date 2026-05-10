<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Book - <?php echo e($section->name); ?></title>
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
                <span class="text-indigo-600 font-medium">Return Book</span>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                            <i class="fas fa-undo text-white text-xl"></i>
                        </div>
                        <div>
                            Return Book
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

        <?php
            // Format students for JavaScript with gender grouping
            $studentsFormatted = $students->map(function ($student, $index) use ($students) {
                $gender = strtoupper($student->gender ?? '');
                $isMale = ($gender == 'MALE' || $gender == 'M');
                
                $maleCount = $students->filter(function($s) {
                    $g = strtoupper($s->gender ?? '');
                    return $g == 'MALE' || $g == 'M';
                })->count();
                
                return [
                    'id' => $student->id,
                    'full_name' => ($student->user->last_name ?? '') . ', ' . ($student->user->first_name ?? '') . ' ' . ($student->user->middle_name ?? ''),
                    'gender' => $isMale ? 'M' : 'F',
                    'lrn' => $student->lrn ?? null,
                    'books' => $student->books->map(function($book) {
                        return [
                            'id' => $book->id,
                            'title' => $book->title,
                            'subject_area' => $book->subject_area,
                            'book_code' => $book->book_code,
                            'date_issued' => $book->date_issued?->format('M d, Y'),
                            'condition' => $book->condition,
                        ];
                    })->values(),
                    'is_first_female' => (!$isMale && $index === $maleCount),
                    'is_first_male' => ($isMale && $index === 0),
                ];
            })->values();
        ?>

        <?php if($students->isEmpty()): ?>
            <!-- Empty State -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-lg p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-double text-4xl text-slate-400"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No Books to Return</h3>
                <p class="text-slate-500 mb-6 max-w-md mx-auto">
                    All students in this section have returned their books or no books are currently issued.
                </p>
                <a href="<?php echo e(route('teacher.books.issue', $section)); ?>" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-medium shadow-lg inline-flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Issue New Book
                </a>
            </div>
        <?php else: ?>
            <!-- Return Form -->
            <div class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/50 shadow-xl shadow-slate-200/50 p-8" 
                 x-data="returnForm()"
                 x-init="init()">
                
                <!-- Step 1: Select Student -->
                <div class="mb-8" x-show="step === 1" x-transition>
                    <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">1</span>
                        Select Student
                    </h2>

                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                        <!-- Search -->
                        <div class="relative mb-4">
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
                                    
                                    <button 
                                        @click="selectStudent(student)"
                                        class="w-full flex items-center gap-3 p-3 rounded-xl border-2 text-left transition-all"
                                        :class="selectedStudent?.id == student.id ? 'border-indigo-500 bg-indigo-50' : 'border-slate-200 hover:border-indigo-300 hover:bg-slate-50'"
                                    >
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm"
                                             :class="student.gender === 'M' ? 'bg-gradient-to-br from-blue-400 to-blue-600' : 'bg-gradient-to-br from-pink-400 to-pink-600'">
                                            <span x-text="getInitials(student)"></span>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-slate-900" x-text="student.full_name"></p>
                                            <p class="text-xs text-slate-500">
                                                <span :class="student.gender === 'M' ? 'text-blue-600 font-semibold' : 'text-pink-600 font-semibold'" x-text="student.gender === 'M' ? 'Male' : 'Female'"></span> | 
                                                LRN: <span x-text="student.lrn ?? 'N/A'"></span> |
                                                <span class="text-amber-600 font-semibold" x-text="student.books.length + ' book(s)'"></span>
                                            </p>
                                        </div>
                                        <i class="fas fa-chevron-right text-slate-400"></i>
                                    </button>
                                </div>
                            </template>
                            
                            <div x-show="displayedStudents.length === 0" class="text-center py-8 text-slate-500">
                                <i class="fas fa-search text-4xl mb-2 text-slate-300"></i>
                                <p>No students found matching "<span x-text="searchQuery"></span>"</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Select Book to Return -->
                <div class="mb-8" x-show="step === 2" x-transition>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">2</span>
                            Select Book to Return
                        </h2>
                        <button @click="goBack()" class="text-sm text-slate-500 hover:text-indigo-600 flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Change Student
                        </button>
                    </div>

                    <div class="bg-emerald-50 rounded-2xl p-4 mb-6 border border-emerald-100">
                        <p class="text-sm text-emerald-800">
                            <i class="fas fa-user mr-2"></i>
                            Returning book for: <strong x-text="selectedStudent?.full_name"></strong>
                        </p>
                    </div>

                    <div class="space-y-3">
                        <template x-for="book in selectedStudent?.books" :key="book.id">
                            <button 
                                @click="selectBook(book)"
                                class="w-full flex items-center gap-4 p-4 rounded-xl border-2 text-left transition-all"
                                :class="selectedBook?.id == book.id ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-emerald-300 hover:bg-slate-50'"
                            >
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-slate-900" x-text="book.title"></p>
                                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-md" x-text="'Copy #' + (book.copy_number ?? '—')"></span>
                                    </div>
                                    <p class="text-sm text-slate-500" x-text="book.subject_area + ' • Code: ' + book.book_code"></p>
                                    <p class="text-xs text-slate-400 mt-1">
                                        Issued: <span x-text="book.date_issued"></span> | 
                                        Condition: <span class="capitalize" x-text="book.condition"></span>
                                    </p>
                                </div>
                                <div x-show="selectedBook?.id == book.id" class="text-emerald-600">
                                    <i class="fas fa-check-circle text-2xl"></i>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Step 3: Return Details Form -->
                <div x-show="step === 3" x-transition>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold">3</span>
                            Return Details
                        </h2>
                        <button @click="step = 2" class="text-sm text-slate-500 hover:text-indigo-600 flex items-center gap-1">
                            <i class="fas fa-arrow-left"></i> Change Book
                        </button>
                    </div>

                    <form method="POST" action="<?php echo e(route('teacher.books.storeReturn')); ?>" @submit.prevent="validateAndSubmit">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="section_id" value="<?php echo e($section->id); ?>">
                        <input type="hidden" name="book_id" :value="selectedBook?.id">

                        <!-- Summary -->
                        <div class="bg-slate-50 rounded-2xl p-4 mb-6 border border-slate-200">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-slate-500">Student</p>
                                    <p class="font-semibold text-slate-900" x-text="selectedStudent?.full_name"></p>
                                </div>
                                <div>
                                    <p class="text-slate-500">Book</p>
                                    <p class="font-semibold text-slate-900" x-text="selectedBook?.title"></p>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Date Returned -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Date Returned <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    name="date_returned" 
                                    value="<?php echo e(date('Y-m-d')); ?>"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500"
                                    required
                                >
                            </div>

                            <!-- Condition -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-2">
                                    Return Condition <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-3">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="condition" value="new" class="peer sr-only" checked>
                                        <div class="p-3 rounded-xl border-2 border-slate-200 text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                            <i class="fas fa-star text-emerald-500 mb-1"></i>
                                            <p class="text-sm font-medium">New</p>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="condition" value="used" class="peer sr-only">
                                        <div class="p-3 rounded-xl border-2 border-slate-200 text-center peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                            <i class="fas fa-check text-blue-500 mb-1"></i>
                                            <p class="text-sm font-medium">Used</p>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" name="condition" value="damaged" class="peer sr-only" @change="showDamageDetails = true">
                                        <div class="p-3 rounded-xl border-2 border-slate-200 text-center peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                            <i class="fas fa-exclamation-triangle text-red-500 mb-1"></i>
                                            <p class="text-sm font-medium">Damaged</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Damage Details (conditional) -->
                        <div x-show="showDamageDetails" x-transition class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Damage Details <span class="text-red-500">*</span>
                            </label>
                            <textarea 
                                name="damage_details" 
                                rows="2"
                                x-model="damageDetails"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-500 resize-none"
                                placeholder="Describe the damage..."
                                :required="showDamageDetails"
                            ></textarea>
                        </div>

                        <!-- Remarks -->
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-2">
                                Remarks (Optional)
                            </label>
                            <textarea 
                                name="remarks" 
                                rows="2"
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 resize-none"
                                placeholder="Any additional notes..."
                            ></textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-8 flex justify-end gap-4">
                            <button type="button" @click="resetForm()" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-medium transition-colors">
                                Cancel
                            </button>
                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-medium rounded-xl shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2"
                            >
                                <i class="fas fa-check"></i>
                                Confirm Return
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Quick Stats -->
                <div class="mt-8 grid grid-cols-3 gap-4 pt-6 border-t border-slate-200">
                    <div class="text-center p-4 bg-blue-50 rounded-xl">
                        <p class="text-2xl font-bold text-blue-600" id="maleCount">0</p>
                        <p class="text-xs text-blue-600 uppercase">Male Students</p>
                    </div>
                    <div class="text-center p-4 bg-pink-50 rounded-xl">
                        <p class="text-2xl font-bold text-pink-600" id="femaleCount">0</p>
                        <p class="text-xs text-pink-600 uppercase">Female Students</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 rounded-xl">
                        <p class="text-2xl font-bold text-amber-600"><?php echo e($students->sum(fn($s) => $s->books->count())); ?></p>
                        <p class="text-xs text-amber-600 uppercase">Books to Return</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

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
    function returnForm() {
        return {
            step: 1,
            searchQuery: '',
            selectedStudent: null,
            selectedBook: null,
            showDamageDetails: false,
            damageDetails: '',
            allStudents: <?php echo json_encode($studentsFormatted, 15, 512) ?>,
            displayedStudents: [],
            
            init() {
                this.displayedStudents = this.allStudents;
                this.updateGenderCounts();
                
                // Watch for damage condition selection
                document.querySelectorAll('input[name="condition"]').forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        this.showDamageDetails = e.target.value === 'damaged';
                        if (!this.showDamageDetails) {
                            this.damageDetails = '';
                        }
                    });
                });
            },
            
            filterStudents() {
                if (!this.searchQuery || this.searchQuery.trim() === '') {
                    this.displayedStudents = this.allStudents;
                } else {
                    const query = this.searchQuery.toLowerCase().trim();
                    this.displayedStudents = this.allStudents.filter(student => 
                        student.full_name.toLowerCase().includes(query) ||
                        (student.lrn && student.lrn.toLowerCase().includes(query))
                    );
                }
            },
            
            selectStudent(student) {
                this.selectedStudent = student;
                this.step = 2;
                this.searchQuery = '';
            },
            
            selectBook(book) {
                this.selectedBook = book;
                this.step = 3;
            },
            
            goBack() {
                this.step = 1;
                this.selectedStudent = null;
                this.selectedBook = null;
                this.showDamageDetails = false;
            },
            
            resetForm() {
                this.step = 1;
                this.selectedStudent = null;
                this.selectedBook = null;
                this.showDamageDetails = false;
                this.damageDetails = '';
                this.searchQuery = '';
                this.displayedStudents = this.allStudents;
            },
            
            getInitials(student) {
                const names = student.full_name.split(',');
                const lastName = names[0]?.trim() ?? '';
                const firstName = names[1]?.trim() ?? '';
                return (lastName.charAt(0) + firstName.charAt(0)).toUpperCase();
            },
            
            updateGenderCounts() {
                const males = this.allStudents.filter(s => s.gender === 'M').length;
                const females = this.allStudents.filter(s => s.gender === 'F').length;
                const maleEl = document.getElementById('maleCount');
                const femaleEl = document.getElementById('femaleCount');
                if (maleEl) maleEl.textContent = males;
                if (femaleEl) femaleEl.textContent = females;
            },
            
            validateAndSubmit(e) {
                // Validate damage details if damaged selected
                const damagedRadio = document.querySelector('input[name="condition"]:checked');
                if (damagedRadio && damagedRadio.value === 'damaged' && !this.damageDetails.trim()) {
                    alert('Please provide damage details.');
                    return;
                }
                
                e.target.submit();
            }
        }
    }
</script>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\books\return.blade.php ENDPATH**/ ?>