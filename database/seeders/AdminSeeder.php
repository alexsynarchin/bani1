<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            'name' => 'Администратор',
            'email' => 'admin@baniufa.ru',
            'password'=> Hash::make('6MKXbymfO')
        ];
        DB::table('users') ->insert($admins);
    }
}
