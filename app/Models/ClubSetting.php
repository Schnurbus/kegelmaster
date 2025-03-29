<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $club_id
 * @property string $name
 * @property int $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\ClubSettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClubSetting whereValue($value)
 *
 * @mixin \Eloquent
 */
class ClubSetting extends Model
{
    /** @use HasFactory<\Database\Factories\ClubSettingFactory> */
    use HasFactory;
}
