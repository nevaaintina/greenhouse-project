<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\SensorApiController;
use App\Http\Controllers\Api\HeartbeatApiController;

Route::post(

    '/sensor-data',

    [SensorApiController::class, 'store']
);

Route::post(

    '/heartbeat',

    [HeartbeatApiController::class, 'store']
);