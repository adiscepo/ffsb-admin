<?php

namespace Database\Factories;

use App\Models\DocuLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field' => fake()->unique()->randomElement(['Biologie', 'Math', 'Physique', 'Biologie', 'Informatique', 'Chimie']),
            'color' => fake()->hexColor(),
        ];
    }
}
