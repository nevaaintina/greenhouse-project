@extends('layouts.app')

@section('title', 'Data Analytics')

@section('content')

<header class="flex justify-between items-center mb-10">
    <div class="flex items-center gap-3">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                Data Analytics
            </h2>
            <p class="text-xs text-gray-400 mt-1">
                Last Update: <span id="analyticsLastUpdate">{{ now()->format('d M Y H:i:s') }}</span>
            </p>
        </div>
    </div>

    <a href="{{ url('/profile') }}" class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">
            {{ auth()->user()->name }}
        </span>
    </a>
</header>

<form method="GET" action="{{ route('grafik.index') }}" id="filterForm">
    <div class="bg-white p-6 rounded-3xl shadow-sm border mb-6 flex flex-wrap items-end gap-4">
        <div class="flex items-center gap-2 mr-auto">
            <span class="material-symbols-rounded text-forest">
                calendar_month
            </span>
            <div>
                <h4 class="text-sm font-bold text-forest uppercase">
                    Filter Data Sensor
                </h4>
                <p class="text-[10px] text-gray-400">
                    Pilih rentang tanggal monitoring
                </p>
            </div>
        </div>

        <div class="flex flex-col">
            <label class="text-[10px] font-bold uppercase text-gray-400 mb-1">
                Dari Tanggal
            </label>
            <input type="date" id="startDateInput" name="start_date" value="{{ request('start_date') }}" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest">
        </div>

        <div class="flex flex-col">
            <label class="text-[10px] font-bold uppercase text-gray-400 mb-1">
                Sampai Tanggal
            </label>
            <input type="date" id="endDateInput" name="end_date" value="{{ request('end_date') }}" class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-xs font-bold text-forest">
        </div>

        <button type="submit" class="bg-forest text-white px-6 py-2 rounded-xl text-xs font-bold hover:scale-95 transition">
            Terapkan Filter
        </button>

        <a href="{{ route('grafik.index') }}" class="bg-gray-100 text-gray-500 px-6 py-2 rounded-xl text-xs font-bold">
            Reset
        </a>
    </div>
</form>

@if(request('start_date') && request('end_date'))
<div class="mb-6 text-xs text-gray-400 px-1">
    {{ $filterInfo }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-blue-500 text-xs">
                water_drop
            </span>
            Kelembapan Tanah (%)
        </h4>
        <div class="h-[250px]">
            <canvas id="soilChart"></canvas>
        </div>
    </section>

    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-orange-500 text-xs">
                device_thermostat
            </span>
            Suhu (°C)
        </h4>
        <div class="h-[250px]">
            <canvas id="tempChart"></canvas>
        </div>
    </section>

    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-emerald-500 text-xs">
                air
            </span>
            Kelembapan Udara (%)
        </h4>
        <div class="h-[250px]">
            <canvas id="humChart"></canvas>
        </div>
    </section>

    <section class="bg-white p-6 rounded-3xl shadow-sm border">
        <h4 class="text-[10px] font-bold uppercase text-slate-400 flex items-center gap-1 mb-4">
            <span class="material-symbols-rounded text-yellow-500 text-xs">
                wb_sunny
            </span>
            Intensitas Cahaya (Lux)
        </h4>
        <div class="h-[250px]">
            <canvas id="lightChart"></canvas>
        </div>
    </section>
</div>

<section class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h3 class="text-xl font-black text-forest uppercase tracking-tight">
                Riwayat Data Sensor
            </h3>
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">
                Detailed Logs & Export
            </p>
        </div>

        <div class="flex gap-2">
            <a href="{{ route('grafik.export', request()->all()) }}" class="flex items-center gap-2 bg-red-50 text-red-600 px-5 py-2.5 rounded-2xl text-xs font-black hover:bg-red-600 hover:text-white transition shadow-sm border border-red-100">
                <span class="material-symbols-rounded text-sm">
                    picture_as_pdf
                </span>
                EKSPOR PDF
            </a>
        </div>
    </div>

    <div class="table-container overflow-x-auto px-8 pb-4">
        <table class="w-full text-left border-separate border-spacing-y-2">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">
                    <th class="px-4 py-3">Waktu</th>
                    <th class="px-4 py-3">Soil (%)</th>
                    <th class="px-4 py-3">Temp (°C)</th>
                    <th class="px-4 py-3">Hum (%)</th>
                    <th class="px-4 py-3">Light (Lux)</th>
                </tr>
            </thead>
            <tbody id="sensorTableBody">
                @forelse($labels as $index => $label)
                <tr class="sensor-row bg-gray-50/50 hover:bg-forest/5 transition-colors group">
                    <td class="px-4 py-4 rounded-l-2xl border-y border-l border-gray-100/50">
                        <span class="text-xs font-bold text-gray-600 row-time-raw" data-timestamp="{{ $label }}">
                            {{ \Carbon\Carbon::parse($label)->translatedFormat('d M Y • H:i:s') }}
                        </span>
                    </td>
                    <td class="px-4 py-4 border-y border-gray-100/50">
                        <span class="text-xs font-black text-blue-600 row-soil-val">
                            {{ $soil[$index] ?? 0 }}%
                        </span>
                    </td>
                    <td class="px-4 py-4 border-y border-gray-100/50">
                        <span class="text-xs font-black text-orange-600 row-temp-val">
                            {{ $temp[$index] ?? 0 }}°C
                        </span>
                    </td>
                    <td class="px-4 py-4 border-y border-gray-100/50">
                        <span class="text-xs font-black text-emerald-600 row-hum-val">
                            {{ $hum[$index] ?? 0 }}%
                        </span>
                    </td>
                    <td class="px-4 py-4 rounded-r-2xl border-y border-r border-gray-100/50">
                        <span class="text-xs font-black text-yellow-600 row-light-val">
                            {{ $light[$index] ?? 0 }} Lux
                        </span>
                    </td>
                </tr>
                @empty
                <tr id="emptyRowPlaceholder">
                    <td colspan="5" class="text-center py-10 text-xs font-bold text-gray-400 uppercase">
                        Refilter Data Terlebih Dahulu / Tidak ada data untuk periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-8 pb-8 pt-4 flex items-center justify-between border-t border-gray-50 text-xs">
        <span class="text-gray-400 font-medium" id="paginationInfo">
            Showing 1 to 10 of 0 entries
        </span>
        <div class="flex gap-2">
            <button type="button" id="prevPageBtn" onclick="changeTablePage(-1)" class="px-4 py-2 bg-gray-100 hover:bg-forest hover:text-white rounded-xl font-bold text-gray-600 transition disabled:opacity-40 disabled:hover:bg-gray-100 disabled:hover:text-gray-600">
                Previous
            </button>
            <button type="button" id="nextPageBtn" onclick="changeTablePage(1)" class="px-4 py-2 bg-gray-100 hover:bg-forest hover:text-white rounded-xl font-bold text-gray-600 transition disabled:opacity-40 disabled:hover:bg-gray-100 disabled:hover:text-gray-600">
                Next
            </button>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Penampung Data Awal dari Backend Laravel
let rawServerLabels = @json($labels ?? []);
let globalSoilData  = @json($soil ?? []);
let globalTempData  = @json($temp ?? []);
let globalHumData   = @json($hum ?? []);
let globalLightData = @json($light ?? []);

// Fungsi Parsing Tanggal ISO Lokal ke Format Visual Grafis ("03 Jun • 20:25:38")
function formatLabel(isoString) {
    const d = new Date(isoString);
    if(isNaN(d.getTime())) return '-';
    const dateStr = d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
    const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    return `${dateStr} • ${timeStr}`;
}

function formatTableTime(isoString) {
    const d = new Date(isoString);
    if(isNaN(d.getTime())) return '-';
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const dateStr = `${String(d.getDate()).padStart(2, '0')} ${months[d.getMonth()]} ${d.getFullYear()}`;
    const timeStr = d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    return `${dateStr} • ${timeStr}`;
}

function safeData(d) { return d.length ? d : [0]; }
function safeLabels(l) { return l.length ? l.map(formatLabel) : ['-']; }

// COLOR DYNAMIC ENGINE
function getColor(type, value) {
    if(type === 'soil') {
        if(value < 45) return '#ef4444';
        if(value <= 70) return '#3b82f6';
        return '#9ca3af';
    }
    if(type === 'temp') {
        if(value > 28) return '#ef4444';
        if(value >= 20) return '#22c55e';
        return '#3b82f6';
    }
    if(type === 'light') {
        if(value < 300) return '#facc15';
        if(value <= 800) return '#22c55e';
        return '#f97316';
    }
    return '#10b981';
}

// CHART CONSTRUCTORS
const charts = {};
function createLineChart(id, dataList, color, fill = false) {
    const ctx = document.getElementById(id);
    if(!ctx) return;

    charts[id] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: safeLabels(rawServerLabels),
            datasets: [{
                data: safeData(dataList),
                borderColor: color,
                backgroundColor: fill ? color + '15' : 'transparent',
                tension: 0.4,
                fill: fill,
                pointRadius: 3,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                borderWidth: 2.5,
                spanGaps: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#1f2937', padding: 10, cornerRadius: 10 }
            },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: {
                    grid: { display: false },
                    ticks: { maxTicksLimit: 6, maxRotation: 0, autoSkip: true, font: { size: 9 } }
                }
            }
        }
    });
}

// INITIALIZATION RENDERING
createLineChart('soilChart', globalSoilData, getColor('soil', globalSoilData[globalSoilData.length - 1] || 0), true);
createLineChart('tempChart', globalTempData, getColor('temp', globalTempData[globalTempData.length - 1] || 0), true);
createLineChart('humChart', globalHumData, '#10b981', true);
createLineChart('lightChart', globalLightData, getColor('light', globalLightData[globalLightData.length - 1] || 0), true);

// =======================================================
// CORE LIVE RE-RENDER ENGINE: AJAX POLLING RE-FETCH
// =======================================================
async function fetchAnalyticsRealtime() {
    // PROTEKSI FILTER: Jika user sedang memilih filter tanggal tertentu, nonaktifkan auto-polling agar pencarian tidak kacau
    const startVal = document.getElementById('startDateInput').value;
    const endVal = document.getElementById('endDateInput').value;
    if (startVal || endVal) return;

    try {
        const response = await fetch('{{ route("grafik.realtime") }}');
        if (!response.ok) return;
        const freshData = await response.json();

        if (!freshData.labels || freshData.labels.length === 0) return;

        // 1. Update Memory State dengan data terbaru dari server
        rawServerLabels = freshData.labels;
        globalSoilData  = freshData.soil;
        globalTempData  = freshData.temp;
        globalHumData   = freshData.hum;
        globalLightData = freshData.light;

        // 2. Dorong Pembaruan Koordinat Titik Grafik Baru
        if (charts['soilChart']) {
            charts['soilChart'].data.labels = safeLabels(rawServerLabels);
            charts['soilChart'].data.datasets[0].data = safeData(globalSoilData);
            charts['soilChart'].data.datasets[0].borderColor = getColor('soil', globalSoilData[globalSoilData.length - 1]);
            charts['soilChart'].update('none'); // Update secara 'none' agar grafik bergeser halus tanpa flickering
        }
        if (charts['tempChart']) {
            charts['tempChart'].data.labels = safeLabels(rawServerLabels);
            charts['tempChart'].data.datasets[0].data = safeData(globalTempData);
            charts['tempChart'].data.datasets[0].borderColor = getColor('temp', globalTempData[globalTempData.length - 1]);
            charts['tempChart'].update('none');
        }
        if (charts['humChart']) {
            charts['humChart'].data.labels = safeLabels(rawServerLabels);
            charts['humChart'].data.datasets[0].data = safeData(globalHumData);
            charts['humChart'].update('none');
        }
        if (charts['lightChart']) {
            charts['lightChart'].data.labels = safeLabels(rawServerLabels);
            charts['lightChart'].data.datasets[0].data = safeData(globalLightData);
            charts['lightChart'].data.datasets[0].borderColor = getColor('light', globalLightData[globalLightData.length - 1]);
            charts['lightChart'].update('none');
        }

        // 3. Bangun Ulang Baris Tabel Riwayat secara Real-time
        const tableBody = document.getElementById('sensorTableBody');
        const placeholder = document.getElementById('emptyRowPlaceholder');
        if (placeholder) placeholder.remove();

        let newTableHtml = '';
        
        // Kita balikkan urutan array (reverse) khusus tabel agar data paling baru masuk berada di paling atas
        const reversedLabels = [...rawServerLabels].reverse();
        const totalItems = reversedLabels.length;

        reversedLabels.forEach((label, revIdx) => {
            const originalIdx = totalItems - 1 - revIdx; // Mencari index asli pasangannya
            
            const sVal = globalSoilData[originalIdx] !== null ? globalSoilData[originalIdx] : 0;
            const tVal = globalTempData[originalIdx] !== null ? globalTempData[originalIdx] : 0;
            const hVal = globalHumData[originalIdx] !== null ? globalHumData[originalIdx] : 0;
            const lVal = globalLightData[originalIdx] !== null ? globalLightData[originalIdx] : 0;

            newTableHtml += `
            <tr class="sensor-row bg-gray-50/50 hover:bg-forest/5 transition-colors group">
                <td class="px-4 py-4 rounded-l-2xl border-y border-l border-gray-100/50">
                    <span class="text-xs font-bold text-gray-600">
                        ${formatTableTime(label)}
                    </span>
                </td>
                <td class="px-4 py-4 border-y border-gray-100/50">
                    <span class="text-xs font-black text-blue-600">${sVal}%</span>
                </td>
                <td class="px-4 py-4 border-y border-gray-100/50">
                    <span class="text-xs font-black text-orange-600">${tVal}°C</span>
                </td>
                <td class="px-4 py-4 border-y border-gray-100/50">
                    <span class="text-xs font-black text-emerald-600">${hVal}%</span>
                </td>
                <td class="px-4 py-4 rounded-r-2xl border-y border-r border-gray-100/50">
                    <span class="text-xs font-black text-yellow-600">${lVal} Lux</span>
                </td>
            </tr>`;
        });

        tableBody.innerHTML = newTableHtml;
        
        // Panggil kembali fungsi pembagi halaman agar tabel tetap terpotong rapi per 10 baris
        renderTablePagination();

        // Update waktu pencatatan jam di header halaman utama analitik
        document.getElementById('analyticsLastUpdate').innerText = new Date().toLocaleTimeString('id-ID');

    } catch (err) {
        console.error("Gagal sinkronisasi data analitik secara otomatis:", err);
    }
}

// JAVASCRIPT SLICING TABLE 10 ROWS CLIENT-SIDE
let currentTablePage = 1;
const rowsPerPage = 10;

function renderTablePagination() {
    const tableRows = document.getElementsByClassName('sensor-row');
    const totalRows = tableRows.length;

    if (totalRows === 0) {
        document.getElementById('paginationInfo').innerText = "Showing 0 to 0 of 0 entries";
        return;
    }

    const start = (currentTablePage - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    for (let i = 0; i < totalRows; i++) {
        if (i >= start && i < end) {
            tableRows[i].style.display = '';
        } else {
            tableRows[i].style.display = 'none';
        }
    }

    const actualEnd = Math.min(end, totalRows);
    document.getElementById('paginationInfo').innerText = `Showing ${start + 1} to ${actualEnd} of ${totalRows} entries`;

    document.getElementById('prevPageBtn').disabled = (currentTablePage === 1);
    document.getElementById('nextPageBtn').disabled = (end >= totalRows);
}

function changeTablePage(direction) {
    currentTablePage += direction;
    renderTablePagination();
}

// TRIGGER RUNNING POLLING: Melakukan refresh data di background setiap 5 detik sekali
setInterval(fetchAnalyticsRealtime, 5000);

document.addEventListener("DOMContentLoaded", function() {
    renderTablePagination();
});
</script>

<style>
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
    100% { transform: translateY(0px); }
}
.animate-float { animation: float 3s ease-in-out infinite; }

canvas { transition: all 0.3s ease; }
canvas:hover { transform: scale(1.01); }

.table-container::-webkit-scrollbar { height: 6px; }
.table-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

@endsection