<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
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

        return $user->hasVerifiedEmail()
                    ? redirect()->intended(route($dashboardRoute, absolute: false))
                    : view('auth.verify-email');
    }
}
