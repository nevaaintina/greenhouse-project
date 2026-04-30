<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime', // 🔥 INI PENTING
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}