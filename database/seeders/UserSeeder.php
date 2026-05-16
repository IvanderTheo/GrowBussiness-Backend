<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'first_name'=>'root',
            'last_name'=>'root',
            'email'=>'root@gmail.com',
            'role'=>'admin',
            'password'=>'root',
            'is_agree_terms'=>true,
        ]);
    }
}
