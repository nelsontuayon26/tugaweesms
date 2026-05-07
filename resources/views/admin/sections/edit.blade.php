<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Section | Tugawe Elementary</title>
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
            background: linear-gradient(90deg, #f59e0b, #d97706);
            width: 100%;
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
            color: #f59e0b;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            font-weight: 500;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 20px;
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
            color: #f59e0b;
        }

        .btn-primary {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 16px;
            font-weight: 700;
            font-size: 15px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px -3px rgba(245, 158, 11, 0.4);
            position: relative;
            overflow-x: hidden;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px -5px rgba(245, 158, 11, 0.5);
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 10px 20px -5px rgba(245, 158, 11, 0.3);
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
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

        .input-hint {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
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

        .current-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            background: #f1f5f9;
            border-radius: 6px;
            font-size: 10px;
            color: #64748b;
            font-weight: 600;
            margin-left: 6px;
            text-transform: uppercase;
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
        <div class="progress-fill"></div>
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
                                    <i class="fas fa-edit text-2xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-3xl font-bold">Edit Section</h1>
                                    <p class="text-amber-100 mt-1">Update {{ $section->name }} information</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-amber-100 mt-6">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-history"></i>
                                    Created {{ $section->created_at->diffForHumans() }}
                                </span>
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-users"></i>
                                    {{ $section->students->count() }} students enrolled
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

                    <form action="{{ route('admin.sections.update', $section) }}" method="POST" class="space-y-6" id="sectionForm">
                        @csrf
                        @method('PUT')
                        
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
                                    <label class="form-label">
                                        Section Name
                                        <span class="current-badge">
                                            <i class="fas fa-tag"></i>
                                            {{ $section->name }}
                                        </span>
                                    </label>
                                    <i class="fas fa-th-large input-icon"></i>
                                    <input type="text" name="name" required 
                                           value="{{ old('name', $section->name) }}"
                                           class="form-input">
                                    @error('name')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                               <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <div class="input-group">
                                        <label class="form-label">
                                            Grade Level
                                            <span class="current-badge">
                                                <i class="fas fa-graduation-cap"></i>
                                                {{ $section->gradeLevel->name ?? 'None' }}
                                            </span>
                                        </label>
                                        <i class="fas fa-graduation-cap input-icon"></i>
                                        <select name="grade_level_id" required class="form-select">
                                            <option value="">Select Grade Level</option>
                                            @foreach($gradeLevels as $grade)
                                                <option value="{{ $grade->id }}" {{ old('grade_level_id', $section->grade_level_id) == $grade->id ? 'selected' : '' }}>
                                                    {{ $grade->name }} (Grade {{ $grade->level }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('grade_level_id')
                                            <p class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="input-group">
                                        <label class="form-label">
                                            School Year
                                            <span class="current-badge">
                                                <i class="fas fa-calendar"></i>
                                                {{ $section->schoolYear->name ?? 'None' }}
                                            </span>
                                        </label>
                                        <i class="fas fa-calendar-alt input-icon"></i>
                                        <select name="school_year_id" class="form-select">
                                            <option value="">Select School Year</option>
                                            @foreach($schoolYears as $year)
                                                <option value="{{ $year->id }}" {{ old('school_year_id', $section->school_year_id) == $year->id ? 'selected' : '' }}>
                                                    {{ $year->name }} {{ $year->is_active ? '(Active)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('school_year_id')
                                            <p class="error-message"><i class="fas fa-exclamation-triangle"></i> {{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-2 bg-gradient-to-br from-amber-50/50 to-orange-50/50 border-amber-200/50">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Adviser Assignment</h3>
                                    <p class="section-subtitle">Change or assign a new teacher</p>
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="form-label">
                                    Adviser (Teacher)
                                    <span class="current-badge">
                                        <i class="fas fa-user-tie"></i>
                                        {{ $section->teacher->full_name ?? 'None' }}
                                    </span>
                                </label>
                                <i class="fas fa-chalkboard-teacher input-icon"></i>
                                <select name="teacher_id" class="form-select">
                                    <option value="">No Adviser Assigned</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $section->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <p class="error-message">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Capacity & Room Section -->
                        <div class="glass-card p-8 rounded-3xl animate-fade-in stagger-3">
                            <div class="section-header">
                                <div class="section-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                    <i class="fas fa-cog"></i>
                                </div>
                                <div>
                                    <h3 class="section-title">Capacity & Location</h3>
                                    <p class="section-subtitle">Room and student limit settings</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="input-group">
                                    <label class="form-label">
                                        Room Number
                                        <span class="current-badge">
                                            <i class="fas fa-door-open"></i>
                                            {{ $section->room_number ?? 'None' }}
                                        </span>
                                    </label>
                                    <i class="fas fa-door-open input-icon"></i>
                                    <input type="text" name="room_number" 
                                           value="{{ old('room_number', $section->room_number) }}"
                                           class="form-input">
                                    @error('room_number')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div class="input-group">
                                    <label class="form-label">
                                        Student Capacity
                                        <span class="current-badge">
                                            <i class="fas fa-users"></i>
                                            {{ $section->capacity ?? 'None' }}
                                        </span>
                                    </label>
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="number" name="capacity" 
                                           value="{{ old('capacity', $section->capacity) }}"
                                           class="form-input"
                                           min="1" max="60">
                                    @error('capacity')
                                        <p class="error-message">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sticky Footer -->
                        <div class="sticky-footer">
                           <div class="max-w-6xl mx-auto flex items-center justify-between">
                                <div class="hidden md:flex items-center gap-3 text-sm text-slate-500">
                                    <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                                    <span>Editing {{ $section->name }}</span>
                                </div>
                                <div class="flex gap-3 ml-auto">
                                    <a href="{{ route('admin.sections.index') }}" class="btn-secondary">                                        Cancel
                                    </a>
                                    <button type="submit" class="btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i>
                                        Update Section
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
        // Form submission
        document.getElementById('sectionForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            btn.disabled = true;
        });
    </script>

</body>
</html>