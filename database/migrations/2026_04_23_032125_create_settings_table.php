<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel greenhouses
            $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

            // Threshold Suhu Udara
            $table->float('temperature_min');
            $table->float('temperature_max');

            // Threshold Kelembapan Udara
            $table->float('humidity_min');
            $table->float('humidity_max');

            // Threshold Kelembapan Tanah
            $table->float('soil_moisture_min');
            $table->float('soil_moisture_max');

            // Threshold Intensitas Cahaya (Urutan sesuai phpMyAdmin lokal)
            $table->float('light_min');
            $table->float('light_max');

            // Mode Sistem (Menggunakan string agar fleksibel dan aman di SQLite)
            $table->string('system_mode');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};