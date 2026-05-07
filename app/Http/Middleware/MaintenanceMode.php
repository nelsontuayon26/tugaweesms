<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        // Check maintenance mode setting
        if (setting('maintenance_mode') && !$request->user()?->isAdmin()) {
            return response()->view('maintenance', [], 503);
        }
        
        return $next($request);
    }
}