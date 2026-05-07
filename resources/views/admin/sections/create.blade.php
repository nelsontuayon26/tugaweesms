<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Section | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { 
            margin: 0; 
            padding: 0; 
            background: #f8fafc;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow-x: hidden;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
             padding: 32px 48px; 
            background: #f8fafc;
        }

        .main-content::-webkit-scrollbar { 
            width: 8px; 
        }
        .main-content::-webkit-scrollbar-track { 
            background: transparent; 
        }
        .main-content::-webkit-scrollbar-thumb { 
            background: #cbd5e1; 
            border-radius: 4px; 
        }

        @media (max-width: 1024px) {
            .main-wrapper { margin-left: 0; }
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            border-radius: 24px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card:hover {
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
        }

        .progress-bar {
            position: fixed;
            top: 0;
            left: 280px;
            right: 0;
            height: 4px;
            background: #e2e8f0;
            z-index: 100;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            width: 0%;
            transition: width 0.3s ease;
        }

        .input-group {
            position: relative;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            transition: all 0.3s ease;
            pointer-events: none;
        }

        .input-group:focus-within .input-icon {
            color: #10b981;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            font-weight: 500;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            transition: all 0.3s ease;
        }

        .input-group:focus-within .form-label {
            color: #10b981;
        }

        .form-select {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            font-weight: 500;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 20px;
        }

        .form-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(16, 185, 129, 0.4);
            position: relative;
            overflow-x: hidden;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(16, 185, 129, 0.5);
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 600;
            font-size: 15px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            color: #475569;
            border-color: #cbd5e1;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f1f5f9;
        }

        .section-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3);
        }

        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
        }

        .section-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px;
        }

        .glass-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px;
            border-radius: 24px;
            margin-bottom: 32px;
            position: relative;
            overflow-x: hidden;
        }

        .glass-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(60px);
        }

        .glass-header::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            filter: blur(40px);
        }

        .input-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .input-hint.valid {
            color: #10b981;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-weight: 500;
        }

        .sticky-footer {
            position: fixed;
            bottom: 0;
            left: 280px;
            right: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-top: 1px solid #e2e8f0;
            padding: 20px 32px;
            z-index: 50;
        }

        @media (max-width: 1024px) {
            .sticky-footer { left: 0; }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }

        .info-card {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .teacher-preview {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8fafc;
            border-radius: 12px;
            margin-top: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .teacher-preview.active {
            background: #ecfdf5;
            border-color: #10b981;
        }

        .teacher-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="antialiased text-slate-800" x-data="{ mobileOpen: false }" @keydown.escape.window="mobileOpen = false">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="mobileOpen = false"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Progress Bar -->
    <div class="progress-bar">
        <div class="progress-fill" id="progressBar"></div>
    </div>

    <div class="dashboard-container">
        @include('admin.includes.sidebar')

        <div class="main-wrapper">
            <div class="main-content">
               <div class="max-w-6xl mx-auto pb-32">
                    
                    <!-- Enhanced Header -->
                    <div class="glass-header animate-fade-in">
                        <div class="relative z-10">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-th-large text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold">Create New Section</h1>
                                    <p class="text-emerald-100 mt-1">Set up a new class section with grade level and adviser</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-emerald-100 mt-6">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    Auto-assigns students by grade
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-clock"></i>
                                    Takes less than 1 minute
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(session('error'))
                        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-r-xl mb-6 animate-fade-in flex items-start gap-3">
                            <i class="fas fa-exclamation-circle mt-1"></i>
                            <div>
                                <p class="font-semibold">Error</p>
                                <p class="text-sm">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Info Card -->
                    <div class="info-card animate-fade-in stagger-1">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-lightbulb text-emerald-600 mt-1"></i>
                            <div>
                                <p class="font-semibold text-emerald-900 text-sm">Quick Tip</p>
                                <p class="text-emerald-700 text-sm mt-1">Sections are grouped by grade level. Each section should have a unique name (e.g., "A", "B", "Rose") within the same grade.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.sections.store') }}" method="POST" class="space-y-6" id="sectionForm">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-1">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Basic Information</h3>
                                    <p class="section-subtitle">Section name and identification</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="input-group">
                                    <label class="form-label">Section Name <span class="text-red-500">*</span></label>
                                    <i class="fas fa-th-large input-icon"></i>
                                    <input type="text" name="name" required 
                                           value="{{ old('name') }}"
                                           class="form-input" 
                                           placeholder="e.g., Rose, A, B, Orchid"
                                           autocomplete="off"
                                           id="sectionName">
                                    <p class="input-hint">
                                        <i class="fas fa-tag"></i>
                                        Unique identifier for this section
                                    </p>
                                    @error('name')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="input-group">
                                        <label class="form-label">Grade Level <span class="text-red-500">*</span></label>
                                        <i class="fas fa-graduation-cap input-icon"></i>
                                        <select name="grade_level_id" required class="form-select" id="gradeLevel">
                                            <option value="">Select Grade Level</option>
                                            @foreach($gradeLevels as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_level_id') == $grade->id ? 'selected' : '' }}>
                                                    {{ $grade->name }} (Grade {{ $grade->level }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('grade_level_id')
                                            <p class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="input-group">
                                        <label class="form-label">School Year</label>
                                        <i class="fas fa-calendar-alt input-icon"></i>
                                        <select name="school_year_id" class="form-select" id="schoolYear">
                                            <option value="">Auto (Active Year)</option>
                                            @foreach($schoolYears as $year)
                                                <option value="{{ $year->id }}" 
                                                    {{ old('school_year_id', $activeSchoolYear?->id) == $year->id ? 'selected' : '' }}
                                                    {{ $year->is_active ? 'data-active="true"' : '' }}>
                                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="input-hint" id="schoolYearHint">
                                            <i class="fas fa-star"></i>
                                            <span class="{{ $activeSchoolYear ? 'valid' : '' }}">
                                                {{ $activeSchoolYear ? 'Default: ' . $activeSchoolYear->name : 'No active school year set' }}
                                            </span>
                                        </p>
                                        @error('school_year_id')
                                            <p class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-2 bg-gradient-to-br from-emerald-50/50 to-teal-50/50 border-emerald-200/50">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Adviser Assignment</h3>
                                    <p class="section-subtitle">Assign a teacher to manage this section</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="input-group">
                                    <label class="form-label">Adviser (Teacher)</label>
                                    <i class="fas fa-chalkboard-teacher input-icon"></i>
                                 <select name="teacher_id" class="form-select" id="teacherSelect">
    <option value="">No Adviser Assigned</option>
    @foreach($teachers as $teacher)
        <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
            {{ $teacher->full_name }} 
            @if($teacher->sections->count())
                ({{ $teacher->sections->count() }} section{{ $teacher->sections->count() > 1 ? 's' : '' }})
            @endif
        </option>
    @endforeach
</select>
                                    <p class="input-hint" id="teacherHint">
                                        <i class="fas fa-info-circle"></i>
                                        Adviser selection.
                                    </p>
                                    @error('teacher_id')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Teacher Preview Card (shows when selected) -->
                                <div class="teacher-preview hidden" id="teacherPreview">
                                    <div class="teacher-avatar" id="teacherInitials">T</div>
                                    <div>
                                        <p class="font-semibold text-slate-900" id="teacherName">Teacher Name</p>
                                        <p class="text-xs text-slate-500">Will be assigned as adviser</p>
                                    </div>
                                    <i class="fas fa-check-circle text-emerald-500 ml-auto"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Capacity & Room Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-3">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Capacity & Location</h3>
                                    <p class="section-subtitle">Room assignment and student limits</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="input-group">
                                    <label class="form-label">Room Number</label>
                                    <i class="fas fa-door-open input-icon"></i>
                                    <input type="text" name="room_number" 
                                           value="{{ old('room_number') }}"
                                           class="form-input" 
                                           placeholder="e.g., 101, A-12"
                                           id="roomNumber">
                                    <p class="input-hint">
                                        <i class="fas fa-map-marker-alt"></i>
                                        Physical classroom location
                                    </p>
                                    @error('room_number')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="input-group">
                                    <label class="form-label">Student Capacity</label>
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="number" name="capacity" 
                                           value="{{ old('capacity', 40) }}"
                                           class="form-input" 
                                           placeholder="40"
                                           min="1"
                                           max="60"
                                           id="capacity">
                                    <p class="input-hint">
                                        <i class="fas fa-chart-bar"></i>
                                        Maximum students allowed (default: 40)
                                    </p>
                                    @error('capacity')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Summary Card -->
                        <div class="glass-card p-6 rounded-2xl animate-fade-in stagger-3 bg-slate-50/50 border-slate-200/50">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-clipboard-check text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 mb-1">Before You Create</h4>
                                    <p class="text-sm text-slate-600 leading-relaxed">
                                        Once created, students can be enrolled to this section based on the selected grade level. The adviser will gain access to manage attendance, grades, and student records.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="sticky-footer">
                            <div class="max-w-6xl mx-auto flex items-center justify-between">
                                <div class="hidden md:flex items-center gap-3 text-sm text-slate-500">
                                    <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span id="formStatus">Ready to create</span>
                                </div>
                                <div class="flex gap-3 ml-auto">
                                    <a href="{{ route('admin.sections.index') }}" class="btn-secondary">                                        Cancel
                                    </a>
                                    <button type="submit" class="btn-primary" id="submitBtn">
                                        <i class="fas fa-plus"></i>
                                        Create Section
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Progress bar
        const form = document.getElementById('sectionForm');
        const progressBar = document.getElementById('progressBar');
        const inputs = form.querySelectorAll('input[required], select[required]');
        
        function updateProgress() {
            const filled = Array.from(inputs).filter(input => input.value.trim() !== '').length;
            const percent = (filled / inputs.length) * 100;
            progressBar.style.width = percent + '%';
            
            // Update status text
            const statusEl = document.getElementById('formStatus');
            if (percent === 0) statusEl.textContent = 'Ready to create';
            else if (percent < 50) statusEl.textContent = 'Keep going...';
            else if (percent < 100) statusEl.textContent = 'Almost there!';
            else statusEl.textContent = 'Ready to submit';
        }
        
        inputs.forEach(input => {
            input.addEventListener('input', updateProgress);
            input.addEventListener('change', updateProgress);
        });

        // Teacher preview
        const teacherSelect = document.getElementById('teacherSelect');
        const teacherPreview = document.getElementById('teacherPreview');
        const teacherName = document.getElementById('teacherName');
        const teacherInitials = document.getElementById('teacherInitials');
        const teacherHint = document.getElementById('teacherHint');

        teacherSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const name = selectedOption.getAttribute('data-name');
            
            if (this.value && name) {
                teacherPreview.classList.remove('hidden');
                teacherPreview.classList.add('active');
                teacherName.textContent = name;
                teacherInitials.textContent = name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                teacherHint.innerHTML = '<i class="fas fa-check-circle"></i> <span class="valid">Adviser selected</span>';
                teacherHint.classList.add('valid');
            } else {
                teacherPreview.classList.add('hidden');
                teacherPreview.classList.remove('active');
                teacherHint.innerHTML = '<i class="fas fa-info-circle"></i> Only teachers without an advisory section in the active school year are shown';
                teacherHint.classList.remove('valid');
            }
            updateProgress();
        });

        // Auto-format school year
        const schoolYearInput = document.getElementById('schoolYear');
        schoolYearInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^0-9-]/g, '');
            if (value.length === 4 && !value.includes('-')) {
                const nextYear = parseInt(value) + 1;
                e.target.value = value + '-' + nextYear;
            }
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            btn.disabled = true;
        });

        // Initialize
        updateProgress();
        
        // Trigger teacher preview if old value exists
        if (teacherSelect.value) {
            teacherSelect.dispatchEvent(new Event('change'));
        }
    </script>

</body>
</html>