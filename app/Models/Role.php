<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Concerns\IsRole;

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
