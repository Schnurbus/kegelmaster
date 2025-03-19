<?php

namespace App\Http\Resources\v1\Statistics;

use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade;

class PlayerBalance extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $validated = $request->validate([
        //     'club_id' => 'required|exists:clubs,id',
        // ]);

        // $club = Club::find($validated['club_id']);

        // BouncerFacade::authorize('view', $club);

        return ['balance' => $this->balance];
    }
}
