<?php

namespace App\Http\Requests;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->club_id);

        return BouncerFacade::can('create', getClubScopedModel(Player::class, $this->club_id));
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
            'name' => 'required|string|max:255|unique:players,name,NULL,id,club_id,'.$this->club_id,
            'sex' => 'required|integer|in:1,2',
            'active' => 'nullable|boolean',
            'initial_balance' => 'required|numeric',
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('scope', $this->club_id)
                    ->whereNot('name', 'owner'),
            ],
        ];
    }
}
