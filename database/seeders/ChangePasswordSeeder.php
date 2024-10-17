<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ChangePasswordSeeder extends Seeder
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
            'email' => 'admin3@novomostovye.ru',
            'password'=> Hash::make('HGeC6SRly7KL')
        ];
        DB::table('users') ->insert($admins);
    }
}
