<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || str_contains(config('app.url', ''), 'https://') || app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Rate limiting global sur les requêtes web : 60 requêtes / minute par IP
        RateLimiter::for('global-web', function (Request $request) {
            if (app()->environment('testing')) {
                return Limit::none();
            }
            return Limit::perMinute(60)->by($request->ip());
        });
    }
}
