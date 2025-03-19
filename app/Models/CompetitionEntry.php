<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionEntry extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionEntryFactory> */
    use HasFactory;

    protected $fillable = ['matchday_id', 'competition_type_id', 'player_id', 'amount'];

    public function matchday()
    {
        return $this->belongsTo(Matchday::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
