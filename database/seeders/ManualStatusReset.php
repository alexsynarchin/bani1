<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use App\Models\Order;
use App\Models\Place;
use Illuminate\Database\Seeder;

class ManualStatusReset extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders =  Order::whereIn('type', ['non-cash', 'offsetting', 'transfer', 'sert', 'founder', 'invitation'])
            ->whereHas('reservations')
            ->with('reservations')
            ->orderBy('created_at','DESC')
            -> get();
        foreach ($orders as $order) {
            $reserved = false;
            foreach ($order -> reservations as $reservation) {
                if($reservation -> reservationable_type === 'App\Models\Cabinet') {
                    $cabinet = Cabinet::findOrFail($reservation -> reservationable_id);
                    $reserved = $cabinet->reservations()
                        ->whereDate('date', '=', $order->date)
                        ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start))
                        -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                        ->whereHas('order', function ($query) use ($order) {
                            $query->where('id', '!=', $order->id)
                                ->where('status','success')
                                ->orWhere('status', 'progress');
                        }) -> exists();
                } else if($reservation -> reservationable_type === 'App\Models\Place') {
                    $place = Place::findOrFail($reservation -> reservationable_id);

                    $reserved = $place->reservations()
                        ->whereDate('date', '=', $order->date)
                        ->whereTime('end', '>=', \Carbon\Carbon::parse($order->start ))
                        -> whereTime('start' , '<=', \Carbon\Carbon::parse($order->end))
                        ->whereHas('order', function ($query) use ($order) {
                            $query->where('id', '!=', $order->id)
                                ->where('status','success')
                                ->orWhere('status', 'progress');
                        }) -> exists();
                }

                if($reserved) {
                    break;
                }
            }
            if(!$reserved) {
                $order->status = 'success';
                $order->save();
            }
        }

    }
}
