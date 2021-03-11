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

        Order::create([
            'product_id' => 1,
            'user_id' => 1,
            'quantity' => 2,
        ]);
    }
}
