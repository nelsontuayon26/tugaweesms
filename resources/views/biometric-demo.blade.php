<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    @include('partials.pwa-meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Face ID / Fingerprint / Passkey Demo</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-md mx-auto p-6">
        
        <!-- Header -->
        <div class="text-center mb-8 pt-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-fingerprint text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Face ID / Fingerprint / Passkey Authentication</h1>
            <p class="text-slate-600 mt-2">Secure login with Face ID, Fingerprint, or Passkey</p>
        </div>

        @auth
            <!-- Logged In - Show Setup -->
            <div class="mb-6">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                        <div>
                            <p class="font-medium text-green-800">Logged in as {{ auth()->user()->first_name }}</p>
                            <p class="text-sm text-green-600">You can set up Face ID, Fingerprint, or Passkey login</p>
                        </div>
                    </div>
                </div>

                @include('components.biometric-setup')
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-semibold text-slate-800 mb-4">Test Authentication</h3>
                <button onclick="testBiometricAuth()" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center space-x-2">
                    <i class="fas fa-fingerprint"></i>
                    <span>Test Face ID / Fingerprint / Passkey Login</span>
                </button>
            </div>

            <div class="mt-6 text-center">
                <a href="/pwa-settings" class="text-blue-600 hover:text-blue-700 font-medium">
                    Go to PWA Settings →
                </a>
            </div>
        @else
            <!-- Not Logged In - Show Login Options -->
            <div class="space-y-4">
                <a href="/login" 
                   class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition text-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login with Password
                </a>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-slate-50 text-slate-500">or</span>
                    </div>
                </div>

                <button onclick="biometricLogin()" 
                        class="w-full bg-white border-2 border-slate-300 hover:border-blue-500 text-slate-700 hover:text-blue-600 font-semibold py-3 rounded-lg transition flex items-center justify-center space-x-2">
                    <i class="fas fa-fingerprint text-xl"></i>
                    <span>Login with Face ID, Fingerprint, or Passkey</span>
                </button>
            </div>

            <div class="mt-8 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-amber-800">Note</p>
                        <p class="text-sm text-amber-600">
                            Face ID, Fingerprint, or Passkey login must be set up while logged in with your password first.
                        </p>
                    </div>
                </div>
            </div>
        @endauth

        <!-- Status Display -->
        <div id="status-display" class="mt-6 hidden">
            <div class="p-4 rounded-lg" id="status-content"></div>
        </div>
    </div>

    <script>
        async function testBiometricAuth() {
            showStatus('Testing Face ID / Fingerprint / Passkey authentication...', 'info');
            
            try {
                const result = await window.authenticateWithBiometric('{{ auth()->user()->username ?? '' }}');
                showStatus('✓ Face ID / Fingerprint / Passkey authentication successful!', 'success');
                console.log('Auth result:', result);
            } catch (error) {
                showStatus('✗ ' + error.message, 'error');
            }
        }

        async function biometricLogin() {
            const username = prompt('Enter your username:');
            if (!username || !username.trim()) {
                showStatus('Username is required for Face ID / Fingerprint / Passkey login.', 'error');
                return;
            }

            showStatus('Initiating Face ID / Fingerprint / Passkey login...', 'info');
            
            try {
                const result = await window.authenticateWithBiometric(username.trim());
                showStatus('Login successful! Redirecting...', 'success');
                
                if (result.redirect) {
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1000);
                }
            } catch (error) {
                showStatus('Login failed: ' + error.message, 'error');
            }
        }

        function showStatus(message, type) {
            const display = document.getElementById('status-display');
            const content = document.getElementById('status-content');
            
            display.classList.remove('hidden');
            content.className = 'p-4 rounded-lg ' + (
                type === 'success' ? 'bg-green-50 border border-green-200 text-green-800' :
                type === 'error' ? 'bg-red-50 border border-red-200 text-red-800' :
                'bg-blue-50 border border-blue-200 text-blue-800'
            );
            content.textContent = message;
        }
    </script>
</body>
</html>
