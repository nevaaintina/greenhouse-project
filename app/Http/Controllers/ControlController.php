<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use App\Models\Setting;
use App\Models\Log;
use Illuminate\Support\Facades\Http;

class ControlController extends Controller
{
    // =========================
    // TOGGLE ACTUATOR
    // =========================

    private function toggle($type)
    {
        // =========================
        // AMBIL SETTING
        // =========================

        $setting = Setting::first();

        $mode = $setting->system_mode ?? 'Otomatis';

        // =========================
        // CEK MODE
        // =========================

        if ($mode == 'Otomatis') {

            return back()->with(

                'error',

                'Mode otomatis aktif'
            );
        }

        // =========================
        // AMBIL ACTUATOR
        // =========================

        $actuator = Actuator::where(

                'type',
                $type

            )->where(

                'greenhouse_id',
                1

            )->first();

        // =========================
        // CREATE ACTUATOR
        // =========================

        if (!$actuator) {

            $actuator = Actuator::create([

                'greenhouse_id' => 1,

                'name' => ucfirst($type),

                'type' => $type,

                'status' => 'off',

                'mode' => 'manual'
            ]);
        }

        // =========================
        // TOGGLE STATUS
        // =========================

        $newStatus = $actuator->status == 'on'

            ? 'off'

            : 'on';

        $actuator->status = $newStatus;

        $actuator->mode = 'manual';

        $actuator->save();

        // =========================
        // KIRIM KE ESP32
        // =========================

        try {

            Http::timeout(3)->get(

                "http://192.168.1.100/$type/$newStatus"
            );

        } catch (\Exception $e) {

            // skip kalau ESP mati
        }

        // =========================
        // LOG ACTIVITY
        // =========================

        Log::create([

            'user_id' => auth()->id() ?? 1,

            'activity' => strtoupper($type).' CONTROL',

            'description' =>

                ucfirst($type) .

                ' diubah menjadi ' .

                strtoupper($newStatus),

            'created_at' => now()
        ]);

        // =========================
        // RETURN
        // =========================

        return back()->with(

            'success',

            ucfirst($type) .

            ' berhasil diubah'
        );
    }

    // =========================
    // CHANGE MODE
    // =========================

    public function changeMode($mode)
    {
        // =========================
        // VALIDASI MODE
        // =========================

        if (!in_array($mode, [

            'Manual',
            'Otomatis'

        ])) {

            return back()->with(

                'error',

                'Mode tidak valid'
            );
        }

        // =========================
        // AMBIL SETTING
        // =========================

        $setting = Setting::first();

        if (!$setting) {

            $setting = Setting::create([

                'greenhouse_id' => 1,

                'system_mode' => $mode
            ]);

        } else {

            $setting->system_mode = $mode;

            $setting->save();
        }

        // =========================
        // MODE OTOMATIS
        // =========================

        if ($mode == 'Otomatis') {

            $pump = Actuator::firstOrCreate(

                [
                    'greenhouse_id' => 1,
                    'type' => 'pump'
                ],

                [
                    'name' => 'Pompa Air',
                    'status' => 'off',
                    'mode' => 'auto'
                ]
            );

            $fan = Actuator::firstOrCreate(

                [
                    'greenhouse_id' => 1,
                    'type' => 'fan'
                ],

                [
                    'name' => 'Kipas',
                    'status' => 'off',
                    'mode' => 'auto'
                ]
            );

            $lamp = Actuator::firstOrCreate(

                [
                    'greenhouse_id' => 1,
                    'type' => 'lamp'
                ],

                [
                    'name' => 'Lampu UV',
                    'status' => 'off',
                    'mode' => 'auto'
                ]
            );

            // reset actuator
            $pump->update([

                'status' => 'off',

                'mode' => 'auto'
            ]);

            $fan->update([

                'status' => 'off',

                'mode' => 'auto'
            ]);

            $lamp->update([

                'status' => 'off',

                'mode' => 'auto'
            ]);
        }

        // =========================
        // MODE MANUAL
        // =========================

        if ($mode == 'Manual') {

            // matikan semua actuator
            Actuator::query()->update([

                'status' => 'off',

                'mode' => 'manual'
            ]);

            // kirim OFF ke ESP32
            try {

                Http::timeout(3)->get(
                    "http://192.168.1.100/pump/off"
                );

                Http::timeout(3)->get(
                    "http://192.168.1.100/fan/off"
                );

                Http::timeout(3)->get(
                    "http://192.168.1.100/lamp/off"
                );

            } catch (\Exception $e) {

                // skip kalau ESP mati
            }
        }

        // =========================
        // LOG MODE
        // =========================

        Log::create([

            'user_id' => auth()->id() ?? 1,

            'activity' => 'MODE CHANGE',

            'description' =>

                'System mode diubah ke ' .

                strtoupper($mode),

            'created_at' => now()
        ]);

        // =========================
        // RETURN
        // =========================

        return redirect()->back()->with(

            'success',

            'Mode berhasil diubah ke ' .

            strtoupper($mode)
        );
    }

    // =========================
    // RESET NODE
    // =========================

    public function resetNode()
    {
        // =========================
        // RESET ACTUATOR
        // =========================

        Actuator::query()->update([

            'status' => 'off',

            'mode' => 'auto'
        ]);

        // =========================
        // RESET MODE
        // =========================

        $setting = Setting::first();

        if ($setting) {

            $setting->system_mode = 'Otomatis';

            $setting->save();
        }

        // =========================
        // KIRIM RESET KE ESP32
        // =========================

        try {

            Http::timeout(3)->get(
                "http://192.168.1.100/reset"
            );

        } catch (\Exception $e) {

            // skip kalau ESP mati
        }

        // =========================
        // LOG RESET
        // =========================

        Log::create([

            'user_id' => auth()->id() ?? 1,

            'activity' => 'RESET NODE',

            'description' =>

                'Node greenhouse berhasil direset',

            'created_at' => now()
        ]);

        // =========================
        // RETURN
        // =========================

        return back()->with(

            'success',

            'Node berhasil direset'
        );
    }

    // =========================
    // CONTROL BUTTON
    // =========================

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