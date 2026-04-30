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
    <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-extrabold text-forest">Data Analytics</h2>
            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                Analisis Performa Greenhouse
            </p>
        </div>

        <div class="flex gap-2 w-full sm:w-auto">
            <button class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white px-4 py-2 rounded-xl shadow-sm border">
                <span class="material-symbols-rounded text-forest text-sm">download</span>
                <span class="text-[9px] font-black text-slate-500 uppercase">Export PDF</span>
            </button>

            <div class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-forest px-4 py-2 rounded-xl text-white">
                <span class="material-symbols-rounded text-sm">calendar_today</span>
                <span class="text-[9px] font-black uppercase">Last 24 Hours</span>
            </div>
        </div>
    </header>

    <!-- CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        <div class="bg-orange-50 p-4 rounded-2xl border flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-orange-500">
                <span class="material-symbols-rounded">trending_up</span>
            </div>
            <div>
                <p class="text-xs font-bold text-orange-400">Avg Temp</p>
                <p class="text-sm font-black text-forest">28.4°C</p>
            </div>
        </div>

        <div class="bg-blue-50 p-4 rounded-2xl border flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-500">
                <span class="material-symbols-rounded">water_lux</span>
            </div>
            <div>
                <p class="text-xs font-bold text-blue-400">Lowest Soil</p>
                <p class="text-sm font-black text-forest">42%</p>
            </div>
        </div>

        <div class="bg-emerald-50 p-4 rounded-2xl border flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-emerald-500">
                <span class="material-symbols-rounded">sprinkler</span>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-400">Watered</p>
                <p class="text-sm font-black text-forest">5 Times</p>
            </div>
        </div>

    </div>

    <!-- INSIGHT -->
    <div class="bg-gradient-to-br from-forest to-emerald-900 p-6 rounded-3xl text-white mb-6 animate-float">
        <p class="text-xs uppercase opacity-60">Smart System Insight</p>
        <h4 class="text-lg font-bold mt-1">Saran:</h4>
        <p class="text-sm opacity-80 mt-2 italic">
            "Tanamanmu terlihat sangat aktif berfotosintesis siang ini. Pastikan ventilasi terbuka untuk sirkulasi CO2 yang optimal!"
        </p>
    </div>

    <!-- CHART -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-xs font-bold uppercase text-slate-400 mb-6">
            Environmental Growth Trend
        </h4>

        <div class="h-[350px]">
            <canvas id="mainAnalysisChart"></canvas>
        </div>
    </section>

</main>

<!-- CHART JS (AMAN DI BAWAH) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('mainAnalysisChart');

if (ctx) {
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['08:00','10:00','12:00','14:00','16:00','18:00','20:00'],
            datasets: [
                { label: 'Suhu', data: [24,27,31,32,28,26,25], borderColor: '#f97316' },
                { label: 'Tanah', data: [70,65,60,58,85,80,75], borderColor: '#3b82f6' },
                { label: 'Udara', data: [55,52,48,45,55,54,53], borderColor: '#10b981' }
            ]
        }
    });
}
</script>

@endsection