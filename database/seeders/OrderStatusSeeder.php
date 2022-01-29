<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['name' => 'pending'],            
            ['name' => 'failed'],
            ['name' => 'complete'],
            ['name' => 'refund']
        ];
        foreach ($statuses as $status) {
            OrderStatus::create($status);
    }
}
}
