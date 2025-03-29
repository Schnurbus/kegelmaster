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
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

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
     * @throws Exception
     */
    public function create(array $data): Role
    {
        try {
            BouncerFacade::scope()->to($data['club_id']);
            /** @var Role $role */
            $role = BouncerFacade::role()->firstOrCreate([
                'name' => $data['name'],
                'is_base_fee_active' => $data['is_base_fee_active'],
            ]);

            foreach ($data['permissions'] as $entity => $actions) {
                if (! isset($this->entityMap[$entity])) {
                    continue;
                }

                $modelClass = $this->entityMap[$entity];
                foreach ($actions as $action => $allowed) {
                    if ($allowed) {
                        BouncerFacade::allow($role)->to($action, $modelClass);
                    }
                }
            }

            return $role;
        } catch (\Exception $exception) {
            Log::error('Error creating role', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Update a role
     *
     * @throws Exception
     */
    public function update(Role $role, array $data): Role
    {
        try {
            BouncerFacade::scope()->to($role->id);

            $role->update([
                'name' => $data['name'],
                'title' => $data['name'],
                'is_base_fee_active' => $data['is_base_fee_active'],
            ]);

            foreach ($this->entityMap as $key => $modelClass) {
                BouncerFacade::disallow($role)->to(['list', 'view', 'create', 'update', 'delete'], $modelClass);
            }

            // Neue Berechtigungen setzen
            foreach ($data['permissions'] as $entity => $actions) {
                if (! isset($this->entityMap[$entity])) {
                    continue;
                }

                $modelClass = $this->entityMap[$entity];
                foreach ($actions as $action => $allowed) {
                    if ($allowed) {
                        BouncerFacade::allow($role)->to($action, $modelClass);
                    }
                }
            }
            BouncerFacade::refresh();

            return $role;
        } catch (\Exception $exception) {
            Log::error('Error updating role', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * Delete a role
     *
     * @throws Exception
     */
    public function delete(Role $role): void
    {
        try {
            BouncerFacade::scope()->to($role->id);
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
        BouncerFacade::scope()->to($clubId);

        return Role::all();
    }
}
