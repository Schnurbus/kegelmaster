<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPlayerToMatchdayRequest;
use App\Http\Requests\RemovePlayerFromMatchdayRequest;
use App\Http\Requests\StoreMatchdayRequest;
use App\Http\Requests\UpdateMatchdayRequest;
use App\Models\CompetitionEntry;
use App\Models\CompetitionType;
use App\Models\FeeEntry;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Models\User;
use App\Services\MatchdayService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class MatchdayController extends Controller
{
    use AuthorizesRequests;

    protected $matchdayService;

    public function __construct(MatchdayService $matchdayService)
    {
        $this->matchdayService = $matchdayService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $club = session('currentClub');
        $user = User::findOrFail(Auth::user()->id);

        if ($user->can('list', getClubScopedModel(Matchday::class))) {
            $matchdays = $this->matchdayService->getMatchdaysWithPermissions($user, $club->id);
        }

        return Inertia::render('matchdays/index', [
            'matchdays' => $matchdays ?? [],
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Matchday::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $club = session('currentClub');
        BouncerFacade::authorize('create', getClubScopedModel(Matchday::class));

        return Inertia::render('matchdays/create', [
            'club' => $club,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatchdayRequest $request)
    {
        $validated = $request->validated();

        try {
            $matchday = $this->matchdayService->createMatchday($validated);
            toast_success('Matchday created successfully');
        } catch (Exception $execption) {
            toast_error('Could not create matchday');
            Log::error('Error creating matchday', ['error' => $execption->getMessage()]);
            redirect()->back();
        }

        return to_route('matchdays.edit', $matchday);
    }

    /**
     * Display the specified resource.
     */
    public function show(Matchday $matchday)
    {
        BouncerFacade::authorize('view', $matchday);

        $feeEntries = FeeEntry::where('matchday_id', $matchday->id)
            ->with(['feeTypeVersion:id,fee_type_id'])
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'fee_type_version_id']);

        $players = Player::whereHas('feeEntries', function ($query) use ($matchday) {
            $query->where('matchday_id', $matchday->id);
        })->get();

        $notAttachedPlayers = Player::where('club_id', $matchday->club_id)
            ->whereDoesntHave('feeEntries', function ($query) use ($matchday) {
                $query->where('matchday_id', $matchday->id);
            })
            ->get();

        $feeTypes = FeeType::where('club_id', $matchday->club_id)->orderBy('position')->with('latestVersion')->get();

        $competitionTypes = CompetitionType::where('club_id', $matchday->club_id)->orderBy('position')->get();
        $competitionEntries = CompetitionEntry::where('matchday_id', $matchday->id)
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'competition_type_id']);

        return Inertia::render('matchdays/show', [
            'club' => session('currentClub'),
            'matchday' => $matchday,
            'players' => $players,
            'notAttachedPlayers' => $notAttachedPlayers,
            'feeTypes' => $feeTypes,
            'feeEntries' => $feeEntries,
            'competitionTypes' => $competitionTypes,
            'competitionEntries' => $competitionEntries,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matchday $matchday)
    {
        BouncerFacade::authorize('update', $matchday);

        $feeEntries = FeeEntry::where('matchday_id', $matchday->id)
            ->with(['feeTypeVersion:id,fee_type_id'])
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'fee_type_version_id']);

        $players = $matchday->players->toArray();
        $notAttachedPlayers = Player::where('club_id', $matchday->club_id)
            ->whereNotIn('id', function ($query) use ($matchday) {
                $query->select('player_id')
                    ->from('matchday_player')
                    ->where('matchday_id', $matchday->id);
            })
            ->get();

        $feeTypes = FeeType::where('club_id', $matchday->club_id)->orderBy('position')->with('latestVersion')->get();

        $competitionTypes = CompetitionType::where('club_id', $matchday->club_id)->orderBy('position')->get();
        $competitionEntries = CompetitionEntry::where('matchday_id', $matchday->id)
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'competition_type_id']);

        return Inertia::render('matchdays/edit', [
            'club' => session('currentClub'),
            'matchday' => $matchday,
            'players' => $players,
            'notAttachedPlayers' => $notAttachedPlayers,
            'feeTypes' => $feeTypes,
            'feeEntries' => $feeEntries,
            'competitionTypes' => $competitionTypes,
            'competitionEntries' => $competitionEntries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatchdayRequest $request, Matchday $matchday)
    {
        $validated = $request->validated();

        try {
            $matchday->update($validated);
            toast_success('Matchday updated successfully');
        } catch (Exception $execption) {
            toast_error('Could not update matchday');
            Log::error('Error updating matchday', ['error' => $execption->getMessage()]);
            redirect()->back();
        }

        return to_route('matchdays.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matchday $matchday)
    {
        BouncerFacade::authorize('delete', $matchday);

        if ($this->matchdayService->deleteMatchday($matchday)) {
            toast_success('Matchday deleted successfully');
        } else {
            toast_error('Could not delete matchday');
        }

        return to_route('matchdays.index');
    }

    public function addPlayer(AddPlayerToMatchdayRequest $request, Matchday $matchday)
    {
        $validated = $request->validated();

        $this->matchdayService->addPlayerToMatchday($matchday, $validated['player_id']);

        return back()->with('success', 'Player added to matchday');
    }

    public function removePlayer(RemovePlayerFromMatchdayRequest $request, Matchday $matchday)
    {
        $validated = $request->validated();

        $this->matchdayService->removePlayerFromMatchday($matchday, $validated['player_id']);

        return back()->with('success', 'Player removed from matchday');
    }
}
