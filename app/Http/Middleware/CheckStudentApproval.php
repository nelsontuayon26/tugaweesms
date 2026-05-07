<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckStudentApproval
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && strtolower($user->role->name) === 'pupil') {
            // Block if not approved
            if (!$user->student || $user->student->status !== 'approved') {
                Auth::logout();
                return redirect('/login')->withErrors([
                    'login' => 'Your account is pending admin approval. You cannot access this page yet.'
                ]);
            }
        }

        return $next($request);
    }
}