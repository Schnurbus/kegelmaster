<?php

namespace App\Http\Requests\Matchday;

use App\Models\Matchday;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateMatchdayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('create', getClubScopedModel(Matchday::class));
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
