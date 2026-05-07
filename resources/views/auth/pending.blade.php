<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-indigo-100 to-purple-100 min-h-screen flex items-center justify-center p-4">
    
    <!-- Success Card -->
    <div id="success-card" class="bg-white p-6 rounded-2xl shadow-xl max-w-sm w-full text-center">
        <div class="w-14 h-14 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
        </div>
        
        <h2 class="text-xl font-bold text-gray-800 mb-2">Registration Submitted!</h2>
        <p class="text-gray-600 text-sm mb-4">Your account is pending admin approval.</p>
        
        <div class="bg-blue-50 rounded-lg p-3 mb-4 text-left">
            <p class="text-xs text-blue-700">
                <i class="fas fa-envelope mr-1"></i> You'll be notified at <span class="font-semibold">tes@gmail.com</span>
            </p>
        </div>

        <button onclick="window.location.href='/'" class="w-full py-2.5 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
            Back to Home
        </button>
    </div>

    <!-- Error Card (Hidden) -->
    <div id="error-card" class="hidden bg-white p-6 rounded-2xl shadow-xl max-w-sm w-full text-center">
        <div class="w-14 h-14 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-times-circle text-red-500 text-2xl"></i>
        </div>
        
        <h2 class="text-xl font-bold text-gray-800 mb-2">Registration Failed</h2>
        <p class="text-gray-600 text-sm mb-4" id="error-text">Something went wrong. Please try again.</p>
        
        <div class="flex gap-2">
            <button onclick="window.location.href='/register'" class="flex-1 py-2.5 bg-red-500 text-white rounded-lg text-sm font-medium hover:bg-red-600 transition">
                Try Again
            </button>
            <button onclick="window.location.href='/support'" class="flex-1 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                Support
            </button>
        </div>
    </div>

    <script>
        function showError(msg) {
            document.getElementById('success-card').classList.add('hidden');
            document.getElementById('error-card').classList.remove('hidden');
            if (msg) document.getElementById('error-text').textContent = msg;
        }
        function showSuccess() {
            document.getElementById('error-card').classList.add('hidden');
            document.getElementById('success-card').classList.remove('hidden');
        }
    </script>
</body>
</html>