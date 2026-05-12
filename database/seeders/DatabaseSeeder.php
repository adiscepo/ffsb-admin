<?php

namespace Database\Seeders;

use App\Models\Docu;
use App\Models\DocuLink;
use App\Models\Evaluation;
use App\Models\Field;
use App\Models\ProductionHouse;
use App\Models\Tag;
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

        $user = User::factory()->create([
            'name' => 'Attilio',
            'email' => 'attiliodiscepoli@hotmail.be',
            'password' => Hash::make("Epanadiplose")
        ]);
        User::factory()->create([
            'name' => 'Margaux',
            'email' => 'margaux.vandererven@ulb.be',
            'password' => Hash::make("Epanadiplose")
        ]);

        $production_houses = ProductionHouse::factory()->count(10)->create();
        $docu_fields = Field::factory()->count(5)->create();
        $docu_tags = Tag::factory()->count(3)->create();

        $docus = Docu::factory()->count(50)
                       ->hasAttached($production_houses, [], 'from')
                       ->has(DocuLink::factory()->count(rand(1, 2)), 'see_at')
                       ->create();
        
        Evaluation::factory()->count(10)->for($user)->for($docus->random())->create();
        
        // use ($var) is needed to include $var to the closure's environment
        $docus->each(function (Docu $docu) use ($docu_fields, $docu_tags) {
            $docu->fields()->attach($docu_fields->random(random_int(1, 2)));
            if (fake()->boolean()) $docu->tags()->attach($docu_tags->random(random_int(0, 2)));
        });
    }
}
