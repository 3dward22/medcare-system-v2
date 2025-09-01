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
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // login: password
                'role' => 'admin',
            ]
        );

        // Nurse account
        User::firstOrCreate(
            ['email' => 'nurse@example.com'],
            [
                'name' => 'Nurse User',
                'password' => Hash::make('password'), // login: password
                'role' => 'nurse',
            ]
        );

        // Student account
        User::firstOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'), // login: password
                'role' => 'student',
            ]
        );
    }
}
