<?php

namespace App\Policies;

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

        return $user->players()
            ->where('club_id', $clubId)
            ->exists() && $user->can('list.FeeType');
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('view.FeeType');

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
        $hasPermission = $user->can('create.FeeType');

        return $hasPlayer && $hasPermission;
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('update.FeeType');

        return $hasPlayer && $hasPermission;
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

        $hasPlayer = $user->players()
            ->where('club_id', $clubId)
            ->exists();
        $hasPermission = $user->can('delete.FeeType');

        return $hasPlayer && $hasPermission;
    }
}
