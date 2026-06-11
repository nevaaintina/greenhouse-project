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
        Schema::create('sensors', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel greenhouses
            $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Contoh: DHT22, LDR
            $table->string('type'); // Contoh: Suhu, Kelembapan, Cahaya
            $table->string('unit'); // Contoh: °C, %, Lux

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};