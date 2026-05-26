<?php

namespace Database\Factories;

use App\Models\EditionYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EditionYear>
 */
class EditionYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year' => "2026",
            'current' => true,
        ];
    }
}
