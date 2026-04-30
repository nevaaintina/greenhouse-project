<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    protected $table = 'sensor_data';

    protected $fillable = [
        'sensor_id',
        'value',
        'recorded_at'
    ];

    public function sensor()
    {
        return $this->belongsTo(Sensor::class);
    }
}