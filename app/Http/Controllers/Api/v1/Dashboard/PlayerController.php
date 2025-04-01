<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use Illuminate\Auth\Access\AuthorizationException;
use Silber\Bouncer\BouncerFacade;

class PlayerController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Player $player): PlayerResource
    {
        BouncerFacade::authorize('view', $player);

        return new PlayerResource($player);
    }
}
