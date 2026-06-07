@extends('layouts.app')

@section('title', 'Sensor Management')

@section('content')

<header class="flex justify-between items-center mb-10">
    <div class="flex items-center gap-3">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                Sensor Management
            </h2>
            <p class="text-xs text-gray-400 mt-1">
                Last Update: <span id="sensorsLastUpdate">{{ now()->format('d M Y H:i:s') }}</span>
            </p>
        </div>
    </div>

    <a href="{{ url('/profile') }}" class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">
            {{ auth()->user()->name }}
        </span>
    </a>
</header>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
@for($i = 0; $i < 4; $i++)
@php
    $sensor = $sensors[$i] ?? null;
    $value = $sensor?->latestData?->value;
    
    // Inisialisasi awal default data sebelum AJAX memuat data riil
    $color = 'text-gray-300';
    $barColor = 'bg-gray-300';
    $label = 'Checking...';
    $icon = 'sensors';
    $progress = 0;
    $unit = '';
    $typeAttr = $sensor?->type ?? 'none';

    if ($sensor) {
        if ($sensor->type == 'soil') {
            $icon = 'water_drop'; $unit = '%';
        } elseif ($sensor->type == 'temperature') {
            $icon = 'device_thermostat'; $unit = '°C';
        } elseif ($sensor->type == 'humidity') {
            $icon = 'humidity_percentage'; $unit = '%';
        } elseif ($sensor->type == 'light') {
            $icon = 'wb_sunny'; $unit = 'lx';
        }
    }
@endphp

<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden sensor-card-block" data-type="{{ $typeAttr }}">

    <div class="absolute -bottom-4 -right-4 opacity-5 pointer-events-none">
        <span class="material-symbols-rounded text-9xl">
            {{ $icon }}
        </span>
    </div>

    <div class="flex justify-between items-start">
        <div>
            <h3 class="text-lg font-bold text-gray-700">
                {{ $sensor->name ?? 'Empty Slot' }}
            </h3>
            <p class="text-xs text-gray-400 uppercase tracking-widest">
                {{ $sensor->type ?? 'N/A' }}
            </p>
        </div>
        <div class="p-2 bg-gray-50 rounded-xl">
            <span class="material-symbols-rounded text-forest">
                {{ $icon }}
            </span>
        </div>
    </div>

    <div class="mt-6 flex flex-col">
        <div class="flex items-baseline gap-1">
            <h4 class="sensor-value-text text-5xl font-black {{ $color }}">
                {{ $value ?? '--' }}
            </h4>
            <span class="text-xl font-bold text-gray-400">
                {{ $unit }}
            </span>
        </div>
        <p class="sensor-label-status text-xs font-bold uppercase {{ $color }} mt-1">
            {{ $label }}
        </p>
    </div>

    <div class="w-full bg-gray-100 rounded-full h-2 mt-6">
        <div class="sensor-progress-bar {{ $barColor }} h-2 rounded-full transition-all duration-1000" style="width: {{ $progress }}%">
        </div>
    </div>

    <div class="flex justify-between items-center mt-4">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full {{ $sensor ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></div>
            <p class="text-[10px] font-bold uppercase {{ $sensor ? 'text-green-600' : 'text-gray-400' }}">
                {{ $sensor ? 'Connected' : 'Disconnected' }}
            </p>
        </div>
    </div>
</div>
@endfor
</div>

<script>
// Sinkronisasi konfigurasi batasan threshold dinamis langsung dari backend Laravel
const thresholds = {
    soil: { min: {{ $soilMin }}, max: {{ $soilMax }} },
    temp: { min: {{ $tempMin }}, max: {{ $tempMax }} },
    hum:  { min: {{ $humMin }},  max: {{ $humMax }} },
    light:{ min: {{ $lightMin }}, max: {{ $lightMax }} }
};

async function updateRealtimeSensors() {
    try {
        // Ambil payload JSON segar dari rute realtime yang telah kita perbaiki bersama
        const response = await fetch('{{ route("stats.realtime") }}');
        if (!response.ok) return;
        const data = await response.json();

        // Cari semua blok kartu sensor yang aktif di halaman
        const cards = document.querySelectorAll('.sensor-card-block');
        
        cards.forEach(card => {
            const type = card.getAttribute('data-type');
            if (type === 'none') return;

            let value = 0;
            let label = 'Ideal';
            let colorClass = 'text-green-500';
            let bgClass = 'bg-green-500';
            let progressPercent = 0;

            // Mapping kriteria penentuan warna dan hitungan matematis persentase progress bar
            if (type === 'soil') {
                value = data.soil;
                progressPercent = Math.max(0, Math.min(value, 100));
                if (value < thresholds.soil.min) {
                    colorClass = 'text-red-500'; bgClass = 'bg-red-500'; label = 'Kering';
                } else if (value <= thresholds.soil.max) {
                    colorClass = 'text-blue-500'; bgClass = 'bg-blue-500'; label = 'Ideal';
                } else {
                    colorClass = 'text-yellow-500'; bgClass = 'bg-yellow-500'; label = 'Basah';
                }
            } 
            else if (type === 'temperature') {
                value = data.temp;
                progressPercent = Math.max(0, Math.min((value / 50) * 100, 100));
                if (value > thresholds.temp.max) {
                    colorClass = 'text-red-500'; bgClass = 'bg-red-500'; label = 'Panas';
                } else if (value >= thresholds.temp.min) {
                    colorClass = 'text-green-500'; bgClass = 'bg-green-500'; label = 'Ideal';
                } else {
                    colorClass = 'text-blue-500'; bgClass = 'bg-blue-500'; label = 'Dingin';
                }
            } 
            else if (type === 'humidity') {
                value = data.hum;
                progressPercent = Math.max(0, Math.min(value, 100));
                if (value < thresholds.hum.min) {
                    colorClass = 'text-yellow-500'; bgClass = 'bg-yellow-500'; label = 'Kering';
                } else if (value <= thresholds.hum.max) {
                    colorClass = 'text-green-500'; bgClass = 'bg-green-500'; label = 'Ideal';
                } else {
                    colorClass = 'text-blue-500'; bgClass = 'bg-blue-500'; label = 'Lembab';
                }
            } 
            else if (type === 'light') {
                value = data.light;
                progressPercent = Math.max(0, Math.min((value / 1000) * 100, 100));
                if (value < thresholds.light.min) {
                    colorClass = 'text-yellow-500'; bgClass = 'bg-yellow-500'; label = 'Gelap';
                } else if (value <= thresholds.light.max) {
                    colorClass = 'text-green-500'; bgClass = 'bg-green-500'; label = 'Ideal';
                } else {
                    colorClass = 'text-orange-500'; bgClass = 'bg-orange-500'; label = 'Terang';
                }
            }

            // Manipulasi DOM Element secara spesifik tanpa mengedipkan layar
            const valEl = card.querySelector('.sensor-value-text');
            const lblEl = card.querySelector('.sensor-label-status');
            const barEl = card.querySelector('.sensor-progress-bar');

            if (valEl) {
                valEl.innerText = value;
                valEl.className = `sensor-value-text text-5xl font-black ${colorClass}`;
            }
            if (lblEl) {
                lblEl.innerText = label;
                lblEl.className = `sensor-label-status text-xs font-bold uppercase ${colorClass} mt-1`;
            }
            if (barEl) {
                barEl.className = `sensor-progress-bar ${bgClass} h-2 rounded-full transition-all duration-1000`;
                barEl.style.width = `${progressPercent}%`;
            }
        });

        // Update teks indikator deteksi clock server terakhir pada header halaman
        document.getElementById('sensorsLastUpdate').innerText = data.last_update || new Date().toLocaleTimeString('id-ID');

    } catch (error) {
        console.error("Gagal melakukan sinkronisasi data sensor secara realtime:", error);
    }
}

// Eksekusi trigger polling setiap 3 detik secara konstan di background thread browser
setInterval(updateRealtimeSensors, 3000);

// Panggil sekali di awal untuk instan render data segar sesaat setelah halaman selesai dimuat
document.addEventListener('DOMContentLoaded', updateRealtimeSensors);
</script>

@endsection