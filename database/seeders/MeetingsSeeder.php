<?php

namespace Database\Seeders;

use App\Domains\Meetings\Meeting;
use Illuminate\Database\Seeder;

class MeetingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Meeting::factory(5)->create();
    }
}
