<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollment Closed - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center">
        <!-- Icon -->
        <div class="mx-auto w-24 h-24 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mb-6">
            <i class="fas fa-lock text-white text-4xl"></i>
        </div>

        <!-- Title -->
        <h2 class="text-3xl font-bold text-white mb-4">
            Enrollment is Closed
        </h2>

        <!-- Message -->
        <p class="text-white/80 mb-8">
            Online enrollment is currently not available. Please check back later or contact the school administration for assistance.
        </p>

        <!-- Contact Info -->
        <?php
            $schoolName = \App\Models\Setting::get('school_name', 'Tugawe Elementary School');
            $schoolContact = \App\Models\Setting::get('school_phone', '');
            $schoolEmail = \App\Models\Setting::get('school_email', '');
        ?>

        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 mb-6 border border-white/20">
            <h3 class="font-semibold text-white mb-4">Contact Information</h3>
            
            <?php if($schoolContact): ?>
                <div class="flex items-center justify-center gap-2 mb-2 text-white/90">
                    <i class="fas fa-phone text-amber-300"></i>
                    <span><?php echo e($schoolContact); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if($schoolEmail): ?>
                <div class="flex items-center justify-center gap-2 text-white/90">
                    <i class="fas fa-envelope text-amber-300"></i>
                    <span><?php echo e($schoolEmail); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Back Button -->
        <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center gap-2 text-white hover:text-amber-200 font-medium transition-colors">
            <i class="fas fa-arrow-left"></i>
            Back to Home
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\enrollment\closed.blade.php ENDPATH**/ ?>