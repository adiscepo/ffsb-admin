<?php

namespace App\Domains\Bugs\Factory;

use App\Models\User;
use App\Domains\Bugs\Bug;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bug>
 */
class BugFactory extends Factory
{
    // Needed because we are in a non-standard location for models
    protected $model = Bug::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->randomElement(['Erreur', 'Problème']) . ' avec ' . fake()->randomLetter(),
            'description' => fake()->text(250),
            // 'files_upload' => json_encode([fake()->file()]),
            'user_id' => fake()->numberBetween(1, User::latest('id')->first()->id),
        ];
    }
}
