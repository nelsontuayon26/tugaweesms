<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student ID Card | <?php echo e($student->full_name); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <?php echo $__env->make('components.student-id-card', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <div class="mt-6 text-center">
            <a href="<?php echo e(route('student.dashboard')); ?>" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-700 transition">
                <i class="fas fa-arrow-left"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\student\id-card.blade.php ENDPATH**/ ?>