<?php

namespace App\Http\Requests\Matchday;

use App\Models\Matchday;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddPlayerToMatchdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('update', $this->route('matchday'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        /** @var Matchday $matchday */
        $matchday = $this->route('matchday');

        return [
            'player_id' => [
                'required',
                'exists:players,id',
                Rule::unique('matchday_player', 'player_id')
                    ->where('matchday_id', $matchday->id),
            ],
        ];
    }
}
