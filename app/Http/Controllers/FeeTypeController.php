<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeeType\CreateFeeTypeRequest;
use App\Http\Requests\FeeType\DeleteFeeTypeRequest;
use App\Http\Requests\FeeType\EditFeeTypeRequest;
use App\Http\Requests\FeeType\IndexFeeTypeRequest;
use App\Http\Requests\FeeType\ShowFeeTypeRequest;
use App\Http\Requests\FeeType\StoreFeeTypeRequest;
use App\Http\Requests\FeeType\UpdateFeeTypeRequest;
use App\Models\FeeType;
use App\Services\FeeTypeService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class FeeTypeController extends Controller
{
    use AuthorizesRequests;

    private FeeTypeService $feeTypeService;

    public function __construct(FeeTypeService $feeTypeService)
    {
        $this->feeTypeService = $feeTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexFeeTypeRequest $request)
    {
        $currentClubId = session('current_club_id');

        return Inertia::render('fee-types/index', [
            'feeTypes' => fn () => $this->feeTypeService->getByClubId($currentClubId),
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(FeeType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(FeeType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(FeeType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(FeeType::class)),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateFeeTypeRequest $request)
    {
        return Inertia::render('fee-types/create', [
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(FeeType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(FeeType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(FeeType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(FeeType::class)),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTypeRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->feeTypeService->create($validated);
            toast_success('Fee type created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating fee type', ['error' => $exception->getMessage()]);
            toast_error('Cloud not create fee type');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('fee-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowFeeTypeRequest $request, FeeType $feeType): \Inertia\Response
    {
        return Inertia::render('fee-types/show', [
            'feeType' => $feeType,
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(FeeType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(FeeType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(FeeType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(FeeType::class)),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditFeeTypeRequest $request, FeeType $feeType): \Inertia\Response
    {
        return Inertia::render('fee-types/edit', [
            'feeType' => $feeType,
            'can' => [
                'create' => BouncerFacade::can('create', getClubScopedModel(FeeType::class)),
                'delete' => BouncerFacade::can('delete', getClubScopedModel(FeeType::class)),
                'update' => BouncerFacade::can('update', getClubScopedModel(FeeType::class)),
                'view' => BouncerFacade::can('view', getClubScopedModel(FeeType::class)),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeTypeRequest $request, FeeType $feeType): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->feeTypeService->update($feeType, $validated);
            toast_success('Fee type updated successfully');
        } catch (Exception $exception) {
            Log::error('Error updating fee type', ['error' => $exception->getMessage()]);
            toast_error('Cloud not update fee type');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('fee-type.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteFeeTypeRequest $request, FeeType $feeType): \Illuminate\Http\RedirectResponse
    {
        try {
            $feeType->delete();
            toast_success('Fee type deleted successfully');
        } catch (Exception $exception) {
            Log::error('Error deleting fee type', ['error' => $exception->getMessage()]);
            toast_error('Cloud not delete fee type');
        }

        return to_route('fee-type.index');
    }
}
