<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class AddStandardInOrders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::all();
        foreach ($orders as $order) {
            $order->type = 'standard';
            $order->save();
        }
    }
}
