<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionFactory> */
    use HasFactory;

    protected $fillable = [
        'club_id',
        'player_id',
        'fee_type_id',
        'matchday_id',
        'fee_entry_id',
        'type',
        'date',
        'amount',
        'notes',
    ];

    protected $casts = [
        'type' => TransactionType::class,
    ];

    public function club(): BelongsTo
    {
        return $this->BelongsTo(Club::class);
    }

    public function player(): BelongsTo
    {
        return $this->BelongsTo(Player::class);
    }

    public function feeEntry(): BelongsTo
    {
        return $this->BelongsTo(FeeEntry::class);
    }

    public function matchday(): BelongsTo
    {
        return $this->BelongsTo(Matchday::class);
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
