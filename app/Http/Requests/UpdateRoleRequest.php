<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $role = new Role();
        // $role->scope = $this->club_id;
        // dd($this->role);
        BouncerFacade::scope()->to($this->role->scope);

        return BouncerFacade::can('update', $this->role);
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
                'max:255',
                Rule::unique('roles')->where('scope', $this->club_id)->ignore($this->role->id),
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
