<?php

namespace App\Http\Controllers\Api\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CompetitionTypeResource;
use App\Models\Club;
use App\Models\CompetitionType;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class CompetitionTypeController extends Controller
{
    public function index(Club $club): JsonResource
    {
        if (! Gate::allows('list', [CompetitionType::class, $club->id])) {
            abort(403);
        }

        $competitionTypes = $club->competitionTypes;

        return CompetitionTypeResource::collection($competitionTypes);
    }
}
