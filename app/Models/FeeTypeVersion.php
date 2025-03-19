<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeTypeVersion extends Model
{
    protected $fillable = ['fee_type_id', 'name', 'description', 'amount'];

    public function feeType(): BelongsTo
    {
        return $this->belongsTo(FeeType::class);
    }

    public function matchdays(): BelongsToMany
    {
        return $this->belongsToMany(Matchday::class, 'fee_type_version_matchday');
    }

    public function feeEntries(): HasMany
    {
        return $this->hasMany(FeeEntry::class);
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
