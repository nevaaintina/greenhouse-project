@extends('layouts.app')

@section('content')

@php

use App\Models\Setting;
use App\Models\Actuator;

// =========================
// AMBIL SETTING
// =========================

$setting = Setting::first();

// 🔥 ENUM SETTINGS
// Manual / Otomatis

$mode = $setting->system_mode ?? 'Otomatis';

// =========================
// AMBANG BATAS DARI SETTINGS
// =========================

$soilMin  = $setting->soil_moisture_min ?? 45;
$soilMax  = $setting->soil_moisture_max ?? 70;

$tempMin  = $setting->temperature_min ?? 20;
$tempMax  = $setting->temperature_max ?? 28;

$humMin  = $setting->humidity_min ?? 40;
$humMax  = $setting->humidity_max ?? 80;

$lightMin = $setting->light_min ?? 300;
$lightMax = $setting->light_max ?? 800;

// =========================
// AUTO STATUS
// =========================

// 🌱 SOIL
if ($soil < $soilMin) {

    $soilStatus = 'Kering';
    $pumpAuto = 'on';

} elseif ($soil <= $soilMax) {

    $soilStatus = 'Ideal';
    $pumpAuto = 'off';

} else {

    $soilStatus = 'Basah';
    $pumpAuto = 'off';
}

// 🌡️ TEMPERATURE
if ($temp > $tempMax) {

    $tempStatus = 'Overheat';
    $fanAuto = 'on';

} elseif ($temp >= $tempMin) {

    $tempStatus = 'Stable';
    $fanAuto = 'off';

} else {

    $tempStatus = 'Cold';
    $fanAuto = 'off';
}

// 💡 LIGHT
if ($light < $lightMin) {

    $lightStatus = 'Low';
    $lampAuto = 'on';

} elseif ($light <= $lightMax) {

    $lightStatus = 'Good';
    $lampAuto = 'off';

} else {

    $lightStatus = 'High';
    $lampAuto = 'off';
}

// =========================
// PRIORITAS MODE
// =========================

// 🔥 ENUM SETTINGS
if ($mode == 'Manual') {

    // ambil actuator dari database
    $pump = Actuator::where(
        'greenhouse_id',
        1
    )->where(
        'type',
        'pump'
    )->first();

    $fan = Actuator::where(
        'greenhouse_id',
        1
    )->where(
        'type',
        'fan'
    )->first();

    $lamp = Actuator::where(
        'greenhouse_id',
        1
    )->where(
        'type',
        'lamp'
    )->first();

    // status actuator manual
    $actuators = [

        'pump' => $pump->status ?? 'off',

        'fan'  => $fan->status ?? 'off',

        'lamp' => $lamp->status ?? 'off',
    ];

} else {

    // =========================
    // AUTO MODE
    // =========================

    $actuators = [

        'pump' => $pumpAuto,

        'fan'  => $fanAuto,

        'lamp' => $lampAuto
    ];
}

@endphp

<!-- HEADER -->
<header class="flex justify-between items-center mb-10 px-2">
    <div class="flex items-center gap-3">

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                {{ Request::is('grafik*') ? 'Data Analytics' : (Request::is('dashboard*') ? 'Greenhouse Overview' : 'SmartGrow') }}
            </h2>
            <p class="text-xs text-gray-400 mt-1">
                Last Update: {{ now()->format('d M Y H:i') }}
            </p>
        </div>
    </div>

    <a href="/profile" class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">
            {{ auth()->user()->name }}
        </span>
    </a>
</header>

<!-- NOTIFIKASI KONDISI -->
@if(
    $soil < $soilMin ||
    $temp > $tempMax ||
    $light < $lightMin ||
    $hum < $humMin
)

<div class="mb-6 space-y-3">

    <!-- 🌱 SOIL ALERT -->
    @if($soil < $soilMin)

    <div class="flex items-center justify-between
        bg-red-50 border-l-4 border-red-500
        p-4 rounded-r-2xl shadow-sm animate-pulse">

        <div class="flex items-center gap-3">

            <div class="bg-red-500 p-2 rounded-full text-white">

                <span class="material-symbols-rounded text-sm">
                    water_drop
                </span>

            </div>

            <div>

                <h5 class="text-xs font-black text-red-800 uppercase tracking-tighter">
                    Soil Moisture Alert
                </h5>

                <p class="text-[11px] text-red-600 font-medium">

                    Tanah terlalu kering
                    ({{ $soil }}%).

                    Pompa otomatis akan aktif.

                </p>

            </div>

        </div>

        <button class="text-red-400 hover:text-red-600"
            onclick="this.parentElement.remove()">

            <span class="material-symbols-rounded">
                close
            </span>

        </button>

    </div>

    @endif

    <!-- 🌡️ TEMPERATURE ALERT -->
    @if($temp > $tempMax)

    <div class="flex items-center justify-between
        bg-orange-50 border-l-4 border-orange-500
        p-4 rounded-r-2xl shadow-sm animate-pulse">

        <div class="flex items-center gap-3">

            <div class="bg-orange-500 p-2 rounded-full text-white">

                <span class="material-symbols-rounded text-sm">
                    device_thermostat
                </span>

            </div>

            <div>

                <h5 class="text-xs font-black text-orange-800 uppercase tracking-tighter">
                    Temperature Warning
                </h5>

                <p class="text-[11px] text-orange-600 font-medium">

                    Suhu greenhouse terlalu tinggi
                    ({{ $temp }}°C).

                    Kipas otomatis dinyalakan.

                </p>

            </div>

        </div>

        <button class="text-orange-400 hover:text-orange-600"
            onclick="this.parentElement.remove()">

            <span class="material-symbols-rounded">
                close
            </span>

        </button>

    </div>

    @endif

    <!-- 💡 LIGHT ALERT -->
    @if($light < $lightMin)

    <div class="flex items-center justify-between
        bg-yellow-50 border-l-4 border-yellow-500
        p-4 rounded-r-2xl shadow-sm animate-pulse">

        <div class="flex items-center gap-3">

            <div class="bg-yellow-500 p-2 rounded-full text-white">

                <span class="material-symbols-rounded text-sm">
                    lightbulb
                </span>

            </div>

            <div>

                <h5 class="text-xs font-black text-yellow-800 uppercase tracking-tighter">
                    Light Intensity Alert
                </h5>

                <p class="text-[11px] text-yellow-700 font-medium">

                    Intensitas cahaya rendah
                    ({{ $light }} lux).

                    Lampu UV otomatis aktif.

                </p>

            </div>

        </div>

        <button class="text-yellow-500 hover:text-yellow-700"
            onclick="this.parentElement.remove()">

            <span class="material-symbols-rounded">
                close
            </span>

        </button>

    </div>

    @endif

    <!-- 💧 HUMIDITY ALERT -->
    @if($hum < $humMin)

    <div class="flex items-center justify-between
        bg-cyan-50 border-l-4 border-cyan-500
        p-4 rounded-r-2xl shadow-sm animate-pulse">

        <div class="flex items-center gap-3">

            <div class="bg-cyan-500 p-2 rounded-full text-white">

                <span class="material-symbols-rounded text-sm">
                    air
                </span>

            </div>

            <div>

                <h5 class="text-xs font-black text-cyan-800 uppercase tracking-tighter">
                    Humidity Warning
                </h5>

                <p class="text-[11px] text-cyan-700 font-medium">

                    Kelembapan udara rendah
                    ({{ $hum }}%).

                    Kondisi greenhouse kurang optimal.

                </p>

            </div>

        </div>

        <button class="text-cyan-500 hover:text-cyan-700"
            onclick="this.parentElement.remove()">

            <span class="material-symbols-rounded">
                close
            </span>

        </button>

    </div>

    @endif

</div>

@endif


<!-- SENSOR CARD SECTION -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

    <!-- 🌊 SOIL MOISTURE (Circular Liquid Gauge) -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative overflow-hidden group border border-transparent hover:border-blue-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Soil Moisture</p>
        <div class="relative w-28 h-28 rounded-full border-4 border-gray-100 shadow-inner flex items-center justify-center bg-gray-50 overflow-hidden">
            <div class="absolute bottom-0 w-full bg-gradient-to-t from-blue-600 to-blue-400 transition-all duration-1000" style="height: {{ $soil }}%">
                <div class="absolute -top-4 left-0 w-[200%] h-5 bg-white/20 rounded-[40%] animate-wave"></div>
            </div>
            <div class="relative z-10 text-center">
                <h3 class="text-3xl font-black {{ $soil > 50 ? 'text-white' : 'text-forest' }}">{{ $soil }}<span class="text-sm">%</span></h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full {{ $soil < 30 ? 'bg-red-500 animate-pulse' : 'bg-blue-500' }}"></span>
            <p class="text-[11px] font-bold text-gray-600 uppercase">{{ $soil < 45 ? 'Kering' : 'Ideal' }}</p>
        </div>
    </div>

    <!-- 🌡️ TEMPERATURE (With Side Red Bar Indication) -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-orange-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Temperature</p>
        <div class="relative w-28 h-28 flex items-center justify-center rounded-full bg-gray-50 border-4 border-gray-100">
            <span class="material-symbols-rounded absolute text-6xl text-orange-100 opacity-70">device_thermostat</span>
            
            <div class="flex items-center gap-2 z-10">
                <div class="w-1 h-8 rounded-full transition-all duration-500 {{ $temp > 30 ? 'bg-red-500 animate-pulse opacity-100' : 'bg-gray-200 opacity-30' }}"></div>
                <div class="text-center">
                    <h3 class="text-3xl font-black text-gray-800 leading-none">{{ $temp }}<span class="text-sm text-gray-400">°C</span></h3>
                </div>
            </div>
        </div>
        <span class="text-[10px] font-bold {{ $temp > 30 ? 'text-red-500 animate-pulse' : 'text-green-600' }} uppercase mt-4">
            {{ $temp > 28 ? 'Overheat' : 'Stable' }}
        </span>
    </div>

    <!-- 💧 HUMIDITY (Animated Mist Icon) -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-emerald-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Humidity</p>
        <div class="relative w-28 h-28 flex items-center justify-center rounded-full bg-gray-50 border-4 border-gray-100 overflow-hidden">
            <div class="absolute w-20 h-20 bg-emerald-400/20 rounded-full blur-xl animate-pulse"></div>
            
            <div class="absolute inset-0 flex items-center justify-center animate-float">
                <span class="material-symbols-rounded text-6xl text-emerald-100 opacity-80">air</span>
            </div>
            
            <div class="text-center z-10">
                <h3 class="text-3xl font-black text-gray-800 leading-none">{{ $hum }}<span class="text-sm text-gray-400">%</span></h3>
            </div>
        </div>
        <span class="text-[10px] font-bold text-emerald-600 uppercase mt-4">Optimal Air</span>
    </div>

    <!-- ☀️ LIGHT (Sunshine Radial Style) -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-yellow-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Light Intensity</p>
        <div class="relative w-28 h-28 flex items-center justify-center">
            <span class="material-symbols-rounded text-5xl text-yellow-500 animate-spin-slow absolute">wb_sunny</span>
            <div class="absolute inset-0 border-2 border-dashed border-yellow-200 rounded-full animate-spin-slow" style="animation-duration: 10s"></div>
        </div>
        <div class="text-center mt-2">
            <h3 class="text-xl font-black text-gray-800">{{ $light }} <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Lux</span></h3>
            <p class="text-[9px] font-black text-yellow-700 uppercase bg-yellow-100 px-2 py-0.5 rounded-full mt-1">{{ $light < 300 ? 'Low' : 'Good' }}</p>
        </div>
    </div>

   @php

// =========================
// WEATHER CONDITION
// =========================

if ($temp >= 32) {

    $weatherIcon = 'wb_sunny';
    $weatherText = 'Panas';
    $weatherColor = 'text-yellow-500';
    $weatherBg = 'from-yellow-50 to-white';
    $rainProb = '5%';

} elseif ($temp >= 26) {

    $weatherIcon = 'partly_cloudy_day';
    $weatherText = 'Cerah';
    $weatherColor = 'text-blue-400';
    $weatherBg = 'from-blue-50 to-white';
    $rainProb = '10%';

} elseif ($temp >= 20) {

    $weatherIcon = 'cloud';
    $weatherText = 'Berawan';
    $weatherColor = 'text-gray-400';
    $weatherBg = 'from-gray-50 to-white';
    $rainProb = '35%';

} else {

    $weatherIcon = 'thunderstorm';
    $weatherText = 'Hujan';
    $weatherColor = 'text-indigo-500';
    $weatherBg = 'from-indigo-50 to-white';
    $rainProb = '80%';
}

@endphp

<!-- ☁️ WEATHER -->
<div class="bg-gradient-to-br {{ $weatherBg }}
    p-5 rounded-3xl shadow flex flex-col items-center justify-center
    relative border border-transparent hover:border-blue-100 transition-all">

    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">

        Local Weather

    </p>

    <div class="flex flex-col items-center gap-1">

        <span class="material-symbols-rounded text-5xl
            {{ $weatherColor }} animate-bounce">

            {{ $weatherIcon }}

        </span>

        <div class="text-center">

            <h3 class="text-lg font-black text-gray-800 leading-tight">

                {{ $weatherText }}

            </h3>

            <p class="text-[10px] text-gray-400 font-bold uppercase">

                {{ now()->format('l') }}, ID

            </p>

        </div>

    </div>

    <p class="text-[10px] font-bold uppercase mt-4
        {{ $weatherColor }}">

        Rain Prob: {{ $rainProb }}

    </p>

</div>

</div>


<!-- LIVE ECOSYSTEM -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- LIVE ECOSYSTEM -->
    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow flex items-center gap-8">

        <div class="w-40 h-40 relative">

            <svg viewBox="0 0 200 200" class="animate-bounce-slow">

                <path d="M40,160 L160,160 L160,80 L100,30 L40,80 Z"
                    fill="none"
                    stroke="#2D5A27"
                    stroke-width="4"/>

                <path d="M80,160 Q80,120 100,120 Q120,120 120,160"
                    fill="{{ $soil > 60 ? '#2E7D32' : '#A16207' }}" />

                <circle cx="100" cy="110" r="15"
                    fill="#facc15"
                    class="opacity-20 animate-pulse" />

                <circle cx="100" cy="110" r="8"
                    fill="{{ $light < 300 ? '#facc15' : '#4ADE80' }}"
                    class="{{ $light < 300 ? 'animate-pulse' : '' }}" />

            </svg>

        </div>

        <div>

            <h4 class="font-bold text-forest text-lg">
                Live Ecosystem
            </h4>

            <div class="flex flex-wrap gap-3 mt-3">

                <!-- PUMP -->
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1">

                    <span class="material-symbols-rounded text-sm">
                        water_drop
                    </span>

                    Pompa:
                    {{ ($actuators['pump'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}

                </span>

                <!-- LAMP -->
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1">

                    <span class="material-symbols-rounded text-sm">
                        lightbulb
                    </span>

                    Lampu:
                    {{ ($actuators['lamp'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}

                </span>

                <!-- FAN -->
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1">

                    <span class="material-symbols-rounded text-sm animate-spin-slow">
                        mode_fan
                    </span>

                    Kipas:
                    {{ ($actuators['fan'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}

                </span>

            </div>

        </div>

    </div>

    <!-- CONTROL MODE -->
    <div class="bg-white p-6 rounded-3xl shadow flex flex-col items-center justify-center">

        <form action="/reset-node" method="POST">
    @csrf

    <!-- RESET NODE -->
<button
    type="button"

    onclick="openResetModal()"

    class="w-full bg-forest text-white p-5 rounded-2xl
    font-bold hover:scale-95 transition shadow-lg
    flex items-center justify-center gap-2">

    <span class="material-symbols-rounded">
        restart_alt
    </span>

    RESET NODE

</button>

<!-- =========================
RESET MODAL
========================= -->

<div id="resetModal"
class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[999]
hidden items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-md rounded-3xl
        shadow-2xl overflow-hidden animate-modal">

        <!-- HEADER -->
        <div class="p-6 text-center border-b border-gray-100">

            <div class="w-16 h-16 mx-auto rounded-full
                bg-red-100 flex items-center justify-center mb-4">

                <span class="material-symbols-rounded text-4xl text-red-500">
                    warning
                </span>

            </div>

            <h3
                class="text-xl font-black text-red-500 uppercase">

                Reset Node

            </h3>

            <p
                class="text-sm text-gray-500 mt-2 leading-relaxed">

                Semua actuator akan dimatikan dan sistem
                kembali ke mode otomatis.

            </p>

        </div>

        <!-- FOOTER -->
        <div class="grid grid-cols-2 gap-3 p-5 bg-gray-50">

            <!-- CANCEL -->
            <button
                onclick="closeResetModal()"

                class="py-3 rounded-2xl border border-gray-200
                text-gray-500 font-bold hover:bg-gray-100 transition">

                Batal

            </button>

            <!-- CONFIRM -->
            <button
                onclick="submitResetNode()"

                class="py-3 rounded-2xl bg-red-500 text-white
                font-black hover:scale-95 transition">

                Ya, Reset

            </button>

        </div>

    </div>

</div>
</form>

        <!-- MODE -->
        <div class="mt-4 w-full space-y-2">

            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">

                Operation Mode

            </p>

            <div class="flex gap-2 w-full">

    <!-- MANUAL -->
    <button
        type="button"

        onclick="openConfirmModal(
            '/mode/Manual',
            'MODE MANUAL',
            'Kontrol actuator akan dilakukan secara manual.'
        )"

        class="flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition
        flex items-center justify-center gap-1

        {{ $mode == 'Manual'
            ? 'bg-forest text-white border-forest'
            : 'bg-white text-forest border-forest hover:bg-forest hover:text-white' }}">

        <span class="material-symbols-rounded text-sm">
            handyman
        </span>

        MANUAL

    </button>

    <!-- AUTO -->
    <button
        type="button"

        onclick="openConfirmModal(
            '/mode/Otomatis',
            'MODE OTOMATIS',
            'Actuator akan berjalan otomatis sesuai threshold settings.'
        )"

        class="flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition
        flex items-center justify-center gap-1

        {{ $mode == 'Otomatis'
            ? 'bg-forest text-white border-forest'
            : 'bg-white text-forest border-forest hover:bg-forest hover:text-white' }}">

        <span class="material-symbols-rounded text-sm">
            auto_mode
        </span>

        OTOMATIS

    </button>

</div>

<!-- =========================
CONFIRM MODAL
========================= -->

<div id="confirmModal"
class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[999]
hidden items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-modal">

        <!-- HEADER -->
        <div class="p-6 text-center border-b border-gray-100">

            <div class="w-16 h-16 mx-auto rounded-full
                bg-forest/10 flex items-center justify-center mb-4">

                <span class="material-symbols-rounded text-4xl text-forest">
                    settings
                </span>

            </div>

            <h3 id="confirmTitle"
                class="text-xl font-black text-forest uppercase">

                Ubah Mode

            </h3>

            <p id="confirmText"
                class="text-sm text-gray-500 mt-2 leading-relaxed">

                Yakin ingin mengubah mode sistem?

            </p>

        </div>

        <!-- FOOTER -->
        <div class="grid grid-cols-2 gap-3 p-5 bg-gray-50">

            <!-- CANCEL -->
            <button
                onclick="closeConfirmModal()"

                class="py-3 rounded-2xl border border-gray-200
                text-gray-500 font-bold hover:bg-gray-100 transition">

                Batal

            </button>

            <!-- CONFIRM -->
            <button
                id="confirmActionBtn"

                class="py-3 rounded-2xl bg-forest text-white
                font-black hover:scale-95 transition">

                Ya, Ubah

            </button>
                        </div>
            </div>
            </div>
        </div>

        <div class="mt-4 opacity-30 animate-bounce-slow">

            <span class="material-symbols-rounded text-6xl text-forest">
                potted_plant
            </span>

        </div>

    </div>

</div>


<!-- CONTROL + HEATMAP STYLE ANALYSIS -->
<div class="flex flex-row gap-6 items-start">

<!-- =========================
MANUAL CONTROL
========================= -->

<div class="w-[32%] bg-white p-6 rounded-3xl shadow flex flex-col gap-4">

    <h4 class="text-xs font-bold text-gray-400 uppercase">
        Manual Control
    </h4>

    <!-- =========================
    PUMP
    ========================= -->

    @php
        $pumpStatus =
            ($actuators['pump'] ?? 'off') == 'on';
    @endphp

    <div class="space-y-2">

        <!-- CARD -->
        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">

            <span class="flex items-center gap-2 text-sm font-medium">

                <span class="material-symbols-rounded text-green-600 animate-bounce">
                    water
                </span>

                Pompa Air

            </span>

            <!-- SWITCH -->
            <div class="w-10 h-5 rounded-full flex items-center px-1

                {{ $pumpStatus
                    ? 'bg-green-500'
                    : 'bg-gray-300' }}">

                <div class="w-3 h-3 bg-white rounded-full

                    {{ $pumpStatus
                        ? 'ml-auto animate-pulse'
                        : '' }}">
                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <button
            type="button"

            onclick="openControlModal(
                '/control/pump',

                '{{ $pumpStatus
                    ? 'Matikan Pompa Air'
                    : 'Pompa Air' }}',

                '{{ $pumpStatus
                    ? 'Matikan pompa air sekarang?'
                    : 'Aktifkan pompa air sekarang?' }}'
            )"

            class="w-full border border-forest text-forest py-3 rounded-xl
            hover:bg-forest hover:text-white transition text-sm font-semibold

            {{ $mode == 'Otomatis'
                ? 'opacity-50 cursor-not-allowed'
                : '' }}"

            {{ $mode == 'Otomatis'
                ? 'disabled'
                : '' }}>

            {{ $pumpStatus
                ? 'MATIKAN POMPA'
                : 'SIRAM SEKARANG' }}

        </button>

    </div>


    <!-- =========================
    FAN
    ========================= -->

    @php
        $fanStatus =
            ($actuators['fan'] ?? 'off') == 'on';
    @endphp

    <div class="space-y-2">

        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">

            <span class="flex items-center gap-2 text-sm font-medium">

                <span class="material-symbols-rounded text-blue-500 animate-spin-slow">
                    mode_fan
                </span>

                Kipas

            </span>

            <div class="w-10 h-5 rounded-full flex items-center px-1

                {{ $fanStatus
                    ? 'bg-blue-500'
                    : 'bg-gray-300' }}">

                <div class="w-3 h-3 bg-white rounded-full

                    {{ $fanStatus
                        ? 'ml-auto'
                        : '' }}">
                </div>

            </div>

        </div>

        <button
            type="button"

            onclick="openControlModal(
                '/control/fan',

                '{{ $fanStatus
                    ? 'Matikan Kipas'
                    : 'Kipas' }}',

                '{{ $fanStatus
                    ? 'Matikan kipas greenhouse?'
                    : 'Nyalakan kipas greenhouse?' }}'
            )"

            class="w-full border border-forest text-forest py-3 rounded-xl
            hover:bg-forest hover:text-white transition text-sm font-semibold

            {{ $mode == 'Otomatis'
                ? 'opacity-50 cursor-not-allowed'
                : '' }}"

            {{ $mode == 'Otomatis'
                ? 'disabled'
                : '' }}>

            {{ $fanStatus
                ? 'MATIKAN KIPAS'
                : 'NYALAKAN KIPAS' }}

        </button>

    </div>


    <!-- =========================
    LAMP
    ========================= -->

    @php
        $lampStatus =
            ($actuators['lamp'] ?? 'off') == 'on';
    @endphp

    <div class="space-y-2">

        <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">

            <span class="flex items-center gap-2 text-sm font-medium">

                <span class="material-symbols-rounded text-yellow-500 animate-pulse">
                    lightbulb
                </span>

                Lampu UV

            </span>

            <div class="w-10 h-5 rounded-full flex items-center px-1

                {{ $lampStatus
                    ? 'bg-yellow-500'
                    : 'bg-gray-300' }}">

                <div class="w-3 h-3 bg-white rounded-full

                    {{ $lampStatus
                        ? 'ml-auto'
                        : '' }}">
                </div>

            </div>

        </div>

        <button
            type="button"

            onclick="openControlModal(
                '/control/lamp',

                '{{ $lampStatus
                    ? 'Matikan Lampu UV'
                    : 'Lampu UV' }}',

                '{{ $lampStatus
                    ? 'Matikan lampu UV greenhouse?'
                    : 'Aktifkan lampu UV greenhouse?' }}'
            )"

            class="w-full border border-forest text-forest py-3 rounded-xl
            hover:bg-forest hover:text-white transition text-sm font-semibold

            {{ $mode == 'Otomatis'
                ? 'opacity-50 cursor-not-allowed'
                : '' }}"

            {{ $mode == 'Otomatis'
                ? 'disabled'
                : '' }}>

            {{ $lampStatus
                ? 'MATIKAN LAMPU'
                : 'NYALAKAN LAMPU' }}

        </button>

    </div>

</div>


<!-- =========================
HEATMAP
========================= -->

<div class="flex-1 bg-white p-6 rounded-3xl shadow">

    <div class="flex justify-between items-center mb-6">

        <h4 class="font-bold text-forest">
            Moisture Weekly Analysis
        </h4>

        <div class="flex gap-2">

            <span class="flex items-center gap-1 text-[9px]
                font-bold text-gray-400 uppercase">

                <div class="w-2 h-2 bg-red-400 rounded-sm"></div>

                Kering

            </span>

            <span class="flex items-center gap-1 text-[9px]
                font-bold text-gray-400 uppercase">

                <div class="w-2 h-2 bg-blue-500 rounded-sm"></div>

                Ideal

            </span>

        </div>

    </div>

    <div class="h-64">

        <canvas id="soilHeatmapChart"></canvas>

    </div>

</div>

</div>


<!-- =========================
CONTROL MODAL
========================= -->

<div id="controlModal"
class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[999]
hidden items-center justify-center p-4">

    <div
        class="bg-white w-full max-w-md rounded-3xl
        shadow-2xl overflow-hidden animate-modal">

        <!-- HEADER -->
        <div class="p-6 text-center border-b border-gray-100">

            <div class="w-16 h-16 mx-auto rounded-full
                bg-forest/10 flex items-center justify-center mb-4">

                <span class="material-symbols-rounded text-4xl text-forest">
                    tune
                </span>

            </div>

            <h3
                id="controlTitle"
                class="text-xl font-black text-forest uppercase">

                Manual Control

            </h3>

            <p
                id="controlText"
                class="text-sm text-gray-500 mt-2 leading-relaxed">

                Yakin ingin menjalankan actuator?

            </p>

        </div>

        <!-- FOOTER -->
        <div class="grid grid-cols-2 gap-3 p-5 bg-gray-50">

            <!-- CANCEL -->
            <button
                onclick="closeControlModal()"

                class="py-3 rounded-2xl border border-gray-200
                text-gray-500 font-bold hover:bg-gray-100 transition">

                Batal

            </button>

            <!-- CONFIRM -->
            <button
                id="controlActionBtn"

                class="py-3 rounded-2xl bg-forest text-white
                font-black hover:scale-95 transition">

                Ya, Jalankan

            </button>

        </div>

    </div>

</div>

<!-- =======================================================
SMARTGROW DASHBOARD SCRIPTS
Control Modal • Mode Modal • Reset Modal • Chart • Animation
======================================================= -->

<!-- =======================================================
CHART.JS
======================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<!-- =======================================================
MAIN SCRIPT
======================================================= -->

<script>

//
// =======================================================
// CONTROL MODAL
// Manual actuator confirmation
// =======================================================
//

function openControlModal(url, title, text)
{
    const modal =
        document.getElementById('controlModal');

    document.getElementById('controlTitle').innerText =
        title;

    document.getElementById('controlText').innerText =
        text;

    document.getElementById('controlActionBtn').onclick =
        function ()
    {
        submitPostForm(url);
    };

    modal.classList.remove('hidden');

    modal.classList.add('flex');
}

function closeControlModal()
{
    const modal =
        document.getElementById('controlModal');

    modal.classList.add('hidden');

    modal.classList.remove('flex');
}



//
// =======================================================
// MODE MODAL
// Change Manual / Auto mode
// =======================================================
//

function openConfirmModal(url, title, text)
{
    const modal =
        document.getElementById('confirmModal');

    document.getElementById('confirmTitle').innerText =
        title;

    document.getElementById('confirmText').innerText =
        text;

    document.getElementById('confirmActionBtn').onclick =
        function ()
    {
        submitPostForm(url);
    };

    modal.classList.remove('hidden');

    modal.classList.add('flex');
}

function closeConfirmModal()
{
    const modal =
        document.getElementById('confirmModal');

    modal.classList.add('hidden');

    modal.classList.remove('flex');
}



//
// =======================================================
// RESET NODE MODAL
// Restart greenhouse node system
// =======================================================
//

function openResetModal()
{
    const modal =
        document.getElementById('resetModal');

    modal.classList.remove('hidden');

    modal.classList.add('flex');
}

function closeResetModal()
{
    const modal =
        document.getElementById('resetModal');

    modal.classList.add('hidden');

    modal.classList.remove('flex');
}

function submitResetNode()
{
    submitPostForm('/reset-node');
}



//
// =======================================================
// GENERIC POST FORM
// Reusable POST submit helper
// =======================================================
//

function submitPostForm(url)
{
    const form =
        document.createElement('form');

    form.method = 'POST';

    form.action = url;

    // CSRF TOKEN
    const csrf =
        document.createElement('input');

    csrf.type = 'hidden';

    csrf.name = '_token';

    csrf.value = '{{ csrf_token() }}';

    form.appendChild(csrf);

    document.body.appendChild(form);

    form.submit();
}



//
// =======================================================
// SOIL HEATMAP CHART
// Weekly moisture analysis
// =======================================================
//

const soilWeekly =
{!! json_encode($soilWeekly ?? [0,0,0,0,0,0,0]) !!};

const ctx =
document.getElementById('soilHeatmapChart');

if (ctx)
{
    new Chart(ctx,
    {
        type: 'bar',

        data:
        {
            labels:
            [
                'Mon',
                'Tue',
                'Wed',
                'Thu',
                'Fri',
                'Sat',
                'Sun'
            ],

            datasets:
            [{
                label: 'Moisture Level',

                data: soilWeekly,

                backgroundColor: function(context)
                {
                    const val =
                        context.dataset.data[
                            context.dataIndex
                        ];

                    return val < 45

                        ? '#f87171'

                        : '#3b82f6';
                },

                borderRadius: 12,

                borderSkipped: false,

                barThickness: 30
            }]
        },

        options:
        {
            responsive: true,

            maintainAspectRatio: false,

            plugins:
            {
                legend:
                {
                    display: false
                }
            },

            scales:
            {
                y:
                {
                    beginAtZero: true,

                    max: 100,

                    grid:
                    {
                        display: false
                    }
                },

                x:
                {
                    grid:
                    {
                        display: false
                    }
                }
            }
        }
    });
}

</script>



<!-- =======================================================
ANIMATION STYLE
Dashboard Animation Collection
======================================================= -->

<style>

//
// =======================================================
// WAVE ANIMATION
// =======================================================
//

@keyframes wave
{
    0%
    {
        transform:
            translateX(0)
            rotate(0deg);
    }

    100%
    {
        transform:
            translateX(-50%)
            rotate(360deg);
    }
}

.animate-wave
{
    animation:
        wave 4s linear infinite;
}



//
// =======================================================
// SPIN SLOW
// =======================================================
//

@keyframes spinSlow
{
    from
    {
        transform: rotate(0deg);
    }

    to
    {
        transform: rotate(360deg);
    }
}

.animate-spin-slow
{
    animation:
        spinSlow 3s linear infinite;
}



//
// =======================================================
// BOUNCE SLOW
// =======================================================
//

@keyframes bounceSlow
{
    0%,
    100%
    {
        transform: translateY(0);
    }

    50%
    {
        transform: translateY(-10px);
    }
}

.animate-bounce-slow
{
    animation:
        bounceSlow 4s ease-in-out infinite;
}



//
// =======================================================
// FLOAT ANIMATION
// =======================================================
//

@keyframes float
{
    0%
    {
        transform:
            translateY(0px)
            translateX(0px);
    }

    50%
    {
        transform:
            translateY(-5px)
            translateX(5px);
    }

    100%
    {
        transform:
            translateY(0px)
            translateX(0px);
    }
}

.animate-float
{
    animation:
        float 3s ease-in-out infinite;
}



//
// =======================================================
// MODAL POP ANIMATION
// =======================================================
//

@keyframes modalPop
{
    0%
    {
        transform: scale(.8);

        opacity: 0;
    }

    100%
    {
        transform: scale(1);

        opacity: 1;
    }
}

.animate-modal
{
    animation:
        modalPop .25s ease;
}

</style>

@endsection