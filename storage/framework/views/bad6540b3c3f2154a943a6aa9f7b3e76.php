<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php echo $__env->make('partials.pwa-meta', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Take Attendance - <?php echo e($section->name); ?></title>
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            background: #f1f5f9;
            overscroll-behavior: none;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .touch-manipulation {
            touch-action: manipulation;
        }
        
        /* iOS safe area support */
        .safe-top {
            padding-top: env(safe-area-inset-top);
        }
        .safe-bottom {
            padding-bottom: env(safe-area-inset-bottom);
        }
        
        /* Smooth transitions */
        [x-cloak] { display: none !important; }
        
        /* Custom checkbox animation */
        @keyframes checkPop {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .check-pop {
            animation: checkPop 0.2s ease;
        }
    </style>
</head>
<body class="antialiased">
    <div x-data="mobileAttendance(<?php echo e($section->id); ?>, <?php echo e(json_encode($students)); ?>)" 
         x-init="init()"
         class="min-h-screen bg-slate-50 pb-28"
         :class="{ 'opacity-75': saving }">
        
        
        <div class="sticky top-0 z-30 bg-white border-b border-slate-200 safe-top">
            <div class="px-4 py-3">
                
                <div class="flex items-center justify-between mb-3">
                    <a href="<?php echo e(route('teacher.sections.attendance', $section)); ?>" 
                       class="flex items-center justify-center w-10 h-10 -ml-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-full transition">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                    <h1 class="text-lg font-bold text-slate-800">Take Attendance</h1>
                    <div class="text-right min-w-[80px]">
                        <p class="text-sm font-medium text-slate-700" x-text="formattedDate"></p>
                        <p class="text-xs text-slate-500 truncate max-w-[100px]"><?php echo e($section->name); ?></p>
                    </div>
                </div>

                
                <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Present</p>
                            <p class="text-2xl font-bold text-green-600" x-text="stats.present"></p>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Absent</p>
                            <p class="text-2xl font-bold text-red-600" x-text="stats.absent"></p>
                        </div>
                        <div class="w-px h-8 bg-slate-200"></div>
                        <div class="text-center">
                            <p class="text-xs text-slate-500 uppercase tracking-wide">Late</p>
                            <p class="text-2xl font-bold text-amber-600" x-text="stats.late"></p>
                        </div>
                    </div>
                    <div class="text-center pl-2 border-l border-slate-200">
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Total</p>
                        <p class="text-2xl font-bold text-slate-700" x-text="students.length"></p>
                    </div>
                </div>
            </div>

            
            <div class="px-4 pb-3 flex space-x-2 overflow-x-auto scrollbar-hide">
                <button @click="markAll('present')" 
                        class="flex-shrink-0 bg-green-100 hover:bg-green-200 active:bg-green-300 text-green-700 px-4 py-2.5 rounded-full text-sm font-semibold transition flex items-center space-x-2 touch-manipulation">
                    <i class="fas fa-check-circle"></i>
                    <span>All Present</span>
                </button>
                <button @click="markAll('absent')" 
                        class="flex-shrink-0 bg-red-100 hover:bg-red-200 active:bg-red-300 text-red-700 px-4 py-2.5 rounded-full text-sm font-semibold transition flex items-center space-x-2 touch-manipulation">
                    <i class="fas fa-times-circle"></i>
                    <span>All Absent</span>
                </button>
                <button @click="resetAll()" 
                        class="flex-shrink-0 bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 px-4 py-2.5 rounded-full text-sm font-semibold transition flex items-center space-x-2 touch-manipulation">
                    <i class="fas fa-undo"></i>
                    <span>Reset</span>
                </button>
            </div>

            
            <div x-show="!isOnline" 
                 x-transition
                 class="bg-amber-50 border-t border-amber-200 px-4 py-2.5 flex items-center space-x-3">
                <i class="fas fa-wifi-slash text-amber-500 text-lg"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-amber-800">Working Offline</p>
                    <p class="text-xs text-amber-600">Changes will sync automatically when you're back online</p>
                </div>
            </div>

            
            <div class="px-4 py-3 border-t border-slate-200">
                <?php echo $__env->make('components.location-verifier', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>

        
        <div class="px-4 py-4 space-y-3">
            <template x-for="(student, index) in students" :key="student.id">
                <div class="bg-white rounded-2xl shadow-sm border-2 p-4 transition-all duration-200"
                     :class="{ 
                         'border-green-500 bg-green-50/30': student.status === 'present',
                         'border-red-500 bg-red-50/30': student.status === 'absent',
                         'border-amber-500 bg-amber-50/30': student.status === 'late',
                         'border-slate-200': !student.status
                     }">
                    <div class="flex items-center justify-between">
                        
                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-lg"
                                     :class="student.photo ? '' : 'bg-gradient-to-br from-blue-500 to-purple-600'">
                                    <img x-show="student.photo" :src="student.photo" class="w-full h-full rounded-full object-cover">
                                    <span x-show="!student.photo" x-text="getInitials(student.name)"></span>
                                </div>
                                <div x-show="student.status === 'present'" 
                                     class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-white text-xs border-2 border-white">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div x-show="student.status === 'absent'" 
                                     class="absolute -bottom-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs border-2 border-white">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div x-show="student.status === 'late'" 
                                     class="absolute -bottom-1 -right-1 w-6 h-6 bg-amber-500 rounded-full flex items-center justify-center text-white text-xs border-2 border-white">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-base" x-text="student.name"></p>
                                <p class="text-sm text-slate-500 font-mono" x-text="student.lrn"></p>
                            </div>
                        </div>

                        
                        <div class="flex space-x-2">
                            <button @click="student.status = 'present'; $event.target.classList.add('check-pop'); setTimeout(() => $event.target.classList.remove('check-pop'), 200)"
                                    :class="student.status === 'present' ? 'bg-green-500 text-white shadow-green-200' : 'bg-slate-100 text-slate-600'"
                                    class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center transition-all shadow-sm active:scale-95 touch-manipulation">
                                <i class="fas fa-check text-lg mb-0.5"></i>
                                <span class="text-[10px] font-bold">P</span>
                            </button>
                            <button @click="student.status = 'absent'; $event.target.classList.add('check-pop'); setTimeout(() => $event.target.classList.remove('check-pop'), 200)"
                                    :class="student.status === 'absent' ? 'bg-red-500 text-white shadow-red-200' : 'bg-slate-100 text-slate-600'"
                                    class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center transition-all shadow-sm active:scale-95 touch-manipulation">
                                <i class="fas fa-times text-lg mb-0.5"></i>
                                <span class="text-[10px] font-bold">A</span>
                            </button>
                            <button @click="student.status = 'late'; $event.target.classList.add('check-pop'); setTimeout(() => $event.target.classList.remove('check-pop'), 200)"
                                    :class="student.status === 'late' ? 'bg-amber-500 text-white shadow-amber-200' : 'bg-slate-100 text-slate-600'"
                                    class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center transition-all shadow-sm active:scale-95 touch-manipulation">
                                <i class="fas fa-clock text-lg mb-0.5"></i>
                                <span class="text-[10px] font-bold">L</span>
                            </button>
                        </div>
                    </div>

                    
                    <div x-show="student.status === 'absent' || student.status === 'late'" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="mt-3 pt-3 border-t border-slate-200/50">
                        <input type="text" 
                               x-model="student.remarks"
                               placeholder="Reason/remarks (optional)..."
                               class="w-full text-sm bg-white border-slate-200 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400">
                    </div>
                </div>
            </template>
        </div>

        
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 safe-bottom z-40">
            <div class="max-w-lg mx-auto flex space-x-3">
                <button @click="save()" 
                        :disabled="saving || !hasChanges"
                        :class="(saving || !hasChanges) ? 'opacity-50 cursor-not-allowed' : 'active:scale-95'"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-xl transition shadow-lg shadow-blue-200 flex items-center justify-center space-x-2 touch-manipulation">
                    <svg x-show="saving" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                        <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <i x-show="!saving" class="fas fa-save text-lg"></i>
                    <span x-text="saving ? 'Saving...' : 'Save Attendance'"></span>
                </button>
                
                
                <button @click="showDatePicker = !showDatePicker"
                        class="bg-slate-100 hover:bg-slate-200 active:bg-slate-300 text-slate-700 px-4 rounded-xl transition touch-manipulation">
                    <i class="fas fa-calendar-alt text-lg"></i>
                </button>
            </div>
        </div>

        
        <div x-show="showDatePicker" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             @click.self="showDatePicker = false"
             x-cloak>
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm shadow-2xl">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Select Date</h3>
                <input type="date" 
                       x-model="date"
                       @change="showDatePicker = false"
                       class="w-full border-slate-300 rounded-xl text-lg p-3 mb-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button @click="showDatePicker = false"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold py-3 rounded-xl transition touch-manipulation">
                    Cancel
                </button>
            </div>
        </div>

        
        <div x-show="showSuccess" 
             x-transition
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             x-cloak>
            <div class="bg-white rounded-2xl p-6 w-full max-w-sm text-center shadow-2xl">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Saved!</h3>
                <p class="text-slate-600 mb-6" x-text="successMessage"></p>
                <button @click="showSuccess = false"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition touch-manipulation">
                    Continue
                </button>
            </div>
        </div>

        
        <div id="toast-container" class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 space-y-2 pointer-events-none"></div>
    </div>

    <script>
        function mobileAttendance(sectionId, initialStudents) {
            return {
                sectionId: sectionId,
                students: initialStudents.map(s => ({ ...s, status: s.status || 'present', remarks: s.remarks || '' })),
                date: new Date().toISOString().split('T')[0],
                isOnline: navigator.onLine,
                saving: false,
                hasChanges: false,
                showDatePicker: false,
                showSuccess: false,
                successMessage: '',
                originalState: null,

                get formattedDate() {
                    return new Date(this.date).toLocaleDateString('en-US', {
                        weekday: 'short',
                        month: 'short',
                        day: 'numeric'
                    });
                },

                get stats() {
                    return {
                        present: this.students.filter(s => s.status === 'present').length,
                        absent: this.students.filter(s => s.status === 'absent').length,
                        late: this.students.filter(s => s.status === 'late').length
                    };
                },

                init() {
                    this.originalState = JSON.stringify(this.students);
                    
                    this.$watch('students', () => {
                        this.hasChanges = JSON.stringify(this.students) !== this.originalState;
                    }, { deep: true });

                    window.addEventListener('online', () => {
                        this.isOnline = true;
                        this.showToast('Back online!', 'success');
                    });
                    
                    window.addEventListener('offline', () => {
                        this.isOnline = false;
                        this.showToast('Working offline', 'warning');
                    });

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            this.showDatePicker = false;
                            this.showSuccess = false;
                        }
                    });
                },

                getInitials(name) {
                    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                },

                markAll(status) {
                    this.students.forEach(s => s.status = status);
                    this.showToast(`Marked all as ${status}`, 'info');
                },

                resetAll() {
                    this.students.forEach(s => {
                        s.status = 'present';
                        s.remarks = '';
                    });
                    this.showToast('Reset all attendance', 'info');
                },

                async save() {
                    this.saving = true;

                    const attendanceData = {
                        section_id: this.sectionId,
                        date: this.date,
                        attendance: this.students.map(s => ({
                            student_id: s.id,
                            status: s.status,
                            remarks: s.remarks
                        }))
                    };

                    try {
                        if (this.isOnline) {
                            const response = await fetch('<?php echo e(route('teacher.attendance.bulk-store')); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify(attendanceData)
                            });

                            if (!response.ok) throw new Error('Server error');
                            
                            this.originalState = JSON.stringify(this.students);
                            this.hasChanges = false;
                            this.successMessage = 'Attendance saved successfully.';
                            this.showSuccess = true;
                        } else {
                            const result = await window.queueOfflineAttendance(
                                this.sectionId, 
                                this.date, 
                                attendanceData
                            );

                            if (result.success) {
                                this.originalState = JSON.stringify(this.students);
                                this.hasChanges = false;
                                this.successMessage = 'Saved locally. Will sync when online.';
                                this.showSuccess = true;
                            } else {
                                throw new Error(result.error || 'Failed to save');
                            }
                        }
                    } catch (error) {
                        this.showToast('Error: ' + error.message, 'error');
                    } finally {
                        this.saving = false;
                    }
                },

                showToast(message, type = 'info') {
                    const container = document.getElementById('toast-container');
                    const toast = document.createElement('div');
                    
                    const colors = {
                        success: 'bg-green-500',
                        error: 'bg-red-500',
                        warning: 'bg-amber-500',
                        info: 'bg-blue-500'
                    };
                    
                    const icons = {
                        success: 'fa-check-circle',
                        error: 'fa-exclamation-circle',
                        warning: 'fa-exclamation-triangle',
                        info: 'fa-info-circle'
                    };
                    
                    toast.className = `${colors[type]} text-white px-4 py-3 rounded-xl shadow-lg flex items-center space-x-2 transform transition-all duration-300 translate-y-4 opacity-0`;
                    toast.innerHTML = `
                        <i class="fas ${icons[type]}"></i>
                        <span class="font-medium">${message}</span>
                    `;
                    
                    container.appendChild(toast);
                    
                    requestAnimationFrame(() => {
                        toast.classList.remove('translate-y-4', 'opacity-0');
                    });
                    
                    setTimeout(() => {
                        toast.classList.add('translate-y-4', 'opacity-0');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                }
            };
        }
    </script>
    
    
    <script src="/js/pwa/geolocation.js"></script>
</body>
</html>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\teacher\attendance\mobile.blade.php ENDPATH**/ ?>