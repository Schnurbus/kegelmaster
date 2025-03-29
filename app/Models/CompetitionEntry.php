<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CompetitionEntry
 *
 * @property int $id
 * @property int $amount
 * @property int $matchday_id
 * @property int $player_id
 * @property int $competition_type_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Matchday $matchday
 * @property-read \App\Models\Player $player
 *
 * @method static \Database\Factories\CompetitionEntryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereCompetitionTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereMatchdayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionEntry whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CompetitionEntry extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionEntryFactory> */
    use HasFactory;

    protected $fillable = ['matchday_id', 'competition_type_id', 'player_id', 'amount'];

    public function matchday(): BelongsTo
    {
        return $this->belongsTo(Matchday::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
