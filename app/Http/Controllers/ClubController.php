<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use App\Services\ClubService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class ClubController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    public function index(\Illuminate\Http\Request $request): \Inertia\Response
    {
        /** @var User $user */
        $user = $request->user();
        $clubs = $this->clubService->getClubsWithPermissions($user);

        return Inertia::render('clubs/index', [
            'clubs' => $clubs,
            'can' => [
                'list' => BouncerFacade::can('list', Club::class),
                'create' => BouncerFacade::can('create', Club::class),
            ],
        ]);
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('clubs/create');
    }

    public function store(\App\Http\Requests\StoreClubRequest $request): \Illuminate\Http\RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        try {
            $club = $this->clubService->createClub($user, $validated);

            $userClubs = $this->clubService->getUserClubs($user);

            if (count($userClubs) === 1) {
                session(['current_club_id' => $club->id]);
                session(['currentClub' => $club]);
            }
            toast_success('New club created successfully');
        } catch (Exception $exception) {
            toast_error($exception->getMessage());

            return redirect()->back()->withInput();
        }

        return to_route('club.index');
    }

    public function show(Club $club): \Inertia\Response
    {
        // $showClub = $this->clubService->getClubInfo($club);
        $club
            ->load('owner:id,name')
            ->loadCount('players');

        return Inertia::render('clubs/show', [
            'club' => $club,
        ]);
    }

    public function edit(Club $club): \Inertia\Response
    {
        // $editClub = $this->clubService->getClubInfo($club, 'update');
        $club
            ->load('owner:id,name')
            ->loadCount('players');

        return Inertia::render('clubs/edit', [
            'club' => $club,
        ]);
    }

    public function update(\App\Http\Requests\UpdateClubRequest $request, Club $club): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $club->update($validated);
            toast_success('Club updated successfully');
        } catch (Exception $exception) {
            toast_error($exception->getMessage());

            return redirect()->back()->withInput();
        }

        return to_route('club.index');
    }

    public function destroy(Club $club): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->clubService->deleteClub(Auth::user(), $club);
            toast_success('Club deleted successfully');
        } catch (Exception $exception) {
            toast_error($exception->getMessage());
        }

        return to_route('club.index');
    }
}
