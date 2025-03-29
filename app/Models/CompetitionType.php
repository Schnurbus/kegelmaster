<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\CompetitionType
 *
 * @property int $id
 * @property string $name
 * @property int $type
 * @property bool $is_sex_specific
 * @property int $position
 * @property string $description
 * @property int $club_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Club $club
 *
 * @method static \Database\Factories\CompetitionTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereIsSexSpecific($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType wherePosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CompetitionType whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CompetitionType extends Model
{
    /** @use HasFactory<\Database\Factories\CompetitionTypeFactory> */
    use HasFactory;

    protected $fillable = ['club_id', 'name', 'description', 'type', 'is_sex_specific', 'position'];

    protected $casts = [
        'is_sex_specific' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }
}
