<?php

namespace Database\Seeders;

use App\Models\OrderStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   DB::table('order_statuses')->truncate();
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
