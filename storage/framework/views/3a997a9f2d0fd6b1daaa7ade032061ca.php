<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signing In...</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 0.5; }
            100% { transform: scale(1.3); opacity: 0; }
        }
        .pulse-ring {
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="text-center fade-in-up">
        <!-- Animated Logo/Icon -->
        <div class="relative w-20 h-20 mx-auto mb-6">
            <div class="absolute inset-0 rounded-full bg-teal-400 pulse-ring"></div>
            <div class="absolute inset-0 rounded-full bg-teal-500 pulse-ring" style="animation-delay: 0.4s"></div>
            <div class="relative w-full h-full rounded-full bg-teal-600 flex items-center justify-center shadow-lg">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
        </div>

        <!-- Role Message -->
        <h2 class="text-2xl font-bold text-slate-800 mb-2">
            Signing in as a <span class="text-teal-600 capitalize"><?php echo e(session('signing_in_role', 'User')); ?></span>
        </h2>
        <p class="text-slate-500 text-sm mb-8">Please wait while we prepare your dashboard...</p>

        <!-- Progress Bar -->
        <div class="w-64 h-1.5 bg-slate-200 rounded-full mx-auto overflow-hidden">
            <div class="h-full bg-teal-500 rounded-full animate-[loading_1.5s_ease-in-out_forwards]"></div>
        </div>

        <style>
            @keyframes loading {
                0% { width: 0%; }
                100% { width: 100%; }
            }
        </style>

        <!-- Redirect Script -->
        <script>
            setTimeout(function() {
                window.location.href = "<?php echo e(session('signing_in_redirect', url('/dashboard'))); ?>";
            }, 1800);
        </script>
    </div>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/auth/signing-in.blade.php ENDPATH**/ ?>