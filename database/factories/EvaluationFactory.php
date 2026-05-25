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
            '1' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '2' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '3' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '4' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '5' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '5' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '6' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '7' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '8' => [
                'note' => fake()->numberBetween(0, 6),
                'comment' => fake()->text(100),
            ],
            '9' => [
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
