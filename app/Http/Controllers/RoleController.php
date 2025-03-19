<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Club;
use App\Models\CompetitionType;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class RoleController extends Controller
{
    use AuthorizesRequests;

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
     * Display a listing of the resource.
     */
    public function index()
    {
        $club = session('currentClub');
        $user = User::findOrFail(Auth::user()->id);
        BouncerFacade::scope()->to($club->id);

        $dummyRole = new Role();
        $dummyRole->scope = $club->id;

        if (BouncerFacade::can('list', $dummyRole)) {
            $roles = Role::where('scope', $club->id)
                // ->where('name', '!=', 'owner')
                ->get()
                ->map(fn($role) => [
                    'id' => $role->id,
                    'is_base_fee_active' => $role->is_base_fee_active,
                    'name' => $role->name,
                    'can' => [
                        'view' => BouncerFacade::can('view', $role),
                        'update' => BouncerFacade::can('update', $role),
                        'delete' => BouncerFacade::can('delete', $role),
                    ],
                ]);
        }

        return inertia('role/index', [
            'roles' => $roles ?? [],
            'can' => [
                'create' => BouncerFacade::can('create', $dummyRole),
            ],

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $club = session('currentClub');
        $dummyRole = new Role();
        $dummyRole->scope = $club->id;

        BouncerFacade::authorize('create', $dummyRole);

        // $club = Club::find(session('currentClub'))->first();

        // if (! $club) {
        //     abort(404);
        // }

        return inertia('role/create', [
            'club' => $club,
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            $role = BouncerFacade::role()->firstOrCreate([
                'name' => $validated['name'],
                'is_base_fee_active' => $validated['is_base_fee_active'],
            ]);

            foreach ($validated['permissions'] as $entity => $actions) {
                if (! isset($this->entityMap[$entity])) {
                    continue; // Falls eine unbekannte Entität dabei ist -> überspringen
                }

                $modelClass = $this->entityMap[$entity];

                foreach ($actions as $action => $allowed) {
                    if ($allowed) {
                        BouncerFacade::allow($role)->to($action, $modelClass);
                    }
                }
            }
            toast_success('Role created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating role', ['error' => $exception->getMessage()]);
            toast_error('Cloud not create role');
        }

        return to_route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        if (BouncerFacade::cannot('view', $role)) {
            abort(403);
        }

        return inertia('role/show', [
            'role' => $role,
            'role.permissions' => $this->loadPermissions($role),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if (BouncerFacade::cannot('update', $role)) {
            abort(403);
        }

        return inertia('role/edit', [
            'role' => $role,
            'role.permissions' => $this->loadPermissions($role),
            'permissions' => $this->getPermissions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $validated = $request->validated();

        try {
            $role->update([
                'name' => $validated['name'],
                'title' => $validated['name'],
                'is_base_fee_active' => $validated['is_base_fee_active'],
            ]);

            // Vorhandene Berechtigungen entfernen
            foreach ($this->entityMap as $key => $modelClass) {
                BouncerFacade::disallow($role)->to(['list', 'view', 'create', 'update', 'delete'], $modelClass);
            }

            // Neue Berechtigungen setzen
            foreach ($request->permissions as $entity => $actions) {
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
            toast_success('Role updated successfully');
        } catch (Exception $exception) {
            Log::error('Error updating role', ['error' => $exception->getMessage()]);
            toast_error('Could not update role');
        }

        return to_route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (BouncerFacade::cannot('delete', $role)) {
            abort(403);
        }

        try {
            $role->delete();
            toast_success('Role deleted successfully');
        } catch (Exception $exception) {
            Log::error('Error deleting role', ['error' => $exception->getMessage()]);
            toast_error('Could not delete role');
        }

        return to_route('role.index')->with('success', 'Role deleted successfully.');
    }

    private function getPermissions(): array
    {
        return [
            'club' => ['view', 'update'],
            'player' => ['list', 'view', 'create', 'update', 'delete'],
            'role' => ['list', 'view', 'create', 'update', 'delete'],
            'matchday' => ['list', 'view', 'create', 'update', 'delete'],
            'feeType' => ['list', 'view', 'create', 'update', 'delete'],
            'competitionType' => ['list', 'view', 'create', 'update', 'delete'],
            'transaction' => ['list', 'view', 'create', 'update', 'delete'],
        ];
    }

    private function loadPermissions(?Role $role): array
    {
        $permissions = [];
        foreach ($this->entityMap as $key => $modelClass) {
            $permissions[$key] = [
                'list' => $role->can('list', $modelClass),
                'view' => $role->can('view', $modelClass),
                'create' => $role->can('create', $modelClass),
                'update' => $role->can('update', $modelClass),
                'delete' => $role->can('delete', $modelClass),
            ];
        }

        return $permissions;
    }
}
