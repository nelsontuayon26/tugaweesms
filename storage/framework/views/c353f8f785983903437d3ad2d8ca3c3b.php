<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SF3 - Books Issued and Returned - <?php echo e($selectedSection->name ?? ''); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
            body { background: white; }
            .sf3-container { box-shadow: none; border: none; }
        }
        .sf3-header { background: #1e3a8a; color: white; }
        .sf3-table th { background: #e5e7eb; font-weight: bold; text-align: center; }
        .sf3-table td { border: 1px solid #d1d5db; vertical-align: top; }
        .rotate-text { writing-mode: vertical-rl; text-orientation: mixed; transform: rotate(180deg); }
        .book-cell { min-width: 120px; max-width: 150px; }
        .gender-header { background: #f1f5f9 !important; font-weight: bold; text-transform: uppercase; }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

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

<div class="flex">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="lg:ml-72 w-full min-h-screen p-8">
        
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between no-print">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">School Form 3 (SF3)</h1>
                <p class="text-slate-600">Books Issued and Returned</p>
            </div>
            <div class="flex gap-3">
                <form method="GET" class="flex gap-2">
                    <select name="section_id" class="px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>" <?php echo e($selectedSection?->id == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->name); ?> (<?php echo e($section->gradeLevel->name ?? 'N/A'); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </form>
                <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>

        <!-- SF3 Form Container -->
        <div class="overflow-x-auto pb-4">
            <div class="sf3-container bg-white p-8 shadow-lg border border-slate-200 min-w-[1024px]">
            
            <!-- School Header -->
            <div class="text-center mb-6">
                <p class="text-sm font-semibold">School ID: <?php echo e($schoolId); ?></p>
                <h2 class="text-2xl font-bold uppercase"><?php echo e($schoolName); ?></h2>
                <p class="text-sm">District: <?php echo e($schoolDistrict); ?> | Division: <?php echo e($schoolDivision); ?> | Region: <?php echo e($schoolRegion); ?></p>
            </div>

            <!-- Form Title -->
            <div class="sf3-header text-center py-3 mb-4">
                <h3 class="text-xl font-bold">SCHOOL FORM 3 (SF3)</h3>
                <p class="text-lg">BOOKS ISSUED AND RETURNED</p>
            </div>

            <!-- Basic Information -->
            <div class="grid grid-cols-4 gap-4 mb-4 text-sm">
                <div class="border border-slate-800 p-2">
                    <span class="font-bold">School Year:</span> <?php echo e($schoolYear); ?>

                </div>
                <div class="border border-slate-800 p-2">
                    <span class="font-bold">Grade Level:</span> <?php echo e($selectedSection?->gradeLevel?->name ?? 'N/A'); ?>

                </div>
                <div class="border border-slate-800 p-2">
                    <span class="font-bold">Section:</span> <?php echo e($selectedSection?->name ?? 'N/A'); ?>

                </div>
                <div class="border border-slate-800 p-2">
                    <span class="font-bold">Adviser:</span> <?php echo e($adviserName); ?>

                </div>
            </div>

            <!-- Main Table -->
            <table class="w-full sf3-table text-xs border-collapse border-2 border-slate-800">
                <thead>
                    <tr class="bg-slate-200">
                        <th rowspan="2" class="border-2 border-slate-800 p-2 w-8">No.</th>
                        <th rowspan="2" class="border-2 border-slate-800 p-2 w-32">LEARNER'S NAME<br>(Last Name, First Name, Middle Name)</th>
                        <th rowspan="2" class="border-2 border-slate-800 p-2 w-12">Sex<br>(M/F)</th>
                        
                        <!-- Book Columns - Dynamic based on inventory -->
                        <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th colspan="4" class="border-2 border-slate-800 p-2 book-cell">
                                <?php echo e($inventory->subject_area); ?><br>
                                <?php echo e(Str::limit($inventory->title, 30)); ?><br>
                                <span class="text-xs">(<?php echo e($inventory->book_code); ?>)</span>
                            </th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <!-- Additional book slots if needed -->
                        <?php for($i = $bookInventories->count(); $i < 5; $i++): ?>
                            <th colspan="4" class="border-2 border-slate-800 p-2 book-cell">
                                Subject:<br>
                                Title:<br>
                                Code:
                            </th>
                        <?php endfor; ?>
                    </tr>
                    <tr class="bg-slate-100">
                        <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <th class="border border-slate-600 p-1 w-16">Date<br>Issued</th>
                            <th class="border border-slate-600 p-1 w-16">Date<br>Returned</th>
                            <th class="border border-slate-600 p-1 w-12">Condi-<br>tion<br>(New/<br>Good/<br>Used/<br>Damaged)</th>
                            <th class="border border-slate-600 p-1 w-20">Remarks</th>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <?php for($i = $bookInventories->count(); $i < 5; $i++): ?>
                            <th class="border border-slate-600 p-1 w-16">Date<br>Issued</th>
                            <th class="border border-slate-600 p-1 w-16">Date<br>Returned</th>
                            <th class="border border-slate-600 p-1 w-12">Condi-<br>tion</th>
                            <th class="border border-slate-600 p-1 w-20">Remarks</th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $maleCount = $enrollments->filter(function($e) {
                            $g = strtoupper($e->student->gender ?? '');
                            return $g === 'MALE' || $g === 'M';
                        })->count();
                        $displayIndex = 0;
                    ?>

                    <?php if($enrollments->isEmpty()): ?>
                        <tr>
                            <td colspan="<?php echo e(3 + (max(5, $bookInventories->count()) * 4)); ?>" class="border border-slate-600 p-4 text-center text-slate-500">
                                No students found in this section.
                            </td>
                        </tr>
                    <?php else: ?>
                        
                        <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $student = $enrollment->student;
                                if (!$student) continue;
                                $studentBooks = $books->get($student->id, collect())->keyBy('book_code');
                                $gender = strtoupper($student->gender ?? '');
                                $sex = ($gender == 'MALE' || $gender == 'M') ? 'M' : 'F';
                                $isFirstFemale = ($sex === 'F' && $displayIndex === $maleCount && $maleCount > 0);
                                $displayIndex++;
                            ?>
                            
                            
                            <?php if($isFirstFemale): ?>
                                <tr class="gender-header">
                                    <td colspan="<?php echo e(3 + (max(5, $bookInventories->count()) * 4)); ?>" class="border border-slate-600 p-2 text-center text-slate-700">
                                        FEMALE STUDENTS
                                    </td>
                                </tr>
                            <?php endif; ?>
                            
                            <tr>
                                <td class="border border-slate-600 p-2 text-center"><?php echo e($displayIndex); ?></td>
                                <td class="border border-slate-600 p-2">
                                    <?php echo e($student->user->last_name ?? ''); ?>, 
                                    <?php echo e($student->user->first_name ?? ''); ?> 
                                    <?php echo e($student->user->middle_name ?? ''); ?>

                                </td>
                                <td class="border border-slate-600 p-2 text-center"><?php echo e($sex); ?></td>
                                
                                <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $book = $studentBooks->get($inventory->book_code);
                                    ?>
                                    <td class="border border-slate-600 p-1 text-center">
                                        <?php echo e($book?->date_issued ? \Carbon\Carbon::parse($book->date_issued)->format('m/d/Y') : ''); ?>

                                    </td>
                                    <td class="border border-slate-600 p-1 text-center">
                                        <?php echo e($book?->date_returned ? \Carbon\Carbon::parse($book->date_returned)->format('m/d/Y') : ''); ?>

                                    </td>
                                    <td class="border border-slate-600 p-1 text-center">
                                        <?php if($book): ?>
                                            <?php if($book->condition == 'new'): ?> New
                                            <?php elseif($book->condition == 'good'): ?> Good
                                            <?php elseif($book->condition == 'used'): ?> Used
                                            <?php elseif($book->condition == 'damaged'): ?> Damaged
                                            <?php else: ?> <?php echo e($book->condition); ?>

                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="border border-slate-600 p-1 text-center text-xs">
                                        <?php if($book): ?>
                                            <?php if($book->copy_number): ?>
                                                <span class="font-bold">Copy #<?php echo e($book->copy_number); ?></span><br>
                                            <?php endif; ?>
                                            <?php if($book->status == 'lost'): ?>
                                                <span class="text-red-600 font-bold">LOST</span>
                                                <?php if($book->loss_code): ?><br>(<?php echo e($book->loss_code); ?>)<?php endif; ?>
                                            <?php elseif($book->status == 'damaged'): ?>
                                                <span class="text-amber-600">Damaged</span>
                                            <?php elseif($book->date_returned): ?>
                                                <span class="text-emerald-600">Returned</span>
                                            <?php else: ?>
                                                <span class="text-blue-600">Issued</span>
                                            <?php endif; ?>
                                            <?php if($book->remarks): ?><br><?php echo e(Str::limit($book->remarks, 15)); ?><?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                                <?php for($i = $bookInventories->count(); $i < 5; $i++): ?>
                                    <td class="border border-slate-600 p-1"></td>
                                    <td class="border border-slate-600 p-1"></td>
                                    <td class="border border-slate-600 p-1"></td>
                                    <td class="border border-slate-600 p-1"></td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                    
                    
                    <?php for($emptyRow = $displayIndex; $emptyRow < 30; $emptyRow++): ?>
                        <tr>
                            <td class="border border-slate-600 p-2 text-center"><?php echo e($emptyRow + 1); ?></td>
                            <td class="border border-slate-600 p-2"></td>
                            <td class="border border-slate-600 p-2"></td>
                            <?php for($i = 0; $i < max(5, $bookInventories->count()); $i++): ?>
                                <td class="border border-slate-600 p-1"></td>
                                <td class="border border-slate-600 p-1"></td>
                                <td class="border border-slate-600 p-1"></td>
                                <td class="border border-slate-600 p-1"></td>
                            <?php endfor; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>

            <!-- Summary Section -->
            <div class="mt-6 grid grid-cols-2 gap-8">
                <div>
                    <h4 class="font-bold text-sm mb-2">SUMMARY:</h4>
                    <table class="text-xs border border-slate-800">
                        <tr>
                            <td class="border border-slate-600 px-3 py-1">Total Books Issued:</td>
                            <td class="border border-slate-600 px-3 py-1 text-center font-bold"><?php echo e($totalBooksIssued); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-600 px-3 py-1">Total Books Returned:</td>
                            <td class="border border-slate-600 px-3 py-1 text-center font-bold"><?php echo e($totalBooksReturned); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-600 px-3 py-1">Total Books Damaged:</td>
                            <td class="border border-slate-600 px-3 py-1 text-center font-bold text-amber-600"><?php echo e($totalBooksDamaged); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-600 px-3 py-1">Total Books Lost:</td>
                            <td class="border border-slate-600 px-3 py-1 text-center font-bold text-red-600"><?php echo e($totalBooksLost); ?></td>
                        </tr>
                        <tr>
                            <td class="border border-slate-600 px-3 py-1">Books Outstanding:</td>
                            <td class="border border-slate-600 px-3 py-1 text-center font-bold text-blue-600"><?php echo e($totalBooksIssued - $totalBooksReturned); ?></td>
                        </tr>
                    </table>
                </div>
                
                <div>
                    <h4 class="font-bold text-sm mb-2">CERTIFICATION:</h4>
                    <p class="text-xs leading-relaxed mb-4">
                        I hereby certify that the information provided in this form is true and correct to the best of my knowledge.
                    </p>
                    <div class="mt-8">
                        <div class="border-t border-slate-800 w-64"></div>
                        <p class="text-xs font-bold mt-1"><?php echo e($adviserName); ?></p>
                        <p class="text-xs">Adviser/Teacher</p>
                        <p class="text-xs">Date: <?php echo e(date('F d, Y')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-6 text-xs border-t border-slate-300 pt-4">
                <p class="font-bold mb-2">LEGEND:</p>
                <div class="grid grid-cols-4 gap-2">
                    <p><strong>Condition:</strong> New, Used, Damaged</p>
                    <p><strong>Status:</strong> Issued, Returned, Lost, Damaged</p>
                    <p><strong>Loss Codes:</strong> FM=Force Majeure, TDO=Transferred/Dropout, NEG=Negligence</p>
                    <p><strong>Action Codes:</strong> LLTR=Letter from Learner, TLTR=Teacher Letter, PTLTR=Parent/Teacher Letter</p>
                </div>
            </div>
            </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex gap-3 no-print">
            <?php if($selectedSection && $selectedSection->id): ?>
                <a href="<?php echo e(route('teacher.books.issue', ['section' => $selectedSection->id])); ?>" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-plus mr-2"></i>Issue Book
                </a>
                <a href="<?php echo e(route('teacher.books.return', ['section' => $selectedSection->id])); ?>" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                    <i class="fas fa-undo mr-2"></i>Return Book
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('teacher.books.inventory')); ?>" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                <i class="fas fa-boxes mr-2"></i>Book Inventory
            </a>
        </div>

    </div>
</div>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\school-forms\sf3.blade.php ENDPATH**/ ?>