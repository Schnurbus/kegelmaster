<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\Transaction\DeleteTransactionRequest;
use App\Http\Requests\Transaction\EditTransactionRequest;
use App\Http\Requests\Transaction\IndexTransactionRequest;
use App\Http\Requests\Transaction\ShowTransactionRequest;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Player;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTransactionRequest $request): Response
    {
        $user = $request->user();
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
            'can' => [
                'create' => $user->can('create', [Transaction::class, $currentClubId]),
                'view' => $user->can('view', Transaction::class),
                'update' => $user->can('update', Transaction::class),
                'delete' => $user->can('delete', Transaction::class),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateTransactionRequest $request): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        $players = Player::where('club_id', $currentClubId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('transactions/create', [
            'players' => $players,
            'can' => [
                'create' => $user->can('create', [Transaction::class, $currentClubId]),
                'view' => $user->can('view', Transaction::class),
                'update' => $user->can('update', Transaction::class),
                'delete' => $user->can('delete', Transaction::class),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $validated['type'] = $request->enum('type', TransactionType::class);
            $this->transactionService->createTransaction($validated);
            toast_success('Transaction created successfully');
        } catch (Throwable $exception) {
            Log::error('Error creating transaction', ['error' => $exception->getMessage()]);
            toast_error('Could not create transaction');

            return redirect()->back()->withInput($request->input());
        }

        return to_route('transactions.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ShowTransactionRequest $request, Transaction $transaction): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        $transaction->load(['player', 'feeEntry', 'feeEntry.feeTypeVersion', 'matchday']);

        return Inertia::render('transactions/show', [
            'transaction' => $transaction,
            'can' => [
                'create' => $user->can('create', [Transaction::class, $currentClubId]),
                'view' => $user->can('view', Transaction::class),
                'update' => $user->can('update', Transaction::class),
                'delete' => $user->can('delete', Transaction::class),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EditTransactionRequest $request, Transaction $transaction): Response
    {
        /** @var User $user */
        $user = $request->user();
        $currentClubId = session('current_club_id');

        $players = Player::where('club_id', $currentClubId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('transactions/edit', [
            'transaction' => $transaction,
            'players' => $players,
            'can' => [
                'create' => $user->can('create', [Transaction::class, $currentClubId]),
                'view' => $user->can('view', Transaction::class),
                'update' => $user->can('update', Transaction::class),
                'delete' => $user->can('delete', Transaction::class),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->transactionService->updateTransaction($transaction, $validated);
            toast_success('Transaction updated successfully');
        } catch (Throwable $exception) {
            Log::error('Error updating transaction', ['error' => $exception->getMessage()]);
            toast_error('Could not update transaction');

            return back();
        }

        return to_route('transactions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteTransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        try {
            $this->transactionService->deleteTransaction($transaction);
            toast_success('Transaction deleted successfully');
        } catch (Throwable $exception) {
            Log::error('Error deleting transaction', ['error' => $exception->getMessage()]);
            toast_error('Could not delete transaction');
        }

        return to_route('transactions.index');
    }
}
