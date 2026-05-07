<?php

namespace App\Providers;

use App\Services\SettingsEnforcer;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            SettingsEnforcer::applyMailConfig();
            SettingsEnforcer::applySessionConfig();
        } catch (\Exception $e) {
            // Silently fail if settings table is not available (e.g. during initial migration)
        }

        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function ($request) {
            $maxAttempts = SettingsEnforcer::getMaxLoginAttempts();
            return Limit::perMinute($maxAttempts)->by($request->ip());
        });
    }
}
