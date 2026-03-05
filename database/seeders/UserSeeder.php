<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@lumina.cafe',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $cashier = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@lumina.cafe',
            'password' => bcrypt('password'),
        ]);
        $cashier->assignRole('cashier');
    }
}
