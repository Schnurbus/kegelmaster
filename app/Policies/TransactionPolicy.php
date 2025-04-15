<?php

namespace App\Policies;

use App\Enums\PermissionsEnum;
use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
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

        return $player && $player->can(PermissionsEnum::LIST_TRANSACTION->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::VIEW_TRANSACTION->value);

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

        return $player && $player->can(PermissionsEnum::CREATE_TRANSACTION->value);

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::UPDATE_TRANSACTION->value);

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ?Transaction $transaction = null): bool
    {
        $clubId = $transaction->club_id ?? session('current_club_id');

        if (empty($clubId)) {
            return false;
        }

        if (setClubContext($user, $clubId)) {
            return true;
        }

        $player = $user->player($clubId);

        return $player && $player->can(PermissionsEnum::DELETE_TRANSACTION->value);

    }
}
