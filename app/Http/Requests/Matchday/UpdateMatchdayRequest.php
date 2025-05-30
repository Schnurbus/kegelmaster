<?php

namespace App\Http\Requests\Matchday;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', $this->route('matchday'));
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
