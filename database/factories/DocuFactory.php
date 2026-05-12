<?php

namespace Database\Factories;

use App\Models\Docu;
use App\Models\Enum\DocuLang;
use App\Models\Enum\DocuTarget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Docu>
 */
class DocuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'summary' => fake()->text(),
            'duration' => fake()->randomNumber(2),
            'year' => fake()->year('+1 year'),
            'lang' => fake()->randomElement(DocuLang::cases()),
            'subtitles' => fake()->boolean(50) ? fake()->randomElement(DocuLang::cases()) : null,
            'target' => fake()->boolean(50) ? fake()->randomElement(DocuTarget::cases()) : null,
            'user_id' => fake()->numberBetween(1, User::latest('id')->first()->id)
            ];
    }
}
