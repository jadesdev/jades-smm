<?php

namespace Database\Seeders;

use App\Models\Admin;
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

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // seed super admin
        Admin::updateOrCreate(
            [
                'email' => 'jayflashdev@gmail.com',
            ],
            [
                'name' => 'Jay Flash',
                'password' => Hash::make('Password123'),
                'phone' => '08035852702',
                'is_active' => true,
                'type' => 'super',
            ]
        );
    }
}
