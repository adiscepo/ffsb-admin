<?php

namespace App\Domains\ProductionHouses\Factory;

use App\Domains\Docus\Enum\DocuLang;
use App\Domains\ProductionHouses\ProductionHouse;
use Database\Factories\FakerBinary;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductionHouse>
 */
class ProductionHouseFactory extends Factory
{
    protected $model = ProductionHouse::class;
    use FakerBinary;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(15),
            'lang' => fake()->randomElement(DocuLang::cases()),
            'website' => FakerBinary::percentChance(50, fake()->url()),
            'contact_email' => fake()->safeEmail(),
            'contact_phone' => fake('fr_BE')->phoneNumber(),
            'remark' => fake()->text(75),
        ];
    }
}
