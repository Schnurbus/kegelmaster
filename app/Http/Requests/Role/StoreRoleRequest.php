<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', [Role::class, $this->input('club_id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => ['required', 'exists:clubs,id'],
            'name' => [
                'required',
                'string',
                'not_regex:/\./',
                'max:255',
                Rule::unique('roles')->where('club_id', $this->club_id),
            ],
            'is_base_fee_active' => ['required', 'boolean'],

            // Die erste Ebene (Hauptkategorien wie 'player', 'role', etc.)
            'permissions' => ['required', 'array'],

            // Die zweite Ebene (die einzelnen Berechtigungen)
            'permissions.*' => ['array'], // Muss ein Array sein
            'permissions.*.*' => ['boolean'], // Jedes Element muss true/false sein
        ];
    }
}
