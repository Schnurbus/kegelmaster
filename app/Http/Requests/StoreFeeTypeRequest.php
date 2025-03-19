<?php

namespace App\Http\Requests;

use App\Models\FeeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class StoreFeeTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->club_id);
        return BouncerFacade::can('create', getClubScopedModel(FeeType::class, $this->club_id));
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
                Rule::unique('fee_types')->where('club_id', $this->club_id),
            ],
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'position' => 'required|integer|min:0',
            'club_id' => 'required|exists:clubs,id',
        ];
    }
}
