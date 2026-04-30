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

        $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

        $table->float('temperature_min');
        $table->float('temperature_max');
        $table->float('humidity_min');
        $table->float('humidity_max');
        $table->float('soil_moisture_min');
        $table->float('soil_moisture_max');


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
