<?php

namespace Database\Factories;

use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evaluation>
 */
class EvaluationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $json = json_encode([
            'real' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'graphics' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'vulgarization' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'accessibility' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'source' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'general_impact' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'personal_interest' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'duration' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'festival_interest' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            'findable' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
        ]);
        return [
            'evaluation' => $json,
            'comment' => fake()->text(100),
        ];
    }
}

// $eval = json_decode($json, true);
// $note = 0;
// $note += (intval($eval["real"][0]) + intval($eval["graphics"][0])) / 2;
// $note += intval($eval["vulgarisation"][0]) * 2;
// $note += intval($eval["accessibility"][0]);
// $note += intval($eval["personnal_interest"][0]);
// $note += intval($eval["duration"][0]) / 2;
// $note += intval($eval["festival_interest"][0]) * 2;
// $note += intval($eval["findable"][0]);
// $note += intval
