<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->float('light_min')->after('soil_moisture_max');
        $table->float('light_max')->after('light_min');
    });
}

public function down()
{
    Schema::table('settings', function (Blueprint $table) {
        $table->dropColumn(['light_min', 'light_max']);
    });
}
};
