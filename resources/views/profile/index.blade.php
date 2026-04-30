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
</style>

<h2 class="text-3xl font-extrabold text-forest mb-1">
    ADMINISTRATOR PROFILE
</h2>
<p class="text-xs text-gray-400 mb-6 tracking-widest">
    MANAGEMENT & SYSTEM IDENTITY
</p>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- LEFT -->
    <div class="bg-white rounded-3xl p-6 text-center shadow">

        <div class="w-24 h-24 bg-forest text-white flex items-center justify-center rounded-2xl text-4xl mx-auto mb-4 shadow">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>

        <h3 class="text-xl font-bold text-forest">
            {{ $user->name }}
        </h3>

        <p class="text-xs text-gray-400 mt-1 tracking-wider">
            {{ ucfirst($user->role) }}
        </p>

        <p class="text-xs text-gray-400">
            {{ $user->location ?? 'Belum diisi' }}
        </p>

        <div class="mt-6 flex gap-2 justify-center">
            <a href="#" class="bg-forest text-white px-4 py-2 rounded-lg text-sm">
                Edit
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="border px-4 py-2 rounded-lg text-sm text-gray-500">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="lg:col-span-2 space-y-6">

        <!-- DETAIL -->
        <div class="bg-white rounded-3xl p-6 shadow">
            <h4 class="font-bold text-forest mb-4">Detail Profil</h4>

            <div class="grid grid-cols-2 gap-6 text-sm">

                <div>
                    <p class="text-gray-400 text-xs">FULL NAME</p>
                    <p class="font-semibold text-forest">{{ $user->name }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">EMAIL</p>
                    <p class="font-semibold text-forest">{{ $user->email }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">PHONE</p>
                    <p class="font-semibold text-forest">{{ $user->phone }}</p>
                </div>

                <div>
                    <p class="text-gray-400 text-xs">FARM TYPE</p>
                    <p class="font-semibold text-forest">
                        {{ $user->farm_type ?? 'Belum diisi' }}
                    </p>
                </div>

            </div>
        </div>

        <!-- 🌿 GREENHOUSE + TANAMAN -->
        <div class="bg-white p-8 rounded-3xl shadow flex flex-col items-center justify-center text-center">

            <!-- NAMA GREENHOUSE (DIBESARKAN) -->
            <p class="text-xs text-gray-400 mb-2 uppercase tracking-widest">
                Greenhouse
            </p>

            <h3 class="text-3xl md:text-4xl font-extrabold text-forest">
                {{ $user->greenhouse_name ?? 'Belum diisi' }}
            </h3>

            <!-- TANAMAN -->
            <div class="plant-sway w-36 h-36 mt-6">
                <svg viewBox="0 0 200 200" fill="none">

                    <!-- batang -->
                    <path d="M100 170 C100 140, 100 120, 100 100"
                          stroke="#166534" stroke-width="5" stroke-linecap="round"/>

                    <!-- daun kiri -->
                    <path d="M100 110 
                             C60 100, 60 60, 100 70 
                             C120 80, 120 100, 100 110Z"
                          fill="#22c55e"/>

                    <!-- daun kanan -->
                    <path d="M100 110 
                             C140 100, 140 60, 100 70 
                             C80 80, 80 100, 100 110Z"
                          fill="#16a34a"/>

                </svg>
            </div>

            <p class="text-xs text-gray-400 mt-6 italic">
                "Pertumbuhan tanaman muda dalam greenhouse."
            </p>

        </div>

    </div>
</div>

@endsection