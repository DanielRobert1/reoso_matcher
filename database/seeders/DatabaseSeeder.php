<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\PropertyType::factory(10)->create();
        \App\Models\SearchProfile::factory(20)->create();
        \App\Models\Property::factory(20)->create();
    }
}
