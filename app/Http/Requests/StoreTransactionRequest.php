<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use Silber\Bouncer\BouncerFacade;

class StoreTransactionRequest extends FormRequest
{
    protected $casts = [
        'type' => 'TransactionType',
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->club_id);

        return BouncerFacade::can('create', getClubScopedModel(Transaction::class, $this->club_id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => 'required|exists:clubs,id',
            'matchday_id' => 'nullable|exists:matchdays,id',
            'type' => 'required|in:1,2,3,4,5',
            'player_id' => 'exclude_if:type,5|required|exists:players,id',
            'amount' => 'required|numeric',
            'auto_tip' => 'nullable|boolean',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
