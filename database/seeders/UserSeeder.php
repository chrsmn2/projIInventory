<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin account - use updateOrCreate to avoid duplicates and preserve existing relations
        User::updateOrCreate(
            ['email' => 'admin@company.com'],
            [
                'name' => 'Admin IT',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin'
            ]
        );

        // Supervisor account
        User::updateOrCreate(
            ['email' => 'supervisor@company.com'],
            [
                'name' => 'Supervisor IT',
                'username' => 'supervisor',
                'password' => Hash::make('password'),
                'role' => 'supervisor'
            ]
        );
    }
}
