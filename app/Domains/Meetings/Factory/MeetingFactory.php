<?php

namespace App\Domains\Meetings\Factory;

use App\Domains\Meetings\Meeting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Meeting>
 */
class MeetingFactory extends Factory
{
    protected $model = Meeting::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'name' => 'Réunion ' . fake()->randomLetter(),
            'datetime' => fake()->dateTime(),
            'location' => fake()->city(),
            'description' => fake()->text(500),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Meeting $meeting) {
            foreach (User::all() as $user) {
                if (fake()->boolean(40)) {
                    $meeting->members()->attach($user);
                }
            }
        });
    }
}
