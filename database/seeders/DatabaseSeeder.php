<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\ClientRequests;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ClientRequests::factory(10)->create();

       User::factory()->create([
            'name' => 'Admin',
            'email' => 'test@example.com',
            'password' => 'Admin'
        ]);
    }
}
