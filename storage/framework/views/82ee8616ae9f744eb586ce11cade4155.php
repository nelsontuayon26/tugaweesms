<!DOCTYPE html>
<html>
<head>
    <title>Messenger Test</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Messenger Test</h1>
        
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <h2 class="font-semibold mb-2">User Info:</h2>
            <p>Name: <?php echo e(auth()->user()->full_name ?? 'Not logged in'); ?></p>
            <p>Role: <?php echo e(auth()->user()->role->name ?? 'No role'); ?></p>
            <p>ID: <?php echo e(auth()->id()); ?></p>
        </div>
        
        <div class="bg-white p-4 rounded-lg shadow mb-4">
            <h2 class="font-semibold mb-2">Contacts (<?php echo e($contacts->count()); ?>):</h2>
            <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-2 border-b">
                    <p class="font-medium"><?php echo e($contact->full_name); ?></p>
                    <p class="text-sm text-gray-500">ID: <?php echo e($contact->id); ?></p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-gray-500">No contacts found</p>
            <?php endif; ?>
        </div>
        
        <a href="<?php echo e($isStudent ? route('student.messenger') : route('teacher.messenger')); ?>" 
           class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
            Go to Messenger
        </a>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\messenger\test.blade.php ENDPATH**/ ?>