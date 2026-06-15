<?php

namespace App\Domains\Programs\Factory;

use App\Domains\Programs\ProgramEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProgramEvent>
 */
class ProgramEventFactory extends Factory
{
    protected $model = ProgramEvent::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
