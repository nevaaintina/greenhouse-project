@extends('layouts.app')

@section('title', 'Settings')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10">

    <div class="flex items-center gap-3">

        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Settings

            </h2>

            <p class="text-xs text-gray-400 mt-1">

                Last Update:
                {{ now()->format('d M Y H:i') }}

            </p>

        </div>

    </div>

    <a href="{{ url('/profile') }}"
    class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">

        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">

            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

        </div>

        <span class="text-sm font-semibold text-forest hidden sm:block">

            {{ auth()->user()->name }}

        </span>

    </a>

</header>

<!-- ======================================================
SMART GUIDE
====================================================== -->

<div class="mb-8 bg-gradient-to-br from-green-50 via-white to-emerald-50 border border-green-100 rounded-[2rem] shadow-sm overflow-hidden">

    <!-- HEADER -->
    <div class="p-6">

        <div class="flex items-center gap-4">

            <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">

                <span class="material-symbols-rounded text-green-600 text-3xl">

                    auto_awesome

                </span>

            </div>

            <div>

                <h3 class="text-lg font-black text-forest">

                    Smart Automation Guide

                </h3>

                <p class="text-xs text-gray-500 mt-1">

                    Pelajari bagaimana SmartGrow mengambil keputusan secara otomatis berdasarkan data sensor.

                </p>

            </div>

        </div>

    </div>

    <!-- EXPANDABLE -->
    <details class="border-t border-green-100">

        <summary
        class="cursor-pointer px-6 py-4 font-bold text-sm text-forest hover:bg-green-50 transition flex items-center justify-between">

            <span>

                📖 Lihat Penjelasan Lengkap

            </span>

        </summary>

        <div class="p-6 pt-2">

            <!-- INTRO -->
            <div class="bg-white rounded-3xl border border-gray-100 p-5 mb-5">

                <h4 class="font-black text-forest mb-2">

                    Bagaimana Sistem Bekerja?

                </h4>

                <p class="text-sm text-gray-600 leading-relaxed">

                    SmartGrow memantau kondisi greenhouse secara real-time melalui sensor.
                    Ketika nilai sensor melewati batas yang telah ditentukan,
                    sistem akan mengambil tindakan otomatis untuk menjaga kondisi tanaman tetap ideal.

                </p>

            </div>

            <!-- RULES -->
            <div class="grid md:grid-cols-3 gap-4">

                <!-- SOIL -->
                <div class="bg-white rounded-3xl border border-blue-100 p-5">

                    <div class="flex items-center gap-2 mb-3">

                        <span class="material-symbols-rounded text-blue-500">

                            water_drop

                        </span>

                        <h4 class="font-black text-blue-600">

                            Pompa Air

                        </h4>

                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">

                        Jika kelembapan tanah turun di bawah nilai minimum,
                        sistem akan menyalakan pompa air secara otomatis.

                        Setelah kelembapan mencapai nilai maksimum,
                        pompa akan dimatikan untuk mencegah penyiraman berlebih.

                    </p>

                </div>

                <!-- FAN -->
                <div class="bg-white rounded-3xl border border-orange-100 p-5">

                    <div class="flex items-center gap-2 mb-3">

                        <span class="material-symbols-rounded text-orange-500">

                            mode_fan

                        </span>

                        <h4 class="font-black text-orange-600">

                            Kipas Pendingin

                        </h4>

                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">

                        Ketika suhu greenhouse melebihi batas maksimum,
                        kipas akan menyala untuk membantu menurunkan suhu.

                        Kipas akan berhenti saat suhu kembali ke rentang normal.

                    </p>

                </div>

                <!-- LIGHT -->
                <div class="bg-white rounded-3xl border border-yellow-100 p-5">

                    <div class="flex items-center gap-2 mb-3">

                        <span class="material-symbols-rounded text-yellow-500">

                            wb_sunny

                        </span>

                        <h4 class="font-black text-yellow-600">

                            Lampu Grow Light

                        </h4>

                    </div>

                    <p class="text-xs text-gray-600 leading-relaxed">

                        Jika intensitas cahaya terlalu rendah,
                        lampu akan menyala untuk membantu proses fotosintesis.

                        Saat cahaya sudah mencukupi,
                        lampu akan dimatikan secara otomatis.

                    </p>

                </div>

            </div>

            <!-- NOTE -->
            <div class="mt-5 bg-amber-50 border border-amber-200 rounded-3xl p-5">

                <h4 class="font-black text-amber-700 mb-2">

                    ⚠️ Penting

                </h4>

                <p class="text-xs text-amber-700 leading-relaxed">

                    Pengaturan batas minimum dan maksimum hanya digunakan saat
                    <strong>Mode Otomatis</strong> aktif.

                    Jika sistem berada pada
                    <strong>Mode Manual</strong>,
                    seluruh aktuator (pompa, kipas, dan lampu)
                    dikendalikan langsung oleh pengguna melalui dashboard.

                </p>

            </div>

        </div>

    </details>

</div>


<!-- ======================================================
MAIN
====================================================== -->

<main class="max-w-7xl mx-auto">

    <!-- SUCCESS -->
    @if(session('success'))

    <div
        class="mb-6 bg-green-50 border border-green-200
        text-green-700 px-4 py-3 rounded-2xl
        text-sm font-semibold">

        {{ session('success') }}

    </div>

    @endif


    <!-- ERROR -->
    @if(session('error'))

    <div
        class="mb-6 bg-red-50 border border-red-200
        text-red-700 px-4 py-3 rounded-2xl
        text-sm font-semibold">

        {{ session('error') }}

    </div>

    @endif


    <!-- VALIDATION ERROR -->
    @if($errors->any())

    <div
        class="mb-6 bg-red-50 border border-red-200
        text-red-700 px-4 py-3 rounded-2xl">

        <ul class="text-sm list-disc pl-5 space-y-1">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif



    <!-- ======================================================
    FORM
    ====================================================== -->

    <form
        action="{{ route('settings.update') }}"
        method="POST"
        class="space-y-8">

        @csrf

        <!-- GRID -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <!-- ======================================================
            SOIL
            ====================================================== -->

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                <div class="flex items-center gap-3 mb-6">

                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">

                        <span class="material-symbols-rounded">
                            water_drop
                        </span>

                    </div>

                    <div>

                        <h3 class="font-bold text-gray-700 leading-none">

                            Soil Moisture

                        </h3>

                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">

                            Kelembapan Tanah

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    <!-- MIN -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Min (%)

                        </label>

                        <input
                            type="number"

                            name="soil_min"

                            min="0"
                            max="100"

                            required

                            value="{{ old('soil_min', $setting->soil_moisture_min ?? 45) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-bold text-blue-600">

                    </div>

                    <!-- MAX -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Max (%)

                        </label>

                        <input
                            type="number"

                            name="soil_max"

                            min="0"
                            max="100"

                            required

                            value="{{ old('soil_max', $setting->soil_moisture_max ?? 70) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-bold text-blue-600">

                    </div>

                </div>

            </div>



            <!-- ======================================================
            TEMPERATURE
            ====================================================== -->

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                <div class="flex items-center gap-3 mb-6">

                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">

                        <span class="material-symbols-rounded">
                            device_thermostat
                        </span>

                    </div>

                    <div>

                        <h3 class="font-bold text-gray-700 leading-none">

                            Temperature

                        </h3>

                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">

                            Suhu Udara

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    <!-- MIN -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Min (°C)

                        </label>

                        <input
                            type="number"

                            name="temp_min"

                            required

                            value="{{ old('temp_min', $setting->temperature_min ?? 20) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition font-bold text-orange-600">

                    </div>

                    <!-- MAX -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Max (°C)

                        </label>

                        <input
                            type="number"

                            name="temp_max"

                            required

                            value="{{ old('temp_max', $setting->temperature_max ?? 28) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition font-bold text-orange-600">

                    </div>

                </div>

            </div>



            <!-- ======================================================
            HUMIDITY
            ====================================================== -->

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                <div class="flex items-center gap-3 mb-6">

                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">

                        <span class="material-symbols-rounded">
                            humidity_percentage
                        </span>

                    </div>

                    <div>

                        <h3 class="font-bold text-gray-700 leading-none">

                            Air Humidity

                        </h3>

                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">

                            Kelembapan Udara

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    <!-- MIN -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Min (%)

                        </label>

                        <input
                            type="number"

                            name="hum_min"

                            min="0"
                            max="100"

                            required

                            value="{{ old('hum_min', $setting->humidity_min ?? 40) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition font-bold text-emerald-600">

                    </div>

                    <!-- MAX -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Max (%)

                        </label>

                        <input
                            type="number"

                            name="hum_max"

                            min="0"
                            max="100"

                            required

                            value="{{ old('hum_max', $setting->humidity_max ?? 80) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition font-bold text-emerald-600">

                    </div>

                </div>

            </div>



            <!-- ======================================================
            LIGHT
            ====================================================== -->

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                <div class="flex items-center gap-3 mb-6">

                    <div class="w-10 h-10 bg-yellow-50 text-yellow-500 rounded-xl flex items-center justify-center">

                        <span class="material-symbols-rounded">
                            wb_sunny
                        </span>

                    </div>

                    <div>

                        <h3 class="font-bold text-gray-700 leading-none">

                            Light Intensity

                        </h3>

                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">

                            Intensitas Cahaya

                        </p>

                    </div>

                </div>

                <div class="space-y-4">

                    <!-- MIN -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Min (LUX)

                        </label>

                        <input
                            type="number"

                            name="light_min"

                            min="0"

                            required

                            value="{{ old('light_min', $setting->light_min ?? 300) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition font-bold text-yellow-600">

                    </div>

                    <!-- MAX -->
                    <div class="relative">

                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">

                            Max (LUX)

                        </label>

                        <input
                            type="number"

                            name="light_max"

                            min="0"

                            required

                            value="{{ old('light_max', $setting->light_max ?? 800) }}"

                            class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition font-bold text-yellow-600">

                    </div>

                </div>

            </div>

        </div>



        <!-- ======================================================
        BUTTON
        ====================================================== -->

        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6">

            <!-- INFO -->
            <div class="text-center sm:text-left">

                <p class="text-xs font-bold text-gray-500">

                    Auto-Save status:
                    <span class="text-green-600">

                        Active

                    </span>

                </p>

                <p class="text-[10px] text-gray-400 italic">

                    Sistem akan menjaga parameter tetap berada di antara batas Min dan Max.

                </p>

            </div>

            <!-- SUBMIT -->
            <button
                type="submit"

                class="w-full sm:w-auto bg-forest text-white px-12 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-green-900/20 hover:scale-95 transition-all">

                Update Range Configuration

            </button>

        </div>

    </form>


</main>



@endsection