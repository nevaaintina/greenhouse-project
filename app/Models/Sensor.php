<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = [
        'greenhouse_id',
        'name',
        'type',
        'unit'
    ];

    // semua data sensor
    public function data()
    {
        return $this->hasMany(SensorData::class);
    }

    // ambil data TERBARU
    public function latestData()
    {
        return $this->hasOne(SensorData::class)->latestOfMany();
    }
}