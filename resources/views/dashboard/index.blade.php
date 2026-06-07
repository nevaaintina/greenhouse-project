@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
// =========================================================================
// PERBAIKAN MUTLAK INITIAL LOAD: Membaca status string 'on' / 'off' langsung dari DB
// Mencegah komponen melompat kembali ke visual default saat halaman di-refresh (F5)
// =========================================================================
$pumpStatus = ($actuators['pump'] ?? 'off') === 'on';
$fanStatus  = ($actuators['fan'] ?? 'off') === 'on';
$lampStatus = ($actuators['lamp'] ?? 'off') === 'on';

if ($soil < $soilMin) { $soilStatus = 'Kering'; } 
elseif ($soil <= $soilMax) { $soilStatus = 'Ideal'; } 
else { $soilStatus = 'Basah'; }

if ($temp > $tempMax) { $tempStatus = 'Overheat'; } 
elseif ($temp >= $tempMin) { $tempStatus = 'Stable'; } 
else { $tempStatus = 'Cold'; }

if ($light < $lightMin) { $lightStatus = 'Low'; } 
elseif ($light <= $lightMax) { $lightStatus = 'Good'; } 
else { $lightStatus = 'High'; }
@endphp

<header class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-forest uppercase tracking-wide">
            Greenhouse Overview
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            Last Update: <span id="textLastUpdate">{{ now()->format('d M Y H:i:s') }}</span>
        </p>
    </div>

    <a href="{{ url('/profile') }}" class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border hover:bg-gray-50 transition">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">
            {{ auth()->user()->name }}
        </span>
    </a>
</header>

<div id="alertContainer" class="mb-6 space-y-4"></div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    {{-- Card Kelembapan Tanah --}}
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative overflow-hidden group border border-transparent hover:border-blue-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Kelembapan Tanah</p>
        <div class="relative w-28 h-28 rounded-full border-4 border-gray-100 shadow-inner flex items-center justify-center bg-gray-50 overflow-hidden">
            <div id="soilWaveVisual" class="absolute bottom-0 w-full bg-gradient-to-t from-blue-600 to-blue-400 transition-all duration-1000" style="height: {{ $soil }}%">
                <div class="absolute -top-4 left-0 w-[200%] h-5 bg-white/20 rounded-[40%] animate-wave"></div>
            </div>
            <div class="relative z-10 text-center">
                <h3 class="text-3xl font-black text-forest"><span id="textSoilCard">{{ $soil }}</span><span class="text-sm">%</span></h3>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-1.5">
            <span id="dotSoilCard" class="w-2 h-2 rounded-full {{ $soil < $soilMin ? 'bg-red-500 animate-pulse' : 'bg-blue-500' }}"></span>
            <p id="statusSoilCard" class="text-[11px] font-bold text-gray-600 uppercase">{{ $soilStatus }}</p>
        </div>
    </div>

    {{-- Card Suhu Udara --}}
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-orange-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Suhu Udara</p>
        <div class="relative w-28 h-28 flex items-center justify-center rounded-full bg-gray-50 border-4 border-gray-100">
            <span class="material-symbols-rounded absolute text-6xl text-orange-100 opacity-70">device_thermostat</span>
            <div class="flex items-center gap-2 z-10">
                <div id="barTempVisual" class="w-1 h-8 rounded-full transition-all duration-500 {{ $temp > $tempMax ? 'bg-red-500 animate-pulse opacity-100' : 'bg-gray-200 opacity-30' }}"></div>
                <div class="text-center">
                    <h3 class="text-3xl font-black text-gray-800 leading-none"><span id="textTempCard">{{ $temp }}</span><span class="text-sm text-gray-400">°C</span></h3>
                </div>
            </div>
        </div>
        <span id="statusTempCard" class="text-[10px] font-bold {{ $temp > $tempMax ? 'text-red-500 animate-pulse' : 'text-green-600' }} uppercase mt-4">
            {{ $tempStatus }}
        </span>
    </div>

    {{-- Card Kelembapan Udara --}}
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-emerald-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Kelembapan Udara</p>
        <div class="relative w-28 h-28 flex items-center justify-center rounded-full bg-gray-50 border-4 border-gray-100 overflow-hidden">
            <div class="absolute w-20 h-20 bg-emerald-400/20 rounded-full blur-xl animate-pulse"></div>
            <div class="absolute inset-0 flex items-center justify-center animate-float">
                <span class="material-symbols-rounded text-6xl text-emerald-100 opacity-80">air</span>
            </div>
            <div class="text-center z-10">
                <h3 class="text-3xl font-black text-gray-800 leading-none"><span id="textHumCard">{{ $hum }}</span><span class="text-sm text-gray-400">%</span></h3>
            </div>
        </div>
        <span class="text-[10px] font-bold text-emerald-600 uppercase mt-4">Optimal Air</span>
    </div>

    {{-- Card Intensitas Cahaya --}}
    <div class="bg-white/80 p-5 rounded-3xl shadow flex flex-col items-center relative group border border-transparent hover:border-yellow-200 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Intensitas Cahaya</p>
        <div class="relative w-28 h-28 flex items-center justify-center">
            <span class="material-symbols-rounded text-5xl text-yellow-500 animate-spin-slow absolute">wb_sunny</span>
            <div class="absolute inset-0 border-2 border-dashed border-yellow-200 rounded-full animate-spin-slow" style="animation-duration: 10s"></div>
        </div>
        <div class="text-center mt-2">
            <h3 class="text-xl font-black text-gray-800"><span id="textLightCard">{{ $light }}</span> <span class="text-[10px] text-gray-400 uppercase tracking-tighter">Lux</span></h3>
            <p id="statusLightCard" class="text-[9px] font-black text-yellow-700 uppercase bg-yellow-100 px-2 py-0.5 rounded-full mt-1">{{ $lightStatus }}</p>
        </div>
    </div>

    {{-- Weather Widget --}}
    <div id="weatherWidgetBg" class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-3xl shadow flex flex-col items-center justify-center relative border border-transparent hover:border-blue-100 transition-all">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">Cuaca Lokal</p>
        <div class="flex flex-col items-center gap-1">
            <span id="weatherWidgetIcon" class="material-symbols-rounded text-5xl text-blue-400 animate-bounce">partly_cloudy_day</span>
            <div class="text-center">
                <h3 id="weatherWidgetText" class="text-lg font-black text-gray-800 leading-tight">Cerah</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>
        <p class="text-[10px] font-bold uppercase mt-4 text-blue-400">Probabilitas Hujan: <span id="weatherWidgetRain">10%</span></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Live Ecosystem Component --}}
    <div class="lg:col-span-2 bg-white p-8 rounded-3xl shadow flex items-center gap-8">
        <div class="w-40 h-40 relative">
            <svg viewBox="0 0 200 200" class="animate-bounce-slow">
                <path d="M40,160 L160,160 L160,80 L100,30 L40,80 Z" fill="none" stroke="#2D5A27" stroke-width="4"/>
                <path id="svgEcosystemPump" d="M80,160 Q80,120 100,120 Q120,120 120,160" fill="{{ $pumpStatus ? '#34d399' : '#A16207' }}" />
                <circle id="svgEcosystemLightGlow" cx="100" cy="110" r="15" fill="#facc15" class="opacity-20 {{ $lampStatus ? 'animate-pulse' : 'hidden' }}" />
                <circle id="svgEcosystemLightCore" cx="100" cy="110" r="8" fill="{{ $lampStatus ? '#facc15' : '#e2e8f0' }}" />
            </svg>
        </div>

        <div>
            <h4 class="font-bold text-forest text-lg">Live Ecosystem</h4>
            <p class="text-xs text-gray-400 mt-0.5 mb-3">Actuator Control</p>
            <div class="flex flex-wrap gap-3">
                {{-- PERBAIKAN INITIAL BLADE STYLE --}}
                <span id="ecoBadgePump" class="{{ $pumpStatus ? 'bg-green-100 text-green-700 ring-2 ring-green-400/20' : 'bg-gray-100 text-gray-500' }} px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center gap-1.5 transition-all">
                    <span id="ecoIconPump" class="material-symbols-rounded text-sm {{ $pumpStatus ? 'animate-bounce' : '' }}">water_drop</span>
                    Pompa: <span id="ecoTextPump">{{ $pumpStatus ? 'ON' : 'OFF' }}</span>
                </span>

                <span id="ecoBadgeLamp" class="{{ $lampStatus ? 'bg-yellow-100 text-yellow-700 ring-2 ring-yellow-400/20' : 'bg-gray-100 text-gray-500' }} px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center gap-1.5 transition-all">
                    <span id="ecoIconLamp" class="material-symbols-rounded text-sm {{ $lampStatus ? 'animate-pulse' : '' }}">lightbulb</span>
                    Lampu UV: <span id="ecoTextLamp">{{ $lampStatus ? 'ON' : 'OFF' }}</span>
                </span>

                <span id="ecoBadgeFan" class="{{ $fanStatus ? 'bg-blue-100 text-blue-700 ring-2 ring-blue-400/20' : 'bg-gray-100 text-gray-500' }} px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center gap-1.5 transition-all">
                    <span id="ecoIconFan" class="material-symbols-rounded text-sm {{ $fanStatus ? 'animate-spin-slow' : '' }}">mode_fan</span>
                    Kipas: <span id="ecoTextFan">{{ $fanStatus ? 'ON' : 'OFF' }}</span>
                </span>
            </div>
        </div>
    </div>
{{-- Panel Mode Operasional --}}
    <div class="bg-white p-6 rounded-3xl shadow flex flex-col items-center justify-center">
        <div class="w-full space-y-2">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Operation Mode</p>
            <div class="flex gap-2 w-full">
                <button id="btnModeManual" type="button" onclick="openConfirmModal('/mode/Manual', 'MODE MANUAL', 'Kontrol actuator akan dialihkan sepenuhnya ke kontrol manual website.')" 
                    class="flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition flex items-center justify-center gap-1
                    {{ $mode === 'Manual' ? 'bg-forest text-white border-forest' : 'bg-white text-forest border-forest hover:bg-forest hover:text-white' }}">
                    <span class="material-symbols-rounded text-sm">handyman</span>
                    MANUAL
                </button>

                <button id="btnModeOtomatis" type="button" onclick="openConfirmModal('/mode/Otomatis', 'MODE KONTROL OTOMATIS', 'Sistem akan dikembalikan ke kendali pintar otomatis berbasis threshold.')" 
                    class="flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition flex items-center justify-center gap-1
                    {{ $mode === 'Otomatis' ? 'bg-forest text-white border-forest' : 'bg-white text-forest border-forest hover:bg-forest hover:text-white' }}">
                    <span class="material-symbols-rounded text-sm">auto_mode</span>
                    OTOMATIS
                </button>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col lg:flex-row gap-6 items-start mb-8">
    {{-- Panel Kontrol Sakelar Manual --}}
    <div class="w-full lg:w-[32%] bg-white p-6 rounded-3xl shadow flex flex-col gap-4">
        <h4 class="text-xs font-bold text-gray-400 uppercase">Manual Target Control</h4>
        
        {{-- Sakelar Pompa --}}
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <span id="btnIconPump" class="material-symbols-rounded text-blue-500 {{ $pumpStatus ? 'animate-bounce' : '' }}">water_drop</span>
                    Pompa Air
                </span>
                <div id="switchBgPump" class="w-10 h-5 rounded-full flex items-center px-1 transition-colors duration-300 {{ $pumpStatus ? 'bg-green-500' : 'bg-gray-300' }}">
                    <div id="switchDotPump" class="w-3 h-3 bg-white rounded-full transition-all duration-300 {{ $pumpStatus ? 'transform translate-x-5' : '' }}"></div>
                </div>
            </div>
            <button id="btnActionPump" type="button" onclick="openControlModal('/control/pump', '{{ $pumpStatus ? 'Matikan Pompa Air' : 'Nyalakan Pompa Air' }}', 'Apakah anda yakin ingin merubah status pompa air?')"
                class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold {{ $mode === 'Otomatis' ? 'opacity-40 cursor-not-allowed' : '' }}"
                {{ $mode === 'Otomatis' ? 'disabled' : '' }}>
                {{ $pumpStatus ? 'MATIKAN POMPA' : 'SIRAM SEKARANG' }}
            </button>
        </div>

        {{-- Sakelar Kipas --}}
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <span id="btnIconFan" class="material-symbols-rounded text-emerald-500 {{ $fanStatus ? 'animate-spin-slow' : '' }}">mode_fan</span>
                    Kipas
                </span>
                <div id="switchBgFan" class="w-10 h-5 rounded-full flex items-center px-1 transition-colors duration-300 {{ $fanStatus ? 'bg-blue-500' : 'bg-gray-300' }}">
                    <div id="switchDotFan" class="w-3 h-3 bg-white rounded-full transition-all duration-300 {{ $fanStatus ? 'transform translate-x-5' : '' }}"></div>
                </div>
            </div>
            <button id="btnActionFan" type="button" onclick="openControlModal('/control/fan', '{{ $fanStatus ? 'Matikan Kipas' : 'Nyalakan Kipas' }}', 'Apakah anda yakin ingin mengubah status operasional kipas?')"
                class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold {{ $mode === 'Otomatis' ? 'opacity-40 cursor-not-allowed' : '' }}"
                {{ $mode === 'Otomatis' ? 'disabled' : '' }}>
                {{ $fanStatus ? 'MATIKAN KIPAS' : 'NYALAKAN KIPAS' }}
            </button>
        </div>

        {{-- Sakelar Lampu UV --}}
        <div class="space-y-2">
            <div class="flex justify-between items-center bg-gray-50 p-4 rounded-xl">
                <span class="flex items-center gap-2 text-sm font-medium">
                    <span id="btnIconLamp" class="material-symbols-rounded text-yellow-500 {{ $lampStatus ? 'animate-pulse' : '' }}">lightbulb</span>
                    Lampu UV
                </span>
                <div id="switchBgLamp" class="w-10 h-5 rounded-full flex items-center px-1 transition-colors duration-300 {{ $lampStatus ? 'bg-yellow-500' : 'bg-gray-300' }}">
                    <div id="switchDotLamp" class="w-3 h-3 bg-white rounded-full transition-all duration-300 {{ $lampStatus ? 'transform translate-x-5' : '' }}"></div>
                </div>
            </div>
            <button id="btnActionLamp" type="button" onclick="openControlModal('/control/lamp', '{{ $lampStatus ? 'Matikan Lampu UV' : 'Nyalakan Lampu UV' }}', 'Apakah anda yakin ingin mengubah status pencahayaan lampu UV?')"
                class="w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold {{ $mode === 'Otomatis' ? 'opacity-40 cursor-not-allowed' : '' }}"
                {{ $mode === 'Otomatis' ? 'disabled' : '' }}>
                {{ $lampStatus ? 'MATIKAN LAMPU' : 'NYALAKAN LAMPU' }}
            </button>
        </div>
    </div>

    {{-- Chart Analisis --}}
    <div class="flex-1 w-full bg-white p-6 rounded-3xl shadow">
        <div class="flex justify-between items-center mb-6">
            <h4 class="font-bold text-forest">Moisture Weekly Analysis</h4>
            <div class="flex gap-2">
                <span class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase">
                    <div class="w-2 h-2 bg-red-400 rounded-sm"></div> Kering
                </span>
                <span class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase">
                    <div class="w-2 h-2 bg-blue-500 rounded-sm"></div> Ideal
                </span>
            </div>
        </div>
        <div class="h-64">
            <canvas id="soilHeatmapChart"></canvas>
        </div>
    </div>
</div>

{{-- MODALS CONFIG --}}
<div id="confirmModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[999] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-modal">
        <div class="p-6 text-center border-b border-gray-100">
            <div class="w-16 h-16 mx-auto rounded-full bg-forest/10 flex items-center justify-center mb-4">
                <span class="material-symbols-rounded text-4xl text-forest">settings</span>
            </div>
            <h3 id="confirmTitle" class="text-xl font-black text-forest uppercase">Ubah Mode</h3>
            <p id="confirmText" class="text-sm text-gray-500 mt-2 leading-relaxed">Yakin ingin mengubah skema manajemen operasional alat?</p>
        </div>
        <div class="grid grid-cols-2 gap-3 p-5 bg-gray-50">
            <button onclick="closeConfirmModal()" class="py-3 rounded-2xl border border-gray-200 text-gray-500 font-bold hover:bg-gray-100 transition">Batal</button>
            <button id="confirmActionBtn" class="py-3 rounded-2xl bg-forest text-white font-black hover:scale-95 transition">Ya, Konfirmasi</button>
        </div>
    </div>
</div>

<div id="controlModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-[999] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-modal">
        <div class="p-6 text-center border-b border-gray-100">
            <div class="w-16 h-16 mx-auto rounded-full bg-forest/10 flex items-center justify-center mb-4">
                <span class="material-symbols-rounded text-4xl text-forest">tune</span>
            </div>
            <h3 id="controlTitle" class="text-xl font-black text-forest uppercase">Manual Control</h3>
            <p id="controlText" class="text-sm text-gray-500 mt-2 leading-relaxed">Yakin ingin merubah siklus nyala hardware terpilih?</p>
        </div>
        <div class="grid grid-cols-2 gap-3 p-5 bg-gray-50">
            <button onclick="closeControlModal()" class="py-3 rounded-2xl border border-gray-200 text-gray-500 font-bold hover:bg-gray-100 transition">Batal</button>
            <button id="controlActionBtn" class="py-3 rounded-2xl bg-forest text-white font-black hover:scale-95 transition">Ya, Eksekusi</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const config = {
    soilMin: {{ $soilMin }}, soilMax: {{ $soilMax }},
    tempMin: {{ $tempMin }}, tempMax: {{ $tempMax }},
    humMin:  {{ $humMin }},  humMax:  {{ $humMax }},
    lightMin:{{ $lightMin }},lightMax:{{ $lightMax }}
};

async function fetchRealtimeStats() {
    try {
        const response = await fetch('{{ route("stats.realtime") }}');
        if (!response.ok) return;
        const data = await response.json();

        document.getElementById('textSoilCard').innerText = data.soil;
        document.getElementById('textTempCard').innerText = data.temp;
        document.getElementById('textHumCard').innerText  = data.hum;
        document.getElementById('textLightCard').innerText = data.light;
        document.getElementById('textLastUpdate').innerText = data.last_update || new Date().toLocaleTimeString('id-ID');

        document.getElementById('soilWaveVisual').style.height = data.soil + '%';

        updateCardStatus('Soil', data.soil, data.soil < config.soilMin, data.soil < config.soilMin ? 'Kering' : 'Ideal', 'bg-blue-500', 'bg-red-500');
        updateCardStatus('Temp', data.temp, data.temp > config.tempMax, data.temp > config.tempMax ? 'Overheat' : 'Stable', 'text-green-600', 'text-red-500');
        
        const barTemp = document.getElementById('barTempVisual');
        if (data.temp > config.tempMax) {
            barTemp.className = "w-1 h-8 rounded-full bg-red-500 animate-pulse opacity-100";
        } else {
            barTemp.className = "w-1 h-8 rounded-full bg-gray-200 opacity-30";
        }

        renderNotificationAlerts(data);
        updateModeUI(data.mode);

        // SYNC DATA REALTIME BADGE & SAKELAR
        updateActuatorUI('Pump', data.actuators.pump === 'on', '#34d399', '#A16207', 'bg-green-100 text-green-700 ring-2 ring-green-400/20', 'animate-bounce', 'water_drop', 'SIRAM SEKARANG', 'MATIKAN POMPA');
        updateActuatorUI('Fan',  data.actuators.fan === 'on',  '#60a5fa', '#e2e8f0', 'bg-blue-100 text-blue-700 ring-2 ring-blue-400/20', 'animate-spin-slow', 'mode_fan', 'NYALAKAN KIPAS', 'MATIKAN KIPAS');
        updateActuatorUI('Lamp', data.actuators.lamp === 'on', '#facc15', '#e2e8f0', 'bg-yellow-100 text-yellow-700 ring-2 ring-yellow-400/20', 'animate-pulse', 'lightbulb', 'NYALAKAN LAMPU', 'MATIKAN LAMPU');

        updateLocalWeatherWidget(data.temp);

    } catch (err) {
        console.error("AJAX Polling Error: ", err);
    }
}

function updateCardStatus(type, val, isAlert, text, normalClass, alertClass) {
    const txtEl = document.getElementById(`status${type}Card`);
    const dotEl = document.getElementById(`dot${type}Card`);
    if(txtEl) txtEl.innerText = text;
    if(dotEl) dotEl.className = `w-2 h-2 rounded-full ${isAlert ? alertClass + ' animate-pulse' : normalClass}`;
}

function updateModeUI(mode) {
    const manualBtn = document.getElementById('btnModeManual');
    const autoBtn = document.getElementById('btnModeOtomatis');
    
    const activeClass = "flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition flex items-center justify-center gap-1 bg-forest text-white border-forest";
    const inactiveClass = "flex-1 py-2 rounded-xl text-[10px] font-bold border-2 transition flex items-center justify-center gap-1 bg-white text-forest border-forest hover:bg-forest hover:text-white";

    if (mode === 'Manual') {
        manualBtn.className = activeClass; autoBtn.className = inactiveClass;
        ['Pump', 'Fan', 'Lamp'].forEach(act => {
            const btn = document.getElementById(`btnAction${act}`);
            btn.className = "w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold";
            btn.removeAttribute('disabled');
        });
    } else {
        manualBtn.className = inactiveClass; autoBtn.className = activeClass;
        ['Pump', 'Fan', 'Lamp'].forEach(act => {
            const btn = document.getElementById(`btnAction${act}`);
            btn.className = "w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold opacity-40 cursor-not-allowed";
            btn.setAttribute('disabled', 'true');
        });
    }
}

// PERBAIKAN TOTAL SINKRONISASI BADGE ECOSYSTEM DAN WARNA SAKELAR
function updateActuatorUI(name, isOn, svgOnColor, svgOffColor, badgeOnClass, animClass, iconName, textOff, textOn) {
    const svgLayer = document.getElementById(`svgEcosystem${name}`);
    if (svgLayer) svgLayer.setAttribute('fill', isOn ? svgOnColor : svgOffColor);
    
    if (name === 'Lamp') {
        const glow = document.getElementById('svgEcosystemLightGlow');
        const core = document.getElementById('svgEcosystemLightCore');
        if(glow) glow.className.baseVal = isOn ? 'opacity-20 animate-pulse' : 'hidden';
        if(core) core.setAttribute('fill', isOn ? '#facc15' : '#e2e8f0');
    }

    const badge = document.getElementById(`ecoBadge${name}`);
    const text  = document.getElementById(`ecoText${name}`);
    const icon  = document.getElementById(`ecoIcon${name}`);
    
    // =========================================================================
    // KUNCI UTAMA STYLING BADGE: Ukuran font dikunci 'text-[10px] font-bold' baik saat ON maupun OFF
    // =========================================================================
    if (badge) {
        badge.className = isOn 
            ? `${badgeOnClass} px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center gap-1.5 transition-all`
            : 'bg-gray-100 text-gray-500 px-3 py-1.5 rounded-xl text-[10px] font-bold flex items-center gap-1.5 transition-all';
    }
    if (text) text.innerText = isOn ? 'ON' : 'OFF';
    if (icon) icon.className = `material-symbols-rounded text-sm ${isOn ? animClass : ''}`;

    const switchBg  = document.getElementById(`switchBg${name}`);
    const switchDot = document.getElementById(`switchDot${name}`);
    const btnAction = document.getElementById(`btnAction${name}`);
    const btnIcon   = document.getElementById(`btnIcon${name}`);

    if (switchBg)  switchBg.className = `w-10 h-5 rounded-full flex items-center px-1 transition-colors duration-300 ${isOn ? 'bg-green-500' : 'bg-gray-300'}`;
    if (switchDot) switchDot.className = `w-3 h-3 bg-white rounded-full transition-all duration-300 ${isOn ? 'transform translate-x-5' : ''}`;
    
    if (btnAction) {
        btnAction.innerText = isOn ? textOn : textOff;
        btnAction.className = isOn 
            ? "w-full border border-red-500 text-red-500 py-3 rounded-xl hover:bg-red-500 hover:text-white transition text-sm font-semibold"
            : "w-full border border-forest text-forest py-3 rounded-xl hover:bg-forest hover:text-white transition text-sm font-semibold";
    }
    
    // PERBAIKAN UTK WARNA IKON TARGET MANUAL: Konsisten warna cerah (Biru, Hijau, Kuning) dengan tambahan animasi saat ON
    if (btnIcon) {
        let baseIconColor = "text-blue-500";
        if (name === 'Fan') baseIconColor = "text-emerald-500";
        if (name === 'Lamp') baseIconColor = "text-yellow-500";
        
        btnIcon.className = `material-symbols-rounded ${baseIconColor} ${isOn ? animClass : ''}`;
    }
}

function renderNotificationAlerts(data) {
    const container = document.getElementById('alertContainer');
    let html = '';
    if (data.soil > 0 && (data.soil < config.soilMin || data.soil > config.soilMax)) {
        html += `<div class="bg-red-50 border border-red-100 rounded-3xl p-5 flex items-start gap-4 shadow-sm animate-pulse"><div class="w-12 h-12 rounded-2xl bg-red-100 text-red-500 flex items-center justify-center flex-shrink-0"><span class="material-symbols-rounded">water_drop</span></div><div class="flex-1"><h4 class="font-bold text-red-600 text-sm uppercase">Soil Moisture Warning</h4><p class="text-sm text-red-500 mt-1">${data.soil < config.soilMin ? 'Kelembapan tanah terlalu rendah (Butuh Penyiraman)' : 'Kelembapan tanah terlalu tinggi'} (${data.soil}%)</p></div></div>`;
    }
    if (data.temp > 0 && (data.temp < config.tempMin || data.temp > config.tempMax)) {
        html += `<div class="bg-orange-50 border border-orange-100 rounded-3xl p-5 flex items-start gap-4 shadow-sm"><div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-500 flex items-center justify-center flex-shrink-0"><span class="material-symbols-rounded">device_thermostat</span></div><div class="flex-1"><h4 class="font-bold text-orange-600 text-sm uppercase">Temperature Warning</h4><p class="text-sm text-orange-500 mt-1">${data.temp > config.tempMax ? 'Suhu greenhouse terlalu tinggi (Sistem Mengaktifkan Kipas)' : 'Suhu greenhouse terlalu rendah'} (${data.temp}°C)</p></div></div>`;
    }
    container.innerHTML = html;
}

function updateLocalWeatherWidget(temp) {
    const wBg = document.getElementById('weatherWidgetBg');
    const wIcon = document.getElementById('weatherWidgetIcon');
    const wTxt = document.getElementById('weatherWidgetText');
    const wRain = document.getElementById('weatherWidgetRain');
    if (temp >= 32) {
        wBg.className = "bg-gradient-to-br from-yellow-50 to-white p-5 rounded-3xl shadow flex flex-col items-center justify-center relative border border-transparent hover:border-blue-100 transition-all";
        wIcon.className = "material-symbols-rounded text-5xl text-yellow-500 animate-bounce"; wIcon.innerText = "wb_sunny";
        wTxt.innerText = "Panas"; wRain.innerText = "5%";
    } else if (temp >= 26) {
        wBg.className = "bg-gradient-to-br from-blue-50 to-white p-5 rounded-3xl shadow flex flex-col items-center justify-center relative border border-transparent hover:border-blue-100 transition-all";
        wIcon.className = "material-symbols-rounded text-5xl text-blue-400 animate-bounce"; wIcon.innerText = "partly_cloudy_day";
        wTxt.innerText = "Cerah"; wRain.innerText = "10%";
    } else {
        wBg.className = "bg-gradient-to-br from-indigo-50 to-white p-5 rounded-3xl shadow flex flex-col items-center justify-center relative border border-transparent hover:border-blue-100 transition-all";
        wIcon.className = "material-symbols-rounded text-5xl text-indigo-500 animate-bounce"; wIcon.innerText = "thunderstorm";
        wTxt.innerText = "Hujan"; wRain.innerText = "80%";
    }
}

setInterval(fetchRealtimeStats, 3000);

function openControlModal(url, title, text) {
    const modal = document.getElementById('controlModal');
    document.getElementById('controlTitle').innerText = title;
    document.getElementById('controlText').innerText = text;
    document.getElementById('controlActionBtn').onclick = function() { submitPostForm(url); };
    modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeControlModal() {
    const modal = document.getElementById('controlModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}

function openConfirmModal(url, title, text) {
    const modal = document.getElementById('confirmModal');
    document.getElementById('confirmTitle').innerText = title;
    document.getElementById('confirmText').innerText = text;
    document.getElementById('confirmActionBtn').onclick = function() { submitPostForm(url); };
    modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeConfirmModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}

function openResetModal() {
    const modal = document.getElementById('resetModal');
    modal.classList.remove('hidden'); modal.classList.add('flex');
}
function closeResetModal() {
    const modal = document.getElementById('resetModal');
    modal.classList.add('hidden'); modal.classList.remove('flex');
}
function submitResetNode() { submitPostForm('/reset-node'); }

async function submitPostForm(url) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        const resData = await response.json();
        if (resData.success) {
            closeControlModal(); closeConfirmModal(); closeResetModal();
            fetchRealtimeStats(); 
        } else {
            alert("Peringatan: " + resData.message);
            closeControlModal(); closeConfirmModal(); closeResetModal();
        }
    } catch (err) {
        console.error("Gagal AJAX: ", err);
        window.location.reload();
    }
}

const soilWeekly = {!! json_encode($soilWeekly ?? [0,0,0,0,0,0,0]) !!};
const soilThresholdMin = {{ $soilMin }};
const ctx = document.getElementById('soilHeatmapChart');
if (ctx) {
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($weeklyLabels) !!},
            datasets: [{
                label: 'Moisture Level (%)',
                data: soilWeekly,
                backgroundColor: function(context) {
                    const val = context.dataset.data[context.dataIndex];
                    return val < soilThresholdMin ? '#f87171' : '#3b82f6';
                },
                borderRadius: 12,
                barThickness: 24
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, max: 100, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', fetchRealtimeStats);
</script>

<style>
@keyframes wave { 0% { transform: translateX(0) rotate(0deg); } 100% { transform: translateX(-50%) rotate(360deg); } }
.animate-wave { animation: wave 4s linear infinite; }
@keyframes spinSlow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
.animate-spin-slow { animation: spinSlow 3s linear infinite; }
@keyframes bounceSlow { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
.animate-bounce-slow { animation: bounceSlow 4s ease-in-out infinite; }
@keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-5px); } 100% { transform: translateY(0px); } }
.animate-float { animation: float 3s ease-in-out infinite; }
@keyframes modalPop { 0% { transform: scale(.95); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
.animate-modal { animation: modalPop .2s ease-out; }
</style>
@endsection