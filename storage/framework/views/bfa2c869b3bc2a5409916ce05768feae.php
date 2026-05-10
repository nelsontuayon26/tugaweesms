<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Load Slip - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
        }
        
        /* Prevent flash of unstyled content */
        [x-cloak] { display: none !important; }
        
        .load-slip {
            max-width: 297mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .subjects-table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
        }
        
        .subjects-table th,
        .subjects-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            vertical-align: middle;
        }

        .subjects-table th {
            background-color: #1e3a8a;
            color: white;
            font-weight: 600;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .subjects-table td {
            font-size: 10px;
        }
        
        .subjects-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .header-line {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 4px;
            font-size: 11px;
        }
        
        .info-label {
            font-weight: 600;
            min-width: 160px;
            color: #374151;
        }
        
        .info-value {
            flex: 1;
            padding-left: 8px;
            font-weight: 500;
        }

        .info-value:empty::after {
            content: "\00a0";
        }
        
        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 50;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.4), 0 8px 10px -6px rgba(30, 58, 138, 0.2);
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 20px 35px -5px rgba(30, 58, 138, 0.5), 0 10px 15px -6px rgba(30, 58, 138, 0.3);
        }

        .print-btn:active {
            transform: translateY(-1px) scale(0.98);
        }

        .print-btn i {
            font-size: 1.5rem;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 80px;
            color: rgba(30, 58, 138, 0.03);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
        }
        
        .stamp-box {
            position: relative;
            width: 110px;
            height: 110px;
            border: 3px double #dc2626;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, rgba(220, 38, 38, 0.08) 0%, rgba(220, 38, 38, 0.02) 70%, transparent 100%);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1), inset 0 0 20px rgba(220, 38, 38, 0.1);
            transform: rotate(-15deg);
        }

        .stamp-box::before {
            content: '';
            position: absolute;
            inset: 6px;
            border: 2px dashed #dc2626;
            border-radius: 50%;
            opacity: 0.6;
        }

        .stamp-box::after {
            content: '';
            position: absolute;
            inset: 12px;
            border: 1px solid rgba(220, 38, 38, 0.3);
            border-radius: 50%;
        }

        .stamp-content {
            text-align: center;
            color: #dc2626;
            font-weight: 800;
            text-transform: uppercase;
            line-height: 1.2;
            font-size: 11px;
            letter-spacing: 1px;
            text-shadow: 0 1px 2px rgba(220, 38, 38, 0.2);
            z-index: 1;
        }

        .stamp-content .star {
            font-size: 14px;
            color: #dc2626;
            margin: 2px 0;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
            padding-top: 4px;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .status-enrolled {
            background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
            color: #166534;
            border: 1px solid #86efac;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        /* Custom scrollbar for sidebar */
        .scrollbar-thin::-webkit-scrollbar {
            width: 6px;
        }
        .scrollbar-thin::-webkit-scrollbar-track {
            background: transparent;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 20px;
        }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0.4in 0.5in;
            }
            
            aside,
            nav,
            .no-print,
            #sidebar,
            .print-btn,
            .mobile-toggle {
                display: none !important;
            }
            
            main {
                margin-left: 0 !important;
                padding-left: 0 !important;
                width: 100% !important;
            }
            
            body {
                background: white;
            }
            
            .load-slip {
                box-shadow: none;
                margin: 0;
                padding: 0;
                max-width: 100% !important;
            }
            
            .watermark {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen overflow-x-hidden"
      x-data="{ 
          sidebarCollapsed: false, 
          mobileOpen: false,
          init() {
              // Initialize sidebar state based on screen size
              if (window.innerWidth >= 1024) {
                  this.sidebarCollapsed = false;
              } else {
                  this.mobileOpen = false;
              }
          }
      }"
      x-init="init()"
      @resize.window="
          if (window.innerWidth < 1024) {
              sidebarCollapsed = false;
          }
      ">

    <?php
        $enrollment = $student->enrollments()->where('status', 'enrolled')->latest()->first();
        $isEnrolled = $enrollment && $enrollment->status === 'enrolled';
        $activeSchoolYear = \App\Models\SchoolYear::where('is_active', true)->first();
        $schoolYear = $activeSchoolYear ? $activeSchoolYear->name : '2025-2026';
        $totalSubjects = $subjects->count();
    ?>

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-30 lg:hidden"
         style="display: none;">
    </div>

    <!-- Mobile Toggle Button -->
    <button @click="mobileOpen = !mobileOpen" 
            class="mobile-toggle fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-105 hover:shadow-xl transition-all duration-200 border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>



    <!-- Sidebar -->
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main id="mainContent" 
          class="min-h-screen p-4 lg:p-6 transition-all duration-300 lg:ml-72">
        
        <!-- Page Header -->
        <div class="mb-6 flex items-center justify-between no-print max-w-[297mm] mx-auto">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Enrollment Load Slip</h1>
                <p class="text-slate-500">View and print your official enrollment load slip</p>
            </div>
        </div>

        <!-- Not Enrolled Alert -->
        <?php if(!$isEnrolled): ?>
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 text-center no-print mb-6 max-w-[297mm] mx-auto">
                <i class="fas fa-exclamation-triangle text-amber-500 text-3xl mb-2"></i>
                <p class="text-amber-800 font-medium">You are not currently enrolled. Please contact your school administrator.</p>
            </div>
        <?php endif; ?>

        <?php if($isEnrolled): ?>
        <!-- Load Slip Document -->
        <div class="load-slip bg-white p-4 lg:p-6 rounded-lg shadow-lg relative overflow-hidden">
            
            <!-- Watermark -->
            <div class="watermark">TES - 120231</div>
            
            <!-- School Header -->
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row items-center lg:items-start justify-between mb-2 gap-4">
                    <div class="flex flex-col lg:flex-row items-center gap-4 text-center lg:text-left">
                       <!-- School Logo -->
                        <?php if(file_exists(public_path('images/logo.png'))): ?>
                            <img src="<?php echo e(asset('images/logo.png')); ?>" 
                                 alt="Tugawe Elementary School Logo" 
                                 class="w-20 h-20 object-contain rounded-full shadow-lg">
                        <?php else: ?>
                            <div class="w-20 h-20 bg-gradient-to-br from-indigo-900 to-blue-800 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                TES
                            </div>
                        <?php endif; ?>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold text-indigo-900 uppercase tracking-wide">Tugawe Elementary School</h1>
                            <p class="text-sm text-slate-600">Tugawe, Dauin, Negros Oriental</p>
                            <p class="text-xs text-slate-500 uppercase tracking-wider">Department of Education - NIR</p>
                        </div>
                    </div>
                    <div class="text-center lg:text-right">
                        <div class="bg-indigo-900 text-white px-4 py-2 rounded-lg inline-block">
                            <p class="text-xs uppercase tracking-wider">School Year</p>
                            <p class="text-lg font-bold"><?php echo e($schoolYear); ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="header-line flex flex-col sm:flex-row items-center justify-between gap-2">
                    <h2 class="text-lg lg:text-xl font-bold text-slate-800 uppercase tracking-wide">Enrollment Load Slip</h2>
                    <span class="status-badge <?php echo e($isEnrolled ? 'status-enrolled' : 'status-pending'); ?>">
                        <?php echo e(strtoupper($enrollment->status)); ?>

                    </span>
                </div>
            </div>

            <!-- Student Information Grid -->
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-x-12 gap-y-2 mb-6">
                <div class="info-row">
                    <span class="info-label">Student Number (LRN):</span>
                    <span class="info-value font-mono text-base"><?php echo e($student->lrn ?? 'N/A'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Date of Birth / Gender:</span>
                    <span class="info-value">
                        <?php echo e($student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('F d, Y') : 'N/A'); ?> 
                        / <?php echo e(strtoupper($student->gender ?? 'N/A')); ?>

                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value font-bold uppercase text-base tracking-wide">
                        <?php echo e($student->user->last_name ?? ''); ?>, <?php echo e($student->user->first_name ?? ''); ?> <?php echo e($student->user->middle_name ?? ''); ?>

                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Grade Level:</span>
                    <span class="info-value font-semibold"><?php echo e($gradeLevel->name ?? 'N/A'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Section:</span>
                    <span class="info-value"><?php echo e($section->name ?? 'N/A'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Room Number:</span>
                    <span class="info-value font-mono"><?php echo e($section->room_number ?? 'TBA'); ?></span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Adviser:</span>
                    <span class="info-value">
                        <?php if($section && $section->teacher && $section->teacher->user): ?>
                            <?php echo e($section->teacher->user->last_name ?? ''); ?>, <?php echo e($section->teacher->user->first_name ?? ''); ?> <?php echo e($section->teacher->user->middle_name ?? ''); ?>

                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Date Enrolled:</span>
                    <span class="info-value"><?php echo e($enrollment->created_at ? $enrollment->created_at->format('F d, Y h:i A') : now()->format('F d, Y h:i A')); ?></span>
                </div>
            </div>

            <!-- Subjects Table -->
            <div class="relative z-10 mb-6 overflow-x-auto">
                <table class="subjects-table min-w-full">
                    <thead>
                        <tr>
                            <th style="width: 5%;">No.</th>
                            <th style="width: 12%;">Subject Code</th>                          
                            <th style="width: 38%;">Description</th>
                            <th style="width: 20%;">Schedule</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center font-mono"><?php echo e($index + 1); ?></td>
                                <td class="text-center font-mono font-semibold"><?php echo e($subject->code ?? 'N/A'); ?></td>
                                <td class="text-center font-semibold"><?php echo e($subject->description ?? 'N/A'); ?></td>
                                <td class="text-center"><?php echo e($subject->schedule ?? 'TBA'); ?></td>
                                
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-8 text-slate-500">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    No subjects assigned for this grade level.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Note -->
            <div class="relative z-10 mb-6 bg-amber-50 border-l-4 border-amber-400 p-3 rounded-r-lg">
                <p class="text-[10px] font-semibold text-amber-800">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    IMPORTANT: No refund for withdrawal of enrollment, dropping of courses and subjects for whatever reason.
                </p>
            </div>

            <!-- Signatures Section -->
            <div class="relative z-10 grid grid-cols-2 lg:grid-cols-4 gap-6 mb-4">
                <div>
                    <div class="signature-line">
                        <p class="text-[10px] font-semibold text-center text-slate-600">Student's Signature</p>
                    </div>
                </div>
                <div>
                    <div class="signature-line">
                        <p class="text-[10px] font-semibold text-center text-slate-600">Parent/Guardian's Signature</p>
                    </div>
                </div>
                <div>
                    <div class="signature-line">
                        <p class="text-[10px] font-semibold text-center text-slate-600">Class Adviser</p>
                    </div>
                </div>
                <div class="flex justify-center col-span-2 lg:col-span-1">
                    <div class="stamp-box">
                        <div class="stamp-content">
                            <div class="star">★</div>
                            <div>Official</div>
                            <div>Received</div>
                            <div>Stamp</div>
                            <div class="star">★</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="relative z-10 border-t-2 border-indigo-900 pt-3 mt-4">
                <div class="flex flex-col lg:flex-row justify-between items-center gap-4 text-[10px] text-slate-600">
                    <div class="flex flex-col lg:flex-row gap-2 lg:gap-6 text-center lg:text-left">
                        <p><span class="font-semibold">Encoded By:</span> <?php echo e($enrollment->encoded_by ?? 'System Administrator'); ?></p>
                        <p><span class="font-semibold">Printed By:</span> <?php echo e(auth()->user()->name ?? 'System'); ?></p>
                        <p><span class="font-semibold">Date Printed:</span> <?php echo e(now()->format('F d, Y h:i A')); ?></p>
                    </div>
                    <div class="flex gap-4">
                        <div class="bg-indigo-50 px-4 py-2 rounded-lg">
                            <span class="font-semibold text-indigo-900">Total Subjects: <?php echo e($totalSubjects); ?></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php endif; ?>

    </main>

    <!-- Floating Print Button -->
    <button onclick="window.print()" class="print-btn no-print" title="Print Load Slip">
        <i class="fas fa-print"></i>
    </button>

    <!-- Logout Form -->
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="hidden">
        <?php echo csrf_field(); ?>
    </form>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\subjects\index.blade.php ENDPATH**/ ?>