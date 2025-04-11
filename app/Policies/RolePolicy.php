<?php

namespace App\Policies;

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

        return $user->players()
            ->where('club_id', $clubId)
            ->exists() && $user->can('list.Role');
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('view.Role');

        return $hasPlayer && $hasPermission;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, int $clubId): bool
    {
        if (setClubContext($user, $clubId)) {
            return true;
        }

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('create.Role');

        return $hasPlayer && $hasPermission;
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('update.Role');

        return $hasPlayer && $hasPermission;
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('delete.Role');

        return $hasPlayer && $hasPermission;
    }
}
