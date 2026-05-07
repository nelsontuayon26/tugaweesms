<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WebAuthnService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BiometricAuthController extends Controller
{
    private WebAuthnService $webAuthn;

    public function __construct(WebAuthnService $webAuthn)
    {
        $this->webAuthn = $webAuthn;
    }

    /**
     * Check if biometric authentication is available on this device
     */
    public function checkAvailability()
    {
        return response()->json([
            'available' => true,
            'supports_biometric' => true,
            'message' => 'Face ID, Fingerprint, and Passkey authentication is supported',
        ]);
    }

    /**
     * Get registration options for WebAuthn
     */
    public function getRegistrationOptions(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $options = $this->webAuthn->getRegistrationOptions($user);

            return response()->json($options);
        } catch (\Throwable $e) {
            Log::error('WebAuthn registration options error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to generate registration options'], 500);
        }
    }

    /**
     * Register a new biometric credential
     */
    public function register(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'rawId' => 'required|string',
            'type' => 'required|string',
            'response.clientDataJSON' => 'required|string',
            'response.attestationObject' => 'required|string',
        ]);

        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $storedChallenge = session('webauthn_challenge');
        $options = session('webauthn_registration_options');
        if (! $storedChallenge || ! $options) {
            return response()->json(['error' => 'Registration session expired. Please try again.'], 400);
        }

        try {

            $credentialRecord = $this->webAuthn->verifyRegistration(
                $request->all(),
                $options,
                $request->getHost()
            );

            $recordJson = $this->webAuthn->serializeCredentialRecord($credentialRecord);

            DB::table('biometric_credentials')->updateOrInsert(
                ['user_id' => $user->id, 'credential_id' => $request->id],
                [
                    'raw_id' => $request->rawId,
                    'type' => $request->type,
                    'public_key' => $recordJson,
                    'credential_record' => $recordJson,
                    'device_name' => $request->device_name ?? $this->getDeviceName(),
                    'last_used_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Face ID / Fingerprint / Passkey registered successfully',
            ]);
        } catch (\Throwable $e) {
            Log::error('WebAuthn registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get authentication options
     */
    public function getAuthenticationOptions(Request $request)
    {
        $request->validate([
            'username' => 'nullable|string|max:255',
        ]);

        try {
            $user = null;
            $hasCredentials = false;

            if ($request->filled('username')) {
                $user = User::where('username', $request->username)->first();

                if (! $user) {
                    return response()->json([
                        'error' => 'User not found. Please check your username and try again.',
                    ], 404);
                }

                $hasCredentials = \DB::table('biometric_credentials')
                    ->where('user_id', $user->id)
                    ->exists();
            }

            $options = $this->webAuthn->getAuthenticationOptions($user);

            return response()->json([
                ...$options,
                'has_credentials' => $hasCredentials,
            ]);
        } catch (\Throwable $e) {
            Log::error('WebAuthn auth options error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to generate authentication options'], 500);
        }
    }

    /**
     * Authenticate with biometric
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'rawId' => 'required|string',
            'response.authenticatorData' => 'required|string',
            'response.clientDataJSON' => 'required|string',
            'response.signature' => 'required|string',
            'userHandle' => 'nullable|string',
        ]);

        $storedChallenge = session('webauthn_auth_challenge');
        $options = session('webauthn_auth_options');
        if (! $storedChallenge || ! $options) {
            return response()->json(['error' => 'Authentication session expired. Please try again.'], 400);
        }

        try {
            $credentialRecord = $this->webAuthn->findCredentialRecord($request->id);

            if (! $credentialRecord) {
                return response()->json([
                    'success' => false,
                    'error' => 'Credential not found',
                ], 404);
            }

            $user = User::find((int) $credentialRecord->userHandle);

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'error' => 'User not found',
                ], 404);
            }

            $updatedRecord = $this->webAuthn->verifyAuthentication(
                $request->all(),
                $options,
                $credentialRecord,
                $request->getHost()
            );

            // Update stored credential with new counter
            $newRecordJson = $this->webAuthn->serializeCredentialRecord($updatedRecord);
            DB::table('biometric_credentials')
                ->where('credential_id', $request->id)
                ->update([
                    'public_key' => $newRecordJson,
                    'credential_record' => $newRecordJson,
                    'last_used_at' => now(),
                    'updated_at' => now(),
                ]);

            // Log in the user
            Auth::login($user);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Authentication successful',
                'redirect' => $this->getDashboardRoute($user),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'role' => $user->role?->name,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('WebAuthn authentication failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Authentication failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get registered biometric credentials for the current user
     */
    public function getCredentials()
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $credentials = DB::table('biometric_credentials')
            ->where('user_id', $user->id)
            ->select('id', 'device_name', 'last_used_at', 'created_at')
            ->get()
            ->map(function ($cred) {
                return [
                    'id' => $cred->id,
                    'device_name' => $cred->device_name,
                    'last_used' => $cred->last_used_at ? \Carbon\Carbon::parse($cred->last_used_at)->diffForHumans() : 'Never',
                    'created' => \Carbon\Carbon::parse($cred->created_at)->format('M d, Y'),
                ];
            });

        return response()->json([
            'success' => true,
            'credentials' => $credentials,
        ]);
    }

    /**
     * Remove a biometric credential
     */
    public function removeCredential($id)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $deleted = DB::table('biometric_credentials')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Credential removed',
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => 'Credential not found',
        ], 404);
    }

    /**
     * Get dashboard route based on user role
     */
    private function getDashboardRoute(User $user): string
    {
        $role = strtolower($user->role?->name ?? '');

        return match ($role) {
            'admin', 'system admin' => '/admin/dashboard',
            'principal' => '/principal/dashboard',
            'teacher' => '/teacher/dashboard',
            'student' => '/student/dashboard',
            'registrar' => '/registrar/dashboard',
            default => '/dashboard',
        };
    }

    /**
     * Get device name from user agent
     */
    private function getDeviceName(): string
    {
        $userAgent = request()->header('User-Agent', '');

        return match (true) {
            str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad') || str_contains($userAgent, 'iPod') => 'iPhone/iPad',
            str_contains($userAgent, 'Android') => 'Android Device',
            str_contains($userAgent, 'Windows') => 'Windows Device',
            str_contains($userAgent, 'Mac') => 'Mac Device',
            default => 'Unknown Device',
        };
    }
}
