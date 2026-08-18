<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class PingingConnection
{
    public function ping(): bool
    {
        return true;
    }
}

describe('liveness', function () {
    it('returns ok', function () {
        $this->getJson('/api/v1/healthz')
            ->assertOk()
            ->assertJson(['status' => 'ok']);
    });
});

describe('readiness', function () {
    it('returns ready when database and redis reachable', function () {
        Redis::shouldReceive('connection')->once()->andReturn(new PingingConnection);

        $this->getJson('/api/v1/readyz')
            ->assertOk()
            ->assertJson([
                'status' => 'ready',
                'checks' => ['database' => true, 'redis' => true],
            ]);
    });

    it('returns 503 degraded when database unreachable', function () {
        config()->set('database.connections.pgsql.host', '127.0.0.1');
        config()->set('database.connections.pgsql.port', '59999');
        config()->set('database.connections.pgsql.database', 'nonexistent');
        config()->set('database.default', 'pgsql');
        DB::purge();

        Redis::shouldReceive('connection')->once()->andReturn(new PingingConnection);

        $this->getJson('/api/v1/readyz')
            ->assertStatus(503)
            ->assertJsonPath('status', 'degraded')
            ->assertJsonPath('checks.database', false)
            ->assertJsonPath('checks.redis', true);

        config()->set('database.default', 'sqlite');
        DB::purge();
    });

    it('returns 503 degraded when redis unreachable', function () {
        Redis::shouldReceive('connection')->once()->andThrow(new RuntimeException('connection refused'));

        $this->getJson('/api/v1/readyz')
            ->assertStatus(503)
            ->assertJsonPath('status', 'degraded')
            ->assertJsonPath('checks.database', true)
            ->assertJsonPath('checks.redis', false);
    });
});
