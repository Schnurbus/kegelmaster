<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class FeeEntry extends Model
{
    /** @use HasFactory<\Database\Factories\FeeEntryFactory> */
    use HasFactory;

    protected $fillable = ['matchday_id', 'fee_type_version_id', 'player_id', 'amount'];

    public function feeTypeVersion()
    {
        return $this->belongsTo(FeeTypeVersion::class);
    }

    public function matchday()
    {
        return $this->belongsTo(Matchday::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
