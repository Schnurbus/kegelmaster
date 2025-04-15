<?php

namespace App\Http\Controllers;

use App\Http\Requests\Player\CreatePlayerRequest;
use App\Http\Requests\Player\DeletePlayerRequest;
use App\Http\Requests\Player\EditPlayerRequest;
use App\Http\Requests\Player\IndexPlayerRequest;
use App\Http\Requests\Player\ShowPlayerRequest;
use App\Http\Requests\Player\StorePlayerRequest;
use App\Http\Requests\Player\UpdatePlayerRequest;
use App\Models\Player;
use App\Models\Role;
use App\Models\User;
use App\Services\PlayerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

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
    public function index(IndexPlayerRequest $request): Response
    {
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('players/index', [
            'players' => fn () => $this->playerService->getByClubId($currentClubId),
            'roles' => fn () => Role::where('club_id', $currentClubId)->get()->map(fn ($role) => [
                'value' => $role->name,
                'label' => $role->name,
            ]),
            'can' => [
                'create' => $user->can('create', [Player::class, $currentClubId]),
                'view' => $user->can('view', Player::class),
                'update' => $user->can('update', Player::class),
                'delete' => $user->can('delete', Player::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePlayerRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('players/create', [
            'roles' => Role::where('club_id', $currentClubId)->get(),
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
    public function store(StorePlayerRequest $request): RedirectResponse
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
    public function show(ShowPlayerRequest $request, Player $player): Response
    {
        $player->load(['user', 'role']);

        return Inertia::render('players/show', [
            'player' => $player,
            'feeEntries' => $this->playerService->getFeeStatistics($player)->toArray(),
            'competitionEntries' => $this->playerService->getCompetitionStatistics($player)->toArray(),
            'transactions' => $this->playerService->getTransactionStatistics($player)->toArray(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditPlayerRequest $request, Player $player): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        return Inertia::render('players/edit', [
            'player' => $player->load('role'),
            'roles' => Role::where('club_id', $currentClubId)->get(),
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
    public function update(UpdatePlayerRequest $request, Player $player): RedirectResponse
    {
        $validated = $request->validated();

        $updatedPlayer = $this->playerService->updatePlayer($player, $validated);
        if ($updatedPlayer) {
            toast_success('Player updated successfully');
        } else {
            toast_error('Could not update player.');

            return back();
        }

        return to_route('players.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletePlayerRequest $request, Player $player): RedirectResponse
    {
        try {
            if ($player->user) {
                $player->user->removeRole($player->role);
            }
            $player->delete();

            toast_success('Player deleted successfully');
        } catch (\Exception $e) {
            toast_error('Could not delete player');
        }

        return to_route('players.index');
    }
}
