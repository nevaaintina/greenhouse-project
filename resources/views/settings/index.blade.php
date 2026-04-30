@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="/settings/update" method="POST" class="space-y-8 pb-10">
@csrf

<!-- 🔥 SYSTEM MODE -->
<section class="grid grid-cols-1 sm:grid-cols-2 gap-4">

    <!-- AUTO -->
    <label class="p-5 bg-white rounded-2xl shadow flex items-center gap-3 cursor-pointer">
        <span class="material-symbols-rounded text-green-600 text-3xl">auto_mode</span>
        <div>
            <input type="radio" name="system_mode" value="auto"
                {{ ($setting->system_mode ?? 'Otomatis') == 'Otomatis' ? 'checked' : '' }}>
            <p class="font-bold">Mode Otomatis</p>
        </div>
    </label>

    <!-- MANUAL -->
    <label class="p-5 bg-white rounded-2xl shadow flex items-center gap-3 cursor-pointer">
        <span class="material-symbols-rounded text-orange-500 text-3xl">settings</span>
        <div>
            <input type="radio" name="system_mode" value="manual"
                {{ ($setting->system_mode ?? 'Otomatis') == 'Manual' ? 'checked' : '' }}>
            <p class="font-bold">Mode Manual</p>
        </div>
    </label>

</section>

<!-- 🔥 THRESHOLD -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- SUHU -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">
        <label class="flex items-center gap-2">
            <span class="material-symbols-rounded text-orange-500">device_thermostat</span>
            Suhu
        </label>
        <div class="flex gap-2">
            <input type="number" name="temp_min" value="{{ $setting->temperature_min ?? 20 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Min">
            <input type="number" name="temp_max" value="{{ $setting->temperature_max ?? 30 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Max">
        </div>
    </div>

    <!-- TANAH -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">
        <label class="flex items-center gap-2">
            <span class="material-symbols-rounded text-green-600">water_drop</span>
            Tanah
        </label>
        <div class="flex gap-2">
            <input type="number" name="soil_min" value="{{ $setting->soil_moisture_min ?? 40 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Min">
            <input type="number" name="soil_max" value="{{ $setting->soil_moisture_max ?? 80 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Max">
        </div>
    </div>

    <!-- HUMIDITY -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">
        <label class="flex items-center gap-2">
            <span class="material-symbols-rounded text-blue-500">humidity_mid</span>
            Humidity
        </label>
        <div class="flex gap-2">
            <input type="number" name="hum_min" value="{{ $setting->humidity_min ?? 50 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Min">
            <input type="number" name="hum_max" value="{{ $setting->humidity_max ?? 80 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Max">
        </div>
    </div>

    <!-- CAHAYA -->
    <div class="bg-white p-6 rounded-2xl shadow space-y-3">
        <label class="flex items-center gap-2">
            <span class="material-symbols-rounded text-yellow-500">wb_sunny</span>
            Cahaya
        </label>
        <div class="flex gap-2">
            <input type="number" name="light_min" value="{{ $setting->light_min ?? 300 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Min">
            <input type="number" name="light_max" value="{{ $setting->light_max ?? 800 }}" class="w-full p-2 bg-gray-100 rounded" placeholder="Max">
        </div>
    </div>

</section>

<!-- BUTTON -->
<div class="flex justify-end">
    <button type="submit" class="bg-green-700 text-white px-6 py-3 rounded-xl flex items-center gap-2">
        <span class="material-symbols-rounded">save</span>
        SIMPAN
    </button>
</div>

</form>

@endsection