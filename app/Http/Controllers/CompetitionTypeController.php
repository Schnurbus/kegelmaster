<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompetitionType\CreateCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\DeleteCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\EditCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\IndexCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\ShowCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\StoreCompetitionTypeRequest;
use App\Http\Requests\CompetitionType\UpdateCompetitionTypeRequest;
use App\Models\CompetitionType;
use App\Services\CompetitionTypeService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Silber\Bouncer\BouncerFacade;

class CompetitionTypeController extends Controller
{
    use AuthorizesRequests;

    protected CompetitionTypeService $competitionTypeService;

    /**
     * Construct the class
     */
    public function __construct(CompetitionTypeService $competitionTypeService)
    {
        $this->competitionTypeService = $competitionTypeService;
    }

    /**
     * List the resources
     */
    public function index(IndexCompetitionTypeRequest $request): Response
    {
        $currentClubId = session('current_club_id');

        return Inertia::render('competition-types/index', [
            'competitionTypes' => $this->competitionTypeService->getByClubId($currentClubId),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(CompetitionType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(CompetitionType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(CompetitionType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(CompetitionType::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateCompetitionTypeRequest $request): Response
    {
        return Inertia::render('competition-types/create', [
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(CompetitionType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(CompetitionType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(CompetitionType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(CompetitionType::class)),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompetitionTypeRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->competitionTypeService->create($validated);
            toast_success('Competition type created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating competition type', ['error' => $exception->getMessage()]);
            toast_error('Could not create competition type');

            return redirect()->back()->withInput();
        }

        return to_route('competition-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowCompetitionTypeRequest $request, CompetitionType $competitionType): Response
    {
        return Inertia::render('competition-types/show', [
            'competitionType' => $competitionType,
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(CompetitionType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(CompetitionType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(CompetitionType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(CompetitionType::class)),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditCompetitionTypeRequest $request, CompetitionType $competitionType): Response
    {
        return Inertia::render('competition-types/edit', [
            'competitionType' => $competitionType,
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(CompetitionType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(CompetitionType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(CompetitionType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(CompetitionType::class)),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetitionTypeRequest $request, CompetitionType $competitionType): RedirectResponse
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
    public function destroy(DeleteCompetitionTypeRequest $request, CompetitionType $competitionType): RedirectResponse
    {
        try {
            $this->competitionTypeService->delete($competitionType);
            toast_success('Competition type deleted successfully');
        } catch (Exception $exception) {
            Log::error('Error deleting competition type', ['error' => $exception->getMessage()]);
            toast_error('Could not delete competition type');

            return redirect()->back();
        }

        return to_route('competition-type.index');
    }
}
