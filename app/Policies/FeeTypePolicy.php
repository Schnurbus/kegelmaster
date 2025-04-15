<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\FeeType;
use App\Models\User;

class FeeTypePolicy
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

        return $player && $player->can(PermissionsEnum::LIST_FEE_TYPE->value);

    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?FeeType $feeType = null): bool
    {
        $clubId = $feeType->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::VIEW_FEE_TYPE->value);

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

        return $player && $player->can(PermissionsEnum::CREATE_FEE_TYPE->value);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?FeeType $feeType = null): bool
    {
        $clubId = $feeType->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::UPDATE_FEE_TYPE->value);

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?FeeType $feeType = null): bool
    {
        $clubId = $feeType->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::DELETE_FEE_TYPE->value);

    }
}
