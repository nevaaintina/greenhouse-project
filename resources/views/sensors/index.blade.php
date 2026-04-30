@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold text-green-800 mb-6">
    Sensor Management
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

@for($i = 0; $i < 4; $i++)

    @php
        $sensor = $sensors[$i] ?? null;
        $value = $sensor->latestData->value ?? null;
    @endphp

    <div class="bg-white p-6 rounded-2xl shadow-md relative">

        {{-- ICON --}}
        <div class="absolute top-4 right-4 text-2xl">
            @if($sensor && $sensor->type == 'temperature')
                🌡️
            @elseif($sensor && $sensor->type == 'humidity')
                💧
            @elseif($sensor && $sensor->type == 'soil')
                🌱
            @elseif($sensor && $sensor->type == 'light')
                ☀️
            @else
                ⚪
            @endif
        </div>

        {{-- NAMA --}}
        <h3 class="text-lg font-semibold text-gray-700">
            {{ $sensor->name ?? 'Belum ada sensor' }}
        </h3>

        {{-- VALUE --}}
        <p class="text-4xl font-bold mt-2 {{ $value ? 'text-green-700' : 'text-gray-400' }}">
            {{ $value ?? '--' }}

            <span class="text-lg">
                @if($sensor && $sensor->type == 'temperature') °C
                @elseif($sensor && $sensor->type == 'humidity') %
                @elseif($sensor && $sensor->type == 'soil') %
                @elseif($sensor && $sensor->type == 'light') lx
                @endif
            </span>
        </p>

        {{-- TYPE --}}
        <p class="text-sm text-gray-400 mt-1">
            {{ $sensor->type ?? 'Tidak tersedia' }}
        </p>

        {{-- PROGRESS --}}
        <div class="w-full bg-gray-200 rounded-full h-2 mt-4">
            <div class="bg-green-500 h-2 rounded-full"
                 style="width: {{ min($value ?? 0, 100) }}%">
            </div>
        </div>

        {{-- STATUS --}}
        <p class="text-xs mt-3 font-semibold {{ $value ? 'text-green-600' : 'text-gray-400' }}">
            {{ $sensor ? 'Aktif' : 'Belum terhubung' }}
        </p>

        {{-- FOOTER --}}
        <p class="text-xs text-gray-300 mt-2">
            {{ $sensor ? 'Greenhouse ID: '.$sensor->greenhouse_id : '-' }}
        </p>

    </div>

@endfor

</div>

@endsection