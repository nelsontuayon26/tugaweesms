<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Enrollment - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    
    <!-- Header -->
    <header class="glass sticky top-0 z-50 shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>
                <div>
                    <h1 class="font-bold text-slate-800">Tugawe Elementary School</h1>
                    <p class="text-xs text-slate-500">Online Enrollment</p>
                </div>
            </div>
            <a href="/" class="text-slate-600 hover:text-indigo-600">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </header>

    <main class="py-12 px-4">
        <div class="max-w-xl mx-auto">
            
            <!-- Title -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                    <i class="fas fa-user-plus text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Online Enrollment</h1>
                <p class="text-white/80">Select the option that applies to you</p>
                <?php if($currentSchoolYear): ?>
                    <div class="mt-3 inline-flex items-center gap-2 bg-white/20 px-4 py-1.5 rounded-full">
                        <i class="fas fa-calendar-alt text-white/90"></i>
                        <span class="text-white font-medium"><?php echo e($currentSchoolYear->name); ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <?php
                $enrollmentEnabledValue = \App\Models\Setting::get('enrollment_enabled', false);
                $enrollmentEnabled = $enrollmentEnabledValue === true || $enrollmentEnabledValue === '1' || $enrollmentEnabledValue === 1;
            ?>

            <?php if(!$enrollmentEnabled): ?>
                <div class="glass rounded-3xl shadow-2xl p-8 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-slate-400 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Enrollment is Closed</h2>
                    <p class="text-slate-500">Online enrollment is not open at this time. Please contact the school administration.</p>
                </div>
            <?php else: ?>
                <!-- Choice Cards -->
                <div class="space-y-4">
                    
                    <!-- Returning Pupil -->
                    <a href="<?php echo e(route('login')); ?>" class="glass rounded-2xl p-6 flex items-center gap-5 hover:shadow-2xl transition-all duration-300 group cursor-pointer block">
                        <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-indigo-600 transition-colors">
                            <i class="fas fa-sign-in-alt text-indigo-600 text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-indigo-700 transition-colors">Returning Pupil</h3>
                            <p class="text-sm text-slate-500 mt-0.5">
                                I already have a Pupil Portal account and want to enroll for the new school year.
                            </p>
                        </div>
                        <i class="fas fa-chevron-right text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <!-- New / Transferee Pupil -->
                    <a href="<?php echo e(route('register')); ?>" class="glass rounded-2xl p-6 flex items-center gap-5 hover:shadow-2xl transition-all duration-300 group cursor-pointer block">
                        <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-emerald-600 transition-colors">
                            <i class="fas fa-user-plus text-emerald-600 text-xl group-hover:text-white transition-colors"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-lg group-hover:text-emerald-700 transition-colors">New or Transferee Pupil</h3>
                            <p class="text-sm text-slate-500 mt-0.5">
                                I am enrolling for the first time or transferring from another school.
                            </p>
                        </div>
                        <i class="fas fa-chevron-right text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all"></i>
                    </a>

                </div>

                <!-- Check Status -->
                <div class="text-center mt-6">
                    <a href="<?php echo e(route('enrollment.check')); ?>" class="text-white/80 hover:text-white text-sm">
                        <i class="fas fa-search mr-1"></i> Already applied? Check your application status
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\enrollment\form.blade.php ENDPATH**/ ?>