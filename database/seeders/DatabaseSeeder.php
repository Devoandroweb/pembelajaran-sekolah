<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        // \App\Models\User::create([
        //     'name'=>'admin',
        //     'email'=>'admin@mail.com',
        //     'password'=>Hash::make('admin'),
        // ]);
        \App\Models\UserEpic::create([
            'username'=>'admin',
            'password'=>Hash::make('admin'),
            'role'=>1
        ]);
    }
}
