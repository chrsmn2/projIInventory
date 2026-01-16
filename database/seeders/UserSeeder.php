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
        // Admin account
        User::create([
            'name' => 'Admin IT',
            'email' => 'admin@company.com',
            'password' => Hash::make('password'), // Password terenkripsi
            'role' => 'admin'
        ]);

        // Supervisor account
        User::create([
            'name' => 'Supervisor IT',
            'email' => 'supervisor@company.com',
            'password' => Hash::make('password'),
            'role' => 'supervisor'
        ]);
    }
}
