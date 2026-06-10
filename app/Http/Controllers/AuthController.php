<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Greenhouse;

class AuthController extends Controller
{
    // ======================================================
    // SHOW LOGIN
    // ======================================================
    public function showLogin()
    {
        if (Auth::check())
        {
            // PERBAIKAN: Jika sudah login, cek dulu status verifikasi emailnya
            if (!Auth::user()->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->route('dashboard.index');
        }

        return view('auth.login');
    }

    // ======================================================
    // LOGIN PROCESS (WITH RATE LIMITING)
    // ======================================================
    public function login(Request $request)
    {
        // ======================================================
        // VALIDASI INPUT FORM
        // ======================================================
        $validated = $request->validate([
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'string',
                'min:6'
            ]
        ]);

        // ======================================================
        // FORMAT CREDENTIALS & THROTTLE KEY
        // ======================================================
        $emailFormatted = strtolower(trim($validated['email']));
        
        $credentials = [
            'email' => $emailFormatted,
            'password' => $validated['password']
        ];

        $remember = $request->boolean('remember');

        // Buat key unik berdasarkan email (lowercase) dan IP Address user
        $throttleKey = $emailFormatted . '|' . $request->ip();

        // ======================================================
        // CEK APAKAH USER MELEBIHI BATAS LOGIN (MAX 5 KALI)
        // ======================================================
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) 
        {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            throw ValidationException::withMessages([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$minutes} menit."
            ]);
        }

        // ======================================================
        // ATTEMPT LOGIN
        // ======================================================
        if (!Auth::attempt($credentials, $remember))
        {
            // Jika gagal login, tambahkan 1 hit ke limiter (berlaku selama 60 detik)
            RateLimiter::hit($throttleKey, 60);

            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.'
            ]);
        }

        // ======================================================
        // LOGIN SUKSES: CLEAR LIMITER & REGENERATE SESSION
        // ======================================================
        RateLimiter::clear($throttleKey);
        
        $request->session()->regenerate();

        $user = Auth::user();

        // ======================================================
        // AUTO ACTIVE GREENHOUSE CHECKER
        // ======================================================
        if (!$user->active_greenhouse_id)
        {
            $greenhouse = Greenhouse::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($greenhouse)
            {
                $user->update([
                    'active_greenhouse_id' => $greenhouse->id
                ]);
            }
        }

        // ======================================================
        // CEK VERIFIKASI EMAIL SETELAH LOGIN SUKSES
        // Jika belum diverifikasi, arahkan ke rute pemberitahuan verifikasi
        // ======================================================
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // ======================================================
        // PERBAIKAN MUTLAK: Mengunci Redirect Langsung ke Route Dashboard
        // Menggantikan ->intended() untuk menghindari jebakan halaman JSON /stats/realtime
        // ======================================================
        return redirect()
            ->route('dashboard.index')
            ->with('success', 'Login berhasil 👋');
    }

    // ======================================================
    // LOGOUT PROCESS
    // ======================================================
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')
            ->with('success', 'Logout berhasil 👋');
    }
}