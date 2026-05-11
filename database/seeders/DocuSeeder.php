<?php

namespace Database\Seeders;

use App\Models\Docu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Docu::factory()->hasAttached(User::factory())->create([
            'title' => 'Fire of Love',
            'summary' => 'Fire of Love tells the story of two French lovers, Katia and Maurice Krafft, who died in a volcanic explosion doing the very thing that brought them together: unraveling the mysteries of our planet, while simultaneously capturing the most explosive volcano imagery ever recorded. Along the way, they changed our understanding of the natural world, and saved tens of thousands of lives. Previously unseen hours of pristine 16-millimeter film and thousands of photographs reveal the birth of modern volcanology through an unlikely lens — the love of its two pioneers.',
            'duration' => '57',
        ]);
    }
}
