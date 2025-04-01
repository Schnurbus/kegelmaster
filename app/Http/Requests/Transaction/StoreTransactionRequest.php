<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', getClubScopedModel(Transaction::class, $this->input('club_id')));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['required', 'integer', 'exists:clubs,id'],
            'matchday_id' => ['sometimes', 'integer', 'exists:clubs,id'],
            'type' => ['required', 'in:1,2,3,4,5'],
            // 'player_id' => 'exclude_if:type,5|required|exists:players,id',
            'player_id' => [
                Rule::excludeIf($this->input('type') === 5),
                'required',
                Rule::when(
                    $this->input('type') === 3,
                    ['required', 'array']
                ),
                Rule::when(
                    $this->input('type') !== 3,
                    ['required', 'integer', 'exists:players,id']
                ),
            ],
            'player_id.*' => ['sometimes', 'integer', 'exists:players,id'],
            'amount' => ['required', 'numeric'],
            'auto_tip' => ['nullable', 'boolean'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
