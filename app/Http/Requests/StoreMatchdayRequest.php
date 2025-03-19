<?php

namespace App\Http\Requests;

use App\Models\Matchday;
use Illuminate\Foundation\Http\FormRequest;
use Silber\Bouncer\BouncerFacade;

class StoreMatchdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->club_id);

        return BouncerFacade::can('create', getClubScopedModel(Matchday::class, $this->club_id));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'club_id' => 'required|exists:clubs,id',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
