<?php

namespace App\Domains\Docus\Factory;

use App\Domains\Docus\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    protected $model = Field::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field' => fake()->unique()->randomElement(['Biologie', 'Math', 'Physique', 'Biologie', 'Informatique', 'Chimie']),
            'color' => fake()->unique()->randomElement([
                'red',
                'orange',
                'amber',
                'yellow',
                'lime',
                'green',
                'emerald',
                'teal',
                'cyan',
                'sky',
                'blue',
                'indigo',
                'violet',
                'purple',
                'fuchsia',
                'pink',
                'rose',
            ])
        ];
    }
}
