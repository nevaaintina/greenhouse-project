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

// Login Page
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Process Login
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (WAJIB LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // =====================
    // DASHBOARD
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // SENSOR
    // =====================
    Route::get('/sensors', [SensorController::class, 'index'])->name('sensors');

    // =====================
    // ANALYTICS / GRAFIK
    // =====================
    Route::get('/grafik', [AnalyticsController::class, 'index'])->name('grafik');

    // =====================
    // LOGS
    // =====================
    Route::get('/logs', [LogController::class, 'index'])->name('logs');

    // =====================
    // SETTINGS
    // =====================
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // =====================
    // CONTROL (AKTUATOR)
    // =====================
    Route::prefix('control')->group(function () {
        Route::post('/pump', [ControlController::class, 'pump'])->name('control.pump');
        Route::post('/fan', [ControlController::class, 'fan'])->name('control.fan');
        Route::post('/lamp', [ControlController::class, 'lamp'])->name('control.lamp');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // =====================
    // REALTIME STATS
    // =====================
    Route::get('/stats/realtime', [ProfileController::class, 'realtimeStats'])->name('stats.realtime');

    Route::get('/grafik/export', [AnalyticsController::class, 'export'])->name('grafik.export');

});