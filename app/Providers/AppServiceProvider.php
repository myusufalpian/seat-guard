<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private const PUBLIC_READ_LIMIT_PER_MINUTE = 60;

    public function boot(): void
    {
        RateLimiter::for('public-read', function (Request $request) {
            return Limit::perMinute(self::PUBLIC_READ_LIMIT_PER_MINUTE)
                ->by((string) ($request->user()->id ?? $request->ip()));
        });
    }
}
