@extends('layouts.app')

@section('content')

@php
    $map = $sensors->keyBy('type');

    function getVal($map, $type, $default){
        return optional(optional($map->get($type))->latestData)->value ?? $default;
    }

    $soil  = getVal($map, 'soil', 70);
    $temp  = getVal($map, 'temperature', 26.5);
    $hum   = getVal($map, 'humidity', 55);
    $light = getVal($map, 'light', 450);

    $dash = 220 - (220 * $soil / 100);
@endphp

<!-- HEADER -->
<header class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
            Greenhouse Overview
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            Last Update: {{ now()->format('d M Y H:i') }}
        </p>
    </div>

    <a href="/profile"
       class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <span class="text-sm font-semibold text-forest">
            {{ auth()->user()->name }}
        </span>
    </a>
</header>

<!-- SENSOR CARD -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">

    <!-- SOIL -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center">
        <span class="material-symbols-rounded text-3xl text-green-600 animate-bounce">water_drop</span>
        <p class="text-xs text-gray-400 mt-2">Soil Moisture</p>
        <h3 class="text-2xl font-bold">{{ $soil }}%</h3>
        <div class="w-full bg-gray-200 h-1 mt-3 rounded-full">
            <div class="bg-green-600 h-1 rounded-full animate-pulse" style="width: {{ $soil }}%"></div>
        </div>
    </div>

    <!-- TEMP -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center">
        <span class="material-symbols-rounded text-3xl text-orange-400 animate-pulse">device_thermostat</span>
        <p class="text-xs text-gray-400">Suhu</p>
        <h3 class="text-2xl font-bold">{{ $temp }}°C</h3>
        <p class="text-xs {{ $temp > 30 ? 'text-red-500 animate-pulse' : 'text-green-600' }}">
            {{ $temp > 30 ? 'Panas' : 'Optimal' }}
        </p>
    </div>

    <!-- HUM -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center">
        <span class="material-symbols-rounded text-3xl text-blue-400 animate-bounce">humidity_mid</span>
        <p class="text-xs text-gray-400">Humidity</p>
        <h3 class="text-2xl font-bold">{{ $hum }}%</h3>
        <p class="text-xs text-gray-400">Normal</p>
    </div>

    <!-- LIGHT -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center">
        <span class="material-symbols-rounded text-3xl text-yellow-500 animate-spin-slow">wb_sunny</span>
        <p class="text-xs text-gray-400">Cahaya</p>
        <h3 class="text-2xl font-bold">{{ $light }} Lux</h3>
        <p class="text-xs {{ $light < 300 ? 'text-orange-500 animate-pulse' : 'text-green-600' }}">
            {{ $light < 300 ? 'Gelap (ON)' : 'Terang' }}
        </p>
    </div>

    <!-- WEATHER -->
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center">
        <span class="material-symbols-rounded text-3xl text-yellow-500 animate-bounce">light_mode</span>
        <p class="text-xs text-gray-400">Weather</p>
        <h3 class="text-lg font-bold">Cerah</h3>
        <p class="text-xs text-gray-400">{{ $temp }}°C</p>
    </div>

</div>

<!-- LIVE ECOSYSTEM -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow flex items-center gap-8">

        <div class="w-40 h-40">
            <svg viewBox="0 0 200 200">

                <path d="M40,160 L160,160 L160,80 L100,30 L40,80 Z"
                      fill="none" stroke="#2D5A27" stroke-width="4"/>

                <path d="M80,160 Q80,120 100,120 Q120,120 120,160"
                      fill="{{ $soil > 60 ? '#2E7D32' : '#A16207' }}" />

                <circle cx="100" cy="110" r="8"
                    fill="{{ $light < 300 ? '#facc15' : '#4ADE80' }}"
                    class="{{ $light < 300 ? 'animate-pulse' : '' }}" />

                <g class="{{ $soil < 50 ? 'opacity-20' : 'animate-pulse opacity-60' }}">
                    <circle cx="70" cy="130" r="3" fill="#3b82f6"/>
                    <circle cx="130" cy="140" r="3" fill="#3b82f6"/>
                </g>

            </svg>
        </div>

        <div>
            <h4 class="font-bold text-forest text-lg">Live Ecosystem</h4>
            <p class="text-xs text-gray-400 mb-3">Kondisi Greenhouse</p>

            <div class="flex gap-3">
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-bold animate-pulse">
                    Pompa: Aktif
                </span>
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-xs font-bold animate-pulse">
                    Lampu: ON
                </span>
            </div>
        </div>
    </div>

    <!-- BUTTON -->
    <div class="flex flex-col gap-4">
        <button class="bg-red-500 text-white p-4 rounded-xl font-bold hover:scale-95 transition">
            SHUTDOWN
        </button>

        <button class="bg-forest text-white p-4 rounded-xl font-bold hover:scale-95 transition">
            RESET NODE
        </button>
    </div>

</div>

<!-- CONTROL + CHART -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- MANUAL CONTROL -->
    <div class="bg-white p-6 rounded-3xl shadow flex flex-col gap-6">

        <h4 class="text-xs font-bold text-gray-400 uppercase">
            Manual Control
        </h4>

        <!-- POMPA -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-green-600 animate-bounce">water</span>
                    Pompa Air
                </span>
                <div class="w-10 h-5 bg-green-500 rounded-full flex items-center px-1">
                    <div class="w-3 h-3 bg-white rounded-full ml-auto animate-pulse"></div>
                </div>
            </div>

            <button class="w-full bg-forest text-white py-3 rounded-xl hover:scale-95 transition">
                SIRAM SEKARANG
            </button>
        </div>

        <!-- KIPAS -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-500 animate-spin-slow">mode_fan</span>
                    Kipas
                </span>
                <div class="w-10 h-5 bg-green-500 rounded-full flex items-center px-1">
                    <div class="w-3 h-3 bg-white rounded-full ml-auto"></div>
                </div>
            </div>

            <button class="w-full bg-forest text-white py-3 rounded-xl hover:scale-95 transition">
                NYALAKAN KIPAS
            </button>
        </div>

        <!-- LAMPU -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-yellow-500 animate-pulse">lightbulb</span>
                    Lampu UV
                </span>
                <div class="w-10 h-5 bg-gray-300 rounded-full flex items-center px-1">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                </div>
            </div>

            <button class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition">
                NYALAKAN LAMPU
            </button>
        </div>

    </div>

    <!-- CHART -->
    <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow">
        <h4 class="font-bold mb-4 text-forest">Moisture History</h4>
        <canvas id="soilChart"></canvas>
    </div>

</div>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('soilChart'), {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            data: [65,75,70,85,60,55,70],
            borderColor: '#2D5A27',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        plugins: { legend: { display: false } }
    }
});
</script>
<style>
@keyframes spinSlow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.animate-spin-slow {
    animation: spinSlow 3s linear infinite;
}
</style>

@endsection