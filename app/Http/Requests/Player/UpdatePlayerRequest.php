<?php

namespace App\Http\Requests\Player;

use App\Models\User;
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:players,name,'.$this->player->id.',id,club_id,'.$this->player->club_id,
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
