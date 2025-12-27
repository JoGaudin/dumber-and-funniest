<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'admin',
                'password_set_at' => now(),
            ]
        );

        // Create Regular User
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Regular User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'role' => 'user',
                'password_set_at' => now(),
            ]
        );

        // Create League
        $league = \App\Models\League::create([
            'name' => 'Main League',
            'description' => 'The primary league for testing.',
        ]);

        // Link users to league
        $league->users()->attach([1, 2]);

        // Create Comment Types
        \App\Models\CommentType::create(['name' => 'Intelligent', 'emoji' => '🧠']);
        \App\Models\CommentType::create(['name' => 'Drole', 'emoji' => '😂']);
    }
}
