<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Owner
        User::updateOrCreate(
        ['email' => 'owner@cozy.com'],
        [
            'name' => 'Owner Cozy',
            'password' => Hash::make('password'),
            'role_id' => 1, // Owner
        ]
        );

        // 2. Admin
        User::updateOrCreate(
        ['email' => 'admin@cozy.com'],
        [
            'name' => 'Admin Staff',
            'password' => Hash::make('password'),
            'role_id' => 2, // Admin
        ]
        );

        // 3. Cashier (The "User")
        User::updateOrCreate(
        ['email' => 'cashier@cozy.com'],
        [
            'name' => 'Kasir Utama',
            'password' => Hash::make('password'),
            'role_id' => 3, // Cashier
        ]
        );
    }
}
