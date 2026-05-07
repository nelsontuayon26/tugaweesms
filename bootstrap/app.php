<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // CRITICAL: Explicitly define the web middleware group with session support
        // This ensures cookies and sessions work properly
        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        
        // Add InjectAppearance AFTER session is started
        $middleware->web(append: [
            \App\Http\Middleware\InjectAppearance::class,
        ]);
        
        // Middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'student.approved' => \App\Http\Middleware\CheckStudentApproval::class,
            'student' => \App\Http\Middleware\EnsureUserIsStudent::class,
            'principal.view' => \App\Http\Middleware\PrincipalViewSwap::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->is('login') || $request->is('student/login') || $request->is('logout')) {
                return redirect('/')
                    ->withInput($request->except('_token'))
                    ->withErrors(['login' => 'Your session expired. Please try again.']);
            }

            if ($request->is('register') || $request->is('enroll') || $request->is('enroll/check')) {
                return redirect()->back()
                    ->withInput($request->except('_token'))
                    ->withErrors(['error' => 'Your session expired. Please refresh the page and try again.']);
            }

            return redirect()->back()
                ->withInput($request->except('_token'))
                ->withErrors(['error' => 'Your session expired. Please try again.']);
        });
    })->create();