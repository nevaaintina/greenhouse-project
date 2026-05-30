<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Greenhouse extends Model
{
    protected $fillable = [

        'user_id',
        'name',
        'location',
        'size',
        'plant_type'
    ];

    // ======================================================
    // RELATION USER
    // ======================================================

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    // ======================================================
    // RELATION SENSOR
    // ======================================================

    public function sensors()
    {
        return $this->hasMany(
            Sensor::class
        );
    }

    // ======================================================
    // RELATION ACTUATOR
    // ======================================================

    public function actuators()
    {
        return $this->hasMany(
            Actuator::class
        );
    }

    // ======================================================
    // RELATION SETTINGS
    // ======================================================

    public function settings()
    {
        return $this->hasOne(
            Setting::class
        );
    }
}