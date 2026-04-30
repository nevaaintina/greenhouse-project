<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

// Halaman login
Route::get('/', [AuthController::class, 'showLogin'])->name('login');

// Proses login
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
    Route::get('/dashboard', [SensorController::class, 'dashboard']);
Route::get('/sensors', [SensorController::class, 'index']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // SENSOR
    // =====================

    // =====================
    // ANALYTICS / GRAFIK
    // =====================
    Route::get('/grafik', [AnalyticsController::class, 'index'])->name('grafik');

    // =====================
    // LOGS (PAKE DATABASE)
    // =====================
    Route::get('/logs', [LogController::class, 'index'])->name('logs');

    // =====================
    // SETTINGS
    // =====================
Route::get('/settings', [SettingController::class, 'index']);
Route::post('/settings/update', [SettingController::class, 'update']);

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

});