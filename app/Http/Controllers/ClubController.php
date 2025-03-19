<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Services\ClubService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Silber\Bouncer\BouncerFacade;

class ClubController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected $clubService;

    public function __construct(ClubService $clubService)
    {
        $this->clubService = $clubService;
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $clubs = $this->clubService->getClubsWithPermissions($request->user());

        return inertia('clubs/index', [
            'clubs' => $clubs,
            'can' => [
                'list' => BouncerFacade::can('list', Club::class),
                'create' => BouncerFacade::can('create', Club::class),
            ],
        ]);
    }

    public function create()
    {
        return inertia('clubs/create');
    }

    public function store(\App\Http\Requests\StoreClubRequest $request)
    {
        $validated = $request->validated();

        try {
            $club = $this->clubService->createClub($request->user(), $validated);

            $userClubs = $this->clubService->getUserClubs($request->user());

            if (count($userClubs) === 1) {
                session(['currentClub' => $club]);
            }
            toast_success('New club created successfully');
        } catch (Exception $exception) {
            toast_error($exception->getMessage());
        }

        return to_route('club.index');
    }

    public function show(Club $club)
    {
        $showClub = $this->clubService->getClubInfo($club);

        return inertia('clubs/show', [
            'club' => $showClub,
        ]);
    }

    public function edit(Club $club)
    {
        $editClub = $this->clubService->getClubInfo($club, 'update');

        return inertia('clubs/edit', [
            'club' => $editClub,
        ]);
    }

    public function update(\App\Http\Requests\UpdateClubRequest $request, Club $club)
    {
        $validated = $request->validated();

        Log::info('Updating club {club}', ['club' => $club]);

        $club->update($validated);

        return to_route('club.index')->with('success', 'Club updated successfully.');
    }

    public function destroy(Club $club)
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
