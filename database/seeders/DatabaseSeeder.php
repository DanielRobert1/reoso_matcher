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
        \App\Models\PropertyType::factory(1)->create();
        \App\Models\SearchProfile::factory(200)->create();
        \App\Models\Property::factory(200)->create();
    }
}
