<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    // LOGIN PROCESS
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
        // FORMAT CREDENTIALS
        // ======================================================
        $credentials = [
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password']
        ];

        $remember = $request->boolean('remember');

        // ======================================================
        // ATTEMPT LOGIN
        // ======================================================
        if (!Auth::attempt($credentials, $remember))
        {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.'
            ]);
        }

        // REGENERATE SESSION
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