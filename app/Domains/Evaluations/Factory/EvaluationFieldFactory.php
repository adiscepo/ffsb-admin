<?php

namespace App\Domains\Evaluations\Factory;

use App\Domains\Evaluations\EvaluationField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationField>
 */
class EvaluationFieldFactory extends Factory
{
    // Needed because we are in a non-standard location for models
    protected $model = EvaluationField::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => fake()->numberBetween(0, 6),
            'comment' => fake()->text(100),
        ];
    }
}
