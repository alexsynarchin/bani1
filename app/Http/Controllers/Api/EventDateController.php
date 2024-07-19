<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventDate;
use Illuminate\Http\Request;

class EventDateController extends Controller
{
    public function index(Request $request)
    {

        $from = date($request->get('start'));
        $to = date($request->get('end'));
        $event_dates = EventDate::whereBetween('date', [$from, $to])->get();
        return $event_dates;
    }
}
