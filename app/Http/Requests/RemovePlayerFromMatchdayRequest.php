<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Silber\Bouncer\BouncerFacade;

class RemovePlayerFromMatchdayRequest extends FormRequest
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
            'player_id' => [
                'required',
                // Rule::exists('players')->where(function (Builder $query) {
                //     $query->where('club_id', $this->matchday->club_id);
                // }),
                // Rule::unique('matchday_player')->where(function (Builder $query) {
                //     $query->where('matchday_id', $this->matchday->id);
                // })
            ],
        ];
    }
}
