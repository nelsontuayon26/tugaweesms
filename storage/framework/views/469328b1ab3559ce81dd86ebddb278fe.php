<!-- resources/views/student/profile/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Tugawe Elementary School</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased" x-data="profileApp()">

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-30 lg:hidden bg-slate-900/50 backdrop-blur-sm"
         @click="mobileOpen = false"
         style="display: none;"></div>

    <!-- Mobile Hamburger -->
    <button @click="mobileOpen = !mobileOpen" 
            class="fixed top-4 left-4 z-50 lg:hidden w-10 h-10 bg-white rounded-lg shadow-md flex items-center justify-center text-slate-600 hover:text-indigo-600 transition-all">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Include Sidebar -->
    <?php echo $__env->make('student.includes.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Main Content -->
    <main class="transition-all duration-300 ease-out min-h-screen p-4 lg:p-8"
          :class="mainContentClass">
        
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
            <!-- Countdown Progress Bar -->
            <div class="h-1 bg-white/20">
                <div class="h-full bg-white/60 transition-all ease-linear"
                     :style="`width: ${toast.progress}%; transition-duration: ${toast.duration}ms`">
                </div>
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
                            :class="editMode ? 'bg-slate-200 text-slate-700 hover:bg-slate-300' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-200'">
                        <svg x-show="!editMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <svg x-show="editMode" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span x-text="editMode ? 'Cancel' : 'Edit Profile'"></span>
                    </button>
                    <button x-show="editMode" @click="saveProfile" 
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
                    <div class="h-32 bg-gradient-to-br from-indigo-600 to-violet-600 relative">
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
                                    <?php if(isset($student) && $student->user && $student->user->photo): ?>
                                        <img src="<?php echo e(profile_photo_url($student->user->photo)); ?>" 
                                             class="w-full h-full rounded-xl object-cover" 
                                             alt="Profile Photo">
                                    <?php else: ?>
                                        <div class="w-full h-full rounded-xl bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-3xl font-bold">
                                            <?php if(isset($student) && $student->user): ?>
                                                <?php echo e(substr($student->user->first_name, 0, 1)); ?><?php echo e(substr($student->user->last_name, 0, 1)); ?>

                                            <?php else: ?>
                                                S
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <button @click="showPhotoModal = true" 
                                        class="absolute -bottom-2 -right-2 w-10 h-10 bg-white rounded-xl shadow-lg border border-slate-200 flex items-center justify-center text-slate-600 hover:text-indigo-600 hover:border-indigo-300 transition-all duration-200 group-hover:scale-110">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <h2 class="text-xl font-bold text-slate-900">
                                <?php if(isset($student) && $student->user): ?>
                                    <?php echo e($student->user->first_name); ?> <?php echo e($student->user->last_name); ?>

                                <?php else: ?>
                                    Student Name
                                <?php endif; ?>
                            </h2>
                            <p class="text-slate-500 text-sm mt-1">
                                <?php if(isset($student) && $student->user): ?>
                                    <?php echo e($student->user->email); ?>

                                <?php endif; ?>
                            </p>
                            <div class="flex items-center justify-center gap-2 mt-3 flex-wrap">
                                <?php if(isset($student) && $student->lrn): ?>
                                    <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-bold rounded-full">
                                        LRN: <?php echo e($student->lrn); ?>

                                    </span>
                                <?php endif; ?>
                                <?php if(isset($student) && $student->gradeLevel): ?>
                                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-bold rounded-full">
                                        <?php echo e($student->gradeLevel->name); ?>

                                    </span>
                                <?php endif; ?>
                                <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full capitalize">
                                    <?php echo e($student->status ?? 'pending'); ?>

                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-slate-100">
                            <div class="text-center">
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->section): ?>
                                        <?php echo e($student->section->name); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Section</p>
                            </div>
                            <div class="text-center border-l border-slate-100">
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->gender): ?>
                                        <?php echo e(ucfirst($student->gender)); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                                <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Gender</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="font-bold text-slate-900 mb-4">Account Settings</h3>
                    <div class="space-y-2">
                        <button @click="showPasswordModal = true"
                                class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-indigo-600 transition-all duration-200 group">
                            <div class="w-10 h-10 rounded-lg bg-indigo-50 flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                
                <!-- Tabs -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-1">
                    <div class="flex gap-1 flex-wrap">
                        <button @click="activeTab = 'personal'" 
                                class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
                                :class="activeTab === 'personal' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'">
                            Personal
                        </button>
                        <button @click="activeTab = 'family'" 
                                class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
                                :class="activeTab === 'family' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'">
                            Family
                        </button>
                        <button @click="activeTab = 'address'" 
                                class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
                                :class="activeTab === 'address' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'">
                            Address
                        </button>
                        <button @click="activeTab = 'academic'" 
                                class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200"
                                :class="activeTab === 'academic' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'">
                            Academic
                        </button>
                        <button @click="activeTab = 'documents'" 
                                class="flex-1 px-4 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 relative"
                                :class="activeTab === 'documents' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'">
                            Documents
                            <?php if(isset($student) && (!$student->birth_certificate_path || !$student->report_card_path || !$student->good_moral_path)): ?>
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white"></span>
                            <?php endif; ?>
                        </button>
                    </div>
                </div>

                <!-- Personal Tab -->
                <div x-show="activeTab === 'personal'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Personal Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">First Name</label>
                                    <input x-show="editMode" x-model="formData.first_name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.first_name || '—'"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">LRN (Learner Reference Number)</label>
                                    <input x-show="editMode" x-model="formData.lrn" type="text" maxlength="50" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.lrn || '—'"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Birth Date</label>
                                    <input x-show="editMode" x-model="formData.birthdate" type="date" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                   <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formatDateWithAge(formData.birthdate)"></p>
                                </div>
                                <div>
                                  <script>
function formatDateWithAge(date) {
    if (!date) return '—';

    const d = new Date(date);
    const today = new Date();

    let age = today.getFullYear() - d.getFullYear();
    const m = today.getMonth() - d.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < d.getDate())) {
        age--;
    }

    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');

    return `${yyyy}-${mm}-${dd} (${age} years old)`;
}
</script>


                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Gender</label>
                                    <select x-show="editMode" x-model="formData.gender" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <p x-show="!editMode" class="text-slate-900 font-medium capitalize" x-text="formData.gender || '—'"></p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Last Name</label>
                                    <input x-show="editMode" x-model="formData.last_name" type="text" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.last_name || '—'"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email Address</label>
                                    <input x-show="editMode" x-model="formData.email" type="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.email || '—'"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Birth Place</label>
                                    <input x-show="editMode" x-model="formData.birth_place" type="text" maxlength="150" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.birth_place || '—'"></p>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nationality</label>
                                    <input x-show="editMode" x-model="formData.nationality" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                    <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.nationality || '—'"></p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Religion</label>
                                <input x-show="editMode" x-model="formData.religion" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.religion || '—'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Family Tab -->
                <div x-show="activeTab === 'family'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <!-- Father's Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Father's Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Father's Name</label>
                                <input x-show="editMode" x-model="formData.father_name" type="text" maxlength="150" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.father_name || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Father's Occupation</label>
                                <input x-show="editMode" x-model="formData.father_occupation" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.father_occupation || '—'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Mother's Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Mother's Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mother's Name</label>
                                <input x-show="editMode" x-model="formData.mother_name" type="text" maxlength="150" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.mother_name || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mother's Occupation</label>
                                <input x-show="editMode" x-model="formData.mother_occupation" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.mother_occupation || '—'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian's Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Guardian's Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Guardian's Name</label>
                                <input x-show="editMode" x-model="formData.guardian_name" type="text" maxlength="150" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.guardian_name || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Relationship</label>
                                <input x-show="editMode" x-model="formData.guardian_relationship" type="text" maxlength="50" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.guardian_relationship || '—'"></p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Guardian's Contact Number</label>
                                <input x-show="editMode" x-model="formData.guardian_contact" type="tel" maxlength="50" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.guardian_contact || '—'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Tab -->
                <div x-show="activeTab === 'address'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Address Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Street Address</label>
                                <input x-show="editMode" x-model="formData.street_address" type="text" maxlength="255" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.street_address || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Barangay</label>
                                <input x-show="editMode" x-model="formData.barangay" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.barangay || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">City / Municipality</label>
                                <input x-show="editMode" x-model="formData.city" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.city || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Province</label>
                                <input x-show="editMode" x-model="formData.province" type="text" maxlength="100" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.province || '—'"></p>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">ZIP Code</label>
                                <input x-show="editMode" x-model="formData.zip_code" type="text" maxlength="20" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all">
                                <p x-show="!editMode" class="text-slate-900 font-medium" x-text="formData.zip_code || '—'"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Tab -->
                <div x-show="activeTab === 'academic'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h3 class="font-bold text-slate-900 mb-4">Academic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Student ID</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student)): ?>
                                        <?php echo e($student->id); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">LRN</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->lrn): ?>
                                        <?php echo e($student->lrn); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Grade Level</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->gradeLevel): ?>
                                        <?php echo e($student->gradeLevel->name); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Section</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->section): ?>
                                        <?php echo e($student->section->name); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Enrollment Status</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize
                                    <?php if(isset($student) && $student->status === 'enrolled'): ?> bg-emerald-100 text-emerald-700
                                    <?php elseif(isset($student) && $student->status === 'pending'): ?> bg-amber-100 text-amber-700
                                    <?php elseif(isset($student) && $student->status === 'approved'): ?> bg-blue-100 text-blue-700
                                    <?php elseif(isset($student) && $student->status === 'rejected'): ?> bg-rose-100 text-rose-700
                                    <?php else: ?> bg-slate-100 text-slate-700 <?php endif; ?>">
                                    <?php echo e($student->status ?? 'pending'); ?>

                                </span>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50">
                                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Member Since</p>
                                <p class="text-lg font-bold text-slate-900">
                                    <?php if(isset($student) && $student->created_at): ?>
                                        <?php echo e($student->created_at->format('M d, Y')); ?>

                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documents Tab -->
                <div x-show="activeTab === 'documents'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
                    <!-- Document Upload Section -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-bold text-slate-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Required Documents
                            </h3>
                            <span class="text-xs text-slate-500 bg-slate-100 px-3 py-1 rounded-full">Max 5MB per file</span>
                        </div>

                        <?php
                            $documents = [
                                'birth_certificate' => ['label' => 'Birth Certificate', 'required' => true, 'icon' => 'fa-file-medical'],
                                'report_card' => ['label' => 'Report Card / Form 138', 'required' => true, 'icon' => 'fa-file-alt'],
                                'good_moral' => ['label' => 'Certificate of Good Moral', 'required' => true, 'icon' => 'fa-certificate'],
                                'transfer_credential' => ['label' => 'Transfer Credentials (for transferees)', 'required' => false, 'icon' => 'fa-file-signature'],
                            ];
                        ?>

                        <div class="space-y-4">
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $doc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $hasFile = isset($student) && $student->{$field . '_path'};
                                    $filePath = $student->{$field . '_path'} ?? null;
                                ?>
                                <div class="border border-slate-200 rounded-xl p-4 <?php echo e($hasFile ? 'bg-emerald-50/30 border-emerald-200' : 'bg-slate-50'); ?>">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl <?php echo e($hasFile ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-200 text-slate-500'); ?> flex items-center justify-center flex-shrink-0">
                                            <i class="fas <?php echo e($doc['icon']); ?> text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-semibold text-slate-900"><?php echo e($doc['label']); ?></h4>
                                                <?php if($doc['required']): ?>
                                                    <span class="text-[10px] bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full font-medium">Required</span>
                                                <?php else: ?>
                                                    <span class="text-[10px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full font-medium">Optional</span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if($hasFile): ?>
                                                <div class="flex items-center gap-3 mt-2">
                                                    <span class="text-sm text-emerald-600 font-medium flex items-center gap-1">
                                                        <i class="fas fa-check-circle"></i>
                                                        Uploaded
                                                    </span>
                                                    <button @click="openDocumentModal('<?php echo e(route('student.profile.document.view', $field)); ?>', <?php echo e(json_encode($doc['label'])); ?>, '<?php echo e(strtolower(pathinfo($filePath, PATHINFO_EXTENSION))); ?>')" 
                                                            class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                                        View File
                                                    </button>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-sm text-slate-500 mt-1">No file uploaded yet</p>
                                            <?php endif; ?>
                                        </div>
                                        <form action="<?php echo e(route('student.profile.document', $field)); ?>" method="POST" enctype="multipart/form-data" class="flex-shrink-0" id="doc-form-<?php echo e($field); ?>">
                                            <?php echo csrf_field(); ?>
                                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl font-medium text-sm transition-all duration-200 <?php echo e($hasFile ? 'bg-white text-indigo-600 border border-indigo-200 hover:bg-indigo-50' : 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-200'); ?>" id="doc-label-<?php echo e($field); ?>">
                                                <i class="fas <?php echo e($hasFile ? 'fa-sync-alt' : 'fa-upload'); ?>" id="doc-icon-<?php echo e($field); ?>"></i>
                                                <span id="doc-text-<?php echo e($field); ?>"><?php echo e($hasFile ? 'Replace' : 'Upload'); ?></span>
                                                <input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="handleDocUpload(this, '<?php echo e($field); ?>')">
                                            </label>
                                        </form>
                                        <script>
                                            function handleDocUpload(input, field) {
                                                if (input.files && input.files[0]) {
                                                    // Show loading state
                                                    const label = document.getElementById('doc-label-' + field);
                                                    const icon = document.getElementById('doc-icon-' + field);
                                                    const text = document.getElementById('doc-text-' + field);
                                                    
                                                    label.classList.add('opacity-75', 'cursor-not-allowed');
                                                    icon.className = 'fas fa-spinner fa-spin';
                                                    text.textContent = 'Uploading...';
                                                    
                                                    // Submit form
                                                    document.getElementById('doc-form-' + field).submit();
                                                }
                                            }
                                        </script>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-info-circle text-amber-600"></i>
                                </div>
                                <div>
                                    <h5 class="font-semibold text-amber-800 text-sm">Document Guidelines</h5>
                                    <ul class="text-xs text-amber-700 mt-2 space-y-1">
                                        <li>• Accepted formats: PDF, JPG, JPEG, PNG</li>
                                        <li>• Maximum file size: 5MB per document</li>
                                        <li>• Ensure documents are clear and readable</li>
                                        <li>• Required documents must be uploaded for enrollment verification</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

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
            
            <form action="<?php echo e(route('student.profile.password')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                               placeholder="Enter current password">
                        <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">New Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                               placeholder="Enter new password (min 8 chars)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 outline-none transition-all"
                               placeholder="Confirm new password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <button type="button" @click="showPasswordModal = false" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">Update Password</button>
                </div>
            </form>
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
            
            <form action="<?php echo e(route('student.profile.photo')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="p-6">
                    <div class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center hover:border-indigo-400 hover:bg-indigo-50/50 transition-all cursor-pointer relative" onclick="document.getElementById('photo-input').click()">
                        <input type="file" id="photo-input" name="photo" accept="image/*" class="hidden" @change="fileName = $event.target.files[0]?.name">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="font-medium text-slate-900 mb-1" x-text="fileName || 'Click to upload or drag and drop'"></p>
                        <p class="text-sm text-slate-500">PNG, JPG or GIF (max. 2MB)</p>
                    </div>
                </div>
                
                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <button type="button" @click="showPhotoModal = false" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-xl font-medium hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">Upload Photo</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function profileApp() {
            return {
                activeTab: 'personal',
                editMode: false,
                showPasswordModal: false,
                showDeleteModal: false,
                showPhotoModal: false,
                fileName: '',
                toast: { show: false, message: '', type: 'success', progress: 100, duration: 3000 },
                documentModal: { 
                    open: false, 
                    url: '', 
                    title: '', 
                    fileType: '' 
                },
                sidebarCollapsed: false,
                sidebarMobileOpen: false,
                mobileOpen: false,
                isMobile: window.innerWidth < 1024,
                formData: {
                    // User data
                    first_name: <?php echo json_encode($student->user->first_name ?? '', 15, 512) ?>,
                    last_name: <?php echo json_encode($student->user->last_name ?? '', 15, 512) ?>,
                    email: <?php echo json_encode($student->user->email ?? '', 15, 512) ?>,
                    // Student data
                    lrn: <?php echo json_encode($student->lrn ?? '', 15, 512) ?>,
                    birthdate: <?php echo json_encode($student->birthdate ?? '', 15, 512) ?>,
                    birth_place: <?php echo json_encode($student->birth_place ?? '', 15, 512) ?>,
                    gender: <?php echo json_encode($student->gender ?? '', 15, 512) ?>,
                    nationality: <?php echo json_encode($student->nationality ?? '', 15, 512) ?>,
                    religion: <?php echo json_encode($student->religion ?? '', 15, 512) ?>,
                    father_name: <?php echo json_encode($student->father_name ?? '', 15, 512) ?>,
                    father_occupation: <?php echo json_encode($student->father_occupation ?? '', 15, 512) ?>,
                    mother_name: <?php echo json_encode($student->mother_name ?? '', 15, 512) ?>,
                    mother_occupation: <?php echo json_encode($student->mother_occupation ?? '', 15, 512) ?>,
                    guardian_name: <?php echo json_encode($student->guardian_name ?? '', 15, 512) ?>,
                    guardian_relationship: <?php echo json_encode($student->guardian_relationship ?? '', 15, 512) ?>,
                    guardian_contact: <?php echo json_encode($student->guardian_contact ?? '', 15, 512) ?>,
                    street_address: <?php echo json_encode($student->street_address ?? '', 15, 512) ?>,
                    barangay: <?php echo json_encode($student->barangay ?? '', 15, 512) ?>,
                    city: <?php echo json_encode($student->city ?? '', 15, 512) ?>,
                    province: <?php echo json_encode($student->province ?? '', 15, 512) ?>,
                    zip_code: <?php echo json_encode($student->zip_code ?? '', 15, 512) ?>
                },
                passwordForm: {
                    current: '',
                    new: '',
                    confirm: ''
                },
                get mainContentClass() {
                    // Mobile view
                    if (this.isMobile) {
                        return this.sidebarMobileOpen ? 'ml-72' : 'ml-0';
                    }
                    // Desktop view
                    return 'lg:ml-72';
                },
                init() {
                    // Restore active tab from localStorage (for after form submissions)
                    const savedTab = localStorage.getItem('profileActiveTab');
                    if (savedTab) {
                        this.activeTab = savedTab;
                    }
                    
                    // Watch for tab changes and save to localStorage
                    this.$watch('activeTab', value => {
                        localStorage.setItem('profileActiveTab', value);
                    });
                    
                    // Handle session flash messages
                    <?php if(session('success')): ?>
                        this.showToast('<?php echo e(session('success')); ?>', 'success');
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        this.showToast('<?php echo e(session('error')); ?>', 'error');
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        this.showToast('<?php echo e($errors->first()); ?>', 'error');
                    <?php endif; ?>
                    
                    // Check initial sidebar state from localStorage
                    this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                    
                    // Listen for sidebar toggle events
                    window.addEventListener('sidebar-toggle', (e) => {
                        this.sidebarCollapsed = e.detail.collapsed;
                    });
                    
                    // Listen for mobile menu toggle
                    window.addEventListener('sidebar-mobile-toggle', (e) => {
                        this.sidebarMobileOpen = e.detail.open;
                    });

                    // Handle resize
                    window.addEventListener('resize', () => {
                        this.isMobile = window.innerWidth < 1024;
                    });

                    // Watch for localStorage changes (in case sidebar updates it)
                    window.addEventListener('storage', (e) => {
                        if (e.key === 'sidebarCollapsed') {
                            this.sidebarCollapsed = e.newValue === 'true';
                        }
                    });
                },
                saveProfile() {
                    // TODO: Implement actual save via fetch/AJAX to your endpoint
                    // Example:
                    // fetch('<?php echo e(route("student.profile.update")); ?>', {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    //     },
                    //     body: JSON.stringify(this.formData)
                    // })
                    
                    this.editMode = false;
                    this.showToast('Profile updated successfully!', 'success');
                },
                // Password form is submitted via standard form POST to student.profile.password route
                openDocumentModal(url, title, fileType) {
                    // Close first to reset
                    this.documentModal.open = false;
                    this.documentModal.url = '';
                    
                    // Small delay to allow DOM to reset
                    setTimeout(() => {
                        this.documentModal = {
                            open: true,
                            url: url + '?t=' + Date.now(), // Add cache buster
                            title: title,
                            fileType: fileType.toLowerCase()
                        };
                    }, 10);
                    
                    // Prevent body scroll when modal is open
                    document.body.style.overflow = 'hidden';
                },
                closeDocumentModal() {
                    this.documentModal.open = false;
                    this.documentModal.url = '';
                    this.documentModal.title = '';
                    this.documentModal.fileType = '';
                    // Restore body scroll
                    document.body.style.overflow = '';
                },
                showToast(message, type = 'success', duration = 3000) {
                    // Clear any existing timeout
                    if (this.toast.timeout) {
                        clearTimeout(this.toast.timeout);
                    }
                    
                    // Set toast with initial progress
                    this.toast = { 
                        show: true, 
                        message, 
                        type, 
                        progress: 100, 
                        duration: duration 
                    };
                    
                    // Animate progress bar
                    setTimeout(() => {
                        this.toast.progress = 0;
                    }, 50);
                    
                    // Auto hide after duration
                    this.toast.timeout = setTimeout(() => {
                        this.toast.show = false;
                    }, duration);
                }
            }
        }
    </script>

    <!-- Document Viewer Modal -->
    <div x-show="documentModal.open" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto"
         aria-labelledby="modal-title" 
         role="dialog" 
         aria-modal="true">
        
        <!-- Backdrop -->
        <div x-show="documentModal.open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm transition-opacity"
             @click="closeDocumentModal()"></div>

        <!-- Modal Panel -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div x-show="documentModal.open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                
                <!-- Header -->
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="fas fa-file-alt text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900" x-text="documentModal.title"></h3>
                            <p class="text-sm text-slate-500">Document Viewer</p>
                        </div>
                    </div>
                    <button @click="closeDocumentModal()" 
                            class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-slate-600 hover:border-slate-300 flex items-center justify-center transition-all">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Document Content -->
                <div class="bg-slate-100 p-4">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden" style="height: 600px;">
                        <!-- PDF Viewer -->
                        <div x-show="documentModal.fileType === 'pdf'" class="w-full h-full">
                            <iframe :src="documentModal.url" 
                                    :key="documentModal.url"
                                    class="w-full h-full" 
                                    type="application/pdf"
                                    style="border: none;"></iframe>
                        </div>
                        
                        <!-- Image Viewer -->
                        <div x-show="['jpg', 'jpeg', 'png'].includes(documentModal.fileType)" 
                             class="w-full h-full flex items-center justify-center bg-slate-50 p-4">
                            <img :src="documentModal.url" 
                                 :key="documentModal.url"
                                 class="max-w-full max-h-full object-contain rounded-lg shadow-lg"
                                 :alt="documentModal.title">
                        </div>
                        
                        <!-- Unsupported File Type -->
                        <div x-show="documentModal.fileType && !['pdf', 'jpg', 'jpeg', 'png'].includes(documentModal.fileType)"
                             class="w-full h-full flex flex-col items-center justify-center p-8">
                                <div class="w-20 h-20 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mb-4">
                                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-semibold text-slate-900 mb-2">Unsupported File Type</h4>
                                <p class="text-slate-500 text-center mb-4">This file type cannot be previewed. Please download the file to view it.</p>
                                <a :href="documentModal.url" 
                                   download
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
                                    <i class="fas fa-download"></i>
                                    Download File
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                    <div class="text-sm text-slate-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Use browser zoom (Ctrl +/-) to resize document
                    </div>
                    <div class="flex gap-2">
                        <button @click="closeDocumentModal()" 
                                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50 transition-all">
                            Close
                        </button>
                        <a :href="documentModal.url" 
                           download
                           class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all">
                            <i class="fas fa-download"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
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
            
            <div class="px-6 py-4 bg-rose-50 border-b border-rose-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-rose-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Delete Account
                </h3>
                <button @click="showDeleteModal = false" class="text-rose-400 hover:text-rose-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <form action="<?php echo e(route('student.profile.delete')); ?>" method="POST" class="block">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="p-6 space-y-4">
                    <div class="bg-rose-50 border border-rose-100 rounded-xl p-4">
                        <p class="text-sm text-rose-700 leading-relaxed">
                            <strong class="text-rose-800">Warning:</strong> This action cannot be undone. Deleting your account will permanently remove all your data, including profile information, enrollment records, grades, and uploaded documents.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">
                            Enter your password to confirm
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:border-rose-500 focus:ring-2 focus:ring-rose-200 outline-none transition-all"
                               placeholder="Your current password">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-rose-500 text-sm mt-1"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="px-6 py-4 bg-slate-50 flex gap-3">
                    <button type="button" @click="showDeleteModal = false" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-medium hover:bg-slate-100 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 px-4 py-2.5 bg-rose-600 text-white rounded-xl font-medium hover:bg-rose-700 transition-colors shadow-lg shadow-rose-200">Delete Account</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html><?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views/student/profile/index.blade.php ENDPATH**/ ?>