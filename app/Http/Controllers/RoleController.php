<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\DeleteRoleRequest;
use App\Http\Requests\Role\EditRoleRequest;
use App\Http\Requests\Role\IndexRoleRequest;
use App\Http\Requests\Role\ShowRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Models\Club;
use App\Models\CompetitionType;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\Role;
use App\Models\Transaction;
use App\Services\RoleService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class RoleController extends Controller
{
    use AuthorizesRequests;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

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
    public function index(IndexRoleRequest $request)
    {
        $currentClubId = session('current_club_id');

        return Inertia::render('role/index', [
            'roles' => $this->roleService->getByClubId($currentClubId),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Role::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Role::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Role::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Role::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRoleRequest $request)
    {
        return Inertia::render('role/create', [
            'permissions' => $this->getPermissions(),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Role::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Role::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Role::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Role::class)),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $validated = $request->validated();

        try {
            $this->roleService->create($validated);
            toast_success('Role created successfully');
        } catch (Exception $exception) {
            toast_error('Cloud not create role');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRoleRequest $request, Role $role)
    {
        return Inertia::render('role/show', [
            'role' => $role,
            'role.permissions' => $this->loadPermissions($role),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Role::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Role::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Role::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Role::class)),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditRoleRequest $request, Role $role)
    {
        return Inertia::render('role/edit', [
            'role' => $role,
            'role.permissions' => $this->loadPermissions($role),
            'permissions' => $this->getPermissions(),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Role::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Role::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Role::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Role::class)),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->roleService->update($role, $validated);
            toast_success('Role updated successfully');
        } catch (Exception $exception) {
            toast_error('Could not update role');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRoleRequest $request, Role $role): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->roleService->delete($role);
            toast_success('Role deleted successfully');
        } catch (Exception $exception) {
            toast_error('Could not delete role');

            return redirect()->back();
        }

        return to_route('role.index');
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
