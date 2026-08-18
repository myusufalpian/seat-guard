<?php

use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/healthz', [HealthController::class, 'liveness']);
Route::get('/readyz', [HealthController::class, 'readiness']);
