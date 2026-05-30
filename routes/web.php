<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ControlController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function ()
{
    // ======================================================
    // LOGIN PAGE
    // ======================================================

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

    // ======================================================
    // LOGIN PROCESS
    // ======================================================

    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function ()
{
    // ======================================================
    // LOGOUT
    // ======================================================

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ======================================================
    // DASHBOARD
    // ======================================================

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/greenhouse/switch/{id}', [ProfileController::class, 'switchGreenhouse'])->name('greenhouse.switch');

    // ======================================================
    // SENSOR
    // ======================================================

    Route::get('/sensors', [SensorController::class, 'index'])->name('sensors.index');

    // ======================================================
    // ANALYTICS / GRAFIK
    // ======================================================

    Route::get('/grafik', [AnalyticsController::class, 'index'])->name('grafik.index');

    // ======================================================
    // EXPORT PDF
    // ======================================================

    Route::get('/grafik/export', [AnalyticsController::class, 'exportPdf'])->name('grafik.export');

    // ======================================================
    // LOGS
    // ======================================================

    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // ======================================================
    // SETTINGS
    // ======================================================

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // ======================================================
    // CONTROL ACTUATOR
    // ======================================================

    Route::prefix('control')->group(function ()
    {
        // ======================================================
        // PUMP
        // ======================================================

        Route::post('/pump', [ControlController::class, 'pump'])->name('control.pump');

        // ======================================================
        // FAN
        // ======================================================

        Route::post('/fan', [ControlController::class, 'fan'])->name('control.fan');

        // ======================================================
        // LAMP
        // ======================================================

        Route::post('/lamp', [ControlController::class, 'lamp'])->name('control.lamp');
    });

    // ======================================================
    // CHANGE MODE
    // ======================================================

    Route::post('/mode/{type}', [ControlController::class, 'changeMode'])->name('control.mode');

    // ======================================================
    // RESET NODE
    // ======================================================

    Route::post('/reset-node', [ControlController::class, 'resetNode'])->name('control.reset');

    // ======================================================
    // PROFILE
    // ======================================================

    Route::prefix('profile')->group(function ()
    {
        // ======================================================
        // PROFILE PAGE
        // ======================================================

        Route::get('/', [ProfileController::class, 'index'])->name('profile');

        // ======================================================
        // EDIT PROFILE
        // ======================================================

        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');

        // ======================================================
        // UPDATE PROFILE
        // ======================================================

        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // ======================================================
    // REALTIME STATS
    // ======================================================

    Route::get('/stats/realtime', [ProfileController::class, 'realtimeStats'])->name('stats.realtime');
});

/*
|--------------------------------------------------------------------------
| DEFAULT REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/', function ()
{
    return redirect()->route('login');
});