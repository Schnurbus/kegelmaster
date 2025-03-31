<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matchday\AddPlayerToMatchdayRequest;
use App\Http\Requests\Matchday\CreateMatchdayRequest;
use App\Http\Requests\Matchday\DeleteMatchdayRequest;
use App\Http\Requests\Matchday\EditMatchdayRequest;
use App\Http\Requests\Matchday\IndexMatchdayRequest;
use App\Http\Requests\Matchday\RemovePlayerFromMatchdayRequest;
use App\Http\Requests\Matchday\ShowMatchdayRequest;
use App\Http\Requests\Matchday\StoreMatchdayRequest;
use App\Http\Requests\Matchday\UpdateMatchdayRequest;
use App\Models\CompetitionEntry;
use App\Models\CompetitionType;
use App\Models\FeeEntry;
use App\Models\FeeType;
use App\Models\Matchday;
use App\Models\Player;
use App\Services\FeeTypeService;
use App\Services\MatchdayService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class MatchdayController extends Controller
{
    use AuthorizesRequests;

    protected $matchdayService;

    protected $feeTypeService;

    public function __construct(MatchdayService $matchdayService, FeeTypeService $feeTypeService)
    {
        $this->matchdayService = $matchdayService;
        $this->feeTypeService = $feeTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexMatchdayRequest $request): \Inertia\Response
    {
        $currentClubId = session('current_club_id');

        return Inertia::render('matchdays/index', [
            'matchdays' => fn () => $this->matchdayService->getByClubId($currentClubId),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(Matchday::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(Matchday::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(Matchday::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(Matchday::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateMatchdayRequest $request): \Inertia\Response
    {
        return Inertia::render('matchdays/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMatchdayRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $matchday = $this->matchdayService->createMatchday($validated);
            toast_success('Matchday created successfully');

            return to_route('matchdays.edit', $matchday);
        } catch (Exception $execption) {
            toast_error('Could not create matchday');

            return redirect()->back()->withInput($request->input());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowMatchdayRequest $request, Matchday $matchday): \Inertia\Response
    {
        $feeEntries = FeeEntry::where('matchday_id', $matchday->id)
            ->with(['feeTypeVersion:id,fee_type_id'])
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'fee_type_version_id']);

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
            'club' => $matchday->club,
            'matchday' => $matchday,
            'players' => fn () => $matchday->players()->orderByPivot('created_at')->get()->toArray(),
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
    public function edit(EditMatchdayRequest $request, Matchday $matchday): \Inertia\Response
    {
        $notAttachedPlayers = Player::where('club_id', $matchday->club_id)
            ->whereNotIn('id', function ($query) use ($matchday) {
                $query->select('player_id')
                    ->from('matchday_player')
                    ->where('matchday_id', $matchday->id);
            })
            ->get();

        $competitionTypes = CompetitionType::where('club_id', $matchday->club_id)->orderBy('position')->get();
        $competitionEntries = CompetitionEntry::where('matchday_id', $matchday->id)
            ->get(['id', 'matchday_id', 'amount', 'player_id', 'competition_type_id']);

        return Inertia::render('matchdays/edit', [
            'club' => $matchday->club,
            'matchday' => $matchday,
            'players' => fn () => $matchday->players()->orderByPivot('created_at')->get()->toArray(),
            'notAttachedPlayers' => $notAttachedPlayers,
            'feeTypes' => fn () => $this->feeTypeService->getFeeTypesForMatchday($matchday)->toArray(),
            // 'feeEntries' => $feeEntries,
            'feeEntries' => $matchday->feeEntries->toArray(),
            'competitionTypes' => $competitionTypes,
            'competitionEntries' => $competitionEntries,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMatchdayRequest $request, Matchday $matchday): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $matchday->update($validated);
            toast_success('Matchday updated successfully');
        } catch (Exception $execption) {
            toast_error('Could not update matchday');
            Log::error('Error updating matchday', ['error' => $execption->getMessage()]);

            return redirect()->back()->withInput($request->input());
        }

        return to_route('matchdays.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteMatchdayRequest $request, Matchday $matchday)
    {
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
