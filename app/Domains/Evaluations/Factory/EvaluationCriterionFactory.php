<?php

namespace App\Domains\Evaluations\Factory;

use App\Domains\Evaluations\EvaluationCriterion;
use Database\Factories\FakerBinary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EvaluationCriterionFactory>
 */
class EvaluationCriterionFactory extends Factory
{
    use FakerBinary;

    protected $model = EvaluationCriterion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(15),
            'description' => fake()->text(60)
        ];
    }
}
