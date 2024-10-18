<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use Illuminate\Database\Seeder;

class SecondCabineteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cabinetes = [
            [
                'number'=> 21,
                'posX' => 635,
                'posY' => 294,
                'width' => 100,
                'height' =>83,
                'floor' => 2
            ],
            [
                'number'=> 22,
                'posX' => 635,
                'posY' => 166,
                'width' => 100,
                'height' =>83,
                'floor' => 2
            ],
            [
                'number'=> 23,
                'posX' => 635,
                'posY' => 13,
                'width' => 100,
                'height' =>112,
                'floor' => 2
            ],
            [
                'number'=> 24,
                'posX' => 790,
                'posY' => 15,
                'width' => 103,
                'height' =>89,
                'floor' => 2
            ],
            [
                'number'=> 25,
                'posX' => 808,
                'posY' => 120,
                'width' => 89,
                'height' =>110,
                'floor' => 2
            ],
            [
                'number'=> 26,
                'posX' => 808,
                'posY' => 242,
                'width' => 89,
                'height' =>110,
                'floor' => 2
            ],
            [
                'number'=> 27,
                'posX' => 561,
                'posY' => 491,
                'width' => 52,
                'height' =>92,
                'floor' => 2
            ],

            [
                'number'=> 28,
                'posX' => 439,
                'posY' => 491,
                'width' => 52,
                'height' =>92,
                'floor' => 2
            ],
        ];
        foreach ($cabinetes as $cabinete) {
            Cabinet::create($cabinete);
        }
    }
}
