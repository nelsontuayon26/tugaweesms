<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Grades - {{ $section->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>[x-cloak] { display: none !important; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }</style>
</head>
<body class="bg-slate-50 min-h-screen" x-data="{ mobileOpen: false }">

<div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
     class="fixed inset-0 z-40 lg:hidden bg-slate-900/30 backdrop-blur-sm"></div>

<button @click="mobileOpen = !mobileOpen"
        class="fixed top-4 left-4 z-50 lg:hidden w-12 h-12 bg-white rounded-2xl shadow-lg flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all border border-slate-100">
    <i class="fas fa-bars text-lg"></i>
</button>

<div class="flex">
    @include('teacher.includes.sidebar')

    <div class="lg:ml-72 w-full min-h-screen p-6">

        <!-- Header -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-slate-500 mb-3">
                <a href="{{ route('teacher.dashboard') }}" class="hover:text-indigo-600 transition-colors">Dashboard</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <a href="{{ route('teacher.sections.index') }}" class="hover:text-indigo-600 transition-colors">Sections</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-slate-700 font-medium">{{ $section->name }}</span>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-indigo-600 font-medium">Grades</span>
            </nav>
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Grade Management</h1>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $section->name }} &middot; {{ $section->gradeLevel->name ?? 'N/A' }}</p>
                </div>
                <div class="flex items-center gap-2 bg-white rounded-xl border border-slate-200 shadow-sm px-3 py-2">
                    <span class="text-sm font-medium text-slate-600">Quarter:</span>
                    <select id="quarterSelect" class="bg-transparent text-sm font-medium text-slate-700 focus:outline-none cursor-pointer">
                        <option value="1" {{ $requestedQuarter == 1 ? 'selected' : '' }}>1st Quarter</option>
                        <option value="2" {{ $requestedQuarter == 2 ? 'selected' : '' }}>2nd Quarter</option>
                        <option value="3" {{ $requestedQuarter == 3 ? 'selected' : '' }}>3rd Quarter</option>
                        <option value="4" {{ $requestedQuarter == 4 ? 'selected' : '' }}>4th Quarter</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Quarter Info Banner --}}
        @if($quarterInfo)
        <div class="mb-5">
            <div class="bg-white border border-slate-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">
                        Q{{ $requestedQuarter }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-900">{{ $quarterInfo->display_name }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $quarterInfo->start_date?->format('M d, Y') }} — {{ $quarterInfo->end_date?->format('M d, Y') }}
                            @if($quarterInfo->is_current)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold uppercase tracking-wide">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1 animate-pulse"></span>Current
                                </span>
                            @elseif($quarterInfo->is_past)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wide">Ended</span>
                            @elseif($quarterInfo->is_upcoming)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-[10px] font-bold uppercase tracking-wide">Upcoming</span>
                            @endif
                        </p>
                    </div>
                </div>
                @if($quarterInfo->is_current && $quarterInfo->progress_percent)
                <div class="flex items-center gap-3 min-w-[160px]">
                    <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full transition-all" style="width: {{ $quarterInfo->progress_percent }}%"></div>
                    </div>
                    <span class="text-xs font-semibold text-slate-600 w-8 text-right">{{ $quarterInfo->progress_percent }}%</span>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if(!$isEditable)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 flex items-start gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-lock text-amber-600"></i>
            </div>
            <div>
                @if($finalization?->grades_finalized)
                    <h3 class="font-semibold text-amber-900">Grades Finalized</h3>
                    <p class="text-sm text-amber-700">This section has been finalized. You cannot edit grades.</p>
                    @if($finalization?->grades_finalized_at)
                    <p class="text-xs text-amber-600 mt-1">Finalized on: {{ $finalization->grades_finalized_at->format('F d, Y \a\t h:i A') }}</p>
                    @endif
                @elseif($finalization?->is_locked)
                    <h3 class="font-semibold text-amber-900">Section Locked</h3>
                    <p class="text-sm text-amber-700">This section is currently locked by the administrator.</p>
                @elseif($quarterInfo && !$quarterInfo->is_current)
                    <h3 class="font-semibold text-amber-900">Quarter Not Active</h3>
                    <p class="text-sm text-amber-700">
                        Grade entry is only allowed during the quarter's scheduled period 
                        ({{ $quarterInfo->start_date?->format('M d, Y') }} — {{ $quarterInfo->end_date?->format('M d, Y') }}).
                    </p>
                    @if($quarterInfo->is_upcoming)
                        <p class="text-xs text-amber-600 mt-1">This quarter hasn't started yet.</p>
                    @elseif($quarterInfo->is_past)
                        <p class="text-xs text-amber-600 mt-1">This quarter has ended. Contact your administrator if you need to make corrections.</p>
                    @endif
                @else
                    <h3 class="font-semibold text-amber-900">Editing Disabled</h3>
                    <p class="text-sm text-amber-700">Grade editing is currently disabled for this section.</p>
                @endif
            </div>
        </div>
        @endif

        <!-- Save Modal -->
        <div id="saveGradesModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div id="saveModalContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 relative overflow-hidden">
                <div id="modalProgressBar" class="absolute top-0 left-0 h-1 bg-slate-300 w-full"><div id="modalProgressFill" class="h-full w-full"></div></div>
                <div id="saveModalSuccess" class="hidden">
                    <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                        <div id="successIcon" class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900">Saved Successfully!</h3>
                        <p class="text-sm text-emerald-600 mt-1">Grades have been recorded</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="successMessage">Grades have been saved successfully.</p>
                        <p class="text-xs text-slate-400 mb-3">Closing in <span id="successCountdown">3</span>s...</p>
                        <button onclick="closeSaveModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">Continue</button>
                    </div>
                </div>
                <div id="saveModalError" class="hidden">
                    <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                        <div id="errorIcon" class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-900">Save Failed!</h3>
                        <p class="text-sm text-red-600 mt-1">Unable to save grades</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="errorMessage">An error occurred while saving grades.</p>
                        <p class="text-xs text-slate-400 mb-3">Closing in <span id="errorCountdown">3</span>s...</p>
                        <button onclick="closeSaveModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">Try Again</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Finalize Result Modal -->
        <div id="finalizeResultModal" class="fixed inset-0 z-[9999] hidden flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div id="finalizeResultContent" class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 relative overflow-hidden">
                <div id="finalizeProgressBar" class="absolute top-0 left-0 h-1 bg-slate-300 w-full"><div id="finalizeProgressFill" class="h-full w-full"></div></div>
                <div id="finalizeResultSuccess" class="hidden">
                    <div class="bg-emerald-50 rounded-t-2xl p-6 border-b border-emerald-100 text-center">
                        <div class="w-20 h-20 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-emerald-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-emerald-900">Finalized Successfully!</h3>
                        <p class="text-sm text-emerald-600 mt-1">Grades have been locked</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="finalizeSuccessMessage">Grades have been finalized successfully.</p>
                        <p class="text-xs text-slate-400 mb-3">Closing in <span id="finalizeSuccessCountdown">3</span>s...</p>
                        <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-colors">Continue</button>
                    </div>
                </div>
                <div id="finalizeResultError" class="hidden">
                    <div class="bg-red-50 rounded-t-2xl p-6 border-b border-red-100 text-center">
                        <div class="w-20 h-20 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-600 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-red-900">Finalization Failed!</h3>
                        <p class="text-sm text-red-600 mt-1">Unable to finalize grades</p>
                    </div>
                    <div class="p-6 text-center">
                        <p class="text-slate-600 mb-4" id="finalizeErrorMessage">An error occurred while finalizing grades.</p>
                        <p class="text-xs text-slate-400 mb-3">Closing in <span id="finalizeErrorCountdown">3</span>s...</p>
                        <button onclick="closeFinalizeResultModal()" class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">Try Again</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Selector -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-slate-900">Select Subject</h3>
                    <p class="text-sm text-slate-500">Choose a subject to manage grades for <span class="font-medium text-indigo-600">{{ $section->gradeLevel->name ?? 'this section' }}</span></p>
                </div>
                <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center gap-3" id="subjectForm">
                    <input type="hidden" name="quarter" id="quarterInput" value="{{ $requestedQuarter }}">
                    <input type="hidden" name="grade_level" value="{{ $section->grade_level_id }}">
                    <select name="subject" id="subjectSelect"
                            class="pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-slate-700 font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 min-w-[220px]"
                            onchange="if(this.value) this.form.submit();">
                        <option value="">Select Subject</option>
                        @foreach($filteredSubjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" id="loadGradesBtn"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed hidden sm:block"
                            {{ !request('subject') ? 'disabled' : '' }}>Load Grades</button>
                </form>
            </div>
            @if(isset($selectedGradeLevel))
            <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap gap-4 text-sm">
                <span class="text-slate-600"><span class="font-medium text-slate-900">{{ $selectedGradeLevel->name }}</span> &middot; {{ $filteredSubjects->count() }} subjects &middot; {{ $students->count() }} students</span>
            </div>
            @endif
        </div>

        @if(isset($selectedSubject))
        <form method="POST" action="{{ route('teacher.sections.grades.store', $section) }}" class="space-y-6" id="gradeForm">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
            <input type="hidden" name="grade_level_id" value="{{ $selectedGradeLevel->id }}">
            <input type="hidden" name="quarter" value="{{ $requestedQuarter }}">

            <!-- Subject Banner -->
            <div class="bg-indigo-600 rounded-xl shadow-sm p-5 text-white flex items-center justify-between">
                <div>
                    <p class="text-indigo-200 text-xs uppercase tracking-wide font-medium">Currently Managing</p>
                    <h2 class="text-xl font-bold">{{ $selectedSubject->name }}</h2>
                    <p class="text-indigo-200 text-sm">{{ $selectedGradeLevel->name }} &middot; {{ $section->name }} &middot; Quarter {{ $requestedQuarter }}</p>
                </div>
                <div class="text-right">
                    <p class="text-indigo-200 text-xs">Code</p>
                    <p class="text-lg font-bold">{{ $selectedSubject->code ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Component Weights -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="w-full px-5 py-4 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-sliders-h text-indigo-600"></i>
                        <span class="font-semibold text-slate-900">Component Weights</span>
                        <span class="text-xs text-slate-500">(Total must equal 100%)</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex gap-2 text-xs">
                            <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded font-medium">WW {{ $gradeWeights->ww_weight }}%</span>
                            <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded font-medium">PT {{ $gradeWeights->pt_weight }}%</span>
                            <span class="px-2 py-1 bg-amber-50 text-amber-700 rounded font-medium">QE {{ $gradeWeights->qe_weight }}%</span>
                        </div>
                        <i class="fas fa-chevron-down text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                </button>
                <div x-show="open" x-collapse class="border-t border-slate-100">
                    <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Written Work (%)</label>
                            <input type="number" name="ww_weight" id="wwWeight" value="{{ $gradeWeights->ww_weight }}" min="0" max="100"
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-semibold text-blue-600 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                                   onchange="validateWeights()" {{ !$isEditable ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Performance Task (%)</label>
                            <input type="number" name="pt_weight" id="ptWeight" value="{{ $gradeWeights->pt_weight }}" min="0" max="100"
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 font-semibold text-purple-600 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                                   onchange="validateWeights()" {{ !$isEditable ? 'disabled' : '' }}>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Quarterly Exam (%)</label>
                            <input type="number" name="qe_weight" id="qeWeight" value="{{ $gradeWeights->qe_weight }}" min="0" max="100"
                                   class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 font-semibold text-amber-600 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}"
                                   onchange="validateWeights()" {{ !$isEditable ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div id="weightWarning" class="hidden px-5 pb-4 text-red-600 text-sm flex items-center gap-2">
                        <i class="fas fa-exclamation-triangle"></i> Total weight must equal 100%
                    </div>
                    <div class="px-5 pb-4">
                        <button type="button" onclick="resetWeights()" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>Reset to Default (40/40/20)</button>
                    </div>
                </div>
            </div>
            <!-- Tabs -->
            <div x-data="{ activeTab: 'ww' }" class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="flex overflow-x-auto border-b border-slate-200 bg-slate-50 scrollbar-hide">
                    <button type="button" @click="activeTab = 'ww'"
                            :class="activeTab === 'ww' ? 'border-b-2 border-blue-600 text-blue-600 bg-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                            class="flex-shrink-0 lg:flex-1 px-4 py-3 text-sm font-semibold transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-pen text-xs"></i> <span class="hidden sm:inline">Written Work</span><span class="sm:hidden">WW</span>
                    </button>
                    <button type="button" @click="activeTab = 'pt'"
                            :class="activeTab === 'pt' ? 'border-b-2 border-purple-600 text-purple-600 bg-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                            class="flex-shrink-0 lg:flex-1 px-4 py-3 text-sm font-semibold transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-tasks text-xs"></i> <span class="hidden sm:inline">Performance Tasks</span><span class="sm:hidden">PT</span>
                    </button>
                    <button type="button" @click="activeTab = 'qe'"
                            :class="activeTab === 'qe' ? 'border-b-2 border-amber-600 text-amber-600 bg-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                            class="flex-shrink-0 lg:flex-1 px-4 py-3 text-sm font-semibold transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-file-alt text-xs"></i> <span class="hidden sm:inline">Quarterly Exam</span><span class="sm:hidden">QE</span>
                    </button>
                    <button type="button" @click="activeTab = 'summary'"
                            :class="activeTab === 'summary' ? 'border-b-2 border-indigo-600 text-indigo-600 bg-white' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-100'"
                            class="flex-shrink-0 lg:flex-1 px-4 py-3 text-sm font-semibold transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                        <i class="fas fa-calculator text-xs"></i> Summary
                    </button>
                </div>

                <!-- Written Work Tab -->
                <div x-show="activeTab === 'ww'" class="p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-900">Written Work Scores</h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="addWWColumn()" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                <i class="fas fa-plus text-xs"></i> Add Activity
                            </button>
                            <button type="button" onclick="removeLastWW()" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                <i class="fas fa-minus text-xs"></i> Remove Last
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse" id="wwTable">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200" id="wwHeaderRow">
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700 w-10">#</th>
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700">Student Name</th>
                                    @php $wwTitles = $existingGrades['ww_titles'] ?? []; @endphp
                                    @php $wwTotalItems = $existingGrades['ww_total_items'] ?? []; @endphp
                                    @for($i = 0; $i < max(3, count($wwTitles)); $i++)
                                    <th class="px-2 py-2.5 text-center font-semibold text-slate-700 ww-col-header" data-col="{{ $i + 1 }}">
                                        <div class="mb-1 text-xs">WW {{ $i + 1 }}</div>
                                        <input type="text" name="ww_titles[]" class="ww-title w-14 text-center bg-transparent border-b border-slate-300 text-xs mb-1 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" placeholder="Title" value="{{ $wwTitles[$i] ?? '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                        <input type="number" name="ww_total_items[]" class="ww-total-item w-14 text-center bg-slate-50 border border-slate-200 rounded text-xs py-0.5 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" placeholder="Items" value="{{ $wwTotalItems[$i] ?? 100 }}" min="1" onchange="calculateAllWW()" {{ !$isEditable ? 'disabled' : '' }}>
                                    </th>
                                    @endfor
                                    <th class="px-3 py-2.5 text-center font-semibold text-blue-700 bg-blue-50/50">Total</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-blue-700 bg-blue-50/50">PS</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-blue-700 bg-blue-50/50">WS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="wwTableBody">
                                @foreach($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors" data-student-id="{{ $student->id }}">
                                    <td class="px-3 py-2.5 text-slate-400 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2.5 font-medium text-slate-900">{{ $student->user->last_name }}, {{ $student->user->first_name }}</td>
                                    @php $wwKey = $student->id . '_written_work'; @endphp
                                    @php $wwScores = $existingGrades[$wwKey]['scores'] ?? []; @endphp
                                    @for($i = 0; $i < max(3, count($wwTitles)); $i++)
                                    <td class="px-2 py-2.5 text-center ww-col" data-col="{{ $i + 1 }}">
                                        <input type="number" name="ww[{{ $student->id }}][]" class="ww-score w-16 px-2 py-1.5 text-center rounded-md border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" min="0" value="{{ $wwScores[$i] ?? '' }}" onchange="calculateStudentWW({{ $student->id }})" {{ !$isEditable ? 'disabled' : '' }}>
                                    </td>
                                    @endfor
                                    <td class="px-3 py-2.5 text-center font-semibold text-slate-700"><span class="ww-total" id="ww-total-{{ $student->id }}">0</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="ww-ps px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-semibold text-xs" id="ww-ps-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="ww-ws px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-bold text-xs" id="ww-ws-{{ $student->id }}">0.00</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Performance Tasks Tab -->
                <div x-show="activeTab === 'pt'" class="p-5" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-900">Performance Task Scores</h3>
                        <div class="flex gap-2">
                            <button type="button" onclick="addPTColumn()" class="px-3 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                <i class="fas fa-plus text-xs"></i> Add Task
                            </button>
                            <button type="button" onclick="removeLastPT()" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-600 rounded-lg text-sm font-medium transition-colors flex items-center gap-1.5 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                <i class="fas fa-minus text-xs"></i> Remove Last
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse" id="ptTable">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200" id="ptHeaderRow">
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700 w-10">#</th>
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700">Student Name</th>
                                    @php $ptTitles = $existingGrades['pt_titles'] ?? []; @endphp
                                    @php $ptTotalItems = $existingGrades['pt_total_items'] ?? []; @endphp
                                    @for($i = 0; $i < max(2, count($ptTitles)); $i++)
                                    <th class="px-2 py-2.5 text-center font-semibold text-slate-700 pt-col-header" data-col="{{ $i + 1 }}">
                                        <div class="mb-1 text-xs">PT {{ $i + 1 }}</div>
                                        <input type="text" name="pt_titles[]" class="pt-title w-14 text-center bg-transparent border-b border-slate-300 text-xs mb-1 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" placeholder="Title" value="{{ $ptTitles[$i] ?? '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                                        <input type="number" name="pt_total_items[]" class="pt-total-item w-14 text-center bg-slate-50 border border-slate-200 rounded text-xs py-0.5 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" placeholder="Items" value="{{ $ptTotalItems[$i] ?? 100 }}" min="1" onchange="calculateAllPT()" {{ !$isEditable ? 'disabled' : '' }}>
                                    </th>
                                    @endfor
                                    <th class="px-3 py-2.5 text-center font-semibold text-purple-700 bg-purple-50/50">Total</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-purple-700 bg-purple-50/50">PS</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-purple-700 bg-purple-50/50">WS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="ptTableBody">
                                @foreach($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors" data-student-id="{{ $student->id }}">
                                    <td class="px-3 py-2.5 text-slate-400 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2.5 font-medium text-slate-900">{{ $student->user->last_name }}, {{ $student->user->first_name }}</td>
                                    @php $ptKey = $student->id . '_performance_task'; @endphp
                                    @php $ptScores = $existingGrades[$ptKey]['scores'] ?? []; @endphp
                                    @for($i = 0; $i < max(2, count($ptTitles)); $i++)
                                    <td class="px-2 py-2.5 text-center pt-col" data-col="{{ $i + 1 }}">
                                        <input type="number" name="pt[{{ $student->id }}][]" class="pt-score w-16 px-2 py-1.5 text-center rounded-md border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 transition-all {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" min="0" value="{{ $ptScores[$i] ?? '' }}" onchange="calculateStudentPT({{ $student->id }})" {{ !$isEditable ? 'disabled' : '' }}>
                                    </td>
                                    @endfor
                                    <td class="px-3 py-2.5 text-center font-semibold text-slate-700"><span class="pt-total" id="pt-total-{{ $student->id }}">0</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="pt-ps px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-semibold text-xs" id="pt-ps-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="pt-ws px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-bold text-xs" id="pt-ws-{{ $student->id }}">0.00</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quarterly Exam Tab -->
                <div x-show="activeTab === 'qe'" class="p-5" x-cloak>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-slate-900">Quarterly Exam Scores</h3>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Total Items:</span>
                            <input type="hidden" name="qe_total_items" id="qeTotalItemsInput" value="{{ $existingGrades['qe_total_items'] ?? 100 }}">
                            <input type="number" id="qeTotal" value="{{ $existingGrades['qe_total_items'] ?? 100 }}" class="w-20 px-3 py-1.5 rounded-md border border-slate-200 text-sm font-semibold text-amber-600 {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" onchange="updateQETotalItems(this.value)" {{ !$isEditable ? 'disabled' : '' }}>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse" id="qeTable">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700 w-10">#</th>
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700">Student Name</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">Raw Score</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-amber-700 bg-amber-50/50">PS</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-amber-700 bg-amber-50/50">WS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="qeTableBody">
                                @foreach($students as $index => $student)
                                <tr class="hover:bg-slate-50/50 transition-colors" data-student-id="{{ $student->id }}">
                                    <td class="px-3 py-2.5 text-slate-400 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2.5 font-medium text-slate-900">{{ $student->user->last_name }}, {{ $student->user->first_name }}</td>
                                    <td class="px-3 py-2.5 text-center">
                                        @php $qeKey = $student->id . '_quarterly_exam'; @endphp
                                        <input type="number" name="qe[{{ $student->id }}]" class="qe-score w-20 px-3 py-1.5 text-center rounded-md border border-slate-200 focus:border-amber-500 focus:ring-1 focus:ring-amber-500/20 transition-all font-semibold {{ !$isEditable ? 'bg-slate-100 cursor-not-allowed' : '' }}" min="0" value="{{ $existingGrades[$qeKey]['total_score'] ?? '' }}" onchange="calculateStudentQE({{ $student->id }})" {{ !$isEditable ? 'disabled' : '' }}>
                                    </td>
                                    <td class="px-3 py-2.5 text-center"><span class="qe-ps px-2 py-0.5 rounded bg-amber-50 text-amber-700 font-semibold text-xs" id="qe-ps-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="qe-ws px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-bold text-xs" id="qe-ws-{{ $student->id }}">0.00</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary Tab -->
                <div x-show="activeTab === 'summary'" class="p-5" x-cloak>
                    <h3 class="font-semibold text-slate-900 mb-4">Final Grade Summary</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[700px] text-sm border-collapse" id="summaryTable">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-200">
                                    <th class="px-3 py-2.5 text-left font-semibold text-slate-700">Student</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">WW ({{ $gradeWeights->ww_weight }}%)</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">PT ({{ $gradeWeights->pt_weight }}%)</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">QE ({{ $gradeWeights->qe_weight }}%)</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">Initial</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700 bg-indigo-50/50">Transmuted</th>
                                    <th class="px-3 py-2.5 text-center font-semibold text-slate-700">Remarks</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100" id="summaryTableBody">
                                @foreach($students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors" data-student-id="{{ $student->id }}">
                                    <td class="px-3 py-2.5 font-medium text-slate-900">{{ $student->user->last_name }}, {{ $student->user->first_name }}</td>
                                    <td class="px-3 py-2.5 text-center"><span class="final-ww" id="final-ww-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="final-pt" id="final-pt-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="final-qe" id="final-qe-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center font-semibold"><span class="initial-grade" id="initial-grade-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center bg-slate-50/50"><span class="transmuted-grade text-lg font-bold" id="transmuted-{{ $student->id }}">0.00</span></td>
                                    <td class="px-3 py-2.5 text-center"><span class="remarks px-2 py-0.5 rounded-full text-xs font-bold" id="remarks-{{ $student->id }}">-</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                <div class="text-sm text-slate-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Changes are calculated automatically. Click <strong>Save</strong> to store grades.
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="calculateAllGrades()" class="px-5 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 rounded-lg font-medium transition-colors flex items-center gap-2 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                        <i class="fas fa-sync-alt text-sm"></i> Recalculate
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2 {{ !$isEditable ? 'opacity-50 cursor-not-allowed' : '' }}" {{ !$isEditable ? 'disabled' : '' }}>
                        <i class="fas fa-save text-sm"></i> Save Grades
                    </button>
                </div>
            </div>
        </form>

        @if($isAdviser && $isEditable && !$finalization?->grades_finalized)
        <div class="mt-6 bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-lock text-amber-600"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-slate-900">Finalize Grades</h3>
                        <p class="text-sm text-slate-500">Once finalized, grades for this section will be locked.</p>
                    </div>
                </div>
                <button type="button" onclick="showFinalizeGradesModal()" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Finalize Grades
                </button>
            </div>
        </div>

        <div id="finalizeGradesModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full mx-4">
                <div class="bg-amber-50 rounded-t-2xl p-5 border-b border-amber-100">
                    <h3 class="text-lg font-bold text-amber-900">Finalize Grades?</h3>
                    <p class="text-sm text-amber-600">This action cannot be undone</p>
                </div>
                <div class="p-5">
                    <div class="bg-slate-50 rounded-lg p-3 mb-3 text-sm text-slate-700">
                        You are about to finalize grades for <strong>{{ $section->name }}</strong>.
                    </div>
                    <form id="finalizeGradesForm" action="{{ route('teacher.sections.grades.finalize', $section) }}" method="POST">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" onclick="document.getElementById('finalizeGradesModal').classList.add('hidden')" class="flex-1 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors">Cancel</button>
                            <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-lock"></i> Yes, Finalize
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="finalizingModal" class="hidden fixed inset-0 z-[10000] flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6 text-center">
                <div class="w-10 h-10 rounded-full border-4 border-amber-200 border-t-amber-500 animate-spin mx-auto mb-3"></div>
                <h3 class="text-base font-semibold text-slate-800">Finalizing Grades...</h3>
                <p class="text-sm text-slate-500 mt-1">Please wait</p>
            </div>
        </div>
        @elseif($finalization?->grades_finalized)
        <div class="mt-6 bg-emerald-50 rounded-xl border border-emerald-200 p-5 flex items-start gap-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-double text-emerald-600"></i>
            </div>
            <div>
                <h3 class="font-semibold text-emerald-900">Grades Finalized</h3>
                <p class="text-sm text-emerald-700">Grades were finalized on {{ $finalization->grades_finalized_at?->format('F d, Y \a\t h:i A') }}.</p>
            </div>
        </div>
        @endif

        @else
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-slate-100 flex items-center justify-center">
                <i class="fas fa-book-open text-slate-300 text-3xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-slate-900 mb-1">Select a Subject</h3>
            <p class="text-slate-500">Choose a subject from the dropdown above to start managing grades.</p>
        </div>
        @endif
    </div>
</div>

<script>
    const existingGrades = @json($existingGrades ?? []);
    let wwColCount = Math.max(3, (existingGrades['ww_titles'] || []).length);
    let ptColCount = Math.max(2, (existingGrades['pt_titles'] || []).length);

    const transmutationTable = [
        {range:[100,100],grade:100},{range:[98.40,99.99],grade:99},{range:[96.80,98.39],grade:98},
        {range:[95.20,96.79],grade:97},{range:[93.60,95.19],grade:96},{range:[92.00,93.59],grade:95},
        {range:[90.40,91.99],grade:94},{range:[88.80,90.39],grade:93},{range:[87.20,88.79],grade:92},
        {range:[85.60,87.19],grade:91},{range:[84.00,85.59],grade:90},{range:[82.40,83.99],grade:89},
        {range:[80.80,82.39],grade:88},{range:[79.20,80.79],grade:87},{range:[77.60,79.19],grade:86},
        {range:[76.00,77.59],grade:85},{range:[74.40,75.99],grade:84},{range:[72.80,74.39],grade:83},
        {range:[71.20,72.79],grade:82},{range:[69.60,71.19],grade:81},{range:[68.00,69.59],grade:80},
        {range:[66.40,67.99],grade:79},{range:[64.80,66.39],grade:78},{range:[63.20,64.79],grade:77},
        {range:[61.60,63.19],grade:76},{range:[60.00,61.59],grade:75},{range:[56.00,59.99],grade:74},
        {range:[52.00,55.99],grade:73},{range:[48.00,51.99],grade:72},{range:[44.00,47.99],grade:71},
        {range:[40.00,43.99],grade:70},{range:[36.00,39.99],grade:69},{range:[32.00,35.99],grade:68},
        {range:[28.00,31.99],grade:67},{range:[24.00,27.99],grade:66},{range:[20.00,23.99],grade:65},
        {range:[16.00,19.99],grade:64},{range:[12.00,15.99],grade:63},{range:[8.00,11.99],grade:62},
        {range:[4.00,7.99],grade:61},{range:[0,3.99],grade:60}
    ];

    function transmuteGrade(initialGrade) {
        for (let item of transmutationTable) {
            if (initialGrade >= item.range[0] && initialGrade <= item.range[1]) return item.grade;
        }
        return 60;
    }
    function getRemarks(grade) {
        if (grade >= 75) return {text:'Passed', class:'bg-emerald-100 text-emerald-700'};
        if (grade >= 70) return {text:'Almost Passed', class:'bg-amber-100 text-amber-700'};
        return {text:'Failed', class:'bg-red-100 text-red-700'};
    }
    function validateWeights() {
        const ww = parseFloat(document.getElementById('wwWeight').value)||0;
        const pt = parseFloat(document.getElementById('ptWeight').value)||0;
        const qe = parseFloat(document.getElementById('qeWeight').value)||0;
        const el = document.getElementById('weightWarning');
        el.classList.toggle('hidden', Math.round(ww+pt+qe)===100);
        if (Math.round(ww+pt+qe)===100) calculateAllGrades();
    }
    function resetWeights() {
        document.getElementById('wwWeight').value=40;
        document.getElementById('ptWeight').value=40;
        document.getElementById('qeWeight').value=20;
        document.getElementById('weightWarning').classList.add('hidden');
        calculateAllGrades();
    }
    function calculateStudentWW(studentId) {
        const row = document.querySelector(`#wwTableBody tr[data-student-id="${studentId}"]`);
        if (!row) return;
        const scores = row.querySelectorAll('.ww-score');
        const items = document.querySelectorAll('#wwHeaderRow .ww-total-item');
        let totalScore=0, totalPossible=0;
        scores.forEach((input,idx)=>{
            if (input.value!=='') {
                const s=parseFloat(input.value)||0;
                const t=items[idx]?(parseFloat(items[idx].value)||100):100;
                totalScore+=s; totalPossible+=t;
            }
        });
        const ps = totalPossible>0?(totalScore/totalPossible)*100:0;
        const ws = (ps*((parseFloat(document.getElementById('wwWeight').value)||40)/100)).toFixed(2);
        const totEl=document.getElementById(`ww-total-${studentId}`);
        const psEl=document.getElementById(`ww-ps-${studentId}`);
        const wsEl=document.getElementById(`ww-ws-${studentId}`);
        if(totEl)totEl.textContent=totalScore.toFixed(0)+' / '+totalPossible;
        if(psEl)psEl.textContent=ps.toFixed(2);
        if(wsEl)wsEl.textContent=ws;
        calculateFinalGrade(studentId);
    }
    function calculateAllWW() {
        document.querySelectorAll('#wwTableBody tr').forEach(row=>{
            const id=row.getAttribute('data-student-id');
            if(id)calculateStudentWW(id);
        });
    }
    function calculateStudentPT(studentId) {
        const row = document.querySelector(`#ptTableBody tr[data-student-id="${studentId}"]`);
        if (!row) return;
        const scores = row.querySelectorAll('.pt-score');
        const items = document.querySelectorAll('#ptHeaderRow .pt-total-item');
        let totalScore=0, totalPossible=0;
        scores.forEach((input,idx)=>{
            if (input.value!=='') {
                const s=parseFloat(input.value)||0;
                const t=items[idx]?(parseFloat(items[idx].value)||100):100;
                totalScore+=s; totalPossible+=t;
            }
        });
        const ps = totalPossible>0?(totalScore/totalPossible)*100:0;
        const ws = (ps*((parseFloat(document.getElementById('ptWeight').value)||40)/100)).toFixed(2);
        const totEl=document.getElementById(`pt-total-${studentId}`);
        const psEl=document.getElementById(`pt-ps-${studentId}`);
        const wsEl=document.getElementById(`pt-ws-${studentId}`);
        if(totEl)totEl.textContent=totalScore.toFixed(0)+' / '+totalPossible;
        if(psEl)psEl.textContent=ps.toFixed(2);
        if(wsEl)wsEl.textContent=ws;
        calculateFinalGrade(studentId);
    }
    function calculateAllPT() {
        document.querySelectorAll('#ptTableBody tr').forEach(row=>{
            const id=row.getAttribute('data-student-id');
            if(id)calculateStudentPT(id);
        });
    }
    function calculateStudentQE(studentId) {
        const row = document.querySelector(`#qeTableBody tr[data-student-id="${studentId}"]`);
        if (!row) return;
        const input = row.querySelector('.qe-score');
        if (!input) return;
        const score = parseFloat(input.value)||0;
        const totalItems = parseFloat(document.getElementById('qeTotal').value)||100;
        const ps = (score/totalItems)*100;
        const ws = (ps*((parseFloat(document.getElementById('qeWeight').value)||20)/100)).toFixed(2);
        const psEl=document.getElementById(`qe-ps-${studentId}`);
        const wsEl=document.getElementById(`qe-ws-${studentId}`);
        if(psEl)psEl.textContent=ps.toFixed(2);
        if(wsEl)wsEl.textContent=ws;
        calculateFinalGrade(studentId);
    }
    function calculateAllQE() {
        document.querySelectorAll('#qeTableBody tr').forEach(row=>{
            const id=row.getAttribute('data-student-id');
            if(id)calculateStudentQE(id);
        });
    }
    function updateQETotalItems(value) {
        document.getElementById('qeTotalItemsInput').value=value;
        calculateAllQE();
    }
    function calculateFinalGrade(studentId) {
        const wwEl=document.getElementById(`ww-ws-${studentId}`);
        const ptEl=document.getElementById(`pt-ws-${studentId}`);
        const qeEl=document.getElementById(`qe-ws-${studentId}`);
        const ww=wwEl?parseFloat(wwEl.textContent)||0:0;
        const pt=ptEl?parseFloat(ptEl.textContent)||0:0;
        const qe=qeEl?parseFloat(qeEl.textContent)||0:0;
        const initial=ww+pt+qe;
        const transmuted=transmuteGrade(initial);
        const remarks=getRemarks(transmuted);
        const fw=document.getElementById(`final-ww-${studentId}`);
        const fp=document.getElementById(`final-pt-${studentId}`);
        const fq=document.getElementById(`final-qe-${studentId}`);
        const ig=document.getElementById(`initial-grade-${studentId}`);
        const tg=document.getElementById(`transmuted-${studentId}`);
        const rm=document.getElementById(`remarks-${studentId}`);
        if(fw)fw.textContent=ww.toFixed(2);
        if(fp)fp.textContent=pt.toFixed(2);
        if(fq)fq.textContent=qe.toFixed(2);
        if(ig)ig.textContent=initial.toFixed(2);
        if(tg) {
            tg.textContent=transmuted.toFixed(0);
            tg.className='transmuted-grade text-lg font-bold ';
            if(transmuted>=90)tg.classList.add('text-emerald-600');
            else if(transmuted>=75)tg.classList.add('text-blue-600');
            else tg.classList.add('text-red-600');
        }
        if(rm) {
            rm.textContent=remarks.text;
            rm.className=`remarks px-2 py-0.5 rounded-full text-xs font-bold ${remarks.class}`;
        }
    }
    function calculateAllGrades() {
        document.querySelectorAll('#summaryTableBody tr').forEach(row=>{
            const id=row.getAttribute('data-student-id');
            if(id){calculateStudentWW(id);calculateStudentPT(id);calculateStudentQE(id);}
        });
    }
    function addWWColumn(title='',totalItems=100) {
        wwColCount++;
        const headerRow=document.getElementById('wwHeaderRow');
        const newTh=document.createElement('th');
        newTh.className='px-2 py-2.5 text-center font-semibold text-slate-700 ww-col-header';
        newTh.setAttribute('data-col',wwColCount);
        newTh.innerHTML=`<div class="mb-1 text-xs">WW ${wwColCount}</div><input type="text" name="ww_titles[]" class="ww-title w-14 text-center bg-transparent border-b border-slate-300 text-xs mb-1" placeholder="Title" value="${title}"><input type="number" name="ww_total_items[]" class="ww-total-item w-14 text-center bg-slate-50 border border-slate-200 rounded text-xs py-0.5" placeholder="Items" value="${totalItems}" min="1" onchange="calculateAllWW()">`;
        const totalTh=headerRow.querySelector('th:nth-last-child(3)');
        headerRow.insertBefore(newTh,totalTh);
        document.querySelectorAll('#wwTableBody tr').forEach(row=>{
            const studentId=row.getAttribute('data-student-id');
            const newTd=document.createElement('td');
            newTd.className='px-2 py-2.5 text-center ww-col';
            newTd.setAttribute('data-col',wwColCount);
            newTd.innerHTML=`<input type="number" name="ww[${studentId}][]" class="ww-score w-16 px-2 py-1.5 text-center rounded-md border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500/20 transition-all" min="0" onchange="calculateStudentWW(${studentId})">`;
            const totalTd=row.querySelector('td:nth-last-child(3)');
            row.insertBefore(newTd,totalTd);
        });
    }
    function removeLastWW() {
        if (wwColCount<=1) return;
        document.querySelectorAll(`#wwTable .ww-col-header[data-col="${wwColCount}"], #wwTable .ww-col[data-col="${wwColCount}"]`).forEach(el=>el.remove());
        wwColCount--;
        calculateAllWW();
    }
    function addPTColumn(title='',totalItems=100) {
        ptColCount++;
        const headerRow=document.getElementById('ptHeaderRow');
        const newTh=document.createElement('th');
        newTh.className='px-2 py-2.5 text-center font-semibold text-slate-700 pt-col-header';
        newTh.setAttribute('data-col',ptColCount);
        newTh.innerHTML=`<div class="mb-1 text-xs">PT ${ptColCount}</div><input type="text" name="pt_titles[]" class="pt-title w-14 text-center bg-transparent border-b border-slate-300 text-xs mb-1" placeholder="Title" value="${title}"><input type="number" name="pt_total_items[]" class="pt-total-item w-14 text-center bg-slate-50 border border-slate-200 rounded text-xs py-0.5" placeholder="Items" value="${totalItems}" min="1" onchange="calculateAllPT()">`;
        const totalTh=headerRow.querySelector('th:nth-last-child(3)');
        headerRow.insertBefore(newTh,totalTh);
        document.querySelectorAll('#ptTableBody tr').forEach(row=>{
            const studentId=row.getAttribute('data-student-id');
            const newTd=document.createElement('td');
            newTd.className='px-2 py-2.5 text-center pt-col';
            newTd.setAttribute('data-col',ptColCount);
            newTd.innerHTML=`<input type="number" name="pt[${studentId}][]" class="pt-score w-16 px-2 py-1.5 text-center rounded-md border border-slate-200 focus:border-purple-500 focus:ring-1 focus:ring-purple-500/20 transition-all" min="0" onchange="calculateStudentPT(${studentId})">`;
            const totalTd=row.querySelector('td:nth-last-child(3)');
            row.insertBefore(newTd,totalTd);
        });
    }
    function removeLastPT() {
        if (ptColCount<=1) return;
        document.querySelectorAll(`#ptTable .pt-col-header[data-col="${ptColCount}"], #ptTable .pt-col[data-col="${ptColCount}"]`).forEach(el=>el.remove());
        ptColCount--;
        calculateAllPT();
    }
    function populateExistingGrades() {
        if (!existingGrades||Object.keys(existingGrades).length===0) return;
        if (existingGrades['ww_titles']&&existingGrades['ww_titles'].length>3) {
            const titles=existingGrades['ww_titles'];
            const totalItems=existingGrades['ww_total_items']||[];
            for (let i=3;i<titles.length;i++) addWWColumn(titles[i],totalItems[i]||100);
        }
        if (existingGrades['ww_titles']) {
            const titleInputs=document.querySelectorAll('#wwHeaderRow .ww-title');
            const totalItemInputs=document.querySelectorAll('#wwHeaderRow .ww-total-item');
            existingGrades['ww_titles'].forEach((title,index)=>{
                if (titleInputs[index]) titleInputs[index].value=title;
                if (totalItemInputs[index]) totalItemInputs[index].value=existingGrades['ww_total_items']?.[index]||100;
            });
        }
        if (existingGrades['pt_titles']&&existingGrades['pt_titles'].length>2) {
            const titles=existingGrades['pt_titles'];
            const totalItems=existingGrades['pt_total_items']||[];
            for (let i=2;i<titles.length;i++) addPTColumn(titles[i],totalItems[i]||100);
        }
        if (existingGrades['pt_titles']) {
            const titleInputs=document.querySelectorAll('#ptHeaderRow .pt-title');
            const totalItemInputs=document.querySelectorAll('#ptHeaderRow .pt-total-item');
            existingGrades['pt_titles'].forEach((title,index)=>{
                if (titleInputs[index]) titleInputs[index].value=title;
                if (totalItemInputs[index]) totalItemInputs[index].value=existingGrades['pt_total_items']?.[index]||100;
            });
        }
        document.querySelectorAll('#wwTableBody tr').forEach(row=>{
            const studentId=row.getAttribute('data-student-id');
            const key=`${studentId}_written_work`;
            if (existingGrades[key]&&existingGrades[key].scores) {
                const scores=existingGrades[key].scores;
                const inputs=row.querySelectorAll('.ww-score');
                scores.forEach((score,index)=>{
                    if (inputs[index]) inputs[index].value=score;
                    else if (index>=inputs.length) {
                        while (inputs.length<=index) {
                            const titles=existingGrades['ww_titles']||[];
                            const totalItems=existingGrades['ww_total_items']||[];
                            addWWColumn(titles[inputs.length]||'',totalItems[inputs.length]||100);
                        }
                        const newInputs=row.querySelectorAll('.ww-score');
                        if (newInputs[index]) newInputs[index].value=score;
                    }
                });
            }
        });
        document.querySelectorAll('#ptTableBody tr').forEach(row=>{
            const studentId=row.getAttribute('data-student-id');
            const key=`${studentId}_performance_task`;
            if (existingGrades[key]&&existingGrades[key].scores) {
                const scores=existingGrades[key].scores;
                const inputs=row.querySelectorAll('.pt-score');
                scores.forEach((score,index)=>{
                    if (inputs[index]) inputs[index].value=score;
                    else if (index>=inputs.length) {
                        while (inputs.length<=index) {
                            const titles=existingGrades['pt_titles']||[];
                            const totalItems=existingGrades['pt_total_items']||[];
                            addPTColumn(titles[inputs.length]||'',totalItems[inputs.length]||100);
                        }
                        const newInputs=row.querySelectorAll('.pt-score');
                        if (newInputs[index]) newInputs[index].value=score;
                    }
                });
            }
        });
        document.querySelectorAll('#qeTableBody tr').forEach(row=>{
            const studentId=row.getAttribute('data-student-id');
            const key=`${studentId}_quarterly_exam`;
            if (existingGrades[key]) {
                const input=row.querySelector('.qe-score');
                if (input&&existingGrades[key].total_score) input.value=existingGrades[key].total_score;
            }
        });
        setTimeout(()=>calculateAllGrades(),100);
    }

    document.getElementById('quarterSelect')?.addEventListener('change',function(){
        document.getElementById('quarterInput').value=this.value;
        const url=new URL(window.location.href);
        url.searchParams.set('quarter',this.value);
        window.location.href=url.toString();
    });
    document.getElementById('subjectSelect')?.addEventListener('change',function(){
        const btn=document.getElementById('loadGradesBtn');
        if(btn)btn.disabled=!this.value;
    });

    document.addEventListener('DOMContentLoaded',function(){
        populateExistingGrades();
        calculateAllGrades();
        @if(session('success'))
            const msg='{{ session('success') }}';
            if(msg.toLowerCase().includes('finalized')) showFinalizeResultModal('success',msg);
            else showSaveModal('success',msg);
        @endif
        @if(session('error'))
            const err='{{ session('error') }}';
            if(err.toLowerCase().includes('finalized')||err.toLowerCase().includes('finalize')) showFinalizeResultModal('error',err);
            else showSaveModal('error',err);
        @endif
    });

    function showFinalizeGradesModal() {
        document.getElementById('finalizeGradesModal').classList.remove('hidden');
    }
    document.getElementById('finalizeGradesForm')?.addEventListener('submit',async function(e){
        e.preventDefault();
        document.getElementById('finalizeGradesModal').classList.add('hidden');
        document.getElementById('finalizingModal').classList.remove('hidden');
        const formData=new FormData(this);
        try {
            const resp=await fetch(this.action,{method:'POST',body:formData,headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content||''}});
            if(resp.status===419){document.getElementById('finalizingModal').classList.add('hidden');showFinalizeResultModal('error','Session expired. Please refresh the page and try again.');return;}
            const data=await resp.json().catch(()=>({success:resp.ok,message:resp.ok?'Grades finalized successfully!':'Failed to finalize grades'}));
            document.getElementById('finalizingModal').classList.add('hidden');
            if(data.success){showFinalizeResultModal('success',data.message||'Grades finalized successfully!');setTimeout(()=>window.location.reload(),2000);}
            else showFinalizeResultModal('error',data.message||'Failed to finalize grades');
        } catch(error) {
            document.getElementById('finalizingModal').classList.add('hidden');
            showFinalizeResultModal('error','Network error. Please try again.');
        }
    });
    document.getElementById('finalizeGradesModal')?.addEventListener('click',function(e){if(e.target===this)this.classList.add('hidden');});

    let modalAutoCloseTimer=null, modalCountdownInterval=null;
    function showSaveModal(type,message){
        const modal=document.getElementById('saveGradesModal');
        const success=document.getElementById('saveModalSuccess');
        const error=document.getElementById('saveModalError');
        const content=document.getElementById('saveModalContent');
        const progressFill=document.getElementById('modalProgressFill');
        clearTimeout(modalAutoCloseTimer);clearInterval(modalCountdownInterval);
        const successIcon=document.getElementById('successIcon');
        const errorIcon=document.getElementById('errorIcon');
        if(successIcon)successIcon.classList.remove('icon-exit');
        if(errorIcon)errorIcon.classList.remove('icon-exit');
        if(progressFill){progressFill.style.transition='none';progressFill.style.width='100%';progressFill.className='h-full w-full';}
        modal.classList.remove('hidden');
        success.classList.add('hidden');error.classList.add('hidden');
        const countdownEl=type==='success'?document.getElementById('successCountdown'):document.getElementById('errorCountdown');
        let countdown=3;
        if(countdownEl)countdownEl.textContent=countdown;
        if(type==='success'){
            success.classList.remove('hidden');
            if(progressFill)progressFill.classList.add('bg-emerald-500');
            if(message)document.getElementById('successMessage').textContent=message;
            setTimeout(()=>{if(progressFill){progressFill.style.transition='width 3s linear';progressFill.style.width='0%';}},50);
        } else {
            error.classList.remove('hidden');
            if(progressFill)progressFill.classList.add('bg-red-500');
            content.classList.add('shake-animation');
            setTimeout(()=>content.classList.remove('shake-animation'),500);
            if(message)document.getElementById('errorMessage').textContent=message;
            setTimeout(()=>{if(progressFill){progressFill.style.transition='width 3s linear';progressFill.style.width='0%';}},50);
        }
        modalCountdownInterval=setInterval(()=>{countdown--;if(countdownEl)countdownEl.textContent=countdown;if(countdown<=0)clearInterval(modalCountdownInterval);},1000);
        modalAutoCloseTimer=setTimeout(()=>closeSaveModal(type),3000);
    }
    function closeSaveModal(type){
        clearTimeout(modalAutoCloseTimer);clearInterval(modalCountdownInterval);
        const iconId=type==='success'?'successIcon':(type==='error'?'errorIcon':null);
        if(iconId){const icon=document.getElementById(iconId);if(icon){icon.classList.add('icon-exit');setTimeout(()=>document.getElementById('saveGradesModal').classList.add('hidden'),300);return;}}
        document.getElementById('saveGradesModal').classList.add('hidden');
    }

    let finalizeModalAutoCloseTimer=null, finalizeModalCountdownInterval=null;
    function showFinalizeResultModal(type,message){
        const modal=document.getElementById('finalizeResultModal');
        const success=document.getElementById('finalizeResultSuccess');
        const error=document.getElementById('finalizeResultError');
        const content=document.getElementById('finalizeResultContent');
        clearTimeout(finalizeModalAutoCloseTimer);clearInterval(finalizeModalCountdownInterval);
        modal.classList.remove('hidden');
        success.classList.add('hidden');error.classList.add('hidden');
        const progressFill=document.getElementById('finalizeProgressFill');
        progressFill.className='h-full w-full';
        let countdown=3;
        const countdownEl=type==='success'?document.getElementById('finalizeSuccessCountdown'):document.getElementById('finalizeErrorCountdown');
        if(type==='success'){
            success.classList.remove('hidden');
            if(message)document.getElementById('finalizeSuccessMessage').textContent=message;
            progressFill.classList.add('bg-emerald-500');
        } else {
            error.classList.remove('hidden');
            content.classList.add('shake-animation');
            setTimeout(()=>content.classList.remove('shake-animation'),500);
            if(message)document.getElementById('finalizeErrorMessage').textContent=message;
            progressFill.classList.add('bg-red-500');
        }
        setTimeout(()=>{progressFill.style.transition='width 3s linear';progressFill.style.width='0%';},50);
        countdownEl.textContent=countdown;
        finalizeModalCountdownInterval=setInterval(()=>{countdown--;countdownEl.textContent=countdown;if(countdown<=0)clearInterval(finalizeModalCountdownInterval);},1000);
        finalizeModalAutoCloseTimer=setTimeout(()=>closeFinalizeResultModal(),3000);
    }
    function closeFinalizeResultModal(){
        clearTimeout(finalizeModalAutoCloseTimer);clearInterval(finalizeModalCountdownInterval);
        document.getElementById('finalizeResultModal').classList.add('hidden');
    }
</script>

<style>
input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}
@keyframes shake-animation{0%,100%{transform:translateX(0)}25%{transform:translateX(-10px)}75%{transform:translateX(10px)}}
.shake-animation{animation:shake-animation .5s ease-in-out}
@keyframes icon-exit-success{0%{transform:scale(1);opacity:1}50%{transform:scale(1.2);opacity:1}100%{transform:scale(0) rotate(360deg);opacity:0}}
.icon-exit{animation:icon-exit-success .3s ease-in forwards}
#errorIcon.icon-exit{animation:shake-animation .3s ease-in forwards}
</style>

</body>
</html>
