<?php

namespace App\Http\Requests\CompetitionType;

use App\Models\CompetitionType;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class IndexCompetitionTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();

        return $user->can('view', getClubScopedModel(CompetitionType::class));
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
