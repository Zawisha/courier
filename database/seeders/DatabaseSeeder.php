<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StatusCourierSeeder::class,
            TokenInfoSeeder::class,
            WorkRulesSeeder::class,
            CarColorsSeeder::class,
            CarTransmissionSeeder::class,
            CarBrandSeeder::class,
            CarModelSeeder::class,
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
            ApiPermSeeder::class,
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
