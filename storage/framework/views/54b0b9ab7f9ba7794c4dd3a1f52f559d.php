<div class="<?php echo e(($modal ?? false) ? '' : 'max-w-3xl mx-auto px-4 py-8'); ?>" x-data="notificationSettings()" x-init="loadSettings()">
    <?php if(!($modal ?? false)): ?>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="<?php echo e(url()->previous()); ?>" class="p-2 text-slate-600 hover:text-indigo-600 hover:bg-white rounded-lg transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Notification Settings</h1>
            <p class="text-slate-500">Choose how you want to be notified</p>
        </div>
    </div>
    <?php endif; ?>

    <div class="space-y-6 <?php echo e(($modal ?? false) ? 'max-w-2xl mx-auto' : ''); ?>">
        <!-- Phone Number Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">
                <i class="fas fa-phone text-indigo-500 mr-2"></i>Contact Information
            </h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number (for SMS)</label>
                    <div class="flex gap-3">
                        <input type="tel" x-model="settings.phone_number" 
                               class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="+63 912 345 6789">
                        <button @click="saveSettings()" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors">
                            <i class="fas fa-save"></i>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Enter your mobile number with country code (e.g., +63 for Philippines)</p>
                </div>
            </div>
        </div>

        <!-- Email Notifications -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-envelope text-blue-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">Email Notifications</h2>
                    <p class="text-sm text-slate-500">Receive updates via email</p>
                </div>
            </div>

            <div class="space-y-4">
                <template x-for="(label, key) in emailOptions" :key="key">
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div>
                            <p class="font-medium text-slate-700" x-text="label"></p>
                            <p class="text-xs text-slate-500" x-text="descriptions[key]"></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings[key]" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </label>
                    </div>
                </template>
            </div>
        </div>

        <!-- SMS Notifications -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-sms text-emerald-600"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-800">SMS Notifications</h2>
                    <p class="text-sm text-slate-500">Receive text messages on your phone</p>
                </div>
            </div>

            <div class="space-y-4">
                <template x-for="(label, key) in smsOptions" :key="key">
                    <div class="flex items-center justify-between py-3 border-b border-slate-100 last:border-0">
                        <div>
                            <p class="font-medium text-slate-700" x-text="label"></p>
                            <p class="text-xs text-slate-500" x-text="descriptions[key]"></p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="settings[key]" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                </template>
            </div>
        </div>

        <!-- Success Message -->
        <div x-show="saved" x-transition x-cloak class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2">
            <i class="fas fa-check-circle text-emerald-600"></i>
            <span class="font-medium">Settings saved successfully!</span>
        </div>

        <!-- Save Button -->
        <div class="flex justify-end gap-3 pt-2">
            <?php if($modal ?? false): ?>
                <button type="button" @click="$dispatch('close-settings-modal')" class="px-6 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
            <?php else: ?>
                <a href="<?php echo e(url()->previous()); ?>" class="px-6 py-2.5 text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </a>
            <?php endif; ?>
            <button @click="saveSettings()" 
                    :disabled="saving"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors flex items-center gap-2 disabled:opacity-50">
                <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i class="fas fa-check" x-show="!saving"></i>
                <span x-text="saving ? 'Saving...' : 'Save Changes'"></span>
            </button>
        </div>
    </div>
</div>

<script>
function notificationSettings() {
    return {
        settings: {
            phone_number: '',
            email_new_message: true,
            email_announcement: true,
            email_grade_posted: true,
            email_attendance_alert: true,
            email_assignment_due: true,
            sms_new_message: false,
            sms_announcement: false,
            sms_grade_posted: false,
            sms_attendance_alert: true,
            sms_assignment_due: false,
        },
        saving: false,
        saved: false,
        emailOptions: {
            email_new_message: 'New Messages',
            email_announcement: 'Announcements',
            email_grade_posted: 'Grade Posted',
            email_attendance_alert: 'Attendance Alerts',
            email_assignment_due: 'Assignment Due',
        },
        smsOptions: {
            sms_new_message: 'New Messages',
            sms_announcement: 'Announcements',
            sms_grade_posted: 'Grade Posted',
            sms_attendance_alert: 'Attendance Alerts',
            sms_assignment_due: 'Assignment Due',
        },
        descriptions: {
            email_new_message: 'When someone sends you a message',
            email_announcement: 'New school announcements',
            email_grade_posted: 'When grades are published',
            email_attendance_alert: 'Absence or tardiness notifications',
            email_assignment_due: 'Reminders before due dates',
            sms_new_message: 'When someone sends you a message',
            sms_announcement: 'Important school announcements',
            sms_grade_posted: 'When grades are published',
            sms_attendance_alert: 'Urgent: Child absence notifications',
            sms_assignment_due: 'Assignment due reminders',
        },

        async loadSettings() {
            try {
                const response = await fetch('<?php echo e(route('notifications.settings')); ?>', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await response.json();
                this.settings = { ...this.settings, ...data };
            } catch (error) {
                console.error('Failed to load settings:', error);
            }
        },

        async saveSettings() {
            this.saving = true;
            try {
                const response = await fetch('<?php echo e(route('notifications.settings.update')); ?>', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.settings)
                });
                
                if (response.ok) {
                    this.saved = true;
                    this.playSound('success');
                    // Scroll modal or page to top so the success banner is visible
                    const scrollContainer = this.$el.closest('.overflow-y-auto');
                    if (scrollContainer) {
                        scrollContainer.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                    setTimeout(() => this.saved = false, 3000);
                } else {
                    this.playSound('error');
                    alert('Failed to save settings. Please try again.');
                }
            } catch (error) {
                console.error('Failed to save settings:', error);
                this.playSound('error');
                alert('Failed to save settings. Please try again.');
            }
            this.saving = false;
        },

        playSound(type) {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const ctx = new AudioContext();
                const now = ctx.currentTime;

                if (type === 'success') {
                    // Pleasant ascending major chord (C5 - E5 - G5)
                    const notes = [523.25, 659.25, 783.99];
                    notes.forEach((freq, i) => {
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain);
                        gain.connect(ctx.destination);
                        osc.type = 'sine';
                        osc.frequency.value = freq;
                        gain.gain.setValueAtTime(0, now + i * 0.05);
                        gain.gain.linearRampToValueAtTime(0.15, now + i * 0.05 + 0.03);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.05 + 0.4);
                        osc.start(now + i * 0.05);
                        osc.stop(now + i * 0.05 + 0.4);
                    });
                } else if (type === 'error') {
                    // Low descending error tone
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();
                    osc.connect(gain);
                    gain.connect(ctx.destination);
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(150, now);
                    osc.frequency.linearRampToValueAtTime(100, now + 0.3);
                    gain.gain.setValueAtTime(0.15, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.4);
                    osc.start(now);
                    osc.stop(now + 0.4);
                }
            } catch (e) {
                console.error('Failed to play sound:', e);
            }
        }
    }
}
</script>
<?php /**PATH C:\Program Files\Ampps\www\projects\capstone\tugaweesms-main\resources\views\notifications\settings-content.blade.php ENDPATH**/ ?>