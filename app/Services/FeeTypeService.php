<?php

namespace App\Services;

use App\Models\FeeType;
use App\Models\FeeTypeVersion;
use App\Models\Matchday;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class FeeTypeService
{
    /**
     * Create a new Fee Type
     *
     * @throws Throwable
     */
    public function create(array $data): FeeType
    {
        try {
            $feeType = DB::transaction(function () use ($data) {
                $feeType = FeeType::create([
                    'club_id' => $data['club_id'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'amount' => $data['amount'],
                    'position' => $data['position'],
                ]);
                FeeTypeVersion::create([
                    'fee_type_id' => $feeType->id,
                    'name' => $feeType->name,
                    'description' => $feeType->description,
                    'amount' => $feeType->amount,
                ]);

                return $feeType;
            });
        } catch (Throwable $exception) {
            Log::error('Error creating fee type', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }

        return $feeType;
    }

    /**
     * @throws Throwable
     */
    public function update(FeeType $feeType, array $data): FeeType
    {
        try {
            $feeType = DB::transaction(function () use ($feeType, $data) {
                if ($feeType->amount !== $data['amount']) {
                    FeeTypeVersion::create([
                        'fee_type_id' => $feeType->id,
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'amount' => $data['amount'],
                    ]);
                }

                $feeType->update($data);

                return $feeType;
            });
        } catch (Throwable $exception) {
            Log::error('Error creating fee type', ['error' => $exception->getMessage()]);
            throw new Exception($exception->getMessage());
        }

        return $feeType;
    }

    public function getByClubId(int $clubId): Collection
    {
        return FeeType::where('club_id', $clubId)->get();
    }

    /**
     * Get all fee types for a matchDay with their active versions
     */
    public function getFeeTypesForMatchday(Matchday $matchday): Collection
    {
        return FeeType::where('club_id', $matchday->club_id)
            // Nur FeeTypes, die eine Version haben, die mit diesem Matchday verknüpft ist
            ->whereHas('feeTypeVersions.matchdays', function ($query) use ($matchday) {
                $query->where('matchdays.id', $matchday->id);
            })
            ->with('latestVersion')
            ->orderBy('position')
            ->get();
    }
}
