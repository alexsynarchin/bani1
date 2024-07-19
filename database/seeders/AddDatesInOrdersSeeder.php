<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddDatesInOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = Order::whereHas('reservations')->with('reservations')->chunkById(300, function ($orders){
            foreach ($orders as $order) {
                $order -> date = $order->reservations[0]['date'];
                $order -> start = $order->reservations[0]['start'];
                $order -> end = $order->reservations[0]['end'];
                $order->save();
             }
        });
    }
}
