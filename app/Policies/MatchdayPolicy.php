<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Matchday;
use App\Models\User;

class MatchdayPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function list(User $user, int $clubId): bool
    {
        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::LIST_MATCHDAY->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::VIEW_MATCHDAY->value);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $clubId): bool
    {
        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::CREATE_MATCHDAY->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::UPDATE_MATCHDAY->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Matchday $matchday = null): bool
    {
        $clubId = $matchday->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::DELETE_MATCHDAY->value);
    }
}
