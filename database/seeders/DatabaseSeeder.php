<?php

namespace Database\Seeders;

use App\Models\Docu;
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

        Docu::factory()->count(50)->create();
    }
}
