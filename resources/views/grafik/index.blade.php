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

/* 🔥 efek halus grafik */
canvas {
    transition: all 0.3s ease;
}
canvas:hover {
    transform: scale(1.02);
}
</style>

<main class="max-w-7xl mx-auto p-5 md:p-8 text-slate-700">

<header class="flex justify-between items-center mb-10">
    <div class="flex items-center gap-3">
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                {{ Request::is('grafik*') ? 'Data Analytics' : 'Greenhouse Overview' }}
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

<!-- FILTER -->
<form method="GET" action="/grafik">
<div class="bg-white p-6 rounded-3xl shadow-sm border mb-2 flex flex-wrap items-center gap-4">
    <div class="flex items-center gap-2">
        <span class="material-symbols-rounded text-forest">calendar_month</span>
        <h4 class="text-sm font-bold text-forest uppercase">Filter Data</h4>
    </div>

    <div class="flex flex-wrap gap-3 flex-1 justify-end">
        <input type="date" name="date" value="{{ request('date') }}"
            class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest">

        <select name="range"
            class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest">
            <option value="daily" {{ request('range')=='daily' ? 'selected' : '' }}>Harian</option>
            <option value="weekly" {{ request('range')=='weekly' ? 'selected' : '' }}>Mingguan</option>
            <option value="monthly" {{ request('range')=='monthly' ? 'selected' : '' }}>Bulanan</option>
        </select>

        <button type="submit" class="bg-forest text-white px-6 py-2 rounded-xl text-xs font-bold">
            Terapkan Filter
        </button>
    </div>
</div>
</form>

@if(request('date'))
<div class="mb-6 text-xs text-gray-400 px-1">
    {{ $filterInfo }}
</div>
@endif

<!-- CHART -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- SOIL -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-blue-500 text-xs">water_drop</span>
            Soil Moisture (%)
        </h4>
        <div class="h-[250px]"><canvas id="soilChart"></canvas></div>
    </section>

    <!-- TEMP -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-orange-500 text-xs">device_thermostat</span>
            Temperature (°C)
        </h4>
        <div class="h-[250px]"><canvas id="tempChart"></canvas></div>
    </section>

    <!-- HUM -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-emerald-500 text-xs">air</span>
            Humidity (%)
        </h4>
        <div class="h-[250px]"><canvas id="humChart"></canvas></div>
    </section>

    <!-- LIGHT -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-yellow-500 text-xs">wb_sunny</span>
            Light Intensity (Lux)
        </h4>
        <div class="h-[250px]"><canvas id="lightChart"></canvas></div>
    </section>

</div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

const labels = @json($labels ?? []);
const tempData = @json($temp ?? []);
const soilData = @json($soil ?? []);
const humData = @json($hum ?? []);
const lightData = @json($light ?? []);

function safeData(d){ return d?.length ? d : [0]; }
function safeLabels(l){ return l?.length ? l : ['-']; }

// 🔥 WARNA DINAMIS
function getColor(type, value){
    if(type==='soil'){
        if(value<45) return '#ef4444';
        if(value<=70) return '#3b82f6';
        return '#9ca3af';
    }
    if(type==='temp'){
        if(value>28) return '#ef4444';
        if(value>=20) return '#22c55e';
        return '#3b82f6';
    }
    if(type==='light'){
        if(value<300) return '#facc15';
        if(value<=800) return '#22c55e';
        return '#f97316';
    }
    return '#10b981';
}

const charts = {};

function chart(id, data, color, fill=false){
    const ctx = document.getElementById(id);
    if(!ctx) return;

    if(charts[id]) charts[id].destroy();

    charts[id] = new Chart(ctx,{
        type:'line',
        data:{
            labels: safeLabels(labels),
            datasets:[{
                data: safeData(data),
                borderColor: color,
                backgroundColor: fill ? color+'15' : 'transparent',
                tension:0.5,
                fill:fill,
                pointRadius:5,
                pointBackgroundColor:color,
                pointBorderColor:'#fff',
                borderWidth:3,
                spanGaps:true
            }]
        },
        options:{
            responsive:true,
            maintainAspectRatio:false,
            plugins:{
                legend:{display:false},
                tooltip:{
                    backgroundColor:'#1f2937',
                    titleColor:'#fff',
                    bodyColor:'#fff',
                    padding:10,
                    cornerRadius:10
                }
            },
            scales:{
                y:{beginAtZero:true,grid:{color:'#f1f5f9'}},
                x:{grid:{display:false}}
            }
        }
    });
}

// INIT
chart('soilChart', soilData, getColor('soil', soilData.slice(-1)[0]), true);
chart('tempChart', tempData, getColor('temp', tempData.slice(-1)[0]), true);
chart('humChart', humData, '#10b981', true);
chart('lightChart', lightData, getColor('light', lightData.slice(-1)[0]), true);
</script>

@endsection