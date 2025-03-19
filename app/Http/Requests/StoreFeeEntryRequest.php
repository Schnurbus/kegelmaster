<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Silber\Bouncer\BouncerFacade;

class StoreFeeEntryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        BouncerFacade::scope()->to($this->matchday->club_id);

        return BouncerFacade::can('update', $this->matchday);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entries' => 'required|array',
            'entries.*.id' => 'exists:fee_entries,id',
            'entries.*.amount' => 'numeric|min:0',
            'entries.*.player_id' => 'required|exists:players,id',
            'entries.*.matchday_id' => 'required|exists:matchdays,id',
            'entries.*.fee_type_version_id' => 'required|exists:fee_type_versions,id',
        ];
    }
}
