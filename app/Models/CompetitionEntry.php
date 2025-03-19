<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
