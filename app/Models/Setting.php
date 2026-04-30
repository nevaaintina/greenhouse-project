<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'greenhouse_id',
        'temperature_min',
        'temperature_max',
        'humidity_min',
        'humidity_max',
        'soil_moisture_min',
        'soil_moisture_max',
        'light_min',
        'light_max',
        'system_mode'
    ];
}