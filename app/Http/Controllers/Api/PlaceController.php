<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;


class PlaceController extends Controller
{
    public function index(Request $request, $floor) {

        $places = Place::where('floor', $floor) -> get();
        foreach ($places as $place) {
            $place -> reserved = false;
           $reservations = $place ->reservations()
               ->whereDate('date', '=', $request->get('date'))
               ->whereTime('end', '>=', \Carbon\Carbon::parse($request->get('startDate')))
               -> whereTime('start' , '<=', \Carbon\Carbon::parse($request->get('endDate')))
               ->whereHas('order', function ($query) {
               $query->where('status','success')
                   ->orWhere('status', 'progress');
           }) -> get();
           if(count($reservations) > 0) {
               $place -> reserved = true;
           }
        }
        return $places;
    }
}
