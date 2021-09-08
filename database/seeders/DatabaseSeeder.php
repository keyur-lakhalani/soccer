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
        $user = new User();
        $user->name = 'Soccer Admin';
        $user->email = 'admin@socerlocal.com';
        $user->password = Hash::make('Admin12!@');
        $user->save();
    }
}
