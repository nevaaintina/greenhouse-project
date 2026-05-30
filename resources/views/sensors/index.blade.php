@extends('layouts.app')

@section('title', 'Sensor Management')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10">

    <div class="flex items-center gap-3">

        <button
            class="block md:hidden text-forest p-1 focus:outline-none"
            onclick="toggleSidebar()">

            <span class="material-symbols-rounded text-3xl">
                menu
            </span>

        </button>

        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Sensor Management

            </h2>

            <p class="text-xs text-gray-400 mt-1">

                Last Update:
                {{ now()->format('d M Y H:i') }}

            </p>

        </div>

    </div>

    <a href="/profile"
    class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">

        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">

            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

        </div>

        <span class="text-sm font-semibold text-forest hidden sm:block">

            {{ auth()->user()->name }}

        </span>

    </a>

</header>



<!-- SENSOR GRID -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

@for($i = 0; $i < 4; $i++)

@php

    $sensor = $sensors[$i] ?? null;

    $value = $sensor?->latestData?->value;

    $color = 'text-gray-300';

    $barColor = 'bg-gray-300';

    $label = '';

    $icon = 'sensors';

    $progress = 0;

    $unit = '';

    // ======================================================
    // SENSOR LOGIC
    // ======================================================

    if ($sensor && $value !== null)
    {
        // 🌱 SOIL
        if ($sensor->type == 'soil')
        {
            $icon = 'water_drop';

            $unit = '%';

            $progress =
                max(0, min($value, 100));

            if ($value < $soilMin)
            {
                $color = 'text-red-500';

                $barColor = 'bg-red-500';

                $label = 'Kering';
            }

            elseif ($value <= $soilMax)
            {
                $color = 'text-blue-500';

                $barColor = 'bg-blue-500';

                $label = 'Ideal';
            }

            else
            {
                $color = 'text-yellow-500';

                $barColor = 'bg-yellow-500';

                $label = 'Basah';
            }
        }

        // 🌡️ TEMPERATURE
        elseif ($sensor->type == 'temperature')
        {
            $icon = 'device_thermostat';

            $unit = '°C';

            $progress =
                max(
                    0,
                    min(($value / 50) * 100, 100)
                );

            if ($value > $tempMax)
            {
                $color = 'text-red-500';

                $barColor = 'bg-red-500';

                $label = 'Panas';
            }

            elseif ($value >= $tempMin)
            {
                $color = 'text-green-500';

                $barColor = 'bg-green-500';

                $label = 'Ideal';
            }

            else
            {
                $color = 'text-blue-500';

                $barColor = 'bg-blue-500';

                $label = 'Dingin';
            }
        }

        // 💧 HUMIDITY
        elseif ($sensor->type == 'humidity')
        {
            $icon = 'humidity_percentage';

            $unit = '%';

            $progress =
                max(0, min($value, 100));

            if ($value < $humMin)
            {
                $color = 'text-yellow-500';

                $barColor = 'bg-yellow-500';

                $label = 'Kering';
            }

            elseif ($value <= $humMax)
            {
                $color = 'text-green-500';

                $barColor = 'bg-green-500';

                $label = 'Ideal';
            }

            else
            {
                $color = 'text-blue-500';

                $barColor = 'bg-blue-500';

                $label = 'Lembab';
            }
        }

        // 💡 LIGHT
        elseif ($sensor->type == 'light')
        {
            $icon = 'wb_sunny';

            $unit = 'lx';

            $progress =
                max(
                    0,
                    min(($value / 1000) * 100, 100)
                );

            if ($value < $lightMin)
            {
                $color = 'text-yellow-500';

                $barColor = 'bg-yellow-500';

                $label = 'Gelap';
            }

            elseif ($value <= $lightMax)
            {
                $color = 'text-green-500';

                $barColor = 'bg-green-500';

                $label = 'Ideal';
            }

            else
            {
                $color = 'text-orange-500';

                $barColor = 'bg-orange-500';

                $label = 'Terang';
            }
        }
    }

@endphp

<!-- CARD -->
<div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden">

    <!-- BACKGROUND ICON -->
    <div class="absolute -bottom-4 -right-4 opacity-5 pointer-events-none">

        <span class="material-symbols-rounded text-9xl">

            {{ $icon }}

        </span>

    </div>

    <!-- HEADER -->
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

    <!-- VALUE -->
    <div class="mt-6 flex flex-col">

        <div class="flex items-baseline gap-1">

            <h4 class="text-5xl font-black {{ $color }}">

                {{ $value ?? '--' }}

            </h4>

            <span class="text-xl font-bold text-gray-400">

                {{ $unit }}

            </span>

        </div>

        @if($value !== null)

        <p class="text-xs font-bold uppercase {{ $color }} mt-1">

            {{ $label }}

        </p>

        @endif

    </div>

    <!-- PROGRESS -->
    <div class="w-full bg-gray-100 rounded-full h-2 mt-6">

        <div class="{{ $barColor }}
            h-2 rounded-full transition-all duration-1000"

            style="width: {{ $progress }}%">

        </div>

    </div>

    <!-- STATUS -->
    <div class="flex justify-between items-center mt-4">

        <div class="flex items-center gap-2">

            <div class="w-2 h-2 rounded-full
                {{ $sensor
                    ? 'bg-green-500 animate-pulse'
                    : 'bg-gray-300' }}">
            </div>

            <p class="text-[10px] font-bold uppercase
                {{ $sensor
                    ? 'text-green-600'
                    : 'text-gray-400' }}">

                {{ $sensor
                    ? 'Connected'
                    : 'Disconnected' }}

            </p>

        </div>

    </div>

</div>

@endfor

</div>

@endsection