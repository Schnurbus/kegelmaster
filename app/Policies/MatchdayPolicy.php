<?php

namespace App\Policies;

use App\Models\Matchday;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class MatchdayPolicy
{
    // public function before(User $user, string $ability, mixed $arguments): bool|null
    // {
    //     if ($arguments instanceof Matchday && $user->id === $arguments->club->user_id) {
    //         Log::debug('User is owner of matchday', ['ability' => $ability, 'argument' => $arguments]);
    //         return true;
    //     }

    //     return null;
    // }

    public function list(): null
    {
        return null;
    }

    public function create(): null
    {
        return null;
    }

    public function edit(): null
    {
        return null;
    }

    public function delete(): null
    {
        return null;
    }
}
