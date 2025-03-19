<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class UpdateCompetitionTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->competition_type->club_id);
        return BouncerFacade::can('update', $this->competition_type);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('competition_types')
                    ->where('club_id', $this->club_id)
                    ->ignore($this->route('competition_type')->id),
            ],
            'description' => 'nullable|string',
            'type' => 'required|numeric|in:1,2,3',
            'is_sex_specific' => 'required|boolean',
            'position' => 'required|integer|min:0',
            'club_id' => 'required|exists:clubs,id',
        ];
    }
}
