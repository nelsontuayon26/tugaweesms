@extends('layouts.admin')

@section('title', 'My Profile')
@section('header-title', 'My Profile')

@section('content')
<div x-data="adminProfileApp()" class="max-w-5xl mx-auto">

    <!-- Toast Notification with Countdown -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed top-4 right-4 z-50 flex flex-col rounded-xl shadow-lg overflow-hidden min-w-[300px]"
         :class="toast.type === 'success' ? 'bg-emerald-500' : 'bg-rose-500'">
        <div class="flex items-center gap-2 px-4 py-3 text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      :d="toast.type === 'success' ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12'"/>
            </svg>
            <span class="font-medium text-sm" x-text="toast.message"></span>
            <button @click="toast.show = false" class="ml-auto text-white/80 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="h-1 bg-white/20">
            <div class="h-full bg-white/60 transition-all ease-linear"
                 :style="`width: ${toast.progress}%; transition-duration: ${toast.duration}ms`"></div>
        </div>
    </div>

    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">My Profile</h1>
                <p class="text-slate-500 mt-1">Manage your personal information and account settings</p>
            </div>
            <div class="flex items-center gap-3">
                <button @click="editMode = !editMode"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium text-sm transition-all duration-200"
                        :class="editMode ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-blue-600 text-white hover:bg-blue-700 shadow-lg shadow-blue-200'">
                    <svg x-show="!editMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <svg x-show="editMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span x-text="editMode ? 'Cancel' : 'Edit Profile'"></span>
                </button>
                <button x-show="editMode" @click="$refs.profileForm.submit()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl font-medium text-sm hover:bg-emerald-700 transition-all duration-200 shadow-lg shadow-emerald-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Left Column - Profile Card -->
        <div class="xl:col-span-1 space-y-6">
            <!-- Profile Photo Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="h-32 bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 relative">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white"/>
                        </svg>
                    </div>
                </div>
                <div class="px-6 pb-6 relative">
                    <div class="relative -mt-16 mb-4 flex justify-center">
                        <div class="relative group">
                            <div class="w-32 h-32 rounded-2xl bg-white p-1 shadow-xl">
                                @if($user->photo)
                                    <img src="{{ profile_photo_url($user->photo) }}"
                                         class="w-full h-full rounded-xl object-cover"
                                         alt="Profile Photo">
                                @else
                                    <div class="w-full h-full rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
                                        {{ strtoupper(substr($user->first_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <button @click="showPhotoModal = true"
                                    class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-xl shadow-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:text-blue-600 hover:border-blue-300 transition-all duration-200 group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="text-center">
                        <h2 class="text-xl font-bold text-slate-900">{{ $user->first_name }} {{ $user->last_name }}</h2>
                        <p class="text-slate-500 text-sm mt-1">{{ $user->email }}</p>
                        <div class="flex items-center justify-center gap-2 mt-3">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase tracking-wide">System Administrator</span>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full capitalize">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-100">
                        <div class="text-center">
                            <p class="text-lg font-bold text-slate-900">{{ $user->created_at->format('M d, Y') }}</p>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Member Since</p>
                        </div>
                        <div class="text-center border-l border-slate-100">
                            <p class="text-lg font-bold text-slate-900">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Last Login</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-4">Account Settings</h3>
                <div class="space-y-2">
                    <button @click="showPasswordModal = true"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-blue-600 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-sm">Change Password</p>
                            <p class="text-xs text-slate-400">Update your security credentials</p>
                        </div>
                    </button>
                    <button @click="showDeleteModal = true"
                            class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-rose-50 hover:text-rose-600 transition-all duration-200 group">
                        <div class="w-10 h-10 rounded-lg bg-rose-50 flex items-center justify-center group-hover:bg-rose-100 transition-colors">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-medium text-sm">Delete Account</p>
                            <p class="text-xs text-slate-400">Permanently remove your account</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Right Column - Details -->
        <div class="xl:col-span-2 space-y-6">

            <!-- Profile Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    Profile Information
                </h3>

                <form x-ref="profileForm" action="{{ route(($routePrefix ?? 'admin') . '.profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- First Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">First Name</label>
                            <input x-show="editMode"
                                   type="text"
                                   name="first_name"
                                   x-model="formData.first_name"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                            <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.first_name || '—'"></p>
                            @error('first_name')
                                <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Last Name</label>
                            <input x-show="editMode"
                                   type="text"
                                   name="last_name"
                                   x-model="formData.last_name"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                            <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.last_name || '—'"></p>
                            @error('last_name')
                                <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Username - EDITABLE -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Username</label>
                            <input x-show="editMode"
                                   type="text"
                                   name="username"
                                   x-model="formData.username"
                                   class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                            <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.username || '—'"></p>
                            @error('username')
                                <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email - READ ONLY with tooltip -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                            <div class="relative group" title="Email not editable">
                                <input type="email"
                                       x-model="formData.email"
                                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed"
                                       disabled>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Email address cannot be changed</p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Account Information -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    Account Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Role</p>
                        <p class="text-base font-bold text-slate-900">System Administrator</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span>
                            Active
                        </span>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">User ID</p>
                        <p class="text-base font-bold text-slate-900 font-mono">#{{ $user->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Photo Upload Modal -->
    <div x-show="showPhotoModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showPhotoModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showPhotoModal = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="showPhotoModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-900">Update Profile Photo</h3>
                <button @click="showPhotoModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route(($routePrefix ?? 'admin') . '.profile.photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6">
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-blue-400 hover:bg-blue-50/50 transition-all cursor-pointer relative"
                         onclick="document.getElementById('photo-input').click()">
                        <input type="file" id="photo-input" name="photo" accept="image/jpeg,image/png,image/jpg"
                               class="hidden" @change="fileName = $event.target.files[0]?.name">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="font-medium text-slate-900 mb-1" x-text="fileName || 'Click to upload or drag and drop'"></p>
                        <p class="text-sm text-slate-500">PNG, JPG (max. 2MB)</p>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <button type="button" @click="showPhotoModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Password Change Modal -->
    <div x-show="showPasswordModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showPasswordModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showPasswordModal = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="showPasswordModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="font-bold text-slate-900">Change Password</h3>
                <button @click="showPasswordModal = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form action="{{ route(($routePrefix ?? 'admin') . '.profile.password') }}" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                               placeholder="Enter current password">
                        @error('current_password')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                               placeholder="Enter new password (min 8 chars)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                               placeholder="Confirm new password">
                        @error('password')
                            <p class="text-rose-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <button type="button" @click="showPasswordModal = false"
                            class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">Update Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Account Modal with Countdown -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showDeleteModal = false"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>

        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-rose-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Delete Account?</h3>
                <p class="text-slate-500 text-sm mb-6">This action cannot be undone. Your account and all associated data will be permanently removed.</p>

                <form action="{{ route(($routePrefix ?? 'admin') . '.profile.delete') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-slate-700 mb-2 text-left">Enter your password to confirm</label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none transition-all"
                               placeholder="Your password">
                        @error('password')
                            <p class="text-rose-500 text-sm mt-1 text-left">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="showDeleteModal = false"
                                class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 transition-colors">Cancel</button>
                        <button type="submit"
                                :disabled="deleteCountdown > 0"
                                class="flex-1 px-4 py-2.5 bg-rose-500 text-white rounded-xl font-medium hover:bg-rose-600 disabled:opacity-50 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                            <template x-if="deleteCountdown > 0">
                                <span x-text="deleteCountdown + 's'"></span>
                            </template>
                            <span x-show="deleteCountdown === 0">Delete Account</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adminProfileApp() {
            return {
                editMode: false,
                showPhotoModal: false,
                showPasswordModal: false,
                showDeleteModal: false,
                fileName: '',
                deleteCountdown: 0,
                deleteTimer: null,
                toast: { show: false, message: '', type: 'success', progress: 100, duration: 3000 },
                formData: {
                    first_name: @json($user->first_name ?? ''),
                    last_name: @json($user->last_name ?? ''),
                    username: @json($user->username ?? ''),
                    email: @json($user->email ?? '')
                },
                init() {
                    // Handle session flash messages
                    @if(session('success'))
                        this.showToast('{{ session('success') }}', 'success');
                    @endif
                    @if(session('error'))
                        this.showToast('{{ session('error') }}', 'error');
                    @endif
                    @if($errors->any())
                        this.editMode = true;
                        this.showToast('{{ $errors->first() }}', 'error');
                    @endif

                    // Watch delete modal
                    this.$watch('showDeleteModal', value => {
                        if (value) {
                            this.deleteCountdown = 5;
                            this.deleteTimer = setInterval(() => {
                                this.deleteCountdown--;
                                if (this.deleteCountdown <= 0) {
                                    clearInterval(this.deleteTimer);
                                }
                            }, 1000);
                        } else {
                            clearInterval(this.deleteTimer);
                            this.deleteCountdown = 0;
                        }
                    });
                },
                showToast(message, type = 'success', duration = 3000) {
                    if (this.toast.timeout) {
                        clearTimeout(this.toast.timeout);
                    }
                    this.toast = {
                        show: true,
                        message,
                        type,
                        progress: 100,
                        duration: duration
                    };
                    setTimeout(() => {
                        this.toast.progress = 0;
                    }, 50);
                    this.toast.timeout = setTimeout(() => {
                        this.toast.show = false;
                    }, duration);
                }
            }
        }
    </script>
</div>
@endsection
