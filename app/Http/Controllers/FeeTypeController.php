<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeeTypeRequest;
use App\Http\Requests\UpdateFeeTypeRequest;
use App\Models\FeeType;
use App\Models\FeeTypeVersion;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class FeeTypeController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $club = session('currentClub');
        $user = User::findOrFail(Auth::user()->id);

        if (BouncerFacade::can('list', new FeeType(['club_id' => $club->id]))) {
            $feeTypes = FeeType::where('club_id', $club->id)->orderBy('position', 'asc')->get()->map(fn($feeType) => [
                'id' => $feeType->id,
                'name' => $feeType->name,
                'description' => $feeType->description,
                'amount' => $feeType->amount,
                'position' => $feeType->position,
                'can' => [
                    'view' => BouncerFacade::can('view', $feeType),
                    'update' => BouncerFacade::can('update', $feeType),
                    'delete' => BouncerFacade::can('delete', $feeType),
                ],
            ]);
        }

        return Inertia::render('fee-types/index', [
            'feeTypes' => $feeTypes ?? [],
            'can' => [
                'create' => BouncerFacade::can('create', new FeeType(['club_id' => $club->id])),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $club = session('currentClub');
        BouncerFacade::authorize('create', new FeeType(['club_id' => $club->id]));

        return Inertia::render('fee-types/create', [
            'club' => session('currentClub'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeeTypeRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated) {
                $feeType = FeeType::create($validated);
                FeeTypeVersion::create([
                    'fee_type_id' => $feeType->id,
                    'name' => $feeType->name,
                    'description' => $feeType->description,
                    'amount' => $feeType->amount,
                ]);
            });
            toast_success('Fee type created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating fee type', ['error' => $exception->getMessage()]);
            toast_error('Cloud not create fee type');
        }

        return to_route('fee-type.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(FeeType $feeType)
    {
        BouncerFacade::authorize('view', $feeType);

        return Inertia::render('fee-types/show', [
            'feeType' => $feeType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FeeType $feeType)
    {
        BouncerFacade::authorize('update', $feeType);

        return Inertia::render('fee-types/edit', [
            'feeType' => $feeType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeeTypeRequest $request, FeeType $feeType)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $feeType) {
            if ($feeType->amount !== $validated['amount']) {
                FeeTypeVersion::create([
                    ...$validated,
                    'amount' => $validated['amount'],
                    'fee_type_id' => $feeType->id,
                ]);
            }

            $feeType->update([
                ...$validated,
                'amount' => $validated['amount'],
            ]);
        });

        return to_route('fee-type.index')->with('success', 'Fee Type updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FeeType $feeType)
    {
        $feeType->delete();

        return to_route('fee-type.index');
    }
}
