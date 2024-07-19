<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticController extends Controller
{
   public function index(Request $request) {
       if($request->get('date_range')) {
           //dd($request->get('date_range'));
           $start_date = Carbon::parse($request->get('date_range')[0]);
           $end_date = Carbon::parse($request->get('date_range')[1]);
       } else {
           $start_date = Carbon::now();
           $end_date = Carbon::parse($start_date)->addDays(6);
       }

       $statistic_array = [];

       $reservations = Reservation::whereHas('order', function ($query){
           $query->where('status', 'success');
       })->whereDate('date', '<=', $end_date)-> whereDate('date', '>=', $start_date)->get();
       $orders = Order::where('status', 'success')->whereDate('date' ,'>=', $start_date)->whereDate('date' ,'<=', $end_date) -> whereHas('reservations') -> get();

       for ($day = $start_date; $day <= $end_date; $day= $day->addDay()) {

            $current_date = $day ->toDateString();

            $places = $reservations -> where('date' ,'=', $current_date)
                ->where('reservationable_type', 'App\Models\Place')->count();

            $cabinetes = $reservations-> where('date' ,'=', $current_date)
                ->where('reservationable_type', 'App\Models\Cabinet')->count();

            $day_orders = $orders ->where('date' , $current_date);
           // $cash =  $day_orders -> where('type', 'cash')->sum('price');

           $sert =$day_orders ->where('type', 'sert')->sum('price');

           $transfer = $day_orders->where('type', 'transfer')->sum('price');

           $founder = $day_orders->where('type', 'founder')->sum('price');

            $invitation = $day_orders->where('type', 'invitation')->sum('price');
           $offsetting = $day_orders->where('type', 'offsetting')->sum('price');
           $non_cash = $day_orders->where('type', 'non-cash')->sum('price');
            $sum = $day_orders -> where('type', '!=', 'founder')-> where('type', '!=', 'invitation')->where('type', '!=', 'offsetting')->sum('price');

            $standard = $day_orders->where('type', 'standard')->sum('price');

            $day_arr = [
                'current_date' => Carbon::parse($current_date)->format('d.m.Y'),
                'places' => $places,
                'cabinetes' => $cabinetes,
                'standard' => $standard,
                'non_cash' => $non_cash,
                'offsetting' => $offsetting,
                'sert' => $sert,
                'transfer' => $transfer,
                'founder' => $founder,
                'invitation' => $invitation,
                'sum' => $sum
            ];

           $statistic_array[]=$day_arr;
       }


      return $statistic_array;
   }
}
