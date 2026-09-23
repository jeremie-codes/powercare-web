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
        // User::factory(10)->create();

        User::create([
            'name' => 'Test client',
            'phone' => '0827289636',
            'email' => 'client@gmail.com',
            'password'=> Hash::make('password'),
            'role' => 'client',
        ]);

        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@gmail.com',
            'password'=> Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->call([
            ServiceSeeder::class,
            AgentSeeder::class,
        ]);
    }
}
