<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PlayerInvitation extends Model
{
    protected $fillable = [
        'player_id',
        'email',
        'token',
        'expires_at',
    ];

    protected static function defaultExpiresAt(): ?Carbon
    {
        return now()->addDays(7);
    }
}
