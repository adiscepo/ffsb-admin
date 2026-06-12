<?php

namespace App\Domains\Docus\Factory;

use App\Domains\Docus\DocuLink;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DocuLink>
 */
class DocuLinkFactory extends Factory
{
    protected $model = DocuLink::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => fake()->url(),
            'password' => fake()->boolean() ? fake()->password() : null,
            'deadline' => fake()->boolean() ? fake()->dateTimeInInterval('now', '+1 year') : null,
        ];
    }
}
