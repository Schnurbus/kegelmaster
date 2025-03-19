<?php

namespace App\Http\Controllers;

use App\Enums\PermissionType;
use App\Http\Requests\StorePlayerRequest;
use App\Http\Requests\UpdatePlayerRequest;
use App\Models\Player;
use App\Models\Role;
use App\Models\User;
use App\Services\PlayerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class PlayerController extends Controller
{
    use AuthorizesRequests;

    protected $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $club = session('currentClub');
        $user = User::findOrFail(Auth::user()->id);

        if (BouncerFacade::can('list', new Player(['club_id' => $club->id]))) {
            $players = $this->playerService->getPlayersWithPermissions($user, $club->id);
        }

        $roles = Role::where('name', '!=', 'owner')->get(['title'])->map(fn ($role) => [
            'value' => $role->title,
            'label' => $role->title,
        ]);

        return Inertia::render('players/index', [
            'players' => $players ?? [],
            'roles' => $roles ?? [],
            'can' => [
                // 'create' => BouncerFacade::can('create', Player::class),
                'create' => BouncerFacade::can('create', new Player(['club_id' => $club->id])),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $club = session('currentClub');
        // BouncerFacade::authorize(PermissionType::CREATE, new Player(['club_id' => $club->id]));
        BouncerFacade::authorize(PermissionType::CREATE, getClubScopedModel(Player::class, $club->id));

        return Inertia::render('players/create', [
            'roles' => Role::all(['id', 'name']),
            'club' => session('currentClub'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayerRequest $request)
    {
        $player = $this->playerService->createPlayer($request->validated());

        if ($player) {
            toast_success('Player created successfully');
        } else {
            toast_error('Could not create player');
        }

        return to_route('players.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        if (BouncerFacade::cannot('view', $player) && ! $player->user_id === Auth::user()->id) {
            abort(403);
        }

        $player->load(['user', 'role']);

        return Inertia::render('players/show', [
            'player' => $player,
            'statistics' => $this->playerService->getPlayerStatistics($player),
            'role' => $player->role,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        if (BouncerFacade::cannot('update', $player)) {
            abort(403);
        }

        return Inertia::render('players/edit', [
            'player' => $player->load('role'),
            'roles' => Role::where('name', '!=', 'owner')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlayerRequest $request, Player $player)
    {
        $validated = $request->validated();

        $updatedPlayer = $this->playerService->updatePlayer($player, $validated);
        if ($updatedPlayer) {
            toast_success('Player updated successfully');
        } else {
            toast_error('Could not update player.');

            return back();
        }
        // $player->update([
        //     'name' => $validated['name'],
        //     'sex' => $validated['sex'],
        //     'active' => $validated['active'],
        //     'role_id' => $validated['role_id'],

        // ]);

        // if ($player->user_id) {
        //     $role = $player->role;
        //     BouncerFacade::sync($player->user)->roles([$role]);
        // }

        return to_route('players.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        BouncerFacade::authorize('delete', $player);

        if ($player->user) {
            $player->user->retract($player->role);
        }

        $player->delete();

        return to_route('players.index');
    }
}
