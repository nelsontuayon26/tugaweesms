<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invalid QR Code</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md w-full text-center">
        <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times-circle text-5xl text-red-600"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Invalid QR Code</h1>
        <p class="text-gray-600 mb-6">{{ $message ?? 'This QR code is invalid or has expired.' }}</p>
        
        <p class="text-sm text-gray-500 mb-6">
            Please contact the school administration for assistance or scan a valid QR code.
        </p>
        
        <a href="mailto:school@example.com" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors">
            <i class="fas fa-envelope mr-2"></i>Contact School
        </a>
    </div>
</body>
</html>