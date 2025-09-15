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
            'password' => '1505',   // tu mutator en el modelo lo hashea automáticamente
            'rol' => 'admin',
        ]);

        User::create([
            'usuario' => 'user1',
            'password' => '1234',
            'rol' => 'usuario',
        ]);

        User::create([
            'usuario' => 'user2',
            'password' => '1234',
            'rol' => 'usuario',
        ]);
    }
}

