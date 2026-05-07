<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Share settings with all admin views
        View::composer('admin.*', function ($view) {
            $view->with('app_settings', Setting::getAll());
        });
    }
}
