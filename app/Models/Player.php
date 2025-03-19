<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Player
 * @property float initial_balance
 * @property float balance
 */
class Player extends Model
{
    /** @use HasFactory<\Database\Factories\PlayerFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'club_id',
        'user_id',
        'sex',
        'active',
        'role_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($player) {
            $player->balance = $player->initial_balance;
        });
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function matchdays(): BelongsToMany
    {
        return $this->belongsToMany(Matchday::class, 'matchday_player');
    }

    public function feeEntries(): HasMany
    {
        return $this->hasMany(FeeEntry::class);
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn(int $value) => $value / 100,
            set: fn(float $value) => $value * 100,
        );
    }

    // public function setBalanceAttribute($value)
    // {
    //     $this->attributes['balance'] = $value * 100;
    // }

    // public function getBalanceAttribute($value)
    // {
    //     return $value / 100;
    // }
}
