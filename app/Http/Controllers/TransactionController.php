<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\IndexTransactionRequest;
use App\Http\Requests\Transaction\ShowTransactionRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Player;
use App\Models\Transaction;
use App\Services\TransactionService;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Silber\Bouncer\BouncerFacade;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        private TransactionService $transactionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTransactionRequest $request)
    {
        $currentClubId = session('current_club_id');

        $transactions = $this->transactionService->getByClubId($currentClubId);
        $transactions->load(['matchday', 'player', 'feeEntry.feeTypeVersion']);
        $players = $transactions
            ->pluck('player')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        return Inertia::render('transactions/index', [
            'transactions' => fn () => $transactions,
            'players' => fn () => $players,
            'can' => getUserPermissions(Transaction::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateTransactionRequest $request)
    {
        $currentClubId = session('current_club_id');

        $players = Player::where('club_id', $currentClubId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('transactions/create', [
            'players' => $players,
            'can' => getUserPermissions(Transaction::class),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $validated = $request->validated();

        try {
            $validated['type'] = $request->enum('type', TransactionType::class);
            $this->transactionService->createTransaction($validated);
            toast_success('Transaction created successfully');
        } catch (Exception $exception) {
            Log::error('Error creating transaction', ['error' => $exception->getMessage()]);
            toast_error('Could not create transaction');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowTransactionRequest $request, Transaction $transaction)
    {
        $transaction->load(['player', 'feeEntry', 'feeEntry.feeTypeVersion', 'matchday']);

        $transactionArray = [
            ...$transaction->toArray(),
            'can' => [
                'update' => BouncerFacade::can('update', $transaction),
                'delete' => BouncerFacade::can('delete', $transaction),
            ],
        ];

        return Inertia::render('transactions/show', [
            'transaction' => $transactionArray,
            'can' => getUserPermissions(Transaction::class),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        BouncerFacade::authorize('viewupdate', $transaction);

        $currentClub = session('currentClub');

        $players = Player::where('club_id', $currentClub->id)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('transactions/edit', [
            'transaction' => $transaction,
            'players' => $players,
            'can' => getUserPermissions(Transaction::class),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        try {
            $this->transactionService->updateTransaction($transaction, $validated);
            toast_success('Transaction updated successfully');
        } catch (Exception $exception) {
            Log::error('Error updating transaction', ['error' => $exception->getMessage()]);
            toast_error('Could not update transaction');

            return back();
        }

        return to_route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        BouncerFacade::scope()->to($transaction->club_id);
        BouncerFacade::authorize('delete', $transaction);

        $this->transactionService->deleteTransaction($transaction);

        return to_route('transactions.index')->with('success', 'Transaction deleted.');
    }
}
