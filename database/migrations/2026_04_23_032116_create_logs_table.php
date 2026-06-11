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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Relasi ke tabel greenhouses (Bersifat nullable sesuai phpMyAdmin lokal Anda)
            $table->foreignId('greenhouse_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('activity'); // Jenis aktivitas
            $table->text('description')->nullable(); // Detail aktivitas

            // Hanya mencatat waktu pembuatan log tanpa updated_at
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};