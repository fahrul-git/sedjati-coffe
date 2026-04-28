<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sedjaticoffee.test'],
            [
                'name' => 'Admin Sedjati',
                'role' => 'admin',
                'password' => 'password',
            ]
        );

        User::updateOrCreate(
            ['email' => 'kasir@sedjaticoffee.test'],
            [
                'name' => 'Kasir Sedjati',
                'role' => 'kasir',
                'password' => 'password',
            ]
        );
    }
}
