<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // ======================================================
        // LOCALE INDONESIA
        // ======================================================

        Carbon::setLocale('id');

        setlocale(

            LC_TIME,

            'id_ID',

            'id_ID.UTF-8',

            'Indonesian'
        );
    }
}