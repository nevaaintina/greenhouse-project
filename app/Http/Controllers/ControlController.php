<?php

namespace App\Http\Controllers;
use App\Models\Actuator;
use App\Models\Setting;
use App\Models\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class ControlController extends Controller
{
    // ======================================================
    // ESP32 BASE URL
    // ======================================================

    private $espUrl = 'http://192.168.1.100';

    // ======================================================
    // GET ACTIVE GREENHOUSE
    // ======================================================

    private function greenhouse()
    {
        $user = Auth::user()->fresh();

        // ======================================================
        // VALIDASI USER
        // ======================================================

        if (!$user)
        {
            return null;
        }

        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        return $user->activeGreenhouse;
    }

    // ======================================================
    // SEND ESP32 REQUEST
    // ======================================================

    private function sendEsp($endpoint)
    {
        try {
            Http::timeout(3)
                ->retry(1, 200)
                ->get(
                    $this->espUrl . $endpoint
                );
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    // ======================================================
    // GET USER SETTING
    // ======================================================

    private function setting($greenhouseId)
    {
        return Setting::firstOrCreate(

            [
                'greenhouse_id' => $greenhouseId
            ],

            [
                'system_mode' => 'Otomatis',

                'soil_moisture_min' => 45,
                'soil_moisture_max' => 70,

                'temperature_min' => 20,
                'temperature_max' => 28,

                'humidity_min' => 40,
                'humidity_max' => 80,

                'light_min' => 300,
                'light_max' => 800
            ]
        );
    }

    // ======================================================
    // GET ACTUATOR
    // ======================================================

    private function actuator($greenhouseId, $type)
    {
        return Actuator::firstOrCreate(

            [
                'greenhouse_id' => $greenhouseId,
                'type' => $type
            ],

            [
                'name' => ucfirst($type),
                'status' => 'off',
                'mode' => 'manual'
            ]
        );
    }

    // ======================================================
    // LOG ACTIVITY
    // ======================================================

    private function log($activity, $description)
    {
        // ======================================================
        // ACTIVE GREENHOUSE
        // ======================================================

        $greenhouse = $this->greenhouse();

        // ======================================================
        // VALIDASI
        // ======================================================

        if (!$greenhouse)
        {
            return;
        }

        // ======================================================
        // CREATE LOG
        // ======================================================

        Log::create([

            'user_id' => auth()->id(),
            'greenhouse_id' => $greenhouse->id,
            'activity' => $activity,
            'description' => $description,
            'created_at' => now()
        ]);
    }

    // ======================================================
    // TOGGLE ACTUATOR
    // ======================================================

    private function toggle($type)
    {
        $greenhouse = $this->greenhouse();

        if (!$greenhouse)
        {
            return back()->with(
                'error',
                'Greenhouse aktif tidak ditemukan'
            );
        }

        // ======================================================
        // VALIDASI TYPE
        // ======================================================

        if (!in_array($type, [

            'pump',
            'fan',
            'lamp'

        ])) {

            return back()->with(

                'error',
                'Actuator tidak valid'
            );
        }

        // ======================================================
        // USER SETTING
        // ======================================================

        $setting = $this->setting(

            $greenhouse->id
        );

        // ======================================================
        // MODE AUTO
        // ======================================================

        if ($setting->system_mode === 'Otomatis')
        {
            return back()->with(
                'error',
                'Mode otomatis sedang aktif'
            );
        }

        // ======================================================
        // ACTUATOR
        // ======================================================

        $actuator = $this->actuator(
            $greenhouse->id,
            $type
        );

        // ======================================================
        // TOGGLE STATUS
        // ======================================================

        $newStatus =
            $actuator->status === 'on'
            ? 'off'
            : 'on';

        $actuator->update([
            'status' => $newStatus,
            'mode' => 'manual'
        ]);

        // ======================================================
        // SEND ESP
        // ======================================================

        $espOk = $this->sendEsp(
            "/{$type}/{$newStatus}"
        );

        // ======================================================
        // LOG
        // ======================================================

        $this->log(
            strtoupper($type) . ' CONTROL',
            ucfirst($type)
            . ' diubah menjadi '
            . strtoupper($newStatus)
        );

        // ======================================================
        // RESPONSE
        // ======================================================

        return back()->with(
            $espOk ? 'success' : 'warning',

            $espOk
                ? ucfirst($type) . ' berhasil diubah'
                : ucfirst($type) . ' berubah lokal, ESP32 tidak merespon'
        );
    }

    // ======================================================
    // CHANGE MODE
    // ======================================================

    public function changeMode($mode)
    {
        $greenhouse = $this->greenhouse();
        if (!$greenhouse)
        {
            return back()->with(
                'error',
                'Greenhouse aktif tidak ditemukan'
            );
        }

        // ======================================================
        // VALIDASI MODE
        // ======================================================

        if (!in_array($mode, [

            'Manual',
            'Otomatis'

        ])) {

            return back()->with(
                'error',
                'Mode tidak valid'
            );
        }

        // ======================================================
        // USER SETTING
        // ======================================================

        $setting = $this->setting(

            $greenhouse->id
        );

        // ======================================================
        // UPDATE MODE
        // ======================================================

        $setting->update([

            'system_mode' => $mode
        ]);

        // ======================================================
        // ACTUATOR TYPES
        // ======================================================

        $types = [

            'pump',
            'fan',
            'lamp'
        ];

        foreach ($types as $type)
        {
            $actuator = $this->actuator(
                $greenhouse->id,
                $type
            );

            $actuator->update([
                'status' => 'off',
                'mode' =>
                    $mode === 'Manual'
                    ? 'manual'
                    : 'auto'
            ]);

            // ======================================================
            // MODE MANUAL
            // ======================================================

            if ($mode === 'Manual')
            {
                $this->sendEsp(
                    "/{$type}/off"
                );
            }
        }

        // ======================================================
        // LOG
        // ======================================================

        $this->log(

            'MODE CHANGE',
            'System mode diubah ke '
            . strtoupper($mode)
        );

        // ======================================================
        // RESPONSE
        // ======================================================

        return back()->with(

            'success',
            'Mode berhasil diubah ke '
            . strtoupper($mode)
        );
    }

    // ======================================================
    // RESET NODE
    // ======================================================

    public function resetNode()
    {
        $greenhouse = $this->greenhouse();

        if (!$greenhouse)
        {
            return back()->with(
                'error',
                'Greenhouse aktif tidak ditemukan'
            );
        }

        // ======================================================
        // RESET ACTUATOR
        // ======================================================

        Actuator::where(

            'greenhouse_id',
            $greenhouse->id

        )->update([

            'status' => 'off',
            'mode' => 'auto'
        ]);

        // ======================================================
        // RESET SETTING
        // ======================================================

        $setting = $this->setting(

            $greenhouse->id
        );

        $setting->update([

            'system_mode' => 'Otomatis'
        ]);

        // ======================================================
        // SEND RESET ESP
        // ======================================================

        $espOk = $this->sendEsp(

            '/reset'
        );

        // ======================================================
        // LOG
        // ======================================================

        $this->log(

            'RESET NODE',
            'Node greenhouse berhasil direset'
        );

        // ======================================================
        // RESPONSE
        // ======================================================

        return back()->with(

            $espOk ? 'success' : 'warning',

            $espOk
                ? 'Node berhasil direset'
                : 'Reset lokal berhasil, ESP32 tidak merespon'
        );
    }

    // ======================================================
    // CONTROL BUTTONS
    // ======================================================

    public function pump()
    {
        return $this->toggle('pump');
    }

    public function fan()
    {
        return $this->toggle('fan');
    }

    public function lamp()
    {
        return $this->toggle('lamp');
    }
}