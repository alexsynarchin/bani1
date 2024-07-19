<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\EventDate;
use Illuminate\Http\Request;

class EventDateController extends Controller
{
    public function index()
    {
        $event_dates = EventDate::all();
        return $event_dates;
    }

    public function store(Request $request)
    {

        $request->validate([
            'date' => 'required',
            'start_time' => 'required',
        ], [
            'date.required' => 'Выберите дату',
            'start_time.required'=> 'Выберите стартовое время'
        ]);

        $eventDate = EventDate::create([
            'date' => date('Y-m-d H:i:s',  strtotime($request ->get('date'))),
            'start_time' => $request->get('start_time'),
            'day_off' => $request->get('day_off'),
        ]);
        return $eventDate;
    }

    public function update(Request $request, $id)
    {
        $event_date = EventDate::findOrFail($id);
        $event_date-> update([
            'date' => date('Y-m-d H:i:s',  strtotime($request ->get('date'))),
            'start_time' => $request->get('start_time'),
            'day_off' => $request->get('day_off'),
        ]);
        return $event_date;
    }

    public function show($id)
    {
        $event_date = EventDate::findOrFail($id);
        return $event_date;
    }

    public function destroy($id)
    {
        $event_date = EventDate::findOrFail($id);
        $event_date->delete();
        return $event_date;
    }
}
