<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /*DB::table('users')->insert([
            'name' => 'Soccer Admin',
            'email' => 'admin@soccerlocal.com',
            'password' => Hash::make('Admin12!@'),
        ]);*/
    }
}
