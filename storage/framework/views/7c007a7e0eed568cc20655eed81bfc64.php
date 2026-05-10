<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Pending Registrations - Tugawe ES Portal</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { background: #f8fafc; overflow-x: hidden; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.04);
        }
        
        .table-row { transition: all 0.2s ease; }
        .table-row:hover { background: #f8fafc; }
        
        .btn-enroll {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        .btn-enroll:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .btn-enroll:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(0.5);
        }
        
        .btn-reject {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            box-shadow: 0 4px 14px rgba(239, 68, 68, 0.3);
        }
        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }
        
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .custom-scroll::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: transparent; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        
        .avatar-circle {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .status-badge.pending {
            background: #fef3c7;
            color: #d97706;
        }
        .status-badge.enrolled {
            background: #d1fae5;
            color: #059669;
        }
        .status-badge.graduated {
            background: #dbeafe;
            color: #2563eb;
        }
        .status-badge.rejected {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row overflow-x-hidden bg-slate-50 dark:bg-slate-950" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
        <i class="fas fa-bars text-lg"></i>
    </button>

    <!-- Sidebar -->
    <?php echo $__env->make('admin.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col min-h-screen lg:ml-72">
        
        <!-- Header -->
        <header class="min-h-[5rem] bg-white/80 backdrop-blur-md border-b border-slate-200 flex flex-col lg:flex-row items-start lg:items-center justify-between px-4 lg:px-8 py-3 lg:py-0 sticky top-0 z-30 gap-3 lg:gap-0 pl-14 lg:pl-8">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg shadow-amber-500/30 flex-shrink-0">
                    <i class="fas fa-user-clock text-lg lg:text-xl"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg lg:text-2xl font-bold text-slate-900 truncate">Pending Registrations</h1>
                    <p class="text-xs lg:text-sm text-slate-500 truncate">
                        Review and enroll new student applications
                        <span class="font-semibold text-blue-600">— <?php echo e($schoolYear->name ?? 'No School Year'); ?></span>
                        <?php if($schoolYear && $schoolYear->is_active): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] lg:text-xs rounded-full">Active</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 w-full lg:w-auto">
                <!-- School Year Selector -->
                <form method="GET" action="<?php echo e(route('admin.pending-registrations.index')); ?>" class="flex items-center gap-2 flex-1 lg:flex-none" id="schoolYearForm">
                    <div class="relative w-full lg:w-auto">
                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="school_year" onchange="document.getElementById('schoolYearForm').submit()"
                                class="pl-10 pr-8 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 cursor-pointer hover:border-slate-300 transition-all w-full lg:min-w-[200px]">
                            <?php $__currentLoopData = $schoolYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($year->id); ?>" <?php echo e($schoolYear?->id == $year->id ? 'selected' : ''); ?>>
                                    <?php echo e($year->name); ?> <?php echo e($year->is_active ? '(Current)' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </form>

                <div class="relative flex-1 lg:flex-none">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" id="searchInput" placeholder="Search students..." 
                           class="pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 w-full lg:w-64 transition-all">
                </div>
            </div>
        </header>

        <!-- Toast Container -->
        <div id="toast-container" class="fixed top-4 right-4 flex flex-col gap-2 z-50"></div>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            const createToast = (message, type = 'success', duration = 4) => {
                const colors = {
                    success: { bg: 'bg-emerald-50', border: 'border-emerald-500', text: 'text-emerald-800', iconBg: 'bg-emerald-500' },
                    error: { bg: 'bg-red-50', border: 'border-red-500', text: 'text-red-800', iconBg: 'bg-red-500' },
                    warning: { bg: 'bg-amber-50', border: 'border-amber-500', text: 'text-amber-800', iconBg: 'bg-amber-500' },
                    info: { bg: 'bg-blue-50', border: 'border-blue-500', text: 'text-blue-800', iconBg: 'bg-blue-500' }
                };

                const toast = document.createElement('div');
                toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg border-l-4 ${colors[type].bg} ${colors[type].border} ${colors[type].text} relative overflow-hidden min-w-[300px] transform translate-x-full opacity-0 transition-all duration-500 ease-out`;

                const iconMap = {
                    success: 'check',
                    error: 'exclamation-triangle',
                    warning: 'exclamation-circle',
                    info: 'info-circle'
                };

                toast.innerHTML = `
                    <div class="flex-shrink-0 w-8 h-8 rounded-full ${colors[type].iconBg} text-white flex items-center justify-center text-sm">
                        <i class="fas fa-${iconMap[type]}"></i>
                    </div>
                    <div class="flex-1 text-sm font-medium leading-relaxed">${message}</div>
                    <button class="text-current hover:opacity-70 transition-opacity text-sm ml-2 p-1 hover:bg-white/50 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                    <div class="absolute bottom-0 left-0 h-1 ${colors[type].iconBg} rounded-b-xl transition-all duration-100" style="width:100%"></div>
                `;

                const container = document.getElementById('toast-container');
                container.appendChild(toast);

                requestAnimationFrame(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                });

                toast.querySelector('button').addEventListener('click', () => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                });

                let timeLeft = duration;
                const progressBar = toast.querySelector('div.absolute');
                const interval = setInterval(() => {
                    timeLeft -= 0.05;
                    if (timeLeft <= 0) {
                        clearInterval(interval);
                        toast.style.opacity = '0';
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => toast.remove(), 300);
                        return;
                    }
                    progressBar.style.width = (timeLeft / duration * 100) + '%';
                }, 50);
            };

            <?php if(session('success')): ?>
                createToast("<?php echo e(session('success')); ?>", 'success', 5);
            <?php endif; ?>

            <?php if(session('error')): ?>
                createToast("<?php echo e(session('error')); ?>", 'error', 5);
            <?php endif; ?>

            <?php if(session('warning')): ?>
                createToast("<?php echo e(session('warning')); ?>", 'warning', 5);
            <?php endif; ?>
        });
        </script>

        <!-- Content -->
        <main class="flex-1 overflow-auto p-4 lg:p-8 custom-scroll">

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-amber-600">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-900"><?php echo e($students->total()); ?></p>
                        <p class="text-sm text-slate-500 font-medium">Pending in <?php echo e($schoolYear->name ?? 'Selected Year'); ?></p>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-900"><?php echo e($sidebarStudentCount ?? 0); ?></p>
                        <p class="text-sm text-slate-500 font-medium">Total Students</p>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-900"><?php echo e($enrolledTodayCount ?? 0); ?></p>
                        <p class="text-sm text-slate-500 font-medium">Enrolled Today</p>
                    </div>
                </div>
                <div class="glass-card rounded-2xl p-6 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-purple-100 flex items-center justify-center text-purple-600">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-3xl font-bold text-slate-900"><?php echo e($sections->count() ?? 0); ?></p>
                        <p class="text-sm text-slate-500 font-medium">Available Sections</p>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions Bar -->
            <?php if($students->total() > 0 && $schoolYear): ?>
            <div class="glass-card rounded-2xl p-4 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-slate-900">Bulk Actions</h3>
                        <p class="text-sm text-slate-500">Auto-assign pending students to available sections</p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('admin.pending-registrations.bulk-approve')); ?>" 
                      onsubmit="return confirm('Are you sure you want to bulk approve all pending registrations for <?php echo e($schoolYear->name); ?>? Students will be assigned to sections with available capacity.')"
                      class="w-full sm:w-auto">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="school_year_id" value="<?php echo e($schoolYear->id); ?>">
                    <button type="submit" class="btn-enroll w-full sm:w-auto px-6 py-2.5 rounded-xl text-white font-semibold flex items-center justify-center gap-2 transition-all <?php echo e($sections->isEmpty() ? 'opacity-50 cursor-not-allowed' : ''); ?>" 
                            <?php echo e($sections->isEmpty() ? 'disabled' : ''); ?>>
                        <i class="fas fa-check-double"></i>
                        <span>Bulk Approve All</span>
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <!-- Table -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="p-4 lg:p-6 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div class="min-w-0">
                        <h2 class="text-base lg:text-lg font-bold text-slate-900">Registration Requests</h2>
                        <p class="text-xs lg:text-sm text-slate-500 mt-1">
                            Showing pending enrollments for
                            <span class="font-semibold text-blue-600"><?php echo e($schoolYear->name ?? 'selected school year'); ?></span>
                            <?php if($students->total() > 0): ?>
                                <span class="ml-1 text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full"><?php echo e($students->total()); ?> pending</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <span class="text-xs lg:text-sm text-slate-500 whitespace-nowrap">
                        Showing <span class="font-semibold text-slate-900"><?php echo e($students->count()); ?></span> of
                        <span class="font-semibold text-slate-900"><?php echo e($students->total()); ?></span>
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Grade Level</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Applied</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $currentEnrollment = $student->enrollments->where('school_year_id', $schoolYear?->id)->first();
                                ?>
                                <tr class="table-row animate-fade-in" style="animation-delay: <?php echo e(min($loop->index * 0.05, 0.5)); ?>s">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-full avatar-circle flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                <?php echo e(strtoupper(substr($student->user->first_name ?? 'A', 0, 1))); ?><?php echo e(strtoupper(substr($student->user->last_name ?? '', 0, 1))); ?>

                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900">
                                                    <?php echo e($student->user->last_name ?? 'N/A'); ?>, <?php echo e($student->user->first_name ?? 'N/A'); ?> <?php echo e($student->user->middle_name ? substr($student->user->middle_name, 0, 1) . '.' : ''); ?>

                                                </p>
                                                <p class="text-sm text-slate-500"><?php echo e($student->user->email ?? 'No email'); ?></p>
                                                <p class="text-xs text-slate-400 font-mono mt-0.5">LRN: <?php echo e($student->lrn ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 text-sm font-semibold">
                                            <i class="fas fa-graduation-cap text-xs"></i>
                                            Grade <?php echo e($student->gradeLevel->name ?? 'N/A'); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php if($currentEnrollment): ?>
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-50 text-purple-700 text-sm font-semibold">
                                                <?php echo e($currentEnrollment->type); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-slate-400 text-sm">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600">
                                            <?php echo e($student->created_at?->diffForHumans() ?? 'N/A'); ?>

                                        </span>
                                        <p class="text-xs text-slate-400 mt-0.5"><?php echo e($student->created_at?->format('M d, Y')); ?></p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="status-badge pending">
                                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                            <?php echo e($student->status); ?>

                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" 
                                                    onclick="openStudentModal(<?php echo e($student->id); ?>)"
                                                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-blue-50 text-slate-600 hover:text-blue-600 flex items-center justify-center transition-all"
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <button type="button"
                                                    onclick="openDeleteModal(<?php echo e($student->id); ?>, '<?php echo e($student->user->last_name ?? 'N/A'); ?>, <?php echo e($student->user->first_name ?? 'N/A'); ?>')"
                                                    class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-red-50 text-slate-600 hover:text-red-600 flex items-center justify-center transition-all"
                                                    title="Delete Registration">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="max-w-md mx-auto">
                                            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-inbox text-3xl text-slate-400"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-900 mb-2">No Pending Registrations</h3>
                                            <p class="text-slate-500 mb-4">No pending enrollment applications found for <?php echo e($schoolYear->name ?? 'this school year'); ?>.</p>
                                            <?php if($schoolYear && !$schoolYear->is_active): ?>
                                                <p class="text-sm text-amber-600 bg-amber-50 rounded-lg py-2 px-4 inline-block">
                                                    <i class="fas fa-info-circle mr-1"></i>
                                                    This is a past school year. Switch to current year to see new registrations.
                                                </p>
                                            <?php elseif($schoolYear && $schoolYear->is_active): ?>
                                                <p class="text-sm text-slate-500">
                                                    New student registrations will appear here when they scan the QR code or register online.
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($students->hasPages()): ?>
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        <?php echo e($students->appends(['school_year' => $schoolYear?->id])->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Student Details Slide-in Modal -->
    <div id="studentModal" class="fixed inset-0 z-50 hidden">
        <div id="modalBackdrop" class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeStudentModal()"></div>
        
        <div id="modalPanel" class="absolute right-0 top-0 h-full w-full max-w-2xl bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col">
            
            <!-- Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Pupil Details</h3>
                        <p class="text-sm text-slate-500">ID: <span id="modalStudentId">-</span></p>
                    </div>
                </div>
                <button onclick="closeStudentModal()" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:border-slate-300 transition-all hover:rotate-90 duration-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto custom-scroll p-6" id="modalBody">
                
                <!-- Loading State -->
                <div id="modalLoading" class="flex flex-col items-center justify-center h-64">
                    <div class="w-12 h-12 border-4 border-blue-200 border-t-blue-500 rounded-full animate-spin mb-4"></div>
                    <p class="text-slate-500 font-medium">Loading student data...</p>
                </div>

                <!-- Error State -->
                <div id="modalError" class="hidden text-center py-12">
                    <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Failed to Load</h3>
                    <p id="modalErrorMessage" class="text-red-600 text-sm mb-4 font-mono bg-red-50 p-3 rounded-lg"></p>
                    <button onclick="closeStudentModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-700 transition-colors">
                        Close
                    </button>
                </div>

                <!-- Content -->
                <div id="modalContent" class="hidden space-y-6">
                    
                    <!-- Student Header Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-100">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div id="modalPhoto" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-xl sm:text-2xl font-bold shadow-lg shrink-0">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 id="modalName" class="text-xl sm:text-2xl font-bold text-slate-900 mb-1 truncate">-</h4>
                                <p id="modalEmail" class="text-slate-500 mb-3 truncate">-</p>
                                <div class="flex flex-wrap gap-2">
                                    <span id="modalGrade" class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-sm font-semibold">-</span>
                                    <span class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 text-sm font-semibold flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                        Pending Enrollment
                                    </span>
                                    <span id="modalAge" class="px-3 py-1 rounded-lg bg-slate-100 text-slate-600 text-sm font-semibold">-</span>
                                    <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-sm font-semibold">
                                        <?php echo e($schoolYear->name ?? '-'); ?>

                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Full Name</p>
                            <p id="modalFullName" class="font-semibold text-slate-900 truncate">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Birthdate</p>
                            <p id="modalBirthdate" class="font-semibold text-slate-900">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Birth Place</p>
                            <p id="modalBirthPlace" class="font-semibold text-slate-900">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Gender</p>
                            <p id="modalGender" class="font-semibold text-slate-900 capitalize">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Nationality</p>
                            <p id="modalNationality" class="font-semibold text-slate-900">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Religion</p>
                            <p id="modalReligion" class="font-semibold text-slate-900">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Ethnicity</p>
                            <p id="modalEthnicity" class="font-semibold text-slate-900">-</p>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Mother Tongue</p>
                            <p id="modalMotherTongue" class="font-semibold text-slate-900">-</p>
                        </div>
                    </div>

                    <!-- Remarks Badge -->
                    <div id="modalRemarksContainer" class="hidden">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-50 border border-amber-200">
                            <i class="fas fa-sticky-note text-amber-600"></i>
                            <span class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Remark:</span>
                            <span id="modalRemarksBadge" class="text-sm font-bold text-amber-800">-</span>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Contact Information</h5>
                        <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 space-y-3">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-slate-400 mt-1 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 mb-1">Address</p>
                                    <p id="modalAddress" class="font-semibold text-slate-900 text-sm leading-relaxed">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-slate-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 mb-1">Guardian Contact</p>
                                    <p id="modalGuardianContact" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-envelope text-slate-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 mb-1">Email</p>
                                    <p id="modalEmail2" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user-circle text-slate-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-slate-500 mb-1">Username</p>
                                    <p id="modalUsername" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Family Info -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Family Information</h5>
                        <div class="grid grid-cols-1 gap-3">
                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                                <p class="text-xs text-blue-600 mb-1 font-semibold uppercase">Father</p>
                                <p id="modalFather" class="font-semibold text-slate-900">-</p>
                                <p id="modalFatherOccupation" class="text-sm text-slate-500"></p>
                                <p id="modalFatherContact" class="text-sm text-slate-500"></p>
                            </div>
                            <div class="p-4 bg-pink-50/50 rounded-xl border border-pink-100">
                                <p class="text-xs text-pink-600 mb-1 font-semibold uppercase">Mother</p>
                                <p id="modalMother" class="font-semibold text-slate-900">-</p>
                                <p id="modalMotherOccupation" class="text-sm text-slate-500"></p>
                                <p id="modalMotherContact" class="text-sm text-slate-500"></p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1 font-semibold uppercase">Guardian</p>
                                <p id="modalGuardian" class="font-semibold text-slate-900">-</p>
                                <p id="modalGuardianRelationship" class="text-sm text-slate-500"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Emergency Contact</h5>
                        <div class="p-4 bg-red-50/50 rounded-xl border border-red-100 space-y-2">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-user text-red-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-red-600 mb-1">Name</p>
                                    <p id="modalEmergencyName" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-handshake text-red-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-red-600 mb-1">Relationship</p>
                                    <p id="modalEmergencyRelationship" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-phone text-red-400 w-5 text-center"></i>
                                <div class="flex-1">
                                    <p class="text-xs text-red-600 mb-1">Contact Number</p>
                                    <p id="modalEmergencyNumber" class="font-semibold text-slate-900">-</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Academic Info -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Academic Details</h5>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Grade Level</p>
                                <p id="modalGradeLevel" class="font-semibold text-slate-900">-</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Pupil Type</p>
                                <p id="modalStudentType" class="font-semibold text-slate-900 capitalize">-</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">LRN</p>
                                <p id="modalLRN" class="font-semibold text-slate-900 font-mono">-</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Date Applied</p>
                                <p id="modalDateApplied" class="font-semibold text-slate-900">-</p>
                            </div>
                            <div id="modalPreviousSchoolContainer" class="hidden p-4 bg-slate-50 rounded-xl border border-slate-100 sm:col-span-2">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">Previous School</p>
                                <p id="modalPreviousSchool" class="font-semibold text-slate-900">-</p>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 sm:col-span-2">
                                <p class="text-xs text-slate-500 mb-1 uppercase tracking-wide font-medium">School Year</p>
                                <p class="font-semibold text-green-700"><?php echo e($schoolYear->name ?? 'Not specified'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Pupil Documents -->
                    <div class="space-y-3">
                        <h5 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Pupil Documents</h5>
                        <div id="pupilDocumentsList" class="space-y-2">
                            <!-- Dynamically populated -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="border-t border-slate-100 bg-slate-50/50 flex flex-col gap-4">
                <!-- Collapsible Toggle Header -->
                <button type="button" onclick="toggleEnrollmentSection()" class="w-full p-4 flex items-center justify-between hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="text-left">
                            <h3 class="text-sm font-bold text-slate-900">Enrollment Actions</h3>
                            <p class="text-xs text-slate-500">Assign section, add remarks, and approve / reject</p>
                        </div>
                    </div>
                    <i id="enrollmentToggleIcon" class="fas fa-chevron-down text-slate-400 transition-transform duration-300 rotate-180"></i>
                </button>

                <div id="enrollmentSectionContent" class="hidden flex flex-col gap-4 px-6 pb-6">
                    <!-- School Year Info Display -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 flex items-center gap-3">
                        <i class="fas fa-calendar-alt text-blue-600 text-lg"></i>
                        <div>
                            <p class="text-xs text-blue-600 font-semibold uppercase tracking-wide">Enrolling For School Year</p>
                            <p class="text-sm font-bold text-blue-800"><?php echo e($schoolYear->name ?? 'Current Year'); ?></p>
                        </div>
                    </div>

                    <!-- Section Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-chalkboard-teacher mr-1"></i> Assign to Section <span class="text-red-500">*</span>
                        </label>
                        <?php if($sections->isNotEmpty()): ?>
                            <select name="section_id" form="modalApproveForm" required
                                    class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all">
                                <option value="">Select a section...</option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($section->id); ?>" data-grade-level-id="<?php echo e($section->grade_level_id); ?>">
                                        <?php echo e($section->name); ?>

                                        (Grade <?php echo e($section->gradeLevel->name ?? 'N/A'); ?>)
                                        <?php if($section->teacher): ?> — <?php echo e($section->teacher->user->last_name ?? 'No Adviser'); ?> <?php endif; ?>
                                        — <?php echo e($section->enrollments_count); ?>/<?php echo e($section->capacity); ?> enrolled
                                        <?php if($section->enrollments_count >= $section->capacity): ?> [FULL] <?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <p class="text-xs text-slate-500 mt-1">
                                Enrolling in <?php echo e($schoolYear->name ?? 'selected school year'); ?> • 
                                <span class="text-emerald-600 font-medium"><?php echo e($sections->count()); ?> section(s) available</span>
                            </p>
                        <?php else: ?>
                            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>No sections available for <?php echo e($schoolYear->name ?? 'this school year'); ?>. Please create sections first.</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Remarks Selection -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            <i class="fas fa-sticky-note mr-1"></i> Remarks <span class="text-slate-400 font-normal">(Optional)</span>
                        </label>
                        <select name="remarks" form="modalApproveForm"
                                class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 text-sm transition-all">
                            <option value="">-- Select Remark --</option>
                            <?php $__currentLoopData = \App\Models\Student::$remarksLegend ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($code); ?>"><?php echo e($code); ?> - <?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Select a remark code for this student's enrollment record</p>
                    </div>

                    <!-- Action Buttons Row -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
                        
                        <!-- Approve/Enroll Form -->
                        <form id="modalApproveForm" method="POST" action="" class="flex-1">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="school_year_id" value="<?php echo e($schoolYear?->id); ?>">
                            <button type="submit" 
                                    class="w-full btn-enroll py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" 
                                    <?php echo e($sections->isEmpty() ? 'disabled' : ''); ?>>
                                <i class="fas fa-check"></i>
                                <span>Enroll in <?php echo e($schoolYear->name ?? 'Selected Year'); ?></span>
                            </button>
                        </form>

                        <!-- Reject Form -->
                        <form id="modalRejectForm" method="POST" action="">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="school_year_id" value="<?php echo e($schoolYear?->id); ?>">
                            <button type="submit" 
                                    class="btn-reject w-full sm:w-auto px-6 py-3 rounded-xl text-white font-semibold flex items-center justify-center gap-2 transition-all"
                                    onclick="return confirm('Are you sure you want to reject this application for <?php echo e($schoolYear->name ?? 'this school year'); ?>? This action cannot be undone.')">
                                <i class="fas fa-times"></i>
                                <span>Reject</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-[60] hidden">
        <div id="deleteModalBackdrop" class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeDeleteModal()"></div>
        
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div id="deleteModalPanel" class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
                
                <!-- Header -->
                <div class="p-6 bg-gradient-to-r from-red-50 to-red-100 border-b border-red-200">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full bg-red-500 flex items-center justify-center text-white shadow-lg shadow-red-500/30">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-red-900">Delete Registration</h3>
                            <p class="text-sm text-red-600 mt-1">This action cannot be undone</p>
                        </div>
                    </div>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-user text-amber-600 mt-1"></i>
                            <div>
                                <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide mb-1">Student to Delete</p>
                                <p id="deleteStudentName" class="text-lg font-bold text-amber-900">-</p>
                                <p class="text-xs text-amber-600 mt-1">From: <?php echo e($schoolYear->name ?? 'Unknown School Year'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        Are you sure you want to permanently delete this registration for <strong><?php echo e($schoolYear->name ?? 'this school year'); ?></strong>? All associated data including personal information, documents, and application history will be removed from the system.
                    </p>
                    
                    <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-4 border border-slate-200">
                        <i class="fas fa-info-circle text-slate-400 text-xl"></i>
                        <p class="text-xs text-slate-500">
                            <span class="font-semibold text-slate-700">Tip:</span> Consider rejecting instead if you want to keep a record of this application.
                        </p>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="p-6 border-t border-slate-100 bg-slate-50 flex items-center gap-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="flex-1 px-6 py-3 rounded-xl bg-white border-2 border-slate-200 text-slate-700 font-semibold hover:border-slate-300 hover:bg-slate-50 transition-all">
                        Cancel
                    </button>
                    
                    <form id="deleteConfirmForm" method="POST" class="flex-1">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" 
                                class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold shadow-lg shadow-red-500/30 hover:shadow-red-500/40 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                            <i class="fas fa-trash-alt"></i>
                            Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Preview Modal -->
    <div id="documentPreviewModal" class="fixed inset-0 z-[70] hidden">
        <div id="documentPreviewBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closeDocumentPreviewModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div id="documentPreviewPanel" class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden flex flex-col max-h-[90vh]">
                <!-- Header -->
                <div class="p-4 bg-gradient-to-r from-amber-50 to-amber-100 border-b border-amber-200 flex items-center justify-between flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-500 text-white flex items-center justify-center">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-amber-900" id="documentPreviewTitle">Document</h3>
                            <p class="text-xs text-amber-600">Document Preview</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a id="documentPreviewDownload" href="#" download class="px-3 py-1.5 bg-white border border-amber-200 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-50 transition-colors flex items-center gap-1.5">
                            <i class="fas fa-download text-xs"></i> Download
                        </a>
                        <button type="button" onclick="closeDocumentPreviewModal()" class="w-8 h-8 rounded-lg bg-white border border-amber-200 text-amber-600 hover:text-amber-800 flex items-center justify-center transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- Body -->
                <div class="flex-1 bg-slate-100 overflow-y-auto relative" id="documentPreviewBody">
                    <!-- Dynamically populated -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Search functionality
        document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.table-row');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Modal functions
        window.appBaseUrl = '<?php echo e(url('/')); ?>';

        async function openStudentModal(studentId) {
            const modal = document.getElementById('studentModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            const loading = document.getElementById('modalLoading');
            const error = document.getElementById('modalError');
            const content = document.getElementById('modalContent');
            
            // Reset section dropdown
            const sectionSelect = document.querySelector('select[name="section_id"]');
            if (sectionSelect) sectionSelect.value = '';
            
            // Reset remarks dropdown
            const remarksSelect = document.querySelector('select[name="remarks"]');
            if (remarksSelect) remarksSelect.value = '';
            
            // Show modal
            modal.classList.remove('hidden');
            document.getElementById('modalStudentId').textContent = studentId;
            
            // Animate in
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('translate-x-full');
            }, 10);
            
            // Reset states
            loading.classList.remove('hidden');
            error.classList.add('hidden');
            content.classList.add('hidden');
            
            try {
                // Build URL properly with school_year_id parameter
                const schoolYearId = <?php echo e($schoolYear?->id ?? 'null'); ?>;
                const rolePath = window.location.pathname.startsWith('/principal') ? 'principal' : 'admin';
                let url = `${window.appBaseUrl}/${rolePath}/pending-registrations/${studentId}/details`;
                if (schoolYearId) {
                    url += `?school_year_id=${schoolYearId}`;
                }
                
                console.log('Fetching URL:', url); // Debug log
                
                const response = await fetch(url, {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                const responseText = await response.text();
                console.log('Response:', responseText); // Debug log
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid server response: ' + responseText.substring(0, 100));
                }
                
                if (!response.ok) {
                    throw new Error(data.error || `HTTP ${response.status}: ${response.statusText}`);
                }
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                populateStudentModal(data, studentId);
                
                loading.classList.add('hidden');
                content.classList.remove('hidden');
                
            } catch (err) {
                console.error('Modal error:', err);
                loading.classList.add('hidden');
                error.classList.remove('hidden');
                document.getElementById('modalErrorMessage').textContent = err.message;
            }
        }

        function populateStudentModal(data, studentId) {
            const s = data.student;
            const u = s.user || {};
            
            const photoEl = document.getElementById('modalPhoto');
            if (data.photo_url) {
                const img = document.createElement('img');
                img.src = data.photo_url;
                img.className = 'w-full h-full object-cover rounded-2xl';
                img.onerror = function() {
                    const initials = ((u.first_name?.[0] || '') + (u.last_name?.[0] || '')).toUpperCase();
                    photoEl.textContent = initials || '?';
                };
                photoEl.innerHTML = '';
                photoEl.appendChild(img);
            } else {
                const initials = ((u.first_name?.[0] || '') + (u.last_name?.[0] || '')).toUpperCase();
                photoEl.textContent = initials || '?';
            }

            // Show remarks if exists
            const remarksContainer = document.getElementById('modalRemarksContainer');
            const remarksBadge = document.getElementById('modalRemarksBadge');
            
            if (s.remarks && s.remarks !== 'No remarks') {
                const legend = {
                    'TI': 'Transferred In',
                    'TO': 'Transferred Out',
                    'DO': 'Dropped Out',
                    'LE': 'Late Enrollee',
                    'CCT': 'CCT Recipient',
                    'BA': 'Balik Aral',
                    'LWD': 'Learner With Disability'
                };
                remarksBadge.textContent = `${s.remarks} - ${legend[s.remarks] || s.remarks}`;
                remarksContainer.classList.remove('hidden');
            } else {
                remarksContainer.classList.add('hidden');
            }
            
            document.getElementById('modalName').textContent = data.full_name || '-';
            document.getElementById('modalEmail').textContent = u.email || '-';
            document.getElementById('modalGrade').textContent = s.grade_level?.name ? `Grade ${s.grade_level.name}` : '-';
            document.getElementById('modalAge').textContent = data.age ? `${data.age} years old` : '-';
            document.getElementById('modalFullName').textContent = data.full_name || '-';
            
            document.getElementById('modalBirthdate').textContent = s.birthdate 
                ? new Date(s.birthdate).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
                : '-';
            
            document.getElementById('modalGender').textContent = s.gender || '-';
            document.getElementById('modalBirthPlace').textContent = s.birth_place || '-';
            document.getElementById('modalNationality').textContent = s.nationality || '-';
            document.getElementById('modalReligion').textContent = s.religion || '-';
            document.getElementById('modalEthnicity').textContent = s.ethnicity || '-';
            document.getElementById('modalMotherTongue').textContent = s.mother_tongue || '-';

            const addressParts = [s.street_address, s.barangay, s.city, s.province].filter(Boolean);
            document.getElementById('modalAddress').textContent = addressParts.join(', ') || '-';
            document.getElementById('modalGuardianContact').textContent = s.guardian_contact || '-';
            document.getElementById('modalEmail2').textContent = u.email || '-';
            document.getElementById('modalUsername').textContent = u.username || '-';

            document.getElementById('modalFather').textContent = s.father_name || '-';
            document.getElementById('modalFatherOccupation').textContent = s.father_occupation ? `Occupation: ${s.father_occupation}` : '';
            document.getElementById('modalFatherContact').textContent = s.father_contact ? `Contact: ${s.father_contact}` : '';
            document.getElementById('modalMother').textContent = s.mother_name || '-';
            document.getElementById('modalMotherOccupation').textContent = s.mother_occupation ? `Occupation: ${s.mother_occupation}` : '';
            document.getElementById('modalMotherContact').textContent = s.mother_contact ? `Contact: ${s.mother_contact}` : '';
            document.getElementById('modalGuardian').textContent = s.guardian_name || '-';
            document.getElementById('modalGuardianRelationship').textContent = s.guardian_relationship ? `Relationship: ${s.guardian_relationship}` : '';

            // Emergency contact (display guardian information from pupil sign-up form)
            document.getElementById('modalEmergencyName').textContent = s.guardian_name || '-';
            document.getElementById('modalEmergencyRelationship').textContent = s.guardian_relationship || '-';
            document.getElementById('modalEmergencyNumber').textContent = s.guardian_contact || '-';

            document.getElementById('modalGradeLevel').textContent = s.grade_level?.name ? `Grade ${s.grade_level.name}` : '-';
            document.getElementById('modalStudentType').textContent = s.type || '-';
            document.getElementById('modalLRN').textContent = s.lrn || '-';
            document.getElementById('modalDateApplied').textContent = s.created_at
                ? new Date(s.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
                : '-';

            // Previous school (show only if transferee or has value)
            const prevSchoolContainer = document.getElementById('modalPreviousSchoolContainer');
            if (s.previous_school) {
                document.getElementById('modalPreviousSchool').textContent = s.previous_school;
                prevSchoolContainer.classList.remove('hidden');
            } else {
                prevSchoolContainer.classList.add('hidden');
            }

            // Render documents inline
            renderDocumentsList(s.documents || {});
            
            // Set form actions - use direct URLs
            const actionRolePath = window.location.pathname.startsWith('/principal') ? 'principal' : 'admin';
            document.getElementById('modalApproveForm').action = `${window.appBaseUrl}/${actionRolePath}/pending-registrations/${studentId}/approve`;
            document.getElementById('modalRejectForm').action = `${window.appBaseUrl}/${actionRolePath}/pending-registrations/${studentId}/reject`;

            // Auto-select section matching student's grade level
            const sectionSelect = document.querySelector('select[name="section_id"]');
            if (sectionSelect && s.grade_level?.id) {
                const matchingOption = sectionSelect.querySelector(`option[data-grade-level-id="${s.grade_level.id}"]`);
                if (matchingOption) {
                    sectionSelect.value = matchingOption.value;
                } else {
                    sectionSelect.value = '';
                }
            }
        }

        function closeStudentModal() {
            const modal = document.getElementById('studentModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');
            
            backdrop.classList.add('opacity-0');
            panel.classList.add('translate-x-full');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Delete Modal Functions
        function openDeleteModal(studentId, studentName) {
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('deleteModalBackdrop');
            const panel = document.getElementById('deleteModalPanel');
            
            document.getElementById('deleteStudentName').textContent = studentName;
            
            // Set delete URL directly
            const deleteRolePath = window.location.pathname.startsWith('/principal') ? 'principal' : 'admin';
            document.getElementById('deleteConfirmForm').action = `${window.appBaseUrl}/${deleteRolePath}/pending-registrations/${studentId}`;
            
            modal.classList.remove('hidden');
            
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const backdrop = document.getElementById('deleteModalBackdrop');
            const panel = document.getElementById('deleteModalPanel');
            
            backdrop.classList.add('opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function renderDocumentsList(documents) {
            const container = document.getElementById('pupilDocumentsList');
            const docLabels = {
                birth_certificate: 'Birth Certificate',
                report_card: 'Report Card',
                good_moral: 'Good Moral Certificate',
                transfer_credential: 'Transfer Credential'
            };
            const docIcons = {
                birth_certificate: 'fa-file-medical',
                report_card: 'fa-file-alt',
                good_moral: 'fa-certificate',
                transfer_credential: 'fa-file-contract'
            };
            
            let hasAnyDoc = false;
            let docsHtml = '';
            for (const [key, url] of Object.entries(documents)) {
                if (url) {
                    hasAnyDoc = true;
                    docsHtml += `
                        <div class="flex items-center justify-between p-3 bg-amber-50/50 rounded-xl border border-amber-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                    <i class="fas ${docIcons[key] || 'fa-file'} text-sm"></i>
                                </div>
                                <span class="font-semibold text-slate-900 text-sm">${docLabels[key] || key}</span>
                            </div>
                            <button type="button" onclick="openDocumentPreviewModal('${url}', '${docLabels[key] || key}')" class="px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors flex items-center gap-1.5">
                                <i class="fas fa-eye text-xs"></i> View
                            </button>
                        </div>`;
                }
            }
            
            if (hasAnyDoc) {
                container.innerHTML = docsHtml;
            } else {
                container.innerHTML = `
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100 text-center">
                        <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-folder-open text-xl text-slate-400"></i>
                        </div>
                        <p class="text-sm font-medium text-slate-600">No Documents Submitted</p>
                        <p class="text-xs text-slate-500 mt-1">This pupil has not uploaded any documents yet.</p>
                    </div>`;
            }
        }

        function openDocumentPreviewModal(url, title) {
            const modal = document.getElementById('documentPreviewModal');
            const backdrop = document.getElementById('documentPreviewBackdrop');
            const panel = document.getElementById('documentPreviewPanel');
            const body = document.getElementById('documentPreviewBody');
            const titleEl = document.getElementById('documentPreviewTitle');
            const downloadEl = document.getElementById('documentPreviewDownload');
            
            titleEl.textContent = title;
            downloadEl.href = url;
            
            const ext = url.split('.').pop().toLowerCase().split('?')[0];
            
            if (ext === 'pdf') {
                body.innerHTML = `<iframe src="${url}" class="w-full" style="border:none;height:75vh;" title="${title}"></iframe>`;
            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
                body.innerHTML = `
                    <div class="w-full flex justify-center p-4">
                        <img src="${url}" alt="${title}" class="max-w-full h-auto object-contain rounded-lg shadow-lg" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div style="display:none;" class="flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-700 mb-2">Image Failed to Load</h4>
                            <p class="text-xs text-slate-500 text-center mb-4 break-all max-w-md">${url}</p>
                            <a href="${url}" download class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors flex items-center gap-2">
                                <i class="fas fa-download"></i> Download File
                            </a>
                        </div>
                    </div>`;
            } else {
                body.innerHTML = `
                    <div class="w-full h-full flex flex-col items-center justify-center p-8">
                        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-slate-700 mb-2">Preview Not Available</h4>
                        <p class="text-sm text-slate-500 text-center mb-4">This file type cannot be previewed inline.</p>
                        <a href="${url}" download class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg text-sm font-medium hover:bg-amber-200 transition-colors flex items-center gap-2">
                            <i class="fas fa-download"></i> Download File
                        </a>
                    </div>`;
            }
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('scale-95', 'opacity-0');
                panel.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDocumentPreviewModal() {
            const modal = document.getElementById('documentPreviewModal');
            const backdrop = document.getElementById('documentPreviewBackdrop');
            const panel = document.getElementById('documentPreviewPanel');
            const body = document.getElementById('documentPreviewBody');
            
            backdrop.classList.add('opacity-0');
            panel.classList.remove('scale-100', 'opacity-100');
            panel.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                body.innerHTML = '';
            }, 300);
        }

        // Enrollment Section Toggle
        function toggleEnrollmentSection() {
            const content = document.getElementById('enrollmentSectionContent');
            const icon = document.getElementById('enrollmentToggleIcon');
            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.classList.remove('rotate-180');
            } else {
                content.classList.add('hidden');
                icon.classList.add('rotate-180');
            }
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeDocumentPreviewModal();
                closeStudentModal();
                closeDeleteModal();
            }
        });
    </script>
</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\admin\pending-registrations\index.blade.php ENDPATH**/ ?>