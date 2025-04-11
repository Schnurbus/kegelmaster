<?php

namespace App\Http\Requests\Transaction;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('update', $this->route('transaction'));

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'matchday_id' => ['nullable', 'exists:matchdays,id'],
            'player_id' => ['nullable', 'exists:players,id'],
            'type' => ['required', 'in:1,2,3,4,5'],
            'amount' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
