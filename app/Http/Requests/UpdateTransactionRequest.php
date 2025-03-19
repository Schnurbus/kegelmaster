<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Silber\Bouncer\BouncerFacade;

class UpdateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->club_id);

        return BouncerFacade::can('update', $this->transaction);
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
            'player_id' => 'nullable|exists:players,id',
            'type' => 'required|in:1,2,3,4,5',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
