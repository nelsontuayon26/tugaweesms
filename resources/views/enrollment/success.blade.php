<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 flex items-center justify-center p-4">
    
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl p-8 text-center">
        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-4xl text-emerald-600"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-800 mb-2">Enrollment Submitted!</h1>
        <p class="text-slate-600 mb-6">Your application has been received successfully.</p>
        
        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 mb-6">
            <p class="text-sm text-slate-500 mb-1">Application Number</p>
            <p class="text-3xl font-bold text-indigo-600">{{ $application->application_number }}</p>
        </div>
        
        <div class="text-left bg-slate-50 rounded-xl p-4 mb-6 space-y-2">
            <div class="flex justify-between">
                <span class="text-slate-500">Student:</span>
                <span class="font-medium">{{ $application->student_full_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Grade Level:</span>
                <span class="font-medium">{{ $application->gradeLevel->name ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Type:</span>
                <span class="font-medium">{{ $application->application_type_label }}</span>
            </div>
        </div>
        
        <div class="space-y-3 text-sm text-slate-600 mb-6 text-left">
            <p><i class="fas fa-envelope text-indigo-500 mr-2 w-5"></i>Confirmation sent to {{ $application->parent_email }}</p>
            <p><i class="fas fa-clock text-indigo-500 mr-2 w-5"></i>Review takes 3-5 business days</p>
            <p><i class="fas fa-file-alt text-indigo-500 mr-2 w-5"></i>Complete registration after approval</p>
        </div>
        
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
            <p class="text-sm text-amber-800">
                <i class="fas fa-exclamation-circle mr-1"></i>
                <strong>Save your application number!</strong><br>
                You'll need it to check your status.
            </p>
        </div>
        
        <div class="flex flex-col gap-3">
            <a href="{{ route('enrollment.check') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold transition-all flex items-center justify-center gap-2">
                <i class="fas fa-search"></i> Check Status
            </a>
            <a href="{{ route('enrollment.form') }}" class="px-6 py-3 text-slate-600 hover:bg-slate-100 rounded-xl transition-all">
                Submit Another Application
            </a>
            <a href="/" class="px-6 py-3 text-slate-500 hover:bg-slate-100 rounded-xl transition-all">
                Back to Home
            </a>
        </div>
    </div>
    
</body>
</html>
