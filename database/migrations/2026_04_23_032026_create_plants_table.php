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
    Schema::create('plants', function (Blueprint $table) {
        $table->id();

        $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

        $table->string('name');
        $table->string('type');
        $table->date('planting_date');
        $table->date('harvest_estimate')->nullable();
        $table->enum('status', ['aktif','panen'])->default('aktif');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
