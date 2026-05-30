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
        Schema::table('users', function (Blueprint $table)
        {
            $table->foreignId(

                'active_greenhouse_id'

            )->nullable()

             ->after('role')

             ->constrained('greenhouses')

             ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->dropForeign([

                'active_greenhouse_id'
            ]);

            $table->dropColumn(

                'active_greenhouse_id'
            );
        });
    }
};