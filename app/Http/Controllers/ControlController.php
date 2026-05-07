<?php

namespace App\Http\Controllers;

use App\Models\Actuator;
use Illuminate\Support\Facades\Http;

class ControlController extends Controller
{
    private function toggle($type)
    {
        $actuator = Actuator::where('type', $type)->first();

        if (!$actuator) {
            return back()->with('error', 'Actuator tidak ditemukan');
        }

        // toggle status
        $newStatus = $actuator->status === 'on' ? 'off' : 'on';

        $actuator->update([
            'status' => $newStatus,
            'mode' => 'manual'
        ]);

        // 🔥 kirim ke ESP32
        Http::get("http://192.168.1.100/$type/$newStatus");

        return back();
    }

    public function pump() { return $this->toggle('pump'); }
    public function fan()  { return $this->toggle('fan'); }
    public function lamp() { return $this->toggle('lamp'); }
}