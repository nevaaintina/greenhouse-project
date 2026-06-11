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
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel sensors
            $table->foreignId('sensor_id')->constrained()->cascadeOnDelete();

            // Nilai pembacaan sensor (Suhu, Kelembapan, dll.)
            $table->float('value');

            // Waktu saat data sensor tercatat oleh ESP32/Sistem
            $table->timestamp('recorded_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_data');
    }
};