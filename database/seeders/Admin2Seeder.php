<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Admin2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            'name' => 'Главный администратор',
            'email' => 'admin2@baniufa.ru',
            'password'=> Hash::make('HGeC6SRl')
        ];
        DB::table('users') ->insert($admins);
    }
}
