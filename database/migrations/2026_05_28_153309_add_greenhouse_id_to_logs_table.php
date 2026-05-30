<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations
     */
    public function up(): void
    {
        Schema::table('logs', function (Blueprint $table)
        {
            $table->foreignId(

                'greenhouse_id'

            )->nullable()

             ->after('user_id')

             ->constrained('greenhouses')

             ->nullOnDelete();
        });
    }

    /**
     * Reverse migrations
     */
    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table)
        {
            $table->dropForeign([

                'greenhouse_id'
            ]);

            $table->dropColumn(

                'greenhouse_id'
            );
        });
    }
};