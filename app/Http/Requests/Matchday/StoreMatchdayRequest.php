<?php

namespace App\Http\Requests\Matchday;

use App\Models\Matchday;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMatchdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', [Matchday::class, $this->input('club_id')]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
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
