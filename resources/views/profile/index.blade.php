@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<style>
    @keyframes sway {
        0% { transform: rotate(0deg); }
        50% { transform: rotate(6deg); }
        100% { transform: rotate(0deg); }
    }
    .plant-sway {
        animation: sway 3.5s ease-in-out infinite;
        transform-origin: bottom center;
    }
    
    /* Animasi halus untuk sidebar */
    #mobile-sidebar {
        transition: all 0.3s ease-in-out;
    }
    #mobile-sidebar.hidden {
        display: none;
        opacity: 0;
    }
    #mobile-sidebar:not(.hidden) {
        display: flex;
        opacity: 1;
    }
</style>

<!-- HEADER -->
<header class="flex justify-between items-center mb-10 px-2">
    <div class="flex items-center gap-3">
        <!-- TOMBOL HAMBURGER -->
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">Administrator Profile</h2>
            <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase">Identity & Greenhouse Management</p>
        </div>
    </div>

    <div class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow-sm border border-gray-100">
        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold text-xs">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>
        <span class="text-sm font-semibold text-forest hidden sm:block">{{ $user->name }}</span>
    </div>
</header>

<!-- MOBILE SIDEBAR OVERLAY -->
<div id="mobile-sidebar" class="fixed inset-0 bg-black/50 z-[999] hidden md:hidden flex-row transition-all duration-300">
    <div class="bg-forest w-72 h-full p-6 relative shadow-2xl text-white flex flex-col">
        <!-- TOMBOL CLOSE -->
        <button class="absolute top-5 right-5 text-white/80 hover:text-white" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">close</span>
        </button>

        <div class="flex items-center gap-3 mb-10 mt-4">
            <span class="material-symbols-rounded text-4xl text-green-400">potted_plant</span>
            <h1 class="text-xl font-bold tracking-widest uppercase">SmartGrow</h1>
        </div>

        <nav class="flex flex-col gap-2">
            <a href="/dashboard" class="flex items-center gap-4 p-3 {{ Request::is('dashboard*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">grid_view</span> Dashboard
            </a>
            <a href="/sensors" class="flex items-center gap-4 p-3 {{ Request::is('sensors*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">sensors</span> Sensors
            </a>
            <a href="/grafik" class="flex items-center gap-4 p-3 {{ Request::is('grafik*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">show_chart</span> Grafik & Riwayat
            </a>
            <a href="/logs" class="flex items-center gap-4 p-3 {{ Request::is('logs*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">history</span> Log Activity
            </a>
            <a href="/profile" class="flex items-center gap-4 p-3 {{ Request::is('profile*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">person</span> Profile
            </a>
            <a href="/settings" class="flex items-center gap-4 p-3 {{ Request::is('settings*') ? 'bg-white/10 font-semibold' : 'text-white/80' }} rounded-xl transition">
                <span class="material-symbols-rounded">settings</span> Pengaturan
            </a>
        </nav>
    </div>
    <!-- Overlay Clickable Area -->
    <div class="flex-1" onclick="toggleSidebar()"></div>
</div>

<!-- PROFILE CONTENT GRID -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-10">
    <!-- LEFT COLUMN -->
    <div class="lg:col-span-4 space-y-6">
        <div class="bg-white rounded-[2rem] p-8 text-center shadow-sm border border-gray-50 relative overflow-hidden">
            <div class="absolute -top-10 -right-10 w-32 h-32 bg-forest/5 rounded-full"></div>
            
            <div class="relative inline-block mb-4">
                <div class="w-32 h-32 bg-forest text-white flex items-center justify-center rounded-[2.5rem] text-5xl font-black shadow-lg shadow-forest/20 rotate-3 transition-transform">
                    {{ strtoupper(substr($user->name,0,1)) }}
                </div>
                <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-500 border-4 border-white rounded-full shadow-sm"></div>
            </div>

            <h3 class="text-2xl font-black text-forest">{{ $user->name }}</h3>
            <div class="inline-block px-4 py-1 bg-gray-100 rounded-full mt-2">
                <p class="text-[10px] text-gray-500 font-bold tracking-tighter uppercase">{{ $user->role ?? 'Chief Administrator' }}</p>
            </div>
            
            <div class="mt-8 space-y-3">
                <a href="{{ route('profile.edit') }}" class="flex items-center justify-center gap-2 w-full bg-forest text-white py-3 rounded-2xl font-bold text-sm hover:scale-[0.98] transition shadow-md">
                    <span class="material-symbols-rounded text-sm">edit</span> Edit Profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-500 py-3 rounded-2xl font-bold text-sm hover:bg-red-50 hover:text-red-500 hover:border-red-100 transition">
                        <span class="material-symbols-rounded text-sm">logout</span> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN -->
    <div class="lg:col-span-8 space-y-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-50">
            <h4 class="font-black text-forest uppercase tracking-widest text-xs mb-8 flex items-center gap-2">
                <span class="w-8 h-[2px] bg-forest"></span> Detail Informasi
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400"><span class="material-symbols-rounded">person</span></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nama Lengkap</p>
                        <p class="font-bold text-forest text-base leading-tight">{{ $user->name }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400"><span class="material-symbols-rounded">mail</span></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat Email</p>
                        <p class="font-bold text-forest text-base leading-tight">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400"><span class="material-symbols-rounded">call</span></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Nomor Telepon</p>
                        <p class="font-bold text-forest text-base leading-tight">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-gray-50 rounded-xl text-gray-400"><span class="material-symbols-rounded">potted_plant</span></div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Jenis Komoditas</p>
                        <p class="font-bold text-forest text-base leading-tight">{{ $user->farm_type ?? 'Ornamental Plants' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-white to-gray-50 rounded-[2rem] p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative">
            <div class="relative z-10 text-center md:text-left">
                <p class="text-[10px] text-gray-400 uppercase tracking-[0.3em] font-black mb-2">Operational Node</p>
                <h3 class="text-3xl font-black text-forest leading-none">{{ $user->greenhouse_name ?? 'Smart Greenhouse' }}</h3>
                <div class="flex items-center gap-2 mt-4 text-green-600">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-bold uppercase tracking-widest">System Synchronized</span>
                </div>
            </div>
            <div class="plant-sway relative">
                <div class="absolute inset-0 bg-green-400/20 blur-3xl rounded-full scale-150"></div>
                <svg viewBox="0 0 200 200" class="w-32 h-32 md:w-40 md:h-40 relative z-10 drop-shadow-2xl">
                    <path d="M100 170 C100 140, 100 120, 100 100" stroke="#166534" stroke-width="6" stroke-linecap="round"/>
                    <path d="M100 110 C60 100, 60 60, 100 70 C120 80, 120 100, 100 110Z" fill="#22c55e"/>
                    <path d="M100 110 C140 100, 140 60, 100 70 C80 80, 80 100, 100 110Z" fill="#16a34a"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- STATISTICS FOOTNOTE -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 px-2">
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">
        <span class="material-symbols-rounded text-forest opacity-50">verified_user</span>
        <div>
            <p class="text-[9px] font-black text-gray-400 uppercase">System Status</p>
            <p class="text-xs font-bold text-forest">Secure & Active</p>
        </div>
    </div>
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">
        <span class="material-symbols-rounded text-forest opacity-50">database</span>
        <div>
            <p class="text-[9px] font-black text-gray-400 uppercase">Total Logs</p>
            <p class="text-xs font-bold text-forest">1,240 Entry</p>
        </div>
    </div>
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">
        <span class="material-symbols-rounded text-forest opacity-50">sensors</span>
        <div>
            <p class="text-[9px] font-black text-gray-400 uppercase">Nodes</p>
            <p class="text-xs font-bold text-forest">4 Active</p>
        </div>
    </div>
    <div class="bg-white/50 backdrop-blur-md p-4 rounded-[1.5rem] border border-dashed border-gray-200 flex items-center gap-3">
        <span class="material-symbols-rounded text-forest opacity-50">update</span>
        <div>
            <p class="text-[9px] font-black text-gray-400 uppercase">Uptime</p>
            <p class="text-xs font-bold text-forest">99.9%</p>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mobile-sidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }
</script>

@endsection