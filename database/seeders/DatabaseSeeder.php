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
        $this->call([BookSeeder::class]);
        $this->call([OrderStatusSeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([OrderSeeder::class]);
        $this->call([RoleSeeder::class]);
        // \App\Models\User::factory(10)->create();
    }
}
