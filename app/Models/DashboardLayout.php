<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $club_id
 * @property string $layout
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereClubId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereLayout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DashboardLayout whereUserId($value)
 *
 * @mixin \Eloquent
 */
class DashboardLayout extends Model
{
    protected $fillable = [
        'club_id',
        'user_id',
        'layout',
    ];
}
