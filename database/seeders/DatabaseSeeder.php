<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => 'password', // update in production via environment variables or seeder
        ]);

        User::factory()->create([
            'name' => 'Analyst',
            'email' => 'analyst@example.com',
            'role' => 'analyst',
            'password' => 'password',
        ]);
    }
}
