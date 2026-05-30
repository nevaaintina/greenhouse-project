@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<main class="max-w-7xl mx-auto p-5 md:p-8 text-slate-700">

    <!-- HEADER -->
    <header class="flex justify-between items-center mb-10">

        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Edit Information

            </h2>

            <p class="text-xs text-gray-400 mt-1">

                Perbarui Data Akun & Greenhouse

            </p>

        </div>

        <a href="{{ route('profile') }}"
            class="bg-white px-4 py-2 rounded-xl text-xs font-semibold text-gray-400 border hover:text-forest transition">

            KEMBALI

        </a>

    </header>

    <!-- NOTIFICATION -->
    @if(session('success'))

    <div class="mb-5 bg-green-50 border border-green-100 text-green-600 text-xs font-semibold px-4 py-3 rounded-2xl">

        {{ session('success') }}

    </div>

    @endif

    @if(session('error'))

    <div class="mb-5 bg-red-50 border border-red-100 text-red-500 text-xs font-semibold px-4 py-3 rounded-2xl">

        {{ session('error') }}

    </div>

    @endif

    <!-- VALIDATION ERROR -->
    @if ($errors->any())

    <div class="mb-5 bg-red-50 border border-red-100 text-red-500 text-xs px-4 py-3 rounded-2xl">

        <ul class="space-y-1 list-disc pl-4">

            @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif

    <!-- FORM -->
    <form action="{{ route('profile.update') }}" method="POST">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- LEFT -->
            <div class="lg:col-span-4">

                <div class="bg-white p-6 rounded-3xl shadow-sm border text-center">

                    <!-- AVATAR -->
                    <div
                        class="w-24 h-24 bg-forest text-white flex items-center justify-center rounded-2xl text-3xl font-black mx-auto shadow-lg shadow-forest/20">

                        {{ strtoupper(substr($user->name,0,1)) }}

                    </div>

                    <!-- NAME -->
                    <h3 class="mt-4 text-sm font-black text-forest uppercase">

                        {{ $user->name }}

                    </h3>

                    <!-- ROLE -->
                    <p class="text-xs text-gray-400 mt-1">

                        {{ ucfirst($user->role ?? 'User') }}

                    </p>

                    <!-- GREENHOUSE -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-2xl border border-gray-100">

                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">

                            Active Greenhouse

                        </p>

                        <p class="text-sm font-black text-forest mt-1">

                            {{ $greenhouse->name ?? 'Smart Greenhouse' }}

                        </p>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="lg:col-span-8 space-y-6">

                <!-- PERSONAL -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border">

                    <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">

                        Personal Information

                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- NAME -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Full Name

                            </label>

                            <input type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- EMAIL -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Email

                            </label>

                            <input type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- PHONE -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Phone

                            </label>

                            <input type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                    </div>

                </div>

                <!-- GREENHOUSE -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border">

                    <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">

                        Greenhouse Information

                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <!-- GREENHOUSE NAME -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Greenhouse Name

                            </label>

                            <input type="text"
                                name="greenhouse_name"
                                value="{{ old('greenhouse_name', $greenhouse->name ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- LOCATION -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Location

                            </label>

                            <input type="text"
                                name="location"
                                value="{{ old('location', $greenhouse->location ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- SIZE -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Greenhouse Size

                            </label>

                            <input type="text"
                                name="size"
                                value="{{ old('size', $greenhouse->size ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- PLANT -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Plant Type

                            </label>

                            <input type="text"
                                name="plant_type"
                                value="{{ old('plant_type', $greenhouse->plant_type ?? '') }}"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                    </div>

                </div>

                <!-- PASSWORD -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border">

                    <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-6">

                        Change Password

                    </h4>

                    <div class="space-y-4">

                        <!-- CURRENT -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Current Password

                            </label>

                            <input type="password"
                                name="current_password"
                                placeholder="Masukkan password lama"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- NEW -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                New Password

                            </label>

                            <input type="password"
                                name="new_password"
                                placeholder="Masukkan password baru"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                        <!-- CONFIRM -->
                        <div>

                            <label class="text-[10px] font-bold text-gray-400 uppercase">

                                Confirm Password

                            </label>

                            <input type="password"
                                name="new_password_confirmation"
                                placeholder="Konfirmasi password baru"
                                class="w-full bg-gray-50 border border-gray-100 rounded-2xl px-4 py-3 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- BUTTON -->
        <div class="flex justify-end mt-8">

            <button type="submit"
                class="bg-forest text-white px-8 py-3 rounded-2xl text-xs font-black tracking-widest uppercase hover:scale-95 transition shadow-lg shadow-forest/20">

                Simpan Perubahan

            </button>

        </div>

    </form>

</main>

@endsection