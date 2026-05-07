<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\View\View;

class PrincipalViewSwap
{
    /**
     * Handle an incoming request.
     *
     * When a principal user accesses a /principal/* route that uses an admin controller,
     * swap the admin view for a principal view if one exists.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only swap views for principal users on principal routes
        $isPrincipal = strtolower(auth()->user()?->role?->name ?? '') === 'principal';
        $isPrincipalRoute = $request->is('principal/*');

        if (!$isPrincipal || !$isPrincipalRoute) {
            return $response;
        }

        // Try to extract the View object from the response
        $view = $this->extractView($response);

        if ($view) {
            $viewName = $view->name();
            $principalView = str_replace('admin.', 'principal.', $viewName);

            // If a principal version of this view exists, render it with the same data
            if (view()->exists($principalView)) {
                return response()->view($principalView, $view->getData());
            }
        }

        return $response;
    }

    /**
     * Extract a View object from a response.
     */
    private function extractView($response): ?View
    {
        if ($response instanceof View) {
            return $response;
        }

        if ($response instanceof \Illuminate\Http\Response && $response->getOriginalContent() instanceof View) {
            return $response->getOriginalContent();
        }

        return null;
    }
}
