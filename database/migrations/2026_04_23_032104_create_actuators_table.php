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
        Schema::create('actuators', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel greenhouses
            $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

            $table->string('name'); // Contoh: Kipas Aliran Udara, Pompa Air Utama
            $table->string('type'); // <-- KOLOM INI YANG TADI KETINGGALAN (Contoh: fan, pump, lamp)
            
            // Menggunakan string dengan default agar aman di SQLite & MySQL
            $table->string('status')->default('off'); // Nilai: 'on' atau 'off'
            $table->string('mode')->default('auto');   // Nilai: 'manual' atau 'auto'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuators');
    }
};