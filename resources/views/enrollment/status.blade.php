<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Application Status - Tugawe Elementary School</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-emerald-50 py-8">
    <div class="max-w-2xl mx-auto px-4">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-slate-800">Check Application Status</h1>
            <p class="text-slate-500">Track your enrollment application</p>
        </div>

        @if(!isset($application))
            <!-- Check Form -->
            <form action="{{ route('enrollment.check-status') }}" method="POST" class="bg-white rounded-xl shadow-lg p-6 mb-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Application Number</label>
                        <input type="text" name="application_number" placeholder="ENR-2024-0001" required 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                        <input type="email" name="parent_email" placeholder="parent@email.com" required 
                               class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold">
                        Check Status
                    </button>
                </div>
            </form>
            
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    {{ $errors->first() }}
                </div>
            @endif
        @else
            <!-- Status Result -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-center mb-6">
                    <div class="inline-block px-4 py-2 rounded-full text-sm font-semibold mb-4
                        @if($application->status == 'approved') bg-emerald-100 text-emerald-700
                        @elseif($application->status == 'rejected') bg-red-100 text-red-700
                        @elseif($application->status == 'pending') bg-amber-100 text-amber-700
                        @else bg-blue-100 text-blue-700 @endif">
                        <i class="fas fa-circle text-xs mr-2"></i>{{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </div>
                    <h2 class="text-xl font-bold text-slate-800">{{ $application->student_full_name }}</h2>
                    <p class="text-slate-500">Application #{{ $application->application_number }}</p>
                </div>
                
                <div class="border-t pt-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Grade Level:</span>
                        <span class="font-medium">{{ $application->gradeLevel->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">School Year:</span>
                        <span class="font-medium">{{ $application->schoolYear->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Submitted:</span>
                        <span class="font-medium">{{ $application->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    @if($application->admin_notes)
                        <div class="bg-slate-50 rounded-lg p-4 mt-4">
                            <p class="text-sm text-slate-600"><strong>Admin Notes:</strong> {{ $application->admin_notes }}</p>
                        </div>
                    @endif
                    
                    @if($application->rejection_reason)
                        <div class="bg-red-50 rounded-lg p-4 mt-4">
                            <p class="text-sm text-red-600"><strong>Reason:</strong> {{ $application->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="mt-6 pt-6 border-t text-center">
                    <a href="{{ route('enrollment.form') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        Check Another Application
                    </a>
                </div>
            </div>
        @endif
        
        <div class="text-center mt-6">
            <a href="/" class="text-slate-500 hover:text-slate-700">
                <i class="fas fa-arrow-left mr-1"></i> Back to Home
            </a>
        </div>
    </div>
</body>
</html>
