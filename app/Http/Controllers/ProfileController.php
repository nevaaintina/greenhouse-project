<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini agar Auth terbaca

class ProfileController extends Controller
{
    // Untuk nampilin halaman profil utama
    public function index()
    {
        $user = Auth::user(); 
        return view('profile.index', compact('user'));
    }

    // INI YANG TADI KURANG: Untuk nampilin halaman form edit
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Untuk memproses simpan perubahan profil
    public function update(Request $request)
    {
        $user = Auth::user();
        
        // Contoh validasi sederhana
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->all());

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }
}