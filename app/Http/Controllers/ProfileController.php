<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorData;

class ProfileController extends Controller
{
    // =========================
    // PROFILE PAGE
    // =========================
    public function index()
    {
        $user = Auth::user();

        $stats = $this->getStats();

        return view('profile.index', array_merge(
            ['user' => $user],
            $stats
        ));
    }

    // =========================
    // REALTIME API
    // =========================
    public function realtimeStats()
    {
        return response()->json($this->getStats());
    }

    // =========================
    // EDIT PAGE
    // =========================
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    // =========================
    // UPDATE PROFILE
    // =========================
    public function update(Request $request)
    {
        $user = Auth::user();

        // 🔥 VALIDASI
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'farm_type' => 'nullable|string|max:100',
            'greenhouse_name' => 'nullable|string|max:100',
            'current_password' => 'nullable',
            'new_password' => 'nullable|min:6'
        ]);

        // 🔥 UPDATE DATA
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'farm_type' => $request->farm_type,
            'greenhouse_name' => $request->greenhouse_name,
        ]);

        // 🔐 UPDATE PASSWORD
        if ($request->filled('new_password')) {

            if (!$request->filled('current_password')) {
                return back()->with('error', 'Masukkan password lama!');
            }

            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Password lama salah!');
            }

            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // =========================
    // 🔥 CORE LOGIC STATS 
    // =========================
    private function getStats()
    {
        // total logs
        $totalLogs = SensorData::count();

        // total sensor
        $totalNodes = Sensor::count();

        // actuator aktif
        $activeActuators = Actuator::where('status', 'on')->count();

        $systemStatus = $activeActuators > 0 ? 'Active' : 'Idle';

        // uptime 
        $lastData = SensorData::latest('recorded_at')->first();

        if ($lastData) {
            $minutes = now()->diffInMinutes($lastData->recorded_at);
            $uptime = $minutes < 5 ? 'Online' : 'Offline';
        } else {
            $uptime = 'Offline';
        }

        return [
            'totalLogs' => $totalLogs,
            'totalNodes' => $totalNodes,
            'systemStatus' => $systemStatus,
            'uptime' => $uptime
        ];
    }
}