@extends('layouts.app')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10 px-6">
    <div class="flex items-center gap-3">
        <!-- TOMBOL HAMBURGER -->
        <button class="block md:hidden text-forest p-1 focus:outline-none" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">menu</span>
        </button>

        <div>
            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
                Activity Log
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

<!-- MOBILE SIDEBAR OVERLAY (SESUAI SMARTGROW) -->
<div id="mobile-sidebar" class="fixed inset-0 bg-black/50 z-50 hidden md:hidden transition-opacity">
    <div class="bg-forest w-72 h-full p-6 relative shadow-2xl text-white">
        <!-- TOMBOL X UNTUK MENUTUP -->
        <button class="absolute top-5 right-5 text-white/80 hover:text-white" onclick="toggleSidebar()">
            <span class="material-symbols-rounded text-3xl">close</span>
        </button>

        <!-- LOGO SMARTGROW -->
        <div class="flex items-center gap-3 mb-10 mt-4">
            <span class="material-symbols-rounded text-4xl text-green-400">potted_plant</span>
            <h1 class="text-xl font-bold tracking-widest uppercase">SmartGrow</h1>
        </div>

        <!-- NAVIGASI MENU LENGKAP -->
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
</div>

<main class="px-6">
    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                <tr>
                    <th class="p-4">Time</th>
                    <th class="p-4">Activity</th>
                    <th class="p-4">User</th>
                    <th class="p-4 text-right">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-sm text-gray-400">
                        {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                    </td>
                    <td class="p-4 font-bold text-forest uppercase text-sm">
                        {{ $log->activity }}
                    </td>
                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-[10px] font-bold">
                            {{ optional($log->user)->name ?? 'SYSTEM' }}
                        </span>
                    </td>
                    <td class="p-4 text-right text-xs text-gray-600">
                        {{ $log->description ?? '-' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-400">
                        Belum ada data log
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
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