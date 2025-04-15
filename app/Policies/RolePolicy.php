<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Role;
use App\Models\User;

class RolePolicy
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

        return $player && $player->can(PermissionsEnum::LIST_ROLE->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?Role $role = null): bool
    {
        $clubId = $role->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::VIEW_ROLE->value);
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

        return $player && $player->can(PermissionsEnum::CREATE_ROLE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Role $role = null): bool
    {
        $clubId = $role->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::UPDATE_ROLE->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Role $role = null): bool
    {
        $clubId = $role->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::DELETE_ROLE->value);
    }
}
