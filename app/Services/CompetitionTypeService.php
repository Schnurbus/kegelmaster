<?php

namespace App\Services;

use App\Models\CompetitionType;
use Illuminate\Database\Eloquent\Collection;

class CompetitionTypeService
{
    /**
     * Create a new competition type
     *
     * @throws \Exception
     */
    public function create(array $data): CompetitionType
    {
        try {
            return CompetitionType::create($data);
        } catch (\Exception $exception) {
            throw new \Exception('Error creating competition type', ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Delete a competition type
     *
     * @throws \Exception
     */
    public function delete(CompetitionType $competitionType): bool|int|null
    {
        try {
            return $competitionType->delete();
        } catch (\Exception $exception) {
            throw new \Exception('Error deleting competition type', ['error' => $exception->getMessage()]);
        }
    }

    /**
     * Get competition types by club id
     */
    public function getByClubId(int $clubId): Collection
    {
        return CompetitionType::where('club_id', $clubId)->get();
    }
}
