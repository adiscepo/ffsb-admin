<?php

namespace Database\Seeders;

use App\Domains\Roles\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory(2)->create();
    }
}
