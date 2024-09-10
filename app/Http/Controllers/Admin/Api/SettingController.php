<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use App\Models\Place;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setStartTime(Request $request)
    {
        $request->validate([
            'default' => 'required',
            'day_off'=>'required',
            'saturday' => 'required',
            'sunday' => 'required'
        ], [
            'default.required' => 'Назначьте стартовое время для бронирования',
            'day_off.required' => 'Назначьте стартовое время для бронирования',
            'saturday.required' => 'Назначьте стартовое время для бронирования',
            'sunday.required' => 'Назначьте стартовое время для бронирования',
        ]);
        $exists_default= Setting::where('group','start-time')->where('name','default')->exists();
        if(!$exists_default) {
            $default = Setting::create(['group' => 'start-time', 'name'=>'default', 'value' => $request->get('default')]);
        } else {
            $default = Setting::where('group', 'start-time')-> where('name', 'default')->firstOrFail();
            $default->update(['value' => $request->get('default')]);
        }

        $exists_day_off= Setting::where('group','start-time')->where('name','day_off')->exists();
        if(!$exists_day_off) {
            $day_off = Setting::create(['group' => 'start-time', 'name'=>'day_off', 'value' => $request->get('day_off')]);
        } else {
            $day_off = Setting::where('group', 'start-time')-> where('name', 'day_off')->firstOrFail();
            $day_off->update(['value' => $request->get('day_off')]);
        }

        $exists_saturday= Setting::where('group','start-time')->where('name','saturday')->exists();
        if(!$exists_saturday) {
            $saturday = Setting::create(['group' => 'start-time', 'name'=>'saturday', 'value' => $request->get('saturday')]);
        } else {
            $saturday = Setting::where('group', 'start-time')-> where('name', 'saturday')->firstOrFail();
            $saturday->update(['value' => $request->get('saturday')]);
        }

        $exists_sunday = Setting::where('group','start-time')->where('name','sunday')->exists();
        if(!$exists_sunday) {
            $sunday = Setting::create(['group' => 'start-time', 'name'=>'sunday', 'value' => $request->get('sunday')]);
        } else {
            $sunday = Setting::where('group', 'start-time')-> where('name', 'sunday')->firstOrFail();
            $sunday->update(['value' => $request->get('sunday')]);
        }

        return  [
            'default' => $default -> value,
            'day_off' => $day_off -> value,
            'saturday' =>  $saturday -> value,
            'sunday' =>  $sunday -> value
        ];
    }

    public function getStartTime()
    {
        $settings = Setting::where('group', 'start-time') -> get();
        $start_times = [];
        foreach ($settings as $setting) {
            $start_times[$setting->name] = $setting->value;
        }
        return $start_times;
    }
    public function getPrices()
    {
        $prices = [];
        $place = Place::first();
        $prices['places'] = $place->price;
        $cabinets = Cabinet::all();
        foreach ($cabinets as $cabinet) {
            $prices['cabinets'][] = [
                'id' => $cabinet->id,
                'number' => $cabinet->number,
                'price' => $cabinet->price,];

        }
        return $prices;
    }

    public function setPrices(Request $request)
    {
        $places = Place::all();
        foreach ($places as $place) {
            $place->price = $request->get('places');
            $place->save();
        }
        foreach ($request->get('cabinets') as $cabinetForm) {
            $cabinet = Cabinet::findOrFail($cabinetForm['id']);
            $cabinet->price = $cabinetForm['price'];
            $cabinet->save();
        }
    }
}
