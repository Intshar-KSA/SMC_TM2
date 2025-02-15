<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'email' => 'super@demo.com',
            'type' => 'super admin',
        ]);

        User::factory()->create([
            'email' => 'admin@demo.com',
            'type' => 'admin',
        ]);

        User::factory()->create([
            'email' => 'admin1@demo.com',
        ]);
        User::factory()->create([
            'email' => 'admin2@demo.com',
        ]);
    }
}
