/**
 * TESSMS Biometric Authentication Module
 * Uses Web Authentication API (WebAuthn) for Face ID / Fingerprint / Passkey login
 */

class BiometricAuth {
    constructor() {
        this.isAvailable = false;
        this.checkAvailability();
    }

    /**
     * Check if biometric authentication is available on this device
     */
    async checkAvailability() {
        if (!window.PublicKeyCredential) {
            console.log('[Biometric] WebAuthn not supported');
            this.isAvailable = false;
            return false;
        }

        try {
            const platformAvailable = await PublicKeyCredential.isUserVerifyingPlatformAuthenticatorAvailable();
            const conditionalAvailable = await (PublicKeyCredential.isConditionalMediationAvailable?.() || Promise.resolve(false));
            this.isAvailable = platformAvailable || conditionalAvailable;
            console.log('[Biometric] Platform auth available:', platformAvailable, '| Conditional mediation available:', conditionalAvailable);
            return this.isAvailable;
        } catch (error) {
            console.error('[Biometric] Error checking availability:', error);
            this.isAvailable = false;
            return false;
        }
    }

    /**
     * Check if user has biometric credentials registered
     */
    async hasCredentials() {
        try {
            const response = await fetch('/api/biometric/credentials', {
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                return data.credentials && data.credentials.length > 0;
            }
            return false;
        } catch (error) {
            console.error('[Biometric] Error checking credentials:', error);
            return false;
        }
    }

    /**
     * Register biometric authentication for current user
     */
    async register(deviceName = null) {
        if (!this.isAvailable) {
            throw new Error('Face ID, Fingerprint, or Passkey authentication is not available on this device');
        }

        try {
            // Get registration options from server
            const optionsResponse = await fetch('/api/biometric/register-options', {
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (!optionsResponse.ok) {
                const errorData = await optionsResponse.json().catch(() => ({}));
                throw new Error(errorData.error || 'Failed to get registration options');
            }

            const options = await optionsResponse.json();

            // Prepare options for WebAuthn
            const publicKeyCredentialCreationOptions = {
                rp: options.rp,
                user: {
                    id: this.base64UrlToBuffer(options.user.id),
                    name: options.user.name,
                    displayName: options.user.displayName
                },
                challenge: this.base64UrlToBuffer(options.challenge),
                pubKeyCredParams: options.pubKeyCredParams,
                authenticatorSelection: options.authenticatorSelection,
                attestation: options.attestation,
                timeout: options.timeout
            };

            // Handle excludeCredentials if present
            if (options.excludeCredentials && options.excludeCredentials.length > 0) {
                publicKeyCredentialCreationOptions.excludeCredentials =
                    options.excludeCredentials.map(cred => ({
                        type: cred.type,
                        id: this.base64UrlToBuffer(cred.id),
                        transports: cred.transports || []
                    }));
            }

            // Create credential
            const credential = await navigator.credentials.create({
                publicKey: publicKeyCredentialCreationOptions
            });

            if (!credential) {
                throw new Error('Credential creation was cancelled');
            }

            // Send credential to server
            const registrationData = {
                id: credential.id,
                rawId: this.bufferToBase64Url(credential.rawId),
                type: credential.type,
                response: {
                    clientDataJSON: this.bufferToBase64Url(credential.response.clientDataJSON),
                    attestationObject: this.bufferToBase64Url(credential.response.attestationObject)
                },
                device_name: deviceName || this.getDeviceName()
            };

            const registerResponse = await fetch('/api/biometric/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(registrationData)
            });

            const result = await registerResponse.json();

            if (result.success) {
                return { success: true, message: 'Face ID / Fingerprint / Passkey registered successfully' };
            } else {
                throw new Error(result.error || 'Registration failed');
            }

        } catch (error) {
            console.error('[Biometric] Registration error:', error);

            if (error.name === 'NotAllowedError') {
                throw new Error('Face ID / Fingerprint / Passkey registration was cancelled or not allowed');
            } else if (error.name === 'SecurityError') {
                throw new Error('Face ID / Fingerprint / Passkey authentication is not supported in this context. Please use HTTPS.');
            } else if (error.name === 'NotSupportedError') {
                throw new Error('This device does not support Face ID, Fingerprint, or Passkey');
            }

            throw error;
        }
    }

    /**
     * Authenticate using biometric
     * @param {string} username - The username to authenticate with
     */
    async authenticate(username = null) {
        if (!this.isAvailable) {
            throw new Error('Face ID, Fingerprint, or Passkey authentication is not available on this device');
        }

        if (!username) {
            throw new Error('Please enter your username first');
        }

        try {
            // Get authentication options from server
            const optionsResponse = await fetch(`/biometric/auth-options?username=${encodeURIComponent(username)}`, {
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (!optionsResponse.ok) {
                const errorData = await optionsResponse.json().catch(() => ({}));
                throw new Error(errorData.error || 'Failed to get authentication options');
            }

            const options = await optionsResponse.json();

            // If user hasn't set up any credentials yet, show a helpful message
            if (options.has_credentials === false) {
                throw new Error('You have not set up Face ID, Fingerprint, or Passkey login yet. Please sign in with your password first and set it up in your settings.');
            }

            // Prepare options for WebAuthn
            const publicKeyCredentialRequestOptions = {
                challenge: this.base64UrlToBuffer(options.challenge),
                timeout: options.timeout,
                rpId: options.rpId,
                userVerification: options.userVerification
            };

            // Handle allowCredentials
            if (options.allowCredentials && options.allowCredentials.length > 0) {
                publicKeyCredentialRequestOptions.allowCredentials =
                    options.allowCredentials.map(cred => ({
                        type: cred.type,
                        id: this.base64UrlToBuffer(cred.id),
                        transports: cred.transports || []
                    }));
            }

            // Get credential
            const assertion = await navigator.credentials.get({
                publicKey: publicKeyCredentialRequestOptions
            });

            if (!assertion) {
                throw new Error('Authentication was cancelled');
            }

            // Send assertion to server
            const authData = {
                id: assertion.id,
                rawId: this.bufferToBase64Url(assertion.rawId),
                type: assertion.type,
                response: {
                    authenticatorData: this.bufferToBase64Url(assertion.response.authenticatorData),
                    clientDataJSON: this.bufferToBase64Url(assertion.response.clientDataJSON),
                    signature: this.bufferToBase64Url(assertion.response.signature),
                    userHandle: assertion.response.userHandle ?
                        this.bufferToBase64Url(assertion.response.userHandle) : null
                }
            };

            const authResponse = await fetch('/biometric/authenticate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(authData)
            });

            // Handle 419 (CSRF token expired) by refreshing and retrying once
            if (authResponse.status === 419) {
                const refreshed = await this.refreshCsrfToken();
                if (refreshed) {
                    const retryResponse = await fetch('/biometric/authenticate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': this.getCsrfToken(),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(authData)
                    });
                    const retryResult = await retryResponse.json();
                    if (retryResult.success) {
                        return {
                            success: true,
                            message: 'Authentication successful',
                            redirect: retryResult.redirect,
                            user: retryResult.user
                        };
                    } else {
                        throw new Error(retryResult.error || 'Authentication failed after retry');
                    }
                }
                throw new Error('Your session expired. Please refresh the page and try again.');
            }

            const result = await authResponse.json();

            if (result.success) {
                return {
                    success: true,
                    message: 'Authentication successful',
                    redirect: result.redirect,
                    user: result.user
                };
            } else {
                throw new Error(result.error || 'Authentication failed');
            }

        } catch (error) {
            console.error('[Biometric] Authentication error:', error);

            if (error.name === 'NotAllowedError') {
                throw new Error('Face ID / Fingerprint / Passkey authentication was cancelled or not recognized');
            } else if (error.name === 'SecurityError') {
                throw new Error('Face ID / Fingerprint / Passkey authentication is not supported in this context. Please use HTTPS.');
            }

            throw error;
        }
    }

    /**
     * Get registered credentials
     */
    async getCredentials() {
        try {
            const response = await fetch('/api/biometric/credentials', {
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                return data.credentials || [];
            }
            return [];
        } catch (error) {
            console.error('[Biometric] Error getting credentials:', error);
            return [];
        }
    }

    /**
     * Remove a biometric credential
     */
    async removeCredential(credentialId) {
        try {
            const response = await fetch(`/api/biometric/credentials/${credentialId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            if (response.ok) {
                return { success: true };
            } else {
                const data = await response.json();
                throw new Error(data.error || 'Failed to remove credential');
            }
        } catch (error) {
            console.error('[Biometric] Error removing credential:', error);
            throw error;
        }
    }

    /**
     * Helper: Convert base64url to ArrayBuffer
     */
    base64UrlToBuffer(base64url) {
        const base64 = base64url.replace(/-/g, '+').replace(/_/g, '/');
        const padLen = (4 - (base64.length % 4)) % 4;
        const padded = base64 + '='.repeat(padLen);
        const binary = atob(padded);
        const buffer = new ArrayBuffer(binary.length);
        const view = new Uint8Array(buffer);
        for (let i = 0; i < binary.length; i++) {
            view[i] = binary.charCodeAt(i);
        }
        return buffer;
    }

    /**
     * Helper: Convert ArrayBuffer to base64url
     */
    bufferToBase64Url(buffer) {
        const binary = String.fromCharCode(...new Uint8Array(buffer));
        const base64 = btoa(binary);
        return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=/g, '');
    }

    /**
     * Helper: Refresh CSRF token from server
     */
    async refreshCsrfToken() {
        try {
            const response = await fetch('/csrf-token');
            if (!response.ok) return false;
            const data = await response.json();
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta && data.token) {
                meta.content = data.token;
                return true;
            }
            return false;
        } catch (error) {
            console.error('[Biometric] Failed to refresh CSRF token:', error);
            return false;
        }
    }

    /**
     * Helper: Get CSRF token
     */
    getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.content : '';
    }

    /**
     * Helper: Get device name
     */
    getDeviceName() {
        const userAgent = navigator.userAgent;
        if (userAgent.match(/iPhone|iPad|iPod/i)) {
            return 'iPhone/iPad (Face ID / Touch ID)';
        } else if (userAgent.match(/Android/i)) {
            return 'Android Device (Fingerprint / Face Unlock)';
        } else if (userAgent.match(/Windows/i)) {
            return 'Windows Device (Windows Hello)';
        } else if (userAgent.match(/Mac/i)) {
            return 'Mac Device (Touch ID)';
        }
        return 'Unknown Device';
    }
}

// Create global instance
window.biometricAuth = new BiometricAuth();

// Helper functions for global access
window.registerBiometric = async function (deviceName) {
    return await window.biometricAuth.register(deviceName);
};

window.authenticateWithBiometric = async function (username = null) {
    return await window.biometricAuth.authenticate(username);
};

window.isBiometricAvailable = async function () {
    return await window.biometricAuth.checkAvailability();
};

window.hasBiometricCredentials = async function () {
    return await window.biometricAuth.hasCredentials();
};
