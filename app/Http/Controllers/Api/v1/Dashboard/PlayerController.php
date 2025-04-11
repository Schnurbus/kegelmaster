<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Support\Facades\Gate;

class PlayerController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Player $player): PlayerResource
    {
        if (! Gate::allows('view', $player)) {
            abort(403);
        }

        return new PlayerResource($player);
    }
}
