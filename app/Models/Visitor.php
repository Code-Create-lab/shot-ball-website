<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'last_url',
        'visits',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
        'visits'       => 'integer',
    ];

    /** Visitors seen within the active window are considered "online now". */
    public const ACTIVE_WINDOW_MINUTES = 5;
}
