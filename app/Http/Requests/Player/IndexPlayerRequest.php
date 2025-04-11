<?php

namespace App\Http\Requests\Player;

use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class IndexPlayerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User $user */
        $user = $this->user();
        $currentClubId = session('current_club_id');

        return $user->can('list', [Player::class, $currentClubId]);
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
