<?php

namespace App\Http\Requests\Club;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class EditClubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->can('update', $this->club);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
