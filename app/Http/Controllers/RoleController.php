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
use App\Models\User;
use App\Services\RoleService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

use function Laravel\Prompts\error;

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
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('role/index', [
            'roles' => $this->roleService->getByClubId($currentClubId),
            'can' => [
                'create' => $user->can('create', [Role::class, $currentClubId]),
                'delete' => $user->can('delete', Role::class),
                'update' => $user->can('update', Role::class),
                'view' => $user->can('view', Role::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateRoleRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('role/create', [
            'permissions' => $this->getPermissions(),
            'can' => [
                'create' => $user->can('create', [Role::class, $currentClubId]),
                'delete' => $user->can('delete', Role::class),
                'update' => $user->can('update', Role::class),
                'view' => $user->can('view', Role::class),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->roleService->create($validated);
            toast_success('Role created successfully');
        } catch (\Throwable $exception) {
            Log:error($exception);
            toast_error('Cloud not create role');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('role.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRoleRequest $request, Role $role): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('role/show', [
            'role' => $role,
            'role.permissions' => $this->roleService->getPermissionsForFrontend($role),
            'can' => [
                'create' => $user->can('create', [Role::class, $currentClubId]),
                'delete' => $user->can('delete', Role::class),
                'update' => $user->can('update', Role::class),
                'view' => $user->can('view', Role::class),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditRoleRequest $request, Role $role): Response
    {
        /** @var User $user */
        $user = auth()->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('role/edit', [
            'role' => $role,
            'role.permissions' => fn () => $this->roleService->getPermissionsForFrontend($role),
            'permissions' => $this->getPermissions(),
            'can' => [
                'create' => $user->can('create', [Role::class, $currentClubId]),
                'delete' => $user->can('delete', Role::class),
                'update' => $user->can('update', Role::class),
                'view' => $user->can('view', Role::class),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->roleService->update($role, $validated);
            toast_success('Role updated successfully');
        } catch (\Throwable $e) {
            toast_error('Could not update role');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteRoleRequest $request, Role $role): RedirectResponse
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
}
