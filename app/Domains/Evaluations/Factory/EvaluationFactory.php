<?php

namespace App\Domains\Evaluations\Factory;

use App\Domains\Evaluations\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evaluation>
 */
class EvaluationFactory extends Factory
{
    // Needed because we are in a non-standard location for models
    protected $model = Evaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => fake()->text(100),
        ];
    }
}
