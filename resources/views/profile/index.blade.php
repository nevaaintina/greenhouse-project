@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<style>

    @keyframes sway
    {
        0% {
            transform: rotate(0deg);
        }

        50% {
            transform: rotate(6deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    .plant-sway
    {
        animation: sway 3.5s ease-in-out infinite;
        transform-origin: bottom center;
    }

</style>

<!-- =======================================================
HEADER
======================================================= -->

<header class="flex justify-between items-center mb-10 px-2">

    <div class="flex items-center gap-3">

        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Administrator Profile

            </h2>

            <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase">

                Identity & Greenhouse Management

            </p>

        </div>

    </div>

    <div class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow-sm border border-gray-100">

        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold text-xs">

            {{ strtoupper(substr($user->name,0,1)) }}

        </div>

        <span class="text-sm font-semibold text-forest hidden sm:block">

            {{ $user->name }}

        </span>

    </div>

</header>

<!-- =======================================================
SUCCESS / ERROR
======================================================= -->

@if(session('success'))

<div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-5 py-4 rounded-3xl text-sm font-semibold">

    {{ session('success') }}

</div>

@endif

@if(session('error'))

<div class="mb-6 bg-red-50 border border-red-100 text-red-500 px-5 py-4 rounded-3xl text-sm font-semibold">

    {{ session('error') }}

</div>

@endif

<!-- =======================================================
PROFILE CONTENT
======================================================= -->

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">

    <!-- =======================================================
    LEFT
    ======================================================= -->

    <div class="lg:col-span-4 space-y-6">

        <!-- PROFILE CARD -->
        <div class="bg-white rounded-[2rem] p-8 text-center shadow-sm border border-gray-50 relative overflow-hidden">

            <div class="absolute -top-10 -right-10 w-32 h-32 bg-forest/5 rounded-full"></div>

            <!-- AVATAR -->
            <div class="relative inline-block mb-4">

                <div class="w-32 h-32 bg-forest text-white flex items-center justify-center rounded-[2.5rem] text-5xl font-black shadow-lg shadow-forest/20 rotate-3 transition-transform">

                    {{ strtoupper(substr($user->name,0,1)) }}

                </div>

                <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>

            </div>

            <!-- USER -->
            <h3 class="text-2xl font-black text-forest">

                {{ $user->name }}

            </h3>

            <!-- ROLE -->
            <div class="inline-block px-4 py-1 bg-gray-100 rounded-full mt-2">

                <p class="text-[10px] text-gray-500 font-bold tracking-tighter uppercase">

                    {{ $user->role ?? 'Petani' }}

                </p>

            </div>

            <!-- ACTION -->
            <div class="mt-8 space-y-3">

                <!-- EDIT -->
                <a href="{{ route('profile.edit') }}"
                class="flex items-center justify-center gap-2 w-full bg-forest text-white py-3 rounded-2xl font-bold text-sm hover:scale-[0.98] transition shadow-md">

                    <span class="material-symbols-rounded text-sm">

                        edit

                    </span>

                    Edit Profil

                </a>

                <!-- LOGOUT -->
                <form method="POST"
                action="{{ route('logout') }}">

                    @csrf

                    <button type="submit"
                    class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-500 py-3 rounded-2xl font-bold text-sm hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition">

                        <span class="material-symbols-rounded text-sm">

                            logout

                        </span>

                        Keluar

                    </button>

                </form>

            </div>

        </div>

        <!-- =======================================================
        GREENHOUSE SWITCHER
        ======================================================= -->

        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-gray-100">

            <!-- HEADER -->
            <div class="flex items-center justify-between mb-5">

                <div>

                    <p class="text-[10px] uppercase tracking-[0.3em] text-gray-400 font-black">

                        Greenhouse Control

                    </p>

                    <h4 class="text-lg font-black text-forest mt-1">

                        Pilih Greenhouse

                    </h4>

                </div>

                <div class="w-10 h-10 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">

                    <span class="material-symbols-rounded">

                        warehouse

                    </span>

                </div>

            </div>

            <!-- EMPTY -->
            @if($greenhouses->isEmpty())

            <div class="bg-gray-50 border border-dashed border-gray-200 rounded-2xl p-6 text-center">

                <span class="material-symbols-rounded text-4xl text-gray-300">

                    sensors_off

                </span>

                <p class="text-sm font-bold text-gray-400 mt-3">

                    Belum ada greenhouse

                </p>

            </div>

            @else
            
            
            <!-- LIST -->
             <div class="space-y-3">

    @foreach($greenhouses as $item)

    <form method="POST"
    action="{{ route('greenhouse.switch', $item->id) }}">

        @csrf

        <button type="submit"
        class="w-full flex items-center justify-between rounded-2xl border px-4 py-4 transition-all duration-300

        {{ optional(auth()->user()->activeGreenhouse)->id == $item->id
            ? 'border-green-500 bg-green-50 shadow-sm'
            : 'border-gray-100 bg-white hover:border-green-200 hover:bg-green-50/40'
        }}">

            <!-- LEFT -->
            <div class="flex items-center gap-3">

                <!-- ICON -->
                <div class="w-11 h-11 rounded-xl flex items-center justify-center

                {{ optional(auth()->user()->activeGreenhouse)->id == $item->id
                    ? 'bg-green-600 text-white'
                    : 'bg-gray-100 text-gray-500'
                }}">

                    <span class="material-symbols-rounded text-[20px]">

                        home_work

                    </span>

                </div>

                <!-- INFO -->
                <div class="text-left">

                    <p class="text-sm font-black text-forest leading-none">

                        {{ $item->name }}

                    </p>

                    <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mt-1">

                        {{ $item->plant_type ?? 'Smart Farming' }}

                    </p>

                </div>

            </div>

            <!-- ACTIVE -->
            @if(optional(auth()->user()->activeGreenhouse)->id == $item->id)

            <div class="flex items-center gap-1 text-green-600">

                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>

                <span class="text-[9px] font-black uppercase">

                    Active

                </span>

            </div>

            @else

            <span class="material-symbols-rounded text-gray-300 text-[20px]">

                chevron_right

            </span>

            @endif

        </button>

    </form>

    @endforeach

</div>
@endif

</div>
</div>

    <!-- =======================================================
    RIGHT
    ======================================================= -->

    <div class="lg:col-span-8 space-y-6">

        <!-- USER INFO -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">

            <h4 class="font-black text-forest uppercase tracking-widest text-xs mb-8 flex items-center gap-2">

                <span class="w-8 h-[2px] bg-forest"></span>

                Detail Informasi

            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">

                <!-- NAME -->
                <div class="flex items-start gap-4">

                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400">

                        <span class="material-symbols-rounded">

                            person

                        </span>

                    </div>

                    <div>

                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">

                            Nama Lengkap

                        </p>

                        <p class="font-bold text-forest text-base leading-tight">

                            {{ $user->name }}

                        </p>

                    </div>

                </div>

                <!-- EMAIL -->
                <div class="flex items-start gap-4">

                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400">

                        <span class="material-symbols-rounded">

                            mail

                        </span>

                    </div>

                    <div>

                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">

                            Alamat Email

                        </p>

                        <p class="font-bold text-forest text-base leading-tight">

                            {{ $user->email }}

                        </p>

                    </div>

                </div>

                <!-- PHONE -->
                <div class="flex items-start gap-4">

                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400">

                        <span class="material-symbols-rounded">

                            call

                        </span>

                    </div>

                    <div>

                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">

                            Nomor Telepon

                        </p>

                        <p class="font-bold text-forest text-base leading-tight">

                            {{ $user->phone ?? '-' }}

                        </p>

                    </div>

                </div>
                <div class="flex items-start gap-4">
                </div>

            </div>

        </div>

        <!-- =======================================================
        GREENHOUSE INFO
        ======================================================= -->

        <div
        class="bg-gradient-to-br from-white to-gray-50 rounded-[2rem] p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">

            <!-- LEFT -->
            <div class="relative z-10 text-center md:text-left">

                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-black mb-2">

                    Operational Node

                </p>

                <h3 class="text-3xl font-black text-forest leading-none">

                    {{ $greenhouse->name ?? 'Smart Greenhouse' }}

                </h3>

                <div class="flex items-center gap-2 mt-4 text-green-600">

                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>

                    <span class="text-[10px] font-bold uppercase tracking-widest">

                        System Synchronized

                    </span>

                </div>

                <!-- DETAIL -->
                <div class="mt-5 grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <!-- LOCATION -->
                    <div class="bg-white/70 px-4 py-3 rounded-2xl border border-gray-100">

                        <p class="text-[9px] uppercase font-black text-gray-400">

                            Lokasi

                        </p>

                        <p class="font-bold text-forest text-sm">

                            {{ $greenhouse->location ?? '-' }}

                        </p>

                    </div>

                    <!-- SIZE -->
                    <div class="bg-white/70 px-4 py-3 rounded-2xl border border-gray-100">

                        <p class="text-[9px] uppercase font-black text-gray-400">

                            Ukuran

                        </p>

                        <p class="font-bold text-forest text-sm">

                            {{ $greenhouse->size ?? '-' }}

                        </p>

                    </div>

                    <!-- PLANT -->
                    <div class="bg-white/70 px-4 py-3 rounded-2xl border border-gray-100">

                        <p class="text-[9px] uppercase font-black text-gray-400">

                            Jenis Komoditas

                        </p>

                        <p class="font-bold text-forest text-sm">

                            {{ $greenhouse->plant_type ?? '-' }}

                        </p>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="plant-sway relative">

                <div class="absolute inset-0 bg-green-400/20 blur-3xl rounded-full scale-150"></div>

                <svg viewBox="0 0 200 200"
                class="w-32 h-32 md:w-40 md:h-40 relative z-10 drop-shadow-2xl">

                    <path d="M100 170 C100 140, 100 120, 100 100"
                    stroke="#166534"
                    stroke-width="6"
                    stroke-linecap="round" />

                    <path d="M100 110 C60 100, 60 60, 100 70 C120 80, 120 100, 100 110Z"
                    fill="#22c55e" />

                    <path d="M100 110 C140 100, 140 60, 100 70 C80 80, 80 100, 100 110Z"
                    fill="#16a34a" />

                </svg>

            </div>

        </div>

    </div>

</div>

<!-- =======================================================
STATS
======================================================= -->

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-2">

    <!-- STATUS -->
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">

        <span class="material-symbols-rounded text-forest opacity-50">

            verified_user

        </span>

        <div>

            <p class="text-[9px] font-black text-gray-400 uppercase">

                System Status

            </p>

            <p id="systemStatus"
            class="text-xs font-bold text-forest">

                {{ ($systemStatus ?? 'Idle') == 'Active'
                    ? 'Secure & Active'
                    : 'Idle'
                }}

            </p>

        </div>

    </div>

    <!-- LOG -->
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">

        <span class="material-symbols-rounded text-forest opacity-50">

            database

        </span>

        <div>

            <p class="text-[9px] font-black text-gray-400 uppercase">

                Total Logs

            </p>

            <p id="totalLogs"
            class="text-xs font-bold text-forest">

                {{ number_format($totalLogs ?? 0) }} Entry

            </p>

        </div>

    </div>

    <!-- NODE -->
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">

        <span class="material-symbols-rounded text-forest opacity-50">

            sensors

        </span>

        <div>

            <p class="text-[9px] font-black text-gray-400 uppercase">

                Nodes

            </p>

            <p id="totalNodes"
            class="text-xs font-bold text-forest">

                {{ $totalNodes ?? 0 }} Active

            </p>

        </div>

    </div>

    <!-- UPTIME -->
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">

        <span class="material-symbols-rounded text-forest opacity-50">

            update

        </span>

        <div>

            <p class="text-[9px] font-black text-gray-400 uppercase">

                Uptime

            </p>

            <p id="uptime"
            class="text-xs font-bold text-forest">

                {{ $uptime ?? 'Offline' }}

            </p>

        </div>

    </div>

</div>

<!-- =======================================================
SCRIPT
======================================================= -->

<script>

    // ======================================================
    // REALTIME STATS
    // ======================================================

    function updateStats()
    {
        fetch('/stats/realtime')

        .then(response => response.json())

        .then(data =>
        {
            // STATUS
            const statusEl =
                document.getElementById(
                    'systemStatus'
                );

            if (statusEl)
            {
                statusEl.innerText =

                    data.systemStatus === 'Active'

                    ? 'Secure & Active'

                    : 'Idle';
            }

            // LOGS
            const logsEl =
                document.getElementById(
                    'totalLogs'
                );

            if (logsEl)
            {
                logsEl.innerText =

                    new Intl.NumberFormat()

                    .format(

                        data.totalLogs
                    )

                    + ' Entry';
            }

            // NODE
            const nodesEl =
                document.getElementById(
                    'totalNodes'
                );

            if (nodesEl)
            {
                nodesEl.innerText =

                    data.totalNodes

                    + ' Active';
            }

            // UPTIME
            const uptimeEl =
                document.getElementById(
                    'uptime'
                );

            if (uptimeEl)
            {
                uptimeEl.innerText =
                    data.uptime;
            }
        })

        .catch(error =>
        {
            console.log(
                'Realtime Error:',
                error
            );
        });
    }

    // ======================================================
    // AUTO REFRESH
    // ======================================================

    setInterval(

        updateStats,

        5000
    );

</script>

@endsection