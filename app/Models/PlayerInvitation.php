<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $player_id
 * @property string $email
 * @property string $token
 * @property string $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PlayerInvitation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
