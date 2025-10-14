<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'usuario' => 'admin',
            'password' => '1505',  
            'rol' => 'admin',
            'estado' => 1,
        ]);

        User::create([
            'usuario' => 'user1',
            'password' => '1234',
            'rol' => 'usuario',
            'estado' => 1,
        ]);

        User::create([
            'usuario' => 'user2',
            'password' => '1234',
            'rol' => 'usuario',
            'estado' => 1,
        ]);
    }
}

