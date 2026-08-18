<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

describe('public-read rate limiter', function () {
    it('limits by user id when authenticated', function () {
        $request = Request::create('/api/v1/events');
        $request->setUserResolver(fn () => (object) ['id' => 42]);

        $limit = RateLimiter::limiter('public-read')($request);

        expect($limit)->toBeInstanceOf(Limit::class)
            ->and($limit->key)->toBe('42')
            ->and($limit->maxAttempts)->toBe(60);
    });

    it('falls back to client ip for guests', function () {
        $request = Request::create('/api/v1/events', 'GET', [], [], [], ['REMOTE_ADDR' => '203.0.113.9']);
        $request->setUserResolver(fn () => null);

        $limit = RateLimiter::limiter('public-read')($request);

        expect($limit)->toBeInstanceOf(Limit::class)
            ->and($limit->key)->toBe('203.0.113.9');
    });
});
