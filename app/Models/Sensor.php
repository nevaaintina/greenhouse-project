<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $table = 'sensors';

    protected $fillable = [
        'greenhouse_id',
        'name',
        'type',
        'unit'
    ];

    public function data()
    {
        return $this->hasMany(SensorData::class);
    }

    public function latestData()
    {
        return $this->hasOne(SensorData::class)->latestOfMany();
    }
}