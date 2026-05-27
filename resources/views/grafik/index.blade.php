@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto p-5 md:p-8 text-slate-700">

<!-- =======================================================
HEADER
======================================================= -->

<header class="flex justify-between items-center mb-10">

    <!-- LEFT -->
    <div class="flex items-center gap-3">

        <!-- MOBILE MENU -->
        <button
            class="block md:hidden text-forest p-1 focus:outline-none"
            onclick="toggleSidebar()">

            <span class="material-symbols-rounded text-3xl">
                menu
            </span>

        </button>

        <!-- TITLE -->
        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Data Analytics

            </h2>

            <p class="text-xs text-gray-400 mt-1">

                Last Update:
                {{ now()->format('d M Y H:i') }}

            </p>

        </div>

    </div>

    <!-- PROFILE -->
    <a
        href="/profile"

        class="flex items-center gap-3 bg-white
        p-2 px-4 rounded-full shadow border">

        <div
            class="w-8 h-8 bg-forest text-white
            flex items-center justify-center
            rounded-full font-bold">

            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

        </div>

        <span
            class="text-sm font-semibold text-forest hidden sm:block">

            {{ auth()->user()->name }}

        </span>

    </a>

</header>



<!-- =======================================================
FILTER
======================================================= -->

<form method="GET" action="{{ route('grafik') }}">

    <div
        class="bg-white p-6 rounded-3xl shadow-sm border
        mb-6 flex flex-wrap items-end gap-4">

        <!-- TITLE -->
        <div class="flex items-center gap-2 mr-auto">

            <span class="material-symbols-rounded text-forest">
                calendar_month
            </span>

            <div>

                <h4
                    class="text-sm font-bold text-forest uppercase">

                    Filter Data Sensor

                </h4>

                <p class="text-[10px] text-gray-400">

                    Pilih rentang tanggal monitoring

                </p>

            </div>

        </div>


        <!-- START -->
        <div class="flex flex-col">

            <label
                class="text-[10px] font-bold uppercase
                text-gray-400 mb-1">

                Dari Tanggal

            </label>

            <input
                type="date"

                name="start_date"

                value="{{ request('start_date') }}"

                class="bg-gray-50 border border-gray-100
                rounded-xl px-4 py-2 text-xs
                font-bold text-forest">
        </div>


        <!-- END -->
        <div class="flex flex-col">

            <label
                class="text-[10px] font-bold uppercase
                text-gray-400 mb-1">

                Sampai Tanggal

            </label>

            <input
                type="date"

                name="end_date"

                value="{{ request('end_date') }}"

                class="bg-gray-50 border border-gray-100
                rounded-xl px-4 py-2 text-xs
                font-bold text-forest">
        </div>


        <!-- BUTTON -->
        <button
            type="submit"

            class="bg-forest text-white
            px-6 py-2 rounded-xl
            text-xs font-bold
            hover:scale-95 transition">

            Terapkan Filter

        </button>


        <!-- RESET -->
        <a
            href="{{ route('grafik') }}"

            class="bg-gray-100 text-gray-500
            px-6 py-2 rounded-xl
            text-xs font-bold">

            Reset

        </a>

    </div>

</form>



<!-- =======================================================
FILTER INFO
======================================================= -->

@if(request('date'))

<div class="mb-6 text-xs text-gray-400 px-1">

    {{ $filterInfo }}

</div>

@endif



<!-- =======================================================
CHART SECTION
======================================================= -->

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

    <!-- SOIL -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">

        <h4
            class="text-[10px] font-bold uppercase text-slate-400
            flex items-center gap-1 mb-4">

            <span
                class="material-symbols-rounded text-blue-500 text-xs">

                water_drop

            </span>

            Soil Moisture (%)

        </h4>

        <div class="h-[250px]">

            <canvas id="soilChart"></canvas>

        </div>

    </section>


    <!-- TEMP -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">

        <h4
            class="text-[10px] font-bold uppercase text-slate-400
            flex items-center gap-1 mb-4">

            <span
                class="material-symbols-rounded text-orange-500 text-xs">

                device_thermostat

            </span>

            Temperature (°C)

        </h4>

        <div class="h-[250px]">

            <canvas id="tempChart"></canvas>

        </div>

    </section>


    <!-- HUM -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">

        <h4
            class="text-[10px] font-bold uppercase text-slate-400
            flex items-center gap-1 mb-4">

            <span
                class="material-symbols-rounded text-emerald-500 text-xs">

                air

            </span>

            Humidity (%)

        </h4>

        <div class="h-[250px]">

            <canvas id="humChart"></canvas>

        </div>

    </section>


    <!-- LIGHT -->
    <section class="bg-white p-6 rounded-3xl shadow-sm border">

        <h4
            class="text-[10px] font-bold uppercase text-slate-400
            flex items-center gap-1 mb-4">

            <span
                class="material-symbols-rounded text-yellow-500 text-xs">

                wb_sunny

            </span>

            Light Intensity (Lux)

        </h4>

        <div class="h-[250px]">

            <canvas id="lightChart"></canvas>

        </div>

    </section>

</div>



<!-- =======================================================
TABLE SECTION
======================================================= -->

<section
    class="bg-white rounded-[2.5rem]
    shadow-sm border border-gray-100 overflow-hidden">

    <!-- HEADER -->
    <div
        class="p-8 flex flex-col md:flex-row
        justify-between items-center gap-4">

        <!-- TITLE -->
        <div>

            <h3
                class="text-xl font-black text-forest
                uppercase tracking-tight">

                Riwayat Data Sensor

            </h3>

            <p
                class="text-[10px] text-gray-400
                font-bold uppercase tracking-widest mt-1">

                Detailed Logs & Export

            </p>

        </div>

        <!-- EXPORT -->
        <div class="flex gap-2">

            <a
                href="{{ route('grafik.export', request()->all()) }}"

                class="flex items-center gap-2
                bg-red-50 text-red-600
                px-5 py-2.5 rounded-2xl
                text-xs font-black
                hover:bg-red-600 hover:text-white
                transition shadow-sm border border-red-100">

                <span
                    class="material-symbols-rounded text-sm">

                    picture_as_pdf

                </span>

                EKSPOR PDF

            </a>

        </div>

    </div>

    <!-- TABLE -->
    <div class="table-container overflow-x-auto px-8 pb-8">

        <table
            class="w-full text-left
            border-separate border-spacing-y-2">

            <thead>

                <tr
                    class="text-[10px] font-black text-gray-400
                    uppercase tracking-[0.2em]">

                    <th class="px-4 py-3">
                        Waktu
                    </th>

                    <th class="px-4 py-3">
                        Soil (%)
                    </th>

                    <th class="px-4 py-3">
                        Temp (°C)
                    </th>

                    <th class="px-4 py-3">
                        Hum (%)
                    </th>

                    <th class="px-4 py-3">
                        Light (Lux)
                    </th>

                </tr>

            </thead>

            <tbody>

                @forelse($labels as $index => $label)

                <tr
                    class="bg-gray-50/50 hover:bg-forest/5
                    transition-colors group">

                    <td
                        class="px-4 py-4 rounded-l-2xl
                        border-y border-l border-gray-100/50">

                        <span
                            class="text-xs font-bold text-gray-600">
                            {{ \Carbon\Carbon::parse($label)->translatedFormat('d M Y • H:i') }}

                        </span>

                    </td>

                    <td class="px-4 py-4 border-y border-gray-100/50">

                        <span
                            class="text-xs font-black text-blue-600">

                            {{ $soil[$index] ?? 0 }}%

                        </span>

                    </td>

                    <td class="px-4 py-4 border-y border-gray-100/50">

                        <span
                            class="text-xs font-black text-orange-600">

                            {{ $temp[$index] ?? 0 }}°C

                        </span>

                    </td>

                    <td class="px-4 py-4 border-y border-gray-100/50">

                        <span
                            class="text-xs font-black text-emerald-600">

                            {{ $hum[$index] ?? 0 }}%

                        </span>

                    </td>

                    <td
                        class="px-4 py-4 rounded-r-2xl
                        border-y border-r border-gray-100/50">

                        <span
                            class="text-xs font-black text-yellow-600">

                            {{ $light[$index] ?? 0 }} Lux

                        </span>

                    </td>

                </tr>

                @empty

                <tr>

                    <td
                        colspan="5"

                        class="text-center py-10
                        text-xs font-bold text-gray-400 uppercase">

                        Tidak ada data untuk periode ini

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</section>



<!-- =======================================================
CHART JS
======================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const labels =
    @json($labels ?? []);

const tempData =
    @json($temp ?? []);

const soilData =
    @json($soil ?? []);

const humData =
    @json($hum ?? []);

const lightData =
    @json($light ?? []);


// =======================================================
// SAFE DATA
// =======================================================

function safeData(d)
{
    return d?.length ? d : [0];
}

function safeLabels(l)
{
    return l?.length ? l : ['-'];
}


// =======================================================
// DYNAMIC COLOR
// =======================================================

function getColor(type, value)
{
    if(type === 'soil')
    {
        if(value < 45) return '#ef4444';

        if(value <= 70) return '#3b82f6';

        return '#9ca3af';
    }

    if(type === 'temp')
    {
        if(value > 28) return '#ef4444';

        if(value >= 20) return '#22c55e';

        return '#3b82f6';
    }

    if(type === 'light')
    {
        if(value < 300) return '#facc15';

        if(value <= 800) return '#22c55e';

        return '#f97316';
    }

    return '#10b981';
}


// =======================================================
// CHART
// =======================================================

const charts = {};

function chart(id, data, color, fill = false)
{
    const ctx =
        document.getElementById(id);

    if(!ctx) return;

    // destroy old
    if(charts[id])
    {
        charts[id].destroy();
    }

    charts[id] = new Chart(ctx,
    {
        type: 'line',

        data:
        {
            labels: safeLabels(labels),

            datasets:
            [{
                data: safeData(data),

                borderColor: color,

                backgroundColor:
                    fill
                    ? color + '15'
                    : 'transparent',

                tension: 0.5,

                fill: fill,

                pointRadius: 5,

                pointBackgroundColor: color,

                pointBorderColor: '#fff',

                borderWidth: 3,

                spanGaps: true
            }]
        },

        options:
        {
            responsive: true,

            maintainAspectRatio: false,

            plugins:
            {
                legend:
                {
                    display: false
                },

                tooltip:
                {
                    backgroundColor: '#1f2937',

                    titleColor: '#fff',

                    bodyColor: '#fff',

                    padding: 10,

                    cornerRadius: 10
                }
            },

            scales:
            {
                y:
                {
                    beginAtZero: true,

                    grid:
                    {
                        color: '#f1f5f9'
                    }
                },

                x:
                {
                    grid:
                    {
                        display: false
                    }
                }
            }
        }
    });
}


// =======================================================
// INIT CHART
// =======================================================

chart(
    'soilChart',
    soilData,
    getColor('soil', soilData.slice(-1)[0]),
    true
);

chart(
    'tempChart',
    tempData,
    getColor('temp', tempData.slice(-1)[0]),
    true
);

chart(
    'humChart',
    humData,
    '#10b981',
    true
);

chart(
    'lightChart',
    lightData,
    getColor('light', lightData.slice(-1)[0]),
    true
);


// =======================================================
// MOBILE SIDEBAR
// =======================================================

function toggleSidebar()
{
    const sidebar =
        document.getElementById('mobile-sidebar');

    if (sidebar)
    {
        sidebar.classList.toggle('hidden');
    }
}

</script>



<!-- =======================================================
STYLE
======================================================= -->

<style>

@keyframes float
{
    0%
    {
        transform: translateY(0px);
    }

    50%
    {
        transform: translateY(-10px);
    }

    100%
    {
        transform: translateY(0px);
    }
}

.animate-float
{
    animation:
        float 3s ease-in-out infinite;
}


/* chart hover */

canvas
{
    transition: all 0.3s ease;
}

canvas:hover
{
    transform: scale(1.02);
}


/* table scrollbar */

.table-container::-webkit-scrollbar
{
    height: 6px;
}

.table-container::-webkit-scrollbar-thumb
{
    background: #e2e8f0;

    border-radius: 10px;
}

</style>

@endsection