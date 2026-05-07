<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Actuator extends Model
{
    protected $fillable = [
        'greenhouse_id',
        'name',
        'type',
        'status',
        'mode'
    ];
}