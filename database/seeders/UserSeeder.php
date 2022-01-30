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
            'email' => 'admin@yahoo.com',
            'password' => bcrypt('123321'),
            'role_id' => 1
            ]);
            User::create(['name' => 'user2',
            'email' => 'user@gmail.com',
            'password' => bcrypt('123321'),
            'role_id' => 2
            ]);
    }
}

