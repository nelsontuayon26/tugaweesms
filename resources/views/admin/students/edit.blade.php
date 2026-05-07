<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | Tugawe Elementary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
            background: #f8fafc;
        }

        .dashboard-layout {
            display: flex;
            height: 100vh;
            width: 100vw;
        }

        .sidebar-container {
            width: 280px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 50;
            flex-shrink: 0;
        }

        .main-wrapper {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            background: #f8fafc;
        }

        .main-header {
            height: 80px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 32px;
            flex-shrink: 0;
            z-index: 40;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 32px;
            padding-bottom: 100px;
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
            .sidebar-container {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar-container.open {
                transform: translateX(0);
            }
            .main-wrapper {
                margin-left: 0;
            }
        }

        .glass-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .form-section {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            color: #64748b;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background: white;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 20px;
            padding-right: 40px;
        }

        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.125rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.125rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 14px 0 rgba(59, 130, 246, 0.39);
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.23);
        }

        .btn-secondary {
            background: white;
            color: #64748b;
            padding: 14px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* Colored back button with gradient */
        .btn-back-colored {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            font-size: 1.25rem;
        }

        .btn-back-colored:hover {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        }

        .alert-success {
            background: #dcfce7;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .mobile-overlay {
            background: rgba(15, 23, 42, 0.3);
            backdrop-filter: blur(4px);
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .animate-fade-in-up { 
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
        }

        .input-with-icon {
            padding-left: 44px;
        }

        .required::after {
            content: '*';
            color: #ef4444;
            margin-left: 4px;
        }

        .input-hint {
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 4px;
        }

        .lrn-prefix {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #3b82f6;
            font-weight: 600;
            font-size: 0.875rem;
            pointer-events: none;
        }

        .input-with-prefix {
            padding-left: 70px;
        }

   /* Floating action buttons container */
.floating-actions {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 100;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

        /* Student info display */
        .student-info-card {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 24px;
        }

        .student-info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .student-info-item:last-child {
            margin-bottom: 0;
        }

        .student-info-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #0369a1;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            min-width: 70px;
        }

        .student-info-value {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0c4a6e;
        }

        /* Tooltip for disabled email */
        .tooltip-container {
            position: relative;
            display: block;
        }

        .tooltip-container:hover .custom-tooltip {
            visibility: visible;
            opacity: 1;
        }

        .custom-tooltip {
            visibility: hidden;
            opacity: 0;
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            background: #1e293b;
            color: white;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            transition: opacity 0.3s;
            margin-bottom: 8px;
            z-index: 10;
        }

        .custom-tooltip::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: #1e293b transparent transparent transparent;
        }

        .email-disabled {
            background: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #64748b !important;
            cursor: not-allowed !important;
        }
    </style>
</head>
<body class="text-slate-800">

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

    <div class="dashboard-layout">
        <!-- Fixed Sidebar -->
        <div class="sidebar-container">
            @include('admin.includes.sidebar')
        </div>

        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Fixed Header -->
            <header class="main-header">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-4">
                        <button type="button" @click="mobileOpen = !mobileOpen" class="lg:hidden p-2.5 hover:bg-slate-100 rounded-xl transition-colors">
                            <i class="fas fa-bars text-slate-600"></i>
                        </button>
                        <div>
                            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Student</h2>
                            <p class="text-sm text-slate-500 font-medium flex items-center gap-2 mt-0.5">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Update student information
                            </p>
                        </div>
                    </div>
                    
                   
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="main-content">
                
                @if(session('success'))
                    <div class="alert-success animate-fade-in-up" id="successAlert">
                        <i class="fas fa-check-circle text-xl"></i>
                        <span class="font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Current Student Info Display (from users table via relationship) -->
                <div class="student-info-card animate-fade-in-up">
                    <div class="flex items-center gap-2 mb-3">
                        <i class="fas fa-id-card text-blue-500"></i>
                        <span class="text-sm font-bold text-sky-700">Current Student Information</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="student-info-item">
                            <span class="student-info-label">Name:</span>
                            <span class="student-info-value">{{ $student->user->first_name }} {{ $student->user->last_name }}</span>
                        </div>
                        <div class="student-info-item">
                            <span class="student-info-label">Email:</span>
                            <span class="student-info-value">{{ $student->user->email }}</span>
                        </div>
                        <div class="student-info-item">
                            <span class="student-info-label">Username:</span>
                            <span class="student-info-value">{{ $student->user->username ?? 'Not set' }}</span>
                        </div>
                        <!-- Birthdate from students table -->
                        <div class="student-info-item">
                            <span class="student-info-label">Birthdate:</span>
                            <span class="student-info-value">{{ $student->birthdate ? $student->birthdate->format('M d, Y') : 'Not set' }}</span>
                        </div>
                    </div>
                </div>

               <form action="{{ route('admin.students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="glass-card p-8 animate-fade-in-up" id="studentForm">
                    @csrf
                    @method('PUT')
                    
                    <!-- Hidden field for user_id -->
                    <input type="hidden" name="user_id" value="{{ $student->user_id }}">

                    <!-- Basic Information Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-blue-50 text-blue-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <span>Basic Information (User Account)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- First Name (from users table) -->
                            <div>
                                <label class="form-label required">First Name</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="first_name" class="form-input input-with-icon" placeholder="First name" required value="{{ old('first_name', $student->user->first_name) }}">
                                </div>
                                @error('first_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Middle Name (from users table) -->
<div>
    <label class="form-label">Middle Name</label>
    <div class="input-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" 
               name="middle_name" 
               class="form-input input-with-icon" 
               placeholder="Middle name (optional)" 
               value="{{ old('middle_name', $student->user->middle_name) }}">
    </div>
    @error('middle_name')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

                            

                            <!-- Last Name (from users table) -->
                            <div>
                                <label class="form-label required">Last Name</label>
                                <div class="input-group">
                                    <i class="fas fa-user input-icon"></i>
                                    <input type="text" name="last_name" class="form-input input-with-icon" placeholder="Last name" required value="{{ old('last_name', $student->user->last_name) }}">
                                </div>
                                @error('last_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Suffix -->
                            <div>
                                <label class="form-label">Suffix</label>
                                <select name="suffix" class="form-select">
                                    <option value="" {{ old('suffix', $student->user->suffix) == '' ? 'selected' : '' }}>None</option>
                                    <option value="Jr." {{ old('suffix', $student->user->suffix) == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('suffix', $student->user->suffix) == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('suffix', $student->user->suffix) == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('suffix', $student->user->suffix) == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ old('suffix', $student->user->suffix) == 'IV' ? 'selected' : '' }}>IV</option>
                                </select>
                                @error('suffix')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Username (from users table) -->
                            <div>
                                <label class="form-label required">Username</label>
                                <div class="input-group">
                                    <i class="fas fa-at input-icon"></i>
                                    <input type="text" name="username" class="form-input input-with-icon" placeholder="Username" required value="{{ old('username', $student->user->username) }}">
                                </div>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email (from users table) - UNEDITABLE with tooltip -->
                            <div class="md:col-span-2">
                                <label class="form-label">Email <span class="text-slate-400 font-normal">(Not Editable)</span></label>
                                <div class="tooltip-container">
                                    <div class="input-group">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input 
                                            type="email" 
                                            name="email" 
                                            class="form-input input-with-icon email-disabled" 
                                            value="{{ $student->user->email }}"
                                            disabled
                                            readonly
                                            title="Email Not Editable"
                                        >
                                    </div>
                                    <div class="custom-tooltip">Email Not Editable</div>
                                </div>
                                <p class="input-hint">Contact administrator to change email address</p>
                                <!-- Hidden input to preserve email value on form submit -->
                                <input type="hidden" name="email" value="{{ $student->user->email }}">
                            </div>

                            <!-- Password (Optional - leave blank to keep current) -->
<div class="md:col-span-2">
    <label class="form-label">New Password <span class="text-slate-400 font-normal">(Leave blank to keep current)</span></label>
    <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input 
            type="password" 
            name="password" 
            id="passwordInput"
            class="form-input input-with-icon" 
            placeholder="Enter new password"
            minlength="8"
        >
        <button type="button" onclick="togglePassword('passwordInput', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
            <i class="fas fa-eye"></i>
        </button>
    </div>
    <p class="input-hint">Minimum 8 characters</p>
    @error('password')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

<!-- Confirm Password -->
<div class="md:col-span-2">
    <label class="form-label">Confirm New Password</label>
    <div class="input-group">
        <i class="fas fa-lock input-icon"></i>
        <input 
            type="password" 
            name="password_confirmation" 
            id="confirmPasswordInput"
            class="form-input input-with-icon" 
            placeholder="Confirm new password"
            oninput="validateMatch()"
        >
        <button type="button" onclick="togglePassword('confirmPasswordInput', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
            <i class="fas fa-eye"></i>
        </button>
    </div>
    <p class="input-hint" id="matchHint">Leave both blank to keep current password</p>
    @error('password_confirmation')
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>

                            <!-- LRN -->
                            <div>
                                <label class="form-label">LRN (Learner Reference Number)</label>
                                <div class="input-group">
                                    <span class="lrn-prefix">120231</span>
                                    <input 
                                        type="text" 
                                        name="lrn_suffix" 
                                        id="lrnInput"
                                        class="form-input input-with-prefix" 
                                        placeholder="XXXXXX"
                                        maxlength="6"
                                        pattern="[0-9]{6}"
                                        inputmode="numeric"
                                        value="{{ old('lrn_suffix', $student->lrn ? substr($student->lrn, 6) : '') }}"
                                    >
                                </div>
                                <p class="input-hint">Enter last 6 digits only (12 digits total)</p>
                                @error('lrn_suffix')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birthdate -->
                            <div>
                                <label class="form-label required">Birthdate</label>
                                <div class="input-group">
                                    <i class="fas fa-calendar input-icon"></i>
                                    <input 
                                        type="date" 
                                        name="birthday" 
                                        class="form-input input-with-icon"
                                        min="1900-01-01"
                                        max="{{ date('Y-m-d') }}"
                                        value="{{ old('birthday', $student->birthdate ? $student->birthdate->format('Y-m-d') : '') }}"
                                        required
                                    >
                                </div>
                                @error('birthday')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Birth Place -->
                            <div>
                                <label class="form-label required">Birth Place</label>
                                <div class="input-group">
                                    <i class="fas fa-map-marker-alt input-icon"></i>
                                    <input type="text" name="birth_place" class="form-input input-with-icon" placeholder="City, Province" value="{{ old('birth_place', $student->birth_place) }}" required>
                                </div>
                                @error('birth_place')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div>
                                <label class="form-label required">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender', $student->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nationality -->
                            <div>
                                <label class="form-label required">Nationality</label>
                                <div class="input-group">
                                    <i class="fas fa-globe input-icon"></i>
                                    <input type="text" name="nationality" class="form-input input-with-icon" placeholder="e.g., Filipino" value="{{ old('nationality', $student->nationality) }}" required>
                                </div>
                                @error('nationality')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Religion -->
                            <div>
                                <label class="form-label required">Religion</label>
                                <div class="input-group">
                                    <i class="fas fa-praying-hands input-icon"></i>
                                    <input type="text" name="religion" class="form-input input-with-icon" placeholder="e.g., Roman Catholic" value="{{ old('religion', $student->religion) }}" required>
                                </div>
                                @error('religion')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Ethnicity -->
                            <div>
                                <label class="form-label required">Ethnicity</label>
                                <div class="input-group">
                                    <i class="fas fa-users input-icon"></i>
                                    <input type="text" name="ethnicity" class="form-input input-with-icon" placeholder="e.g., Tagalog, Cebuano, Ilocano" value="{{ old('ethnicity', $student->ethnicity) }}" required>
                                </div>
                                @error('ethnicity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mother Tongue -->
                            <div>
                                <label class="form-label required">Mother Tongue</label>
                                <div class="input-group">
                                    <i class="fas fa-language input-icon"></i>
                                    <input type="text" name="mother_tongue" class="form-input input-with-icon" placeholder="e.g., Tagalog, Cebuano, English" value="{{ old('mother_tongue', $student->mother_tongue) }}" required>
                                </div>
                                @error('mother_tongue')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div> 


                          <!-- ✅ REMARKS SECTION (NEW) -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-amber-50 text-amber-600">
                                <i class="fas fa-sticky-note"></i>
                            </div>
                            <span>Enrollment Remarks</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Remarks Code</label>
                                <select name="remarks" class="form-select">
                                    <option value="">-- Select Remark --</option>
                                    @foreach(\App\Models\Student::$remarksLegend as $code => $label)
                                        <option value="{{ $code }}" {{ old('remarks', $student->remarks) == $code ? 'selected' : '' }}>
                                            {{ $code }} - {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="input-hint">Select if applicable (e.g., Transferred In, Late Enrollee)</p>
                                @error('remarks')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Information Section -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-rose-50 text-rose-600">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span>Enrollment Information</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Grade Level -->
                            <div>
                                <label class="form-label required">Grade Level</label>
                                <select name="grade_level_id" id="gradeLevel" class="form-select" required onchange="updateSections()">
                                    <option value="">Select Grade Level</option>
                                    @foreach($gradeLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('grade_level_id', $student->grade_level_id) == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <p class="input-hint">Select current grade level for enrollment</p>
                                @error('grade_level_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Section -->
                            <div>
                                <label class="form-label required">Section</label>
                                <select name="section_id" id="sectionId" class="form-select" required>
                                    <option value="">Select Section</option>
                                </select>
                                <p class="input-hint">Select section for enrollment</p>
                                @error('section_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Student Type -->
                            <div>
                                <label class="form-label required">Student Type</label>
                                <select name="type" id="studentType" class="form-select" required onchange="togglePreviousSchool()">
                                    <option value="">Select Type</option>
                                    <option value="new" {{ old('type', $activeEnrollment?->type) == 'new' ? 'selected' : '' }}>New Student</option>
                                    <option value="continuing" {{ old('type', $activeEnrollment?->type) == 'continuing' ? 'selected' : '' }}>Continuing Student</option>
                                    <option value="transferee" {{ old('type', $activeEnrollment?->type) == 'transferee' ? 'selected' : '' }}>Transferee</option>
                                </select>
                                <p class="input-hint">Select based on student's enrollment status</p>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Previous School (for Transferees) -->
                            <div id="previousSchoolContainer" class="hidden">
                                <label class="form-label required">Previous School</label>
                                <div class="input-group">
                                    <i class="fas fa-school input-icon"></i>
                                    <input 
                                        type="text" 
                                        name="previous_school" 
                                        id="previousSchoolInput"
                                        class="form-input input-with-icon" 
                                        placeholder="Name of previous school"
                                        value="{{ old('previous_school', $activeEnrollment?->previous_school ?? '') }}"
                                    >
                                </div>
                                <p class="input-hint">Required for transferee students</p>
                                @error('previous_school')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <script>
                        // Sections data from server
                        const allSections = @json($sections->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'grade_level_id' => $s->grade_level_id]));
                        const oldSectionId = "{{ old('section_id', $activeEnrollment?->section_id ?? $student->section_id ?? '') }}";

                        function updateSections() {
                            const gradeLevelId = document.getElementById('gradeLevel').value;
                            const sectionSelect = document.getElementById('sectionId');
                            
                            // Clear current options
                            sectionSelect.innerHTML = '<option value="">Select Section</option>';
                            
                            if (!gradeLevelId) return;
                            
                            // Filter sections by grade level
                            const filteredSections = allSections.filter(s => String(s.grade_level_id) === String(gradeLevelId));
                            
                            filteredSections.forEach(section => {
                                const option = document.createElement('option');
                                option.value = section.id;
                                option.textContent = section.name;
                                if (String(section.id) === String(oldSectionId)) {
                                    option.selected = true;
                                }
                                sectionSelect.appendChild(option);
                            });
                        }

                        function togglePreviousSchool() {
                            const typeSelect = document.getElementById('studentType');
                            const previousSchoolContainer = document.getElementById('previousSchoolContainer');
                            const previousSchoolInput = document.getElementById('previousSchoolInput');
                            
                            if (typeSelect.value === 'transferee') {
                                previousSchoolContainer.classList.remove('hidden');
                                previousSchoolInput.required = true;
                            } else {
                                previousSchoolContainer.classList.add('hidden');
                                previousSchoolInput.required = false;
                                previousSchoolInput.value = '';
                            }
                        }
                        // Initialize on page load
                        document.addEventListener('DOMContentLoaded', function() {
                            updateSections();
                            togglePreviousSchool();
                        });
                    </script>

                    <!-- Photo Upload Section -->
<div class="form-section">
    <div class="section-title">
        <div class="section-icon bg-purple-50 text-purple-600">
            <i class="fas fa-camera"></i>
        </div>
        <span>Profile Photo</span>
    </div>
    
    <div class="flex items-center gap-6">
        <!-- Preview Container -->
        <div class="relative">
            <div id="photoPreview" class="w-32 h-32 rounded-full bg-slate-100 border-4 border-white shadow-lg flex items-center justify-center overflow-hidden">
                @if($student->user->photo)
                    <img src="{{ profile_photo_url($student->user->photo) }}" class="w-full h-full object-cover" alt="Current Photo">
                @else
                    <i class="fas fa-user text-4xl text-slate-300"></i>
                @endif
            </div>
            <button type="button" id="removePhoto" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full shadow-md {{ $student->user->photo ? '' : 'hidden' }} hover:bg-red-600 transition-colors">            </button>
        </div>

        <div class="flex-1">
            <label class="form-label">Upload Photo</label>
            <div class="relative">
                <input 
                    type="file" 
                    name="photo" 
                    id="photoInput"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    class="hidden"
                    onchange="previewPhoto(this)"
                >
                <button 
                    type="button" 
                    onclick="document.getElementById('photoInput').click()"
                    class="btn-secondary w-full md:w-auto"
                >
                    <i class="fas fa-upload"></i>
                    Choose Photo
                </button>
                <p class="input-hint mt-2">JPEG, PNG, GIF up to 2MB</p>
            </div>
            @error('photo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

                    <!-- Family Information -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-emerald-50 text-emerald-600">
                                <i class="fas fa-users"></i>
                            </div>
                            <span>Family Information</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Father -->
                            <div class="md:col-span-2">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-male text-blue-500"></i>
                                    Father's Information
                                </h4>
                            </div>
                            
                            <div>
                                <label class="form-label">Father's Name</label>
                                <input type="text" name="father_name" class="form-input" placeholder="Full name" value="{{ old('father_name', $student->father_name) }}">
                                @error('father_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Father's Occupation</label>
                                <input type="text" name="father_occupation" class="form-input" placeholder="e.g., Farmer, Teacher, OFW" value="{{ old('father_occupation', $student->father_occupation) }}">
                                @error('father_occupation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mother -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-female text-pink-500"></i>
                                    Mother's Information
                                </h4>
                            </div>

                            <div>
                                <label class="form-label">Mother's Name</label>
                                <input type="text" name="mother_name" class="form-input" placeholder="Full name" value="{{ old('mother_name', $student->mother_name) }}">
                                @error('mother_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Mother's Occupation</label>
                                <input type="text" name="mother_occupation" class="form-input" placeholder="e.g., Housewife, Teacher, OFW" value="{{ old('mother_occupation', $student->mother_occupation) }}">
                                @error('mother_occupation')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Guardian -->
                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-sm font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-amber-500"></i>
                                    Guardian's Information (if applicable)
                                </h4>
                            </div>

                            <div>
                                <label class="form-label">Guardian's Name</label>
                                <input type="text" name="guardian_name" class="form-input" placeholder="Full name" value="{{ old('guardian_name', $student->guardian_name) }}">
                                @error('guardian_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Relationship to Student</label>
                                <input type="text" name="guardian_relationship" class="form-input" placeholder="e.g., Grandmother, Uncle" value="{{ old('guardian_relationship', $student->guardian_relationship) }}">
                                @error('guardian_relationship')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="form-label">Guardian's Contact Number</label>
                                <div class="input-group">
                                    <i class="fas fa-phone input-icon"></i>
                                    <input 
                                        type="tel" 
                                        name="guardian_contact" 
                                        id="contactInput"
                                        class="form-input input-with-icon" 
                                        placeholder="09XX XXX XXXX"
                                        maxlength="11"
                                        pattern="[0-9]{11}"
                                        inputmode="numeric"
                                        value="{{ old('guardian_contact', $student->guardian_contact) }}"
                                    >
                                </div>
                                <p class="input-hint">Optional. Must be 11 digits (e.g., 09123456789)</p>
                                @error('guardian_contact')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-amber-50 text-amber-600">
                                <i class="fas fa-home"></i>
                            </div>
                            <span>Address</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-2">
                                <label class="form-label">Street Address</label>
                                <div class="input-group">
                                    <i class="fas fa-road input-icon"></i>
                                    <input type="text" name="street_address" class="form-input input-with-icon" placeholder="House number, Street name" value="{{ old('street_address', $student->street_address) }}">
                                </div>
                                @error('street_address')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Barangay</label>
                                <input type="text" name="barangay" class="form-input" placeholder="Barangay name" value="{{ old('barangay', $student->barangay) }}">
                                @error('barangay')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">City / Municipality</label>
                                <input type="text" name="city" class="form-input" placeholder="City name" value="{{ old('city', $student->city) }}">
                                @error('city')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Province</label>
                                <input type="text" name="province" class="form-input" placeholder="Province name" value="{{ old('province', $student->province) }}">
                                @error('province')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">Zip Code</label>
                                <div class="input-group">
                                    <i class="fas fa-mail-bulk input-icon"></i>
                                    <input type="text" name="zip_code" class="form-input input-with-icon" placeholder="4-digit code" value="{{ old('zip_code', $student->zip_code) }}">
                                </div>
                                @error('zip_code')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="form-section">
                        <div class="section-title">
                            <div class="section-icon bg-indigo-50 text-indigo-600">
                                <i class="fas fa-file-upload"></i>
                            </div>
                            <span>Documents</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Birth Certificate <span class="text-xs text-slate-400">(Optional)</span></label>
                                <input type="file" name="birth_certificate" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                                @if($student->birth_certificate_path)
                                    <p class="text-xs text-teal-600 mt-1"><a href="{{ asset('storage/' . $student->birth_certificate_path) }}" target="_blank" class="underline">View existing file</a></p>
                                @endif
                            </div>
                            <div>
                                <label class="form-label">Report Card / Form 138 <span class="text-xs text-slate-400">(Optional)</span></label>
                                <input type="file" name="report_card" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                                @if($student->report_card_path)
                                    <p class="text-xs text-teal-600 mt-1"><a href="{{ asset('storage/' . $student->report_card_path) }}" target="_blank" class="underline">View existing file</a></p>
                                @endif
                            </div>
                            <div>
                                <label class="form-label">Certificate of Good Moral <span class="text-xs text-slate-400">(Optional)</span></label>
                                <input type="file" name="good_moral" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                                @if($student->good_moral_path)
                                    <p class="text-xs text-teal-600 mt-1"><a href="{{ asset('storage/' . $student->good_moral_path) }}" target="_blank" class="underline">View existing file</a></p>
                                @endif
                            </div>
                            <div>
                                <label class="form-label">Transfer Credentials <span class="text-xs text-slate-400">(Optional)</span></label>
                                <input type="file" name="transfer_credential" accept=".pdf,.jpg,.jpeg,.png" class="form-input">
                                <p class="input-hint">PDF, JPG, PNG (MAX. 5MB)</p>
                                @if($student->transfer_credential_path)
                                    <p class="text-xs text-teal-600 mt-1"><a href="{{ asset('storage/' . $student->transfer_credential_path) }}" target="_blank" class="underline">View existing file</a></p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-between pt-4">
                        <div class="text-sm text-slate-500">
                            Fields marked with <span class="text-red-500">*</span> are required
                        </div>
                        <div class="flex items-center gap-4">
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i>
                                Update Student
                            </button>
                        </div>
                    </div>

                </form>
            </main>
        </div>
    </div>

   <!-- Floating Action Buttons (Bottom Right) -->
<div class="floating-actions">
    <!-- View Details Button (Green) -->
    <a href="{{ route('admin.students.show', $student->id) }}" class="btn-back-colored" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);" title="View Details">
        <i class="fas fa-eye"></i>
    </a>
    <!-- Back Button (Blue) -->
    <a href="{{ route('admin.students.index') }}" class="btn-back-colored" title="Back to List">
        <i class="fas fa-arrow-left"></i>
    </a>
</div>


    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar-container');
            const overlay = document.getElementById('mobileOverlay');
            if (sidebar && overlay) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('hidden');
            }
        }

        // LRN Validation - Only numbers, max 6 digits after prefix
        function validateLRN(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length > 6) {
                input.value = input.value.slice(0, 6);
            }
        }

        // Contact Number Validation - Only numbers, exactly 11 digits, must start with 09
        function validateContact(input) {
            input.value = input.value.replace(/[^0-9]/g, '');
            if (input.value.length > 11) {
                input.value = input.value.slice(0, 11);
            }
            if (input.value.length >= 2 && !input.value.startsWith('09')) {
                input.value = '09' + input.value.slice(2);
            }
        }

        // Form submission validation
        document.getElementById('studentForm').addEventListener('submit', function(e) {
            const lrnInput = document.getElementById('lrnInput');
            const contactInput = document.getElementById('contactInput');
            
            if (lrnInput.value && lrnInput.value.length !== 6) {
                e.preventDefault();
                alert('LRN must be exactly 6 digits after the prefix 120231 (12 digits total)');
                lrnInput.focus();
                return false;
            }
            
            if (contactInput.value && contactInput.value.length !== 11) {
                e.preventDefault();
                alert('Contact number must be exactly 11 digits');
                contactInput.focus();
                return false;
            }
            
            if (lrnInput.value) {
                const fullLRN = '120231' + lrnInput.value;
                const hiddenLRN = document.createElement('input');
                hiddenLRN.type = 'hidden';
                hiddenLRN.name = 'lrn';
                hiddenLRN.value = fullLRN;
                this.appendChild(hiddenLRN);
                lrnInput.name = 'lrn_partial';
            }
        });

        // Auto-hide success message after 3 seconds
        const successAlert = document.getElementById('successAlert');
        if (successAlert) {
            setTimeout(function() {
                successAlert.style.opacity = '0';
                successAlert.style.transition = 'opacity 0.5s';
                setTimeout(function() {
                    if (successAlert.parentNode) {
                        successAlert.parentNode.removeChild(successAlert);
                    }
                }, 500);
            }, 3000);
        }


    </script>


    {{-- ADD THE JAVASCRIPT HERE --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userData = document.getElementById('userData');
    const photoUrl = userData ? userData.dataset.photo : '';
    const studentName = userData ? userData.dataset.name : 'Student Name';
    
    function getInitials(name) {
        if (!name) return 'SN';
        return name.split(/\s+/).slice(0, 2).map(w => w[0]?.toUpperCase()).join('') || 'SN';
    }
    
    const profileContainer = document.createElement('span');
    profileContainer.className = 'inline-block mr-3 align-middle';
    
    if (photoUrl && photoUrl.trim() !== '' && !photoUrl.includes('..')) {
        const img = document.createElement('img');
        img.src = '/storage/' + photoUrl;
        img.className = 'w-10 h-10 rounded-full object-cover border-2 border-indigo-500 shadow-md';
        img.alt = '';
        profileContainer.appendChild(img);
    } else {
        const initials = getInitials(studentName);
        const div = document.createElement('div');
        div.className = 'w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm border-2 border-white shadow-md';
        div.textContent = initials;
        profileContainer.appendChild(div);
    }
    
    const nameEl = document.querySelector('h2.text-2xl.font-bold.text-gray-800');
    if (nameEl) {
        nameEl.parentNode.style.display = 'flex';
        nameEl.parentNode.style.alignItems = 'center';
        nameEl.parentNode.insertBefore(profileContainer, nameEl);
    }
});
</script>

<script>
    // Toggle password visibility
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check if passwords match
function validateMatch() {
    const password = document.getElementById('passwordInput').value;
    const confirm = document.getElementById('confirmPasswordInput').value;
    const hint = document.getElementById('matchHint');
    const confirmInput = document.getElementById('confirmPasswordInput');
    
    if (confirm && password !== confirm) {
        hint.textContent = 'Passwords do not match!';
        hint.style.color = '#ef4444';
        confirmInput.style.borderColor = '#ef4444';
    } else if (confirm && password === confirm) {
        hint.textContent = 'Passwords match!';
        hint.style.color = '#22c55e';
        confirmInput.style.borderColor = '#22c55e';
    } else {
        hint.textContent = 'Leave both blank to keep current password';
        hint.style.color = '#64748b';
        confirmInput.style.borderColor = '#e2e8f0';
    }
}

// Photo Preview Function
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const removeBtn = document.getElementById('removePhoto');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            removeBtn.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Remove Photo
document.getElementById('removePhoto').addEventListener('click', function() {
    const input = document.getElementById('photoInput');
    const preview = document.getElementById('photoPreview');
    
    input.value = '';
    preview.innerHTML = '<i class="fas fa-user text-4xl text-slate-300"></i>';
    this.classList.add('hidden');
    
    // Add hidden input to indicate photo removal
    const removeInput = document.createElement('input');
    removeInput.type = 'hidden';
    removeInput.name = 'remove_photo';
    removeInput.value = '1';
    document.getElementById('studentForm').appendChild(removeInput);
});
</script>

{{-- Hidden Data Element (place this BEFORE the script) --}}
<div id="userData" 
     data-photo="{{ $student->user->photo ?? '' }}" 
     data-name="{{ $student->user->first_name ?? '' }} {{ $student->user->last_name ?? '' }}" 
     style="display: none;">
</div>

</body>
</html>