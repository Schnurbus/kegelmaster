<?php

namespace App\Policies;

use App\Models\FeeType;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FeeTypePolicy
{
    // public function before(User $user, string $ability, mixed $arguments): bool|null
    // {
    //     // if ($arguments instanceof FeeType && $user->id === $arguments->club->user_id) {
    //     //     Log::debug('User is owner of fee type', ['ability' => $ability, 'argument' => $arguments]);
    //     //     return true;
    //     // }

    //     return null;
    // }

    public function list(User $user): null
    {
        return null;
    }

    public function create(User $user): null
    {
        return null;
    }
}
