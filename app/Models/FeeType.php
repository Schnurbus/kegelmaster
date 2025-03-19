<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FeeType extends Model
{
    /** @use HasFactory<\Database\Factories\FeeTypeFactory> */
    use HasFactory;

    protected $fillable = ['club_id', 'name', 'description', 'amount', 'position'];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function feeTypeVersions()
    {
        return $this->hasMany(FeeTypeVersion::class);
    }

    public function latestVersion(): HasOne
    {
        return $this->hasOne(FeeTypeVersion::class)->latestOfMany();
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = $value * 100;
    }

    public function getAmountAttribute($value)
    {
        return $value / 100;
    }
}
