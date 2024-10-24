<?php

namespace Database\Seeders;

use App\Models\Hub;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a faker instance
        $faker = Faker::create();

        User::factory(5)->create();

        Hub::factory(10)->create();
    }
}
