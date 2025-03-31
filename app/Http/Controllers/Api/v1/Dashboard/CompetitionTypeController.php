<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompetitionTypeResource;
use App\Models\Club;
use App\Models\CompetitionType;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Silber\Bouncer\BouncerFacade;

class CompetitionTypeController extends Controller
{
    /**
     * @param Club $club
     * @return JsonResource
     * @throws AuthorizationException
     */
    public function index(Club $club): JsonResource
    {
        BouncerFacade::authorize('list',  getClubScopedModel(CompetitionType::class, $club->id));

        $competitionTypes = $club->competitionTypes;

        return CompetitionTypeResource::collection($competitionTypes);
    }
}
