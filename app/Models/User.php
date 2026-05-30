<?php

namespace App\Models;

use Database\Factories\UserFactory;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;

use App\Models\Log;
use App\Models\Greenhouse;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // ======================================================
    // FILLABLE
    // ======================================================

    protected $fillable = [

        'name',
        'email',
        'phone',
        'password',
        'active_greenhouse_id'
    ];

    // ======================================================
    // HIDDEN
    // ======================================================

    protected $hidden = [

        'password',
        'remember_token'
    ];

    // ======================================================
    // CASTS
    // ======================================================

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ======================================================
    // LOGS
    // ======================================================

    public function logs()
    {
        return $this->hasMany(

            Log::class
        );
    }

    // ======================================================
    // GREENHOUSES
    // ======================================================

    public function greenhouses()
    {
        return $this->hasMany(

            Greenhouse::class
        );
    }

    // ======================================================
    // ACTIVE GREENHOUSE
    // ======================================================

    public function activeGreenhouse()
    {
        return $this->belongsTo(

            Greenhouse::class,

            'active_greenhouse_id'
        );
    }
}