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
        // ======================================================
        // USER SUDAH LOGIN
        // ======================================================

        if (Auth::check())
        {
            return redirect()->route(
                'dashboard.index'
            );
        }

        // ======================================================
        // RETURN VIEW
        // ======================================================

        return view(
            'auth.login'
        );
    }

    // ======================================================
    // LOGIN
    // ======================================================

    public function login(Request $request)
    {
        // ======================================================
        // VALIDASI
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

            'email' => strtolower(
                trim(
                    $validated['email']
                )
            ),
            'password' => $validated['password']
        ];

        // ======================================================
        // REMEMBER ME
        // ======================================================

        $remember = $request->boolean(

            'remember'
        );

        // ======================================================
        // ATTEMPT LOGIN
        // ======================================================

        if (!Auth::attempt(

            $credentials,
            $remember
        ))
        {
            throw ValidationException::withMessages([

                'email' =>
                    'Email atau password salah.'
            ]);
        }

        // ======================================================
        // REGENERATE SESSION
        // ======================================================

        $request->session()->regenerate();

        // ======================================================
        // USER LOGIN
        // ======================================================

        $user = Auth::user();

        // ======================================================
        // AUTO ACTIVE GREENHOUSE
        // ======================================================

        if (!$user->active_greenhouse_id)
        {
            $greenhouse = Greenhouse::where(
                'user_id',
                $user->id
            )->latest()->first();

            // ======================================================
            // SET ACTIVE
            // ======================================================

            if ($greenhouse)
            {
                $user->update([
                    'active_greenhouse_id' =>
                        $greenhouse->id
                ]);
            }
        }

        // ======================================================
        // REDIRECT USER
        // ======================================================

        return redirect()

            ->intended(
                route('dashboard.index')
            )
            ->with(
                'success',
                'Login berhasil 👋'
            );
    }

    // ======================================================
    // LOGOUT
    // ======================================================

    public function logout(Request $request)
    {
        // ======================================================
        // LOGOUT
        // ======================================================

        Auth::logout();

        // ======================================================
        // INVALIDATE SESSION
        // ======================================================

        $request->session()->invalidate();

        // ======================================================
        // REGENERATE TOKEN
        // ======================================================

        $request->session()->regenerateToken();

        // ======================================================
        // REDIRECT
        // ======================================================

        return redirect('/login')

            ->with(
                'success',
                'Logout berhasil 👋'
            );
    }
}