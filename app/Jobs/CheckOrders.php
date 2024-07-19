<?php

namespace App\Jobs;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $orders = Order::where('alfa_order_id', null)->where('status', 'progress') ->where('type', 'standard')->get();
        foreach ($orders as $order) {
            $expired_time = Carbon::parse($order->created_at)->addMinutes(20);
            $expired_time = $expired_time->getTimestamp();
            if($expired_time < Carbon::now()->getTimestamp()) {
                $order -> status = 'cancelled';
                $order->save();
            }
        }
    }
}
