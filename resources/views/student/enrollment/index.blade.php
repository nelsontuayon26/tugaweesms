<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollment | Tugawe Elementary School</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 6px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
        .scrollbar-thin::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="min-h-screen bg-slate-50 font-sans antialiased"
      x-data="{ sidebarCollapsed: false, mobileOpen: false }">

    <!-- Mobile Toggle -->
    <button @click="mobileOpen = !mobileOpen"
            class="mobile-toggle fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg shadow-slate-200/50 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:scale-105 hover:shadow-xl transition-all duration-200 border border-slate-100">
        <i class="fas fa-bars text-lg"></i>    </button>

    <!-- Sidebar -->
    @include('student.includes.sidebar')

    <!-- Main Content -->
    <main class="min-h-screen transition-all duration-300 ease-out lg:ml-72">

        <!-- Top Header -->
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('student.dashboard') }}" class="hover:text-slate-700 cursor-pointer transition-colors">Home</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-slate-800 font-medium">Enrollment</span>
                </div>
                @include('components.notification-bell')
            </div>
        </header>

        <!-- Enrollment Content -->
        <div class="p-6 max-w-4xl mx-auto animate-fade-in">

            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">School Year Enrollment</h1>
                <p class="text-slate-500 mt-1">Enroll for the upcoming school year</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl p-4 flex items-start gap-3">
                    <i class="fas fa-check-circle text-emerald-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-emerald-800">Success</p>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 rounded-2xl p-4 flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-rose-600 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-rose-800">Please fix the following:</p>
                        <ul class="text-sm text-rose-700 mt-1 space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Enrollment Closed -->
            @if(!$enrollmentEnabled)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 p-8 text-center">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-slate-400 text-3xl"></i>
                    </div>
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Enrollment is Currently Closed</h2>
                    <p class="text-slate-500 max-w-md mx-auto">
                        Online enrollment is not open at this time. Please check back later or contact the school administration for assistance.
                    </p>
                </div>
            @else

                <!-- Already Enrolled -->
                @if($isAlreadyEnrolled)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-3xl p-8 text-center">
                        <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-double text-emerald-600 text-3xl"></i>
                        </div>
                        <h2 class="text-xl font-bold text-emerald-900 mb-2">You're All Set!</h2>
                        <p class="text-emerald-700 max-w-md mx-auto">
                            You are already enrolled for <strong>{{ $currentSchoolYear?->name }}</strong>. No further action is needed.
                        </p>
                        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 mt-6 px-6 py-3 bg-emerald-600 text-white rounded-xl font-semibold hover:bg-emerald-700 transition-colors">
                            <i class="fas fa-arrow-left"></i>
                            Back to Dashboard
                        </a>
                    </div>

                <!-- Pending Application -->
                @elseif($existingApplication)
                    <div class="bg-amber-50 border border-amber-200 rounded-3xl p-8">
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-clock text-amber-600 text-3xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-amber-900 mb-2">Application Pending</h2>
                            <p class="text-amber-700">
                                Your enrollment application is being reviewed by the school administration.
                            </p>
                        </div>

                        <div class="bg-white rounded-2xl border border-amber-200 p-6 max-w-lg mx-auto">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="text-slate-500">Application #</span>
                                    <p class="font-bold text-slate-800">{{ $existingApplication->application_number }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500">Status</span>
                                    <p class="font-bold text-amber-700">{{ $existingApplication->status_label }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500">Submitted</span>
                                    <p class="font-bold text-slate-800">{{ $existingApplication->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="text-slate-500">Grade Level</span>
                                    <p class="font-bold text-slate-800">{{ $existingApplication->gradeLevel?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-6">
                            <a href="{{ route('enrollment.check') }}" class="text-amber-700 hover:text-amber-900 font-medium text-sm underline">
                                <i class="fas fa-search mr-1"></i> Check application status
                            </a>
                        </div>
                    </div>

                <!-- Enrollment Form -->
                @else
                    <div class="bg-white rounded-3xl border border-slate-200 shadow-lg shadow-slate-200/50 overflow-hidden">
                        <!-- Student Info Banner -->
                        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 p-6 text-white">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                                    <i class="fas fa-user-graduate text-2xl"></i>
                                </div>
                                <div>
                                    <h2 class="font-bold text-lg">{{ $user->first_name }} {{ $user->last_name }}</h2>
                                    <p class="text-white/80 text-sm">
                                        LRN: {{ $student->lrn }} &middot;
                                        Current Grade: {{ $student->gradeLevel?->name ?? 'N/A' }}
                                    </p>
                                    @if(isset($generalAverage) && $generalAverage > 0)
                                    <p class="text-white/80 text-sm mt-1">
                                        General Average: <span class="font-bold">{{ $generalAverage }}</span>
                                        @if($isPassing)
                                            <span class="ml-2 px-2 py-0.5 bg-emerald-400/30 rounded text-xs">Passed</span>
                                        @else
                                            <span class="ml-2 px-2 py-0.5 bg-rose-400/30 rounded text-xs">Failed</span>
                                        @endif
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="p-8">
                            @if(!$currentSchoolYear)
                                <div class="text-center py-8">
                                    <i class="fas fa-calendar-times text-slate-300 text-4xl mb-3"></i>
                                    <p class="text-slate-500">No active school year found. Please contact the school administration.</p>
                                </div>
                            @else
                                <form action="{{ route('student.enrollment.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <input type="hidden" name="application_type" value="continuing">

                                    <!-- School Year -->
                                    <div class="bg-indigo-50 rounded-xl p-4 flex items-center gap-3">
                                        <i class="fas fa-calendar-check text-indigo-600"></i>
                                        <div>
                                            <span class="text-sm text-slate-600">Enrolling for School Year</span>
                                            <p class="font-bold text-indigo-900">{{ $currentSchoolYear->name }}</p>
                                        </div>
                                    </div>

                                    <!-- Grade Level (System Determined) -->
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-award text-indigo-500 mr-1"></i>
                                            Grade Level to Enroll <span class="text-rose-500">*</span>
                                        </label>
                                        @if($suggestedGradeLevel)
                                            <input type="hidden" name="grade_level_id" value="{{ $suggestedGradeLevel->id }}">
                                            <div class="w-full px-4 py-3 bg-indigo-50 border border-indigo-200 rounded-xl text-indigo-900 font-semibold">
                                                {{ $suggestedGradeLevel->name }}
                                                @if($isRetained)
                                                    <span class="ml-2 px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs">Retained</span>
                                                @else
                                                    <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs">Promoted</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-500 mt-1">
                                                @if($isRetained)
                                                    <i class="fas fa-exclamation-circle mr-1 text-rose-500"></i>
                                                    Your general average ({{ $generalAverage }}) is below 75. You are retained in the same grade level.
                                                @else
                                                    <i class="fas fa-check-circle mr-1 text-emerald-500"></i>
                                                    Your general average ({{ $generalAverage }}) qualifies you for the next grade level.
                                                @endif
                                            </p>
                                        @else
                                            <div class="w-full px-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500">
                                                Unable to determine grade level. Please contact the school administration.
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Parent Email -->
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            <i class="fas fa-envelope text-indigo-500 mr-1"></i>
                                            Parent / Guardian Email <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="email" name="parent_email" required
                                               value="{{ old('parent_email', $user->email) }}"
                                               placeholder="parent@email.com"
                                               class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                                        <p class="text-xs text-slate-500 mt-1">
                                            We'll send enrollment confirmation and status updates to this email
                                        </p>
                                    </div>

                                    <!-- Terms -->
                                    <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl">
                                        <input type="checkbox" id="confirm" required class="mt-1 w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-500">
                                        <label for="confirm" class="text-sm text-slate-600">
                                            I confirm that the information provided is accurate and I am authorized to enroll this student for the upcoming school year.
                                        </label>
                                    </div>

                                    <!-- Submit -->
                                    <button type="submit"
                                            class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-semibold rounded-xl hover:from-indigo-700 hover:to-violet-700 transition-all flex items-center justify-center gap-2 shadow-lg shadow-indigo-200">
                                        <i class="fas fa-check-circle"></i>
                                        Submit Enrollment Application
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </main>
</body>
</html>
