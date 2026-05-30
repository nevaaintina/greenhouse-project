<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Sensor;
use App\Models\Actuator;
use App\Models\SensorData;
use App\Models\Greenhouse;

class ProfileController extends Controller
{
    // ======================================================
    // PROFILE PAGE
    // ======================================================

    public function index()
    {
        $user = Auth::user()->fresh();

        // ======================================================
        // USER GREENHOUSES
        // ======================================================

        $greenhouses = Greenhouse::where(
            'user_id',
            $user->id
        )->latest()->get();

        // ======================================================
        // AUTO ACTIVE
        // ======================================================

        if (

            !$user->active_greenhouse_id
            &&
            $greenhouses->isNotEmpty()
        ) {

            $user->update([
                'active_greenhouse_id' =>
                    $greenhouses->first()->id
            ]);

            $user->refresh();
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $user->activeGreenhouse;

        // ======================================================
        // STATS
        // ======================================================

        $stats = $this->getStats();

        return view(

            'profile.index',

            array_merge(

                [

                    'user' => $user,
                    'greenhouse' => $greenhouse,
                    'greenhouses' => $greenhouses
                ],

                $stats
            )
        );
    }

    // ======================================================
    // REALTIME API
    // ======================================================

    public function realtimeStats()
    {
        return response()->json(

            $this->getStats()
        );
    }

    // ======================================================
    // EDIT PAGE
    // ======================================================

    public function edit()
    {
        $user = Auth::user()->fresh();

        return view(

            'profile.edit',

            [

                'user' => $user,
                'greenhouse' => $user->activeGreenhouse
            ]
        );
    }

    // ======================================================
    // UPDATE PROFILE
    // ======================================================

    public function update(Request $request)
    {
        $user = Auth::user();

        // ======================================================
        // VALIDATION
        // ======================================================

        $validated = $request->validate([

            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|unique:users,email,' . $user->id,

            'phone' =>
                'nullable|string|max:20',

            // ======================================================
            // GREENHOUSE
            // ======================================================

            'greenhouse_name' =>
                'nullable|string|max:100',

            'location' =>
                'nullable|string|max:255',

            'size' =>
                'nullable|string|max:100',

            'plant_type' =>
                'nullable|string|max:100',

            // ======================================================
            // PASSWORD
            // ======================================================

            'current_password' =>
                'nullable',

            'new_password' =>
                'nullable|min:6|confirmed'
        ]);

        // ======================================================
        // UPDATE USER
        // ======================================================

        $user->update([

            'name' => trim(
                (string) $validated['name']
            ),

            'email' => strtolower(
                trim(
                    (string) $validated['email']
                )
            ),

            'phone' => trim(
                (string) ($validated['phone'] ?? '')
            ),
        ]);

        // ======================================================
        // PASSWORD
        // ======================================================

        if ($request->filled('new_password'))
        {
            if (!$request->filled('current_password'))
            {
                return back()->with(

                    'error',
                    'Masukkan password lama!'
                );
            }

            if (!Hash::check(

                $request->current_password,
                $user->password
            ))
            {
                return back()->with(

                    'error',
                    'Password lama salah!'
                );
            }

            $user->update([

                'password' => Hash::make(

                    $request->new_password
                )
            ]);
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $user->activeGreenhouse;

        // ======================================================
        // CREATE GREENHOUSE
        // ======================================================

        if (!$greenhouse)
        {
            $greenhouse = Greenhouse::create([

                'user_id' => $user->id,
                'name' => 'Smart Greenhouse'
            ]);

            $user->update([

                'active_greenhouse_id' =>
                    $greenhouse->id
            ]);

            $user->refresh();
        }

        // ======================================================
        // UPDATE GREENHOUSE
        // ======================================================

        $greenhouse->update([

            'name' => trim(

                (string)
                ($validated['greenhouse_name']
                ?? 'Smart Greenhouse')
            ),

            'location' => trim(

                (string)
                ($validated['location']
                ?? '')
            ),

            'size' => trim(

                (string)
                ($validated['size']
                ?? '')
            ),

            'plant_type' => trim(

                (string)
                ($validated['plant_type']
                ?? '')
            ),
        ]);

        return back()->with(

            'success',
            'Profil berhasil diperbarui!'
        );
    }

    // ======================================================
    // SWITCH GREENHOUSE
    // ======================================================

    public function switchGreenhouse($id)
    {
        $user = Auth::user();

        // ======================================================
        // VALIDASI
        // ======================================================

        $greenhouse = Greenhouse::where(

            'id',
            $id

        )->where(

            'user_id',
            $user->id

        )->first();

        // ======================================================
        // NOT FOUND
        // ======================================================

        if (!$greenhouse)
        {
            return back()->with(

                'error',
                'Greenhouse tidak ditemukan'
            );
        }

        // ======================================================
        // UPDATE ACTIVE
        // ======================================================

        $user->update([

            'active_greenhouse_id' =>
                $greenhouse->id
        ]);

        // ======================================================
        // REFRESH RELATION
        // ======================================================

        $user->refresh();

        return back()->with(

            'success',
            'Berhasil pindah greenhouse'
        );
    }

    // ======================================================
    // CORE STATS
    // ======================================================

    private function getStats()
    {
        $user = Auth::user()->fresh();

        if (!$user)
        {
            return [

                'totalLogs' => 0,
                'totalNodes' => 0,
                'systemStatus' => 'Offline',
                'uptime' => 'Offline'
            ];
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $user->activeGreenhouse;

        if (!$greenhouse)
        {
            return [

                'totalLogs' => 0,
                'totalNodes' => 0,
                'systemStatus' => 'Offline',
                'uptime' => 'Offline'
            ];
        }

        // ======================================================
        // SENSOR IDS
        // ======================================================

        $sensorIds = Sensor::where(

            'greenhouse_id',
            $greenhouse->id

        )->pluck('id');

        // ======================================================
        // EMPTY
        // ======================================================

        if ($sensorIds->isEmpty())
        {
            return [

                'totalLogs' => 0,
                'totalNodes' => 0,
                'systemStatus' => 'Idle',
                'uptime' => 'Offline'
            ];
        }

        // ======================================================
        // TOTAL LOGS
        // ======================================================

        $totalLogs = SensorData::whereIn(

            'sensor_id',
            $sensorIds
        )->count();

        // ======================================================
        // TOTAL NODES
        // ======================================================

        $totalNodes = Sensor::where(

            'greenhouse_id',
            $greenhouse->id

        )->count();

        // ======================================================
        // ACTUATORS
        // ======================================================

        $activeActuators = Actuator::where(

            'greenhouse_id',
            $greenhouse->id

        )->where(

            'status',
            'on'

        )->count();

        // ======================================================
        // STATUS
        // ======================================================

        $systemStatus =
            $activeActuators > 0
            ? 'Active'
            : 'Idle';

        // ======================================================
        // LAST DATA
        // ======================================================

        $lastData = SensorData::whereIn(

            'sensor_id',
            $sensorIds

        )->latest(

            'recorded_at'

        )->first();

        // ======================================================
        // UPTIME
        // ======================================================

        if ($lastData)
        {
            $minutes = now()->diffInMinutes(

                $lastData->recorded_at
            );

            $uptime =
                $minutes < 5
                ? 'Online'
                : 'Offline';
        }

        else
        {
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
