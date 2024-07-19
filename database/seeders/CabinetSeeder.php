<?php

namespace Database\Seeders;

use App\Models\Cabinet;
use Illuminate\Database\Seeder;

class CabinetSeeder extends Seeder
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
                'number'=> 11,
                'posX' => 764,
                'posY' => 502,
                'width' => 103,
                'height' =>74
            ],
            [
                'number'=> 12,
                'posX' => 849,
                'posY' => 374,
                'width' => 103,
                'height' =>88
            ],
            [
                'number'=> 13,
                'posX' => 849,
                'posY' => 271,
                'width' => 103,
                'height' =>88
            ],
            [
                'number'=> 14,
                'posX' => 840,
                'posY' => 88,
                'width' => 111,
                'height' =>123
            ],
            [
                'number'=> 15,
                'posX' => 751,
                'posY' => 88,
                'width' => 78,
                'height' =>50
            ],

        ];
        foreach ($cabinetes as $cabinete) {
            Cabinet::create($cabinete);
        }
    }
}
