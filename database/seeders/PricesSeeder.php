<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use App\Models\Place;
use Illuminate\Database\Seeder;

class PricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $places = Place::all();
        foreach ($places as $place) {
            $place -> price = 450;
            $place -> save();
        }
        $cabinets = Cabinet::all();
        foreach ($cabinets as $cabinet) {
            if($cabinet -> number === 1) {
                $cabinet -> price = 3100;
            } elseif ($cabinet -> number === 7) {
                $cabinet -> price = 3900;
            } else {
                $cabinet -> price = 2500;
            }
            $cabinet -> save();
        }
    }
}
