<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RolePolicy
{
    // public function before(User $user, string $ability, mixed $class, ?array $arguments): bool|null
    // public function before(User $user, string $ability, $arguments): bool|null
    // {
    //     Log::debug('Role policy before called', ['ability' => $ability, 'arguments' => $arguments]);

    //     if (isset($arguments) && $arguments instanceof Role) {
    //         $role = $arguments;
    //         $club = Club::find($role->scope);
    //         if ($club && $club->user_id === $user->id) {
    //             return true;
    //         }
    //     }
    //     return null;
    // }

    /**
     * Determine whether the user can list the models.
     */
    public function list(User $user) {}

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Role $role) {}

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user) {}

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role) {}

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role) {}

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
