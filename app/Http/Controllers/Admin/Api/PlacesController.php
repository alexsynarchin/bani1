<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Date\Date;

class PlacesController extends Controller
{


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
