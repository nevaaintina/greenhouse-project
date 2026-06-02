@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')

<!-- HEADER -->
<header class="flex justify-between items-center mb-10">

    <div class="flex items-center gap-3">

        <div>

            <h2 class="text-xl md:text-2xl font-bold text-forest uppercase">

                Logs Activity

            </h2>

            <p class="text-xs text-gray-400 mt-1">

                Last Update:
                {{ now()->format('d M Y H:i') }}

            </p>

        </div>

    </div>

    <a href="/profile"
    class="flex items-center gap-3 bg-white p-2 px-4 rounded-full shadow border">

        <div class="w-8 h-8 bg-forest text-white flex items-center justify-center rounded-full font-bold">

            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

        </div>

        <span class="text-sm font-semibold text-forest hidden sm:block">

            {{ auth()->user()->name }}

        </span>

    </a>

</header>

<main class="px-6">

<!-- 🔍 FILTER -->
<form method="GET" action="{{ route('logs.index') }}" class="mb-4 flex flex-wrap gap-2 justify-end">

    <!-- SEARCH ACTIVITY -->
    <input 
        type="text" 
        name="activity" 
        value="{{ request('activity') }}"
        placeholder="Cari activity..."
        class="border border-gray-200 rounded-xl px-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-forest"
    >

    <!-- FILTER DATE -->
    <input 
        type="date" 
        name="date" 
        value="{{ request('date') }}"
        class="border border-gray-200 rounded-xl px-4 py-2 text-xs"
    >

    <button class="bg-forest text-white px-4 py-2 rounded-xl text-xs font-bold">
        Filter
    </button>

</form>

<!-- INFO FILTER -->
@if(request('activity') || request('date'))
<p class="text-xs text-gray-400 mb-4">
    Filter:
    @if(request('activity'))
        Activity: <span class="font-bold text-forest">{{ request('activity') }}</span>
    @endif

    @if(request('date'))
        | Tanggal: <span class="font-bold text-forest">{{ request('date') }}</span>
    @endif
</p>
@endif

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
                    {{ $log->created_at?->format('d M Y, H:i') ?? '-' }}
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
                    Tidak ada data ditemukan
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PAGINATION -->
    <div class="p-4">
        {{ $logs->links() }}
    </div>

</div>

</main>
@endsection