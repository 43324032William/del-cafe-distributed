<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::factory()->create([
            'name' => 'Admin Del Cafe',
            'email' => 'admin@delcafe.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create customer user
        User::factory()->create([
            'name' => 'Customer Example',
            'email' => 'customer@example.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
        ]);

        // Create sample menus
        $this->call(MenuSeeder::class);
    }
}