<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use Illuminate\Http\Request;

class CabinetController extends Controller
{
    public function index(Request $request, $floor)
    {
        $cabinets = Cabinet::where('floor',$floor)->get();
        foreach ($cabinets as $cabinet) {
            $cabinet -> reserved = false;
            $reservations = $cabinet ->reservations()
                ->whereDate('date', '=', $request->get('date'))
                ->whereTime('end', '>=', \Carbon\Carbon::parse($request->get('startDate')))
                -> whereTime('start' , '<=', \Carbon\Carbon::parse($request->get('endDate')))
                ->whereHas('order', function ($query) {
                    $query->where('status','success')
                        ->orWhere('status', 'progress');
                }) -> get();
            if(count($reservations) > 0) {
                $cabinet -> reserved = true;
            }
        }
        return $cabinets;
    }
}
