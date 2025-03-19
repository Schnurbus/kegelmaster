<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Matchday extends Model
{
    /** @use HasFactory<\Database\Factories\MatchdayFactory> */
    use HasFactory;

    protected $fillable = [
        'club_id',
        'date',
        'notes',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class, 'matchday_player');
    }

    public function feeTypeVersions(): BelongsToMany
    {
        return $this->belongsToMany(FeeTypeVersion::class, 'fee_type_version_matchday');
    }

    public function feeEntries(): HasMany
    {
        return $this->hasMany(FeeEntry::class);
    }

    public function competitionEntries(): HasMany
    {
        return $this->hasMany(CompetitionEntry::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
