<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsStudent
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role?->name === 'Pupil') {
            return $next($request);
        }

        abort(403, 'Unauthorized access.');
    }
}