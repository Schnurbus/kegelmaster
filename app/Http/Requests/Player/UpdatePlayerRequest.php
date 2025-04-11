<?php

namespace App\Http\Requests\Player;

use App\Models\Player;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('update', $this->route('player'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var Player $player */
        $player = $this->route('player');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('players', 'name')
                    ->where('club_id', $player->club_id)
                    ->ignore($player->id),
            ],
            'sex' => ['required', 'integer', 'in:1,2'],
            'active' => 'boolean',
            'initial_balance' => ['required', 'numeric'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('club_id', $player->club_id),
            ],
        ];
    }
}
