<?php

namespace Database\Factories;
use Faker\Generator;

trait FakerBinary {
    public static function percentChance(int $chanceOfGettingTrue, $gen) {
        return fake()->boolean($chanceOfGettingTrue) ? $gen : null;
    }
}
