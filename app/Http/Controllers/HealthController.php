<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class HealthController extends Controller
{
    public function liveness(): JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }

    public function readiness(): JsonResponse
    {
        $checks = [
            'database' => $this->databaseIsReachable(),
            'redis' => $this->redisIsReachable(),
        ];

        $healthy = ! in_array(false, $checks, true);

        return response()->json([
            'status' => $healthy ? 'ready' : 'degraded',
            'checks' => $checks,
        ], $healthy ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE);
    }

    private function databaseIsReachable(): bool
    {
        try {
            DB::select('SELECT 1');

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function redisIsReachable(): bool
    {
        try {
            Redis::connection()->ping();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
