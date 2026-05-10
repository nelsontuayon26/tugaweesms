<div class="overflow-x-auto pb-4">
    <div class="sf3-container bg-white p-8 shadow-lg border border-slate-200 min-w-[1024px]">
    <style>
        .sf3-header { background: #1e3a8a; color: white; }
        .sf3-table th { background: #e5e7eb; font-weight: bold; text-align: center; }
        .sf3-table td { border: 1px solid #d1d5db; vertical-align: top; }
        .book-cell { min-width: 120px; max-width: 150px; }
        .gender-header { background: #f1f5f9 !important; font-weight: bold; text-transform: uppercase; }
    </style>

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
                <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th colspan="4" class="border-2 border-slate-800 p-2 book-cell">
                        <?php echo e($inventory->subject_area); ?><br>
                        <?php echo e(Str::limit($inventory->title, 30)); ?><br>
                        <span class="text-xs">(<?php echo e($inventory->book_code); ?>)</span>
                    </th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php for($i = $bookInventories->count(); $i < 5; $i++): ?>
                    <th colspan="4" class="border-2 border-slate-800 p-2 book-cell">
                        Subject:<br>Title:<br>Code:
                    </th>
                <?php endfor; ?>
            </tr>
            <tr class="bg-slate-100">
                <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th class="border border-slate-600 p-1 w-16">Date<br>Issued</th>
                    <th class="border border-slate-600 p-1 w-16">Date<br>Returned</th>
                    <th class="border border-slate-600 p-1 w-12">Condi-<br>tion</th>
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
                            <?php echo e($student->user->last_name ?? ''); ?>, <?php echo e($student->user->first_name ?? ''); ?> <?php echo e($student->user->middle_name ?? ''); ?>

                        </td>
                        <td class="border border-slate-600 p-2 text-center"><?php echo e($sex); ?></td>
                        <?php $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php $book = $studentBooks->get($inventory->book_code); ?>
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
                                    <?php if($book->status == 'lost'): ?> <span class="text-red-600 font-bold">LOST</span>
                                    <?php elseif($book->status == 'damaged'): ?> <span class="text-amber-600">Damaged</span>
                                    <?php elseif($book->date_returned): ?> <span class="text-emerald-600">Returned</span>
                                    <?php else: ?> <span class="text-blue-600">Issued</span>
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
            <p class="text-xs leading-relaxed mb-4">I hereby certify that the information provided in this form is true and correct to the best of my knowledge.</p>
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
</div>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\reports\sf3.blade.php ENDPATH**/ ?>