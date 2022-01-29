<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Order::create(['id' => 1,
        'user_id' => 1,
        'wipay_order_id' => "1w3",
        'book_id' => 1,
        'quantity' => 1,
        'purchase_price' => "10",
        'total' => "10",
        'order_status_id' => 1,
        'created_at' => "2022-01-28 06:53:46",
        'updated_at' => "2022-01-28 06:53:46"]);
}
}
