<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompetitionTypeRequest;
use App\Http\Requests\UpdateCompetitionTypeRequest;
use App\Models\CompetitionType;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class CompetitionTypeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $club = session('currentClub');
        $user = User::findOrFail(Auth::user()->id);

        if (BouncerFacade::can('list', getClubScopedModel(CompetitionType::class))) {
            $competitionTypes = CompetitionType::where('club_id', $club->id)->orderBy('position', 'asc')->get()->map(fn($competitionType) => [
                'id' => $competitionType->id,
                'name' => $competitionType->name,
                'type' => $competitionType->type,
                'is_sex_specific' => $competitionType->is_sex_specific,
                'description' => $competitionType->description,
                'position' => $competitionType->position,
                'can' => [
                    'view' => BouncerFacade::can('view', $competitionType),
                    'update' => BouncerFacade::can('update', $competitionType),
                    'delete' => BouncerFacade::can('delete', $competitionType),
                ],
            ]);
        }

        return Inertia::render('competition-types/index', [
            'competitionTypes' => $competitionTypes ?? [],
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(CompetitionType::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        BouncerFacade::authorize('create', getClubScopedModel(CompetitionType::class));

        return Inertia::render('competition-types/create', [
            'club' => session('currentClub'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetitionTypeRequest $request)
    {
        $validated = $request->validated();

        try {
            CompetitionType::create($validated);
            toast_success('Competition type created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating competition type', ['error' => $exception->getMessage()]);
            toast_error('Could not create competition type');
        }

        return to_route('competition-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompetitionType $competitionType)
    {
        BouncerFacade::authorize('view', $competitionType);

        return Inertia::render('competition-types/show', [
            'competitionType' => $competitionType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompetitionType $competitionType)
    {
        BouncerFacade::authorize('update', $competitionType);

        return Inertia::render('competition-types/edit', [
            'competitionType' => $competitionType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetitionTypeRequest $request, CompetitionType $competitionType)
    {
        BouncerFacade::authorize('update', $competitionType);

        try {
            $competitionType->update($request->validated());
            toast_success('Competition type updated successfully');
        } catch (Exception $exception) {
            Log::error('Error creating competition type', ['error' => $exception->getMessage()]);
            toast_error('Could not create competition type');
        }

        return to_route('competition-type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompetitionType $competitionType) {}
}
