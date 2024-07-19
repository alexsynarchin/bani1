<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            'name' => 'Бухгалтер',
            'email' => 'admin3@baniufa.ru',
            'password'=> Hash::make('Y8rNhzs1')
        ];
        DB::table('users') ->insert($admins);
    }
}
