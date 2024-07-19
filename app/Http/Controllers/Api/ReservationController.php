<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $date = json_decode($request-> get('date'));
        $date = $date -> date;
        $places = Place::whereHas('reservations', function($query) use ($date) {
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
        }) -> with(['reservations' => function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
            $query->with('order.client');
        }]) ->  orderBy('number') -> get();
        $cabinets = Cabinet::whereHas('reservations', function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query -> where('status', 'success');
            } );

        }) -> with(['reservations' => function($query) use ($date){
            $query->where('date', $date);
            $query->whereHas('order', function($query){
                $query->where('status', 'success');
            });
            $query->with('order.client');
        }]) ->  orderBy('number') -> get();
        return ['places' => $places, 'cabinets' => $cabinets];
    }
    public function navigation()
    {
        $date = Carbon::now();
        $i = 5;
        $dates = [
            [
                'date' =>$date -> format('Y-m-d'),
                'date_string' => Date::parse($date)->format('j F'),
            ]
        ];

        for($i = 0; $i <= 7; $i++) {
            $current_date = $date->addDays(1);
            $selected = false;

            $item = [
                'date' =>$current_date -> format('Y-m-d'),
                'date_string' => Date::parse($current_date)->format('j F'),
            ];
            array_push($dates, $item);
        }


        return ['dates' => $dates,
            'now' => $dates[0]];
    }
}
