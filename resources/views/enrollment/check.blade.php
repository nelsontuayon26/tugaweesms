<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Enrollment Status - Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
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
                    <i class="fas fa-search text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Check Enrollment Status</h1>
                <p class="text-white/80">Track your enrollment application</p>
            </div>

            @if($errors->any())
                <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 rounded-r-xl p-4">
                    <ul class="text-sm text-rose-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Check Form -->
            @if(!isset($application))
            <div class="glass rounded-3xl shadow-2xl p-8">
                <form action="{{ route('enrollment.check.status') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            <i class="fas fa-hashtag text-indigo-500 mr-2"></i>Application Number
                        </label>
                        <input type="text" name="application_number" placeholder="ENR-2026-0001" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 uppercase tracking-wider">
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-3">
                            <i class="fas fa-envelope text-indigo-500 mr-2"></i>Parent Email
                        </label>
                        <input type="email" name="parent_email" placeholder="parent@email.com" required
                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl hover:opacity-90 transition-opacity flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        <span>Check Status</span>
                    </button>
                </form>
            </div>
            @endif

            <!-- Results -->
            @if(isset($application))
            <div class="glass rounded-3xl shadow-2xl p-8">
                <div class="text-center mb-6">
                    @if($application->status === 'approved')
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-green-700">Application Approved!</h2>
                    @elseif($application->status === 'rejected')
                        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-red-700">Application Not Approved</h2>
                    @elseif($application->status === 'under_review')
                        <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-search text-blue-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-blue-700">Under Review</h2>
                    @else
                        <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-clock text-yellow-600 text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-yellow-700">Pending Review</h2>
                    @endif
                </div>

                <div class="bg-slate-50 rounded-xl p-6 mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-slate-500">Application #:</span>
                            <span class="font-semibold ml-1">{{ $application->application_number }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Student:</span>
                            <span class="font-semibold ml-1">{{ $application->student_full_name }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Grade Level:</span>
                            <span class="font-semibold ml-1">{{ $application->gradeLevel->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-slate-500">Submitted:</span>
                            <span class="font-semibold ml-1">{{ $application->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($application->status === 'rejected' && $application->rejection_reason)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-red-800">
                            <span class="font-semibold">Reason:</span> {{ $application->rejection_reason }}
                        </p>
                    </div>
                @endif

                @if($application->status === 'approved')
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <p class="text-sm text-green-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Please check your email for login credentials and next steps.
                        </p>
                    </div>
                @endif

                <a href="{{ route('enrollment.check') }}" class="block w-full py-3 bg-slate-100 text-slate-700 font-semibold rounded-xl hover:bg-slate-200 transition-colors text-center">
                    <i class="fas fa-arrow-left mr-2"></i>Check Another Application
                </a>
            </div>
            @endif

            <!-- Back to Enrollment Link -->
            <div class="text-center mt-6">
                <a href="{{ route('enrollment.form') }}" class="text-white/80 hover:text-white text-sm">
                    <i class="fas fa-user-plus mr-1"></i> Go to Enrollment Form
                </a>
            </div>
        </div>
    </main>
</body>
</html>
