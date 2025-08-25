<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin account
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // login: password
            'role' => 'admin',
        ]);

        // Nurse account
        User::create([
            'name' => 'Nurse User',
            'email' => 'nurse@example.com',
            'password' => Hash::make('password'), // login: password
            'role' => 'nurse',
        ]);

        // Student account
        User::create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'password' => Hash::make('password'), // login: password
            'role' => 'student',
        ]);
    }
}
