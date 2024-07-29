<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventDate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getCurrentDate()
    {
        $today =  Carbon::now()->addHours(5);
        $dayOfWeek = $today->dayOfWeek;
        $date = $today;
        $hours = 15;
        $time = (int) $today->format('H');
        if($dayOfWeek === 6 ) {
          $hours = 12;
        }
        if( $dayOfWeek === 0) {
            $hours = 11;
        }
       /* if(EventDate::where('date', $date->format('Y-m-d'))->where('day_off', 0)->exists()) {
            $event_date = EventDate::where('date', $date->format('Y-m-d'))->where('day_off', 0)->firstOrFail();
            $event_date ->start_time;
        }*/
        if(EventDate::where('date', $date->format('Y-m-d'))->exists()) {
            $eventDate = EventDate::where('date', $date->format('Y-m-d'))->first();
           $hours = Carbon::parse($eventDate->start_time)->hour;
        };
        if( $time >= $hours) {
            $date = $date ->addDays(1);
        }

        while (EventDate::where('date', $date->format('Y-m-d'))->where('day_off', 1)->exists()) {
            $date = $date->addDays(1);
        }
        return $date->format('Y-m-d');
    }

}
