<?php

namespace App\Domains\Roles\Factory;

use App\Domains\Meetings\Meeting;
use App\Domains\Roles\Role;
use App\Enums\Color;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meeting>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['ffsb', 'CdS']),
            'color' => fake()->randomElement(Color::cases()),
        ];
    }
}
