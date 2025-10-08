<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Application; // <-- ADD THIS IMPORT
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 20 dummy applications with random statuses (paid, pending, failed)
        Application::factory(20)->create();

        // Optional: Keep the default User factory or your registered user for login
        // If you used the registration form, you can comment this out or adjust it.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
