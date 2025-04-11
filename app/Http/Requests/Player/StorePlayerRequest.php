<?php

namespace App\Http\Requests\Player;

use App\Models\Player;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', [Player::class, $this->input('club_id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['required', 'exists:clubs,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:players,name,NULL,id,club_id,'.$this->input('club_id'),
            ],
            'sex' => ['required', 'integer', 'in:1,2'],
            'active' => ['nullable', 'boolean'],
            'initial_balance' => ['required', 'numeric'],
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')
                    ->where('club_id', $this->input('club_id')),
            ],
        ];
    }
}
