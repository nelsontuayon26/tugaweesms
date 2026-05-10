<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Inventory | Teacher Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .modern-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }

        .modern-table th {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 16px 20px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .modern-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.03) 0%, transparent 100%);
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 20px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #059669);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.4);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
        }

        .inventory-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-available {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .status-low {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .status-out {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .subject-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: all 0.5s ease;
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
        }

        .search-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .action-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
            color: #64748b;
        }

        .action-btn:hover {
            background: #f1f5f9;
            color: #475569;
        }

        @media (hover: none) and (pointer: coarse) {
            .action-btn {
                opacity: 1 !important;
            }
        }

        .action-btn.edit:hover { color: #d97706; background: #fffbeb; }
        .action-btn.delete:hover { color: #dc2626; background: #fef2f2; }
        .action-btn.view:hover { color: #2563eb; background: #eff6ff; }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            font-size: 32px;
            color: #94a3b8;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 antialiased text-slate-800" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

<div class="flex">
    <?php echo $__env->make('teacher.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="lg:ml-72 flex-1 min-w-0 min-h-screen p-8">
            
            <!-- Header -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8 animate-fade-in">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                            <i class="fas fa-boxes text-white text-xl"></i>
                        </div>
                        <div>
                            Book Inventory
                            <p class="text-sm font-normal text-slate-500 mt-1">
                                Manage textbooks and learning materials
                            </p>
                        </div>
                    </h1>
                </div>
                <div class="flex gap-3">
                    <a href="<?php echo e(route('teacher.books.createInventory')); ?>" class="btn-primary">
                        <i class="fas fa-plus"></i>
                        Add Book
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if(session('success')): ?>
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 animate-fade-in">
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
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center gap-3 animate-fade-in">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-red-900">Error!</p>
                        <p class="text-sm text-red-700"><?php echo e(session('error')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Section Selector & Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                <!-- Section Filter -->
                <div class="glass-card p-6 animate-fade-in stagger-1">
                    <h3 class="font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-filter text-indigo-500"></i>
                        Select Section
                    </h3>
                    <form action="<?php echo e(route('teacher.books.inventory')); ?>" method="GET">
                        <select name="section_id" onchange="this.form.submit()" 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($section->id); ?>" <?php echo e($selectedSection?->id == $section->id ? 'selected' : ''); ?>>
                                    <?php echo e($section->name); ?> - <?php echo e($section->gradeLevel->name ?? 'N/A'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <option value="">No sections assigned</option>
                            <?php endif; ?>
                        </select>
                    </form>
                    <?php if($selectedSection): ?>
                        <p class="text-xs text-slate-500 mt-2">
                            <i class="fas fa-graduation-cap mr-1"></i>
                            Grade: <?php echo e($selectedSection->gradeLevel->name ?? 'N/A'); ?>

                        </p>
                    <?php endif; ?>
                </div>

                <!-- Stats Cards -->
                <div class="stat-card animate-fade-in stagger-1">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-book text-blue-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($totalStats['total_titles']); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Total Titles</p>
                    <p class="text-xs text-slate-400 mt-1">Book varieties</p>
                </div>

                <div class="stat-card animate-fade-in stagger-2">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-check-circle text-emerald-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($totalStats['available']); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Available</p>
                    <p class="text-xs text-slate-400 mt-1">Ready to issue</p>
                </div>

                <div class="stat-card animate-fade-in stagger-3">
                    <div class="flex items-center justify-between mb-2">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-hand-holding text-amber-600"></i>
                        </div>
                        <span class="text-2xl font-bold text-slate-900"><?php echo e($totalStats['issued']); ?></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600">Issued</p>
                    <p class="text-xs text-slate-400 mt-1">With students</p>
                </div>
            </div>

            <!-- Detailed Stats -->
            <div class="glass-card p-6 mb-8 animate-fade-in stagger-2">
                <h3 class="font-semibold text-slate-900 mb-4">Inventory Overview</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-slate-50 rounded-xl">
                        <p class="text-2xl font-bold text-slate-900"><?php echo e($totalStats['total_copies']); ?></p>
                        <p class="text-xs text-slate-500 uppercase tracking-wider">Total Copies</p>
                    </div>
                    <div class="text-center p-4 bg-emerald-50 rounded-xl">
                        <p class="text-2xl font-bold text-emerald-600"><?php echo e($totalStats['available']); ?></p>
                        <p class="text-xs text-emerald-600 uppercase tracking-wider">Available</p>
                    </div>
                    <div class="text-center p-4 bg-amber-50 rounded-xl">
                        <p class="text-2xl font-bold text-amber-600"><?php echo e($totalStats['issued']); ?></p>
                        <p class="text-xs text-amber-600 uppercase tracking-wider">Issued</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 rounded-xl">
                        <p class="text-2xl font-bold text-red-600"><?php echo e($totalStats['damaged'] + $totalStats['lost']); ?></p>
                        <p class="text-xs text-red-600 uppercase tracking-wider">Damaged/Lost</p>
                    </div>
                </div>
            </div>

            <!-- Inventory Table -->
            <div class="glass-card overflow-hidden animate-fade-in stagger-3">
                <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <i class="fas fa-list text-amber-500"></i>
                        Book List
                        <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-bold">
                            <?php echo e($bookInventories->count()); ?>

                        </span>
                    </h3>
                    <div class="relative w-full sm:w-64">
                        <input type="text" id="searchInventory" placeholder="Search books..." 
                               class="search-input pl-10"
                               onkeyup="searchTable()">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="modern-table w-full" id="inventoryTable">
                        <thead>
                            <tr>
                                <th>Book Details</th>
                                <th>Subject</th>
                                <th>Copies</th>
                                <th>Status</th>
                                <th>Issued To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $bookInventories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inventory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $totalManaged = $inventory->issued_copies + $inventory->damaged_copies + $inventory->lost_copies;
                                    $availabilityPercent = $inventory->total_copies > 0 
                                        ? ($inventory->available_copies / $inventory->total_copies) * 100 
                                        : 0;
                                    
                                    if ($availabilityPercent == 0) {
                                        $statusClass = 'status-out';
                                        $statusText = 'Out of Stock';
                                    } elseif ($availabilityPercent < 20) {
                                        $statusClass = 'status-low';
                                        $statusText = 'Low Stock';
                                    } else {
                                        $statusClass = 'status-available';
                                        $statusText = 'Available';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white font-bold text-sm">
                                                <?php echo e(strtoupper(substr($inventory->title, 0, 2))); ?>

                                            </div>
                                            <div>
                                                <p class="font-semibold text-slate-900 text-sm"><?php echo e(Str::limit($inventory->title, 40)); ?></p>
                                                <p class="text-xs text-slate-500">
                                                    Code: <?php echo e($inventory->book_code); ?>

                                                    <?php if($inventory->isbn): ?> | ISBN: <?php echo e($inventory->isbn); ?> <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="subject-badge">
                                            <?php echo e($inventory->subject_area); ?>

                                        </span>
                                        <p class="text-xs text-slate-400 mt-1"><?php echo e($inventory->grade_level); ?></p>
                                    </td>
                                    <td>
                                        <div class="w-24">
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="font-semibold text-slate-700"><?php echo e($inventory->available_copies); ?>/<?php echo e($inventory->total_copies); ?></span>
                                            </div>
                                            <div class="progress-bar">
                                                <div class="progress-fill <?php echo e($availabilityPercent < 20 ? 'bg-red-500' : 'bg-emerald-500'); ?>" 
                                                     style="width: <?php echo e($availabilityPercent); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="inventory-badge <?php echo e($statusClass); ?>">
                                            <i class="fas fa-circle text-[8px]"></i>
                                            <?php echo e($statusText); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <p class="text-sm font-semibold text-slate-700">
                                            <?php echo e($inventory->issued_count ?? $inventory->issued_copies); ?>

                                        </p>
                                        <p class="text-xs text-slate-400">students</p>
                                    </td>
                                    <td>
                                        <div class="flex gap-1">
                                            <a href="<?php echo e(route('teacher.books.editInventory', $inventory)); ?>" 
                                               class="action-btn edit" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('teacher.books.destroyInventory', $inventory)); ?>" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Delete this book inventory?');"
                                                  class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="action-btn delete" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <div class="empty-state-icon">
                                                <i class="fas fa-box-open"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">No Books in Inventory</h3>
                                            <p class="text-slate-500 mb-6 max-w-md mx-auto">
                                                No books found for this grade level. Add books to start managing your inventory.
                                            </p>
                                            <a href="<?php echo e(route('teacher.books.createInventory')); ?>" class="btn-primary">
                                                <i class="fas fa-plus mr-2"></i>
                                                Add First Book
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($bookInventories instanceof \Illuminate\Contracts\Pagination\Paginator && $bookInventories->hasPages()): ?>
                    <div class="p-6 border-t border-slate-100">
                        <?php echo e($bookInventories->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
</div>

<!-- Floating Add Button -->
<a href="<?php echo e(route('teacher.books.createInventory')); ?>" 
   class="fixed bottom-8 right-8 w-14 h-14 bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white rounded-full flex items-center justify-center shadow-lg shadow-amber-500/40 transition-all hover:scale-110 hover:rotate-3 z-50"
   title="Add New Book">
    <i class="fas fa-plus text-lg"></i>
</a>

<script>
    function searchTable() {
        const input = document.getElementById('searchInventory');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('inventoryTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            tr[i].style.display = found ? '' : 'none';
        }
    }
</script>

</div>

</div>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\books\inventory.blade.php ENDPATH**/ ?>