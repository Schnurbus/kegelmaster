<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Concerns\IsRole;

/**
 * App\Models\Role
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $scope
 * @property bool $is_base_fee_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Silber\Bouncer\Database\Ability> $abilities
 * @property-read int|null $abilities_count
 * @property-read \App\Models\Club|null $club
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 *
 * @method static \Database\Factories\RoleFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereAssignedTo($model, ?array $keys = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereIsBaseFeeActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    use HasFactory, IsRole;

    protected $fillable = ['name', 'title', 'is_base_fee_active'];

    protected $casts = [
        'is_base_fee_active' => 'boolean',
    ];

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'scope', 'id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $currentClub = session('currentClub');
        if ($currentClub) {
            BouncerFacade::scope()->to($currentClub->id);
        }

        return $this->where('id', $value)->firstOrFail();
    }
}
