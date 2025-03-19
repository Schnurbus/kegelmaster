<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
