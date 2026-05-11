<?php

namespace Database\Seeders;

use App\Models\Docu;
use App\Models\ProductionHouse;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Attilio',
            'email' => 'attilio.discepoli@ulb.be',
            'password' => Hash::make("Epanadiplose")
        ]);
        User::factory()->create([
            'name' => 'Margaux',
            'email' => 'margaux.vandererven@ulb.be',
        ]);

        $production_houses = ProductionHouse::factory()->count(10)->create();

        Docu::factory()->count(50)->hasAttached($production_houses, [], 'from')->create();
    }
}
