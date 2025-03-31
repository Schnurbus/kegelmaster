<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Matchday
 *
 * @property int $id
 * @property string $date
 * @property string $notes
 * @property int $club_id
 * @property bool $is_calculated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club $club
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CompetitionEntry> $competitionEntries
 * @property-read int|null $competition_entries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FeeEntry> $feeEntries
 * @property-read int|null $fee_entries_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FeeTypeVersion> $feeTypeVersions
 * @property-read int|null $fee_type_versions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Player> $players
 * @property-read int|null $players_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 *
 * @method static \Database\Factories\MatchdayFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereIsCalculated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matchday whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Matchday extends Model
{
    /** @use HasFactory<\Database\Factories\MatchdayFactory> */
    use HasFactory;
    // private mixed $club;

    protected $fillable = [
        'club_id',
        'date',
        'notes',
    ];

    /**
     * Get the club relationship
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * Get the club associated with this matchday
     */
    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function players(): BelongsToMany
    {
        // return $this->belongsToMany(Player::class, 'matchday_player')->withTimestamps();
        // return $this->belongsToMany(Player::class, 'matchday_player')->withPivot('position');
        return $this->belongsToMany(Player::class, 'matchday_player')->withPivot('created_at');
        // return $this->belongsToMany(Player::class, 'matchday_player');
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
