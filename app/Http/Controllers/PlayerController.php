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
use App\Services\PlayerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    public function index(IndexPlayerRequest $request): \Inertia\Response
    {
        $currentClubId = session('current_club_id');

        return Inertia::render('players/index', [
            'players' => fn () => $this->playerService->getByClubId($currentClubId),
            'roles' => fn () => Role::where('scope', $currentClubId)->get()->map(fn ($role) => [
                'value' => $role->title,
                'label' => $role->title,
            ]),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Player::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Player::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Player::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Player::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreatePlayerRequest $request): \Inertia\Response
    {
        return Inertia::render('players/create', [
            'roles' => Role::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayerRequest $request): \Illuminate\Http\RedirectResponse
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
    public function show(ShowPlayerRequest $request, Player $player): \Inertia\Response
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
    public function edit(EditPlayerRequest $request, Player $player): \Inertia\Response
    {
        return Inertia::render('players/edit', [
            'player' => $player->load('role'),
            'roles' => Role::all(),
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

        return to_route('players.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeletePlayerRequest $request, Player $player)
    {
        if ($player->user) {
            $player->user->retract($player->role);
        }

        $player->delete();

        return to_route('players.index');
    }
}
