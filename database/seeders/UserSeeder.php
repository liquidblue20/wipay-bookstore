<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            User::create(['name' => 'user1',
            'email' => 'shev@yahoo.com',
            'password' => '123321'
            ]);
    }
}

