<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * App\Models\Club
 * @property float initial_balance
 * @property float balance
 */
class Club extends Model
{
    /** @use HasFactory<\Database\Factories\ClubFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'base_fee',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function competitionTypes(): HasMany
    {
        return $this->hasMany(CompetitionType::class);
    }

    public function matchdays(): HasMany
    {
        return $this->hasMany(Matchday::class);
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function setInitialBalanceAttribute($value)
    {
        $this->attributes['initial_balance'] = $value * 100;
    }

    public function getInitialBalanceAttribute($value)
    {
        return $value / 100;
    }

    protected function balance(): Attribute
    {
        return Attribute::make(
            get: fn(int $value): float => $value / 100,
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

    public function setBaseFeeAttribute($value)
    {
        $this->attributes['base_fee'] = $value * 100;
    }

    public function getBaseFeeAttribute($value)
    {
        return $value / 100;
    }
}
