<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        $user = User::where('email', 'admin@novomostovye.ru')->firstOrFail();
        $user -> password = Hash::make('h<WMd?>[QMR79gp');
        $user->save();
    }
}
