<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $roleName = strtolower($user->role?->name ?? '');
        $dashboardRoute = match ($roleName) {
            'system admin', 'admin' => 'admin.dashboard',
            'principal' => 'principal.dashboard',
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
            'registrar' => 'registrar.dashboard',
            default => 'login',
        };

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route($dashboardRoute, absolute: false).'?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->intended(route($dashboardRoute, absolute: false).'?verified=1');
    }
}
