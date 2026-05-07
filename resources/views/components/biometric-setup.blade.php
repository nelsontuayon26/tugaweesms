{{-- Biometric Authentication Setup Component --}}
<div x-data="biometricSetup()" x-init="init()" class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-slate-800">
            <i class="fas fa-fingerprint text-blue-500 mr-2"></i>
            Face ID / Fingerprint / Passkey Login
        </h3>
        <span x-show="isAvailable" class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
            Available
        </span>
        <span x-show="!isAvailable && !checking" class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
            Not Available
        </span>
    </div>

    {{-- Loading State --}}
    <div x-show="checking" class="text-center py-8">
        <x-inline-spinner size="xl" />
        <p class="text-slate-600 mt-3">Checking device compatibility...</p>
    </div>

    {{-- Not Available Message --}}
    <div x-show="!isAvailable && !checking" class="text-center py-6">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-fingerprint text-3xl text-slate-400"></i>
        </div>
        <p class="text-slate-600 mb-2">Face ID, Fingerprint, or Passkey authentication is not available on this device.</p>
        <p class="text-sm text-slate-500">
            Requires a device with Face ID, Touch ID, or fingerprint sensor, and a compatible browser.
        </p>
    </div>

    {{-- Available - Setup Options --}}
    <div x-show="isAvailable && !checking">
        {{-- Already Registered --}}
        <div x-show="hasCredentials" class="space-y-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            Face ID / Fingerprint / Passkey login is active
                        </p>
                        <p class="text-sm text-green-600">
                            You can now log in using Face ID, Fingerprint, or Passkey
                        </p>
                    </div>
                </div>
            </div>

            {{-- Registered Devices --}}
            <div>
                <h4 class="text-sm font-semibold text-slate-700 mb-2">Registered Devices</h4>
                <div class="space-y-2">
                    <template x-for="cred in credentials" :key="cred.id">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800" x-text="cred.device_name"></p>
                                    <p class="text-xs text-slate-500">
                                        Last used: <span x-text="cred.last_used"></span>
                                    </p>
                                </div>
                            </div>
                            <button @click="removeCredential(cred.id)" 
                                    :disabled="removing === cred.id"
                                    class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition">
                                <svg x-show="removing === cred.id" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <i x-show="removing !== cred.id" class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Add Another Device --}}
            <button @click="startRegistration()" 
                    :disabled="registering"
                    class="w-full py-3 border-2 border-blue-200 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition flex items-center justify-center space-x-2">
                <svg x-show="registering" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i x-show="!registering" class="fas fa-plus"></i>
                <span x-text="registering ? 'Setting up...' : 'Register Another Device'"></span>
            </button>
        </div>

        {{-- Not Registered Yet --}}
        <div x-show="!hasCredentials">
            <p class="text-slate-600 mb-4">
                Set up Face ID, Fingerprint, or Passkey authentication to log in quickly and securely.
            </p>

            <div class="space-y-3">
                <div class="flex items-center space-x-3 text-sm text-slate-600">
                    <i class="fas fa-check text-green-500"></i>
                    <span>Log in without typing your password</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-slate-600">
                    <i class="fas fa-check text-green-500"></i>
                    <span>Enhanced security with device biometrics or passkeys</span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-slate-600">
                    <i class="fas fa-check text-green-500"></i>
                    <span>Works offline after first setup</span>
                </div>
            </div>

            <button @click="startRegistration()" 
                    :disabled="registering"
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center space-x-2">
                <svg x-show="registering" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <i x-show="!registering" class="fas fa-fingerprint text-xl"></i>
                <span x-text="registering ? 'Setting up...' : 'Set Up Face ID / Fingerprint / Passkey Login'"></span>
            </button>
        </div>
    </div>

    {{-- Registration Modal --}}
    <div x-show="showRegistrationModal" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
         x-cloak>
        <div class="bg-white rounded-2xl p-6 w-full max-w-sm text-center shadow-2xl">
            <div class="w-20 h-20 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 animate-pulse">
                <i class="fas fa-fingerprint text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Verify Your Identity</h3>
            <p class="text-slate-600 mb-4">
                Use your device's Face ID, fingerprint sensor, or passkey to complete setup.
            </p>
            <div class="flex items-center justify-center space-x-2 text-sm text-slate-500">
                <i class="fas fa-shield-alt text-green-500"></i>
                <span>Secure & encrypted</span>
            </div>
        </div>
    </div>

    {{-- Error Modal --}}
    <div x-show="errorMessage" 
         x-transition
         class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg"
         x-cloak>
        <div class="flex items-start space-x-3">
            <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
            <div>
                <p class="font-medium text-red-800">Setup Failed</p>
                <p class="text-sm text-red-600" x-text="errorMessage"></p>
            </div>
            <button @click="errorMessage = null" class="ml-auto text-red-400 hover:text-red-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>

<script>
function biometricSetup() {
    return {
        isAvailable: false,
        checking: true,
        hasCredentials: false,
        credentials: [],
        registering: false,
        removing: null,
        showRegistrationModal: false,
        errorMessage: null,

        async init() {
            await this.checkAvailability();
        },

        async checkAvailability() {
            this.checking = true;
            
            try {
                // Check if biometric auth is available
                if (window.biometricAuth) {
                    this.isAvailable = await window.biometricAuth.checkAvailability();
                    
                    if (this.isAvailable) {
                        await this.loadCredentials();
                    }
                } else {
                    this.isAvailable = false;
                }
            } catch (error) {
                console.error('Error checking biometric availability:', error);
                this.isAvailable = false;
            } finally {
                this.checking = false;
            }
        },

        async loadCredentials() {
            try {
                if (window.biometricAuth) {
                    this.credentials = await window.biometricAuth.getCredentials();
                    this.hasCredentials = this.credentials.length > 0;
                }
            } catch (error) {
                console.error('Error loading credentials:', error);
            }
        },

        async startRegistration() {
            this.registering = true;
            this.errorMessage = null;
            this.showRegistrationModal = true;

            try {
                const result = await window.biometricAuth.register();
                
                if (result.success) {
                    await this.loadCredentials();
                    this.showToast('Face ID / Fingerprint / Passkey login set up successfully!', 'success');
                }
            } catch (error) {
                console.error('Registration error:', error);
                this.errorMessage = error.message || 'Failed to set up Face ID / Fingerprint / Passkey authentication';
            } finally {
                this.registering = false;
                this.showRegistrationModal = false;
            }
        },

        async removeCredential(id) {
            if (!confirm('Remove this device? You will need to set up Face ID / Fingerprint / Passkey login again.')) {
                return;
            }

            this.removing = id;

            try {
                await window.biometricAuth.removeCredential(id);
                await this.loadCredentials();
                this.showToast('Device removed successfully', 'success');
            } catch (error) {
                console.error('Error removing credential:', error);
                this.showToast('Failed to remove device', 'error');
            } finally {
                this.removing = null;
            }
        },

        showToast(message, type = 'info') {
            if (window.showToast) {
                window.showToast(message, type);
            } else {
                // Simple fallback
                const div = document.createElement('div');
                div.className = `fixed bottom-4 right-4 px-4 py-3 rounded-lg text-white z-50 ${
                    type === 'success' ? 'bg-green-500' :
                    type === 'error' ? 'bg-red-500' : 'bg-blue-500'
                }`;
                div.textContent = message;
                document.body.appendChild(div);
                setTimeout(() => div.remove(), 3000);
            }
        }
    };
}
</script>
