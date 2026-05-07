<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiEnabled = (bool) Setting::get('api_enabled', false);

        // If API is disabled, block all unauthenticated API access
        if (!$apiEnabled && !$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'API access is currently disabled.',
            ], 403);
        }

        // Authenticated web users (frontend) can bypass API key check
        if ($request->user()) {
            return $next($request);
        }

        // External API callers must provide a valid API key
        $providedKey = $request->header('X-API-Key');
        $validKey = Setting::get('api_key');

        if (empty($validKey) || !hash_equals($validKey, $providedKey ?? '')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing API key.',
            ], 401);
        }

        return $next($request);
    }
}
