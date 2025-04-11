<?php

namespace App\Http\Requests\CompetitionType;

use App\Models\CompetitionType;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompetitionTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', [CompetitionType::class, $this->input('club_id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('competition_types')
                    ->where('club_id', $this->input('club_id')),
            ],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'numeric', 'in:1,2,3'],
            'is_sex_specific' => ['required', 'boolean'],
            'position' => ['required', 'integer', 'min:0'],
            'club_id' => ['required', 'exists:clubs,id'],
        ];
    }
}
