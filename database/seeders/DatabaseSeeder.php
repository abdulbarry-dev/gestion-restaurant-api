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
        // Create a default admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@restaurant.com',
            'password' => bcrypt('password123'),
        ]);

        // Seed restaurant data
        $this->call([
            CustomerSeeder::class,
            TableSeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
