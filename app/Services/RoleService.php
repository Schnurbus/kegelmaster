<?php

namespace App\Services;

use App\Models\Club;
use App\Models\CompetitionType;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Throwable;

class RoleService
{
    protected array $entityMap = [
        'club' => Club::class,
        'player' => Player::class,
        'role' => Role::class,
        'matchday' => Matchday::class,
        'feeType' => FeeType::class,
        'competitionType' => CompetitionType::class,
        'transaction' => Transaction::class,
    ];

    /**
     * Create a new role
     *
     * @throws Throwable
     */
    public function create(array $data): Role
    {
        Log::info('Creating new role', [
            'club_id' => $data['club_id'],
            'name' => $data['name'],
        ]);

        return DB::transaction(function () use ($data) {
            /** @var Role $role */
            $role = Role::create([
                'name' => $data['name'],
                'is_base_fee_active' => $data['is_base_fee_active'],
                'club_id' => $data['club_id'],
                'guard_name' => 'web',
            ]);

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $this->syncRolePermissions($role, $data['permissions']);
            }

            return $role->fresh();
        });
    }

    /**
     * Update a role
     *
     * @throws Throwable
     */
    public function update(Role $role, array $data): Role
    {
        Log::info('Updating role', [
            'role_id' => $role->id,
            'club_id' => $data['club_id'],
            'name' => $data['name'],
        ]);

        return DB::transaction(function () use ($role, $data) {
            $role->update([
                'name' => $data['name'],
                'is_base_fee_active' => $data['is_base_fee_active'],
            ]);

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $this->syncRolePermissions($role, $data['permissions']);
            }

            return $role->refresh();
        });
    }

    /**
     * Delete a role
     *
     * @throws Exception
     */
    public function delete(Role $role): void
    {
        try {
            $role->delete();
        } catch (\Exception $exception) {
            Log::error('Error deleting role', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Get all roles for club id
     */
    public function getByClubId(int $clubId): Collection
    {
        return Role::where('club_id', $clubId)->get();
    }

    /**
     * Get permissions array of role for frontend
     */
    public function getPermissionsForFrontend(Role $role): array
    {
        $permissions = $role->permissions->pluck('name')->map(function ($permission) {
            $parts = explode('.', $permission);
            if (count($parts) !== 2) {
                return null;
            }
            [$action, $entity] = $parts;

            return [$entity => [$action => true]];
        })->filter()
            ->reduce(function ($result, $item) {
                foreach ($item as $entity => $actions) {
                    foreach ($actions as $action => $value) {
                        $result[$entity] = array_merge($result[$entity] ?? [], [$action => $value]);
                    }
                }

                return $result;
            }, []);

        //        $allEntities = config('permissions.entities');
        //        $allActions = config('permissions.actions');
        $allEntities = config('permissions');

        foreach ($allEntities as $entity => $actions) {
            if (! isset($permissions[$entity])) {
                $permissions[$entity] = [];
            }

            foreach ($actions as $action) {
                if (! isset($permissions[$entity][$action])) {
                    $permissions[$entity][$action] = false;
                }
            }
        }

        return $permissions;
    }

    /**
     * Sync the permissions of a role based on the given permission structure
     *
     * @throws Throwable
     */
    protected function syncRolePermissions(Role $role, array $permissions): void
    {
        try {
            $permissionsToSync = [];

            foreach ($permissions as $entity => $actions) {
                foreach ($actions as $action => $isGranted) {
                    if ($isGranted) {
                        $entityName = ucfirst($entity);
                        $permission = Permission::findOrCreate("{$action}.{$entityName}");
                        $permissionsToSync[] = $permission->name;
                    }
                }
            }

            $role->syncPermissions($permissionsToSync);

            Log::info('Role permissions synced', [
                'role_id' => $role->id,
                'permissions_count' => count($permissionsToSync),
            ]);
        } catch (\Exception $e) {
            Log::error('Error syncing role permissions', [
                'role_id' => $role->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }
}
