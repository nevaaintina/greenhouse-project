@extends('layouts.app')

@section('content')

<style>
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}
.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>

<main class="max-w-7xl mx-auto p-5 md:p-8 text-slate-700">

    <!-- HEADER -->
<header class="flex justify-between items-center mb-10">
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
            <!-- DASHBOARD -->
            <a href="/dashboard" class="flex items-center gap-4 p-3 {{ Request::is('dashboard*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">grid_view</span> Dashboard
            </a>
            <!-- SENSORS -->
            <a href="/sensors" class="flex items-center gap-4 p-3 {{ Request::is('sensors*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <!-- GRAFIK -->
            <a href="/grafik" class="flex items-center gap-4 p-3 {{ Request::is('grafik*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">show_chart</span> Grafik & Riwayat
            </a>
            <!-- LOGS -->
            <a href="/logs" class="flex items-center gap-4 p-3 {{ Request::is('logs*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
            <!-- PROFILE -->
            <a href="/profile" class="flex items-center gap-4 p-3 {{ Request::is('profile*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">person</span> Profile
            </a>
            <!-- SETTINGS -->
            <a href="/settings" class="flex items-center gap-4 p-3 {{ Request::is('settings*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">settings</span> Pengaturan
            </a>
        </nav>
    </div>
</div>

    <!-- INSIGHT -->
    <div class="bg-gradient-to-br from-forest to-emerald-900 p-6 rounded-3xl text-white mb-8 animate-float">
        <p class="text-xs uppercase opacity-60">Smart System Insight</p>
        <h4 class="text-lg font-bold mt-1">Saran:</h4>
        <p class="text-sm opacity-80 mt-2 italic">
            "Tanamanmu terlihat sangat aktif berfotosintesis siang ini. Pastikan ventilasi terbuka untuk sirkulasi CO2 yang optimal!"
        </p>
    </div>

    <!-- FILTER SECTION -->
    <div class="bg-white p-6 rounded-3xl shadow-sm border mb-8 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-2">
            <span class="material-symbols-rounded text-forest">calendar_month</span>
            <h4 class="text-sm font-bold text-forest uppercase">Filter Data</h4>
        </div>
        <div class="flex flex-wrap gap-3 flex-1 justify-end">
            <input type="date" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest focus:outline-none focus:ring-2 focus:ring-forest/20">
            <select class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest focus:outline-none focus:ring-2 focus:ring-forest/20">
                <option>Harian</option>
                <option>Mingguan</option>
                <option>Bulanan</option>
                <option>Tahunan</option>
            </select>
            <button class="bg-forest text-white px-6 py-2 rounded-xl text-xs font-bold hover:scale-95 transition">Terapkan Filter</button>
        </div>
    </div>

    <!-- 4 GRAPHS GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- SOIL MOISTURE CHART -->
        <section class="bg-white p-6 rounded-3xl shadow-sm border">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-[10px] font-bold uppercase text-slate-400">Soil Moisture (%)</h4>
                <span class="material-symbols-rounded text-blue-500">water_drop</span>
            </div>
            <div class="h-[250px]">
                <canvas id="soilChart"></canvas>
            </div>
        </section>

        <!-- TEMPERATURE CHART -->
        <section class="bg-white p-6 rounded-3xl shadow-sm border">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-[10px] font-bold uppercase text-slate-400">Temperature (°C)</h4>
                <span class="material-symbols-rounded text-orange-500">device_thermostat</span>
            </div>
            <div class="h-[250px]">
                <canvas id="tempChart"></canvas>
            </div>
        </section>

        <!-- HUMIDITY CHART -->
        <section class="bg-white p-6 rounded-3xl shadow-sm border">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-[10px] font-bold uppercase text-slate-400">Humidity (%)</h4>
                <span class="material-symbols-rounded text-emerald-500">humidity_mid</span>
            </div>
            <div class="h-[250px]">
                <canvas id="humChart"></canvas>
            </div>
        </section>

        <!-- LIGHT INTENSITY CHART -->
        <section class="bg-white p-6 rounded-3xl shadow-sm border">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-[10px] font-bold uppercase text-slate-400">Light Intensity (Lux)</h4>
                <span class="material-symbols-rounded text-yellow-500">wb_sunny</span>
            </div>
            <div class="h-[250px]">
                <canvas id="lightChart"></canvas>
            </div>
        </section>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    if (sidebar) sidebar.classList.toggle('hidden');
}

// Labels interval 10 menit
const labels = ['12:00','12:10','12:20','12:30','12:40','12:50','13:00'];
const commonOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false } },
    scales: { 
        y: { beginAtZero: true },
        x: { grid: { display: false } }
    }
};

// 1. Soil Chart
new Chart(document.getElementById('soilChart'), {
    type: 'line',
    data: { labels: labels, datasets: [{ data: [60,62,61,58,59,60,61], borderColor: '#3b82f6', tension: 0.4, fill: true, backgroundColor: 'rgba(59, 130, 246, 0.1)' }] },
    options: commonOptions
});

// 2. Temp Chart
new Chart(document.getElementById('tempChart'), {
    type: 'line',
    data: { labels: labels, datasets: [{ data: [31,31.2,31.5,31.1,31.3,31.4,31.2], borderColor: '#f97316', tension: 0.4 }] },
    options: commonOptions
});

// 3. Hum Chart
new Chart(document.getElementById('humChart'), {
    type: 'line',
    data: { labels: labels, datasets: [{ data: [45,46,45,44,45,47,46], borderColor: '#10b981', tension: 0.4 }] },
    options: commonOptions
});

// 4. Light Chart
new Chart(document.getElementById('lightChart'), {
    type: 'line',
    data: { labels: labels, datasets: [{ data: [800,810,805,790,820,815,810], borderColor: '#fbbf24', tension: 0.4, fill: true, backgroundColor: 'rgba(251, 191, 36, 0.1)' }] },
    options: commonOptions
});
</script>

@endsection