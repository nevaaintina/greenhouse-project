@extends('layouts.guest')

@section('title', 'Verifikasi Email')

@section('content')
<div class="flex h-screen w-full items-center justify-center bg-gray-50">
    <div class="w-full max-w-md p-8 bg-white rounded-2xl shadow-lg border border-gray-100 text-center">
        
        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-5 text-emerald-600">
            <span class="material-symbols-rounded text-3xl">mark_email_read</span>
        </div>

        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Verifikasi Email Anda</h2>
        <p class="text-slate-500 text-sm mt-4 leading-relaxed">
            Sebelum melanjutkan, silakan periksa kotak masuk email Anda untuk melihat link verifikasi yang telah kami kirimkan.
        </p>

        @if (session('message'))
            <div class="mt-4 bg-green-50 text-green-600 p-3 rounded-xl text-xs font-semibold">
                {{ session('message') }}
            </div>
        @endif

        <div class="mt-8 space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full bg-forest text-white py-4 rounded-2xl font-bold hover:bg-green-900 transition shadow-lg shadow-green-900/10 uppercase tracking-wider text-sm">
                    Kirim Ulang Email Verifikasi
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-slate-100 text-slate-600 py-4 rounded-2xl font-bold hover:bg-slate-200 transition tracking-wider text-sm uppercase">
                    Batal & Kembali ke Login
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    /* Sinkronisasi animasi dengan halaman login jika diperlukan */
    .flex {
        animation: fadeIn 0.5s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection