@extends('layouts.app')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10">
    <div class="flex items-center gap-3">
        <!-- TOMBOL HAMBURGER -->
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                Sensor Management
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

<!-- MOBILE SIDEBAR OVERLAY (SESUAI SMARTGROW) -->
<div id="mobile-sidebar" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden transition-opacity">
    <div class="bg-forest w-72 h-full p-6 relative shadow-2xl text-white">
        <!-- TOMBOL X UNTUK MENUTUP -->
        <button class="absolute top-5 right-5 text-white/80 hover:text-white" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">close</span>
        </button>

        <!-- LOGO SMARTGROW -->
        <div class="flex items-center gap-3 mb-10 mt-4">
            <span class="material-symbols-rounded text-4xl text-green-400">potted_plant</span>
            <h1 class="text-xl font-bold tracking-widest uppercase">SmartGrow</h1>
        </div>

        <!-- NAVIGASI MENU LENGKAP -->
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

<!-- SENSOR GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

@for($i = 0; $i < 4; $i++)

    @php
        $sensor = $sensors[$i] ?? null;
        $value = $sensor->latestData->value ?? null;
    @endphp

    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">

        {{-- ICON BACKGROUND DECORATION --}}
        <div class="absolute -bottom-4 -right-4 opacity-5 pointer-events-none">
            <span class="material-symbols-rounded text-9xl">
                @if($sensor && $sensor->type == 'temperature') device_thermostat
                @elseif($sensor && $sensor->type == 'humidity') humidity_mid
                @elseif($sensor && $sensor->type == 'soil') water_drop
                @elseif($sensor && $sensor->type == 'light') wb_sunny
                @else sensors
                @endif
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
                    @if($sensor && $sensor->type == 'temperature') device_thermostat
                    @elseif($sensor && $sensor->type == 'humidity') humidity_mid
                    @elseif($sensor && $sensor->type == 'soil') water_drop
                    @elseif($sensor && $sensor->type == 'light') wb_sunny
                    @else help
                    @endif
                </span>
            </div>
        </div>

        {{-- VALUE --}}
        <div class="mt-6 flex items-baseline gap-1">
            <h4 class="text-5xl font-black {{ $value ? 'text-forest' : 'text-gray-300' }}">
                {{ $value ?? '--' }}
            </h4>
            <span class="text-xl font-bold text-gray-400">
                @if($sensor && $sensor->type == 'temperature') °C
                @elseif($sensor && $sensor->type == 'humidity') %
                @elseif($sensor && $sensor->type == 'soil') %
                @elseif($sensor && $sensor->type == 'light') lx
                @endif
            </span>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="w-full bg-gray-100 rounded-full h-2 mt-6">
            <div class="bg-forest h-2 rounded-full transition-all duration-1000"
                 style="width: {{ min($value ?? 0, 100) }}%">
            </div>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full {{ $sensor ? 'bg-green-500 animate-pulse' : 'bg-gray-300' }}"></div>
                <p class="text-[10px] font-bold uppercase {{ $sensor ? 'text-green-600' : 'text-gray-400' }}">
                    {{ $sensor ? 'Connected' : 'Disconnected' }}
                </p>
            </div>
            <p class="text-[10px] text-gray-300 font-mono">
                {{ $sensor ? 'ID: '.$sensor->greenhouse_id : 'UID: ---' }}
            </p>
        </div>
    </div>

@endfor

</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mobile-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }
</script>

@endsection