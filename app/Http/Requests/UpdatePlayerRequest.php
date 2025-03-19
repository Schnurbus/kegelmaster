<?php

namespace App\Http\Requests;

use App\Models\Player;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class UpdatePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->player->club_id);
        return BouncerFacade::can('update', $this->player);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:players,name,' . $this->player->id . ',id,club_id,' . $this->player->club_id,
            'sex' => 'required|integer|in:1,2',
            'active' => 'boolean',
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
