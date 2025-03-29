<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\FeeEntry
 *
 * @property int $id
 * @property float $amount
 * @property int $matchday_id
 * @property int $player_id
 * @property int $fee_type_version_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\FeeTypeVersion $feeTypeVersion
 * @property-read \App\Models\Matchday $matchday
 * @property-read \App\Models\Player $player
 * @property-read \App\Models\Transaction|null $transaction
 *
 * @method static \Database\Factories\FeeEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereFeeTypeVersionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereMatchdayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FeeEntry whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class FeeEntry extends Model
{
    /** @use HasFactory<\Database\Factories\FeeEntryFactory> */
    use HasFactory;

    protected $fillable = ['matchday_id', 'fee_type_version_id', 'player_id', 'amount'];

    public function feeTypeVersion(): BelongsTo
    {
        return $this->belongsTo(FeeTypeVersion::class);
    }

    public function matchday(): BelongsTo
    {
        return $this->belongsTo(Matchday::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function transaction(): HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
