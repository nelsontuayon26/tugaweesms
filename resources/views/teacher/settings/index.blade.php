<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Settings</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.06), 0 2px 8px -1px rgba(0, 0, 0, 0.04);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .tab-liquid {
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .tab-liquid::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .tab-liquid:hover::before {
            opacity: 1;
        }
        
        .tab-active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4), 0 4px 10px -2px rgba(79, 70, 229, 0.2);
            transform: translateY(-1px);
        }
        
        .input-field, .select-field {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        
        .input-field:focus, .select-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            transform: translateY(-1px);
        }
        
        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
            background: #cbd5e1;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .toggle-switch.active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }
        
        .toggle-switch::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            background: white;
            border-radius: 50%;
            top: 2px;
            left: 2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        
        .toggle-switch.active::after {
            left: 26px;
        }
        
        .section-header {
            background: linear-gradient(135deg, rgba(248, 250, 252, 0.9) 0%, rgba(226, 232, 240, 0.8) 100%);
            border: 1px solid rgba(255, 255, 255, 0.6);
        }
        
        .sidebar-transition {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .mobile-overlay {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #cbd5e1 0%, #94a3b8 100%);
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #94a3b8 0%, #64748b 100%);
        }
        
        .tab-container {
            background: rgba(226, 232, 240, 0.5);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .avatar-glow {
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
        }
        
        .form-actions {
            position: sticky;
            bottom: 0;
            background: rgba(248, 250, 252, 0.95);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(226, 232, 240, 0.8);
            padding: 16px 24px;
            margin: 0 -24px -24px -24px;
            z-index: 20;
        }
        
        .settings-section {
            animation: fadeIn 0.4s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .danger-zone {
            border: 1px solid rgba(239, 68, 68, 0.2);
            background: rgba(254, 242, 242, 0.5);
        }
        
        .setting-item {
            transition: all 0.3s ease;
        }
        
        .setting-item:hover {
            background: rgba(255, 255, 255, 0.6);
        }
    </style>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="{ mobileOpen: false }">

    {{-- Success Message --}}
    @if(session('success'))
    <div id="success-message" class="fixed top-4 right-4 z-50 max-w-sm" style="animation: slideDown 0.5s ease-out;">
        <div class="glass-panel bg-green-50/90 border border-green-200/50 rounded-2xl p-4 shadow-2xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-check text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-green-900">Success!</p>
                <p class="text-xs text-green-700 mt-0.5 leading-relaxed">{{ session('success') }}</p>
            </div>
            <button onclick="closeSuccessMessage()" class="text-green-400 hover:text-green-700 transition-colors p-1 hover:bg-green-100 rounded-lg">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error') || $errors->any())
    <div class="fixed top-4 right-4 z-50 max-w-sm" style="animation: slideDown 0.5s ease-out;">
        <div class="glass-panel bg-red-50/90 border border-red-200/50 rounded-2xl p-4 shadow-2xl flex items-start gap-3">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-exclamation-triangle text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-red-900">Error!</p>
                <p class="text-xs text-red-700 mt-0.5 leading-relaxed">
                    {{ session('error') ?? 'Please check your input and try again.' }}
                </p>
            </div>
            <button onclick="this.closest('.fixed').style.display='none'" class="text-red-400 hover:text-red-700 transition-colors p-1 hover:bg-red-100 rounded-lg">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- Mobile Header --}}
    <div class="lg:hidden fixed top-0 left-0 right-0 z-40 glass-panel border-b border-white/50 px-4 py-3 flex items-center justify-between">
        <button @click="mobileOpen = !mobileOpen" class="p-2.5 rounded-xl hover:bg-white/60 transition-all active:scale-95">
            <i class="fas fa-bars text-slate-700 text-lg"></i>
        </button>
        <div class="flex items-center gap-2">
            <h1 class="text-lg font-bold gradient-text">Settings</h1>
        </div>
        <div class="w-9 h-9 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-sm avatar-glow">
            {{ substr(auth()->user()->first_name ?? 'T', 0, 1) }}{{ substr(auth()->user()->last_name ?? 'P', 0, 1) }}
        </div>
    </div>

    {{-- Mobile Sidebar Overlay --}}
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

    <div class="flex h-screen overflow-hidden pt-14 lg:pt-0">
        
        {{-- Sidebar --}}
        @include('teacher.includes.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden lg:ml-72 relative">
            
            {{-- Desktop Header --}}
            <header class="hidden lg:flex sticky top-0 z-30 glass-panel border-b border-white/60 px-6 xl:px-8 py-4 flex-shrink-0 items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold gradient-text tracking-tight">Settings</h1>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Manage your account preferences and security</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full gradient-bg flex items-center justify-center text-white font-bold text-lg avatar-glow ring-4 ring-white/50">
                        {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                    </div>
                </div>
            </header>

            {{-- Scrollable Content Area --}}
            <main class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar p-4 lg:p-6 xl:p-8 bg-gradient-to-br from-slate-50 via-white to-slate-100">
                
                <form id="settings-form" action="{{ route('teacher.settings.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Tab Navigation --}}
                    <div class="flex flex-wrap gap-2 mb-6 p-2 tab-container rounded-2xl w-fit max-w-full overflow-x-auto shadow-sm">
                        <button type="button" onclick="switchTab('account')" id="tab-account" class="tab-liquid tab-active px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-user-shield text-lg"></i>
                            <span>Account</span>
                        </button>
                        <button type="button" onclick="switchTab('security')" id="tab-security" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-lock text-lg"></i>
                            <span>Security</span>
                        </button>
                        <button type="button" onclick="switchTab('notifications')" id="tab-notifications" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-bell text-lg"></i>
                            <span>Notifications</span>
                        </button>
                        <button type="button" onclick="switchTab('preferences')" id="tab-preferences" class="tab-liquid px-4 lg:px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-600 hover:text-slate-900 transition-all duration-300 whitespace-nowrap flex items-center gap-2">
                            <i class="fas fa-sliders-h text-lg"></i>
                            <span>Preferences</span>
                        </button>
                    </div>

                    {{-- Account Settings Tab --}}
                    <div id="content-account" class="settings-section space-y-6">
                        {{-- Profile Information Card --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    Profile Information
                                </h2>
                            </div>
                            
                            <div class="flex flex-col md:flex-row gap-6 mb-6">
                                {{-- Avatar Display (Read-only from profile) --}}
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-24 h-24 rounded-full gradient-bg flex items-center justify-center text-white text-3xl font-bold avatar-glow">
                                        {{ substr(auth()->user()->first_name, 0, 1) }}{{ substr(auth()->user()->last_name, 0, 1) }}
                                    </div>
                                    <a href="{{ route('teacher.profile.edit') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
                                        Edit in Profile
                                    </a>
                                </div>
                                
                                {{-- Basic Info (Read-only display) --}}
                                <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Full Name</label>
                                        <div class="px-4 py-3 rounded-xl bg-slate-100 text-sm font-semibold text-slate-800">
                                            {{ auth()->user()->first_name }} {{ auth()->user()->middle_name }} {{ auth()->user()->last_name }} {{ auth()->user()->suffix }}
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Email Address</label>
                                        <div class="px-4 py-3 rounded-xl bg-slate-100 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                            <i class="fas fa-envelope text-slate-400"></i>
                                            {{ auth()->user()->email }}
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Username</label>
                                        <div class="px-4 py-3 rounded-xl bg-slate-100 text-sm font-semibold text-slate-800 flex items-center gap-2">
                                            <i class="fas fa-at text-slate-400"></i>
                                            {{ auth()->user()->username }}
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</label>
                                        <div class="px-4 py-3 rounded-xl bg-primary-100 text-sm font-bold text-primary-700 border border-primary-200 inline-flex items-center gap-2">
                                            <i class="fas fa-user-tie"></i>
                                            {{ auth()->user()->role->name ?? 'Teacher' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
                                <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                                <p class="text-sm text-amber-800">
                                    To update your profile information (name, email, contact details), please use the <a href="{{ route('teacher.profile.edit') }}" class="font-semibold underline">Edit Profile</a> page.
                                </p>
                            </div>
                        </div>

                        {{-- Session Management --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-desktop"></i>
                                    </div>
                                    Active Sessions
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                @forelse($sessions ?? [] as $session)
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg {{ $session->is_current ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-600' }} flex items-center justify-center">
                                            <i class="fas {{ $session->device_type == 'mobile' ? 'fa-mobile-alt' : ($session->device_type == 'tablet' ? 'fa-tablet-alt' : 'fa-laptop') }}"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">{{ $session->device_name }} {{ $session->is_current ? '(Current)' : '' }}</p>
                                            <p class="text-xs text-slate-500">{{ $session->location }} • {{ $session->last_active->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    @if(!$session->is_current)
                                    <form action="{{ route('teacher.settings.revoke-session', $session->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors px-3 py-1.5 rounded-lg hover:bg-red-50">
                                            Revoke
                                        </button>
                                    </form>
                                    @else
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">Active Now</span>
                                    @endif
                                </div>
                                @empty
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 text-center">
                                    <p class="text-sm text-slate-500">Only this session is active.</p>
                                </div>
                                @endforelse
                            </div>
                            
                            @if(count($sessions ?? []) > 1)
                            <div class="mt-4 pt-4 border-t border-slate-200">
                                <form action="{{ route('teacher.settings.revoke-all-sessions') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 transition-colors flex items-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i>
                                        Sign out all other devices
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Security Settings Tab --}}
                    <div id="content-security" class="settings-section space-y-6 hidden">
                        {{-- Change Password --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    Change Password
                                </h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2 md:col-span-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Current Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="current_password" required class="input-field w-full pl-11 pr-12 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Enter current password">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">New Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="new_password" required class="input-field w-full pl-11 pr-12 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="New password">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Confirm New Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="password" name="new_password_confirmation" required class="input-field w-full pl-11 pr-12 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none" placeholder="Confirm new password">
                                        <button type="button" onclick="togglePassword(this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Password Requirements --}}
                            <div class="mt-6 p-4 rounded-xl bg-slate-50 border border-slate-200">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Password Requirements</p>
                                <ul class="space-y-2 text-sm text-slate-600">
                                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> At least 8 characters</li>
                                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> One uppercase letter</li>
                                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> One number</li>
                                    <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> One special character</li>
                                </ul>
                            </div>
                        </div>

                        {{-- Two Factor Authentication --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    Two-Factor Authentication
                                </h2>
                            </div>
                            
                            <div class="flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                                        <i class="fas fa-mobile-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">Authenticator App</p>
                                        <p class="text-sm text-slate-500">Use Google Authenticator or similar app</p>
                                    </div>
                                </div>
                                <div class="toggle-switch {{ auth()->user()->two_factor_enabled ? 'active' : '' }}" onclick="toggle2FA(this)">
                                    <input type="hidden" name="two_factor_enabled" value="{{ auth()->user()->two_factor_enabled ? '1' : '0' }}">
                                </div>
                            </div>
                            
                            @if(auth()->user()->two_factor_enabled)
                            <div class="mt-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200">
                                <p class="text-sm text-emerald-800 flex items-center gap-2">
                                    <i class="fas fa-check-circle"></i>
                                    2FA is enabled. You'll need your authenticator app when logging in.
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>

                    {{-- Notifications Tab --}}
                    <div id="content-notifications" class="settings-section space-y-6 hidden">
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    Notification Preferences
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                {{-- Email Notifications --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Email Notifications</p>
                                            <p class="text-sm text-slate-500">Receive updates via email</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['email_notifications'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'email_notifications')">
                                        <input type="hidden" name="settings[email_notifications]" value="{{ auth()->user()->settings['email_notifications'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                {{-- Attendance Alerts --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Attendance Alerts</p>
                                            <p class="text-sm text-slate-500">Get notified about attendance issues</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['attendance_alerts'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'attendance_alerts')">
                                        <input type="hidden" name="settings[attendance_alerts]" value="{{ auth()->user()->settings['attendance_alerts'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                {{-- Grade Submissions --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Grade Submission Reminders</p>
                                            <p class="text-sm text-slate-500">Reminders for grade deadlines</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['grade_reminders'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'grade_reminders')">
                                        <input type="hidden" name="settings[grade_reminders]" value="{{ auth()->user()->settings['grade_reminders'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                {{-- New Students --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-600">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">New Student Enrollments</p>
                                            <p class="text-sm text-slate-500">Notify when students join your section</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['new_student_notifications'] ?? false ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'new_student_notifications')">
                                        <input type="hidden" name="settings[new_student_notifications]" value="{{ auth()->user()->settings['new_student_notifications'] ?? false ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                {{-- System Updates --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600">
                                            <i class="fas fa-cog"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">System Updates</p>
                                            <p class="text-sm text-slate-500">News about features and improvements</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['system_updates'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'system_updates')">
                                        <input type="hidden" name="settings[system_updates]" value="{{ auth()->user()->settings['system_updates'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                {{-- SMS Notifications --}}
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                                            <i class="fas fa-sms"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">SMS Notifications</p>
                                            <p class="text-sm text-slate-500">Receive urgent alerts via text message</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['sms_notifications'] ?? false ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'sms_notifications')">
                                        <input type="hidden" name="settings[sms_notifications]" value="{{ auth()->user()->settings['sms_notifications'] ?? false ? '1' : '0' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Preferences Tab --}}
                    <div id="content-preferences" class="settings-section space-y-6 hidden">
                        {{-- Display Settings --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-palette"></i>
                                    </div>
                                    Display Settings
                                </h2>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Theme</label>
                                    <select name="settings[theme]" id="themeSelect"
                                            onchange="applyTeacherTheme(this.value)"
                                            class="select-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                        <option value="light" {{ (auth()->user()->settings['theme'] ?? 'light') == 'light' ? 'selected' : '' }}>Light</option>
                                        <option value="dark" {{ (auth()->user()->settings['theme'] ?? 'light') == 'dark' ? 'selected' : '' }}>Dark</option>
                                        <option value="system" {{ (auth()->user()->settings['theme'] ?? 'light') == 'system' ? 'selected' : '' }}>System Default</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Language</label>
                                    <select name="settings[language]" class="select-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                        <option value="en" {{ (auth()->user()->settings['language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                        <option value="fil" {{ (auth()->user()->settings['language'] ?? 'en') == 'fil' ? 'selected' : '' }}>Filipino</option>
                                        <option value="ceb" {{ (auth()->user()->settings['language'] ?? 'en') == 'ceb' ? 'selected' : '' }}>Cebuano</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Format</label>
                                    <select name="settings[date_format]" class="select-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                        <option value="MM/DD/YYYY" {{ (auth()->user()->settings['date_format'] ?? 'MM/DD/YYYY') == 'MM/DD/YYYY' ? 'selected' : '' }}>MM/DD/YYYY</option>
                                        <option value="DD/MM/YYYY" {{ (auth()->user()->settings['date_format'] ?? 'MM/DD/YYYY') == 'DD/MM/YYYY' ? 'selected' : '' }}>DD/MM/YYYY</option>
                                        <option value="YYYY-MM-DD" {{ (auth()->user()->settings['date_format'] ?? 'MM/DD/YYYY') == 'YYYY-MM-DD' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Time Format</label>
                                    <select name="settings[time_format]" class="select-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none">
                                        <option value="12h" {{ (auth()->user()->settings['time_format'] ?? '12h') == '12h' ? 'selected' : '' }}>12-hour (AM/PM)</option>
                                        <option value="24h" {{ (auth()->user()->settings['time_format'] ?? '12h') == '24h' ? 'selected' : '' }}>24-hour</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Privacy Settings --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-user-shield"></i>
                                    </div>
                                    Privacy Settings
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-cyan-100 flex items-center justify-center text-cyan-600">
                                            <i class="fas fa-eye"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Profile Visibility</p>
                                            <p class="text-sm text-slate-500">Allow other teachers to see your profile</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['profile_visible'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'profile_visible')">
                                        <input type="hidden" name="settings[profile_visible]" value="{{ auth()->user()->settings['profile_visible'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                                            <i class="fas fa-envelope-open-text"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Show Email to Students</p>
                                            <p class="text-sm text-slate-500">Students can see your email address</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['email_visible_to_students'] ?? false ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'email_visible_to_students')">
                                        <input type="hidden" name="settings[email_visible_to_students]" value="{{ auth()->user()->settings['email_visible_to_students'] ?? false ? '1' : '0' }}">
                                    </div>
                                </div>
                                
                                <div class="setting-item flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Show Last Active Status</p>
                                            <p class="text-sm text-slate-500">Others can see when you were last online</p>
                                        </div>
                                    </div>
                                    <div class="toggle-switch {{ auth()->user()->settings['show_last_active'] ?? true ? 'active' : '' }}" onclick="this.classList.toggle('active'); updateSetting(this, 'show_last_active')">
                                        <input type="hidden" name="settings[show_last_active]" value="{{ auth()->user()->settings['show_last_active'] ?? true ? '1' : '0' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data & Privacy --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-white/60">
                            <div class="section-header rounded-2xl p-5 mb-6 border border-white/60 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-500 to-slate-600 flex items-center justify-center text-white shadow-lg">
                                        <i class="fas fa-database"></i>
                                    </div>
                                    Data & Privacy
                                </h2>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-xl bg-white/50 border border-slate-100">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                            <i class="fas fa-download"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-800">Download My Data</p>
                                            <p class="text-sm text-slate-500">Export all your personal data</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('teacher.settings.export-data') }}" class="px-4 py-2 rounded-lg bg-blue-100 text-blue-700 font-semibold text-sm hover:bg-blue-200 transition-colors border border-blue-200">
                                        Export
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Danger Zone --}}
                        <div class="glass-card rounded-3xl p-5 lg:p-8 shadow-lg border border-red-200 danger-zone">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-rose-600 flex items-center justify-center text-white shadow-lg">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <h2 class="text-xl font-bold text-red-700">Danger Zone</h2>
                            </div>
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 rounded-xl bg-white/70 border border-red-200">
                                    <div>
                                        <p class="font-semibold text-slate-800">Delete Account</p>
                                        <p class="text-sm text-slate-500">Permanently delete your account and all data</p>
                                    </div>
                                    <button type="button" onclick="confirmDelete()" class="px-4 py-2 rounded-lg bg-red-100 text-red-700 font-semibold text-sm hover:bg-red-200 transition-colors border border-red-200">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom Action Buttons --}}
                    <div class="form-actions flex items-center justify-end gap-3">
                        <a href="{{ route('teacher.dashboard') }}" 
                           class="px-5 py-2.5 bg-white border-2 border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </a>
                        <button type="button" onclick="resetForm()"
                                class="px-5 py-2.5 bg-white border-2 border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:border-red-200 hover:text-red-600 hover:bg-red-50 transition-all">
                            <i class="fas fa-undo mr-2"></i>Reset
                        </button>
                        <button type="button" onclick="saveSettings()" id="save-btn"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:-translate-y-0.5 active:scale-95">
                            <i class="fas fa-save mr-2"></i>Save Settings
                        </button>
                    </div>

                </form>

            </main>
        </div>
    </div>

    {{-- Delete Account Modal --}}
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="glass-panel bg-white rounded-3xl p-8 max-w-md w-full mx-4 shadow-2xl">
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center text-red-600 mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Delete Account?</h3>
                <p class="text-sm text-slate-500">This action cannot be undone. All your data will be permanently removed.</p>
            </div>
            
            <form action="{{ route('teacher.settings.delete-account') }}" method="POST" class="space-y-4">
                @csrf
                @method('DELETE')
                
                <div class="space-y-2">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Type "DELETE" to confirm</label>
                    <input type="text" name="confirmation" required pattern="DELETE" class="input-field w-full px-4 py-3 rounded-xl bg-white text-sm font-semibold text-slate-800 focus:outline-none text-center uppercase tracking-widest" placeholder="DELETE">
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-3 rounded-xl bg-slate-100 text-slate-700 font-semibold text-sm hover:bg-slate-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-red-500 text-white font-semibold text-sm hover:bg-red-600 transition-colors">
                        Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    // Save settings function
    function saveSettings() {
        const form = document.getElementById('settings-form');
        const saveBtn = document.getElementById('save-btn');
        const icon = saveBtn.querySelector('i');
        
        // Validate password fields if they contain values
        const currentPassword = form.querySelector('input[name="current_password"]');
        const newPassword = form.querySelector('input[name="new_password"]');
        const confirmPassword = form.querySelector('input[name="new_password_confirmation"]');
        
        // If any password field has value, all must have value
        if (currentPassword.value || newPassword.value || confirmPassword.value) {
            if (!currentPassword.value || !newPassword.value || !confirmPassword.value) {
                alert('Please fill in all password fields to change your password.');
                return;
            }
            
            if (newPassword.value !== confirmPassword.value) {
                alert('New password and confirmation do not match.');
                return;
            }
            
            if (newPassword.value.length < 8) {
                alert('Password must be at least 8 characters long.');
                return;
            }
        }
        
        // Show loading state
        saveBtn.classList.add('loading');
        saveBtn.disabled = true;
        icon.classList.remove('fa-save');
        icon.classList.add('fa-spinner', 'fa-spin');
        
        // Submit the form
        form.submit();
    }

    // Reset form function - properly resets all fields including toggles
    function resetForm() {
        const form = document.getElementById('settings-form');
        
        // Reset all text inputs, selects, and textareas
        form.reset();
        
        // Reset all toggle switches to their original state from the database
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            const input = toggle.querySelector('input');
            const originalValue = input.getAttribute('value') === '1';
            
            if (originalValue) {
                toggle.classList.add('active');
            } else {
                toggle.classList.remove('active');
            }
            
            // Ensure input value matches visual state
            input.value = originalValue ? '1' : '0';
        });
        
        // Show feedback
        const btn = document.querySelector('button[onclick="resetForm()"]');
        const originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-xl"></i>';
        setTimeout(() => {
            btn.innerHTML = originalIcon;
        }, 1000);
    }

    // Tab switching
    function switchTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.settings-section').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Show selected tab content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Update tab buttons
        document.querySelectorAll('[id^="tab-"]').forEach(tab => {
            tab.classList.remove('tab-active');
            tab.classList.add('text-slate-600');
        });
        
        // Activate selected tab
        const activeTab = document.getElementById('tab-' + tabName);
        activeTab.classList.add('tab-active');
        activeTab.classList.remove('text-slate-600');
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const deleteModal = document.getElementById('delete-modal');
            if (!deleteModal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        }
    });

    // Close success message
    function closeSuccessMessage() {
        const message = document.getElementById('success-message');
        if (message) {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                message.style.display = 'none';
            }, 300);
        }
    }

    // Toggle password visibility
    function togglePassword(btn) {
        const input = btn.previousElementSibling;
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

    // Update toggle setting value
    function updateSetting(toggle, name) {
        const input = toggle.querySelector('input');
        input.value = toggle.classList.contains('active') ? '1' : '0';
    }

    // Toggle 2FA with confirmation
    function toggle2FA(toggle) {
        const input = toggle.querySelector('input');
        const isCurrentlyActive = toggle.classList.contains('active');
        
        // If currently active (turning OFF)
        if (isCurrentlyActive) {
            if (confirm('Disabling 2FA will make your account less secure. Are you sure?')) {
                toggle.classList.remove('active');
                input.value = '0';
            }
            // If cancelled, do nothing - keep active
        } else {
            // Turning ON - just toggle (in real app, would open QR code setup)
            toggle.classList.add('active');
            input.value = '1';
            alert('In production, this would open 2FA setup with QR code.');
        }
    }

    // Delete account modal
    function confirmDelete() {
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-modal').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        document.getElementById('delete-modal').classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Theme real-time preview
    function applyTeacherTheme(theme) {
        if (window.tessmsTheme) {
            if (theme === 'system') {
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                window.tessmsTheme.set(prefersDark ? 'dark' : 'light');
            } else {
                window.tessmsTheme.set(theme);
            }
        }
    }

    // Auto-hide messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            closeSuccessMessage();
        }, 5000);
    });
</script>

</body>
</html>