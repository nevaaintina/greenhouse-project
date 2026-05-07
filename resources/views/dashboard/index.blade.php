@extends('layouts.app')

@section('content')

@php

// =========================
// AUTO STATUS (STANDAR)
// =========================

// 🌱 SOIL
if ($soil < 45) {
    $soilStatus = 'Kering';
    $pumpAuto = 'on';
} elseif ($soil <= 70) {
    $soilStatus = 'Ideal';
    $pumpAuto = 'off';
} else {
    $soilStatus = 'Basah';
    $pumpAuto = 'off';
}

// 🌡️ TEMP
if ($temp > 28) {
    $tempStatus = 'Overheat';
    $fanAuto = 'on';
} elseif ($temp >= 20) {
    $tempStatus = 'Stable';
    $fanAuto = 'off';
} else {
    $tempStatus = 'Cold';
    $fanAuto = 'off';
}

// 💡 LIGHT
if ($light < 300) {
    $lightStatus = 'Low';
    $lampAuto = 'on';
} elseif ($light <= 800) {
    $lightStatus = 'Good';
    $lampAuto = 'off';
} else {
    $lightStatus = 'High';
    $lampAuto = 'off';
}

// =========================
// PRIORITAS (AUTO > DB)
// =========================
$actuators = [
    'pump' => $pumpAuto,
    'fan'  => $fanAuto,
    'lamp' => $lampAuto
];

@endphp

<!-- HEADER -->
<header class="flex justify-between items-center mb-10 px-2">
    <div class="flex items-center gap-3">
        <!-- TOMBOL HAMBURGER -->
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>

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

<!-- NOTIFIKASI KONDISI (TAMBAHAN) -->
@if($soil < 30 || $temp > 30)
<div class="mb-6 flex items-center justify-between bg-red-50 border-l-4 border-red-500 p-4 rounded-r-2xl shadow-sm animate-bounce-slow">
    <div class="flex items-center gap-3">
        <div class="bg-red-500 p-2 rounded-full text-white">
            <span class="material-symbols-rounded text-sm">warning</span>
        </div>
        <div>
            <h5 class="text-xs font-black text-red-800 uppercase tracking-tighter">System Alert</h5>
            <p class="text-[11px] text-red-600 font-medium">
                @if($soil < 30) 
                    Tanah terlalu kering ({{ $soil }}%). Pompa membutuhkan perhatian! 
                @elseif($temp > 30) 
                    Suhu berlebih ({{ $temp }}°C). Aktifkan kipas segera! 
                @endif
            </p>
        </div>
    </div>
    <button class="text-red-400 hover:text-red-600" onclick="this.parentElement.remove()">
        <span class="material-symbols-rounded">close</span>
    </button>
</div>
@endif

<!-- MOBILE SIDEBAR OVERLAY -->
<div id="mobile-sidebar" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden transition-opacity">
    <div class="bg-forest w-72 h-full p-6 relative shadow-2xl text-white">
        <!-- TOMBOL X -->
        <button class="absolute top-5 right-5 text-white/80 hover:text-white" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">close</span>
        </button>

        <div class="flex items-center gap-3 mb-10 mt-4">
            <span class="material-symbols-rounded text-4xl text-green-400">potted_plant</span>
            <h1 class="text-xl font-bold tracking-widest uppercase">SmartGrow</h1>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="flex items-center gap-4 p-3 {{ Request::is('dashboard*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">grid_view</span> Dashboard
            </a>
            <a href="/sensors" class="flex items-center gap-4 p-3 {{ Request::is('sensors*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <a href="/grafik" class="flex items-center gap-4 p-3 {{ Request::is('grafik*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">show_chart</span> Grafik & Riwayat
            </a>
            <a href="/logs" class="flex items-center gap-4 p-3 {{ Request::is('logs*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
            <a href="/profile" class="flex items-center gap-4 p-3 {{ Request::is('profile*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">person</span> Profile
            </a>
            <a href="/settings" class="flex items-center gap-4 p-3 {{ Request::is('settings*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">settings</span> Pengaturan
            </a>
        </nav>
    </div>
</div>

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

    <!-- ☁️ WEATHER (Dynamic Icon Section) -->
    <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-3xl shadow flex flex-col items-center justify-center relative border border-transparent hover:border-blue-100 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Local Weather</p>
        <div class="flex flex-col items-center gap-1">
            <span class="material-symbols-rounded text-5xl text-blue-400 animate-bounce">cloud</span>
            <div class="text-center">
                <h3 class="text-lg font-black text-gray-800 leading-tight">Cerah</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase">Malang, ID</p>
            </div>
        </div>
        <p class="text-[10px] font-bold text-blue-500 uppercase mt-4">Rain Prob: 10%</p>
    </div>

</div>

<style>
@keyframes float {
    0% { transform: translateY(0px) translateX(0px); }
    50% { transform: translateY(-5px) translateX(5px); }
    100% { transform: translateY(0px) translateX(0px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>

<!-- LIVE ECOSYSTEM -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow flex items-center gap-8">
        <div class="w-40 h-40 relative">
            <svg viewBox="0 0 200 200" class="animate-bounce-slow">
                <path d="M40,160 L160,160 L160,80 L100,30 L40,80 Z" fill="none" stroke="#2D5A27" stroke-width="4"/>
                <path d="M80,160 Q80,120 100,120 Q120,120 120,160" fill="{{ $soil > 60 ? '#2E7D32' : '#A16207' }}" />
                <circle cx="100" cy="110" r="15" fill="#facc15" class="opacity-20 animate-pulse" />
                <circle cx="100" cy="110" r="8" fill="{{ $light < 300 ? '#facc15' : '#4ADE80' }}" class="{{ $light < 300 ? 'animate-pulse' : '' }}" />
                <path d="M60,170 Q100,150 140,170" fill="none" stroke="#3b82f6" stroke-width="2" class="animate-pulse opacity-40" />
                <g class="{{ $soil < 50 ? 'opacity-20' : 'opacity-60' }}">
                    <circle cx="70" cy="130" r="3" fill="#3b82f6" class="animate-bounce" />
                    <circle cx="130" cy="140" r="3" fill="#3b82f6" class="animate-bounce" style="animation-delay: 0.5s" />
                </g>
            </svg>
        </div>

        <div>
            <h4 class="font-bold text-forest text-lg">Live Ecosystem</h4>

            <div class="flex flex-wrap gap-3 mt-3">

                <!-- 🌊 POMPA -->
                <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-[10px] font-bold animate-pulse flex items-center gap-1">
                    <span class="material-symbols-rounded text-sm">water_drop</span> 
                    Pompa: {{ ($actuators['pump'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}
                </span>

                <!-- 💡 LAMPU -->
                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-[10px] font-bold animate-pulse flex items-center gap-1">
                    <span class="material-symbols-rounded text-sm">lightbulb</span> 
                    Lampu: {{ ($actuators['lamp'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}
                </span>

                <!-- 🌬️ KIPAS -->
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1">
                    <span class="material-symbols-rounded text-sm animate-spin-slow">mode_fan</span> 
                    Kipas: {{ ($actuators['fan'] ?? 'off') == 'on' ? 'ON' : 'OFF' }}
                </span>

            </div>
        </div>
    </div>
    <div class="flex flex-col items-center justify-center bg-white p-6 rounded-3xl shadow">
        <button class="w-full bg-forest text-white p-5 rounded-2xl font-bold hover:scale-95 transition shadow-lg z-10">RESET NODE</button>
        
        <!-- TOMBOL MODE OPERASIONAL (TAMBAHAN) -->
        <div class="mt-4 w-full space-y-2">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Operation Mode</p>
            <div class="flex gap-2">
                <button class="flex-1 bg-white border-2 border-forest text-forest py-2 rounded-xl text-[10px] font-bold hover:bg-forest hover:text-white transition shadow-sm">
                    MANUAL
                </button>
                <button class="flex-1 bg-forest text-white py-2 rounded-xl text-[10px] font-bold border-2 border-forest hover:scale-95 transition shadow-md flex items-center justify-center gap-1">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-ping"></span> OTOMATIS
                </button>
            </div>
        </div>

        <div class="mt-4 opacity-30 animate-bounce-slow">
             <span class="material-symbols-rounded text-6xl text-forest">potted_plant</span>
        </div>
    </div>
</div>

<!-- CONTROL + HEATMAP STYLE ANALYSIS -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-3xl shadow flex flex-col gap-6">
        <h4 class="text-xs font-bold text-gray-400 uppercase">Manual Control</h4>

        <!-- 🌊 POMPA -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-green-600 animate-bounce">water</span> 
                    Pompa Air
                </span>

                <div class="w-10 h-5 
                    {{ ($actuators['pump'] ?? 'off')=='on' ? 'bg-green-500' : 'bg-gray-300' }} 
                    rounded-full flex items-center px-1">

                    <div class="w-3 h-3 bg-white rounded-full 
                        {{ ($actuators['pump'] ?? 'off')=='on' ? 'ml-auto animate-pulse' : '' }}">
                    </div>
                </div>
            </div>

            <form action="/control/pump" method="POST">
                @csrf
                <button type="submit" class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition">
                    SIRAM SEKARANG
                </button>
            </form>
        </div>

        <!-- 🌬️ KIPAS -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-blue-500 animate-spin-slow">mode_fan</span> 
                    Kipas
                </span>

                <div class="w-10 h-5 
                    {{ ($actuators['fan'] ?? 'off')=='on' ? 'bg-blue-500' : 'bg-gray-300' }} 
                    rounded-full flex items-center px-1">

                    <div class="w-3 h-3 bg-white rounded-full 
                        {{ ($actuators['fan'] ?? 'off')=='on' ? 'ml-auto' : '' }}">
                    </div>
                </div>
            </div>

            <form action="/control/fan" method="POST">
                @csrf
                <button type="submit" class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition">
                    NYALAKAN KIPAS
                </button>
            </form>
        </div>

        <!-- 💡 LAMPU -->
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2">
                    <span class="material-symbols-rounded text-yellow-500 animate-pulse">lightbulb</span> 
                    Lampu UV
                </span>

                <div class="w-10 h-5 
                    {{ ($actuators['lamp'] ?? 'off')=='on' ? 'bg-yellow-500' : 'bg-gray-300' }} 
                    rounded-full flex items-center px-1">

                    <div class="w-3 h-3 bg-white rounded-full 
                        {{ ($actuators['lamp'] ?? 'off')=='on' ? 'ml-auto' : '' }}">
                    </div>
                </div>
            </div>

            <form action="/control/lamp" method="POST">
                @csrf
                <button type="submit" class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition">
                    NYALAKAN LAMPU
                </button>
            </form>
        </div>

    </div>

    <!-- 📊 MOISTURE HEATMAP STYLE CHART -->
    <div class="lg:col-span-2 bg-white p-6 rounded-3xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-bold text-forest">Moisture Weekly Analysis</h4>
            <div class="flex gap-2">
                <span class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase"><div class="w-2 h-2 bg-red-400 rounded-sm"></div> Kering</span>
                <span class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase"><div class="w-2 h-2 bg-blue-500 rounded-sm"></div> Ideal</span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="soilHeatmapChart"></canvas>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const soilWeekly = {!! json_encode($soilWeekly ?? [0,0,0,0,0,0,0]) !!};

    const ctx = document.getElementById('soilHeatmapChart').getContext('2d');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Moisture Level',
                data: soilWeekly,
                backgroundColor: function(context) {
                    const val = context.dataset.data[context.dataIndex];
                    return val < 45 ? '#f87171' : '#3b82f6';
                },
                borderRadius: 12,
                borderSkipped: false,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('mobile-sidebar');
        if (sidebar) sidebar.classList.toggle('hidden');
    }
</script>

<style>
@keyframes wave {
    0% { transform: translateX(0) rotate(0deg); }
    100% { transform: translateX(-50%) rotate(360deg); }
}
.animate-wave { animation: wave 4s linear infinite; }

@keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes bounceSlow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
.animate-spin-slow { animation: spinSlow 3s linear infinite; }
.animate-bounce-slow { animation: bounceSlow 4s ease-in-out infinite; }
</style>

@endsection