@extends('layouts.app')

@section('content')

<main class="p-6">

    <h2 class="text-2xl font-bold text-forest mb-6">
        Activity Log
    </h2>

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

                    <!-- 🔥 TIME FIX -->
                    <td class="p-4 text-sm text-gray-400">
                        {{ $log->created_at ? $log->created_at->format('d M Y, H:i') : '-' }}
                    </td>

                    <!-- ACTIVITY -->
                    <td class="p-4 font-bold text-forest uppercase">
                        {{ $log->activity }}
                    </td>

                    <!-- USER -->
                    <td class="p-4">
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded text-xs font-bold">
                            {{ optional($log->user)->name ?? 'SYSTEM' }}
                        </span>
                    </td>

                    <!-- DESC -->
                    <td class="p-4 text-right text-sm text-gray-600">
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

@endsection