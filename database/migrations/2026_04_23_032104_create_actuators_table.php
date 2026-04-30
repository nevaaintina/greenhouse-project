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

        $table->foreignId('greenhouse_id')->constrained()->cascadeOnDelete();

        $table->string('name');
        $table->enum('status', ['on','off'])->default('off');
        $table->enum('mode', ['manual','auto'])->default('auto');

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
