<?php

namespace Database\Factories;

use App\Models\FeeType;
use App\Models\FeeTypeVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeeType>
 */
class FeeTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (FeeType $feeType) {
            FeeTypeVersion::create([
                'fee_type_id' => $feeType->id,
                'name' => $feeType->name,
                'description' => $feeType->description,
                'amount' => $feeType->amount,
            ]);
        });
    }
}
