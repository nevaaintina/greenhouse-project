<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- Tambahkan ini jika belum ada

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Tambahkan baris ini untuk memaksa HTTPS di VM/Hosting
        if (config('app.env') === 'production' || env('FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
    }
}