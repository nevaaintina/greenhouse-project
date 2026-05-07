@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto p-5 md:p-8 text-slate-700">

<header class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">
            Edit Information
        </h2>
        <p class="text-xs text-gray-400 mt-1">
            Perbarui Data Akun
        </p>
    </div>

    <a href="/profile" class="bg-white px-4 py-2 rounded-xl text-xs font-semibold text-gray-400 border hover:text-forest transition">
        KEMBALI
    </a>
</header>

{{-- NOTIF --}}
@if(session('success'))
<div class="text-green-500 text-xs mb-4 font-semibold">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="text-red-500 text-xs mb-4 font-semibold">
    {{ session('error') }}
</div>
@endif

<form action="{{ route('profile.update') }}" method="POST">
@csrf
@method('PUT')

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

<!-- LEFT -->
<div class="lg:col-span-4">
    <div class="bg-white p-6 rounded-3xl shadow text-center border">

        <div class="w-24 h-24 bg-forest text-white flex items-center justify-center rounded-2xl text-3xl font-bold mx-auto">
            {{ strtoupper(substr($user->name,0,1)) }}
        </div>

        <h3 class="mt-4 text-sm font-bold text-forest uppercase">
            {{ $user->name }}
        </h3>

        <p class="text-xs text-gray-400 mt-1">
            {{ $user->role ?? 'User' }}
        </p>

    </div>
</div>

<!-- RIGHT -->
<div class="lg:col-span-8 space-y-6">

<div class="bg-white p-6 rounded-3xl shadow border">

<h4 class="text-[10px] font-bold uppercase text-gray-400 mb-6">
Personal Information
</h4>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

<!-- NAME -->
<div>
<label class="text-[10px] font-semibold text-gray-400">Full Name</label>
<input type="text" name="name"
value="{{ old('name',$user->name) }}"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">
</div>

<!-- EMAIL -->
<div>
<label class="text-[10px] font-semibold text-gray-400">Email</label>
<input type="email" name="email"
value="{{ old('email',$user->email) }}"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">
</div>

<!-- PHONE -->
<div>
<label class="text-[10px] font-semibold text-gray-400">Phone</label>
<input type="text" name="phone"
value="{{ old('phone',$user->phone) }}"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">
</div>

<!-- FARM TYPE -->
<div>
<label class="text-[10px] font-semibold text-gray-400">Farm Type</label>
<input type="text" name="farm_type"
value="{{ old('farm_type',$user->farm_type) }}"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">
</div>

</div>

<!-- GREENHOUSE -->
<div class="mt-6">
<label class="text-[10px] font-semibold text-gray-400">Greenhouse Name</label>
<input type="text" name="greenhouse_name"
value="{{ old('greenhouse_name',$user->greenhouse_name) }}"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">
</div>

</div>

<!-- PASSWORD -->
<div class="bg-white p-6 rounded-3xl shadow border">

<h4 class="text-[10px] font-bold uppercase text-gray-400 mb-4">
Change Password
</h4>

<input type="password" name="current_password" placeholder="Password lama"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none mb-3 focus:ring-2 focus:ring-forest/20">

<input type="password" name="new_password" placeholder="Password baru"
class="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2 text-sm font-semibold text-forest outline-none focus:ring-2 focus:ring-forest/20">

</div>

</div>

</div>

<div class="flex justify-end mt-6">
<button class="bg-forest text-white px-6 py-3 rounded-xl text-xs font-bold hover:scale-95 transition">
SIMPAN PERUBAHAN
</button>
</div>

</form>

</main>

@endsection