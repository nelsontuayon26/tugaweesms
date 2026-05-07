<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        Mail::alwaysFrom('onboarding@resend.dev', 'Tugawe ES Portal');

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );
        } catch (\Exception $e) {
            report($e);

            $errorMessage = $e->getMessage();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => ['email' => [$errorMessage]],
                    'debug' => [
                        'mail_from_address' => config('mail.from.address'),
                        'mail_from_name' => config('mail.from.name'),
                        'mail_mailer' => config('mail.default'),
                        'resend_key_set' => !empty(config('services.resend.key')),
                    ]
                ], 500);
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => $errorMessage]);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return $status == Password::RESET_LINK_SENT
                ? response()->json(['message' => __($status)])
                : response()->json(['errors' => ['email' => [__($status)]]], 422);
        }

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }
}
