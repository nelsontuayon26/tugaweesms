<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->user->first_name }} {{ $student->user->last_name }} - Student Profile</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }">

<!-- Mobile Overlay -->
<div x-show="mobileOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"
     style="display: none;"></div>

<!-- Mobile Toggle Button -->
<button @click="mobileOpen = !mobileOpen" 
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex min-h-screen">
    @include('teacher.includes.sidebar')

    <div class="flex-1 lg:ml-72 min-h-screen">
        <!-- Header -->
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="flex items-center h-16 px-6">
                <a href="{{ url()->previous() }}" class="p-2 -ml-2 text-slate-500 hover:text-indigo-600 rounded-lg hover:bg-slate-100 transition-all mr-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-lg font-bold text-slate-800">Student Profile</h1>
            </div>
        </header>

        <main class="p-6 max-w-5xl mx-auto">
            @php
                $user = $student->user;
                $section = $student->section;
                $age = $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->age : null;
            @endphp

            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
                <div class="h-32 bg-gradient-to-r from-indigo-600 to-violet-600 relative">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"/>
                        </svg>
                    </div>
                </div>
                <div class="px-6 pb-6 relative">
                    <div class="relative -mt-16 mb-4 flex justify-between items-end">
                        <div class="flex items-end gap-4">
                            <div class="w-28 h-28 rounded-2xl bg-white p-1 shadow-xl">
                                @if($student->photo)
                                    <img src="{{ profile_photo_url($student->photo) }}" 
                                         class="w-full h-full rounded-xl object-cover" 
                                         alt="Profile Photo">
                                @else
                                    <div class="w-full h-full rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="mb-2">
                                <h2 class="text-2xl font-bold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                <p class="text-slate-500">
                                    LRN: {{ $student->lrn ?? 'Not Assigned' }}
                                    @if($section)
                                        <span class="mx-2 text-slate-300">|</span>
                                        {{ $section->gradeLevel->name ?? '' }} - {{ $section->name }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('teacher.messenger', ['contact' => $student->user_id]) }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200">
                            <i class="fas fa-comment-dots"></i>
                            Send Message
                        </a>
                    </div>

                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Gender</p>
                            <p class="text-base font-bold text-slate-800 capitalize">{{ $student->gender ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Age</p>
                            <p class="text-base font-bold text-slate-800">{{ $age ? $age . ' years old' : 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Birth Date</p>
                            <p class="text-base font-bold text-slate-800">{{ $student->birthdate ? \Carbon\Carbon::parse($student->birthdate)->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize
                                {{ ($student->enrollment->status ?? '') === 'enrolled' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $student->enrollment->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact & Guardian Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-address-card text-indigo-600"></i>
                        Contact Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</p>
                            <p class="text-sm font-medium text-slate-800">{{ $user->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Address</p>
                            <p class="text-sm font-medium text-slate-800">
                                {{ $student->street_address ?? '' }}
                                {{ $student->barangay ? ', Brgy. ' . $student->barangay : '' }}
                                {{ $student->city ? ', ' . $student->city : '' }}
                                {{ $student->province ? ', ' . $student->province : '' }}
                                @if(!$student->street_address && !$student->barangay && !$student->city)
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-users text-indigo-600"></i>
                        Guardian Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Guardian's Name</p>
                            <p class="text-sm font-medium text-slate-800">{{ $student->guardian_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Relationship</p>
                            <p class="text-sm font-medium text-slate-800">{{ $student->guardian_relationship ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Contact Number</p>
                            <p class="text-sm font-medium text-slate-800">{{ $student->guardian_contact ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-indigo-600"></i>
                    Academic Information
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Grade Level</p>
                        <p class="text-lg font-bold text-slate-800">{{ $student->gradeLevel->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Section</p>
                        <p class="text-lg font-bold text-slate-800">{{ $section->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">School Year</p>
                        <p class="text-lg font-bold text-slate-800">{{ $section->schoolYear->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 text-center">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Adviser</p>
                        <p class="text-lg font-bold text-slate-800">{{ $section->teacher->user->full_name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html>
