<?php

namespace App\Http\Controllers;

use App\Http\Requests\Club\EditClubRequest;
use App\Http\Requests\Club\IndexClubRequest;
use App\Http\Requests\Club\ShowClubRequest;
use App\Models\Club;
use App\Models\User;
use App\Services\ClubService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ClubController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    public function index(IndexClubRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $clubs = $this->clubService->getClubsWithPermissions($user);

        return Inertia::render('clubs/index', [
            'clubs' => $clubs,
            'can' => [
                'list' => true,
                'create' => true,
            ],
            //            'can' => [
            //                'list' => BouncerFacade::can('list', Club::class),
            //                'create' => BouncerFacade::can('create', Club::class),
            //            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('clubs/create');
    }

    public function store(\App\Http\Requests\Club\StoreClubRequest $request): \Illuminate\Http\RedirectResponse
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

    public function show(ShowClubRequest $request, Club $club): Response
    {
        // $showClub = $this->clubService->getClubInfo($club);
        $club
            ->load('owner:id,name')
            ->loadCount('players');

        return Inertia::render('clubs/show', [
            'club' => $club,
        ]);
    }

    public function edit(EditClubRequest $request, Club $club): Response
    {
        // $editClub = $this->clubService->getClubInfo($club, 'update');
        $club
            ->load('owner:id,name')
            ->loadCount('players');

        return Inertia::render('clubs/edit', [
            'club' => $club,
        ]);
    }

    public function update(\App\Http\Requests\Club\UpdateClubRequest $request, Club $club): \Illuminate\Http\RedirectResponse
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
