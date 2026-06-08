<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
| DEFAULT REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (GUEST)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Login Page
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Login Process
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION ROUTES (Bagi User yang Sudah Login tapi Belum Verifikasi)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // 1. Halaman Pemberitahuan: "Silakan Verifikasi Email Anda"
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // 2. Handler saat Link Verifikasi di Email di-klik oleh Petani
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard.index')->with('success', 'Email berhasil diverifikasi!');
    })->middleware('signed')->name('verification.verify');

    // 3. Tombol Aksi untuk Kirim Ulang Email Verifikasi (Resend)
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Link verifikasi baru telah dikirim ke email Anda!');
    })->middleware('throttle:6,1')->name('verification.send');

    // Logout tetap bisa diakses meskipun email belum diverifikasi
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (AUTH & VERIFIED)
| Hanya bisa diakses jika Petani sudah LOGIN DAN EMAILNYA VALID
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard & Greenhouse Switcher
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/greenhouse/switch/{id}', [ProfileController::class, 'switchGreenhouse'])->name('greenhouse.switch');

    // Sensor Monitoring
    Route::get('/sensors', [SensorController::class, 'index'])->name('sensors.index');

    // Analytics / Grafik & Export PDF
    Route::get('/grafik', [AnalyticsController::class, 'index'])->name('grafik.index');
    Route::get('/grafik/export', [AnalyticsController::class, 'exportPdf'])->name('grafik.export');
    
    // PERBAIKAN REAL-TIME: Rute pendukung AJAX data real-time untuk halaman Grafik Analitik
    Route::get('/grafik/realtime', [AnalyticsController::class, 'realtimeAnalytics'])->name('grafik.realtime');

    // System Log Activities
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');

    // Dynamic Threshold Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // Control Actuator (Manual Target Mode)
    Route::prefix('control')->group(function () {
        Route::post('/pump', [ControlController::class, 'pump'])->name('control.pump');
        Route::post('/fan', [ControlController::class, 'fan'])->name('control.fan');
        Route::post('/lamp', [ControlController::class, 'lamp'])->name('control.lamp');
    });

    // Change System Mode (Disinkronkan menjadi {mode} agar sesuai variabel Controller)
    Route::post('/mode/{mode}', [ControlController::class, 'changeMode'])->name('control.mode');

    // Reset Node Hardware System
    Route::post('/reset-node', [ControlController::class, 'resetNode'])->name('control.reset');

    // User Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });

    // ==========================================================================
    // PERBAIKAN SELESAI: Dialihkan kembali ke DashboardController agar membawa 
    // muatan array 'actuators' utuh demi kestabilan kontrol manual & otomatis
    // ==========================================================================
    Route::get('/stats/realtime', [DashboardController::class, 'realtimeStats'])->name('stats.realtime');

    // ==========================================================================
    // DATA REALTIME SIDEBAR: Menyediakan data mode, status aktuator, dan koneksi ESP32
    // ==========================================================================
    Route::get('/api/greenhouse/status', [DashboardController::class, 'sidebarStatus'])->name('sidebar.status');
});