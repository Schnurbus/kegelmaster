<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreFeeEntryRequest extends FormRequest
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
        return [
            'entries' => 'required|array',
            'entries.*.id' => 'nullable|exists:fee_entries,id',
            'entries.*.amount' => 'numeric|min:0',
            'entries.*.player_id' => 'required|exists:players,id',
            'entries.*.matchday_id' => 'required|exists:matchdays,id',
            'entries.*.fee_type_version_id' => 'required|exists:fee_type_versions,id',
        ];
    }
}
