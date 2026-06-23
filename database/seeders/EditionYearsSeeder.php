<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\EditionYear;
use Illuminate\Database\Seeder;

class EditionYearsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EditionYear::factory()->create(['year' => 2024]);
    }
}
