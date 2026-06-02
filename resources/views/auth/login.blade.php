@extends('layouts.guest')

@section('title', 'Login')

@section('content')



<div class="flex h-screen w-full">

    <!-- ======================================================
    LEFT
    ======================================================= -->

    <div class="hidden lg:flex lg:w-7/12 relative overflow-hidden bg-forest">

        <div
        class="absolute inset-0 bg-gradient-to-br
        from-forest/90 to-emerald-950/80 z-10">
        </div>

        <img

        src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?auto=format&fit=crop&w=1200&q=80"

        class="absolute inset-0 w-full h-full object-cover
        mix-blend-overlay opacity-60 scale-110">

        <div
        class="relative z-20 m-auto p-16 w-full animate-fade-up">

            <div
            class="w-20 h-20 bg-white/10 backdrop-blur-xl
            rounded-[2.5rem] flex items-center justify-center
            mb-8 border border-white/20">

                <span
                class="material-symbols-rounded text-white text-4xl">

                    potted_plant

                </span>

            </div>

            <h1
            class="text-6xl font-black text-white
            leading-tight tracking-tighter italic">

                SmartGrow

                <br>

                <span class="text-emerald-300 not-italic">

                    Ultimate.

                </span>

            </h1>

            <p
            class="text-white/70 text-lg mt-6
            max-w-lg leading-relaxed font-light italic">

                "Skip repetitive gardening tasks.
                Get highly productive through IoT automation
                and monitoring for your greenhouse."

            </p>

        </div>

        <div
        class="absolute bottom-10 left-16 z-20
        text-white/30 text-[10px]
        font-bold tracking-[0.3em] uppercase">

            © {{ date('Y') }} SmartGrow.

        </div>

    </div>

    <!-- ======================================================
    RIGHT
    ======================================================= -->

    <div
    class="w-full lg:w-5/12 flex flex-col
    items-center justify-center p-8 md:p-20
    relative bg-white">

        <div
        class="w-full max-w-sm animate-fade-up">

            <!-- TITLE -->
            <header
            class="mb-10 text-center lg:text-left">

                <h2
                class="text-3xl font-black
                text-slate-800 tracking-tight">

                    Login Akun Petani

                </h2>

                <p
                class="text-slate-400 text-sm
                mt-2 font-medium">

                    Masukkan email dan password
                    untuk mengakses sistem SmartGrow.

                </p>

            </header>

            <!-- ERROR -->
            @if ($errors->any())

            <div
            class="bg-red-50 border border-red-100
            text-red-600 p-4 rounded-2xl text-sm mb-5">

                {{ $errors->first() }}

            </div>

            @endif

            <!-- SUCCESS -->
            @if(session('success'))

            <div
            class="bg-green-50 border border-green-100
            text-green-600 p-4 rounded-2xl text-sm mb-5">

                {{ session('success') }}

            </div>

            @endif

            <!-- FORM -->
            <form
            method="POST"
            action="{{ route('login.process') }}"
            class="space-y-6">

                @csrf

                <!-- EMAIL -->
                <div>

                    <label
                    class="text-xs font-bold
                    text-slate-400 uppercase">

                        Email

                    </label>

                    <input

                    type="email"

                    name="email"

                    required

                    value="{{ old('email') }}"

                    class="w-full p-4 mt-2 border border-gray-200
                    rounded-2xl focus:ring-2
                    focus:ring-forest/20 focus:border-forest
                    outline-none transition"

                    placeholder="email@gmail.com">

                </div>

                <!-- PASSWORD -->
                <div>

                    <label
                    class="text-xs font-bold
                    text-slate-400 uppercase">

                        Password

                    </label>

                    <input

                    type="password"

                    name="password"

                    required

                    class="w-full p-4 mt-2 border border-gray-200
                    rounded-2xl focus:ring-2
                    focus:ring-forest/20 focus:border-forest
                    outline-none transition"

                    placeholder="••••••••">

                </div>

                <!-- REMEMBER -->
                <div
                class="flex items-center justify-between">

                    <label
                    class="flex items-center gap-2
                    text-sm text-gray-500">

                        <input

                        type="checkbox"

                        name="remember"

                        class="rounded border-gray-300">

                        Ingat Saya

                    </label>

                </div>

                <!-- BUTTON -->
                <button

                type="submit"

                class="w-full bg-forest text-white
                py-4 rounded-2xl font-bold
                hover:bg-green-900 transition-all
                shadow-lg shadow-green-900/10">

                    MASUK SEKARANG

                </button>

            </form>

            <!-- FOOTER -->
            <footer
            class="mt-12 text-center">

                <p
                class="text-[10px] text-slate-300
                font-bold uppercase tracking-widest
                leading-relaxed">

                    Secure Connection

                    <br>

                    <span
                    class="text-emerald-500 italic">

                        SmartGrow Management System

                    </span>

                </p>

            </footer>

        </div>

    </div>

</div>

<style>

    @keyframes fadeUp
    {
        from
        {
            opacity: 0;
            transform: translateY(20px);
        }

        to
        {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-up
    {
        animation: fadeUp .8s ease-out forwards;
    }

</style>

@endsection