@extends('layouts.app')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10 px-2">
    <div class="flex items-center gap-3">
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">System Settings</h2>
            <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mt-1">Configure Threshold Range Preferences</p>
        </div>
    </div>

    <div class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name,0,1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">{{ auth()->user()->name }}</span>
    </div>
</header>

<!-- MOBILE SIDEBAR OVERLAY -->
<div id="mobile-sidebar" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden transition-opacity">
    <div class="bg-forest w-72 h-full p-6 relative shadow-2xl text-white">
        <button class="absolute top-5 right-5 text-white/80 hover:text-white" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">close</span>
        </button>
        <div class="flex items-center gap-3 mb-10 mt-4">
            <span class="material-symbols-rounded text-4xl text-green-400">potted_plant</span>
            <h1 class="text-xl font-bold tracking-widest uppercase">SmartGrow</h1>
        </div>
        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="flex items-center gap-4 p-3 {{ Request::is('dashboard*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">grid_view</span> Dashboard</a>
            <a href="/sensors" class="flex items-center gap-4 p-3 {{ Request::is('sensors*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">sensors</span> Sensors</a>
            <a href="/grafik" class="flex items-center gap-4 p-3 {{ Request::is('grafik*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">show_chart</span> Grafik & Riwayat</a>
            <a href="/logs" class="flex items-center gap-4 p-3 {{ Request::is('logs*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">history</span> Log Activity</a>
            <a href="/profile" class="flex items-center gap-4 p-3 {{ Request::is('profile*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">person</span> Profile</a>
            <a href="/settings" class="flex items-center gap-4 p-3 {{ Request::is('settings*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition"><span class="material-symbols-rounded">settings</span> Pengaturan</a>
        </nav>
    </div>
</div>

<main class="max-w-7xl mx-auto">
    <form action="#" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- 1. SOIL MOISTURE -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-rounded">water_drop</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-700 leading-none">Soil Moisture</h3>
                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">Kelembapan Tanah</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Min (%)</label>
                        <input type="number" name="soil_min" value="30" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-bold text-blue-600">
                    </div>
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Max (%)</label>
                        <input type="number" name="soil_max" value="70" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition font-bold text-blue-600">
                    </div>
                </div>
            </div>

            <!-- 2. TEMPERATURE -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-rounded">device_thermostat</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-700 leading-none">Temperature</h3>
                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">Suhu Udara</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Min (°C)</label>
                        <input type="number" name="temp_min" value="24" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition font-bold text-orange-600">
                    </div>
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Max (°C)</label>
                        <input type="number" name="temp_max" value="32" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none transition font-bold text-orange-600">
                    </div>
                </div>
            </div>

            <!-- 3. HUMIDITY -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-rounded">humidity_mid</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-700 leading-none">Air Humidity</h3>
                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">Kelembapan Udara</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Min (%)</label>
                        <input type="number" name="hum_min" value="50" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition font-bold text-emerald-600">
                    </div>
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Max (%)</label>
                        <input type="number" name="hum_max" value="80" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition font-bold text-emerald-600">
                    </div>
                </div>
            </div>

            <!-- 4. LIGHT INTENSITY -->
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-500 rounded-xl flex items-center justify-center">
                        <span class="material-symbols-rounded">wb_sunny</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-700 leading-none">Light Intensity</h3>
                        <p class="text-[9px] text-gray-400 uppercase tracking-tighter mt-1">Intensitas Cahaya</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Min (LUX)</label>
                        <input type="number" name="light_min" value="200" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition font-bold text-yellow-600">
                    </div>
                    <div class="relative">
                        <label class="text-[9px] font-black text-gray-300 absolute -top-2 left-3 bg-white px-1 z-10 uppercase">Max (LUX)</label>
                        <input type="number" name="light_max" value="1000" class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 focus:ring-2 focus:ring-yellow-500/20 focus:border-yellow-500 outline-none transition font-bold text-yellow-600">
                    </div>
                </div>
            </div>

        </div>

        <!-- ACTION BUTTON -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6">
            <div class="text-center sm:text-left">
                <p class="text-xs font-bold text-gray-500">Auto-Save status: <span class="text-green-600">Active</span></p>
                <p class="text-[10px] text-gray-400 italic">Sistem akan menjaga parameter tetap berada di antara batas Min dan Max.</p>
            </div>
            <button type="submit" class="w-full sm:w-auto bg-forest text-white px-12 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-green-900/20 hover:scale-95 transition-all">
                Update Range Configuration
            </button>
        </div>
    </form>

    <div class="mt-20 border-t border-dashed border-gray-100 pt-10 text-center">
        <span class="material-symbols-rounded text-gray-200 text-5xl">memory</span>
        <p class="text-[10px] text-gray-300 uppercase tracking-[0.3em] mt-4">SmartGrow Node-Controller</p>
    </div>
</main>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mobile-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }
</script>

@endsection